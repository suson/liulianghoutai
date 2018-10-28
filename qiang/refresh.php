<?php
	/*
	 * 功能：刷新SP_url表，
	 * 		将所有URL 设置online=0
	 * 		将开通代挂服务的URL 设置 online=1
	 * 		将所有网址的今日统计设为0
	 * 		更新用户开通的服务剩余时间
	 * 		将表url_ip清空
	 */

require_once "./securityCheck.php";
require_once '../dbcfg.php';
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	
	$qOff="UPDATE url SET online=0,tdclick=0 ;";
	$qOn="UPDATE url SET online=1 WHERE EXISTS(SELECT svcid FROM url_odrs WHERE url.urlid=urlid AND svcid <> 10 AND status <> 0) ;";
	/*
	 * 更新开通 代挂服务的网址的状态，指他们的代挂时间有没有到
	 * UPDATE url_odrs SET sday=DATEDIFF(etime,now())*24*60,status=IF(sday <> 0,status,0)
	 * 
	 */
	$qUpdateUrlodrs="UPDATE url_odrs SET sday=DATEDIFF(etime,now())*24*60,  status=IF(sday <> 0,status,0);";
	$qEmptyUrlidIP="DELETE FROM url_ip;";
	$link=@mysql_connect($HOST,$USER,$PWD) or die('{"success":false,"msg":"连接数据库失败"}');
	$error=array();
	if(@mysql_select_db($DATABASE)){
		@mysql_query($qOff,$link);
		$error[]=array("code"=>mysql_errno(),"msg"=>mysql_error());
		@mysql_query($qEmptyUrlidIP,$link);
		$error[]=array("code"=>mysql_errno(),"msg"=>mysql_error());
		@mysql_query($qUpdateUrlodrs,$link);
		$error[]=array("code"=>mysql_errno(),"msg"=>mysql_error());
		@mysql_query($qOn,$link);
		$error[]=array("code"=>mysql_errno(),"msg"=>mysql_error());
		if(!$error[0]['code'] && !$error[1]['code'] && !$error[2]['code']){
			echo '{"success":true,"msg":"刷新数据库成功！"}';
		}else{
			echo json_encode(array("sucess"=>false,"msg"=>$error));
			
		}
	}else{
		die('{"success":false,"msg":"<font color=red>'.mysql_errno()."</font>:<br />".mysql_error().'"}');
	}
?>