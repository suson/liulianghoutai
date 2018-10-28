<?php
/*
 * 往数据表  filter 添加数据 
 * 传入参数:url reason
 * 传入方式：POST
 */

require_once "./securityCheck.php";
require_once '../dbcfg.php';
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	

	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$database=DATABASE;
	$table=TB_FILTER;
	
	$url=$_POST['url'];
	$reason=$_POST['reason'];
	
	if(0!=preg_match('/\//',$url)){
		exit('{"success":false,"msg":"网址中不能含有/"}');
	}
	
	$qI="INSERT INTO $table (url,reason) VALUES('$url','$reason');";
	$link=@mysql_connect($host,$user,$pwd);
	@mysql_select_db($database);
	$result=@mysql_query($qI,$link);
	if($result){
		$response=array("success"=>true,"msg"=>"网址: ".$url."<br/ >原因: ".$reason);
	}else{
		$error=@mysql_error($link);
		$response=array("success"=>false,"msg"=>$error);
	}
	echo json_encode($response);
?>