<?php
/*
 * 文件:ping.php
 * 接收软件客户端的在线信号请求
 * 输入参数：urlid
 * GET 方式传入
 * 输出参数：error  0 更新时间成功  
 * 		tdclick:  今日流量
 * JSON : {"error":0|-1,"tdclick": }
 * 
 * 输入参数：ud 格式: [urlid1,urlid2]
 *  更新URL 状态
 */

require_once './dbcfg.php';
	echo ping();
	update_db();
function ping(){

	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	
	if(isset($_GET['urlid'])){
		$urlid=$_GET['urlid'];
		//更新网址的最后登入时间
		$qU="UPDATE url SET ltime=now(),online=1 WHERE urlid=$urlid and urltype = 8;";
		//获取网址的今日流量和累计流量
		$qS="SELECT tdclick,clickself FROM url WHERE urlid=$urlid;";
		//获取上次登时间
		$qT="SELECT ltime FROM url WHERE urlid=$urlid;";

		$link=@mysql_connect($HOST,$USER,$PWD);
		@mysql_select_db($DATABASE);
		
		//今日流量清0
		$result=@mysql_query($qT,$link);
		if(0!=@mysql_num_rows($result)){
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$ltime=$row['ltime'];
		}

		$timestamp1=strtotime($ltime);
		$day1=date("d", $timestamp1); //最后登陆时间
		$timestamp2 = strtotime(date("y-m-d H:i:s",time()+3600*8));
		$day2=date("d", $timestamp2); //系统时间
		if($day2>$day1)
		{
			$qUtdclick="UPDATE url SET tdclick=0 WHERE urlid=$urlid;";
			@mysql_query($qUtdclick,$link);

			//清0时段数据开始
			$result=mysql_query("select flowcrl from url where urlid=$urlid;",$link);
			if($row=mysql_fetch_array($result,MYSQL_ASSOC))
			{
				$flowcrl=$row['flowcrl'];
			}

			$arraylist=explode("|",$flowcrl);
			for($i=0;$i<sizeof($arraylist);$i++) 
	 		{	
				$arrayhctrl=explode(":",$arraylist[$i]);
				$arrayflow[$i]="0:".$arrayhctrl[1].":".$arrayhctrl[2];
 			}
			$Aflowcrl=implode('|',$arrayflow);
			$fupdate="update url set flowcrl='$Aflowcrl' where urlid=$urlid;";
			@mysql_query($fupdate,$link);
			//清0时段数据结束

		}
		//今日流量和时段数据清0结束

		

		if(@mysql_query($qU,$link)){
			$result=@mysql_query($qS,$link);
			if(0!=@mysql_num_rows($result)){
				$row=mysql_fetch_array($result,MYSQL_ASSOC);
				$tdclick=$row['tdclick'];
				$clickself=$row['clickself'];
				return "{0 | $tdclick | $clickself}";
			}else{
				return "{-1 | -1 | -1}";
			}
		}else{
			return "{-1 | -1 | -1}";
		}
	}else{
		return "{-1 | -1 | -1}";
	}
}

function update_db(){
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	
	$link=mysql_connect($HOST,$USER,$PWD);
	mysql_select_db($DATABASE,$link);
	
	if(isset($_GET['ud']) && $_GET['ud'] !=""){
		$json=$_GET['ud'];
		$oJson=json_decode($json);
		
		$ip=getInvokeIP();
		//$count=0;
		$len=count($oJson);
		for($i=0;$i<$len;++$i){
			$urlid=$oJson[$i];
			if(is_numeric($urlid)){
				//防止因为网络延迟造成的重复提交和恶意提交数据
				$bExistUrlidIP="SELECT NOT EXISTS(SELECT urlid FROM url_ip WHERE urlid=$urlid AND ip='$ip') bnotexist;";
				$result=@mysql_query($bExistUrlidIP,$link);
				$row=mysql_fetch_array($result,MYSQL_ASSOC);
				if(1 == $row['bnotexist']){
					$qUpdate="UPDATE url SET tdclick=tdclick+1, clickself=clickself+1 WHERE urlid=$urlid;";
					@mysql_query($qUpdate,$link);

					//写入时段数据开始
					$timestamp = strtotime(date("y-m-d G:i:s",time()+3600*8));
					$hour=date("G", $timestamp); //系统时间
					
					$result=mysql_query("select flowcrl from url where urlid=$urlid;",$link);
					if($row=mysql_fetch_array($result,MYSQL_ASSOC))
					{
						$flowcrl=$row['flowcrl'];
					}

					$arraylist=explode("|",$flowcrl);
					$Aflowcrl="";
					for($i=0;$i<sizeof($arraylist);$i++) 
	 				{	
						if($i==$hour)
						{
							$arrayhctrl=explode(":",$arraylist[$i]);
							$tcount=$arrayhctrl[0]+1;
							$Aflowcrl=$Aflowcrl."|".$tcount.":".$arrayhctrl[1].":".$arrayhctrl[2];
						}
						else
						{
							$Aflowcrl=$Aflowcrl."|".$arraylist[$i];
						}
 					}
					$Aflowcrl=substr($Aflowcrl,1,strlen($Aflowcrl));
					//$flowcrl=$flowcrl."|".sizeof($arraylist);
					//$Aflowcrl=$Aflowcrl."|".sizeof($arraylist);
					$fupdate="update url set flowcrl='$Aflowcrl' where urlid=$urlid;";
					@mysql_query($fupdate,$link);
					//写入时段数据结束

					//++$count;
					//更新表url_ip
					//$date=date('Y-m-d H:i:s',time());
					//$qUpdateUrlIp="INSERT INTO url_ip(urlid,ip,iptime) VALUES($urlid,'$ip','$date');";
					//mysql_query($qUpdateUrlIp,$link);
				}
			}
		}
		//更新分享流量 clickother
		//if(isset($_GET['urlid'])){
		//	$urlid=$_GET['urlid'];
		//	$qU="UPDATE url SET clickother=clickother+$count WHERE urlid=$urlid;";
		//	@mysql_query($qU,$link);
		//}
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