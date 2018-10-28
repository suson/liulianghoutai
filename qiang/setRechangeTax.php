<?php
/*
 * pmoney lmoney score
 * 
 */
	require_once "./securityCheck.php";

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":"登入超时"}');
	
	$file="../service/urlcore/cfg/rechangeTaxcfg.php";
	
	$lmoney= isset($_POST['lmoney']) && is_numeric($_POST['lmoney']) ? $_POST['lmoney'] : LMONEY ;
	$pmoney= isset($_POST['pmoney']) && is_numeric($_POST['pmoney']) ? $_POST['pmoney'] : PMONEY ;
	$score= isset($_POST['score']) && is_numeric($_POST['score']) ? $_POST['score'] : SCORE ;
	$pday=isset($_POST['pday']) && is_numeric($_POST['pday']) ? $_POST['pday'] : PDAY;
	
	$cnt="<?php \r\n";
	$cnt .="define('LMONEY',$lmoney); \r\n";
	$cnt .="define('PMONEY',$pmoney); \r\n";
	$cnt .="define('SCORE',$score); \r\n";
	$cnt .="define('PDAY',$pday); \r\n";
	$cnt .="?>";
	
	$fp=fopen($file,"w");
	fwrite($fp,$cnt);
	fclose($fp);
	echo '{"success":true,"msg":"设置成功！"}';
?>