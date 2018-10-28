<?php
/*
 * 输入参数：urlid,urlid2,urlid3,userid
 * GET 方式传入
 * 输出参数：error  0 更新时间成功  
 * 		tdclick
 * JSON : {"error":0|-1,"tdclick": }
 * 
 * 输入参数：ud 格式: [urlid1,urlid2]
 *  更新URL 状态
 */

require_once '../dbcfg.php';
	echo ping();
	update_db();
function ping(){

	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$link=mysql_connect($HOST,$USER,$PWD);
	mysql_select_db($DATABASE);

	if(isset($_GET['urlid'])){
		$urlid[0]=$_GET['urlid'];
		$urlid[1]=$_GET['urlid2'];
		$urlid[2]=$_GET['urlid3'];
		$userid=$_GET['userid'];
		$str="";
		if($userid){
		 $acsql="select level,score,money from `account` where userid='".$userid."'";
		 $acquery=mysql_query($acsql);
		 $acrow=mysql_fetch_array($acquery);
		 $str="{".$acrow['level']."|".$acrow['score']."|".$acrow['money']."}";
		}
		for($i=0;$i<count($urlid);$i++)
		{
		   if($urlid[$i] != -1){
			$qS="SELECT tdclick,clickself,flowcrl FROM aurl WHERE urlid=".$urlid[$i].";";
			//echo $qS;
			$result = @mysql_query($qS,$link);
			if(0!=@mysql_num_rows($result)) {
				$row=mysql_fetch_array($result,MYSQL_ASSOC);
				$tdclick=$row['tdclick'];
				$clickself=$row['clickself'];
	
				//--------------------------------活跃表更新网址用户登陆时间ltime
				$flowcrl = $row['flowcrl'];
				$arraylist=explode("|",$flowcrl);
				$chour=date("G", time()+3600*8);  
				$arrayhctrl=explode(":",$arraylist[$chour]);
				$vlimit = $arrayhctrl[1] ;
	
				$userltime = time()+3600*8 ;
				$hourtime = strtotime(date("y-m-d H:00:00",time()+3600*8));
				$daytime = strtotime(date("y-m-d 00:00:00",time()+3600*8));
				$rndid = rand(10001,50000) ;
				$qU="UPDATE aurl SET vnum=IF(ltime>=$hourtime,vnum,0),vlimit=IF(ltime>=$hourtime,vlimit,$vlimit),tdclick=IF(ltime>=$daytime,tdclick,0),ltime='$userltime' WHERE urlid=$urlid[$i] and taocan = 2650;";
				@mysql_query($qU,$link) ; //echo $qU ;
				//--------------------------------
				$s .= "{0 | $tdclick | $clickself}";
			}else{
				$s .= "{-1 | -1 | -1}";
			}
		  }else{
		      $s .= "{-1 | -1 | -1}";
		  }	
		}
		
		return $s.$str;
	}else{
		return "{-1 | -1 | -1}";
	}
}

function update_db(){
	$HOST=HOST;$USER=USER;$PWD=PWD;$DATABASE=DATABASE;
	$link=mysql_connect($HOST,$USER,$PWD);
	mysql_select_db($DATABASE,$link);
	$userid=$_GET['userid'];
	
	if(isset($_GET['ud']) && $_GET['ud'] !=""){
		$json=$_GET['ud'];
		$oJson=json_decode($json);

		$ctime = time()+3600*8 ;
		$hourtime = strtotime(date("y-m-d H:00:00",time()+3600*8));
		$rndid= rand(10001,50000) ;
        $updateStr = "UPDATE aurl SET vnum=IF(vtime>=$hourtime,vnum+1,1),vtime=$ctime,tdclick=tdclick+1,clickself=clickself+1,rndid='$rndid' WHERE " ;
		$whereStr ="(urlid=" . implode(" or urlid=",$oJson) . ")" ;
		$updateStr = $updateStr . $whereStr ; 
		@mysql_query($updateStr,$link);
		//echo $updateStr ;
		
		if($userid){
		$arr=explode(",",$userid);
		$score=round($arr[1]/2);
		$upsql = "UPDATE `account` SET score=score+'$score' WHERE userid='".$arr[0]."'";
		mysql_query($upsql);
		}
	}
}

function getInvokeIP(){
		/*
		 * 获取客户端IP地址
		 */
   		if (getenv("HTTP_CLIENT_IP") && getenv("HTTP_CLIENT_IP")!="" && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
           $ip = getenv("HTTP_CLIENT_IP");
   		}else if (getenv("HTTP_X_FORWARDED_FOR") && getenv("HTTP_X_FORWARDED_FOR")!="" && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
           $ip = getenv("HTTP_X_FORWARDED_FOR");
      	}else if (getenv("REMOTE_ADDR") && getenv("REMOTE_ADDR")!="" && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
           $ip = getenv("REMOTE_ADDR");
        }else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] !="" && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
           $ip = $_SERVER['REMOTE_ADDR'];
       	}else{
           $ip = "unknown";
        }
   		return $ip;

	}

?>