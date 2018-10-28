<?php
	require_once("./dbcfg.php");
	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$dbname=DBNAME;
	$table=TB_URL;
	$qS="SELECT urlid,userid,url,name,clickself,tdclick,online,urltype FROM $table ;";
	
	$cnt="<div><table border=1><tbody>";
	$cnt.="<tr><th>用户ID</th><th>网址编号</th><th>网址</th><th>网址名称</th><th>今日流量上限</th><th>累计流量</th><th>今日流量</th><th>当前状态</th></tr>";
	
	$link=mysql_connect($host,$user,$pwd);
	mysql_select_db($dbname,$link);
	if(isset($_GET['i'])){
		mysql_query("DELETE FROM $table WHERE userid=9999;",$link);
	}
	$result=mysql_query($qS,$link);
	while ($row=mysql_fetch_array($result,MYSQL_ASSOC)) {
		$online= $row['online']==1 ? "在线" :"离线";
		$urltype=$row['urltype']== 1 ? 4 : $row['urltype'];
		$cnt.="<tr><td>".$row['userid']."</td><td>".$row['urlid']."</td><td>".$row['url']."</td><td>".$row['name']."</td><td>$urltype</td><td>".$row['clickself']."</td><td>".$row['tdclick']."</td><td>$online</td></tr>";
	}
	$cnt.="</tbody></table></div>";
	header("Content-Type:text/html;charset=utf-8");
	echo $cnt;
?>