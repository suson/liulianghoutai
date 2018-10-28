<?php
/*
 * 保存建议反馈
 */
	$msg= isset($_POST['msg']) ? $_POST['msg'] : "";
	$qq= isset($_POST['qq']) ? $_POST['qq'] : "";
	$email= isset($_POST['email']) ? $_POST['email'] : "";
	$app= isset($_POST['app']) ? $_POST['app'] : "";
	
	$file="./lookfeedback.html";
	if($msg!=""){
		$fp=fopen($file,"a");
		$cnt="\r\n <div><table><tbody><tr><th>留言内容:</th><td>$msg</td></tr>";
		$cnt .="<tr><th>留言者QQ：</th><td>$qq</td></tr>";
		$cnt .="<tr><th>留言者E-mail:</th><td>$email</td></tr>";
		$cnt .="</tbody></table></div>";
		fwrite($fp,$cnt);
		fclose($fp);
	}
?>