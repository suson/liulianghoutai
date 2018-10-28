<?php
require_once ("class/alipay_service.php");

define ( 'CFG_ALIPAY_PARTNER', 2232323 );
define ( 'CFG_ALIPAY_SECURITY_CODE', '8800' );

/*
 * 重新组合的alipay.php 
 *  将功能全部集合在一个函数里以 方便调用
 */
function alipayto($total_fee) {
	global $svcfg; //获取支付宝配置
	$out_trade_no = mkOuttradeno ();
	$subject = '龙卷风流量充值中心';
	$body = '您于' . date ( 'Y年m月d日  G时i分s秒', time () ) . '在龙卷风流量充值中心充值' . $total_fee . '元, 充值成功！';
	$encrypt_key = "";
	$exter_invoke_ip = "";
	if ($svcfg->antiphishing == 1) {
		$encrypt_key = query_timestamp ( $svcfg->partner );
		$exter_invoke_ip = getInvokeIP ();
	}
	
	$parameter = array ("service" => "create_direct_pay_by_user", "payment_type" => "1", 

	"partner" => CFG_ALIPAY_PARTNER, "seller_email" => $svccfg->seller_email, "return_url" => $svccfg->return_url, "notify_url" => $svcfg->notify_url, "_input_charset" => 'utf8', "show_url" => $svcfg->show_url, 

	"out_trade_no" => $out_trade_no, "subject" => $subject, "body" => $body, "total_fee" => $total_fee, 

	"paymethod" => "directPay", "defaultbank" => '', 

	"anti_phishing_key" => '', "exter_invoke_ip" => '', 

	//"royalty_type"=>"10",
	//"royalty_parameters"=>"",
	

	//"it_b_pay"=>"1c",
	"buyer_email" => '', "extra_common_param" => gzcompress ( base64_encode ( array ('userid' => $_SESSION ['userid'], 'total_fee' => $total_fee * 100, 'time' => time () ) ) ) );
	
	$security_code = CFG_ALIPAY_SECURITY_CODE;
	$alipay = new alipay_service ( $parameter, $security_code, 'MD5' );
	$url = $alipay->create_url ();
	return $url;
}

/*
 *  用户根据自己的功能需求必须实现的函数
 *  生成订单规则：(年月日)(用户ID)(4位随机数)
 */

function mkOuttradeno() {
	$date = date ( "Ymd", time () );
	$userid = $_SESSION ['userid'];
	$rand = mt_rand ( 1000, 9999 );
	$orderid = $date . $userid . $rand;
	
	return $orderid;
}

?>
