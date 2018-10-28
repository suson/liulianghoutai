<?php
########################################################
# Date: 2011-04-04										#
# Author: Mr.Jackin									#
# Description: Implements Interface IService.interface	#
########################################################


define ( 'CFG_PMONEY_RATE', 0.1 );
define ( 'CFG_LMONEY_RATE', 0.1 );
define ( 'CFG_PDAY_RATE', 0.1 );
define ( 'CFG_SERVICE_PRICE_8', 160 );
define ( 'CFG_SERVICE_PRICE_10', 160 );
define ( 'CFG_SERVICE_PRICE_101', 160 );
define ( 'CFG_SERVICE_PRICE_103', 260 );
define ( 'CFG_SERVICE_PRICE_106', 520 );
define ( 'CFG_SERVICE_PRICE_110', 980 );

define ( 'CFG_SERVICE_URLTYPE_DEFAULT', 800 );
define ( 'CFG_SERVICE_URLTYPE_8', 800 );
define ( 'CFG_SERVICE_URLTYPE_10', 1000 );
define ( 'CFG_SERVICE_URLTYPE_101', 1100 );
define ( 'CFG_SERVICE_URLTYPE_103', 1300 );
define ( 'CFG_SERVICE_URLTYPE_106', 1600 );
define ( 'CFG_SERVICE_URLTYPE_110', 2000 );

//require_once ('alipayto.php');
class CService {
	public function __construct() {
	}
	public function __destruct() {
	}
	
