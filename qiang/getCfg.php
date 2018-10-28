<?php

	require_once "./securityCheck.php";
	require_once "../service/urlcore/cfg/gradeScorecfg.php";
	require_once "../service/urlcore/cfg/rechangeTaxcfg.php";
	require_once "../service/urlcore/cfg/urlLimitcfg.php";
	require_once "../service/urlcore/cfg/tdlogincfg.php";
	require_once "../service/urlcore/cfg/svcipcfg.php";
	require_once "../service/urlcore/cfg/servicecfg.php";
	
	header('Content-Type:text/html;charset=UTF-8');
	securityCheck() or exit('{"success":false,"msg":"登入超时"}');
	
	$cfg=$_GET['cfg'];
	
	if($cfg =='cfgservice' ){
		echo json_encode(array(CFG_OPTIMIZE/100,CFG_IP8/100,CFG_IP101/100,CFG_IP103/100,CFG_IP106/100,CFG_IP108/100,CFG_IP110/100,CFG_IP115/100,CFG_IP120/100));
	}
	if($cfg == 'GradeScore'){
		echo json_encode(array(GRADESCORE1,GRADESCORE2,GRADESCORE3,GRADESCORE4));
	}
	if($cfg == "tdloginscore"){
		echo json_encode(array(TDLOGINSCORE));
	}
	if($cfg == "rechangetax"){
		echo json_encode(array(0,LMONEY,PMONEY,SCORE,PDAY));
	}
	if($cfg == "svcIPs"){
		echo json_encode(array(SVC_OPTIMIZE,SVC_IP8,SVC_IP101,SVC_IP103,SVC_IP106,SVC_IP108,SVC_IP110,SVC_IP115,SVC_IP120));
	}
	if($cfg == "addurllimit"){
		echo json_encode(array(LV1_URLLIMIT,LV2_URLLIMIT,LV3_URLLIMIT,LV4_URLLIMIT,LV5_URLLIMIT));
	}
?>