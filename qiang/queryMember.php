<?php
/*
 *  查询用户详细信息
 *  接收参数：q 用户名
 *  传递方式：POST
 *  输出数据格式：
 *  	{userid,name,ltime,money,pmoney,lmoney, urls:[{urlid,name,url,t,o}]
 *  	}
 */

	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	echo json_encode(queryMemberInfo());
	
	function queryMemberinfo() {
		
		$tbUser=TB_USER;
		$tbAccount=TB_ACCOUNT;
		$tbUrl=TB_URL;
		$tbUrlOdrs=TB_URLODRS;
		
		if(isset($_POST['q'])){
			$q=$_POST['q'];
		}else{
			return array("success"=>false,);
		}
		
		$link=@mysql_connect(HOST,USER,PWD);
		@mysql_select_db(DATABASE,$link);
		/*
		 * 查询用户信息和账户信息
		 */
		$qUserAndAccount="SELECT $tbUser.userid,name,ltime,money,pmoney,lmoney FROM $tbUser,$tbAccount WHERE $tbUser.userid=$tbAccount.userid AND $tbUser.name='$q';";
		
		$result=@mysql_query($qUserAndAccount,$link);
		$row=@mysql_fetch_array($result,MYSQL_ASSOC);
		$response=array("userid"=>$row['userid'],
				"name"=>$row['name'],
				"ltime"=>$row['ltime'],
				"money"=>$row['money'],
				"pmoney"=>$row['pmoney'],
				"lmoney"=>$row['lmoney'],
				"urls"=>array());
		/*
		 * 查询用户URL 信息 
		 */
		$userid=$response['userid'];
		$qUrls="SELECT urlid,url,name FROM $tbUrl WHERE userid=$userid;";
		$result=@mysql_query($qUrls,$link);
		while ($row=@mysql_fetch_array($result,MYSQL_ASSOC)) {
			$url=array("urlid"=>$row['urlid'],
				"name"=>$row['name'],
				"url"=>$row['url'],
				"o"=>false,
				"t"=>false);
			$urlid=$url['urlid'];
			$rslt=@mysql_query("SELECT svcid FROM $tbUrlOdrs WHERE status <> 0 AND urlid=$urlid;",$link);
			if (0 != @mysql_num_rows($rslt)) {
				while ($row=@mysql_fetch_array($rslt,MYSQL_ASSOC)) {
					if ($row['svcid']==10) {
						$url['o']=true;
					}elseif($row['svcid']==8 || $row['svcid']>100){
						$url['t']=true;
					}
				}
			}
			$response['urls'][]=$url;
		}
		$response['success']=true;
		return $response;
	}
	
?>