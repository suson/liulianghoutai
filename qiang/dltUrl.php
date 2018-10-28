<?php
/*
 * 删除URL 
 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":"登入超时"}');
	
	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$database=DATABASE;
	$tb_url=TB_URL;
	$tb_urlodrs=TB_URLODRS;
	$tb_order=TB_ORDER;
	
	$link=mysql_connect($host,$user,$pwd);
	mysql_select_db($database);
	
	if(isset($_POST['url']) || isset($_POST['urlid']) || isset($_POST['name'])){
		if($_POST['url'] != ''){
			$url=$_POST['url'];
			$qGetUrlid="SELECT urlid FROM $tb_url WHERE url='$url';";
			$result=mysql_query($qGetUrlid,$link);
			if(mysql_num_rows($result) != 0){
				while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
					$urlid=$row['urlid'];
					//删除该网址的交易信息
					$qDltOrder="DELETE FROM $tb_order WHERE urlid=$urlid ;";
					mysql_query($qDltOrder,$link);
					
					//删除该网址开通的服务信息
					$qDltUrlOdrs="DELETE FROM $tb_urlodrs WHERE urlid=$urlid ;";
					mysql_query($qDltUrlOdrs,$link);
					
					//删除该网址
					$qDltUrl="DELETE FROM $tb_url WHERE urlid=$urlid ;";
					mysql_query($qDltUrl,$link);
				}
			}else{
				die('{"success":false,"msg":"没有这个网址"}');
			}
		}elseif($_POST['urlid'] != ''){
			$urlid=$_POST['urlid'];
			//删除该网址的交易信息
			$qDltOrder="DELETE FROM $tb_order WHERE urlid=$urlid ;";
			mysql_query($qDltOrder,$link);
			
			//删除该网址开通的服务信息
			$qDltUrlOdrs="DELETE FROM $tb_urlodrs WHERE urlid=$urlid ;";
			mysql_query($qDltUrlOdrs,$link);
			
			//删除该网址
			$qDltUrl="DELETE FROM $tb_url WHERE urlid=$urlid ;";
			mysql_query($qDltUrl,$link);
			
		}elseif($_POST['name'] != ''){
			$name=$_POST['name'];
			$qGetUrlid="SELECT urlid FROM $tb_url WHERE name='$name';";
			$result=mysql_query($qGetUrlid,$link);
			if(mysql_num_rows($result) != 0){
				while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
					$urlid=$row['urlid'];
					//删除该网址的交易信息
					$qDltOrder="DELETE FROM $tb_order WHERE urlid=$urlid ;";
					mysql_query($qDltOrder,$link);
					
					//删除该网址开通的服务信息
					$qDltUrlOdrs="DELETE FROM $tb_urlodrs WHERE urlid=$urlid ;";
					mysql_query($qDltUrlOdrs,$link);
					
					//删除该网址
					$qDltUrl="DELETE FROM $tb_url WHERE urlid=$urlid ;";
					mysql_query($qDltUrl,$link);
				}
			}else{
				die('{"success":false,"msg":"没有这个网址"}');
			}
		}else{
			echo '{"success":false,"msg":"请输入网址！"}';
		}
		echo json_encode(array('success'=>true,"msg"=>mysql_error()));
	}else{
		echo '{"success":false,"msg":"请从客户端登入！"}';
	}
	
	
?>