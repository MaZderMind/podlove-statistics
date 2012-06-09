<?php

require_once 'lib/PhpTemplate.php';

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

// conenct to the database
$db = new PDO($config['db']);

// read the last status information
$stm = $db->query('SELECT k, v FROM status', PDO::FETCH_ASSOC);
$status = array();

// check for a fresh database
if($stm)
{
	foreach($stm as $row)
	{
		$status[$row['k']] = $row['v'];
	}
}
else
{
	// yup, that database is maiden
	// create the status table and 
	echo "initiating empty database...\n";
	$sql = file_get_contents('res/scheme.sql');
	$db->exec($sql);
}


// give some hope while we're busy with the files
echo "scanning ".count($files)." file(s)\n";

foreach($files as $file)
{
	$fp = @fopen($file, 'r');
	if(!$fp)
	{
		error(2, 'unable to open file: '.$file);
	}

	
}
