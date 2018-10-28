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
	if(isset($_GET['opm']) && $_GET['opm'] !=""){
		$opm=stripslashes($_GET['opm']);
		$json=json_decode($opm);
		if($json != null){
			$link=mysql_connect($HOST,$USER,$PWD);
			mysql_select_db($DATABASE,$link);
			
			$urlid=$json->urlid;
			$usepop=$json->usepop;
			$turl=$json->turl;
			$useturl=$json->useturl;
			$furls=$json->furls;
			$usefurl=$json->usefurl;
			
			
			$fields=" SET usepop=$usepop,turl='$turl',furls='$furls',useturl=$useturl,usefurl=$usefurl";
			$qUpdateUrl="UPDATE $TABLE $fields WHERE urlid=$urlid ;";
			$bSucc=mysql_query($qUpdateUrl,$link);
			
			if($bSucc){
				echo 0;
			}else{
				echo -1;
			}
		}else{
			echo -1;
		}
	}
?>