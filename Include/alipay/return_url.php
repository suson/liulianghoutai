<html>
<head>
<title>龙卷风流量充值中心</title>
<style type="text/css">
.font_content {
	font-family: "宋体";
	font-size: 14px;
	color: #FF6600;
}

.font_title {
	font-family: "宋体";
	font-size: 16px;
	color: #FF0000;
	font-weight: bold;
}

table {
	border: 1px solid #CCCCCC;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
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
	
	//echo "success";
	}
} else {
	//验证失败
	//echo "fail";
	echo '<script type="text/JavaScript">alert("充值失败！请联系管理员！"); window.location="http://www.ipziyuan.com";</script>';
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
<table align="center" width="350" cellpadding="5" cellspacing="0">
	<tr>
		<td align="center" class="font_title" colspan="2">通知返回</td>
	</tr>
	<tr>
		<td class="font_content" align="right">支付宝交易号：</td>
		<td class="font_content" align="left"><?php
		echo $_GET ['trade_no'];
		?></td>
	</tr>
	<tr>
		<td class="font_content" align="right">付款总金额：</td>
		<td class="font_content" align="left"><?php
		echo $_GET ['total_fee'];
		?></td>
	</tr>
	<tr>
		<td class="font_content" align="right">商品标题：</td>
		<td class="font_content" align="left"><?php
		echo $_GET ['subject'];
		?></td>
	</tr>
	<tr>
		<td class="font_content" align="right">商品描述：</td>
		<td class="font_content" align="left"><?php
		echo $_GET ['body'];
		?></td>
	</tr>
	<tr>
		<td class="font_content" align="right">买家账号：</td>
		<td class="font_content" align="left"><?php
		echo $_GET ['buyer_email'];
		?></td>
	</tr>
</table>
<div>还有&nbsp;&nbsp;<span id="time">10</span>&nbsp;%nbsp;秒，<a
	href="http://www.ipziyuan.com" target="_self">返回首页</a></div>
<script type="text/javascript">
window.onload=function(){
	var second=10,
	d=document.getElementbyId('time');
	setInterval(function(){second--; d.innerText=second;if(second <= 0 ){window.location='http://www.ipziyuan.com';}},1000);
}
</script>
</body>
</html>