	/*
 * 定义函数 startService
 *  开通服务, 参数$cfg = (urlid,odrid,etime,day,nowpay,svcid)
 *  失败返回 false, 成功返回 array(odrid,status,dayprice,paymoney,pday,btime,etime,sday,allmoney)
 */
	public static function startService() {
		global $dbcn;
		$services = array ();
		$info = json_decode ( $_POST ['i'], true );
		$urlid = $info ['urlid'];
		$svcid = $info ['svcid'];
		$status = $info ['nowpay'] + 1;
		if (! checkUserOnline () || ! chkURLwithUser ( $urlid )) {
			return array ('error' => - 1 );
		}
		if (! in_array ( $svcid, array (8, 10, 101, 103, 106, 110 ) )) {
			return array ('error' => - 1 );
		}
		switch ($svcid) {
			case 8 :
				$price = CFG_SERVICE_PRICE_8;
				$urltype = CFG_SERVICE_URLTYPE_8;
				break;
			case 10 :
				$price = CFG_SERVICE_PRICE_10;
				$urltype = CFG_SERVICE_URLTYPE_10;
				break;
			case 101 :
				$price = CFG_SERVICE_PRICE_101;
				$urltype = CFG_SERVICE_URLTYPE_101;
				break;
			case 103 :
				$price = CFG_SERVICE_PRICE_103;
				$urltype = CFG_SERVICE_URLTYPE_103;
				break;
			case 106 :
				$price = CFG_SERVICE_PRICE_106;
				$urltype = CFG_SERVICE_URLTYPE_106;
				break;
			case 110 :
				$price = CFG_SERVICE_PRICE_110;
				$urltype = CFG_SERVICE_URLTYPE_110;
				break;
			default :
				$price = 1000000;
				$urltype = 0;
		}
		$allMoney = $info ['day'] * $price;
		$pmoney = $allMoney * CFG_PMONEY_RATE / 100;
		$allMoney -= $pmoney;
		$pday = floor ( $info ['day'] * CFG_PDAY_RATE );
		$days = floor ( $info ['day'] * (1 + CFG_PDAY_RATE) );
		if ($allMoney > $_SESSION ['money']) {
			return array ('error' => - 1 );
		}
		$money = $_SESSION ['money'] - $allMoney;
		$_SESSION ['money'] = $money;
		$pmoney += $_SESSION ['pmoney'];
		$_SESSION ['pmoney'] = $pmoney;
		$qUpDateAccount = sprintf ( "UPDATE `Jackin_account` SET `money`=%d,`pmoney`=%d WHERE `userid`=%d AND `money`>%d;", $money, $pmoney, $_SESSION ['userid'], $allMoney );
		if (! $dbcn->query ( $qUpDateAccount ) || $dbcn->affected_rows != 1) {
			return array ('error' => - 1, 'msg' => $dbcn->error, 'affected_rows' => $dbcn->affected_rows );
		}
		$qHasService = sprintf ( "SELECT `odrid`,`status`,`sday`,`svcid`,`btime`,`etime` FROM `Jackin_url_odrs` WHERE `urlid`='%d' AND `status`<>0  ORDER BY `svcid`", $info ['urlid'] );
		$result = $dbcn->query ( $qHasService );
		if (! $result || $result->num_rows > 2) {
			return array ('error' => - 1 );
		}
		$num_rows = $result->num_rows;
		if ($num_rows > 0) {
			//续费服务 
			for($i = $num_rows; $i > 0; $i --) {
				$services [] = $result->fetch_assoc ();
			}
			if ($services [0] ['svcid'] == $svcid || $num_rows == 2 && $svcid == $services [1] ['svcid']) {
				//续费服务
				$service = $services [0] ['svcid'] == $svcid ? $services [0] : $services [1];
				if ($service ['status'] != $status) {
					//支付方式不一样
					return array ('error' => - 1 );
				}
				$btime = $service ['btime'];
				$et = new DateTime ( $service ['etime'] );
				$et = $et->add ( new DateInterval ( sprintf ( 'P%dD', $days ) ) );
				$etime = $et->format ( 'Y-m-d H:i:s' );
				$sday = $service ['sday'] + $days * 1440;
				$dayprice = $price;
			} elseif ($num_rows == 1 && ($services [0] ['svcid'] + $svcid) < 200) {
				$btime = date ( 'Y-m-d H:i:s', time () );
				$et = new DateTime ( 'now' );
				$et = $et->add ( new DateInterval ( sprintf ( 'P%dD', $days ) ) );
				$etime = $et->format ( 'Y-m-d H:i:s' );
				$sday = $days * 1440;
				$dayprice = $price;
			} else {
				// 只能开通2种服务 代挂 和 优化
				return array ('error' => - 1 );
			}
		
		} else {
			$btime = date ( 'Y-m-d H:i:s', time () );
			$et = new DateTime ( 'now' );
			$et = $et->add ( new DateInterval ( sprintf ( 'P%dD', $days ) ) );
			$etime = $et->format ( 'Y-m-d H:i:s' );
			$sday = $days * 1440;
			$dayprice = $price;
		}
		$odrid = getOdrId ( $info ['urlid'], $info ['svcid'], $allMoney );
		if (false === $odrid) {
			return array ('error' => - 1 );
		}
		$fields = '`odrid`,`urlid`,`status`,`sday`,`svcid`,`dayprice`,`btime`,`etime`';
		$qAddService = sprintf ( "INSERT INTO `Jackin_url_odrs`(%s) VALUES(%d,%d,%d,%d,%d,%d,'%s','%s')", $fields, $odrid, $urlid, $status, $sday, $svcid, $dayprice, $btime, $etime );
		if (! $dbcn->query ( $qAddService )) {
			return array ('error' => - 1 );
		}
		$qUpdateUrlType = sprintf ( "UPDATE `Jackin_url` SET `urltype`=%d WHERE `urltype` < %d", $urltype, $urltype );
		if (! $dbcn->query ( $qUpdateUrlType )) {
			return array ('error' => - 1 );
		}
		return array ("error" => 0, "odrid" => $odrid, "status" => $status, "dayprice" => $dayprice, "paymoney" => $_SESSION ['money'], "pday" => $pday, "btime" => $btime, "etime" => $etime, "sday" => $sday, "allmoney" => $allMoney );
	}
	public static function getPayMoney() { //K.O.
		$info = json_decode ( $_POST ['i'], true );
		switch ($info ['svcid']) {
			case 8 :
				$price = CFG_SERVICE_PRICE_8;
				break;
			case 10 :
				$price = CFG_SERVICE_PRICE_10;
				break;
			case 101 :
				$price = CFG_SERVICE_PRICE_101;
				break;
			case 103 :
				$price = CFG_SERVICE_PRICE_103;
				break;
			case 106 :
				$price = CFG_SERVICE_PRICE_106;
				break;
			case 110 :
				$price = CFG_SERVICE_PRICE_110;
				break;
			default :
				$price = 1000000;
		}
		$payMoney = isset ( $_SESSION ['money'] ) ? $_SESSION ['money'] : 0;
		$allMoney = $info ['day'] * $price * (1 - CFG_PMONEY_RATE);
		$pday = floor ( $info ['day'] * CFG_PDAY_RATE );
		$day = sprintf ( 'P%dD', $info ['day'] + $pday );
		$now = new DateTime ( 'now' );
		$btime = $now->format ( 'Y-m-d' );
		$now->add ( new DateInterval ( $day ) );
		$etime = $now->format ( 'Y-m-d' );
		return array ('error' => 0, 'paymoney' => $payMoney, 'allmoney' => $allMoney, 'pday' => $pday, 'btime' => $btime, 'etime' => $etime );
	}
	
