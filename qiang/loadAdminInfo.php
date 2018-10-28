<?php
	require_once '../dbcfg.php';
	require_once "./securityCheck.php";
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('<h1 style="color:red;" >登入超时....</h1>');
	
	$link=@mysql_connect(HOST,USER,PWD);
	@mysql_select_db(DBNAME);
	
	$result=@mysql_query("SELECT * FROM admins WHERE name='admin';",$link);
	if(!!$result){
		$row=@mysql_fetch_array($result,MYSQL_ASSOC);
		$cnt  ='<div style="margin-top:10px;"><table border=0><tbody><tr><th style="color:blue;font-weight:bold;width:50px;font-size:21px;">'.$row['name'];
		$cnt .='</th><td style="font-size:11px;">&nbsp;&nbsp;你好！</td><td style="width:45%;"></td><td style="font-weight:bold;">您上次登入时间：</td><th>'.$row['ltime'];
		$cnt .='</th></tr></tbody></table>';
		$cnt .='<table style="margin-top:20px;margin-left:10px;"><tbody><tr> <th style="font-weight:bold;width:150px;">当前已注册用户数:</th><td style="color:blue;">'.$row['countuser'];
		$cnt .='</td></tr></tbody></table>';
		$cnt .='<table style="margin-top:20px;margin-left:10px;"><tbody><tr> <th style="font-weight:bold;width:150px;">当前网址总数:</th><td style="color:blue;">'.$row['counturl'];
		$cnt .='</td></tr></tbody></table>';
		$cnt .='<table style="margin-top:20px;margin-left:10px;"><tbody><tr> <th style="font-weight:bold;width:150px;">用户充值总额:</th><td style="color:blue;">'.$row['countrechange'];
		$cnt .='</td></tr></tbody></table>';
		$cnt .='<table style="margin-top:20px;margin-left:10px;"><tbody><tr> <th style="font-weight:bold;width:150px;">用户消费总额:</th><td style="color:blue;">'.$row['countexp'];
		$cnt .='</td></tr></tbody></table>';
		$cnt .='</div>';

	}
	echo $cnt;
?>