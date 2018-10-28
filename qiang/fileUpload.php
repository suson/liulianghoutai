<?php
/*
 * Upload file to ../up/
 */
	
	require_once "./securityCheck.php";
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":"登入超时"}');
	
	$dir="../up/";
	
	if($_FILES['file']["error"] == UPLOAD_ERR_OK){
		$tmpName=basename($_FILES['file']['name']);
		$Xname=strstr($tmpName,'.');
		if(isset($_POST['name']) && $_POST['name'] !=""){
			$name=$_POST['name']."$Xname";
		}else{
			$name=$tmpName;
		}
		
		$file=$dir.$name;
		if(file_exists($file)){
			@unlink($file);
		}
		$bSuc=move_uploaded_file($_FILES['file']['tmp_name'],$file);
		if($bSuc){
			echo '{"success":true,"msg":"上传文件'. $name .'成功！"}';
		}else{
			echo '{"success":false,"msg":"上传文件'. $name .'失败！"}';
		}
	}else{
		echo '{"success":false,"msg":"上传文件失败！"}';
	}
?>