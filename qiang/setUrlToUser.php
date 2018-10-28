<?php
	/*
	 * 修改网址所属用户
	 * name url
	 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$dbname=DBNAME;
	$tb_url=TB_URL;
	$tb_user=TB_USER;
	
	if(isset($_POST['name']) && $_POST['name'] != "" && isset($_POST['url']) && $_POST['url'] != "" ){
		$name=$_POST['name'];
		$url=$_POST['url'];
		
		$link=mysql_connect($host,$user,$pwd);
		mysql_select_db($dbname,$link);
		
		$qGetUserID="SELECT userid FROM $tb_user WHERE name='$name';";
		$qGetUrl="SELECT name FROM $tb_url WHERE url='$url';";
		
		$result=mysql_query($qGetUserID,$link);
		$row=mysql_fetch_array($result,MYSQL_ASSOC);
		if($row){
			$userid=$row['userid'];
			$result=mysql_query($qGetUrl,$link);
			if(0 != mysql_num_rows($result)){
				$qModUser="UPDATE $tb_url SET userid=$userid WHERE url='$url';";
				$bSuc=mysql_query($qModUser,$link);
				if($bSuc){
					echo '{"success":true,"msg":"修改成功！"}';
				}else{
					echo '{"success":false,"msg":"更新数据库失败"}';
				}
			}else{
				echo '{"success":false,"msg":"网址不存在"}';
			}
		}else{
			echo '{"success":false,"msg":"用户名不存在"}';
		}
	}else{
		echo '{"success":false,"msg":"请输入用户名和/或网址"}';
	}
?>