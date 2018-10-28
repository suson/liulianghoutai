<?php
	require_once("myAlipaycfg.php");
	function cfg_alipay() {
		$cfg=array(
			"partner"=>PARTNER,
			"security_code"=>SECURITY_CODE,
			"seller_email"=>SELLER_EMAIL,
			"_input_charset"=>"utf-8",
			"transport"=>"http",
			"notify_url"=>"./notify_url.php",
			"return_url"=>"./return_url.php",
			"show_url"=>"http://www.alipay.com",
			"sign_type"=>"MD5",
			"antiphishing"=>"0",
			"mainname"=>MAINNAME,
		);
		return $cfg;
	}
?>