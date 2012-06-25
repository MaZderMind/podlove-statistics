<?php

require_once 'lib/PhpTemplate.php';
require_once 'lib/DBCon.php';

$tpl = new PhpTemplate('tpl/index.php');
echo $tpl->render(array(
	'l18n' => json_decode(file_get_contents('l18n/de.json'))
));

?>