<?php
	require_once "./securityCheck.php";

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$file="../service/urlcore/cfg/gradeScorecfg.php";
	
	$lv1= isset($_POST['lv2']) && is_numeric($_POST['lv2']) ? $_POST['lv2'] : 2000;
	$lv2= isset($_POST['lv3']) && is_numeric($_POST['lv3']) ? $_POST['lv3'] : 8000;
	$lv3= isset($_POST['lv4']) && is_numeric($_POST['lv4']) ? $_POST['lv4'] : 30000;
	$lv4= isset($_POST['lv5']) && is_numeric($_POST['lv5']) ? $_POST['lv5'] : 100000;
	
	$cnt="<?php \r\n";
	$cnt .="define('GRADESCORE1',$lv1); \r\n";
	$cnt .="define('GRADESCORE2',$lv2); \r\n";
	$cnt .="define('GRADESCORE3',$lv3); \r\n";
	$cnt .="define('GRADESCORE4',$lv4); \r\n";
	$cnt .="?>";
	
	$fp=fopen($file,"w");
	fwrite($fp,$cnt);
	fclose($fp);
	
	echo '{"success":true}';
?>