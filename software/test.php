<?php
/*
 * 生成随机网址
 */
	require_once("../dbcfg.php");
	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$dbname=DBNAME;
	$table=TB_URL;
	$str="123456789";
	$com="";
	for($i=0;$i<rand(4,5);$i++){
		$com.=substr($str,rand(0,8),1);
	}
	$url="http://www.$com.com";
	$name=$com;
	$usepop=rand(5,100);
	$urltype=3000;
	$qI="INSERT INTO $table(userid,url,name,urltype,usepop,furls,usefurl,turl,useturl,rtime,ltime,online) VALUES(9999,'$url','$name',$urltype,$usepop,'',0,'',0,now(),now(),1);";
	$link=mysql_connect($host,$user,$pwd);
	mysql_select_db($dbname);
	mysql_query($qI,$link);
	$result=mysql_query("SELECT COUNT(url)  FROM url ;",$link);
	$row=mysql_fetch_array($result,MYSQL_ASSOC);
	$count=$row['COUNT(url)'];
	
	mysql_query("UPDATE admins SET counturl=$count WHERE name='admin';" ,$link);
	echo mysql_error();
?>