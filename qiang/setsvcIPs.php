<?php

	require_once "./securityCheck.php";

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	require_once "../service/urlcore/cfg/svcipcfg.php";
	
	$file="../service/urlcore/cfg/svcipcfg.php";
	$optimize=isset($_POST['optimize']) && is_numeric($_POST['optimize']) ? $_POST['optimize'] : SVC_OPTIMIZE;
	$ip8=isset($_POST['ip8']) && is_numeric($_POST['ip8']) ? $_POST['ip8'] : SVC_IP8;
	$ip101=isset($_POST['ip101']) && is_numeric($_POST['ip101']) ? $_POST['ip101'] : SVC_IP101;
	$ip103=isset($_POST['ip103']) && is_numeric($_POST['ip103']) ? $_POST['ip103'] : SVC_IP103;
	$ip106=isset($_POST['ip106']) && is_numeric($_POST['ip106']) ? $_POST['ip106'] : SVC_IP106;
	$ip108=isset($_POST['ip108']) && is_numeric($_POST['ip108']) ? $_POST['ip108'] : SVC_IP108;
	$ip110=isset($_POST['ip110']) && is_numeric($_POST['ip110']) ? $_POST['ip110'] : SVC_IP110;
	$ip115=isset($_POST['ip115']) && is_numeric($_POST['ip115']) ? $_POST['ip115'] : SVC_IP115;
	$ip120=isset($_POST['ip120']) && is_numeric($_POST['ip120']) ? $_POST['ip120'] : SVC_IP120;
	
	$cnt="<?php \r\n";
	$cnt.="define('SVC_OPTIMIZE',$optimize); \r\n";
	$cnt.="define('SVC_IP8',$ip8); \r\n";
	$cnt.="define('SVC_IP101',$ip101); \r\n";
	$cnt.="define('SVC_IP103',$ip103); \r\n";
	$cnt.="define('SVC_IP106',$ip106); \r\n";
	$cnt.="define('SVC_IP108',$ip108); \r\n";
	$cnt.="define('SVC_IP110',$ip110); \r\n";
	$cnt.="define('SVC_IP115',$ip115); \r\n";
	$cnt.="define('SVC_IP120',$ip120); \r\n";
	$cnt.="?>";
	
	$fp=fopen($file,"w");
	fwrite($fp,$cnt);
	fclose($fp);
	
	echo '{"success":true,"msg":"设置成功！"}';
?>