<?php
	/*
 	 * 获取用户账户信息
 	 * 输入参数： start limit
 	 * 传入方式： POST 
 	 * 返回数据格式：
 	 * 		{totalCount, records:{userid,name,money,pmoney,lmoney,score}
 	 * 		}
 	 */

	require_once "./securityCheck.php";
	require_once '../dbcfg.php';
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":"登入超时"}');
		echo getAccountInfo();		

	function getAccountInfo(){
		$HOST=HOST;
		$USER=USER;
		$PWD=PWD;
		$DATABASE=DATABASE;
		$TABLE1=TB_USER;
		$TABLE2=TB_ACCOUNT;
		
		if(isset($_POST["start"]) && isset($_POST['limit'])){
			$start=$_POST['start'];
			$end=$start + $_POST['limit'];
		}else{
			$start=0;
			$end=50;	
		}
		$fields=" $TABLE1.userid,name,money,pmoney,lmoney,score";
		$qS="SELECT $fields FROM $TABLE1,$TABLE2 WHERE $TABLE1.userid=$TABLE2.userid AND $TABLE1.userid <> 9999 LIMIT $start,$end;";
		
		$link=@mysql_connect($HOST,$USER,$PWD);
		@mysql_select_db($DATABASE);
		
		$result=@mysql_query("SELECT COUNT($TABLE1.userid) FROM $TABLE1,$TABLE2 WHERE $TABLE1.userid =$TABLE2.userid AND $TABLE1.userid <> 9999;",$link);
		$row=@mysql_fetch_array($result,MYSQL_NUM);
		$totalCount=$row[0];
		$response=array("totalCount"=>$totalCount,"records"=>array(),"msg"=>array(mysql_error(),),);
		
		$result=@mysql_query($qS,$link);
		while ($row=@mysql_fetch_array($result,MYSQL_ASSOC)){
			$record=array();
			foreach ($row as $key=>$value)
				$record[$key]=$value;
			$response["records"][]=$record;
		}
		$response["msg"][]=mysql_error();
		return json_encode($response);
	}
?>