<?php
/*
 * 重新组合的return_url.php
 *  将原文件的功能集合在一个函数了
 */
$path = dirname ( __FILE__ );
require_once ($path . '/class/alipay_notify.php');

define ( 'CFG_ALIPAY_PARTNER', 2232323 );
define ( 'CFG_ALIPAY_SECURITY_CODE', '8800' );

$alipay = new alipay_notify ( CFG_ALIPAY_PARTNER, CFG_ALIPAY_SECURITY_CODE, 'MD5', 'utf8', 'http' );

$verifty_result = $alipay->notify_verify ();

if ($verifty_result) {
	//验证成功
	$trade_no = $_GET ['trade_no']; //支付宝订单号
	$out_trade_no = $_GET ['out_trade_no'];
	$total_fee = $_GET ['total_fee'];
	$bayer_email = $_GET ['bayer_email']; //买家账号
	$trade_status = $_GET ['trade_status'];
	$buyer_email = $_GET ['buyer_emai'];
	$subject = $_GET ['subject'];
	$body = $_GET ['body'];
	if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
		myAlipay ();
	echo "success";
	}
} else {
	//验证失败
	echo "fail";
}
function myAlipay() {
	global $dbcn;
	$qChek = sprintf ( "SELECT `out_trade_no` FROM `Jackin_alipay_trade` WHERE `out_trade_no`='%d'", $_GET ['out_trade_no'] );
	$result = $dbcn->query ( $qChek );
	if (! $result || 0 != $result->num_rows) {
		logger ();
		return;
	}
	$qInsert = sprintf ( "INSERT INTO `Jackin_alipay_trade`(`out_trade_no`,`total_fee`,`trade_no`,`buyer_email`) VALUES('%d','%d','%s','%s')", $_GET ['out_trade_no'], $_GET ['total_fee'], $_GET ['trade_no'], $_GET ['buyer_email'] );
	$dbcn->query ( $qInsert );
	$userid = substr ( $_GET ['out_trade_no'], 8, - 4 );
	$qUpdateAccount = sprintf ( "UPDATE `Jackin_account` SET `money`=`money`+'%d' WHERE `userid`='%d'", $_GET ['total_fee'] * 100, $userid );
	@$dbcn->query ( $qUpdateAccount );
}
function logger() {
	$hFile = fopen ( 'log.txt', 'a+' );
	$txt = sprintf ( '\r\n%s    %s', json_encode ( $_GET ), date ( 'Y-m-d H:i:s', time () ) );
	fwrite ( $hFile, $txt );
	fclose ( $hFile );
}
?>