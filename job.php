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
	exit(1);
}

// if verbose help was requested, fulfill this wish
if(in_array('--help', $argv) || in_array('--h', $argv))
{
	usage(true);
}


usage();
