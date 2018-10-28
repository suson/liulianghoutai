<?php
session_start ();
error_reporting ( E_ALL );
define ( 'APP_ROOT', $_SERVER ['DOCUMENT_ROOT'] . '/Include' );
set_error_handler ( function ($code, $str, $file, $line) {
	echo json_encode ( array ('error' => - 1, 'msg' => "[$code]$str in file $file at line $line" ) );
	exit ();
} );
date_default_timezone_set ( 'Asia/Shanghai' );
set_include_path ( get_include_path () . PATH_SEPARATOR . dirname ( __FILE__ ) );
$dbcn = new mysqli ('localhost', 'root', 'root', 'ipziyuan' );
echo $dbcn->error;
require_once ('Class/CUser.class.php');
require_once ('Class/CUrl.class.php');
require_once ('Class/CService.class.php');
$event = array (1 => 'getVerifyCode', 2 => 'regist', 3 => 'nameExist', 4 => 'login', 5 => 'getQuestion', 6 => 'resetPWD', 7 => 'loadUser', 8 => 'logOut', 9 => 'modPWD', 20 => 'loadURL', 21 => 'addURL', 22 => 'setOptimize', 23 => 'delURL', 24 => 'setFree', 25 => 'setFlowLine', 26 => 'getFlowLine', 27 => 'cloneSet', 31 => 'getPayMoney', 32 => 'startService' );
// array('className','method')
$eventListener = array ('getVerifyCode' => array ('CUser', 'getVerifyCode' ), 'regist' => array ('CUser', 'regist' ), 'nameExist' => array ('CUser', 'nameExist' ), 'login' => array ('CUser', 'login' ), 'getQuestion' => array ('CUser', 'getQuestion' ), 'resetPWD' => array ('CUser', 'resetPWD' ), 'loadUser' => array ('CUser', 'loadUser' ), 'logOut' => array ('CUser', 'logOut' ), 'modPWD' => array ('CUser', 'modPWD' ), 'loadURL' => array ('CUrl', 'loadURL' ), 'addURL' => array ('CUrl', 'addURL' ), 'setOptimize' => array ('CUrl', 'setOptimize' ), 'delURL' => array ('CUrl', 'delURL' ), 'setFree' => array ('CUrl', 'setFree' ), 'setFlowLine' => array ('CUrl', 'setFlowLine' ), 'getFlowLine' => array ('CUrl', 'getFlowLine' ), 'cloneSet' => array ('CUrl', 'cloneSet' ), 'getPayMoney' => array ('CService', 'getPayMoney' ), 'startService' => array ('CService', 'startService' ) );
if (! isset ( $_POST ['f'] ) || ! isset ( $event [$_POST ['f']] )) {
	$error = json_encode ( array ('error' => - 1 ) );
	exit ( $error );
}
echo json_encode ( call_user_func ( $eventListener [$event [$_POST ['f']]] ) );
//unset($_SESSION['error']);
?>