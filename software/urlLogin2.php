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
	mysql_query('set names utf8');
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
	$tempstr = '';
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
	//$TABLE2=TB_FLOWER;
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
				clearzero($urlid);
				$qU="UPDATE $TABLE1 SET free=1,online=0,ltime=now(),ip='$ip' WHERE urlid=$u ;";
				mysql_query($qU,$link);
				/*
				//----------------------------------------活跃表更新记录
				$userltime = time()+3600*8 ;
				
				$qU="UPDATE aurl SET ltime='$userltime',ip='$ip',rndid='$rndid' WHERE urlid=$u ;";
				mysql_query($qU,$link) ;
				$rowsnum = mysql_affected_rows();
				if ( $rowsnum <1 ) { //无记录
					 
					$useturl=$row['useturl'] ;
					$usepop=$row['usepop'] ;
					$url = $row['url'] ;
					$urltype=$row['urltype'];
					$ltime = $userltime ;
					$vtime = $ltime ;
					$vnum = 0 ;
					//---------------------------运算时段上限 vlimit
					$flowcrl=$row['flowcrl'];
					$arraylist=explode("|",$flowcrl);
					$timestamp = strtotime(date("y-m-d G:i:s",time()+3600*8));
					$hour=date("G", $timestamp); //系统时间
					$arrayhctrl=explode(":",$arraylist[$hour]);
					$vlimit = $arrayhctrl[1] ;
					//---------------------------
					$qI="INSERT INTO aurl(urlid,url,subv,furls,usefurl,turl,useturl,usepop,ip,ltime,vtime,vnum,vlimit,urltype,flowcrl,rndid) VALUES('$urlid','$url','$subv','$furls','$usefurl','$turl','$useturl','$usepop','$ip','$ltime','$vtime','$vnum','$vlimit','$urltype','$flowcrl','$rndid');";

					mysql_query($qI,$link);
				}
				else { //有记录

				}
                 */
				//----------------------------------------
				 
				$ltime=$row['ltime'];
				$timestamp1=strtotime($ltime);
				$day1=date("d", $timestamp1); //最后登陆时间
				$timestamp2 = strtotime(date("y-m-d G:i:s",time()+3600*8));
				$day2=date("d", $timestamp2); //系统时间
				if($day2>$day1)
				{
				    $qUtdclick="UPDATE $TABLE1 SET tdclick=0 WHERE urlid=$u ;";
				    @mysql_query($qUtdclick,$link);
				}
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
				$sfurls = 'http://www.baidu.com';
				//$sfurls = 'http://zhenaiweiyi.taobao.com/';
				$flowcrl="0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14|0:10:14";
				$qI="INSERT INTO url(userid,url,name,urltype,furls,rtime,ltime,free,online,ip,flowcrl,useturl,usepop,usefurl,urlindex) VALUES(9999,'$url','anonymity',8,'$sfurls',now(),now(),1,1,'$ip','$flowcrl',30,5,10,'$urlindexv');";
				mysql_query($qI,$link);

				$urlid= mysql_insert_id($link); //返回新插入的网址的ID
				$rtn="{".$urlid."(|)0(|)(|)0(|)(|)0(|)}";
				
				//$qU="UPDATE url SET free=1,online=1,ltime=now() WHERE urlid=$urlid and urltype = 8 ;";
				//$bSuc=mysql_query($qU,$link);
				
				//----------------------------------------活跃表新增记录
				$userltime = time()+3600*8 ;
				$vtime = $userltime ;
				$qI="INSERT INTO aurl(urlid,url,furls,ltime,vtime,usefurl,useturl,ip,vlimit,urltype,usepop,flowcrl,rndid) VALUES('$urlid','$url','$sfurls','$userltime','$vtime',100,30,'$ip',8,8,5,'$flowcrl','$rndid');";
				$bSuc = mysql_query($qI,$link);
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
					
				//$hrs="";
				//$result=mysql_query("SELECT hrs FROM $TABLE2 WHERE cid=$urlid;",$link);
				//if($row=mysql_fetch_array($result,MYSQL_ASSOC))
				//{
				//	$hrs=$row["hrs"];
				//}

				// {网站ID(|)启用来源网址(|)来源网址(|)启用目标网址(|)目标网址(|)启用弹窗(|)流量曲线控制}
				//$rtn="{".$urlid."(|)".$usefurl."(|)".$furls."(|)".$useturl."(|)".$turl."(|)".$usepop."(|)$hrs}";

				$rtn="{".$urlid."(|)".$usefurl."(|)".$furls."(|)".$useturl."(|)".$turl."(|)".$usepop."(|)$subv}";
				clearzero($urlid);
				$qU="UPDATE url SET free=1,online=0,ltime=now(),ip='$ip' WHERE urlid=$urlid and urltype = 8;";
				$bSuc=mysql_query($qU,$link);
 
                /*
				//----------------------------------------活跃表更新记录
				$userltime = time()+3600*8 ;
				$qU="UPDATE aurl SET ltime='$userltime',ip='$ip',rndid='$rndid' WHERE urlid=$urlid ;";
				mysql_query($qU,$link) ;
				$rowsnum = mysql_affected_rows();
				if ( $rowsnum <1 ) { //无记录
					 
					$useturl=$row['useturl'] ;
					$usepop=$row['usepop'] ;
					$url = $row['url'] ;
					$urltype=$row['urltype'];
					$tdclick=$row['tdclick'];
					$clickself=$row['clickself'];
					$ltime = $userltime ;
					$vtime = $ltime ;
					$vnum = 0 ;
					//---------------------------运算时段上限 vlimit
					$flowcrl=$row['flowcrl'];
					$arraylist=explode("|",$flowcrl);
					$timestamp = strtotime(date("y-m-d G:i:s",time()+3600*8));
					$hour=date("G", $timestamp); //系统时间
					$arrayhctrl=explode(":",$arraylist[$hour]);
					$vlimit = $arrayhctrl[1] ;
					//---------------------------
					$qI="INSERT INTO aurl(urlid,url,subv,furls,usefurl,turl,useturl,usepop,ip,ltime,vtime,vnum,vlimit,urltype,tdclick,clickself,flowcrl,rndid) VALUES('$urlid','$url','$subv','$furls','$usefurl','$turl','$useturl','$usepop','$ip','$ltime','$vtime','$vnum','$vlimit','$urltype','$tdclick','$clickself','$flowcrl','$rndid');";
					
					mysql_query($qI,$link);
				}
				else { //有记录

				}
                */
				//----------------------------------------
				$ltime = $row['ltime'] ;
				$timestamp1=strtotime($ltime);
				$day1=date("d", $timestamp1); //最后登陆时间
			$timestamp2 = strtotime(date("y-m-d H:i:s"));
				$day2=date("d", $timestamp2); //系统时间
				if($day2>$day1)
				{
				    $qUtdclick="UPDATE url SET tdclick=0 WHERE urlid=$urlid;";
				    @mysql_query($qUtdclick,$link);
				}
				if(!$bSuc) echo mysql_error();
				return $rtn;
			}
		}
	//}
}

function clearzero($urlid)
{
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$link=mysql_connect($HOST,$USER,$PWD);
	@mysql_select_db($DATABASE);

	$qT="SELECT ltime FROM url WHERE urlid=$urlid;";
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
		$qUtdclick="UPDATE url SET tdclick=0,vnum=0 WHERE urlid=$urlid;";
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
			//$Aflowcrl=$Aflowcrl."|"."0:".$arrayhctrl[1].":".$arrayhctrl[2];
			$arrayflow[$i]="0:".$arrayhctrl[1].":".$arrayhctrl[2];
 		}
		//$Aflowcrl=substr($Aflowcrl,1,strlen($Aflowcrl));
		$Aflowcrl=implode('|',$arrayflow);
		$fupdate="update url set flowcrl='$Aflowcrl' where urlid=$urlid;";
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