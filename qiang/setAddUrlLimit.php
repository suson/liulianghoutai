<?php
	/*
	 * 设置用户能添加的网址上限
	 */
	require_once "./securityCheck.php";

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$lv[]= isset($_POST['lv1']) &&$_POST['lv1'] !="" ? $_POST['lv1'] : 15;
	$lv[]= isset($_POST['lv2']) &&$_POST['lv2'] !="" ? $_POST['lv2'] : 30;
	$lv[]= isset($_POST['lv3']) &&$_POST['lv3'] !="" ? $_POST['lv3'] : 60;
	$lv[]= isset($_POST['lv4']) &&$_POST['lv4'] !="" ? $_POST['lv4'] : 100;
	$lv[]= isset($_POST['lv5']) &&$_POST['lv5'] !="" ? $_POST['lv5'] : 150;
	
	$cnt="<?php \r\n";
	$cnt .= "define('LV1_URLLIMIT',".$lv[0]."); \r\n";
	$cnt .= "define('LV2_URLLIMIT',".$lv[1]."); \r\n";
	$cnt .= "define('LV3_URLLIMIT',".$lv[2]."); \r\n";
	$cnt .= "define('LV4_URLLIMIT',".$lv[3]."); \r\n";
	$cnt .= "define('LV5_URLLIMIT',".$lv[4]."); \r\n";
	$cnt .="?>";
	
	$file="../service/urlcore/cfg/urlLimitcfg.php";
	$fp=fopen($file,"w");
	fwrite($fp,$cnt);
	fclose($fp);
	
	echo '{"success":true,"msg":"设置成功！"}';
?>