<?php

// ignore requests that don't originate from CLI
if(php_sapi_name() != 'cli')
	die('not runnable from !cli');

require_once 'lib/PhpTemplate.php';
require_once 'lib/DBCon.php';
require_once 'lib/PodcatchIdentify.php';

// print more or less verbode usage instructions
// on error or if requestd via command line.
function usage($verbose = false)
{
	global $argv;

	$tpl = new PhpTemplate('tpl/usage.php');
	echo $tpl->render(array(
		'exe' => $argv[0],
		'verbose' => $verbose,
	));
}

// an error happened
function error($ret, $msg)
{
	echo "Error: $msg\n\n";
	exit($ret);
}

// if verbose help was requested, fulfill this wish
if(in_array('--help', $argv) || in_array('--h', $argv))
{
	usage(true);
	exit(0);
}

// check if all required extensions are loaded
if(!extension_loaded('sqlite3') && !extension_loaded('pdo_sqlite'))
{
	error(1, 'SQLite-Extension is not installed or not loaded');
}

// read all relevant config files
require 'lib/ConfigReader.php';

// files to parse
$files = array();

// check the supplied the arguments
foreach($argv as $n => $arg)
{
	// ignore first argument
	if($n == 0)
		continue;

	// ignore parameter
	if(substr($arg, 0, 2) == '--')
		continue;

	$files[] = $arg;
}

// check for invalid input
if(count($files) == 0)
{
	usage();
	exit(1);
}

// last status
$status = array();

// transfer the database settings to the mighty DBCon class
DBCon::setConfig($config['db']);

// read the last status information
$import = false;
try {
	$status = db()->queryAssoc('SELECT k, v FROM status');
}

// check for a fresh database
catch (PDOException $e)
{
	// yup, that database is maiden
	// create the status table and
	echo "initiating empty database\n";
	$sql = file_get_contents('res/000-before.sql');
	db()->exec($sql);

	echo "running in import-mode\n";
	$import = true;
	$status = array();
}

// prepare statements
$fileLookupStm  = db()->prepare('SELECT id FROM files WHERE episode = ? AND format = ?');
$fileInsertStm  = db()->prepare('INSERT INTO files (episode, format) VALUES (?, ?)');
$agentLookupStm = db()->prepare('SELECT id FROM agents WHERE app = ? AND os = ?');
$agentInsertStm = db()->prepare('INSERT INTO agents (app, os) VALUES (?, ?)');
$userLookupStm  = db()->prepare('SELECT id FROM users WHERE name = ?');
$userInsertStm  = db()->prepare('INSERT INTO users (name) VALUES (?)');
$countHitStm    = db()->prepare('UPDATE stats SET szsum = szsum + :sz WHERE file = :file AND norm_stamp = :norm_stamp AND agent = :agent AND user = :user');
$countNewHitStm = db()->prepare('INSERT INTO stats (file, norm_stamp, agent, user, szsum) VALUES (:file, :norm_stamp, :agent, :user, :sz)');

// list of valid extensions for quick lookup
//  http://blog.straylightrun.net/2008/12/03/tip-of-the-day-codeissetcode-vs-codein_arraycode/
$formatLookup = array();
foreach($config['formats'] as $format)
	$formatLookup[$format['extension']] = true;

// give some hope while we're busy with the files
echo "scanning ".count($files)." file(s)\n";

// start transaction
db()->query('BEGIN');

// simple in-memory cache
$mcache = array();

// for each file
foreach($files as $file)
{
	// try to open it
	$fp = @gzopen($file, 'r');
	if(!$fp)
	{
		error(2, 'unable to open file: '.$file);
	}

	echo "scanning ".$file."\n";
	$lines = 0;

	// when we're in import-mode, ignore the timestamp
	if(!$import)
	{
		// TODO: do sophisticated timestamp checks
	}

	// scan it
	while($line = gzgets($fp, $config['linelen']))
	{
		// parse it
		if(preg_match('@^([^ ]+) ([^ ]+) ([^ ]+) \[([^\]]+)\] "([^ ]+) ([^ ]+) ([^ ]+)" ([^ ]+) ([^ ]+)(?: "([^"]+)" "([^"]+)")?@', $line, $m))
		{
			// 0 = line
			// 1 = ip
			// 2 = ident
			// 3 = user
			// 4 = date
			// 5 = http-method
			// 6 = url
			// 7 = http-version
			// 8 = response
			// 9 = size
			// 10 = referer (opt.)
			// 11 = agent (opt.)

			$lines++;

			// parse the time
			$dt = DateTime::createFromFormat('d/M/Y:H:i:s O', $m[4]);
			$time = $dt->getTimestamp();

			// when we're in import-mode, ignore the timestamp
			if(!$import)
			{
				// TODO: compare timestamps
			}

			// requet size
			$size = intval($m[9]);
			if(!$size)
				continue;

			// named variables
			$user = $m[3];
			$agent = @$m[11];

			// split the path up into episode & format
			$path = pathinfo($m[6]);

			// we won't process files without extension
			if(!isset($path['extension']))
				continue;

			$format = $path['extension'];
			$episode = ltrim($path['dirname'], '/').$path['filename'];

			// skip unknown formats
			if(!isset($formatLookup[$format]))
				continue;

			// lookup the file id
			if(isset($mcache['file'][$episode][$format]))
			{
				$fileId = $mcache['file'][$episode][$format];
			}
			else
			{
				$fileId = $fileLookupStm->executeOne(array($episode, $format));
				if(!$fileId)
				{
					// a new file, store it
					// TODO: fetch filesize
					$fileInsertStm->execute(array($episode, $format));
					$fileId = db()->lastInsertId();
				}
				$mcache['file'][$episode][$format] = $fileId;
			}

			// detect podcatcher/browser and os
			list($app, $os) = PodcatchIdentify::identify($agent);

			// lookup the agent id
			if(isset($mcache['agent'][$app][$os]))
			{
				$agentId = $mcache['agent'][$app][$os];
			}
			else
			{
				$agentId = $agentLookupStm->executeOne(array($app, $os));
				if(!$agentId)
				{
					// a new agent, store it
					$agentInsertStm->execute(array($app, $os));
					$agentId = db()->lastInsertId();
				}
				$mcache['agent'][$app][$os] = $agentId;
			}

			// lookup the username id
			if(isset($mcache['user'][$user]))
			{
				$userId = $mcache['user'][$user];
			}
			else
			{
				$userId = $userLookupStm->executeOne(array($user));
				if(!$userId)
				{
					// a new agent, store it
					$userInsertStm->execute(array($user));
					$userId = db()->lastInsertId();
				}
				$mcache['user'][$user] = $userId;
			}

			// normalize the timestamp
			$normtime = $time - ($time % $config['timeinterval']);

			// count a hit
			$countHitStm->execute(array(
				'file' => $fileId,
				'norm_stamp' => $normtime,
				'agent' => $agentId,
				'user' => $userId,
				'sz' => $size,
			));
			if($countHitStm->rowCount() == 0)
			{
				$countNewHitStm->execute(array(
					'file' => $fileId,
					'norm_stamp' => $normtime,
					'agent' => $agentId,
					'user' => $userId,
					'sz' => $size,
				));
			}
		}
	}

	echo "  read ".$lines." lines\n";

	gzclose($fp);
}

// start transaction
db()->query('END');

// create indexes
if($import)
{
	echo "finishing database (indexes and such)\n";
	$sql = file_get_contents('res/999-after.sql');
	db()->exec($sql);
}
