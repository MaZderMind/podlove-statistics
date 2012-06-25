<?php

require_once 'lib/PhpTemplate.php';
require_once 'lib/DBCon.php';
require_once 'lib/ConfigReader.php';

// an error happened
function exitWithError($title, $msg)
{
	$tpl = new PhpTemplate('tpl/error.php');
	header('HTTP/1.0 500 Internal Server Error');
	echo $tpl->render(array(
		'title' => $title,
		'msg' => $msg,
	));
	exit;
}

// return a chunk of json
function exitWithJson($data = null)
{
	header('Content-Type: application/json');
	$json = json_encode($data);
	if($json) json_encode(null);
	echo $json;
	exit;
}

// check if all required extensions are loaded
if(!extension_loaded('sqlite3') && !extension_loaded('pdo_sqlite'))
{
	exitWithError('SQLite-Extension is not installed or not loaded', 'The PHP-Extension that enables Access to SQLite-Databases is not installed. Depending on your system it may be available in your system\'s package manager as php5-sqlite or php5-sqlite3.');
}

// transfer the database settings to the mighty DBCon class
DBCon::setConfig($config['db']);

switch($_GET['get'])
{
	case 'metrics':
		exitWithJson(array(
			'files' => db()->queryAll('
				SELECT
					DISTINCT stats.file AS id,
					files.episode AS e,
					files.format AS f
				FROM
					stats
				JOIN
					files
				ON
					files.id = stats.file
				ORDER BY
					stats.norm_stamp
			'),

			'foo' => 'bar',
		));
	break;
	default:
		$tpl = new PhpTemplate('tpl/index.php');
		echo $tpl->render(array(
			'l18n' => json_decode(file_get_contents('l18n/de.json')),
		));
	break;
}
?>