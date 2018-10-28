<?php
/*
 * 查询指定用户的充值记录和消费记录
 * 传入参数：width 查询方式 id,name, query
 * 输出数据格式：
 * 	{
 * 		success
 * 		RS:{totalCount,records:{orderid, userid,name,otime,val,pm,lm,bae}}
 * 		Exp:{totalCount,records:{orderid,userid,name,otime,val,bae}}
 * }
 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE1=TB_ORDER;
	$TABLE2=TB_USER;
	
	$RSfields="orderid,$TABLE1.userid,$TABLE2.name,$TABLE1.otime,val,pm,lm,bae";
	$Expfields="orderid,$TABLE1.userid,$TABLE2.name,$TABLE1.otime,val,bae";
	$width=$_POST['width'];
	$query=$_POST['query'];
	if($width=="id"){
		$qSRS="SELECT $RSfields FROM $TABLE1,$TABLE2 WHERE $TABLE1.userid='$query' AND $TABLE1.userid=$TABLE2.userid AND $TABLE1.otype=4;";
		$qSExp="SELECT $Expfields FROM $TABLE1,$TABLE2 WHERE $TABLE1.userid='$query' AND  $TABLE1.userid=$TABLE2.userid AND $TABLE1.otype=3;";
	}else{
		$qSRS="SELECT $RSfields FROM $TABLE1,$TABLE2 WHERE $TABLE2.name='$query' AND $TABLE1.userid=$TABLE2.userid AND $TABLE1.otype=4;";
		$qSExp="SELECT $Expfields FROM $TABLE1,$TABLE2 WHERE $TABLE2.name='$query' AND $TABLE1.userid=$TABLE2.userid AND $TABLE1.otype=3;";
	}
	
	$response=array("success"=>true,);
	
	$link=@mysql_connect($HOST,$USER,$PWD);
	@mysql_select_db($DATABASE);
	$RSresult=@mysql_query($qSRS,$link);
	$totalCount=@mysql_num_rows($RSresult);
	$response['RS']=array("totalCount"=>$totalCount,"records"=>array(),"msg"=>@mysql_error(),);
	while ($row=@mysql_fetch_array($RSresult,MYSQL_ASSOC)) {
		$record=array();
		foreach ($row as $key=>$value)
			$record[$key]=$value;
		$response['RS']['records'][]=$record;
	}
	
	$Expresult=@mysql_query($qSExp,$link);
	$totalCount=@mysql_num_rows($Expresult);
	$response['Exp']=array("totalCount"=>$totalCount,"records"=>array(),);
	while ($row=@mysql_fetch_array($Expresult,MYSQL_ASSOC)) {
		$record=array();
		foreach ($row as $key=>$value)
			$record[$key]=$value;
		$response['Exp']['records'][]=$record;
	}
	
	echo json_encode($response);

?>