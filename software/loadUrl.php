<?php
/*文件:loadUrl.php
 * 这个程序只为软件提供数据
 * 通过查询SP_url表获取URL列表 并发送给软件，每次向返回10条数据
 * 传入参数：urlid
 * 传入方式:	GET
 *  注：暂时不考虑流量控制
 * 输入参数：ud 格式: [urlid1,urlid2]
 *  更新URL 状态
 */
	require_once '../dbcfg.php';
	require_once '../service/urlcore/cfg/svcipcfg.php';

	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE=TB_URL;
	
	$link=mysql_connect($HOST,$USER,$PWD);
	mysql_select_db($DATABASE,$link);
	mysql_query('SET NAMES utf8');
	$ip=getInvokeIP();
	//设置10分钟没更新的网址离线
	$qNoTrustee=" UPDATE url SET online=0 WHERE NOT EXISTS(SELECT svcid FROM url_odrs WHERE url.urlid=urlid AND svcid <> 10) AND timestampdiff(MINUTE,ltime,now()) > 10";
	@mysql_query($qNoTrustee,$link);

	$response=array();
	
	/*
	*select * from url order by rand() limit 2;
	* 	开启代挂服务 且流量未达到目标流量的URL
	* EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND status <> 0 AND svcid <> 10)
	*	  未开启代挂服务,在线的URL,今日流量已达1000的URL 
	*NOT EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid)
	*	没有被该IP访问过的
	*NOT EXISTS( SELECT urlid FROM url_ip WHERE url.urlid==urlid AND ip='$ip')
	*	在线且开启了流量分享的url
	*	free=1 AND online=1 AND urltype>=tdclick
	*/
	//$bNotExist="NOT EXISTS( SELECT urlid FROM url_ip WHERE url.urlid=urlid AND ip='$ip')";
	$bExist="EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid AND status <> 0 AND svcid <> 10 )";
	$urlInfo="url.urlid,url.flowcrl,usepop,url,useturl,turl,usefurl,furls";
	$bNoTrustee="NOT EXISTS(SELECT urlid FROM url_odrs WHERE url.urlid=urlid)";
	$bOther="free=1 AND online=1 AND urltype >= tdclick";
	/* AND (( $bExist ) OR ( $bNoTrustee ))*/
	//$qS="SELECT $urlInfo FROM url WHERE $bOther  AND $bNotExist  ORDER BY RAND() LIMIT 10;";
	$qS="SELECT $urlInfo FROM url WHERE $bOther ORDER BY RAND() LIMIT 10;";

	$result=mysql_query($qS,$link);
	if (0!=mysql_num_rows($result)){
		while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$flowcrl=$row['flowcrl'];
			$arraylist=explode("|",$flowcrl);
			$timestamp = strtotime(date("y-m-d G:i:s",time()+3600*8));
			$hour=date("G", $timestamp); //系统时间
			$arrayhctrl=explode(":",$arraylist[$hour]);
			if($arrayhctrl[0]>=$arrayhctrl[1])
			{
				continue;
			}

			$urlid=$row['urlid'];
			$url=$row['url'];
			$usepop= rand(0,10000)<$row['usepop']*100 ? 1 : 0;
			$turl=$row['turl'];
			$useturl= $turl != "" ? (rand(0,10000)<($row['useturl']& 0X00FF)*100 ? 1 : 0) : 0;
			$usefurl=$row['usefurl'];
			$furl="";
			if($usefurl==1)
			{
				$furls=explode("|",$row['furls']);
				$len=count($furls);
				$furl=$furls[rand(0,$len-1)];
			}
			$usefurl= $usefurl==1 && $furl != "" ? 1 : 0 ;
			//{ 网址编号|网址 | 是否启用弹窗 | 是否启用目标网址 | 目标网址 | 是否启用来源网址 | 来源网址 }
			$rsp =("{ $urlid | $url | $usepop | $useturl | $turl | $usefurl | $furl }");
			$response[]=$rsp;
		}
	}
	//var_dump($response);
	echo json_encode($response);

function gbk2utf8($data){
	//return $data;
 if(is_array($data))
  {
    return array_map('gbk2', $data);
  }
 return @iconv("gbk", "gbk//ignore", $data);
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