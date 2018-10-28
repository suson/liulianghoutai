<?php
	//$i->urlid,$i->usepop,$i->turl,$i->useturl,$i->furls,$i->usefurl
	//opm='{"urlid":网址ID,"usepop":开启弹窗,"turl":"目标网址","useturl":"开启目标网址","furls":"来源网址","usefurl":"开启来源网址"}'
	
require_once '../dbcfg.php';

	$HOST=HOST;
	$USER=USER;
	$PWD=PWD;
	$DATABASE=DATABASE;
	$TABLE=TB_URL;
	//echo $_GET['opm'];
	if(isset($_GET['opm']) && $_GET['opm'] !="")
	{
		$stropm=$_GET['opm'];
		$opm=str_replace("&","@",$stropm);
		echo $opm;
		//$opm=stripslashes($_GET['opm']);
		//$opm=stripslashes($opm);
		//$json=json_decode($opm);
		//$furls=str_replace("@","&",$furls);
	}
?>