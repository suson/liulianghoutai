<?php
	/*
	 * 获取用户的消费记录
	 * 返回数据格式：
	 * 		{records:{orderid,userid,name,otime,val,bae}
	 * 		totalCount}
	 */

	require_once '../dbcfg.php';
	require_once "./securityCheck.php";
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE1=TB_ORDER;
	$TABLE2=TB_USER;
	
	$fields="orderid,$TABLE1.userid,$TABLE2.name,$TABLE1.otime,val,bae ";
	$qS="SELECT $fields FROM $TABLE1,$TABLE2 WHERE $TABLE1.userid=$TABLE2.userid AND otype=3;";
	
	$link=@mysql_connect($HOST,$USER,$PWD);
	@mysql_select_db($DATABASE,$link);
	$result=@mysql_query($qS,$link);
	
	$totalCount=@mysql_num_rows($result);
	$response=array("totalCount"=>$totalCount,"records"=>array(),);
	
	while ($row=@mysql_fetch_array($result,MYSQL_ASSOC)){
		$record=array();
		foreach ($row as $key=>$values)
			$record[$key]=$values;
		$response['records'][]=$record;
	}
	//$response['error']=mysql_error();
	echo json_encode($response);
	
?>