CREATE TABLE Jackin_users (
	userid INT UNSIGNED AUTO_INCREMENT UNIQUE ,
	name CHAR(16) NOT NULL UNIQUE ,
	pwd CHAR(32),
	ltime DATETIME,
	rtime DATETIME,
	email CHAR(128) NOT NULL,
	question CHAR(128) NOT NULL,
	answer CHAR(128) NOT NULL,
	PRIMARY KEY (userid)
)engine=innodb AUTO_INCREMENT=10000 DEFAULT CHARACTER SET utf8;

CREATE TABLE Jackin_account(
	userid INT UNSIGNED PRIMARY KEY,
	money INT UNSIGNED  DEFAULT 0,
	pmoney INT UNSIGNED  DEFAULT 0,
	lmoney INT UNSIGNED  DEFAULT 0,
	score INT UNSIGNED DEFAULT 50,
	level INT UNSIGNED DEFAULT 0,
	FOREIGN KEY (userid) REFERENCES users (userid)
)engine=innodb DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS Jackin_sys_var(
	vname CHAR(32) NOT NULL PRIMARY KEY,
	vvalue CHAR(32),
	vdefault CHAR(32),
	comment TINYTEXT
)DEFAULT CHARACTER SET utf8;

