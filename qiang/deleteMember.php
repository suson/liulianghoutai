<?php

	require_once "./securityCheck.php";
	require_once '../dbcfg.php';
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":"登入超时"}');
	
	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$database=DATABASE;
	$tb_user=TB_USER;
	$tb_account=TB_ACCOUNT;
	$tb_url=TB_URL;
	$tb_urlodrs=TB_URLODRS;
	$tb_order=TB_ORDER;
	
	$link=mysql_connect($host,$user,$pwd);
	if(isset($_POST['user']) && $_POST['user'] != ""){
		$user=$_POST['user'];
		$n=preg_match("/^([0-9a-zA-z_]+)$/",$user);
		if($n==0){
			die('{"success":false,"msg":"请输入用户名！"}');
		}
		
		mysql_select_db($database);
		$qGetUserid="SELECT userid FROM $tb_user WHERE name='$user';";
		$qDltUser="DELETE FROM $tb_user WHERE name='$user';";
		$result=mysql_query($qGetUserid,$link);
		if(0 != mysql_num_rows($result)){
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$userid=$row['userid'];
			//删除用户账户
			$qDltAccount="DELETE FROM $tb_account WHERE userid=$userid ;";
			mysql_query($qDltAccount,$link);
			//获取url数据
			$qGetUrl="SELECT urlid FROM $tb_url WHERE userid=$userid ;";
			$result=mysql_query($qGetUrl,$link);
			if(0!=mysql_num_rows($result)){
				while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
					$urlid=$row['urlid'];
					//删除 urlodr
					$qDltUrlOdrs="DELETE FROM $tb_urlodrs WHERE urlid=$urlid ;";
					mysql_query($qDltUrlOdrs,$link);
					//删除URL
					$qDltUrl="DELETE FROM $tb_url WHERE urlid=$urlid ;";
					mysql_query($qDltUrl,$link);
				}
			}
			//删除用户的交易记录
			$qDltOrder="DELETE FROM $tb_order WHERE userid=$userid;";
			mysql_query($qDltOrder,$link);
			//删除用户
			mysql_query($qDltUser,$link);
				echo '{"success":true}';
		}
		
	}else{
		echo '{"success":false,"msg":"请输入用户名！"}';
	}
?>