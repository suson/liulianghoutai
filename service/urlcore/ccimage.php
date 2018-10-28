<?php
	session_start();
	
	if(isset($_SESSION['v'])){
		$txt=$_SESSION['v'];
		$len=strlen($txt);
		$y=floor(64/$len);
		$oImage=imagecreatetruecolor(64,20);
		$bg=imagecolorallocate($oImage,246,246,246);
		$tcolor=imagecolorallocate($oImage,255,0,0);
		imagefill($oImage,0,0,$bg);
		imagestring($oImage,14,$y,3,$txt,$tcolor);
		header('Content-type: image/png');
		imagepng($oImage);
		imagedestroy($oImage);
	}else{
		$txt='fail';
		$oImage=imagecreatetruecolor(64, 20);
		$bg=imagecolorallocate($oImage,246,246,246);
		$tcolor=imagecolorallocate($oImage,255,0,0);
		imagefill($oImage,0,0,$bg);
		imagestring($oImage,10,20,10,$txt,$tcolor);
		header('Content-type: image/png');
		imagepng($oImage);
		imagedestroy($oImage);
	}
?>