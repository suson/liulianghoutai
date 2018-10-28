<?php
session_start ();
if (isset ( $_SESSION ['cc'] )) {
	$txt = $_SESSION ['cc'];
	$len = strlen ( $txt );
	$x = (64 - (floor ( 64 / $len )) * $len);
	$oImage = imagecreatetruecolor ( 64, 20 );
	$bg = imagecolorallocate ( $oImage, 246, 246, 246 );
	$tcolor = imagecolorallocate ( $oImage, 255, 0, 0 );
	imagefill ( $oImage, 0, 0, $bg );
	imagestring ( $oImage, 5, $x, 3, $txt, $tcolor );
	imagefilter ( $oImage, IMG_FILTER_GAUSSIAN_BLUR );
	header ( 'Content-type: image/png' );
	imagepng ( $oImage );
	imagedestroy ( $oImage );
} else {
	$txt = 'file';
	$oImage = imagecreatetruecolor ( 64, 20 );
	$bg = imagecolorallocate ( $oImage, 246, 246, 246 );
	$tcolor = imagecolorallocate ( $oImage, 255, 0, 0 );
	imagefill ( $oImage, 0, 0, $bg );
	imagestring ( $oImage, 5, 10, 3, $txt, $tcolor );
	header ( 'Content-type: image/png' );
	imagepng ( $oImage );
	imagedestroy ( $oImage );
}
?>