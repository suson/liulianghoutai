<?php
/*
 * 文件：urlLogin.php
 * 实现软件端URL 登入与数据库接口
 * 主要功能是设置登入的URL 最后登入时间 ltime=now() 为当前时间 
 *  在线字段设置为  online=1
 * 参数为URL 或者 urlid
 * GET 方式传入
 * 参数名：u
 * 返回 值:urlid
 */
header("Content-type: text/html; charset=utf-8");
require_once '../dbcfg.php';
require_once "../service/urlcore/cfg/svcipcfg.php";
 

	if(isset($_GET['u']))
	{
		echo  urlLogin();
	}
	else
	{
		//echo  limitip();
		echo 0 ;
	}
	
function limitip()
{
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE1=TB_URL;
	//$TABLE2=TB_FLOWER;
	$ip=getInvokeIP();
	$link=mysql_connect($HOST,$USER,$PWD);
	mysql_select_db($DATABASE);

	//$ipcount="SELECT count(1) FROM url WHERE ip='$ip' and free=1 and online=1;";
	$ipcount="SELECT urlid FROM aurl WHERE ip='$ip' ;";
	$result=mysql_query($ipcount,$link);
	$result=mysql_num_rows($result);
	if($result>=3)
	{
		return "1";
	}
	else
	{
		return "0";
	}
}
function urlrndv($url) { 
	$url = strtolower($url) ;
	$url = $url . 'ljf' ;
	$urlmd5=  md5($url) ;
	for($i=0;$i<=31;$i++) {
		$thisstr = substr($urlmd5,$i,1) ;
		if ( is_numeric($thisstr) ) $tempstr .= $thisstr ;
	}
	$rndv = substr( $tempstr,3,4) ;
	$rndv = (int)$rndv ;
	return $rndv ;
}

