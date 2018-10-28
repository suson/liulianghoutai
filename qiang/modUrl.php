<?php
/*
 * 修改网址的信息
 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$database=DATABASE;
	$tb_url=TB_URL;
	
	$link=mysql_connect($host,$user,$pwd);
	mysql_select_db($database);
	
	if(isset($_POST['urlid']) || isset($_POST['url']) && isset($_POST['name'])){
		if(isset($_POST['urlid']) && $_POST['urlid'] !=''){
			$urlid=$_POST['urlid'];
			if(isset($_POST['usepop']) && isset($_POST['poptax'])){
				$poptax=is_numeric($_POST['poptax']) ? $_POST['poptax'] : 5;
				mysql_query("UPDATE $tb_url SET usepop=$poptax WHERE urlid=$urlid;",$link);
			}
			if(isset($_POST['useturl']) && $_POST['useturl'] ==1 && isset($_POST['turl'])){
				$turl=$_POST['turl'];
				mysql_query("UPDATE $tb_url SET turl='$turl',useturl=1 WHERE urlid=$urlid;",$link);
			}
			if(isset($_POST['usefurl']) && $_POST['usefurl'] && isset($_POST['furls'])){
				$furls=$_POST['furls'];
				mysql_query("UPDATE $tb_url SET furs='$furls',usefurl=1 WHERE urlid=$urlid;",$link);
			}
			echo '{"success":true,"msg":""}';
		}elseif(isset($_POST['url']) && isset($_POST['name']) && $_POST['url']!='' && $_POST['name']!=''){
			$url=$_POST['url'];
			$name=$_POST['name'];
			mysql_query("INSERT INTO $tb_url (url,name) VALUES('$url','$name');",$link);
			$urlid=mysql_insert_id($link);
			if(isset($_POST['usepop']) && isset($_POST['poptax'])){
				$poptax=is_numeric($_POST['poptax']) ? $_POST['poptax'] : 5;
				mysql_query("UPDATE $tb_url SET usepop=$poptax WHERE urlid=$urlid;",$link);
			}
			if(isset($_POST['useturl']) && $_POST['useturl']==1 && isset($_POST['turl'])){
				$turl=$_POST['turl'];
				mysql_query("UPDATE $tb_url SET turl='$turl',useturl=1 WHERE urlid=$urlid;",$link);
			}
			if(isset($_POST['usefurl']) && $_POST['usefurl'] && isset($_POST['furls'])){
				$furls=$_POST['furls'];
				mysql_query("UPDATE $tb_url SET furls='$furls',usefurl=1 WHERE urlid=$urlid;",$link);
			}
			echo mysql_error();
			echo '{"success":true,"msg":"herre"}';
		}else{
			echo '{"success":false,"msg":"信息输入不全"}';
		}
		
	}else{
		echo '{"success":false,"msg":"请从客户端登入！"}';
	}
?>