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
	

	$response=array();
	$ctime = time() + 3600*8 -60*60 ;

	//---------------------------------
	$chushu = rand(4,30) ;
	$yushu = $chushu % 2 ==0 ? $chushu / 2 - 1 : ($chushu-1)/2 ;
	
	$rndidqstrA = array('floor(rndid / 10000)=1','floor(rndid / 10000)=2','floor(rndid / 10000)=3','floor(rndid / 10000)=4','(rndid % 5)=0','(rndid % 5)=1','(rndid % 5)=2','(rndid % 5)=3','(rndid % 5)=4') ;
	$rndidqstr = $rndidqstrA[rand(0,8)] ;
 
	$orderbycolumn = (time()+rand(200,300)) % 2 ==0 ? 'urlid' : 'rndid' ; 
	$orderstr = (time()+rand(1,100)) % 2 ==0 ? 'asc' : 'desc' ;

	$qS="SELECT * FROM aurl WHERE vnum < vlimit and ltime > $ctime and (urlid % $chushu) > $yushu and $rndidqstr  order by $orderbycolumn $orderstr limit 10 " ;

	$result=mysql_query($qS,$link);
	if ( mysql_num_rows($result)<=4 ) {
		$qS="SELECT * FROM aurl WHERE vnum < vlimit and ltime > $ctime ORDER BY RAND() LIMIT 10";
		$result=mysql_query($qS,$link);
	}
	//---------------------------------//---------------------------------

	$result=mysql_query($qS,$link);
	if (0!=mysql_num_rows($result)) {
		while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
		{
			
 
			//{ 网址编号|网址 | 是否启用弹窗 | 是否启用目标网址 | 目标网址 | 是否启用来源网址 | 来源网址 | 子页浏览 }
			$urlid=$row['urlid'];
			$url=$row['url'];
			//if($row['urltype']==2){
			//    $url=$row['url'].rand(0,5000);
			//}else{
			//    $url=$row['url'];
			//}
			$usepop=$row['usepop'];
			$useturl=$row['useturl'];
			$turl=$row['turl'];
			$usefurl=$row['usefurl'];
			$furl=$row['furl'];
			$subv=$row['subv'];
			$tliu=rand(1,6);

			$usepop= rand(0,10000)<$usepop*100 ? 1 : 0;
			$useturl= $row['turl'] != "" ? (rand(0,10000)<($row['useturl']& 0X00FF)*100 ? 1 : 0) : 0;
			$usefurl= $row['furls'] != "" ? (rand(0,10000)<($row['usefurl']& 0X00FF)*100 ? 1 : 0) : 0;
			
			$turl="";
			if($useturl>0)
			{
				$turls=explode("|",$row['turl']);
				$len=count($turls);
				$turl=$turls[rand(0,$len-1)];
			}
			
			$furl="";
			if($usefurl>0)
			{
				$furls=explode("|",$row['furls']);
				$len=count($furls);
				$furl=$furls[rand(0,$len-1)];
			}

			$rsp ="{ $urlid | $url | $usepop | $useturl | $turl | $usefurl | $furl | $subv | 3 }";
			//$rndurlid = rand(0,10000) ;
			//$rsp = '{'.$rndurlid . "| http:\/\/blog.sina.com.cn\/u\/2173136550 | 0 | 0 | http:\/\/blog.sina.com.cn\/s\/blog_81876ea60100y6n3.html| 0 | | 0}" ;
			$response[]=$rsp;
		}
	}

	echo json_encode($response);

?>