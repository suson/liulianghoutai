<?php
/*
 *数据库模块 
 *主要功能：
 *    提供数据库链接和查询
 */
require_once '../../dbcfg.php';
require_once '../../checkSQL.php';
require_once "./cfg/servicecfg.php";
require_once "./cfg/urlLimitcfg.php";
require_once "./cfg/gradeScorecfg.php";
require_once "./cfg/tdlogincfg.php";
require_once "./cfg/rechangeTaxcfg.php";
require_once "./cfg/svcipcfg.php";
require_once("./alipay/myAlipaycfg.php");

	class DB {
		
		private $link;
		private $userid;
		private $response=array();
		public function __construct($userid){
			$this->userid=$userid;
			$this->_initDB();
		}
		private function _initDB(){
			$this->link=@mysql_connect(HOST,USER,PWD);
			if(!$this->link){
				echo '{"error":-2,"msg":'.mysql_error().'}';
				return ;
			}
			if(!@mysql_select_db(DBNAME,$this->link)){
				echo '{"error":-1}';
				return ;
			}
		}
		public function loadUserInfo(){
			$qUser="SELECT userid,name,ltime FROM users WHERE userid={$this->userid};";
			$qAccount="SELECT money,pmoney,lmoney,score,level FROM account WHERE userid={$this->userid};";
			
			$result=@mysql_query($qUser,$this->link) or die('{"error":-1}');
			if(mysql_num_rows($result)!=0){
				$row=@mysql_fetch_array($result,MYSQL_ASSOC);
				foreach ($row as $key =>$value)
					$this->response[$key]=$value;
			}
				
			$result=@mysql_query($qAccount,$this->link) or die('{"error":-1}');
			if(@mysql_num_rows($result)!=0){
				$row=@mysql_fetch_array($result,MYSQL_ASSOC);
				foreach ($row as $key =>$value)
					$this->response[$key]=$value;
			}
			$this->response['error']=0;
			@mysql_query("UPDATE users SET ltime=now() WHERE userid='".$this->userid."';",$this->link);
			return $this->response;
		}
		private function updateDB(){
			$qGetUrl="SELECT urlid,usepop,useturl,furls,urltype FROM url WHERE userid={$this->userid};";
			$result=@mysql_query($qGetUrl,$this->link);
			$row=@mysql_fetch_array($result,MYSQL_ASSOC);
			while ($row) {
				$urlid=$row['urlid'];
				$usepop=$row['usepop'];
				$furls=$row['furls'];
				$useturl=$row['useturl'] & 0x00ff;
				$urltype=$row['urltype']>SVC_IP8? $row['urltype'] : SVC_IP8;
				//更新数据库
				@mysql_query("UPDATE url_odrs SET sday= TIMESTAMPDIFF(MINUTE,now(),etime),  status=IF(sday > 0,status,0) WHERE urlid=$urlid ;",$this->link);
				@mysql_query("UPDATE url_odrs SET sday=IF( sday > 0,sday,0) WHERE urlid=$urlid;",$this->link);
				//@mysql_query("DELETE FROM url_odrs WHERE urlid=$urlid AND DATEDIFF(etime,now())<0 ;",$this->link);
				$odrResult=@mysql_query("SELECT odrid,TIMESTAMPDIFF(MINUTE,now(),etime) days,svcid FROM url_odrs WHERE urlid=$urlid;",$this->link);
				if(mysql_num_rows($odrResult)!=0){
					$odrRow=@mysql_fetch_array($odrResult,MYSQL_ASSOC);
					$odrid=$odrRow['odrid'];
					if($odrRow['days']<0){
						//服务到期
						if($odrRow['svcid']==10){
							//优化服务到期，修改URL的设置信息
							$usepop= $usepop > 5 ? 5 : $usepop;
							$useturl= $useturl > 1 ? (1 | 0x0100) : $useturl;
							$newFurls=explode('|',$furls);
							if(count($newFurls) > 2){
								$furls="{$newFurls[0]}|{$newFurls[1]}";
							}
							//恢复URL 优设置
							@mysql_query("UPDATE url_odrs SET urltype=$urltype,usepop=$usepop,furls='$furls',useturl=$useturl WHERE urlid=$urlid;",$this->link);
							//删除该url_odrs 记录
							@mysql_query("DELETE FROM url_odrs WHERE odrid=$odrid ;",$this->link);
						}else{
							//代挂服务到期,修改网站的urltype
							$utype=SVC_IP8;
							@mysql_query("UPDATE url SET online=0,urltype=$utype WHERE urlid=$urlid;",$this->link);
							//删除该url_odrs记录
							@mysql_query("DELETE FROM url_odrs WHERE odrid=$odrid;",$this->link);
						}
					}
				}
				$row=@mysql_fetch_array($result,MYSQL_ASSOC);
			}
		}
		public function loadUrlInfo(){
			$this->updateDB();
			/*
			 * URL 信息
			 * urlid url name urltype furls turl usefurl useturl 
			 * usepop rtime ltime free clickother clickself tdclick online 
			 */
			$urlinfo="urlid,url,name,urltype,furls,turl,usefurl,useturl,usepop,rtime,ltime,free,clickother,clickself,tdclick,online";
			/*
			 * URL orders信息
			 * odrid urlid status sday svcid dayprice btime etime 
			 */
			$odrinfo="odrid,urlid,status,sday,svcid,dayprice price,btime,etime";
			
			$tdonline=0;
			$this->response['urls']=Array();
			$tdclick=array();
			$odrs=array();
			$url=array();
			$url['odrs']=array();
			$qUrl="SELECT $urlinfo FROM url WHERE userid='".$this->userid."';";
			$qOdrs="SELECT $odrinfo FROM url_odrs WHERE urlid='";
			$urltype=SVC_IP8;
			$result=@mysql_query($qUrl,$this->link) or die('{"error":-1,"msg":"'.mysql_error().'"}');

			while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
				foreach ($row as $key=>$value)
					$url[$key]=$value;
				if($row['online']==1)
					$tdonline++;
				if(is_null($url['furls'])){
					$url['furls']=" ";
				}
				$odrs_result=@mysql_query($qOdrs.$row['urlid']."';",$this->link);
				if(mysql_num_rows($odrs_result)!=0){
					while ($odrs_row=mysql_fetch_array($odrs_result,MYSQL_ASSOC)){
						foreach ($odrs_row as $k=>$v)
							$odrs[$k]=$v;
							/*
						//更新服务状态
						//$sday=$this->getSday($odrs['etime']);
						//UPDATE url_odrs SET sday=DATEDIFF(etime,now())*24*60,  status=IF(sday <> 0,status,0);
						@mysql_query("UPDATE url_odrs SET sday= TIMESTAMPDIFF(MINUTE,now(),etime),  status=IF(sday > 0,status,0) WHERE odrid={$odrs['odrid']} ;",$this->link);
						@mysql_query("UPDATE url_odrs SET sday=IF( sday > 0,sday,0) WHERE odrid={$odrs['odrid']};",$this->link);
						//$odrs['sday']=$sday;
						//当前服务时间结束
						if($odrs['sday'] <= 0){
							//$url['online']=0;
							@mysql_query("DELETE url_odrs WHERE urlid={$odrs['urlid']} AND odrid={$odrs['odrid']};",$this->link);
							if($odrs['svcid'] != 10){
								//设置URL 离线
								@mysql_query("UPDATE url SET online=0,urltype=$urltype WHERE urlid={$odrs['urlid']};",$this->link);
								//$tdonline--;
							}
							if($odrs['svcid'] == 10){
								//如果是优化服务到期 就更新URL
								$qGetInfo="SELECT furls,usefurl,usepop,useturl FROM url WHERE urlid={$odrs['urlid']} AND userid={$this->userid};";
								$result1=mysql_query($qGetInfo,$this->link);
								if(mysql_num_rows($result1) != 0){
									$row=mysql_fetch_array($result1,MYSQL_ASSOC);
									if($row['usefurl'] !=0){
										$newFurls=$row['furls'];
										$furls=explode('|',$row['furls']);
										if(count($furls)>2){
											$newFurls="{$furls[0]}|{$furls[1]}";
										}
										@mysql_query("UPDATE url SET furls='$newFurls' WHERE urlid={$odrs['urlid']};",$this->link);
									}
									if($row['usepop'] !=0){
										@mysql_query("UPDATE url SET usepop=5 WHERE urlid={$odrs['urlid']};",$this->link);
									}
									if($row['useturl'] !=0){
										$useturl=1 & 0X00FF;
										@mysql_query("UPDATE url SET useturl=$useturl WHERE urlid={$odrs['urlid']};",$this->link);
									}
								}
							}
							//
							$odrs=array();
						} */
						$url['odrs'][]=$odrs;
						$odrs=array();
					}
				}else{
					$url['odrs']=array();
				}
				$this->response['urls'][]=$url;
				$url=array();
			}
			$this->response['error']=0;
			$this->response['tdonline']=$tdonline;
			$this->response['mysqlerror']=mysql_error();
			return $this->response;
		}
		
		public function addUrlOnline( $url, $name, $tid, $level){
			//获取当前用户能添加的网址数量以及当前用户已添加网址数量
			$lmt[]=LV1_URLLIMIT;
			$lmt[]=LV2_URLLIMIT;
			$lmt[]=LV3_URLLIMIT;
			$lmt[]=LV4_URLLIMIT;
			$lmt[]=LV5_URLLIMIT;
			$qGetUrlCount="SELECT urlid,name FROM url WHERE userid=".$this->userid.";";
			$result=mysql_query($qGetUrlCount,$this->link);
			$count=mysql_num_rows($result) + 1;
			if($count > $lmt[$level]){
				return array("error"=>-4,"msg"=>"当前等级添加网址已达上限！");
			}
			$url=stripslashes($url);
			$qUrlExist="SELECT urlid,userid FROM url WHERE url='$url';";
			$result=mysql_query($qUrlExist,$this->link);
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			if($row){
				//存在该网址	判断该网址是否属于同一个用户 是返回URL信息        否 返回 错误
				if($this->userid == $row['userid'] || $row['userid'] == 9999){
					$urlid=$row['urlid'];
					$userid=$row['userid'];
					$fields="urlid,url,name,urltype,furls,turl,usefurl,useturl,usepop,rtime,ltime,free,clickother,clickself,tdclick,online";
					$qGetUrl="SELECT $fields FROM url WHERE urlid=$urlid;";
					$result=mysql_query($qGetUrl,$this->link);
					$row=mysql_fetch_array($result,MYSQL_ASSOC);
					foreach ($row as $key => $value) {
						$urlInfo[$key]=$value;
					}
					$odrs=array();
					$qGetOdrs="SELECT odrid,status,sday,dayprice,btime,etime FROM url_odrs WHERE urlid=$urlid;";
					$result=mysql_query($qGetOdrs,$this->link);
					while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
						$odr=array();
						foreach ($row as $key=>$value) {
							$odr[$key]=$value;
						}
						$odrs[]=$odr;
					}
					$urlInfo['odrs']=$odrs;
					$urlInfo['error']=0;
					if($userid==9999){
						//如果是匿名网址 就修改它的userid
						@mysql_query("UPDATE url SET name='$name',userid='".$this->userid."' WHERE urlid=$urlid ;",$this->link);
					}
					return $urlInfo;
				}
				return array("error"=>-22,"msg"=>"已有用户添加该网址，请联系管理员添加，用户ID：".$row['userid']);
			}
			$odrs=array();
			$urltype=SVC_IP8;
			$ip=getInvokeIP();
			$tcount=intval(SVC_IP8/24);
