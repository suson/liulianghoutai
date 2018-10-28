<?php
	/*
	 * 网址管理   返回数据库中的网址信息
	 * 接收参数：start limit
	 * 返回数据格式{totalCount, records:{userid,username,urlid, urlname,url}}
	 */
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	if(isset($_POST['start']) && isset($_POST['limit'])){
		$start=$_POST['start'];
		$end=$_POST['limit'];
	}else{
		$start=0;
		$end=25;
	}
	
	$fields="users.userid,users.name username ,urlid,url.name urlname,url,clickother,tdclick,clickself,online";
	$qS="SELECT $fields FROM users,url WHERE users.userid=url.userid ORDER BY urlid ASC LIMIT $start,$end;";
	$qCount="SELECT counturl FROM admins WHERE name='admin';";
	
	$link=@mysql_connect($HOST,$USER,$PWD);
	@mysql_select_db($DATABASE,$link);
	
	$result=@mysql_query($qCount,$link);
	$row=@mysql_fetch_array($result,MYSQL_ASSOC);
	
	$totalCount=$row['counturl'];
	
	$result=@mysql_query($qS,$link);
	
	$response=array("totalCount"=>$totalCount,"msg"=>mysql_error(),"records"=>array(),);
	
	/*
	 *  弃用:
	 *  由于在表user和url里都有name字段 所以这里使用MYSQL_NUM来处理mysql_fetch_rows() 返回的数组

	 */
	while($row=@mysql_fetch_array($result,MYSQL_ASSOC)){
		//$record=array("userid"=>$row[0],"username"=>$row[1],"urlid"=>$row[2],"urlname"=>$row[3],"url"=>$row[4],);
		$record=array("userid"=>$row['userid'],
			"username"=>$row['username'],
			"urlid"=>$row['urlid'],
			"urlname"=>$row['urlname'],
			"url"=>$row['url'],
			"clickother"=>$row['clickother'],
			"tdclick"=>$row['tdclick'],
			"clickself"=>$row['clickself'],
			"online"=>$row['online']);
		$response["records"][]=$record;
	}
	
	echo json_encode($response);
?>