<?php
//更新积分
session_start();
$userid=$_SESSION['userid'];
require_once '../dbcfg.php';
$HOST=HOST;
$USER=USER;
$PWD=PWD;
$DATABASE=DATABASE;

$link=mysql_connect($HOST,$USER,$PWD);
mysql_select_db($DATABASE,$link);

if(isset($_GET['score']))
{
	$score=$_GET['score'];
	$qs="UPDATE account SET score=score+$score WHERE userid=$userid;";
	@mysql_query($qs,$link);
	//echo $score."|".$userid;	
}
//else
//{
//	echo "2";
//}
?>