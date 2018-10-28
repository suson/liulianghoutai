<?php
/*
 * 文件:blacklist.php
 * 取黑名单数据
 * 输入参数：url
 */
require_once '../dbcfg.php';
	echo blacklist();
function blacklist()
{
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	if(isset($_GET['url']))
	{
		$url=$_GET['url'];
		$bL="SELECT url FROM filter WHERE url='$url';";

		$link=@mysql_connect($HOST,$USER,$PWD);
		@mysql_select_db($DATABASE);
		$result=@mysql_query($bL,$link);
		if(0!=@mysql_num_rows($result))
		{
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$bLUrl=$row['url'];
			//if(isset($bLUrl) || $bLUrl=="")
			if(empty($bLUrl) || $bLUrl=="")
			{
				//return "0";
				//return $bLUrl;
				echo "A";
			}
			else
			{
				return "1";
			}
		}
		else
		{
			//return "0";
			echo "B";
		}
	}
	else
	{
		//return "0";
		echo "C";
	}
}
?>