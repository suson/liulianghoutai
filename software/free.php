<?php
/*
 * 文件:free.php
 * 更新网址分享
 * 输入参数：urlid,url
 */
require_once '../dbcfg.php';
	echo free();
function free()
{
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	if(isset($_GET['u']))
	{
		$link=@mysql_connect($HOST,$USER,$PWD);
		@mysql_select_db($DATABASE);
		if(is_numeric($_GET['u']))
		{
			//以urlid的方式更新free
			$u=$_GET['u'];
			$UpDateFree="UPDATE Url SET free=0 WHERE urlid=$u;";
			@mysql_query($UpDateFree,$link);
			//return "1";
		}
		else
		{
			//以url形式更新free
			$u=$_GET['u'];
			$url=$u;
			$UpDateFree="UPDATE Url SET free=0 WHERE url='$url';";
			@mysql_query($UpDateFree,$link);
			//return "1";
		}
	}
	else
	{
		return "0";
	}
}
?>