function urlLogin(){
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE1=TB_URL;
 
	$ip=getInvokeIP();
	$rndid = rand(10001,50000) ;
	//if(isset($_GET['u']))
	//{
		$link=mysql_connect($HOST,$USER,$PWD);
		mysql_select_db($DATABASE);
		if(is_numeric($_GET['u']))
		{
			//以urlid的方式登入
			$u=$_GET['u'];
			$qS="SELECT * FROM $TABLE1 WHERE urlid='$u';";
			$result=mysql_query($qS,$link);
			if(0 != @mysql_num_rows($result))
			{
				//URLID 存在
				$row=mysql_fetch_array($result,MYSQL_ASSOC);
				$urlid=$row['urlid'];
				$usefurl=$row['usefurl'];
				$furls=$row['furls'];
				$useturl=$row['useturl'] & 0X00FF;
				$turl=$row['turl'];
				$usepop=$row['usepop'] ==0 ? 0 : 1;
				$ltime=$row['ltime'];
				$subv=$row['subv'];
 
				$rtn="{".$urlid."(|)".$usefurl."(|)".$furls."(|)".$useturl."(|)".$turl."(|)".$usepop."(|)". $subv."}";

				$oldltime = $ltime ;
				
				$qU="UPDATE $TABLE1 SET free=1,online=1,ltime=now(),ip='$ip' WHERE urlid=$u ;";
				mysql_query($qU,$link);

				//----------------------------------------活跃表更新记录
				$userltime = time()+3600*8 ;

				$qS = "select ltime,flowcrl from aurl where urlid=$u " ;
				$result=mysql_query($qS,$link);
				if(0 != @mysql_num_rows($result)) { //有记录
					$row=mysql_fetch_array($result,MYSQL_ASSOC) ;
					$oldflowcrl=$row['flowcrl'];
					$oldltime=$row['ltime'];

					$qU="UPDATE aurl SET ltime='$userltime',ip='$ip',rndid='$rndid' WHERE urlid=$u ;";
					mysql_query($qU,$link) ;
					$ifinaurl = 1 ;
				}
				else { //无记录
					$useturl=$row['useturl'] ;
					$usepop=$row['usepop'] ;
					$url = $row['url'] ;
					$urltype=$row['urltype'];
					//$taocan=$row['taocan'];
					$ltime = $userltime ;
					$vtime = $ltime ;
					$vnum = 0 ;
					$clickself = $row['clickself'] ;
					$tdclick = $row['tdclick'] ;
					//---------------------------运算时段上限 vlimit
					$flowcrl=$row['flowcrl'];
					
					$arraylist=explode("|",$flowcrl);
					$timestamp = strtotime(date("y-m-d G:i:s",time()+3600*8));
					$hour=date("G", $timestamp); //系统时间
					$arrayhctrl=explode(":",$arraylist[$hour]);
					$vlimit = $arrayhctrl[1] ;
					//---------------------------

					$timestamp1=strtotime($oldltime);
					$hour1=date("G", $timestamp1);  
					$vnum = $hour1 == $hour ? $row['vnum'] : 0 ;

					$qI="INSERT INTO aurl(urlid,url,subv,furls,usefurl,turl,useturl,usepop,ip,ltime,vtime,vnum,vlimit,urltype,flowcrl,rndid,clickself,tdclick) VALUES('$urlid','$url','$subv','$furls','$usefurl','$turl','$useturl','$usepop','$ip','$ltime','$vtime','$vnum','$vlimit','$urltype','$flowcrl','$rndid','$clickself','$tdclick');";

					mysql_query($qI,$link);
					$ifinaurl = 0 ;
				}
				//----------------------------------------
				 
				clearzero($urlid,$oldltime,$ifinaurl);

				return $rtn;
			}
			else
			{
				//URLID 不存在
				return "0";
			}
			
		}
		else
		{
			//以url形式登入
			$u=$_GET['u'];
			$url=$u;

			$url=str_replace("@","&",$url);
			$url=str_replace("*","%",$url);
			$url=str_replace("^","#",$url);
			if(0 == preg_match("@^(http://|https://)@",$url))
			{
				$url="http://".$url;
			}

			$urlindexv = urlrndv($url) ; 

			$qS="SELECT * FROM url WHERE urlindex='$urlindexv' and url='$url' ";
			$result=mysql_query($qS,$link);
			if(0==mysql_num_rows($result)){
				/*
				 * 新网址 匿名：anonymity
				 * userid : 9999,
				 * name : anonymity 
				 * free : 1
				 * urltype : SVC_IP8
				 * rtime : now()
				 * ltime : now()
				 */
				//$urltype=SVC_IP8;
				$tcount=intval(SVC_IP8/24);
				$flowcrl="0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100";
				$qI="INSERT INTO url(userid,url,name,urltype,taocan,rtime,ltime,free,online,ip,flowcrl,useturl,usepop,usefurl,urlindex) VALUES(9999,'$url','anonymity',1,2650,now(),now(),1,1,'$ip','$flowcrl',30,5,60,'$urlindexv');";
				mysql_query($qI,$link);

				$urlid= mysql_insert_id($link); //返回新插入的网址的ID
				$rtn="{".$urlid."(|)0(|)(|)0(|)(|)0(|)}";
				
				//$qU="UPDATE url SET free=1,online=1,ltime=now() WHERE urlid=$urlid and urltype = 8 ;";
				//$bSuc=mysql_query($qU,$link);
				
				//----------------------------------------活跃表新增记录
				$userltime = time()+3600*8 ;
				$vtime = $userltime ;
				$qI="INSERT INTO aurl(urlid,url,ltime,vtime,usefurl,useturl,ip,vlimit,urltype,taocan,usepop,flowcrl,rndid) VALUES('$urlid','$url','$userltime','$vtime',60,30,'$ip','$tcount',1,2650,5,'$flowcrl','$rndid');";
				mysql_query($qI,$link);
				//----------------------------------------

				if(!$bSuc)
				{
					echo mysql_error();
				}
				else
				{

				}
				return $rtn;
			}
			else
			{
				//旧网址登入
				$row=@mysql_fetch_array($result,MYSQL_ASSOC);
				$urlid=$row['urlid'];
				$usefurl=$row['usefurl'];
				$furls=$row['furls'];
				$useturl=$row['useturl'];
				$turl=$row['turl'];
				$usepop=$row['usepop'] == 0 ? 0 : 1;
				$ltime=$row['ltime'];
				$subv=$row['subv'];
					
 				$rtn="{".$urlid."(|)".$usefurl."(|)".$furls."(|)".$useturl."(|)".$turl."(|)".$usepop."(|)$subv}";
				
				$oldltime = $ltime ;

				$qU="UPDATE url SET free=1,online=1,ltime=now(),ip='$ip' WHERE urlid=$urlid and taocan = 2650;";
				$bSuc=mysql_query($qU,$link);

				//----------------------------------------活跃表更新记录
				$userltime = time()+3600*8 ;

				$qS = "select ltime,flowcrl from aurl where urlid=$urlid " ;
				$result=mysql_query($qS,$link);
				if(0 != @mysql_num_rows($result)) { //有记录
					$row=mysql_fetch_array($result,MYSQL_ASSOC) ;
					$oldflowcrl=$row['flowcrl'];
					$oldltime=$row['ltime'];

					$qU="UPDATE aurl SET ltime='$userltime',ip='$ip',rndid='$rndid' WHERE urlid=$urlid ;";
					mysql_query($qU,$link) ;
					$ifinaurl = 1 ;
				}
				else { //无记录
					$useturl=$row['useturl'] ;
					$usepop=$row['usepop'] ;
					$url = $row['url'] ;
					$urltype=$row['urltype'];
					//$taocan=$row['taocan'];
					$ltime = $userltime ;
					$vtime = $ltime ;
					$vnum = 0 ;
					$clickself = $row['clickself'] ;
					$tdclick = $row['tdclick'] ;
					//---------------------------运算时段上限 vlimit
					$flowcrl=$row['flowcrl'];
					
					$arraylist=explode("|",$flowcrl);
					$timestamp = strtotime(date("y-m-d G:i:s",time()+3600*8));
					$hour=date("G", $timestamp); //系统时间
					$arrayhctrl=explode(":",$arraylist[$hour]);
					$vlimit = $arrayhctrl[1] ;
					//---------------------------

					$timestamp1=strtotime($oldltime);
					$hour1=date("G", $timestamp1);  
					$vnum = $hour1 == $hour ? $row['vnum'] : 0 ;

					$qI="INSERT INTO aurl(urlid,url,subv,furls,usefurl,turl,useturl,usepop,ip,ltime,vtime,vnum,vlimit,urltype,flowcrl,rndid,clickself,tdclick) VALUES('$urlid','$url','$subv','$furls','$usefurl','$turl','$useturl','$usepop','$ip','$ltime','$vtime','$vnum','$vlimit','$urltype','$flowcrl','$rndid','$clickself','$tdclick');";

					mysql_query($qI,$link);
					$ifinaurl = 0 ;
				}
				//----------------------------------------
				 
				clearzero($urlid,$oldltime,$ifinaurl);

				return $rtn;
			}
		}
	//}
}