	/*
 * 定义函数 prepay 
 *  充值, $cent 
 *  失败返回 false, 成功返回 array(to_score, to_level, pm, lm, aplink)
 */
	public function prepay(int $cent) {
		if (! checkUserOnline () || ! isset ( $cent ))
			return false;
		$aplink = alipayto ( $cent / 100 );
		try {
			$to_score = ( int ) ($cent / 100 * $svcfg->moneyscorerate);
			$levelscore = explode ( $this->svcfg->levelupscore );
			$to_level = 0;
			for($i = count ( $levelscore ); $i > 0; $i --) {
				if ($to_score > $levelscore [$i]) {
					$to_level = $i;
					break;
				}
			}
			$pm = $cent * $this->svcfg->pmrate;
			$lm = $cent * $this->svcfg->lmrate;
		} catch ( Exception $e ) {
			return false;
		}
		return array ('to_score' => $to_score, '$to_level' => $to_level, 'pm' => $pm, 'lm' => $lm, 'aplink' => $aplink );
	}
	
	/*
 * 定义函数 getRecord 
 *  获取用户交易记录, 参数 $page= (page, pagect)
 *  失败返回：false, 成功返回: array(pages,paylog=array(otime, otype, svcid, urlid, val, bae, pm, lm))
 */
	public function getRecord() { //o.k
		global $dbcn;
		if (! isset ( $_SESSION ['userid'] )) {
			return array ('error' => - 1 );
		}
		$page = json_decode ( $_POST, true );
		$mysqli = $dbcn;
		$paylog = array ();
		$q = sprintf ( "SELECT count(*) pages FROM `Jackin_orders` WHERE `userid`='%d';", $_SESSION ['userid'] );
		$result = $dbcn->query ( $q );
		if (! $result) {
			return array ('error' => - 1, 'pages' => 0, 'paylog' => array () );
		}
		$row = $result->fetch_assoc ();
		$pages = $row ['pages'];
		$q = sprintf ( "SELECT `otime`,`otype`,`svcid`,`urlid`,`val`,`bae`,`pm`,`lm` FROM Jackin_orders WHERE `userid`='%d' LIMIT %d,%d;", $_SESSION ['userid'], $page ['page'] * $page ['pagect'], $page ['pagect'] );
		$result = $dbcn->query ( $q );
		if (! $result || 0 == $pages) {
			return array ('error' => - 1, 'pages' => 0, 'paylog' => array () );
		}
		
		for($i = $result->num_rows; $i > 0; $i --) {
			$row = $result->fetch_assoc ();
			array_push ( $paylog, $row );
		}
		return array ('pages' => $pages, 'paylog' => $paylog );
	}
	
	/*
 * 定义函数： rebackService
 * 退订服务, 参数 $svc= (urlid	odrid	svcid)
 * 失败返回 false, 成功返回 array(bdelete,	etime, sday)
 */
	public static function rebackService() {
		global $dbcn;
		$info = json_decode ( $_POST ['i'], true );
		$fields = '`sday`,`dayprice`,`btime`,`etime`,status,DATEDIFF(`etime`,`btime`) day,TIMESTAMPDIFF(MINUTE,NOW(),`etime`) sminute';
		$qGetUrlOdr = sprintf ( "SELECT %s FROM `Jackin_url_odrs` WHERE `urlid`='%d' AND `odrid`='%d' AND `svcid`='%d' AND `status`=2", $fields, $info ['urlid'], $info ['odrid'], $info ['svcid'] );
		$result = $dbcn->query ( $qGetUrlOdr );
		if (! $result || $result->num_rows == 0) {
			return array ('error' => - 1 );
		}
		$row = $result->fetch_assoc ();
		if ($row ['sminute'] < 1440) {
			return array ('error' => - 1 );
		}
		$pday = ($row ['day'] * CFG_PDAY_RATE) / (1 + CFG_PDAY_RATE);
		$sday = floor ( $row ['sminute'] / 1440 );
		if ($pday > $sday) {
			return array ('error' => - 1 );
		}
		$rebackMoney = floor ( ($sday - $pday) ) * $row ['dayprice'];
		$q = sprintf ( "UPDATE `Jackin_account` SET `money`=`money`+%d WHERE `userid`='%d'", $rebackMoney, $_SESSION ['userid'] );
		$dbcn->query ( $q );
		return array ('error' => 0, 'bdelete' => 1, 'etime' => date ( 'Y-m-d', time () ), 'sday' => 0 );
	}
}
function getOdrId($urlid, $svcid, $allmoney) {
	global $dbcn;
	$fields = '`userid`,`urlid`,`val`,`otype`,`svcid`,`pm`,`lm`,`bae`,`otime`';
	$qGetOdrId = sprintf ( 'INSERT INTO `Jackin_orders`(%s)VALUES(%d,%d,%d,3,%d,0,0,%d,NOW())', $fields, $_SESSION ['userid'], $urlid, $allmoney, $svcid, $_SESSION ['money'] );
	if (false === $dbcn->query ( $qGetOdrId )) {
		return false;
	}
	$odrid = $dbcn->insert_id;
	$qDelUrlOdrs = sprintf ( 'DELETE FROM `Jackin_url_odrs` WHERE `urlid`=%d AND `svcid`=%d', $urlid, $svcid );
	$dbcn->query ( $qDelUrlOdrs );
	return $odrid;
}
?>