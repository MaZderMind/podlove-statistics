<?php

require_once 'lib/PhpTemplate.php';
require_once 'lib/DBCon.php';
require_once 'lib/ConfigReader.php';

// return a chunk of json
function exitWithJson($data = null)
{
	// dend a correct mime-type
	header('Content-Type: application/json');
	
	// encode the data
	$json = json_encode($data, JSON_PRETTY_PRINT);

	// send a http-error if we fail
	if(!$json)
		header("HTTP/1.0 500 JOSN-Encoding failed");

	// send the data otherwise
	else
		echo $json;

	// we're done.
	exit;
}

// check for some important php-settings
if(!ini_get('short_open_tag'))
	die('The php.ini Directive short_open_tag needs to be set to "On" for the Templating-System to work.');

if(!ini_get('date.timezone'))
	die('The php.ini Directive date.timezone needs to be set for the date and time calculations to work properly.');

// check if all required extensions are loaded
if(!extension_loaded('sqlite3') && !extension_loaded('pdo_sqlite'))
	die('The PHP-Extension that enables Access to SQLite-Databases is not installed. Depending on your system it may be available in your system\'s package manager as php5-sqlite or php5-sqlite3.');

// transfer the database settings to the mighty DBCon class
DBCon::setConfig($config['db']);

// check for the availability of the database and fail early id it can't be accessed
if(!db())
	die('The Database that should contain your Statistics is not available. This may be because it doesn\'t exist on the filesystem, because it\'s currupt or locked.');

// date range limits
$from = isset($_GET['from']) && is_numeric($_GET['from']) ? intval($_GET['from']) : 0;
$to   = isset($_GET['to'])   && is_numeric($_GET['to'])   ? intval($_GET['to'])   : time()+1;
$get  = isset($_REQUEST['get']) ? $_REQUEST['get'] : '';

switch($get)
{
	case 'metrics':
		exitWithJson(array(
			'Episode' => db()->queryCol('
				SELECT files.episode
				FROM stats
				JOIN files ON files.id = stats.file
				WHERE stats.norm_stamp BETWEEN ? AND ?
				GROUP BY files.episode
				ORDER BY COUNT(*) DESC
			', array($from, $to)),

			'Format' => db()->queryCol('
				SELECT files.format
				FROM stats
				JOIN files ON files.id = stats.file
				WHERE stats.norm_stamp BETWEEN ? AND ?
				GROUP BY files.format
				ORDER BY COUNT(*) DESC
			', array($from, $to)),

			'App' => db()->queryCol('
				SELECT agents.app
				FROM stats
				JOIN agents ON agents.id = stats.agent
				WHERE stats.norm_stamp BETWEEN ? AND ?
				GROUP BY agents.app
				ORDER BY COUNT(*) DESC
			', array($from, $to)),

			'OS' => db()->queryCol('
				SELECT agents.os
				FROM stats
				JOIN agents
				ON agents.id = stats.agent
				WHERE stats.norm_stamp BETWEEN ? AND ?
				GROUP BY agents.os
				ORDER BY COUNT(*) DESC
			', array($from, $to)),
		));
	break;
	case 'downloads':
		$group = isset($_REQUEST['group']) ? max(1, intval($_REQUEST['group'])) : 1;
		exitWithJson(db()->queryAll('
			SELECT
				MIN(stats.norm_stamp) AS date,
				datetime(MIN(stats.norm_stamp), "unixepoch") AS hdate,
				SUM( CAST(stats.szsum AS REAL) ) AS szsum,
				SUM( CAST(stats.szsum AS REAL) / CAST(files.sz AS REAL) ) AS num
			FROM stats
			JOIN files ON files.id = stats.file
			WHERE stats.norm_stamp BETWEEN ? AND ?
			GROUP BY stats.norm_stamp / ?
		', array($from, $to, $group)));
	break;
	default:
		$l18n = json_decode(file_get_contents('l18n/de.json'));
		if(!$l18n)
			die('There seems to be an error in your Language-File.');
		
		$tpl = new PhpTemplate('tpl/index.php');
		echo $tpl->render(array(
			'l18n' => $l18n,
		));
	break;
}
?>