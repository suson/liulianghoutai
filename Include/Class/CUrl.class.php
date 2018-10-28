<?php
class CUrl {
	
	public function __construct() {
	}
	public function __destruct() {
	}
	public static function addURL() {
		global $dbcn;
		if (! checkUserOnline ()) {
			return false;
		}
		$url = json_decode ( $_POST ['i'], true );
		$mysqli = $dbcn;
		if (! checkUrlLawful ( $mysqli, $url ['url'] ))
			return false;
		$name = $mysqli->real_escape_string ( $url ['name'] );
		$url = $mysqli->real_escape_string ( $url ['url'] );
		$defaultype = getItem ( 'defaultype' );
		$qAddUrl = sprintf ( "INSERT INTO Jackin_url(`userid`,`name`,`url`,`rtime`,`ltime`,`urltype`,`furls`) VALUES('%d','%s','%s',NOW(),NOW(),%d,'');", $_SESSION ['userid'], $name, $url, $defaultype ['vvalue'] );
		if (false === $mysqli->query ( $qAddUrl ))
			return false;
		$urlid = $mysqli->insert_id;
		$fields = '`urlid`,`url`,`name`,`urltype`,`furls`,`usefurl`,`turl`,`useturl`,`usepop`,`ltime`,`free`,`clickother`,`clickself`,`tdclick`,`online`';
		$qGetNewUrlInfo = sprintf ( "SELECT %s FROM Jackin_url WHERE `urlid`='%d' AND `userid`='%d';", $fields, $urlid, $_SESSION ['userid'] );
		$result = $mysqli->query ( $qGetNewUrlInfo );
		if (false === $result || 1 != $result->num_rows)
			return false;
		$row = $result->fetch_assoc ();
		$row ['odrs'] = array ();
		$row ['error'] = 0;
		return $row;
	}
	
