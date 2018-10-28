<?php
$path_Class = realpath(dirname(__FILE__).'/Include/Class');
$path_Interface = realpath(dirname(__FILE__).'/Include/Interface');
$path_Function = realpath(dirname(__FILE__).'/Include/Functions');
$path_alipay = realpath(dirname(__FILE__).'/Include/alipay');
$path_alipay_class = realpath(dirname(__FILE__).'/Include/alipay/class');
$path = $path_Class . PATH_SEPARATOR . $path_Interface . PATH_SEPARATOR . $path_Function. PATH_SEPARATOR .  $path_alipay. PATH_SEPARATOR .  $path_alipay_class;
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
	//echo get_include_path();
	require_once('common.func.php');
	

	function __autoload($className){
		require_once($className.'.class.php');
	}
	$mysqli = new mysqli('localhost','root','','liuliang5');
	$svcfg = new CServiceConfig($mysqli);
	$cs = new CService($mysqli,$svcfg);
	$func = array(function() use($cs) {return  $cs->getIP();});
		echo $func['0']();
	try {
		unset($svcfg);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
?>