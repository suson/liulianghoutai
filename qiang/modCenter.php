<?php
	/*
	 * 修改用户中心HTML
	 */
	require_once "./securityCheck.php";

	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$dir="../service/";
	if(isset($_POST['page']) && $_POST['page'] !='' && isset($_POST['cont'])){
		$file=$dir.$_POST['page'];
		$fp=fopen($file,"w");
		$cnt=$_POST['cont'];
		fwrite($fp,$cnt);
		fclose($fp);
		echo json_encode(array("success"=>true,"msg"=>"保存成功"));
	}else{
		echo json_encode(array("success"=>false,"msg"=>"找不到文件"));;
	}
?>