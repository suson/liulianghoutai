<?php
	/*
	 * 加载网站HTML 文件
	 */
	require_once "./securityCheck.php";

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	$dir='../';
	if(isset($_POST['file']) && $_POST['file'] !=''){
		$file=$dir.$_POST['file'];
		$fp=fopen($file,"r");
		$cnt="";
		while(!feof($fp)){
			$cnt.=fgets($fp,4096);
		}
		echo $cnt;
		fclose($fp);
	}else{
		echo "找不到文件";
	}
?>