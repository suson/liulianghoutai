<?php
/*
 * 修改Admin密码
 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE=TB_USER;
		
	if(isset($_POST['oldpwd']) && isset($_POST['newpwd1'])){
		$newpwd=md5($_POST['newpwd1']);
		$oldpwd=md5($_POST['oldpwd']);
		$link=mysql_connect($HOST,$USER,$PWD);
		mysql_select_db($DATABASE,$link);
		
		$qSelect="SELECT name FROM $TABLE WHERE name='admin' AND psw='$oldpwd';";
		$result=mysql_query($qSelect,$link);
		if(0== mysql_num_rows($result)){
			die('{"success":false,"msg":"密码错误"}');
		}
		$qUpdate="UPDATE $TABLE SET psw='$newpwd' WHERE name='admin' AND psw='$oldpwd';";
		$bSuc=mysql_query($qUpdate,$link);
		if($bSuc){
			echo '{"success":true,"msg":"密码修改成功！"}';
		}else{
			echo '{"success":true,"msg":"密码修改失败！"}';
		}
	}else{
		echo '{"success":false,"msg":"请从客户端登入"}';
	}
?>