	public static function cloneURL() {
		return array ('error' => 0 );
		$url = array ();
		if (! isset ( $url ['srcurlid'] ) || ! isset ( $url ['descurlids'] ) || ! is_array ( $url ['descurlids'] ))
			return false;
		$mysqli = $this->dbcn;
		if (! checkUserOnline () || ! chkURLwithUser ( $mysqli, $url ['srcurlid'] ))
			return false;
		global $svcfg; //调用全局对象，获取服务配置信息
		$optimizeID = $svcfg->optimizeID;
		$qGetUrlOdrs = sprintf ( "SELECT `svcid` FROM `Jackin_url_odrs` WHERE `urlid`='%d' AND `sday`<>0 AND `svcid`='%d';", $url ['srcurlid'], $optimizeID );
		$result = $mysqli->query ( $qGetUrlOdrs );
		if (! $result || 1 == $result->num_rows)
			return false;
		$field = '`furls`,`usefurl`,`turl`,`useturl`,`usepop`,`free`';
		$qGetUrlInfo = sprintf ( "SELECT %s FROM `Jackin_url` WHERE `urlid`='%d' AND `userid`='%d';", $field, $url ['srcurlid'], $_SESSION ['userid'] );
		$result = $mysqli->query ( $qGetUrlInfo );
		if (! $result || 1 != $result->num_rows)
			return false;
		$row = $result->fetch_assoc ();
		$setFields = sprintf ( "`furls`='%s',`usefurl`='%d',`turl`='%s',`useturl`='%d',`usepop`='%d',`free`='%d'", $row ['furls'], $row ['usefurl'], $row ['turl'], $row ['useturl'], $row ['usepop'], $row ['free'] );
		while ( false === ($urlid = array_shift ( $url ['descurlids'] )) ) {
			if (! chkURLwithUser ( $mysqli, $urlid ))
				return false;
			$qUpdate = sprintf ( "UPDATE `Jackin_url` SET %s WHERE `urlid`='%d' AND `userid`='%d';", $setFields, $urlid, $_SESSION ['userid'] );
			if (! $mysqli->query ( $qUpdate ) || 1 != $mysqli->affected_rows) {
				return false;
			}
		}
	}
	public static function delURL() {
		global $dbcn;
		$i = json_decode ( $_POST ['i'], true );
		$urlid = $i ['urlid'];
		if (! ctype_digit ( $urlid )) {
			return array ('error' => - 1 );
		}
		$mysqli = $dbcn;
		if (! chkURLwithUser ( $mysqli, $urlid )) {
			return array ('error' => - 1 );
		}
		$qCHK = sprintf ( 'SELECT `svcid` FROM `Jackin_url_odrs` WHERE `urlid`=%d AND `sday`<>0;', $urlid );
		$result = $mysqli->query ( $qCHK );
		if (! $result || 0 != $result->num_rows) {
			return array ('error' => - 1 );
		}
		$qDel = sprintf ( 'DELETE FROM `Jackin_url` WHERE `urlid`=%d AND `userid`=%d;', $urlid, $_SESSION ['userid'] );
		$bSuc = $mysqli->query ( $qDel );
		return array ('error' => $bSuc ? 0 : - 1 );
	}
	public static function setFree() {
		global $dbcn;
		$free = json_decode ( $_POST ['i'], true );
		$mysqli = $dbcn;
		if (! chkURLwithUser ( $mysqli, $free ['urlid'] )) {
			return array ('error' => - 1 );
		}
		$qUpdate = sprintf ( 'UPDATE `Jackin_url` SET `free`=%d WHERE `urlid`=%d AND `userid`=%d;', $free ['free'], $free ['urlid'], $_SESSION ['userid'] );
		$bSuc = $mysqli->query ( $qUpdate );
		return array ('error' => $bSuc ? 0 : - 1 );
	}
	public static function setFlowLine() {
		global $dbcn;
		$url = json_decode ( $_POST ['i'], true );
		$mysqli = $dbcn;
		if (! chkURLwithUser ( $mysqli, $url ['urlid'] )) {
			return array ('error' => - 1 );
		}
		$hrs = implode ( '|', $url ['hrs'] );
		$q = sprintf ( "REPLACE INTO `Jackin_flow`(`cid`,`hrs`) VALUES('%d','%s')", $url ['urlid'], $hrs );
		return array ('error' => $mysqli->query ( $q ) ? 0 : - 1 );
	}
	public static function getFlowLine() {
		global $dbcn;
		$i = json_decode ( $_POST ['i'], true );
		$urlid = $i ['urlid'];
		if (! ctype_digit ( $urlid )) {
			return false;
		}
		$q = sprintf ( 'SELECT `cid`, `hrs` FROM `Jackin_flow` WHERE `cid`=%d;', $urlid );
		$mysqli = $dbcn;
		$result = $mysqli->query ( $q );
		if (! $result || 1 != $result->num_rows) {
			return array ('error' => 0, 'cid' => $urlid, 'hrs' => array_fill ( 0, 23, 100 ) );
		}
		$row = $result->fetch_assoc ();
		return array ('error' => 0, 'cid' => $row ['cid'], 'hrs' => explode ( '|', $row ['hrs'] ) );
	}
	public static function loadURL() {
		global $dbcn;
		updateUrlOdr ();
		$sorts = array ('urlid', 'names' );
		$info = json_decode ( $_POST ['i'], true );
		$urls = array ();
		$tdonline = 0;
		$today = floor ( time () / 86400 );
		$sortype = isset ( $info ['sortype'] ) ? ( int ) $info ['sortype'] : 0;
		$field = '`urlid`,`url`,`name`,`urltype`,`furls`,`usefurl`,`turl`,`useturl`,`usepop`,`ltime`,`free`,`clickother`,`clickself`,`tdclick`,`online`';
		$q = sprintf ( "SELECT %s FROM `Jackin_url` WHERE `userid`='%d' ORDER BY `%s`", $field, $_SESSION ['userid'], $sorts [$sortype] );
		$result = $dbcn->query ( $q );
		if (! $result) {
			return array ('error' => - 1, 'msg' => $dbcn->error );
		}
		if (0 == $result->num_rows) {
			return array ('error' => 0, 'tdonline' => 0, 'urls' => array () );
		}
		$dt = getItem ( 'defaultype' );
		if (FALSE === $dt) {
			return array ('error' => - 1, 'tdonline' => - 1, 'urls' => array () );
		}
		$defaultype = $dt ['vvalue'];
		// 开始获取URL INFO
		for($i = $result->num_rows; $i > 0; $i --) {
			$url = $result->fetch_assoc ();
			$url ['odrs'] = array ();
			if ($url ['urltype'] != $dt ['vvalue']) {
				$url ['odrs'] = getService ( $url ['urlid'] );
				if ($today == floor ( $url ['ltime'] / 86400 )) {
					$tdonline ++;
				}
			}
			$urls [] = $url;
		}
		return array ('error' => 0, 'tdonline' => $tdonline, 'urls' => $urls );
	}
	public static function cloneSet() {
		global $dbcn;
		$i = json_decode ( $_POST ['i'], true );
		#{"srcurlid":"10016","descurlids":["10014","10016"]}
		return array ('error' => 0 );
	}
	public static function setOptimize() {
		global $dbcn;
		$sets = json_decode ( $_POST ['i'], true );
		$qHasOptimizeSvc = sprintf ( 'SELECT `sday` FROM `Jackin_url_odrs` WHERE `urlid`=8' );
		$result = $dbcn->query ( $qHasOptimizeSvc );
		if (FALSE === $result || 1 < $result->num_rows) {
			return array ('error' => - 1 );
		}
		if (0 == $result->num_rows) {
			$len = sizeof ( explode ( '|', $sets ['furls'] ) );
			if ($len > 2 || $sets ['usepop'] > 5 || ($sets ['useturl'] & 0xFEFF) > 1) {
				return array ('error' => - 1 );
			}
		} else if (1 == $result->num_rows) {
			$len = sizeof ( explode ( '|', $sets ['furls'] ) );
			if ($len > 20 || $sets ['usepop'] > 100 || ($sets ['useturl'] & 0xFEFF) > 100) {
				return array ('error' => - 1 );
			}
		} else {
			return array ('error' => - 1 );
		}
		
		$qUpdate = sprintf ( "UPDATE `Jackin_url` SET `url`='%s',`name`='%s',`furls`='%s',`usefurl`=%d,`turl`='%s',`useturl`=%d,`usepop`=%d WHERE `urlid`=%d AND `userid`=%d", $sets ['url'], $sets ['name'], $sets ['furls'], $sets ['usefurl'], $sets ['turl'], $sets ['useturl'], $sets ['usepop'], $sets ['urlid'], $_SESSION ['userid'] );
		$bSuc = $dbcn->query ( $qUpdate );
		if (! $bSuc) {
			return array ('error' => - 1 );
		}
		$qSelect = sprintf ( 'SELECT * FROM `Jackin_url` WHERE `urlid`=%d AND `userid`=%d', $sets ['urlid'], $_SESSION ['userid'] );
		$result = $dbcn->query ( $qSelect );
		if (FALSE === $result || 0 == $result->num_rows) {
			return array ('error' => - 1 );
		}
		$row = $result->fetch_assoc ();
		unset ( $row ['userid'] );
		$row ['error'] = 0;
		return $row;
	}
}