$flowcrl="0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100|0:$tcount:100";
			$qAddUrl="INSERT INTO url(userid,url,name,rtime,ltime,urltype,free,furls,ip,flowcrl) VALUES('".$this->userid."','$url','$name',now(),now(),$urltype,1,'','$ip','$flowcrl');";
			$qUrl="SELECT LAST_INSERT_ID()";
			
			$result=@mysql_query("SELECT url FROM filter;");
			while ($row=@mysql_fetch_array($result,MYSQL_ASSOC)){
				//判断网址是否合法
				$match=preg_match("@".$row['url']."@",$url);
				if(0!=$match){
					return $this->response=array("error"=>-22,);
				}
			}
			if(@mysql_query($qAddUrl,$this->link)){
				$result=mysql_query($qUrl,$this->link);
				$row=mysql_fetch_array($result);
				$urlid=$row['LAST_INSERT_ID()'];
				$result=@mysql_query("SELECT * FROM url WHERE urlid='$urlid';",$this->link);
				$row=mysql_fetch_array($result,MYSQL_ASSOC);
				foreach ($row as $key =>$value)
					$this->response[$key]=$value;
				$this->response['error']=0;
				$this->response['odrs']=array();
				//表admins counturl字段 加1
				$qAddUrlCount="UPDATE admins SET counturl=counturl + 1 WHERE name='admin';";
				mysql_query($qAddUrlCount,$this->link);	
			}else{
				$this->response=array("error"=>-1,);
			}
			return $this->response;

		}
		
		public function controlUrlOnline( $free, $urlid){
			$qUrl="UPDATE ".TB_URL." SET free='".$free."' WHERE urlid='".$urlid."';";
			if(mysql_query($qUrl,$this->link)){
				$this->response['error']=0;
			}else{
				$this->response['error']=-1;
			}
			return $this->response;
		}
		
		public function cloneSetInfo( $srcurlid, $desurlid){
			//克隆URL 设置
			$qHasStartSVC="SELECT svcid,status FROM url_odrs WHERE urlid=$srcurlid;";
			$result=mysql_query($qHasStartSVC,$this->link);
			$bHasStartSVC=mysql_num_rows($result);
			$qGetSrcUrlInfo="SELECT furls,usefurl,turl,useturl,usepop FROM url WHERE urlid=$srcurlid;";
			$result=mysql_query($qGetSrcUrlInfo,$this->link);
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			if($row && $bHasStartSVC==0){
				//stripslashes(
				$furls=$row['furls'];
				$turl=$row['turl'];
				$usepop=$row['usepop'];
				$usefurl=$row['usefurl'];
				$useturl=$row['useturl'];
				$qSetDesUrlInfo="UPDATE url SET usepop=$usepop,turl='$turl',furls='$furls',useturl=$useturl,usefurl=$usefurl WHERE urlid=$desurlid ; ";
				mysql_query($qSetDesUrlInfo,$this->link);
			}
			$this->response=array("error"=>0,"msg"=>mysql_error());
			return $this->response;
		}
		
		public function setFlowLine($urlid, $cid, $hrs){
			$hrs1=implode('|',$hrs);

			//写入时段数据开始			
			$result=mysql_query("select urltype,flowcrl from ".TB_FLOWER." where urlid=$urlid;",$this->$link);
			if($row=mysql_fetch_array($result,MYSQL_ASSOC))
			{
				$urltype=$row['urltype'];
				$flowcrl=$row['flowcrl'];
			}

			$arraylist=explode("|",$flowcrl);
			for($i=0;$i<sizeof($arraylist);$i++) 
	 		{	
				$arrayhctrl=explode(":",$arraylist[$i]);
				$limitcount=intval($urltype/24);
				$flowcrl=$Aflowcrl."|".$arrayhctrl[0].":".$limitcount.":".$hrs1[i];
 			}
			$flowcrl=substr($flowcrl,1,strlen($flowcrl));
			$fupdate="update ".TB_FLOWER." set flowcrl='$flowcrl' where urlid='$urlid';";
			@mysql_query($fupdate,$this->$link);
			//写入时段数据结束

			//$qFlow="UPDATE ".TB_FLOWER." SET hrs='$hrs1' WHERE cid='$urlid';";
			//$result=@mysql_query("SELECT cid FROM ".TB_FLOWER." WHERE cid='$urlid';");
			/if (mysql_num_rows($result)==0){
			//	@mysql_query("INSERT INTO ".TB_FLOWER."(cid,hrs) VALUES('$urlid','$hrs1');",$this->link);
			//}else{
			//	@mysql_query($qFlow,$this->link) or die('{"error":-1}');
			//}
			$this->response=array("error"=>0,);
			return $this->response;
		}
		
		public function getControUrl($urlid)
		{
			$result=mysql_query("select flowcrl from ".TB_FLOWER." where urlid=$urlid;",$this->$link);
			if($row=mysql_fetch_array($result,MYSQL_ASSOC))
			{
				$flowcrl=$row['flowcrl'];
			}
			$arraylist=explode("|",$flowcrl);
			
			for($i=0;$i<sizeof($arraylist);$i++)
	 		{	
				$arrayhctrl=explode(":",$arraylist[$i]);
				$Aflowctrl=$Aflowctrl."|".$arraylist[2];
 			}
			$Aflowctrl=substr($Aflowctrl,1,strlen($Aflowctrl));

			$this->response['hrs']=explode('|',$Aflowctrl);
			//$this->response['cid']=$urlid;
			$this->response['error']=0;
			return $this->response;
		}
		
		Public function setOptimize($urlid, $usepop, $turl, $useturl, $furls, $usefurl){
			$furls=stripcslashes($furls);
			$turl=stripcslashes($turl);
			$userid=$this->userid;
			$qGetUrl="SELECT url FROM url WHERE urlid=$urlid AND userid=$userid;";
			$result=mysql_query($qGetUrl,$this->link);
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$url=$row['url'];
			/*
			 * 过滤网址和目标网址相同
			$match1=preg_match("@$url@",$turl);
			$match2=preg_match("@$turl@",$url);
			if($match1 || $match2){
				$turl="";
			}*/
			$qUrl="UPDATE url SET usepop=$usepop ,useturl=$useturl ,usefurl=$usefurl, turl='$turl', furls='$furls' WHERE urlid=$urlid;";
			@mysql_query($qUrl,$this->link) or die('{"error":-1,"msg":"'.mysql_error().'"}');
			$result=@mysql_query("SELECT urlid,url,name,urltype,furls,usefurl,turl,useturl,usepop,rtime,ltime,free,clickother,clickself,tdclick,online FROM ".TB_URL." WHERE urlid='$urlid';",$this->link);
			$row=@mysql_fetch_array($result,MYSQL_ASSOC);
			foreach ($row as $key=> $value) {
				$this->response[$key]=$value;
			}
			$this->response["error"]=0;
			return $this->response;
		}
		
		public function startTrustee($urlid, $odrid, $etime, $day, $nowpay, $svcid){
			/*
			 * 存在严重问题 ，已不再使用
			 */
			
			//服务单价配置
			$price['10']=160;//优化
			$price['8']=80;
			$price['103']=160;
			$price['106']=180;
			$price['108']=200;
			$price['110']=220;
			$price['115']=240;
			$price['120']=260;
			$this->response=array();
			$date=date_parse($etime);
			if($odrid!=0){
				//续费代挂服务
				
			}else{
				//开通代挂服务
				@mysql_query("INSERT INTO ".TB_ORDER."(userid,urlid,) VALUES();",$this->link);
			}
			return $this->response;
		}
		
		public function calculationPay($day, $nowpay, $odrid, $etime, $svcid){
			//服务单价配置
			$price['10']=CFG_OPTIMIZE;//优化
			$price['8']=CFG_IP8;
			$price['101']=CFG_IP101;
			$price['103']=CFG_IP103;
			$price['106']=CFG_IP106;
			$price['108']=CFG_IP108;
			$price['110']=CFG_IP110;
			$price['115']=CFG_IP115;
			$price['120']=CFG_IP120;
			if ($nowpay==1){
				$response['paymoney']=$day * $price[$svcid];
			}else{
				$response['paymoney']=$day * $price[$svcid];
			}
			$response['pday']=$day * PDAY/100;
			$response['btime']=date('Y-m-d',time()+24*60*60);
			$response['etime']=date('Y-m-d',time() +($day+1)*24*60*60);
			$response['error']=0;
			$response['allmoney']=$response['paymoney'];
			return $response;
			
		}
		
		public function trustReback($urlid, $odrid, $svcid){
			//服务单价配置
			$price['10']=CFG_OPTIMIZE;//优化
			$price['8']=CFG_IP8;
			$price['103']=CFG_IP103;
			$price['106']=CFG_IP106;
			$price['108']=CFG_IP108;
			$price['110']=CFG_IP110;
			$price['115']=CFG_IP115;
			$price['120']=CFG_IP120;
			
			$result=mysql_query("SELECT DATEDIFF(etime,now()) day,dayprice,etime FROM url_odrs WHERE urlid=$urlid AND odrid=$odrid AND svcid=$svcid;",$this->link);
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$etime=$row['etime'];
			$dayprice=$row['dayprice'];
			$day=$row['day']/(PDAY/100+1);
			$reback=$dayprice*floor($day-1);
			$urltype=SVC_IP8;
			$useturl=1 & 0X00FF;
			mysql_query("UPDATE account SET money=money + $reback WHERE userid={$this->userid};",$this->link);
			mysql_query("INSERT INTO orders(userid,urlid,val,otime,svcid,otype) VALUES($this->userid,$urlid,$reback,now(),$svcid,0);",$this->link);
			mysql_query("UPDATE url SET urltype=$urltype,online=0,usepop=1,useturl=$useturl WHERE urlid=$urlid ;",$this->link);
			//恢复到默认
			if($svcid==10){
				$qGetInfo="SELECT furls,usefurl FROM url WHERE urlid=$urlid AND userid={$this->userid};";
				$result=mysql_query($qGetInfo,$this->link);
				if(mysql_num_rows($result) != 0){
					$row=mysql_fetch_array($result,MYSQL_ASSOC);
					if($row['usefurl'] !=0){
						$newFurls=$row['furls'];
						$furls=explode("|",$row['furls']);
						if(count($furls)>2){
							$newFurls=$furls[0]."|".$furls[1];
						}
						@mysql_query("UPDATE url SET furls='$newFurls' WHERE urlid=$urlid;",$this->link);
					}
				}
			}
			
			if(@mysql_query("DELETE FROM url_odrs WHERE urlid=$urlid AND odrid='$odrid';",$this->link)){
				 $this->response=array(
						"error"=>0,
						"bdelete"=>1,
						"etime"=>date("Y-m-d",time()),
						"sday"=>0,
					);
				//更新admin的用户消费金额记录
				@mysql_query("UPDATE admins SET countexp=countexp-$reback WHERE name='admin';",$this->link);
				return $this->response;
			}else{
				 $this->response=array("error"=>-1,);
				 return $this->response;
			}
		}
		
		public function startOptimize($urlid, $odrid, $etime, $day, $nowpay, $svcid){
			//服务单价配置
			$price['10']=CFG_OPTIMIZE;//优化
			$price['8']=CFG_IP8;
			$price['101']=CFG_IP101;
			$price['103']=CFG_IP103;
			$price['106']=CFG_IP106;
			$price['108']=CFG_IP108;
			$price['110']=CFG_IP110;
			$price['115']=CFG_IP115;
			$price['120']=CFG_IP120;
			
			$IPs['8']=SVC_IP8;
			$IPs['10']=SVC_OPTIMIZE;
			$IPs['101']=SVC_IP101;
			$IPs['103']=SVC_IP103;
			$IPs['106']=SVC_IP106;
			$IPs['108']=SVC_IP108;
			$IPs['110']=SVC_IP110;
			$IPs['115']=SVC_IP115;
			$IPs['120']=SVC_IP120;
			
			if($odrid==0){
				//开通
				$result=mysql_query("SELECT money FROM account WHERE userid='$this->userid';",$this->link);
				$row=mysql_fetch_array($result,MYSQL_ASSOC);
				$allMoney=$row['money'];
				$money=$price[$svcid]*$day;
				$bae=$allMoney-$money;
				$pday=floor($day * PDAY / 100);
				$sday=($pday+$day)*24*60;
				$day=$pday+$day+1;
				if ($allMoney >= $money){
					if(@mysql_query("INSERT INTO orders(urlid,userid,val,otime,svcid,otype,bae) VALUES('$urlid','$this->userid','$money',now(),'$svcid',3,'$bae');",$this->link)){
						$result=@mysql_query("SELECT LAST_INSERT_ID();",$this->link);
						$row=@mysql_fetch_array($result,MYSQL_ASSOC);
						$odrid=$row['LAST_INSERT_ID()'];
						@mysql_query("INSERT INTO url_odrs(odrid,urlid,etime,status,sday,svcid,dayprice,btime) VALUES($odrid,$urlid,DATE(DATE_ADD(now(),INTERVAL $day DAY )),1+$nowpay,$sday,$svcid,{$price[$svcid]},now());",$this->link );
						echo mysql_error();
						@mysql_query("UPDATE account SET money=money-$money WHERE userid='$this->userid';",$this->link);
						$result=mysql_query("SELECT odrid,btime,etime,status,dayprice, TIMESTAMPDIFF(MINUTE,NOW(),etime) sday FROM url_odrs WHERE urlid=$urlid AND svcid=$svcid ;",$this->link);
						$row=mysql_fetch_array($result,MYSQL_ASSOC);
						
						/*
						 * 设置已开通代挂的网址在线,开启分享
						 */
						if($svcid!=10 && $svcid != 8){
							$qSetOnline="UPDATE url SET online=1,free=1 WHERE urlid=$urlid;";
							mysql_query($qSetOnline,$this->link);
						}
						
						$this->response=array(
							"error"=>0,
							"odrid"=>$row['odrid'],
							"status"=>$row['status'],
							"dayprice"=>$row['dayprice'],
							"paymoney"=>$money,
							"pday"=>$pday,
							"btime"=>$row['btime'],
							"etime"=>$row['etime'],
							"sday"=>$row['sday'],
							"allmoney"=>$money,
						);
						//修改urltype 
						$urltype=$IPs[$svcid];
						@mysql_query("UPDATE url SET urltype=$urltype WHERE urlid=$urlid AND urltype<$urltype ;",$this->link);
						//修改用户的最近交易时间
						@mysql_query("UPDATE users SET otime=now() WHERE userid=$this->userid;",$this->link);
						//更新admin的用户消费金额
						@mysql_query("UPDATE admins SET countexp=countexp+$money WHERE name='admin';",$this->link);
					}	
				}else{
					$this->response['error']=-1;
				}
			}else{
				//续费
				$money=$price[$svcid]*$day;
				if($this->deductMoney($money)){
					$result=mysql_query("SELECT sday,etime FROM url_odrs WHERE odrid=$odrid AND urlid=$urlid;",$this->link);
					$row=mysql_fetch_array($result,MYSQL_ASSOC);
					$pday=floor($day * PDAY/100);
					$sday=$row['sday']+($day+$pday)*24*60;
					$etime=$this->calculationDay($row['etime'],$day+$pday);
					
					$result=@mysql_query("SELECT money FROM account WHERE userid='$this->userid';",$this->link);
					$row=@mysql_fetch_array($result,MYSQL_ASSOC);
					$bae=$row['money'];
					$userid=$this->userid;
					@mysql_query("INSERT INTO orders(urlid,userid,val,otime,svcid,otype,bae) VALUES($urlid,$userid,$money,now(),$svcid,3,$bae);",$this->link);					
					$newOdrid=mysql_insert_id($this->link);
					@mysql_query("UPDATE url_odrs SET odrid=$newOdrid,sday=$sday,etime='$etime',status=$nowpay+1 WHERE odrid=$odrid;",$this->link);
					
					if($svcid!=10 && $svcid != 8){
						$qSetOnline="UPDATE url SET online=1,free=1 WHERE urlid=$urlid;";
						mysql_query($qSetOnline,$this->link);
					}
					
					
					$result=mysql_query("SELECT odrid,btime,etime,status,dayprice,sday FROM url_odrs WHERE odrid='$newOdrid';",$this->link);
					$row=mysql_fetch_array($result,MYSQL_ASSOC);
					$this->response=array(
						"error"=>0,
						"svcid"=>$svcid,
						"dayprice"=>$price[$svcid],
						"status"=>$row['status'],
						"paymoney"=>$money,
						"pday"=>$pday,
						"btime"=>$row['btime'],
						"etime"=>$row['etime'],
						"odrid"=>$row['odrid'],
						"sday"=>$row['sday'],
						"allmoney"=>$money,
					);
					//更新admins的用户消费金额
					@mysql_query("UPDATE admins SET countexp=countexp+$money WHERE name='admin';",$this->link);
				}else{
					$this->response['error']=-1;
					$this->response['msg']="账户余额不足！";
				}
			}
			return $this->response;
		}
		
		public function doDelect($urlid){
			@mysql_query("DELETE FROM url_odrs WHERE urlid=$urlid;") or die('{"error":-1}');
			@mysql_query("DELETE FROM url WHERE urlid=$urlid;") or die('{"error":-1}');
			$this->response=array("error"=>0,);
			return $this->response;
		}
		
		private function deductMoney($money){
			$result=mysql_query("SELECT money FROM account WHERE userid='".$this->userid."';",$this->link);
			$row=mysql_fetch_array($result);
			if($money<$row['money']){
				$money=$row['money']-$money;
				@mysql_query("UPDATE account SET money=$money WHERE userid='$this->userid';",$this->link);
				return true;
			}
			return false;
		}
		private function calculationDay($datetime,$offsetdays){
			
			/*
			 * 函数功能： 根据参数day(date/time格式的字符串) 计算offset 天后的日期
			 * 
			 * data_parse() 根据传的参数(date/time格式的字符串) 返回包含时间信息的数组 
			 * mktime() 根据参数传递的信息返回一个timestamp
			 * strtotime() 将传的参数(date/time格式的字符串) 返回一个timestamp
			 */
			
			$timestamp=strtotime($datetime);
			
			return date("Y-m-d G:i:s",$timestamp + $offsetdays * 24 * 60 * 60);
			/*
			$date=date_parse($day);
			$stamp=mktime(0,0,0,$date['month'],$date['day'],$date['year']);
			return date("Y-m-d",$stamp+$offset*24*60*60);
			*/
		}
		public function getQuestion($name,$email){
			$q="SELECT question FROM users WHERE name='$name' AND email='$email';";
			$result=@mysql_query($q,$this->link) or die('{"error":-1}');
			if( @mysql_num_rows($result)!=0){
				$row=mysql_fetch_array($result);
				$this->response=array("error"=>0,
							"question"=>$row['question'],);
				return $this->response;
			}else{
				$this->response=array("error"=>-1,);
				return $this->response;
			}
		} 
		public function checkUserExist($name){
			$q="SELECT userid FROM users WHERE name='$name';";
			$result=@mysql_query($q,$this->link);
			if(@mysql_num_rows($result)==0){
				$this->response=array("error"=>0,"good"=>1,);
				return $this->response;
			}else{
				$this->response=array("error"=>0,"good"=>0,);
				return $this->response;
			}
			
		}
		public function regNewUser($name,$pwd,$email,$question,$answer){
			$exist=$this->checkUserExist($name);
			if(0==$exist["good"])
				die('{"error":-2,"msg":"用户已存在"}');
			$ePwd=md5($pwd);
			$eAnswer=md5($answer);
			$qUser="INSERT INTO users(name,psw,question,answer,email,rtime) VALUES('$name','$ePwd','$question','$eAnswer','$email',now());";
			$result=mysql_query($qUser,$this->link) or die('{"error":-2}');
			$result=@mysql_query("SELECT last_insert_id()",$this->link);
			$row=@mysql_fetch_array($result,MYSQL_ASSOC);
			$userID=$row['last_insert_id()'];
			/*
			 *	测试阶段可打开
			 *   默认为新注册用户充值的金额
			 */
			//$money=1000000;
			$money=0;
			$qAunt="INSERT INTO account (userid,money)VALUES('$userID',$money);";
			@mysql_query($qAunt,$this->link);
			//表admins countuser +1
			@mysql_query("UPDATE admins SET countuser=countser+1 WHERE name='admin'",$this->link);
			return $this->response=array("error"=>0,);
		}
		public function loadImageCheck($len=6){
			//$str='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$str='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$v="";
			for($i=0;$i<$len;$i++){
				$v.=substr($str,rand(0,35),1);
			}
			//$v=substr($str,rand()%78,1).substr($str,rand()%78,1).substr($str,rand()%78,1).substr($str,rand()%78,1);//.substr($str,rand()%78,1).substr($str,rand()%78,1);
			$this->response=array("error"=>0,"ccmd5"=>$v);
			return $this->response;
		}
		public function resetPwd($name,$psw,$email,$answer,$question){
			$eAnswer=md5($answer);
			$ePsw=md5($psw);
			$qBExist="SELECT EXISTS(SELECT userid FROM users WHERE name='$name' AND answer='$eAnswer' AND email='$email' AND question='$question') bexist;";
			$result=mysql_query($qBExist,$this->link);
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$q="UPDATE users SET psw='$ePsw' WHERE name='$name';";
			if($row['bexist']==1){
				mysql_query($q,$this->link);
				$this->response=array("error"=>0,"msg"=>mysql_error());
				return $this->response;
			}else{
				$this->response=array("error"=>-1,"msg"=>mysql_error());
				return $this->response;
			}
		}
		public function userLogin($name,$psw){
			$level[]=0;
			$level[]=GRADESCORE1;
			$level[]=GRADESCORE2;
			$level[]=GRADESCORE3;
			$level[]=GRADESCORE4;
			
			$lscore=TDLOGINSCORE;
			$ePsw=md5($psw);
			$q="SELECT userid FROM users WHERE psw='$ePsw' AND name='$name';";
			
			$result=@mysql_query($q,$this->link);
			if (@mysql_num_rows($result)!=0){
				$row=mysql_fetch_array($result,MYSQL_ASSOC);
				$this->response['userid']=$row['userid'];
				$this->response['error']=0;
				$userid=$this->response['userid'];
				$date=date('Y-m-d G:i:s',time());
				
				$qt="SELECT userid,ltime FROM tdlogin WHERE DATEDIFF(ltime,now())>=0 AND userid=$userid;";
				$result=@mysql_query($qt,$this->link);
				if(@mysql_num_rows($result)==0){
					//今天第一次登入
					@mysql_query("INSERT INTO tdlogin (userid,ltime) VALUES($userid,'$date');",$this->link);
					//查询用户积分
					$result=@mysql_query("SELECT score FROM account WHERE userid='$userid';",$this->link);
					$row=@mysql_fetch_array($result,MYSQL_ASSOC);
					$score= $row['score'] + $lscore;
					for($i=0;$i<4 && $row['score'] > $level[$i];$i++);
					@mysql_query("UPDATE account SET score=$score,level=$i WHERE userid='$userid';",$this->link);					
				}
				return $this->response;
			}else{
				die('{"error":-1}');
			}
		}
		
		public function getMoney($cent){
			$level[]=GRADESCORE1;
			$level[]=GRADESCORE2;
			$level[]=GRADESCORE3;
			$level[]=GRADESCORE4;
			
			$s=SCORE * 0.01 ;//奖励积分为充值金额的倍
			$p=PMONEY * 0.01;//赠送金额为充值金额的1/4
			$l=LMONEY * 0.01;//奖励金额为充值金额的1/4
			$result=@mysql_query("SELECT score FROM ".TB_ACCOUNT." WHERE userid='$this->userid';",$this->link);
			$row=mysql_fetch_array($result,MYSQL_ASSOC);
			$to_score=$row['score']+$cent/100 * $s;
			for($i=0;$i<4 && $to_score>$level[$i];$i++);
			$this->response=array(
					"to_score"=>$to_score,
					"pm"=>$cent *$p,
					"lm"=>$cent *$l,
					"to_level"=>$i,
				);
				return $this->response;
			
		}
		
		public function userRecord($page,$pagect){
			$paylog=array();
			$result=@mysql_query("SELECT otime,otype,svcid,urlid,val,bae,pm,lm FROM orders WHERE userid='$this->userid';",$this->link);
			$pages=@mysql_num_rows($result);
			if($page==0){
				$this->response=array("error"=>0,"pages"=>$pages,"paylog"=>array(),);
				if($pages!=0){
					//交易记录数不为0
					$page=0;
					while ($page<$pagect && $row=@mysql_fetch_array($result,MYSQL_ASSOC)){
						foreach ($row as $key=>$value)
							$paylog[$key]=$value;
						$this->response['paylog'][]=$paylog;
						$paylog=array();
						$page++;
					}
				}else{
				//交易记录为0 
				}
			}else{
				$recordStart=$page * $pagect;
				$this->response=array("error"=>0,"pages"=>$pages,"paylog"=>array(),);
				$result=@mysql_query("SELECT otime,otype,svcid,urlid,val,bae,pm,lm FROM orders WHERE userid=$this->userid LIMIT $recordStart,$pagect ;",$this->link);
				//$pages=@mysql_num_rows($result);
				while ($row=@mysql_fetch_array($result,MYSQL_ASSOC)){
					$paylog=array();
					foreach ($row as $key=> $value)
						$paylog[$key]=$value;
					$this->response['paylog'][]=$paylog;
				}
			}
			return $this->response;
		}
		public function modPassword($newpwd,$oldpwd){
			$qU="UPDATE users SET psw=md5($newpwd) WHERE psw=md5('$oldpwd') AND userid=$this->userid;";
			if(mysql_query($qU,$this->link)){
				$this->response['error']=0;
				return $this->response;
			}else{
				return array("error"=>-2,"msg"=>mysql_error());
			}
		}
		private function getSday($endtime){
			/*
			 * strtotime(string) 返回给定的日期的timestamp; 
			 */
			$sday=(strtotime($endtime) - time()) / 60;
			return $sday;
		}
		public function __destruct(){
			mysql_close($this->link);
		}
		
	}
?>