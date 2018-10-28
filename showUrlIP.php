<?php
	require_once("./dbcfg.php");
	header("Content-Type:text/html;charset=utf-8");
	$link=mysql_connect(HOST,USER,PWD);
	mysql_select_db(DBNAME);
	$qUrlIP="SELECT urlid,ip FROM url_ip;";
	$cnt="<div><table border=1><tbody><tr><th>网址编号</th><th>IP</th></tr>";
	$result=mysql_query($qUrlIP,$link);
	while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
		$cnt .= "<tr><td>".$row['urlid']."</td><td>".$row['ip']."</td></tr>";
	}
	$cnt.="</tbody></table></div>";
	echo $cnt;
	
?>