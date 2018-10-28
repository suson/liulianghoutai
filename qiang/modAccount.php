<?php
/*
 * 修改用户账户信息
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
	$tb_account=TB_ACCOUNT;

	$link=mysql_connect($host,$user,$pwd);
	mysql_select_db($database);
	if(isset($_POST['user']) && isset($_POST['money']) && isset($_POST['pmoney']) && isset($_POST['lmoney'])){
		$user=$_POST['user'];
		$n=preg_match("/^([0-9a-zA-z_]+)$/",$user);
		if($n==0){
			die('{"success":false,"msg":"请输入用户名！"}');
		}
		$money= is_numeric($_POST['money']) ? $_POST['money'] * 100 : 0;
		$pmoney= is_numeric($_POST['pmoney']) ? $_POST['pmoney'] * 100 : 0;
		$lmoney= is_numeric($_POST['lmoney']) ? $_POST['lmoney'] * 100 : 0;
		
		$qUser="SELECT userid FROM $tb_user WHERE name='$user';";
		$result=mysql_query($qUser,$link);
		if(mysql_num_rows($result) != 0){
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$userid=$row['userid'];
			
			$qUpdate="UPDATE $tb_account SET money=money+$money,pmoney=pmoney+$pmoney,lmoney=lmoney+$lmoney WHERE userid=$userid;";
			mysql_query($qUpdate,$link);
			$error=mysql_error();
			echo '{"success":true,"msg":"'.$error.'"}';
		}else{
			die('{"success":false,"msg":"请输入正确的用户名！"}');
		}
	}else{
		echo '{"success":false,"msg":"请从客户端登入"}';
	}
?>