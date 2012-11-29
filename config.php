<?php

// DSN used to connect to the database
$config['db']['main'] = array(
	'dsn' => 'sqlite:var/stats.sqlite3',
	'username' => null,
	'password' => null,
);

// Base-URL of the media-files in the logs
//  The server is queried for the filesizes of the media-files
//  in order to calculate the number of complete downloads
$config['base'] = 'http://localhost/podlove-statistics/test-data/files/';

// Sometimes you will rename your files, especially in the beginning time
//  If you did so, you can specify a mapping file here, which is essentially
//  a tab-seperated file with the old *episode name* in the first and the 
//  new *episode name* in the second column.
//
//  So if your file was originally named "XXX00-my-title.m4a" and now you're
//  using "XXX000.m4a" as your filename, the mapping-line should read
//     "XXX00-my-title		XXX000"
$config['mapping'] = 'test-data/mapping.tsv';

// DSN used to connect to the database
$config['linelen'] = 1024;

// normalize all timestamps using this interval in seconds
//  a value of 5 minutes instructs the processor to interpret
//  all requests that happened in an interval of 5 minutes
//  like they happened in the same moment.
//  setting a higher value results in less granular but
//  smaller and faster database.
$config['timeinterval'] = 15*60; // 15 minutes

// podcast file formatsm (copied from podlove ;)
$config['formats'] = array(
	array( 'name' => 'MP3 Audio',              'type' => 'audio', 'mime_type' => 'audio/mpeg',  'extension' => 'mp3' ),
	array( 'name' => 'BitTorrent (MP3 Audio)', 'type' => 'audio', 'mime_type' => 'application/x-bittorrent',  'extension' => 'mp3.torrent' ),
	array( 'name' => 'MPEG-1 Video',           'type' => 'video', 'mime_type' => 'video/mpeg',  'extension' => 'mpg' ),
	array( 'name' => 'MPEG-4 Audio',           'type' => 'audio', 'mime_type' => 'audio/mp4',   'extension' => 'm4a' ),
	array( 'name' => 'MPEG-4 Video',           'type' => 'video', 'mime_type' => 'video/mp4',   'extension' => 'm4v' ),
	array( 'name' => 'MPEG-4 Video',           'type' => 'video', 'mime_type' => 'video/mp4',   'extension' => 'mp4' ),
	array( 'name' => 'Ogg Vorbis Audio',       'type' => 'audio', 'mime_type' => 'audio/ogg',   'extension' => 'oga' ),
	array( 'name' => 'Ogg Theora Video',       'type' => 'video', 'mime_type' => 'video/ogg',   'extension' => 'ogv' ),
	array( 'name' => 'Ogg Vorbis Audio',       'type' => 'audio', 'mime_type' => 'audio/ogg',   'extension' => 'ogg' ),
	array( 'name' => 'WebM Audio',             'type' => 'audio', 'mime_type' => 'audio/webm',  'extension' => 'webm' ),
	array( 'name' => 'WebM Video',             'type' => 'video', 'mime_type' => 'video/webm',  'extension' => 'webm' ),
	array( 'name' => 'Matroska Audio',         'type' => 'audio', 'mime_type' => 'audio/x-matroska',  'extension' => 'mka' ),
	array( 'name' => 'Matroska Video',         'type' => 'video', 'mime_type' => 'video/x-matroska',  'extension' => 'mkv' ),
);

?>