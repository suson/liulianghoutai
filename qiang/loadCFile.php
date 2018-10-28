<?php
/*
 * 加载用户中心HTML
 */
	
	require_once "./securityCheck.php";
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	$dir="../service/";
	if(isset($_POST['file']) && $_POST['file'] !=''){
		$file=$dir.$_POST['file'];
		$fp=fopen($file,"r");
		$cnt="";
		while(!feof($fp)){
			$cnt.=fgets($fp,4096);
		}
		fclose($fp);
		echo $cnt;
	}else{
		echo "找不到文件";
	}
?>