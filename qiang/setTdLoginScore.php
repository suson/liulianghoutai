<?php
	require_once "./securityCheck.php";

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');

	$file="../service/urlcore/cfg/tdlogincfg.php";
	if(isset($_POST['score']) && is_numeric($_POST['score'])){
		$score=$_POST['score'];
		$cnt="<?php define('TDLOGINSCORE',$score); ?>";
		
		$fp=fopen($file,"w+");
		fwrite($fp,$cnt);
		fclose($fp);
		echo '{"success":true,"msg":"设置成功！"}';
	}
?>