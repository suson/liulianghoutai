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
	echo  urlLogin();

function urlLogin(){
	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE1=TB_URL;
	$TABLE2=TB_FLOWER;
	$ip=getInvokeIP();
	if(isset($_GET['u']))
	{
		$link=mysql_connect($HOST,$USER,$PWD);
		mysql_select_db($DATABASE);
		//$ipcount="SELECT count(1) FROM url WHERE ip='$ip' and free=1 and online=1;";
		$ipcount="SELECT url FROM url WHERE ip='$ip' and free=1 and online=1;";
		$result=mysql_query($ipcount,$link);
		$result=mysql_num_rows($result);
		if($result<3)
		{
		if(is_numeric($_GET['u']))
		{
			//以urlid的方式登入
			$u=$_GET['u'];
			$qS="SELECT urlid,usefurl,furls,useturl,turl,usepop,ltime FROM $TABLE1 WHERE urlid='$u';";
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
				$hrs="";
				$result=mysql_query("SELECT hrs FROM $TABLE2 WHERE cid=$urlid;",$link);
				if($row=mysql_fetch_array($result,MYSQL_ASSOC))
				{
					$hrs=$row["hrs"];
				}
					
				// {网站ID(|)启用来源网址(|)来源网址(|)启用目标网址(|)目标网址(|)启用弹窗(|)流量曲线控制}
				$rtn="{".$urlid."(|)".$usefurl."(|)".$furls."(|)".$useturl."(|)".$turl."(|)".$usepop."(|) $hrs }";
				$qU="UPDATE $TABLE1 SET free=1,online=1,ltime=now(),ip='$ip' WHERE urlid=$u ;";
				mysql_query($qU,$link);

				$timestamp1=strtotime($ltime);
				$day1=date("d", $timestamp1); //最后登陆时间
				$timestamp2 = strtotime(date("y-m-d H:i:s"));
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
			if(0 == preg_match("@^(http://|https://)@",$u))
			{
				$url="http://".$u;
			}
			$qS="SELECT  urlid,usefurl,furls,useturl,turl,usepop,ltime FROM url WHERE url='$url';";
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
				$urltype=SVC_IP8;
				$tcount=intval(SVC_IP8/24);
$flowcrl="0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100";
				$qI="INSERT INTO url(userid,url,name,urltype,rtime,ltime,free,ip,flowcrl) VALUES(9999,'$url','anonymity',$urltype,now(),now(),1,'$ip','$flowcrl');";
				mysql_query($qI,$link);

				$urlid= mysql_insert_id($link); //返回新插入的网址的ID
				$rtn="{".$urlid."(|)0(|)(|)0(|)(|)0(|)}";
				
				$qU="UPDATE url SET free=1,online=1,ltime=now() WHERE urlid=$urlid ;";
				$bSuc=mysql_query($qU,$link);

				if(!$bSuc)
				{
					echo mysql_error();
				}
				else
				{
					//添加网址总数
					$qAddUrlCount="UPDATE admins SET counturl=count + 1 WHERE name='admin';";
					@mysql_query($qAddUrlCount,$link);
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
				$hrs="";
				$result=mysql_query("SELECT hrs FROM $TABLE2 WHERE cid=$urlid;",$link);
				if($row=mysql_fetch_array($result,MYSQL_ASSOC))
				{
					$hrs=$row["hrs"];
				}
				// {网站ID(|)启用来源网址(|)来源网址(|)启用目标网址(|)目标网址(|)启用弹窗(|)流量曲线控制}
				$rtn="{".$urlid."(|)".$usefurl."(|)".$furls."(|)".$useturl."(|)".$turl."(|)".$usepop."(|)$hrs}";
				
				$qU="UPDATE url SET free=1,online=1,ltime=now(),ip='$ip' WHERE urlid=$urlid;";
				$bSuc=mysql_query($qU,$link);

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
		}
		else
		{
			return "1";
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