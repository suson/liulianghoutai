<?php
/*
 * 刷新服务IP量
 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';
	require_once "../service/urlcore/cfg/svcipcfg.php";
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":"登入超时...."}');
	
	$response=array("success"=>true,"error"=>array());
	
	$link=mysql_connect(HOST,USER,PWD);
	mysql_select_db(DBNAME,$link);
	//更新 没有开启服务的url的urltype
	$urltype=SVC_IP8;
	$bNotExist="NOT EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid)";
	$qUpdateNotrustee="UPDATE url SET urltype=$urltype WHERE $bNotExist;";
	@mysql_query($qUpdateNotrustee,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());
	
	//更新开通了优化服务的url
	$urltype=SVC_OPTIMIZE;
	$qUdateHasOptimize="UPDATE url SET urltype=$urltype WHERE EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND svcid=10);";
	@mysql_query($qUdateHasOptimize,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());	
	
	//更新开通了代挂服务0 的urltype
	$urltype=SVC_IP101;
	$qUpdateSCVIP101="UPDATE url SET urltype=$urltype WHERE EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND svcid=101);";
	@mysql_query($qUpdateSCVIP101,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());	
	
	//更新开通了代挂服务1 的urltype
	$urltype=SVC_IP103;
	$qUpdateSCVIP103="UPDATE url SET urltype=$urltype WHERE EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND svcid=103);";
	@mysql_query($qUpdateSCVIP103,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());	
	
	//更新开通了代挂服务2的urltype
	$urltype=SVC_IP106;
	$qUpdateSCVIP106="UPDATE url SET urltype=$urltype WHERE EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND svcid=106);";
	@mysql_query($qUpdateSCVIP106,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());	
	
	//更新开通了代挂服务3 的urltype
	$urltype=SVC_IP108;
	$qUpdateSCVIP108="UPDATE url SET urltype=$urltype WHERE EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND svcid=108);";
	@mysql_query($qUpdateSCVIP108,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());	
	
	//更新开通了代挂服务4 的urltype
	$urltype=SVC_IP110;
	$qUpdateSCVIP110="UPDATE url SET urltype=$urltype WHERE EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND svcid=110);";
	@mysql_query($qUpdateSCVIP110,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());	
	
	//更新开通了代挂服务5 的urltype
	$urltype=SVC_IP115;
	$qUpdateSCVIP115="UPDATE url SET urltype=$urltype WHERE EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND svcid=115);";
	@mysql_query($qUpdateSCVIP115,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());	
	
	//更新开通了代挂服务6 的urltype
	$urltype=SVC_IP120;
	$qUpdateSCVIP120="UPDATE url SET urltype=$urltype WHERE EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND svcid=120);";
	@mysql_query($qUpdateSCVIP120,$link);
	$response["error"][]=array("code"=>mysql_errno(),"msg"=>mysql_error());	
	
	echo json_encode($response);
?>