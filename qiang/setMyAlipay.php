<?php
	require_once "./securityCheck.php";
	require_once '../dbcfg.php';

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	if(isset($_POST['partner']) && $_POST['partner'] != "" && isset($_POST['security_code']) && $_POST['security_code'] != "" && isset($_POST['seller_email']) && $_POST['seller_email'] != "" && isset($_POST['mainname']) && $_POST['mainname'] != "" ){
		$partner=$_POST['partner'];
		$security_code=$_POST['security_code'];
		$seller_email=$_POST['seller_email'];
		$mainname=$_POST['mainname'];
		
		$cnt="<?php \r\n";
		$cnt.="define('PARTNER','$partner'); \r\n";
		$cnt.="define('SECURITY_CODE','$security_code'); \r\n";
		$cnt.="define('SELLER_EMAIL','$seller_email'); \r\n";
		$cnt.="define('MAINNAME','$mainname'); \r\n";
		$cnt.="?>";
		
		$file="../service/urlcore/alipay/myAlipaycfg.php";
		$fp=fopen($file,"w");
		fwrite($fp,$cnt);
		fclose($fp);
		echo '{"success":true,"msg":"配置支付宝成功!"}';
	}else{
		echo '{"success":false,"msg":"参数不正确!"}';
	}
?>