<?php
#########################################################
# Date: 2011-03-30										#
# Author: Mr.Jackin										#
# Description: 											#
#########################################################


require_once ('Functions/common.func.php');
class CUser {
	public function __construct() {
	}
	public function __destruct() {
	}
	public static function getVerifyCode() {
		$clen = 6;
		$info = json_decode ( $_POST ['i'], true );
		if (isset ( $info ['clen'] )) {
			$clen = 4;
		}
		$apha = str_split ( 'abcdefghijklmnopqrstuvwxyz' );
		$str = '';
		$max = sizeof ( $apha ) - 1;
		for($i = 0; $i < $clen; $i ++) {
			$str .= $apha [rand ( 0, $max )];
		}
		$_SESSION ['cc'] = $str;
		return array ('error' => 0, 'ccmd5' => md5 ( strtoupper ( $str ) ), 'cc' => $_SESSION ['cc'] );
	}
	/*
	 * 定义函数 regist 
	 * 用户注册函数 ,参数  $info是个必须包含以下成员的数组: name, pwd, email, question, answer, cc(验证码)
	 * 	如果验证码正确且用户名不存在则 将answer和 pwd 用MD5加密, 将 email 转换为小写再将 $info 保存予数据库
	 * 	成功返回true, 失败返回false
	 */
	public static function regist() {
		global $dbcn;
		$info = json_decode ( $_POST ['i'], true );
		$ptn = "/^[[:alnum:]]{3,}$/";
		if (1 != preg_match ( $ptn, $info ['name'], $match )) {
			return array ('error' => - 1 );
		}
		if (FALSE === filter_var ( $info ['email'], FILTER_VALIDATE_EMAIL )) {
			return array ('error' => - 1 );
		}
		if (! checkVerifyCode ( $info ['cc'] )) {
			return array ('error' => - 1 );
		}
		$mysqli = $dbcn;
		$name = $mysqli->real_escape_string ( trim ( $info ['name'] ) );
		$pwd = md5 ( $info ['pwd'] );
		$email = $mysqli->real_escape_string ( strtolower ( trim ( $info ['email'] ) ) );
		$question = $mysqli->real_escape_string ( trim ( $info ['question'] ) );
		$answer = md5 ( trim ( $info ['answer'] ) );
		$qAddUser = sprintf ( "INSERT INTO Jackin_users(`name`,`pwd`,`email`,`question`,`answer`,`rtime`,`ltime`) VALUES('%s','%s','%s','%s','%s',NOW(),NOW());", $name, $pwd, $email, $question, $answer );
		if (! $mysqli->query ( $qAddUser )) {
			return array ('error' => - 1, 'msg' => $mysqli->error );
		}
		$userid = $mysqli->insert_id;
		$qAddAccount = "INSERT INTO Jackin_account(userid) VALUES('$userid')";
		if (! $mysqli->query ( $qAddAccount )) {
			return array ('error' => - 1, 'msg' => $mysqli->error );
		}
		return array ('error' => 0 );
	}
	
	/*
	 * 定义函数 resetPWD
	 * 密码重置, 参数$info 是个必须包含以下成员的数组: name, pwd, email, question, answer, cc(验证码)
	 * 如果验证码正确 且 用户名存在 且answer正确
	 * 成功返回true, 失败返回false
	 */
	public static function resetPWD() {
		global $dbcn;
		$info = json_decode ( $_POST ['i'], true );
		if (FALSE === filter_var ( $info ['email'], FILTER_VALIDATE_EMAIL )) {
			return array ('error' => - 1 );
		}
		if (! checkVerifyCode ( $info ['cc'] )) {
			return array ('error' => - 1 );
		}
		$mysqli = $dbcn;
		$pwd = md5 ( $info ['pwd'] );
		$name = $mysqli->real_escape_string ( $info ['name'] );
		$answer = md5 ( trim ( $info ['answer'] ) );
		$qResetPWD = sprintf ( "UPDATE Jackin_users SET `pwd`='%s' WHERE `name`='%s' AND `answer`='%s';", $pwd, $name, $answer );
		if (! $mysqli->query ( $qResetPWD )) {
			return array ('error' => - 1 );
		}
		return array ('error' => 0 );
	}
	
	/*
	 * 定义函数getQuestion
	 * 获取密码保护问题   参数$info 是个必须包含email, name 的数组
	 * 如果name和email存在 则返回 密码保护问题, 否则返回 fasle
	 */
	public static function getQuestion() {
		global $dbcn;
		$info = json_decode ( $_POST ['i'], true );
		if (FALSE === filter_var ( $info ['email'], FILTER_VALIDATE_EMAIL )) {
			return array ('error' => - 1, 'question' => 'Email not exists!!' );
		}
		$mysqli = $dbcn;
		$name = $mysqli->real_escape_string ( $info ['name'] );
		$email = $mysqli->real_escape_string ( strtolower ( trim ( $info ['email'] ) ) );
		$qGetQuestion = sprintf ( "SELECT `question` FROM `Jackin_users` WHERE `name`='%s' AND `email`='%s'", $name, $email );
		$result = $mysqli->query ( $qGetQuestion );
		if (FALSE === $result || $result->num_rows != 1) {
			return array ('error' => - 1, 'question' => 'Email not exist!' );
		}
		$row = $result->fetch_assoc ();
		return array ('error' => 0, 'question' => $row ['question'] );
	}
	
