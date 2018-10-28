<?php
require_once("alipay_config.php");
require_once("class/alipay_service.php");

/*
 * 重新组合的alipay.php 
 *  将功能全部集合在一个函数里以 方便调用
 */
	function alipayto(){
		$cfg=cfg_alipay(); //获取支付宝配置
		$out_trade_no=mkOuttradeno();
		$subject=getSubject();
		$body=getBody();
		$total_fee=getTotalFee();
		
		$getpay=getPayMethod();
		$paymethod=$getpay['paymethod'];
		$defaultbank=$getpay['defaultbank'];
		
		$encrypt_key="";
		$exter_invoke_ip="";
		if($cfg['antiphishing'] == 1){
			$encrypt_key=query_timestamp($cfg['partner']);
			$exter_invoke_ip=getInvokeIP();
		}
		
		$extra_common_param='';
		$buyer_email="";
		
		$parameter=array(
			"service"=>"create_direct_pay_by_user",
			"payment_type"=>"1",
		
			"partner"=>$cfg['partner'],
			"seller_email"=>$cfg['seller_email'],
			"return_url"=>$cfg['return_url'],
			"notify_url"=>$cfg['notify_url'],
			"_input_charset"=>$cfg['_input_charset'],
			"show_url"=>$cfg['show_url'],
			
			"out_trade_no"=>$out_trade_no,
			"subject"=>$subject,
			"body"=>$body,
			"total_fee"=>$total_fee,

			"paymethod"=>$paymethod,
			"defaultbank"=>$defaultbank,

			"anti_phishing_key"=>$encrypt_key,
			"exter_invoke_ip"=>$exter_invoke_ip,

			//"royalty_type"=>"10",
			//"royalty_parameters"=>"",
			
			//"it_b_pay"=>"1c",
			"buyer_email"=>$buyer_email,
			"extra_common_param"=>$extra_common_param,
		);
		
		$security_code=$cfg['security_code'];
		$sign_type=$cfg['sign_type'];
		$alipay=new alipay_service($parameter,$security_code,$sign_type);
		
		$url=$alipay->create_url();
		$myAlipay=array(
			"parameter"=>$parameter,
			"url"=>$url,
			"error"=>0
		);
		return $myAlipay;
	}

/*
 *  用户根据自己的功能需求必须实现的函数
 */
	function getInvokeIP(){
		/*
		 * 获取客户端IP地址
		 * 可以不修改
		 */
   		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
           $ip = getenv("HTTP_CLIENT_IP");
      	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
           $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
           $ip = getenv("REMOTE_ADDR");
       	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
           $ip = $_SERVER['REMOTE_ADDR'];
        else
           $ip = "unknown";
   		return $ip;

	}
	
	function getPayMethod(){
		/*
		 * 返回支付方式
		 * 这里直接返回：余额支付
		 */
		$payMode=array(
			"paymethod"=>"directPay",
			"defaultbank"=>"",);
		return $payMode;
	}
	function getTotalFee() {
		/*
		 * 获取交易总金额
		 */
		if(!isset($_POST['i']))
			die('{"error":-1}');
		$json=stripcslashes($_POST['i']);
		$oJson=json_decode($json);
		$total_fee=$oJson->cent / 100;
		return $total_fee;
	}
	function getBody(){
		/*
		 * 获取订单描述
		 * 这里返回空
		 */
		$money=getTotalFee();
		$websit="wwww.wutob.com";
		$now=date("Y-m-d G:i:s",time());
		$body="支付宝测试";
		return $body;
	}
	function getSubject() {
		/*
		 * 设置订单名称
		 * 生成规则
		 * 	充值 $total_fee --网址
		 */
		$websit="localhost ";
		$subject="支付宝测试 ";
		return $subject;
	}
	function mkOuttradeno(){
		/* 生成交易订单
		 * 订单生成规则：
		 *   (年月日)(用户ID)(4位随机数)
		 */
		$date=date("Ymd",time());
		if(isset($_SESSION['userid'])){
			$userid=$_SESSION['userid'];
		}else{
			die('{"error":-1}');
		}
		$rand=rand(1000,9999);
		$orderid=$date.$userid.$rand;
		
		return $orderid;
	}

?>