function clearzero($urlid,$ltime,$ifinaurl)
{
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$link=mysql_connect($HOST,$USER,$PWD);
	@mysql_select_db($DATABASE);

	$timestamp1=strtotime($ltime);
	$day1=date("d", $timestamp1); //最后登陆时间
	$timestamp2 = strtotime(date("y-m-d H:i:s",time()+3600*8));
	$day2=date("d", $timestamp2); //系统时间
	if($day2>$day1)
	{
		$qUtdclick="UPDATE aurl SET tdclick=0,vnum=0 WHERE urlid=$urlid;";
		@mysql_query($qUtdclick,$link);
		//清0时段数据开始
		$result=mysql_query("select flowcrl from aurl where urlid=$urlid;",$link);
		if($row=mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$flowcrl=$row['flowcrl'];
		}
		$arraylist=explode("|",$flowcrl);
		for($i=0;$i<sizeof($arraylist);$i++) 
	 	{	
			$arrayhctrl=explode(":",$arraylist[$i]);
			//$Aflowcrl=$Aflowcrl."|"."0:".$arrayhctrl[1].":".$arrayhctrl[2];
			$arrayflow[$i]="0:".$arrayhctrl[1].":".$arrayhctrl[2];
 		}
		//$Aflowcrl=substr($Aflowcrl,1,strlen($Aflowcrl));
		$Aflowcrl=implode('|',$arrayflow);
		$fupdate="update aurl set flowcrl='$Aflowcrl' where urlid=$urlid;";
		@mysql_query($fupdate,$link);
		//清0时段数据结束
	}
	//今日流量和时段数据清0结束
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