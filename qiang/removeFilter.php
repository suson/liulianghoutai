<?php
/*
 * 删除过滤网址
 * 输入参数为：需要删除的URL id
 * POST 方式传入
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
	
	$link=@mysql_connect($host,$user,$pwd);
	mysql_select_db($database,$link);
	
	if(isset($_POST['url']) && $_POST['url']!=""){
		if(is_numeric($_POST['url'])){
			$urlid=$_POST['url'];
			$qDltByUrlid="DELETE FROM $table WHERE urlid=$urlid;";
			$bSuc=mysql_query($qDltByUrlid,$link);
			
			if($bSuc){
				echo json_encode(array("success"=>true,"msg"=>"删除网址成功<br /> By Urlid:$urlid",));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"删除网址失败<br /> By Urlid:$urlid","error"=>mysql_error()));
			}
		}else{
			//通过URL 删除过滤网址
			$url=$_POST['url'];
			$qDltByUrl="DELETE FROM $table WHERE url='$url';";
			$bSuc=mysql_query($qDltByUrl,$link);
			if($bSuc){
				echo json_encode(array("success"=>true,"msg"=>"删除网址成功<br /> By Url:$url",));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"删除网址失败<br /> By Url:$url","error"=>mysql_error()));
			}
		}
	}else{
		echo json_encode(array("success"=>false,"msg"=>"无法删除网址<br /> 请输入真确的参数",));
	}
?>