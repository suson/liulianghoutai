<?php
/*
 * 重新组合的notify_url.php
 *  将原文件的功能集合在一个函数了
 */
require_once("class/alipay_notify.php");
require_once("alipay_config.php");
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
			$trade_no=$_POST['trade_no']; //支付宝订单号
			$out_trade_no=$_POST['out_trade_no']; 
			$total_fee=$_POST['total_fee'];
			$buyer_email=$_POST['buyer_email']; //买家账号
			$trade_status=$_POST['trade_status'];
			$subject=$_POST['subject']; //订单名称
			$body=$_POST['body']; //订单描述
			
			if($trade_status== '交易成功' || $trade_status == 'TRADE_SUCCESS'){
				$status=getStatus($out_trade_no);
				if($status<1){
					processTrade($out_trade_no,$trade_no,$subject,$body,$total_fee,$buyer_email);
					//echo "success";
				}else{
					echo "success";
				}
			}
		}else{
			//验证失败
			echo "充值成功！";
		}	
?>