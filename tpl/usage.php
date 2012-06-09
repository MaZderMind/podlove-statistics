Logfile-Processor of the Podcast Log-Analyzer

Usage:
  Fitst Import: php <?=$exe?> --import access.log access.log.1 access.log.2
  Updates:      php <?=$exe?> access.log access.log.1

<? if(!$verbose): ?>
To get more information about possible config options call with --help.
<? else: ?>
All other settings can be configured in files named config.php which are
searched for at the following places. All files are included in the
following order:

 - ./config.php
 - /opt/etc/podlove-statistics/config.php
 - /usr/local/etc/podlove-statistics/config.php
 - /etc/podlove-statistics/config.php
 
The following settings can be configured:
 - more
 - coming
 - soon
<? endif ?>

