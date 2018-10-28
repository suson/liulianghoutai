<?php
	session_start();
	$_SESSION['userid']=rand();
	echo $_SESSION['userid'];
	
	
	
	
	
	
	/*
	$oJSON=Array();
	$urls=Array();
	$odrs=Array();
	$odr=Array('status'=>rand(0,1),
		"sday"=>rand(1,365),
		"svcid"=>rand(8,10),
		"odrid"=>rand(0,1),);
	$oJSON['urls']=Array();
	for($i=0;$i<5;$i++){
		$urls['urlid']=rand();
		$urls['free']=rand(0,1);
		for ($j=0;$j<2;$j++){
			foreach ($odr as $key=>$value)
				$odrs[$key]=$value;
				$urls['odrs'][]=$odrs;
				unset($odrs);
		}
		$oJSON['urls'][]=$urls;
		unset($urls);
	}
	echo json_encode($oJSON);
	echo "<br /><hr /><br />";
	$date = "04/30/1973";
list($month, $day, $year) = explode('/', $date);
echo "Month: $month; Day: $day; Year: $year<br />\n";
	echo "<br />";
	echo count( $oJSON['urls']);
	echo "<br />";
	$prcir['103']=109;
	echo $prcir['103'];
	*/
?>