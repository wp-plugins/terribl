<?php
$img_src = 'TERRIBL-16x16.gif';
$all_colors = Array('black' => Array(0,0,0),
					'red' => Array(255,0,0),
					'blue' => Array(0,0,255),
					'white' => Array(255,255,255),
					'trans' => Array(1,2,3));
import_request_variables("gP", "img_");
if (isset($_GET['Impression_URI'])) {
	$conf_path = 'wp-load.php';
	while (!file_exists($conf_path) && strlen($conf_path) < 30)
		$conf_path = '../'.$conf_path;
	if (file_exists($conf_path)) {
		$Visits_Impressions = 'StatImpressions';
		include($conf_path);
	}
	$img_t = (isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_GET['Impression_URI']));
	if (!isset($img_z)) $img_z = 4;
	if (!in_array($img_b.'', array_keys($all_colors))) $img_b = 'trans';
	if (!in_array($img_c.'', array_keys($all_colors))) $img_c = 'blue';
	$w = strlen($img_t) * imagefontwidth($img_z) + 2;
	$h = imagefontheight($img_z) + 2;
	$txtimg = imagecreate($w, $h);
	$back = imagecolorallocate($txtimg, $all_colors[$img_b][0], $all_colors[$img_b][1], $all_colors[$img_b][2]);
	$fore = imagecolorallocate($txtimg, $all_colors[$img_c][0], $all_colors[$img_c][1], $all_colors[$img_c][2]);
	if ($img_b == 'trans')
		imagecolortransparent($txtimg, $back);
	header("Content-type: image/gif");
	imagestring($txtimg, $img_z, 1, 1, $img_t, $fore);
	imagegif($txtimg);
	imagedestroy($txtimg);
} elseif (file_exists($img_src)) {
	$imageInfo = getimagesize($img_src);
	header("Content-type: ".$imageInfo['mime']);
	$img = @imagecreatefromgif($img_src);
	imagegif($img);
	imagedestroy($img);
} else echo $img_src.' not found!';
?>
