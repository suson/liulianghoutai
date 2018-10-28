<?php
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');

	$host=HOST;
	$user=USER;
	$pwd=PWD;
	$database=DATABASE;
	$table=TB_FILTER;
	
	$response=array();
	$link=@mysql_connect($host,$user,$pwd);
	
	if(@mysql_select_db($database,$link)){
		$result=@mysql_query("SELECT id,url,reason FROM $table ;",$link);
		$totalCount=@mysql_num_rows($result);
		while ($row=@mysql_fetch_array($result,MYSQL_ASSOC)){
			
			$record=array();
			foreach ($row as $key=>$value)
				$record[$key]=$value;
			/*
			$record=array("id"=>$row['id'],
					"url"=>$row['url'],
					"reason"=>$row['reason'],
					); */
			$response["records"][]=$record;
		}
		$response['totalCount']=$totalCount;
	}else{
		$error=mysql_error();
		$response=array("success"=>false,"msg"=>$error,);
	}
	echo json_encode($response);
?>