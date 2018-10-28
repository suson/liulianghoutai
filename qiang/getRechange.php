<?php
/*
 * 获取用户充值记录
 * 从表SP_order中取出数据
 * 返回数据格式：
 * 	{
 * 		records:[{orderid,userid,name,otime,val,pm,lm,bae}]
 * 		totalCount 记录数量，既会员总数
 * 	}
 * 传入参数为start起始记录行  limit 需要返回的记录行数 
 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
 	$start=$_POST['start'];
 	$end=$start + $_POST['limit'];
 	
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE1=TB_ORDER;
	$TABLE2=TB_USER;
	$fields="orderid,$TABLE1.userid,$TABLE2.name,$TABLE1.otime,val,pm,lm,bae";
	$qS="SELECT $fields FROM $TABLE1,$TABLE2 WHERE $TABLE1.userid=$TABLE2.userid AND otype=4 LIMIT $start,$end;";
	
	
	$link=@mysql_connect($HOST,$USER,$PWD);
	@mysql_select_db($DATABASE,$link);
	
	//获取符合条件的记录的行数
	$result=@mysql_query("SELECT COUNT(*) FROM $TABLE1 WHERE otype=4;",$link);
	$row=@mysql_fetch_array($result,MYSQL_ASSOC);
	$totalCount=$row['COUNT(*)'];
	
	$response=array("totalCount"=>$totalCount,"records"=>array(),);
	
	$result=@mysql_query($qS,$link);
	while ($row=@mysql_fetch_array($result,MYSQL_ASSOC)) {
		$record=array();
		foreach ($row as $key=>$value)
			$record[$key]=$value;
		$response['records'][]=$record;
	}
	echo json_encode($response);
	
	
?>