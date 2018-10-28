<?php
/*
 * 文件：writeflowdata.php
 * 实现软件端URL 登入与数据库接口
 * 主要功能是向Url表中的写入流量控制数据
 * 参数名：fd,u ,fd为流量控制数，u是urlid
 * 返回 值:套餐ip总数，流量时段数据
 */

require_once '../dbcfg.php';
	echo  writeflowdata();

function writeflowdata()
{
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;

	if(isset($_GET['u']) && isset($_GET['fd']))
	{	
		$link=mysql_connect($HOST,$USER,$PWD);
		mysql_select_db($DATABASE);
		$u=$_GET['u'];
		$flowdata=$_GET['fd'];
		$updatefd="update url set flowcrl='$flowdata' where urlid='$u';";
		@mysql_query($updatefd,$link);
	}
}
?>