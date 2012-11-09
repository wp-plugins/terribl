<?php
$img_src = 'TERRIBL-16x16.gif';
$all_colors = Array('black' => Array(0,0,0),
					'red' => Array(255,0,0),
					'blue' => Array(0,0,255),
					'white' => Array(255,255,255),
					'trans' => Array(1,2,3));
import_request_variables("gP", "img_");
if (isset($_GET['Impression_URI'])) {
	$img_t = (isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_GET['Impression_URI']));
	$TERRIBL_HTTP_REFERER = (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$img_t);
	$TERRIBL_REFERER_Parts = explode('/', $TERRIBL_HTTP_REFERER.'//'.$img_t);
	if (($TERRIBL_REFERER_Parts[2] == $img_t) && isset($_GET['Return_URL'])) {
		$TERRIBL_REFERER_Part2 = $TERRIBL_REFERER_Parts[2];
		$TERRIBL_REFERER_Parts = explode('/', ((substr($_GET['Return_URL'],0,7)=='http://'||substr($_GET['Return_URL'],0,8)=='https://')?'':'http://').$_GET['Return_URL']);
		$img_src = 'blocked.gif';
		$Visits_Impressions = 'StatReturn';
	} else
		$Visits_Impressions = 'StatImpressions';
	$conf_path = 'wp-load.php';
	while (!file_exists($conf_path) && strlen($conf_path) < 30)
		$conf_path = '../'.$conf_path;
	if (file_exists($conf_path))
		include($conf_path);
	if ($TERRIBL_REFERER_Part2 == $img_t) {
		if (file_exists($img_src)) {
			$imageInfo = getimagesize($img_src);
			header("Content-type: ".$imageInfo['mime']);
			$img = @imagecreatefromgif($img_src);
		}
	} else {
		if (!isset($img_z)) $img_z = 4;
		if (!in_array($img_b.'', array_keys($all_colors))) $img_b = 'trans';
		if (!in_array($img_c.'', array_keys($all_colors))) $img_c = 'blue';
		$w = strlen($img_t) * imagefontwidth($img_z) + 2;
		$h = imagefontheight($img_z) + 2;
		$img = imagecreate($w, $h);
		$back = imagecolorallocate($img, $all_colors[$img_b][0], $all_colors[$img_b][1], $all_colors[$img_b][2]);
		$fore = imagecolorallocate($img, $all_colors[$img_c][0], $all_colors[$img_c][1], $all_colors[$img_c][2]);
		if ($img_b == 'trans')
			imagecolortransparent($img, $back);
		header("Content-type: image/gif");
		imagestring($img, $img_z, 1, 1, $img_t, $fore);
	}
	imagegif($img);
	imagedestroy($img);
} elseif (file_exists($img_src)) {
	$imageInfo = getimagesize($img_src);
	header("Content-type: ".$imageInfo['mime']);
	$img = @imagecreatefromgif($img_src);
	imagegif($img);
	imagedestroy($img);
} else echo $img_src.' not found!';
?>
