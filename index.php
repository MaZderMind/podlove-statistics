<?php

require_once 'lib/PhpTemplate.php';
require_once 'lib/DBCon.php';
require_once 'lib/ConfigReader.php';

// return a chunk of json
function exitWithJson($data = null)
{
	header('Content-Type: application/json');
	$json = json_encode($data);
	if($json) json_encode(null);
	echo $json;
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

switch($_GET['get'])
{
	case 'metrics':
		exitWithJson(array(
			'episode' => db()->queryCol('
				SELECT
					files.episode
				FROM
					stats
				JOIN
					files
				ON
					files.id = stats.file
				GROUP BY
					files.episode
				ORDER BY
					MIN(stats.norm_stamp)
			'),

			'format' => db()->queryCol('
				SELECT
					files.format
				FROM
					stats
				JOIN
					files
				ON
					files.id = stats.file
				GROUP BY
					files.format
				ORDER BY
					MIN(stats.norm_stamp)
			'),

			'app' => db()->queryCol('
				SELECT
					agents.app
				FROM
					stats
				JOIN
					agents
				ON
					agents.id = stats.agent
				GROUP BY
					agents.app
				ORDER BY
					MIN(stats.norm_stamp)
				LIMIT 10
			'),

			'os' => db()->queryCol('
				SELECT
					agents.os
				FROM
					stats
				JOIN
					agents
				ON
					agents.id = stats.agent
				GROUP BY
					agents.os
				ORDER BY
					MIN(stats.norm_stamp)
				LIMIT 10
			'),
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