CREATE TABLE status (
	k TEXT,
	v TEXT
);

CREATE TABLE files (
	id INTEGER PRIMARY KEY ASC,
	episode TEXT,
	format TEXT,
	sz INTEGER
);

CREATE TABLE agents (
	id INTEGER PRIMARY KEY ASC,
	os TEXT,
	app TEXT
);

CREATE TABLE users (
	id INTEGER PRIMARY KEY ASC,
	name TEXT
);

CREATE TABLE stats (
	file INTEGER,
	norm_stamp INTEGER,
	agent INTEGER,
	user INTEGER,
	szsum INTEGER
);

CREATE TABLE errors (
	url TEXT,
	code INTEGER,
	cnt INTEGER
);
