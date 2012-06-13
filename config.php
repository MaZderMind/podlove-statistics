<?php

// DSN used to connect to the database
$config['db']['main'] = array(
	'dsn' => 'sqlite:stats.sqlite3',
	'username' => null,
	'password' => null,
);

// DSN used to connect to the database
$config['linelen'] = 1024;

// analyze user names
$config['processuser'] = false;

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