	/*
	 * 定义函数 modPWD
	 * 修改密码, 参数$info 是个必须包含以下成员的数组: newpwd, oldpwd, cc(验证码)
	 * 成功返回true, 失败返回false
	 */
	public static function modPWD() {
		global $dbcn;
		$info = json_decode ( $_POST ['i'], true );
		if (! isset ( $info ['cc'] ) || ! checkVerifyCode ( $info ['cc'] )) {
			return false;
		}
		$pwd = md5 ( $info ['newpwd'] );
		$mysqli = $dbcn;
		$qModPWD = sprintf ( "UPDATE `Jackin_users` SET `pwd`='%s' WHERE `userid`='%d';", $pwd, $_SESSION ['userid'] );
		if (false === $mysqli->query ( $qModPWD )) {
			return array ('error' => - 1 );
		}
		return array ('error' => 0 );
	}
	
	/*
	 * 定义函数 login 
	 * 用户登入, 参数 $info 是个必须包含以下成员的数组:name, chkpwd
	 * 成功返回 true且将用户的详细信息保存予$_SESSION中, 失败返回 false
	 */
	public static function login() {
		global $dbcn;
		$info = json_decode ( $_POST ['i'], true );
		if (! isset ( $info ['name'] ) || ! isset ( $info ['chkpwd'] )) {
			return array ('error' => - 1 );
		}
		$mysqli = $dbcn;
		$name = $mysqli->real_escape_string ( trim ( $info ['name'] ) );
		$chkpwd = md5 ( $info ['chkpwd'] );
		$qLogin = sprintf ( "SELECT u.userid userid,name,ltime,rtime,money,pmoney,lmoney,score,level,pwd, DATEDIFF(NOW(),`ltime`) tdlogined 
			FROM `Jackin_users` as u, `Jackin_account` as a WHERE u.`pwd`='%s' AND u.`name`='%s' AND u.`userid`=a.`userid`;", $chkpwd, $name );
		$result = $mysqli->query ( $qLogin );
		if (FALSE === $result || 1 != $result->num_rows) {
			return array ('error' => - 1 );
		}
		$_SESSION = $result->fetch_assoc ();
		ksort ( $_SESSION );
		if (0 != $_SESSION ['tdlogined']) {
			updateScore ( $_SESSION ['userid'], $_SESSION ['ltime'], $_SESSION ['score'] );
		}
		unset ( $_SESSION ['tdlogined'] );
		$q = sprintf ( 'UPDATE `Jackin_users` SET `ltime`=NOW() WHERE `userid`=%d', $_SESSION ['userid'] );
		$mysqli->query ( $q );
		//$_SESSION ['ltime'] = date ( 'Y-m-d m:i:s', time () );
		return array ('error' => 0, 'userid' => $_SESSION ['userid'] );
	}
	
	/*
	 * 定义函数 nameExist
	 * 检查name 是否在数据库中存在
	 * 存在返回true, 不存在返回false
	 */
	public static function nameExist() {
		global $dbcn;
		$mysqli = $dbcn;
		$info = json_decode ( $_POST ['i'], true );
		$name = $info ['name'];
		$ptn = "/^[[:alnum:]]{3,}$/";
		if (1 != preg_match ( $ptn, $info ['name'], $match )) {
			return array ('error' => 0, 'good' => - 1 );
		}
		if (FALSE !== stristr ( $name, 'admin', true )) {
			return array ('error' => 0, 'good' => - 1 );
		}
		$name = $mysqli->real_escape_string ( $name );
		$q = sprintf ( "SELECT `userid` FROM `Jackin_users` WHERE `name`='%s';", $name );
		$result = $mysqli->query ( $q );
		// good: 1 未注册 、0 已经注册、-1 非法用户名 
		if (FALSE === $result || 0 == $result->num_rows) {
			return array ('error' => 0, 'good' => 1 );
		}
		return array ('error' => 0, 'good' => 0 );
	}
	public static function loadUser() {
		if (! checkUserOnline ()) {
			return array ('error' => - 1 );
		}
		$info = $_SESSION;
		$info ['error'] = 0;
		//$info ['rtime'] = date ( 'Y-m-d', strtotime ( $info ['rtime'] ) );
		//$info ['tz'] = date_default_timezone_get ();
		unset ( $info ['pwd'], $info ['rtime'] );
		return $info;
	}
	/*
	 * 定义函数 logout
	 * 注销登入   销毁当前 $_SESSION=array()
	 */
	public static function logOut() {
		session_unset ();
	}
}
function updateScore($userid, $ltime, &$score) {
	global $dbcn;
	$score += 50;
	$qUS = sprintf ( "UPDATE `Jackin_account` SET `score`=`score`+%d WHERE `userid`=%d", 50, $userid );
	if (FALSE === $dbcn->query ( $qUS )) {
		$fp = fopen ( 'log.txt', 'a+' );
		fwrite ( $fp, sprintf ( 'userID    %d    50    Faile $s\n', $userid, date ( 'Y-m-d m:i:s', time () ) ) );
		fclose ( $fp );
	}
}