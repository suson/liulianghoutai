<?php
/*
 * 修改用户信息
 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$database=DATABASE;
	$tb_user=TB_USER;
	
	$link=mysql_connect($host,$user,$pwd);
	mysql_select_db($database);
	if(isset($_POST['user']) && $_POST['user'] !='' && isset($_POST['pwd']) && isset($_POST['email']) && isset($_POST['question']) && isset($_POST['answer'])){
		$user=$_POST['user'];
		$n=preg_match("/^([0-9a-zA-z_]+)$/",$user);
		if($n==0){
			die('{"success":false,"msg":"请输入正确的用户名！"}');
		}
		$qSet="";
		$qSet.= $_POST['pwd']=='' ? "psw=psw" : "psw='".md5($_POST['pwd'])."'";
		$qSet.= $_POST['email']=='' ? ",email=email" : ",email='".$_POST['email']."'";
		$qSet.= $_POST['question']=='' ? ",question=question" : ",question='".$_POST['question']."'";
		$qSet.= $_POST['answer']=='' ? ",answer=answer" : ",answer='".md5($_POST['answer'])."'";
		mysql_query("UPDATE $tb_user SET $qSet WHERE name='$user';",$link);
		$error=mysql_error();
		echo '{"success":true,"msg":"'.$error.'"}';
	}else{
		echo '{"success":false,"msg":"请从客户端登入！"}';
	}
?>