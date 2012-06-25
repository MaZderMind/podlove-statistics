<?php

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

?>