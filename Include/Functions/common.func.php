<?php
########################################
# Date: 2011-03-30						#
# Author: Mr.Jackin					#
# Description: Define Common Function	#
########################################


/*
 * 定义函数 checkVerifyCode
 *  检查验证码 $cc 是否正确
 */
function checkVerifyCode($cc) {
	if (! isset ( $_SESSION ['cc'] ) || 0 != strcmp ( strtolower ( $_SESSION ['cc'] ), strtolower ( $cc ) )) {
		return false;
	}
	unset ( $_SESSION ['cc'] );
	return true;
}

/*
 * 获取客户端IP地址
 */
function getInvokeIP() {
	if (getenv ( "HTTP_CLIENT_IP" ) && getenv ( "HTTP_CLIENT_IP" ) != "" && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" )) {
		$ip = getenv ( "HTTP_CLIENT_IP" );
	} else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && getenv ( "HTTP_X_FORWARDED_FOR" ) != "" && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" )) {
		$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
	} else if (getenv ( "REMOTE_ADDR" ) && getenv ( "REMOTE_ADDR" ) != "" && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" )) {
		$ip = getenv ( "REMOTE_ADDR" );
	} else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] != "" && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" )) {
		$ip = $_SERVER ['REMOTE_ADDR'];
	} else {
		$ip = "unknown";
	}
	return $ip;
}

/*
 * 检验用户是否在线
 */
function checkUserOnline() {
	$chk = 'e1378674973311dd89068d26c5cbb0e6';
	if (isset ( $_SESSION ['cc'] )) {
		$cc = $_SESSION ['cc'];
		unset ( $_SESSION ['cc'] );
	}
	if (0 == strcmp ( $chk, md5 ( implode ( array_keys ( $_SESSION ) ) ) )) {
		if (isset ( $cc )) {
			$_SESSION ['cc'] = $cc;
		}
		return true;
	}
	return false;
}

/*
 * 检查用户提交的网址是否合法
 */
function checkUrlLawful($mysqli, $url) {
	$pattern = '#^http://([[:alnum:]]+\.)+([[:alnum:]]+\.[[:alnum:]]{2,3})/#';
	$url = strtolower ( trim ( $url ) );
	if (substr ( $url, - 1 ) != '/')
		$url .= '/';
	if (0 == preg_match ( $pattern, $url, $match ))
		return false;
	$SqlPattern = $mysqli->real_escape_string ( $match [2] );
	$qchk = sprintf ( "SELECT `id`,`url`,`reason` FROM Jackin_filter WHERE `url` REGEXP '%s' LIMIT 0,1;", $SqlPattern );
	$result = $mysqli->query ( $qchk );
	if (false === $result || 1 == $result->num_rows)
		return false;
	return true;
}
/*
 * 更新表 Jackin_url_odrs
 */
function updateUrlOdrs($mysqli) {
	$qUpdate = 'UPDATE Jackin_url_odrs SET `sday`=IF(TIMESTAMPDIFF(MINUTE,NOW(),`etime`)>0,TIMESTAMPDIFF(MINUTE,NOW(),`etime`),0);';
	if (! $mysqli->query ( $qUpdate ))
		return false;
	return true;
}

/*
 * 将服务到期的网址恢复
 */
function recoverURL($mysqli, $urlid) {
	$qGetUrlOdrs = sprintf ( 'SELECT `svcid`, `urlid`,`sday` FROM Jackin_url_odrs WHERE `urlid`=%d;', $urlid );
	$result = $mysqli->query ( $qGetUrlOdrs );
	if (! $result)
		return false;
	if ($result->num_rows == 0)
		return true;
}

/*
 * 检查要修改信息的网址是否属于当前用户
 */
function chkURLwithUser($urlid) {
	global $dbcn;
	$qCHK = sprintf ( 'SELECT `name`,`url` FROM `Jackin_url` WHERE `userid`=%d AND `urlid`=%d;', $_SESSION ['userid'], $urlid );
	$result = $dbcn->query ( $qCHK );
	if (FALSE === $result || 1 != $result->num_rows)
		return false;
	return true;
}

/*
 *  检查网址是否开通了, 相应的服务
 */
function hasOptimize($mysqli, $urlid, $svcid) {
	$q = sprintf ( "SELECT `sday`,`status` FROM `Jackin_url_odrs` WHERE `urlid`='%d AND `svcid`='%d' AND `status`<>0;", $urlid, $svcid );
	$result = $mysqli->query ( $q );
	if (! $result || 1 != $result->num_rows)
		return false;
	return true;
}

/*
 * 恢复网址开通的服务为默认
 */
function setSVCDefault($mysqli, $urlid) {
	$set = "`furls`='',`usefurl`=0,`turl`='',`useturl`=0,`usepop`=0,`online`=0";
	$q = sprintf ( "UPDATE `Jackin_url` SET %s WHERE `urlid`='%d' AND `userid`='%d';", $set, $urlid, $_SESSION ['userid'] );
}

?>