function getItem($item) {
	global $dbcn;
	$q = sprintf ( "SELECT `vvalue`,`vdefault` FROM `Jackin_sys_var` WHERE `vname`='%s' LIMIT 0,1;", $item );
	$result = $dbcn->query ( $q );
	if (! $result || 0 == $result->num_rows) {
		return array ('vvalue' => 0, 'vdefault' => 0 );
	}
	return $result->fetch_assoc ();
}

function getService($urlid) {
	global $dbcn;
	$odrs = array ();
	$fields = '`odrid`,`status`,`sday`,`svcid`,`dayprice`,`btime`,`etime`';
	$q = sprintf ( "SELECT %s FROM `Jackin_url_odrs` WHERE `urlid`='%d' ", $fields, $urlid );
	$result = $dbcn->query ( $q );
	if ($result && 0 != $result->num_rows) {
		for($i = $result->num_rows; $i > 0; $i --) {
			$odrs [] = $result->fetch_assoc ();
		}
	}
	return $odrs;
}
function updateUrlOdr() {
	$qUpdate = sprintf ( 'UPDATE `Jackin_url_odrs` SET `sday`=IF(`etime`<NOW(),0,TIMESTAMPDIFF(MINUTE,NOW(),`etime`))' );
	GLOBAL $dbcn;
	$dbcn->query ( $qUpdate );
}
?>