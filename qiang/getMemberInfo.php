<?php
	/*
	 * 获取用户信息
	 * 输入参数：start limit
	 * 参数传递方式：POST
	 * 返回数据格式：
	 * 		{totalCount, records{userid,name,money,question,email,rtime,otime,ltime}
	 * 		}
	 */
	
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');

	echo getMemberInfo();

	function getMemberInfo() {
	
		$HOST=HOST;
		$USER=USER;
		$PWD=PWD;
		$DATABASE=DATABASE;
		$TABLE1=TB_ACCOUNT;
		$TABLE2=TB_USER;
	
		if(isset($_POST['start']) && isset($_POST['limit'])){
			$start=$_POST['start'];
			$end=$_POST['limit'];
		}else{
			$start=0;
			$end=25;
		}

		$fields=" $TABLE1.userid,name,money,question,email,rtime,otime,ltime";
		//userid=9999 为匿名账户所以不需要返回
		$qS="SELECT $fields FROM $TABLE1,$TABLE2 WHERE $TABLE1.userid=$TABLE2.userid AND $TABLE1.userid <> 9999 ORDER BY userid ASC LIMIT $start,$end;";
	
		$link=@mysql_connect($HOST,$USER,$PWD);
		@mysql_select_db($DATABASE,$link);
	
		$result=@mysql_query("SELECT COUNT(userid) FROM $TABLE2 ;",$link);
		$row=@mysql_fetch_array($result,MYSQL_ASSOC);
		$totalCount=$row['COUNT(userid)']-1;
	
		$response=array("totalCount"=>$totalCount,"records"=>array(),);
	
		$result=@mysql_query($qS,$link);
		while ($row=@mysql_fetch_array($result,MYSQL_ASSOC)) {
			$record=array();
			foreach ($row as $key=>$value)
				$record[$key]=$value;
			$response['records'][]=$record;
		}
	
		return json_encode($response);
	}
?>