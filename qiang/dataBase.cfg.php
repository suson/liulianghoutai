<?php
/*
* Define  Create table SQL statment
* Date: 11/03/2011
*/
	$table=array(
		'user'=>'CREATE TABLE Jackin_users (userid INT UNSIGNED AUTO_INCREMENT UNIQUE ,name CHAR(16) NOT NULL UNIQUE ,pwd CHAR(32) ,otime datetime ,ltime DATETIME,rtime DATETIME,email CHAR(128) NOT NULL,question CHAR(128) NOT NULL,answer CHAR(128) NOT NULL,PRIMARY KEY (userid))engine=innodb AUTO_INCREMENT=10000 DEFAULT CHARACTER SET utf8;',
		'account'=>'CREATE TABLE Jackin_account(accountid INT UNSIGNED AUTO_INCREMENT UNIQUE ,userid INT UNSIGNED,money INT UNSIGNED  DEFAULT 0,pmoney INT UNSIGNED  DEFAULT 0,lmoney INT UNSIGNED  DEFAULT 0,score INT UNSIGNED DEFAULT 50,level INT UNSIGNED DEFAULT 0,PRIMARY KEY (accountid),FOREIGN KEY (userid) REFERENCES Jackin_users (userid))engine=innodb AUTO_INCREMENT=10000 DEFAULT CHARACTER SET utf8;',
		'filter'=>'CREATE TABLE Jackin_filter(id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,url VARCHAR(250) NOT NULL,reason CHAR(128)) DEFAULT CHARACTER SET utf8;',
		'flow'=>'CREATE TABLE Jackin_flow(cid INT UNSIGNED ,hrs CHAR(128))engine=innodb DEFAULT CHARACTER SET utf8;',
		'alipayTrade'=>'CREATE TABLE Jackin_alipay_trade(out_trade_no CHAR(32) NOT NULL PRIMARY KEY,subject CHAR(128) NOT NULL,body CHAR(254),total_fee INT UNSIGNED NOT NULL,trade_status INT UNSIGNED,trade_no CHAR(64) NOT NULL,buyer_email CHAR(64) NOT NULL,other CHAR(32))DEFAULT CHARACTER SET utf8;',
		'url'=>'CREATE TABLE Jackin_url(urlid  INT UNSIGNED AUTO_INCREMENT,userid INT UNSIGNED,url  VARCHAR(250) NOT NULL,name CHAR(30) NOT NULL,urltype SMALLINT NOT NULL DEFAULT 1,furls TEXT,usefurl SMALLINT DEFAULT 0,turl VARCHAR(250) DEFAULT "",useturl SMALLINT DEFAULT 0,usepop SMALLINT DEFAULT 0,rtime DATETIME,ltime DATETIME,free SMALLINT DEFAULT 1,clickother INT UNSIGNED DEFAULT 0,clickself INT UNSIGNED DEFAULT 0,tdclick INT UNSIGNED DEFAULT 0,online  SMALLINT DEFAULT 0,PRIMARY KEY (urlid),FOREIGN KEY (userid) REFERENCES Jackin_users(userid))engine=innodb AUTO_INCREMENT=10000 DEFAULT CHARACTER SET utf8;',
		'order'=>'CREATE TABLE Jackin_orders(orderid INT UNSIGNED AUTO_INCREMENT primary key,userid INT UNSIGNED,urlid INT UNSIGNED,val INT UNSIGNED NOT NULL ,otype INT,otime DATETIME,svcid INT,pm INT default 0,lm INT default 0,bae INT,FOREIGN KEY (userid) REFERENCES Jackin_users (userid))engine=innodb AUTO_INCREMENT=10000 DEFAULT CHARACTER SET utf8;',
		'urlOdrs'=>'CREATE TABLE Jackin_url_odrs( odrid INT UNSIGNED,urlid INT UNSIGNED,status SMALLINT NOT NULL,sday INT unsigned NOT NULL,svcid SMALLINT NOT NULL,dayprice INT,btime DATETIME,etime DATETIME,FOREIGN KEY (urlid) REFERENCES Jackin_url(urlid),FOREIGN KEY (odrid) REFERENCES Jackin_orders(orderid))engine=innodb DEFAULT CHARACTER SET utf8;',
		'url_ip'=>'CREATE TABLE Jackin_url_ip(urlid INT UNSIGNED NOT NULL, ip CHAR(16))DEFAULT CHARACTER SET utf8;',
		'session'=>'CREATE TABLE Jackin_sessions(id CHAR(32) NOT NULL, data TEXT, last_accessed TIMESTAMP NOT NULL, PRIMARY KEY (id))DEFAULT CHARACTER SET UTF8;'
	);
	$dbcn = new mysqli('localhost','root','','ljf664');
	if($dbcn->connect_error){
		die("链接数据库失败！".$dbcn->connect_errno);
	}
	foreach($table as $key=>$value){
		if($dbcn->query($value) == TRUE){
			echo '创建表'.$key.'成功<br />';
		}else{
			echo '创建表'.$key.'失败!代码：<font color="red">'.$dbcn->error.'</font><br />';
		}
	}
	$dbcn->close();
?>