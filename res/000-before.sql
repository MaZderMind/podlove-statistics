CREATE TABLE status (
	k TEXT,
	v TEXT
);

CREATE TABLE files (
	id INTEGER,
	episode TEXT,
	format TEXT,
	sz INTEGER
);

CREATE TABLE agents (
	id INTEGER,
	os TEXT,
	app TEXT
);

CREATE TABLE usernames (
	id INTEGER,
	name TEXT
);

CREATE TABLE stats (
	file_id INTEGER,
	norm_stamp INTEGER,
	agent INTEGER,
	username INTEGER,
	szsum INTEGER
);

CREATE TABLE errors (
	url TEXT,
	code INTEGER,
	cnt INTEGER
);
