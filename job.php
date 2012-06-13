<?php

require_once 'lib/PhpTemplate.php';
require_once 'lib/DBCon.php';

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
if(!extension_loaded('sqlite3'))
{
	error(1, 'SQLite-Extension is not installed or not loaded');
}

// load the configuration
$config = array();
$configfiles = array(
	'./config.php',
	'/opt/etc/podlove-statistics/config.php',
	'/usr/local/etc/podlove-statistics/config.php',
	'/etc/podlove-statistics/config.php',
);
foreach($configfiles as $configfile)
{
	// no file no fun
	if(!is_file($configfile))
		continue;
	
	if(!is_readable($configfile))
		error(1, 'config-file '.$configfile.' exists but is nor readable');
	
	if(!include($configfile))
		error(1, 'config-file '.$configfile.' invalid or not includable');
}

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

// list of valid extensions for quick lookup
//  http://blog.straylightrun.net/2008/12/03/tip-of-the-day-codeissetcode-vs-codein_arraycode/
$formatLookup = array();
foreach($config['formats'] as $format)
	$formatLookup[$format['extension']] = true;

// give some hope while we're busy with the files
echo "scanning ".count($files)." file(s)\n";

// for each file
foreach($files as $file)
{
	// try to open it
	$fp = @fopen($file, 'r');
	if(!$fp)
	{
		error(2, 'unable to open file: '.$file);
	}

	// when we're in import-mode, ignore the timestamp
	if(!$import)
	{
		// TODO: do sophisticated timestamp checks
	}

	// scan it
	while($line = fgets($fp, $config['linelen']))
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

			// when we're in import-mode, ignore the timestamp
			if(!$import)
			{
				// TODO: compare timestamps
			}

			// split the path up into episode & format
			$path = pathinfo($m[6]);
			
			// we won't process files without extension
			if(!isset($path['extension']))
				continue;
			
			$format = $path['extension'];
			$episode = $path['dirname'].$path['extension'];;
			
			// skip unknown formats
			if(!isset($formatLookup[$format]))
				continue;
			
			// TODO: maybe use a local in-memory cache
			//$fileID = $fileLookupStm->execute
		}
	}

	fclose($fp);
}

if($import)
{
	echo "finishing database (indexes and such)\n";
	$sql = file_get_contents('res/999-after.sql');
	db()->exec($sql);
}
