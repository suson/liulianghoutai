<?php
/*
 * 文件：getflowdata.php
 * 实现软件端URL 登入与数据库接口
 * 主要功能是取Url表中的流量控制数据
 * 参数名：u
 * 返回 值:套餐ip总数，流量时段数据
 */

require_once '../dbcfg.php';
	echo  getflowdata();

function getflowdata()
{
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;

	if(isset($_GET['u']))
	{	
		$link=mysql_connect($HOST,$USER,$PWD);
		mysql_select_db($DATABASE);
		$u=$_GET['u'];
		$qf=" select urltype,flowcrl from url where urlid='$u';";
		$result=mysql_query($qf,$link);
		if(0 != @mysql_num_rows($result))
		{
			//URLID 存在
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$urltype=$row['urltype'];
			$flowcrl=$row['flowcrl'];
			$rtn=$urltype."(|)".$flowcrl;
			return $rtn;
		}
		else
		{
			//URLID 不存在
			return "0";
		}
	}
}
?>