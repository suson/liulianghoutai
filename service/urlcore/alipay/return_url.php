<?php
/*
 * 重新组合的return_url.php
 *  将原文件的功能集合在一个函数了
 */

require_once("class/alipay_notify.php");
require_once("alipay_config.php");
require_once("../../../dbcfg.php");
require_once("./myAlipay.php");
require_once("./myAlipaycfg.php");
		$cfg= cfg_alipay();
		
		$partner=$cfg["partner"];
		$security_code=$cfg['security_code'];
		$sign_type=$cfg['sign_type'];
		$_input_charset=$cfg['_input_charset'];
		$transport=$cfg['transport'];
		$alipay=new alipay_notify($partner,$security_code,$sign_type,$transport);
		
		$verifty_result=$alipay->notify_verify();
		
		if ($verifty_result) {
			//验证成功
			$trade_no=$_GET['trade_no']; //支付宝订单号
			$out_trade_no=$_GET['out_trade_no']; 
			$total_fee=$_GET['total_fee'];
			$bayer_email=$_GET['bayer_email']; //买家账号
			$trade_status=$_GET['trade_status'];
			$buyer_email=$_GET['buyer_emai'];
			$subject=$_GET['subject'];
			$body=$_GET['body'];
			if($trade_status== '交易成功' || $trade_status == 'TRADE_SUCCESS'){
				$status=getStatus($out_trade_no);
				if($status<1){
					//交易未处理
					processTrade($out_trade_no,$trade_no,$subject,$body,$total_fee,$buyer_email);
				}else{
					echo "success";
				}
			}
		}else{
			//验证失败
			echo "充值成功！";
		}
?>
<html>
    <head>
        <title>支付宝即时支付</title>
        <style type="text/css">
            .font_content{
                font-family:"宋体";
                font-size:14px;
                color:#FF6600;
            }
            .font_title{
                font-family:"宋体";
                font-size:16px;
                color:#FF0000;
                font-weight:bold;
            }
            table{
                border: 1px solid #CCCCCC;
            }
        </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
    <body>

        <table align="center" width="350" cellpadding="5" cellspacing="0">
            <tr>
                <td align="center" class="font_title" colspan="2">通知返回</td>
            </tr>
            <tr>
                <td class="font_content" align="right">支付宝交易号：</td>
                <td class="font_content" align="left"><?php echo $_GET['trade_no']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">订单号：</td>
                <td class="font_content" align="left"><?php echo $_GET['out_trade_no']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">付款总金额：</td>
                <td class="font_content" align="left"><?php echo $_GET['total_fee']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">商品标题：</td>
                <td class="font_content" align="left"><?php echo $_GET['subject']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">商品描述：</td>
                <td class="font_content" align="left"><?php echo $_GET['body']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">买家账号：</td>
                <td class="font_content" align="left"><?php echo $_GET['buyer_email']; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">交易状态：</td>
                <td class="font_content" align="left"><?php echo $_GET['trade_status']; ?></td>
            </tr>
        </table>
    </body>
</html>