<?php
	/*
	 * 配置服务单价
	 */
	require_once "./securityCheck.php";
	require_once "../service/urlcore/cfg/servicecfg.php";
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":""}');
	
	$cfg_optimize= isset($_POST['optimize']) && is_numeric($_POST['optimize']) ? $_POST['optimize'] * 100 : CFG_OPTIMIZE;
	$cfg_ip8= isset($_POST['ip8']) && is_numeric($_POST['ip8']) ? $_POST['ip8'] * 100 : CFG_IP8;
	$cfg_ip101= isset($_POST['ip101']) && is_numeric($_POST['ip101']) ? $_POST['ip101'] * 100 : CFG_IP101;
	$cfg_ip103= isset($_POST['ip103']) && is_numeric($_POST['ip103']) ? $_POST['ip103'] * 100 : CFG_IP103;
	$cfg_ip106= isset($_POST['ip106']) && is_numeric($_POST['ip106']) ? $_POST['ip106'] * 100 : CFG_IP106;
	$cfg_ip108= isset($_POST['ip108']) && is_numeric($_POST['ip108']) ? $_POST['ip108'] * 100 : CFG_IP108;
	$cfg_ip110= isset($_POST['ip110']) && is_numeric($_POST['ip110']) ? $_POST['ip110'] * 100 : CFG_IP110;
	$cfg_ip115= isset($_POST['ip115']) && is_numeric($_POST['ip115']) ? $_POST['ip115'] * 100 : CFG_IP115;
	$cfg_ip120= isset($_POST['ip120']) && is_numeric($_POST['ip120']) ? $_POST['ip120'] * 100 : CFG_IP120;

	$cfg="<?php \r\n";
	$cfg .="define('CFG_OPTIMIZE',$cfg_optimize); \r\n";
	$cfg .="define('CFG_IP8',$cfg_ip8); \r\n";
	$cfg .="define('CFG_IP101',$cfg_ip101); \r\n";
	$cfg .="define('CFG_IP103',$cfg_ip103); \r\n";
	$cfg .="define('CFG_IP106',$cfg_ip106); \r\n";
	$cfg .="define('CFG_IP108',$cfg_ip108); \r\n";
	$cfg .="define('CFG_IP110',$cfg_ip110); \r\n";
	$cfg .="define('CFG_IP115',$cfg_ip115); \r\n";
	$cfg .="define('CFG_IP120',$cfg_ip120); \r\n";
	$cfg .="?>";
	
	$file="../service/urlcore/cfg/servicecfg.php";
	$fp=fopen($file,"w");
	fwrite($fp,$cfg);
	fclose($fp);
	
	echo '{"success":true,"msg":"配置成功！"}';
?>