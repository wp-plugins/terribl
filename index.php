<?php
/*
Plugin Name: ELI's SHAREABLE Widget and In-Bound Link Tracking
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/terribl-widget/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/category/my-plugins/
Contributors: scheeeli
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8VWNB5QEJ55TJ
Description: This plugin is not TERRIBL any more, it's SHAREABLE! It still Tracks Every Referer and Returns In-Bound Links but now it also makes it easy to distribute links to your site. Place the new SHAREABLE Widget on your sidebar to display a link to your sites, complete with easy-to-copy link code, that others can put on their own site.
Version: 1.2.11.20
*/
$TERRIBL_Version='1.2.11.20';
$TERRIBL_plugin_dir='TERRIBL';
$TERRIBL_HeadersError = '';
function TERRIBL_admin_notices() {
	global $TERRIBL_HeadersError;
	echo $TERRIBL_HeadersError;
}
if (headers_sent($filename, $linenum)) {
$_SESSION['eli_debug_microtime']['TERRIBL_headers_sent'] = microtime(true);
	if (!$filename)
		$filename = 'an unknown file';
	if (!is_numeric($linenum))
		$linenum = 'unknown';
    $TERRIBL_HeadersError = "<div class=\"error\">Headers already sent in $filename on line $linenum.<br />This is not good, it may just be some poorly written plugin but Headers should not have been sent at this point.<br />Check the code in the above mentioned file to fix this problem.</div>";
	add_action('admin_notices', 'TERRIBL_admin_notices');
} elseif (!isset($_SESSION))
	session_start();
$_SESSION['eli_debug_microtime']['include(TERRIBL)'] = microtime(true);
/**
 * TERRIBL Main Plugin File
 * @package TERRIBL
*/
/*  Copyright 2011 Eli Scheetz (email: wordpress@ieonly.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'] = (isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:preg_replace('/.+\:\/\/([^\/]+).*/', '$1', get_option('siteurl'))));
$_SERVER_REQUEST_URI = str_replace('&amp;','&', htmlspecialchars( $_SERVER['REQUEST_URI'] , ENT_QUOTES ) );
$SHAREABLE_script_URI = 'http://'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'].str_replace('shareable=', 'shared=', $_SERVER_REQUEST_URI.(strpos($_SERVER_REQUEST_URI,'?')?'&':'?')).'shareable=';
function TERRIBL_sexagesimal() {
	return (date("y")>9?chr(ord("A")+date("y")-10):date("y")).(date("n")>9?chr(ord("A")+date("n")-10):date("n")).(date("j")>9?chr(ord("A")+date("j")-10):date("j"));
}
$TERRIBL_settings_array = get_option('TERRIBL_settings_array');
if (!isset($TERRIBL_settings_array['div_style']))
	$TERRIBL_settings_array['div_style'] = "";
if (!isset($TERRIBL_settings_array['image_url']))
	$TERRIBL_settings_array['image_url'] = "";
if (!isset($TERRIBL_settings_array['image_style']))
	$TERRIBL_settings_array['image_style'] = "";
if (!isset($TERRIBL_settings_array['link_text']))
	$TERRIBL_settings_array['link_text'] = "";
if (!isset($TERRIBL_settings_array['text_style']))
	$TERRIBL_settings_array['text_style'] = "";
function SHAREABLE_link($img_url = '/?shareable=img') {
	global $TERRIBL_settings_array, $TERRIBL_plugin_dir, $TERRIBL_font_size;
	if ($TERRIBL_settings_array['image_url'])
		$image = SHAREABLE_image_info($TERRIBL_settings_array['image_url']);
	else
		$image = array('size'=>'width="'.(strlen($_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']) * @imagefontwidth($TERRIBL_font_size) + 2).'" height="'.(@imagefontheight($TERRIBL_font_size) + 2).'"');
	return '<div style="'.htmlentities($TERRIBL_settings_array['div_style']).'"><a title="'.get_bloginfo('name').'" style="'.$TERRIBL_settings_array['text_style'].'" target="_blank" href="'.str_replace('shareable=', '', $img_url).'"><img '.$image['size'].' alt="'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'].'" src="'.($img_url).'" style="'.$TERRIBL_settings_array['image_style'].'">'.$TERRIBL_settings_array['link_text'].'</a></div>';
}
function SHAREABLE_write_cache($filename, $data) {
	$return = false;
	if (function_exists('file_put_contents'))
		$return = @file_put_contents($filename, $data);
	elseif ($fp = @fopen($filename, 'w')) {
		$return = @fwrite($fp, $data);
		@fclose($fp);
	}
	return $return;
}
function SHAREABLE_get_ext($filename) {
	$nameparts = explode('?', $filename.'?');
	$nameparts = explode('.', str_replace('/', '.', '/0'.$nameparts[0]));
	return strtolower($nameparts[(count($nameparts)-1)]);
}
function SHAREABLE_image_info($filename) {
	global $TERRIBL_img_exts;
	$ext = SHAREABLE_get_ext($filename);
	$headers = array('ext' => "$ext", 'cache' => dirname(__FILE__).'/images/cache_'.md5($filename).".$ext", 'type' => "Content-type: image/$ext", 'size' => '');
	$info = @getimagesize((file_exists($headers["cache"])?$headers["cache"]:$filename));
	if (isset($info['mime'])) {
		$headers['type'] = $info['mime'];
		if (!in_array($ext, $TERRIBL_img_exts) && substr($info['mime'], 0, 6) =='image/') {
			$headers['ext'] = SHAREABLE_get_ext($info['mime']);
			$headers['cache'] .= '.'.$headers['ext'];
		}
	}
	if (isset($info[3]))
		$headers['size'] = $info[3];
	return $headers;
}
$TERRIBL_font_size = 5;
$TERRIBL_img_exts = array('gif','png','jpg','jpe','jpeg','tif','tiff', 'svg');
if (isset($_GET['shareable'])) {
	if ($_GET['shareable'] == 'img') {
$_SESSION['eli_debug_microtime']['TERRIBL__GET_shareable_img'] = microtime(true);
		$Visits_Impressions = 'StatImpressions';//$divid = str_replace('.', '_', $_GET['shareable'].'.'.microtime(true));
		TERRIBL_init();
		if ($TERRIBL_settings_array['image_url']) {
			$image = SHAREABLE_image_info($TERRIBL_settings_array['image_url']);
			header($image["type"]);
			if (file_exists($image["cache"]))
				echo TERRIBL_get_URL($image["cache"]);
			else {
				$echo = TERRIBL_get_URL($TERRIBL_settings_array['image_url']);
				echo $echo;
				if (in_array($image["ext"], $TERRIBL_img_exts))
					SHAREABLE_write_cache($image["cache"], $echo);
			}
		} else {
			$all_colors = Array('black' => Array(0,0,0),
						'red' => Array(255,0,0),
						'blue' => Array(0,0,255),
						'white' => Array(255,255,255),
						'trans' => Array(1,2,3)); 
			if (!in_array($img_b.'', array_keys($all_colors))) $img_b = 'trans';
			if (!in_array($img_c.'', array_keys($all_colors))) $img_c = 'blue';
			$w = strlen($_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']) * imagefontwidth($TERRIBL_font_size) + 2;
			$h = imagefontheight($TERRIBL_font_size) + 2;
			$img = imagecreate($w, $h);
			$back = imagecolorallocate($img, $all_colors[$img_b][0], $all_colors[$img_b][1], $all_colors[$img_b][2]);
			$fore = imagecolorallocate($img, $all_colors[$img_c][0], $all_colors[$img_c][1], $all_colors[$img_c][2]);
			if ($img_b == 'trans')
				imagecolortransparent($img, $back);
			header("Content-type: image/gif");
			imagestring($img, $TERRIBL_font_size, 1, 1, $_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'], $fore);
			imagegif($img);
			imagedestroy($img);
		}
		die();
	} else {
$_SESSION['eli_debug_microtime']['TERRIBL__GET_shareable_js'] = microtime(true);
		@header('Content-type: text/javascript');//str_replace("shareable=", "ts='+Math.round((new Date()).getTime() / 1000)+'&shareable=", 
		die("<!--//v$TERRIBL_Version;r=".$_SERVER['HTTP_REFERER']."\ndocument.write('".str_replace("'", "\\'", str_replace("\n", "\\n", str_replace("\\", "\\\\", SHAREABLE_link($SHAREABLE_script_URI.'img'))))."');\n//-->");
	}
}
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) die('You are not allowed to call this page directly.<p>You could try starting <a href="http://'.$_SERVER['SERVER_NAME'].'">here</a>.');
function TERRIBL_install() {
	global $wp_version;
$_SESSION['eli_debug_microtime']['TERRIBL_install_start'] = microtime(true);
	if (version_compare($wp_version, "2.6", "<"))
		die(__("This Plugin requires WordPress version 2.6 or higher"));
	else {
		$MySQL = "CREATE TABLE IF NOT EXISTS `wp_terribl_stats` (
  `StatMonth` date NOT NULL,
  `StatCreated` datetime NOT NULL,
  `StatModified` datetime NOT NULL,
  `StatFirstUserAgent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `StatUserAgent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `StatFirstRemoteAddr` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `StatRemoteAddr` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `StatReferer` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `StatRequestURI` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `StatVisits` bigint(20) unsigned NOT NULL default '0',
  `StatDomain` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
  `StatImpressions` bigint(20) NOT NULL default '0',
  `StatReturn` bigint(20) NULL default '0',
  PRIMARY KEY  (`StatDomain`,`StatMonth`,`StatRequestURI`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		@mysql_query($MySQL);
		if (mysql_errno()) TERRIBL_debug("TERRIBL MySQL CREATE stats\n".mysql_error()."\nSQL:$MySQL");
		$MySQL = "CREATE TABLE IF NOT EXISTS `wp_terribl_blocked` (
  `BlockCreated` datetime NOT NULL,
  `BlockDomain` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
  `BlockReason` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default 'Admin',
  PRIMARY KEY  (`BlockDomain`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		@mysql_query($MySQL);
		if (mysql_errno()) TERRIBL_debug("TERRIBL MySQL CREATE blocked\n".mysql_error()."\nSQL:$MySQL");
	}
$_SESSION['eli_debug_microtime']['TERRIBL_install_end'] = microtime(true);
}
$encode = '/[\?\-a-z\: \.\=\/A-Z\&\_]/';
function TERRIBL_display_header($pTitle) {
	global $TERRIBL_settings_array, $TERRIBL_plugin_dir, $TERRIBL_plugin_home, $TERRIBL_images_path, $TERRIBL_updated_images_path, $TERRIBL_Version;
$_SESSION['eli_debug_microtime']['TERRIBL_display_header_start'] = microtime(true);
	$TERRIBL_menu_groups = array('Main Menu Item placed below <b>Comments</b> and above <b>Appearance</b>','Main Menu Item placed below <b>Settings</b>','Sub-Menu inside the <b>Links</b> Menu Item');
	$menu_opts = '<div class="stuffbox shadowed-box">
		<h3 class="hndle"><span>Menu Item Placement Options</span></h3>
		<div class="inside"><form method="POST" name="TERRIBL_menu_Form">';
	foreach ($TERRIBL_menu_groups as $mg => $TERRIBL_menu_group)
		$menu_opts .= '<div style="float: left; padding: 4px;" id="menu_group_div_'.$mg.'"><input type="radio" name="TERRIBL_menu_group" value="'.$mg.'"'.($TERRIBL_settings_array['menu_group']==$mg?' checked':'').' onchange="document.TERRIBL_menu_Form.submit();" />'.$TERRIBL_menu_group.'</div>';
	$wait_img_URL = $TERRIBL_images_path.'wait.gif';
	echo '<style>
.rounded-corners {margin: 10px; padding: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border: 1px solid #000000;}
.shadowed-box {box-shadow: -3px 3px 3px #666666; -moz-box-shadow: -3px 3px 3px #666666; -webkit-box-shadow: -3px 3px 3px #666666;}
.popup-box {background-color: #FFFFCC; display: none; position: absolute; left: 0px; z-index: 10;}
.sidebar-box {background-color: #CCCCCC;}
.sidebar-links {padding: 0 15px; list-style: none;}
.shadowed-text {text-shadow: #0000FF -1px 1px 1px;}
.sub-option {float: left; margin: 3px 5px;}
.inside p {margin: 10px;}
#right-sidebar {float: right; margin-right: 10px; width: 290px;}
#main-section {margin-right: 310px;}
</style>
<script>
function showhide(id) {
	divx = document.getElementById(id);
	if (divx.style.display == "none")
		divx.style.display = "";
	else
		divx.style.display = "none";
}
function showhideRefDiv(domain) {
	refdiv = document.getElementById(\'RefDiv_\'+domain);
	if (refdiv) {
		if (refdiv.style.display==\'block\') {
			refdiv.style.display=\'none\';
		} else {
			refdiv.style.display=\'block\';
		}
	}
}
function recheckDomain(domain) {
	document.getElementById(\'img_\'+domain).src=\''.$TERRIBL_images_path.'wait.gif\';
	var newimg = new Image();
	newimg.onload = function() {document.getElementById(\'img_\'+domain).src=newimg.src;};
	newimg.src=\''.$TERRIBL_images_path.'index.php?Impression_URI&Return_URL=\'+domain;
}
function recheckAllDomains() {
	refimg = document.getElementsByName(\'img_Domain\');
	for (var i = 0; i < refimg.length; i++) {
		if (refimg[i].src==\''.$TERRIBL_images_path.'checked.gif\') {
			recheckDomain(refimg[i].id.substr(4));
		}
	}
}
</script><h1>'.$pTitle.'</h1>
<div id="right-sidebar" class="metabox-holder">
	<div id="pluginupdates" class="shadowed-box stuffbox"><h3 class="hndle"><span>Plugin Updates</span></h3>
		<div id="findUpdates"><center>Searching for updates ...<br /><img src="'.$wait_img_URL.'" alt="Wait..." /><br /><input type="button" value="Cancel" onclick="document.getElementById(\'findUpdates\').innerHTML = \'Could not find server!\';" /></center></div>
	<script type="text/javascript" src="'.$TERRIBL_plugin_home.$TERRIBL_updated_images_path.'?js='.$TERRIBL_Version.'&p='.$TERRIBL_plugin_dir.'"></script>
	</div>
	<div id="pluginlinks" class="shadowed-box stuffbox"><h3 class="hndle"><span>Plugin Links</span></h3>
		<DIV class="inside">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<table cellpadding=0 cellspacing=0><tr><td>
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="8VWNB5QEJ55TJ">
				<input type="image" src="http://wordpress.ieonly.com/wp-content/uploads/btn_donateCC_WIDE.gif" border="0" name="submit" alt="Make a Donation with PayPal">
			</td></tr><tr><td>
				<ul class="sidebar-links">
					<li>Included with this Plugin<ul class="sidebar-links">
						<li style="float: right;"><a href="javascript:showhide(\'div_Readme\');">Readme File</a>
						<li><a href="javascript:showhide(\'div_License\');">License File</a>
					</ul></li>
					<li style="float: right;">on <a target="_blank" href="'.$TERRIBL_plugin_home.'category/my-plugins/">my Blog</a><ul class="sidebar-links">
						<li><a target="_blank" href="'.$TERRIBL_plugin_home.'category/my-plugins/terribl-widget/">TERRIBL URI</a>
					</ul></li>
					<li>on <a target="_blank" href="http://wordpress.org/extend/plugins/profile/scheeeli">WordPress.org</a><ul class="sidebar-links">
						<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($TERRIBL_plugin_dir).'/faq/">TERRIBL FAQs</a>
						<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($TERRIBL_plugin_dir).'/stats/">TERRIBL Stats</a>
						<li><a target="_blank" href="http://wordpress.org/tags/'.strtolower($TERRIBL_plugin_dir).'">Forum Posts</a>
					</ul></li>
				</ul>
			</td></tr></table>
		</form>
		</div>
	</div>
	'.$menu_opts.'</form><br style="clear: left;" /></div></div>
</div>
<div id="admin-page-container">
	<div id="main-section" class="metabox-holder">';
	TERRIBL_display_File('Readme');
	TERRIBL_display_File('License');
$_SESSION['eli_debug_microtime']['TERRIBL_display_header_end'] = microtime(true);
}
function TERRIBL_display_File($dFile) {
$_SESSION['eli_debug_microtime']['TERRIBL_display_File_start'] = microtime(true);
	if (file_exists(dirname(__FILE__).'/'.strtolower($dFile).'.txt')) {
		echo '<div id="div_'.$dFile.'" class="shadowed-box rounded-corners sidebar-box" style="display: none;"><a class="rounded-corners" style="float: right; padding: 0 4px; margin: 0 0 0 30px; text-decoration: none; color: #CC0000; background-color: #FFCCCC; border: solid #FF0000 1px;" href="javascript:showhide(\'div_'.$dFile.'\');">X</a><h1>'.$dFile.' File</h1><textarea disabled="yes" width="100%" style="width: 100%;" rows="20">';
		include(strtolower($dFile).'.txt');
		echo '</textarea></div>';
	}
$_SESSION['eli_debug_microtime']['TERRIBL_display_File_end'] = microtime(true);
}
if (!function_exists('ur1encode')) { function ur1encode($url) {
	global $encode;
	return preg_replace($encode, '\'%\'.substr(\'00\'.strtoupper(dechex(ord(\'\0\'))),-2);', $url);
}}
function TERRIBL_strip_magic($var) {
//	function wp_magic_quotes()
	if (is_array($var))
		foreach ($var as $key => $val)
			$var[$key] = TERRIBL_strip_magic($val);
	else
		$var = stripcslashes($var);
	return $var;
}
function TERRIBL_mysql_report($MySQL, $MyTitle = 'Stats') {
	$result = mysql_query($MySQL);
	if (mysql_errno()) {
		$MyTitle = 'SQL ERROR';
		$echo = '<li>ERROR: '.TERRIBL_debug(mysql_error(), true).'<li>SQL:<br><textarea disabled="yes" cols="65" rows="15">'.$MySQL.'</textarea>';//only used for debugging.
	} else {
		if ($rs = mysql_fetch_assoc($result)) {
//			$echo .= '<div style="position: relative; background-color: #CCFFCC;" class="shadowed-box rounded-corners"><div style="position: relative;">';
			$echo = '<table border=1 cellspacing=0 cellpadding=1 style="padding: 1px;"><tr>';
			foreach ($rs as $field => $value)
				$echo .= '<td style="padding: 1px;"><div style="width: 100%; background-color: #000000;"><font color="#FFFFFF"><b>&nbsp;'.str_replace('<br />', '&nbsp;<br />&nbsp;', $field).'&nbsp;</b></font></div></td>';
			do {
				$echo .= '</tr><tr>';
				foreach ($rs as $field => $value)
					$echo .= '<td>'.$value.'</td>';
			} while ($rs = mysql_fetch_assoc($result));
			$echo .= '</tr></table>';
		} else
			$echo = '<h3>No Stats Info Available At This Time!</h3>';
	}
	return '<div id="pluginlinks" class="shadowed-box stuffbox"><h3 class="shadowed-text">'.$MyTitle.'</h3><DIV class="inside">'.$echo.'</div></div>';
}
function SHAREABLE_Settings() {
	global $TERRIBL_settings_array, $TERRIBL_SQL_SELECT, $TERRIBL_plugin_dir, $TERRIBL_images_path;
$_SESSION['eli_debug_microtime']['SHAREABLE_Settings_start'] = microtime(true);
	TERRIBL_display_header('SHAREABLE Link Settings');
	echo '<form method="POST" name="TERRIBL_Form"><div class="stuffbox shadowed-box"><h3 class="hndle"><span>Settings Form</span></h3><div class="inside"><table cellpadding=4 style="width: 100%;"><tr><td valign="top" rowspan="2"><b>Optional Div Style</b> (CSS for the Div Tag e.g. background-color: #6CF;):<br /><input type="text" name="div_style" value="'.htmlentities($TERRIBL_settings_array['div_style']).'" style="width: 100%;" /><br /><br /><b>Alternate Image URL</b> (leave blank for an Image of your Domain Name):<br /><input type="text" name="image_url" value="'.htmlentities($TERRIBL_settings_array['image_url']).'" style="width: 100%;" /><br /><br /><b>Optional Image Style</b> (CSS for the Image Tag e.g. float: left;):<br /><input type="text" name="image_style" value="'.htmlentities($TERRIBL_settings_array['image_style']).'" style="width: 100%;" /><br /><br /><b>Optional Link Text</b> (text to be displayed in the link):<br /><input type="text" name="link_text" value="'.htmlentities($TERRIBL_settings_array['link_text']).'" style="width: 100%;" /><br /><br /><b>Optional Link Style</b> (CSS for the Anchor Tag e.g. color: #C30;):<br /><input type="text" name="text_style" value="'.htmlentities($TERRIBL_settings_array['text_style']).'" style="width: 100%;" /></td><td valign="top" align="right" width="200"><b>Example of your link:</b><br /><br /><div style="text-align: left;">'.SHAREABLE_link().'</div></td></tr><tr><td valign="bottom" align="right" width="200"><input type="submit" value="Update Setting" class="button-primary" /></td></tr></table><br /><b>Copy the HTML in this box and give it to others who wish to link to your site</b><br /><textarea style="width: 100%; border: solid #00C 2px;" rows="3">'.htmlentities('<script src="http://'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'].'/?shareable=js"></script>').'</textarea><input type="hidden" id="auto_what" name="auto_what" value="" /><br />&nbsp;</div></div>';
	$filter_failed = " AND (`StatReturn`) > 0";
	$MySQL = str_replace("`StatDomain` AS `Referring<br />Site`", "CONCAT('<img border=0 src=\"".$TERRIBL_images_path."', IF(SUM(`StatReturn`)>0, 'checked.gif\" alt=\"Link Verified\"', 'blocked.gif\" alt=\"Link Not Found'), '\" id=\"img_',StatDomain,'\" name=\"img_Domain\" /><a href=\"javascript:recheckDomain(\'',StatDomain,'\');\">Recheck</a>') AS `Link<br />Verified`, IF(StatDomain IN (SELECT `BlockDomain` FROM `wp_terribl_blocked`), 'Site Blocked!', CONCAT('<input type=\"submit\" value=\"Block Site!\" onclick=\"document.getElementById(\'auto_what\').value=\'block\';document.getElementById(\'manual_add\').value=\'',StatDomain,'\';\" />')) AS `Widget<br />Action`, CONCAT('<a href=\"javascript:showhideRefDiv(\'', `StatDomain`, '\');\">', `StatDomain`, '</a><div class=\"shadowed-box rounded-corners popup-box\" id=\"RefDiv_', `StatDomain`, '\"><table><tr><td>', GROUP_CONCAT(DISTINCT CONCAT(IFNULL(StatReturn, '0'), '</td><td>', StatModified, '</td><td>', StatReferer, '</td><td>', StatRequestURI) ORDER BY StatModified DESC SEPARATOR '</td></tr><tr><td>'), '</td></tr></table></div>') AS `Referring<br />Site`", str_replace(" FROM wp_terribl_stats GROUP BY ", " FROM wp_terribl_stats WHERE (`StatImpressions`) > 0".(isset($_POST['show_failed'])&&($_POST['show_failed']==1)?'':$filter_failed)." GROUP BY ", $TERRIBL_SQL_SELECT));
	echo TERRIBL_mysql_report($MySQL, '<center><div style="float: left;"><a href="javascript:recheckAllDomains();"><img border=0 src="'.$TERRIBL_images_path.'checked.gif" alt="Checked" />Recheck All</a></div><b>In-Bound Links Generated by SHAREABLE</b><div style="float: right;"><input type="checkbox" name="show_failed" onchange="document.TERRIBL_Form.submit();" value="1"'.(isset($_POST['show_failed'])&&($_POST['show_failed']==1)?' checked':'').'>Show In-Bound Links that Failed Validation</div></center>').'</form></div></div>';
$_SESSION['eli_debug_microtime']['SHAREABLE_Settings_end'] = microtime(true);
}
function TERRIBL_Settings() {
	global $TERRIBL_settings_array, $TERRIBL_SQL_SELECT, $TERRIBL_plugin_dir, $TERRIBL_images_path;
$_SESSION['eli_debug_microtime']['TERRIBL_Settings_start'] = microtime(true);
	$current_user = wp_get_current_user();
	TERRIBL_display_header('TERRIBL Settings');
	echo '<form method="POST" name="TERRIBL_Form"><div class="stuffbox shadowed-box"><h3 class="hndle"><span>Settings Form</span></h3><div class="inside"><b>Automatically Validate In-Bound Referers and list in the sidebar Widget?</b><br /><input type="radio" name="auto_return" value="yes"'.($TERRIBL_settings_array['auto_return']=="yes"?" checked":"").' onchange="document.TERRIBL_Form.submit();" />yes &nbsp; <input type="radio" name="auto_return" value="no"'.($TERRIBL_settings_array['auto_return']=="yes"?"":" checked").' onchange="document.TERRIBL_Form.submit();" />no<input type="hidden" name="MonthOf" value="'.$_SESSION['MonthOf'].'" /><br /><br /><b>Kick off your In-Bound Widget by manually adding a site here:</b><br />http://<input type="text" name="manual_add" id="manual_add" value="wordpress.ieonly.com" /><input type="hidden" id="auto_what" name="auto_what" value="" /><input type="submit" value="Add Site Link to Widget" class="button-primary" onclick="document.getElementById(\'auto_what\').value=\'add\';" /><br />&nbsp;</div></div>';
	if (isset($_POST['manual_add']) && strlen(trim($_POST['manual_add'])) > 0 && isset($_POST['auto_what']) && strlen($_POST['auto_what']) > 0) {
		$DomainName = mysql_real_escape_string(trim($_POST['manual_add']));
		if ($_POST['auto_what']=='add') {
			@mysql_query("INSERT INTO `wp_terribl_stats` (`StatMonth`, `StatCreated`, `StatModified`, `StatFirstUserAgent`, `StatUserAgent`, `StatFirstRemoteAddr`, `StatRemoteAddr`, `StatReferer`, `StatVisits`, `StatImpressions`, `StatDomain`, `StatReturn`, `StatRequestURI`) VALUES ('".date("Y-m")."-01', '".date("Y-m-d")."', '".date("Y-m-d")."', 'None', 'None', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['REMOTE_ADDR']."', 'http://$DomainName', 0, 0, '$DomainName', 1, '/".$TERRIBL_settings_array['auto_root']."') ON DUPLICATE KEY UPDATE `StatReturn`=`StatReturn`+1");
			@mysql_query("DELETE FROM `wp_terribl_blocked` WHERE BlockDomain='$DomainName'");
		} elseif ($_POST['auto_what']=='block')
			@mysql_query("INSERT INTO `wp_terribl_blocked` (BlockDomain, BlockReason, BlockCreated) VALUES ('$DomainName', '".$current_user->display_name." said so once', '".date("Y-m-d H:i:s")."') ON DUPLICATE KEY UPDATE BlockReason=CONCAT(BlockReason,' and again')");
		elseif ($_POST['auto_what']=='show')
			@mysql_query("UPDATE `wp_terribl_stats` SET `StatReturn`=`StatReturn`+1 WHERE StatDomain='$DomainName'");
		elseif ($_POST['auto_what']=='hide')
			@mysql_query("UPDATE `wp_terribl_stats` SET `StatReturn`=0 WHERE StatDomain='$DomainName'");
	}
	$filter_failed = " AND (`StatReturn`) > 0";
	$MySQL = str_replace("`StatDomain` AS `Referring<br />Site`", "CONCAT('<img border=0 src=\"".$TERRIBL_images_path."', IF(SUM(`StatReturn`)>0, 'checked.gif\" alt=\"Link Verified\"', 'blocked.gif\" alt=\"Link Not Found'), '\" id=\"img_',StatDomain,'\" name=\"img_Domain\" /><a href=\"javascript:recheckDomain(\'',StatDomain,'\');\">Recheck</a>') AS `Link<br />Verified`, IF(StatDomain IN (SELECT `BlockDomain` FROM `wp_terribl_blocked`), 'Site Blocked!', CONCAT('<input type=\"submit\" value=\"Block Site!\" onclick=\"document.getElementById(\'auto_what\').value=\'block\';document.getElementById(\'manual_add\').value=\'',StatDomain,'\';\" />')) AS `Widget<br />Action`, CONCAT('<a href=\"javascript:showhideRefDiv(\'', `StatDomain`, '\');\">', `StatDomain`, '</a><div class=\"shadowed-box rounded-corners popup-box\" id=\"RefDiv_', `StatDomain`, '\"><table><tr><td>', GROUP_CONCAT(DISTINCT CONCAT(IFNULL(StatReturn, '0'), '</td><td>', StatModified, '</td><td>', StatReferer, '</td><td>', StatRequestURI) ORDER BY StatModified DESC SEPARATOR '</td></tr><tr><td>'), '</td></tr></table></div>') AS `Referring<br />Site`", str_replace(" FROM wp_terribl_stats GROUP BY ", " FROM wp_terribl_stats WHERE (`StatImpressions`) = 0".(isset($_POST['show_failed'])&&($_POST['show_failed']==1)?'':$filter_failed)." GROUP BY ", $TERRIBL_SQL_SELECT));// str_replace("FROM wp_terribl_stats GROUP", "FROM wp_terribl_stats WHERE StatMonth = '".$_SESSION['MonthOf']."' GROUP",
	echo TERRIBL_mysql_report($MySQL, '<center><div style="float: left;"><a href="javascript:recheckAllDomains();"><img border=0 src="'.$TERRIBL_images_path.'checked.gif" alt="Checked" />Recheck All</a></div><b>In-Bound Links <u>NOT</u> Generated by SHAREABLE</b><div style="float: right;"><input type="checkbox" name="show_failed" onchange="document.TERRIBL_Form.submit();" value="1"'.(isset($_POST['show_failed'])&&($_POST['show_failed']==1)?' checked':'').'>Show In-Bound Links that Failed Validation</div></center>').'</form></div></div>';
$_SESSION['eli_debug_microtime']['TERRIBL_Settings_end'] = microtime(true);
}
function TERRIBL_stats() {
$_SESSION['eli_debug_microtime']['TERRIBL_stats_start'] = microtime(true);
	TERRIBL_display_header('TERRIBL Stats');
	if (!isset($_SESSION['MonthOf']))
		$_SESSION['MonthOf'] = date("Y-m")."-01";
	if (isset($_POST['RemoteAddr']) && strlen(trim($_POST['RemoteAddr'])))
		$RemoteAddr = $_POST['RemoteAddr'];
	else
		$RemoteAddr = $_SERVER['REMOTE_ADDR'];
	$StatRemoteAddr = "'".implode("','", explode(',', mysql_real_escape_string(str_replace(' ', '', $RemoteAddr))))."'";
	$WHERE = "WHERE StatMonth = '".$_SESSION['MonthOf']."'";// AND StatRemoteAddr NOT IN ($StatRemoteAddr)";
	echo '<form method="POST" name="TERRIBL_Form"><input type="hidden" name="MonthOf" value="'.$_SESSION['MonthOf'].'" />
	<div style="width: 100%;">
	<table border=0 cellspacing=0><tr><td>';
	$MySQL = "SELECT MONTHNAME(StatMonth) AS MonthOf, StatMonth FROM `wp_terribl_stats` GROUP BY StatMonth ORDER BY StatMonth DESC LIMIT 12";
	$result = mysql_query($MySQL);
	while ($rs = mysql_fetch_assoc($result))
		echo '<input type="submit" value="'.$rs['MonthOf'].'" onclick="document.TERRIBL_Form.MonthOf.value=\''.$rs['StatMonth'].'\';" style="'.($_SESSION['MonthOf']==$rs['StatMonth']?'background-color: #33FF33; ':'').'float: right;" />';
	echo '</td></tr></table></div></form>';
	$SQL = "SELECT SUM(`StatVisits`) AS `In-Bound<br />Clicks`, SUM(`StatImpressions`) AS `In-Bound<br />Impressions`, CONCAT('<a href=\"javascript:showhideRefDiv(\'', `StatRequestURI`, '\');\">', `StatRequestURI`, '</a><div class=\"shadowed-box rounded-corners popup-box\" id=\"RefDiv_', `StatRequestURI`, '\"><li>', GROUP_CONCAT(DISTINCT CONCAT(StatVisits, ' ', StatModified, ' ', StatDomain) ORDER BY StatVisits DESC SEPARATOR '<li>'), '</div>') AS `In-Bound<br />URI` FROM wp_terribl_stats WHERE StatMonth = '".$_SESSION['MonthOf']."' GROUP BY StatRequestURI ORDER BY `In-Bound<br />Clicks` DESC, `In-Bound<br />Impressions` DESC LIMIT 10";
	echo '<div style="float: left;">'.TERRIBL_mysql_report($SQL, 'Top 10 In-Bound Link Destinations').'</div>';
	$VisiterColor = '#FF9999';
	$VisitColor = '#9999FF';
	$AllVisiters = "(SELECT COUNT(StatVisits) FROM wp_terribl_stats $WHERE ORDER BY COUNT(StatVisits) DESC LIMIT 1)";
	$AllVisits = "(SELECT SUM(StatVisits) FROM wp_terribl_stats $WHERE ORDER BY SUM(StatVisits) DESC LIMIT 1)";
	$SQL = "SELECT CONCAT('<div style=\"position: relative; width: 100%; height: 100px; background-color: #000000;\"><font color=\"$VisiterColor\"><b>', $AllVisiters, '<br />Visiters</b></font><br /><br /><font color=\"$VisitColor\"><b>', $AllVisits, '<br />Visits<b></font></div>') AS `OS ->`";
	$ANDNOT = "";
	foreach (Array('Windows', 'Macintosh', 'iPhone', 'Android', 'BlackBerry', 'Linux', '') as $OS) {
		$SQL .= ", (SELECT CONCAT('<table style=\"width: 100%; height: 100px;\" cellspacing=0><tr style=\"width: 100%; height: 100px;\"><td style=\"width: 50%; height: 100px;\"><div style=\"position: relative; width: 100%; height: 100px; background-color: #FFFFFF;\"><div style=\"z-index: 10; position: absolute; background-color: $VisiterColor; right: 1px; bottom: 0px; width: 10px; height: ', ROUND((COUNT(StatVisits) / $AllVisiters) * 100), 'px; text-align: right;\"><div style=\"z-index: 100; position: absolute; background-color: $VisiterColor; right: 1px; top: 0px; text-align: right;\">', COUNT(StatVisits), '</div></div></div></td><td style=\"width: 50%; height: 100px;\"><div style=\"position: relative; width: 100%; height: 100px; background-color: #FFFFFF;\"><div style=\"z-index: 10; position: absolute; background-color: $VisitColor; left: 1px; bottom: 0px; width: 10px; height: ', ROUND((SUM(StatVisits) / $AllVisits) * 100), 'px;\"><div style=\"z-index: 100; position: absolute; background-color: $VisitColor; left: 1px; top: 0px; text-align: left;\">', SUM(StatVisits), '</div></div></div></td></tr></table>') FROM wp_terribl_stats $WHERE AND StatUserAgent LIKE '%$OS%'$ANDNOT LIMIT 1) AS ".$OS;
		$ANDNOT .= " AND StatUserAgent NOT LIKE '%$OS%'";
	}
	$SQL .= "Other";
	echo '<div style="float: left;">'.TERRIBL_mysql_report($SQL,'Operating System').'</div>';
	$SQL = "SELECT CONCAT('<div style=\"position: relative; width: 100%; height: 100px; background-color: #000000;\"><font color=\"$VisiterColor\"><b>', $AllVisiters, '<br />Visiters</b></font><br /><br /><font color=\"$VisitColor\"><b>', $AllVisits, '<br />Visits<b></font></div>') AS Browser";
	$ANDNOT = "";
	foreach (Array('MSIE', 'Firefox', 'Chrome', 'Safari', 'Opera', '') as $OS) {
		$SQL .= ", (SELECT CONCAT('<table style=\"width: 100%; height: 100px;\" cellspacing=0 cellpadding=0><tr style=\"width: 100%; height: 100px;\"><td style=\"width: 50%; height: 100px;\"><div style=\"position: relative; width: 100%; height: 100px; background-color: #FFFFFF;\"><div style=\"z-index: 10; position: absolute; background-color: $VisiterColor; right: 1px; bottom: 0px; width: 10px; height: ', ROUND((COUNT(StatVisits) / $AllVisiters) * 100), 'px; text-align: right;\"><div style=\"z-index: 100; position: absolute; background-color: $VisiterColor; right: 1px; top: 0px; text-align: right;\">', COUNT(StatVisits), '</div></div></div></td><td style=\"width: 50%; height: 100px;\"><div style=\"position: relative; width: 100%; height: 100px; background-color: #FFFFFF;\"><div style=\"z-index: 10; position: absolute; background-color: $VisitColor; left: 1px; bottom: 0px; width: 10px; height: ', ROUND((SUM(StatVisits) / $AllVisits) * 100), 'px;\"><div style=\"z-index: 100; position: absolute; background-color: $VisitColor; left: 1px; top: 0px; text-align: left;\">', SUM(StatVisits), '</div></div></div></td></tr></table>') FROM wp_terribl_stats $WHERE AND StatUserAgent LIKE '%$OS%'$ANDNOT LIMIT 1) AS ".$OS;
		$ANDNOT .= " AND StatUserAgent NOT LIKE '%$OS%'";
	}
	$SQL .= "Other";
 	echo '<div style="float: left;">'.TERRIBL_mysql_report($SQL,'Browsers').'</div>';
 	echo '<div style="float: left;">'.TERRIBL_mysql_report("SELECT COUNT(StatVisits) AS Visiters, SUM(StatVisits) AS Visits, StatUserAgent AS Browser, CONCAT('&nbsp;', GROUP_CONCAT(DISTINCT StatRemoteAddr SEPARATOR ', '), '&nbsp;') AS IPs FROM wp_terribl_stats ".$WHERE.str_replace(" AND StatUserAgent NOT LIKE '%%'", "", $ANDNOT)." GROUP BY StatUserAgent ORDER BY COUNT(StatVisits) DESC LIMIT 10", 'Top 10 Other Browsers').'</div></div></div>';
$_SESSION['eli_debug_microtime']['TERRIBL_stats_end'] = microtime(true);
}
function TERRIBL_menu() {
	global $TERRIBL_settings_array, $TERRIBL_plugin_dir, $TERRIBL_Version, $wp_version, $TERRIBL_plugin_home, $TERRIBL_Logo_IMG, $TERRIBL_updated_images_path, $TERRIBL_images_path;
if (is_admin()) {
$_SESSION['eli_debug_microtime']['TERRIBL_menu_start'] = microtime(true);
	if (!isset($TERRIBL_settings_array['auto_return']))
		$TERRIBL_settings_array['auto_return'] = "yes";
	if (isset($_POST['MonthOf']))
		$_SESSION['MonthOf'] = $_POST['MonthOf'];
	if (!isset($_SESSION['MonthOf']))
		$_SESSION['MonthOf'] = date("Y-m")."-01";
	if (isset($_POST['auto_return']) && $_POST['auto_return'] != $TERRIBL_settings_array['auto_return'])
		$TERRIBL_settings_array['auto_return'] = $_POST['auto_return'];
	if (isset($_POST['image_url']) && TERRIBL_strip_magic($_POST['image_url']) != $TERRIBL_settings_array['image_url'])
		$TERRIBL_settings_array['image_url'] = TERRIBL_strip_magic($_POST['image_url']);
	if (isset($_POST['image_style']) && TERRIBL_strip_magic($_POST['image_style']) != $TERRIBL_settings_array['image_style'])
		$TERRIBL_settings_array['image_style'] = TERRIBL_strip_magic($_POST['image_style']);
	if (isset($_POST['div_style']) && TERRIBL_strip_magic($_POST['div_style']) != $TERRIBL_settings_array['div_style'])
		$TERRIBL_settings_array['div_style'] = TERRIBL_strip_magic($_POST['div_style']);
	if (isset($_POST['link_text']) && TERRIBL_strip_magic($_POST['link_text']) != $TERRIBL_settings_array['link_text'])
		$TERRIBL_settings_array['link_text'] = TERRIBL_strip_magic($_POST['link_text']);
	if (isset($_POST['text_style']) && TERRIBL_strip_magic($_POST['text_style']) != $TERRIBL_settings_array['text_style'])
		$TERRIBL_settings_array['text_style'] = TERRIBL_strip_magic($_POST['text_style']);
	if (isset($_POST['TERRIBL_menu_group']) && is_numeric($_POST['TERRIBL_menu_group']) && $_POST['TERRIBL_menu_group'] != $TERRIBL_settings_array['menu_group'])
		$TERRIBL_settings_array['menu_group'] = $_POST['TERRIBL_menu_group'];
	update_option($TERRIBL_plugin_dir.'_settings_array', $TERRIBL_settings_array);
	$img_path = basename(__FILE__);
	$Full_plugin_logo_URL = get_option('siteurl');
	if (!isset($TERRIBL_settings_array['img_url']))
		$TERRIBL_settings_array['img_url'] = $img_path;
		$img_path.='?v='.$TERRIBL_Version.'&wp='.$wp_version.'&p='.$TERRIBL_plugin_dir;
	if ($img_path != $TERRIBL_settings_array['img_url']) {
		$TERRIBL_settings_array['img_url'] = $img_path;
		$img_path = $TERRIBL_plugin_home.$TERRIBL_updated_images_path.$img_path;
		$Full_plugin_logo_URL = $img_path.'&key='.md5($Full_plugin_logo_URL).'&d='.
		ur1encode($Full_plugin_logo_URL);
		update_option($TERRIBL_plugin_dir.'_settings_array', $TERRIBL_settings_array);
	} else //only used for debugging.//rem this line out
	$Full_plugin_logo_URL = $TERRIBL_images_path.$TERRIBL_Logo_IMG;
	$base_page = 'SHAREABLE-settings';
	if ($TERRIBL_settings_array['menu_group'] == 2)
		$base_page = 'link-manager.php';
	elseif (!function_exists('add_object_page') || $TERRIBL_settings_array['menu_group'] == 1)
		add_menu_page(__('SHAREABLE Settings'), __('SHAREABLE'), 'administrator', $base_page, 'SHAREABLE_settings', $Full_plugin_logo_URL);
	else
		add_object_page(__('SHAREABLE Settings'), __('SHAREABLE'), 'administrator', $base_page, 'SHAREABLE_settings', $Full_plugin_logo_URL);
	add_submenu_page($base_page, __('SHAREABLE Settings Page'), __('Link Settings'), 'administrator', 'SHAREABLE-settings', 'SHAREABLE_settings');
	add_submenu_page($base_page, __('TERRIBL Settings Page'), __('TERRIBL Settings'), 'administrator', $TERRIBL_plugin_dir.'-settings', $TERRIBL_plugin_dir.'_settings');
	add_submenu_page($base_page, __('TERRIBL Stats Page'), __('TERRIBL Stats'), 'administrator', $TERRIBL_plugin_dir.'-stats', $TERRIBL_plugin_dir.'_stats');
}
$_SESSION['eli_debug_microtime']['TERRIBL_menu_end'] = microtime(true);
}
function TERRIBL_debug($my_error = '', $echo_error = false) {
	global $TERRIBL_plugin_dir, $TERRIBL_Version, $wp_version;
$_SESSION['eli_debug_microtime']['TERRIBL_debug'] = microtime(true);
	$mtime=date("Y-m-d H:i:s", filemtime(__file__));
	if (substr($my_error, 0, 6) == "Table " && substr($my_error, -14) == " doesn't exist")
		TERRIBL_install();
	elseif (substr($my_error, 0, 25) == 'Illegal mix of collations') {
		mysql_query("ALTER TABLE `wp_terribl_stats` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci, MODIFY COLUMN `StatReturn` bigint(20) NOT NULL default '0', CHANGE `StatFirstUserAgent` `StatFirstUserAgent` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `StatUserAgent` `StatUserAgent` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `StatFirstRemoteAddr` `StatFirstRemoteAddr` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `StatRemoteAddr` `StatRemoteAddr` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `StatReferer` `StatReferer` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `StatRequestURI` `StatRequestURI` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `StatDomain` `StatDomain` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
		if (mysql_errno())
			$my_error .= "\n<br />".mysql_error();
		else
			$my_error .= "\n<br />ALTER TABLE wp_terribl_stats";
		mysql_query("ALTER TABLE `wp_terribl_blocked` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci, MODIFY COLUMN `BlockDomain` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', CHANGE `BlockReason` `BlockReason` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Admin'");
		if (mysql_errno())
			$my_error .= "\n<br />".mysql_error();
		else
			$my_error .= "\n<br />ALTER TABLE wp_terribl_blocked<br />Try refreshing the page!";
	} //$echo .= '<li>ERROR: '.mysql_error().'<li>SQL:<br><textarea disabled="yes" cols="65" rows="15">'.$MySQL.'</textarea>';//only used for debugging.
	if ($echo_error || (substr($my_error, 0, 22) == 'Access denied for user'))
		echo "<li>debug:<pre>$my_error\n".print_r($_SESSION['eli_debug_microtime'],true).'END;</pre>';
	$_SESSION['eli_debug_microtime']=array();
	return $my_error;
}
function TERRIBL_get_URL($URL) {
$_SESSION['eli_debug_microtime']['TERRIBL_get_URL_start'] = microtime(true);
	if (isset($_SERVER['HTTP_REFERER']))
		$SERVER_HTTP_REFERER = $_SERVER['HTTP_REFERER'];
	elseif (isset($_SERVER['HTTP_HOST']))
		$SERVER_HTTP_REFERER = 'HOST://'.$_SERVER['HTTP_HOST'];
	elseif (isset($_SERVER['SERVER_NAME']))
		$SERVER_HTTP_REFERER = 'NAME://'.$_SERVER['SERVER_NAME'];
	elseif (isset($_SERVER['SERVER_ADDR']))
		$SERVER_HTTP_REFERER = 'ADDR://'.$_SERVER['SERVER_ADDR'];
	else
		$SERVER_HTTP_REFERER = 'NULL://not.anything.com';
	$ReadFile = '';
	if (function_exists('curl_init')) {
		$curl_hndl = curl_init();
		curl_setopt($curl_hndl, CURLOPT_URL, $URL);
		curl_setopt($curl_hndl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl_hndl, CURLOPT_REFERER, $SERVER_HTTP_REFERER);
	    if (isset($_SERVER['HTTP_USER_AGENT']))
	    	curl_setopt($curl_hndl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($curl_hndl, CURLOPT_HEADER, 0);
		curl_setopt($curl_hndl, CURLOPT_RETURNTRANSFER, TRUE);
		$ReadFile = curl_exec($curl_hndl);
		curl_close($curl_hndl);
	}
	if (strlen($ReadFile) == 0 && function_exists('file_get_contents'))
		$ReadFile = @file_get_contents($URL).'';
$_SESSION['eli_debug_microtime']['TERRIBL_get_URL_send'] = microtime(true);
	return $ReadFile;
}
function TERRIBL_init() {
	global $SHAREABLE_script_URI, $TERRIBL_settings_array, $TERRIBL_plugin_dir, $Visits_Impressions, $TERRIBL_REFERER_Parts, $img_src;
	$YourTZ=get_option('timezone_string').'';
	if (function_exists('date_default_timezone_set') && strlen($YourTZ) > 0)
		date_default_timezone_set($YourTZ);
$_SESSION['eli_debug_microtime']['TERRIBL_init_start'] = microtime(true);
//	$TERRIBL_settings_array = get_option($TERRIBL_plugin_dir.'_settings_array');
	if (!isset($TERRIBL_settings_array['auto_return'])) 
		$TERRIBL_settings_array['auto_return'] = "yes";
	$_SESSION[$TERRIBL_plugin_dir.'MonthOf'] = date("Y-m")."-01";
	update_option($TERRIBL_plugin_dir.'_settings_array', $TERRIBL_settings_array);
//TERRIBL_debug("init():2\nVisits_Impressions=$Visits_Impressions\nTERRIBL_REFERER_Parts=".(is_array($TERRIBL_REFERER_Parts)?print_r($TERRIBL_REFERER_Parts, true):$TERRIBL_REFERER_Parts), false);//only used for debugging.//rem this line out
	if (isset($_SERVER['HTTP_REFERER']) && (!(isset($_SERVER['REQUEST_URI']) && substr(str_replace('/', '', strtolower($_SERVER['REQUEST_URI'].'/NOT')), 0, 3) == 'wp-') || strlen($Visits_Impressions)>0)) {
		$TERRIBL_HTTP_REFERER = $_SERVER['HTTP_REFERER'];
		if (!isset($TERRIBL_REFERER_Parts))
			$TERRIBL_REFERER_Parts = explode('/', $TERRIBL_HTTP_REFERER.'//'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']);
		$StatRequestURI = (isset($_GET['Impression_URI']) && strlen($_GET['Impression_URI'])>0?$_GET['Impression_URI']:(isset($_GET['shareable'])?substr(str_replace('shareable=', '', $SHAREABLE_script_URI), 0, -1):'/'));
		$StatReturn = ($TERRIBL_settings_array['auto_return']=="yes"?"0":"NULL");
		$now = date("Y-m-d H:i:s");
		$StatUserAgent = mysql_real_escape_string(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'Unknown USER_AGENT');
		$StatRemoteAddr = (isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'.Unknown.ADDR.');
		if ($TERRIBL_REFERER_Parts[2] != $_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']) {
			if ($Visits_Impressions == 'StatReturn' && !(substr($_GET['Return_URL'],0,7)=='http://'||substr($_GET['Return_URL'],0,8)=='https://')) {
				$MySQL = "SELECT `StatReferer` FROM `wp_terribl_stats` WHERE `StatDomain`='".$TERRIBL_REFERER_Parts[2]."'";
				$result = @mysql_query($MySQL);
				while ($_rs = mysql_fetch_assoc($result)) {
					$ReadFile = TERRIBL_get_URL($_rs['StatReferer']);
					$ReturnOne = '0';
					if (strlen($ReadFile) > 0) {
						if (strpos($ReadFile, '://'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']) > 0) {
							$ReturnOne = '1';
							$img_src = 'checked.gif';
						}
					}
					$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'] = $_rs['StatReferer'];//(isset($_GET['Return_URL'])?$_GET['Return_URL']:$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']);
					$SAFE_REFERER = "'".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'])."'";
					$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'] = $TERRIBL_REFERER_Parts;
					$MySQL = "UPDATE `wp_terribl_stats` SET `$Visits_Impressions`=$ReturnOne WHERE `StatReferer`=$SAFE_REFERER";
//TERRIBL_debug("TERRIBL UPDATE test\n$SQL_Error\nSQL:$MySQL");//only used for debugging.
					@mysql_query($MySQL);
					if (mysql_errno()) {
						$SQL_Error = mysql_error();
						if (substr($SQL_Error, 0, 6) == "Table " && substr($SQL_Error, -14) == " doesn't exist")
							TERRIBL_install();
						else TERRIBL_debug("TERRIBL MySQL UPDATE\n$SQL_Error\nSQL:$MySQL");//only used for debugging.//rem this line out
					}// elseif ($Visits_Impressions == 'StatReturn') TERRIBL_debug("TERRIBL MySQL UPDATE StatReturn\nSQL:$MySQL");//only used for debugging.
				}
//TERRIBL_debug("TERRIBL StatReturn\n$ReturnOne $img_src\n".$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']);//only used for debugging.
			} else {
				if ($Visits_Impressions != 'StatImpressions' && $Visits_Impressions != 'StatReturn')
					$Visits_Impressions = 'StatVisits';
				$ReturnOne = "`$Visits_Impressions`+1";
				if ($Visits_Impressions == 'StatReturn') {
					$TERRIBL_HTTP_REFERER = (isset($_GET['Return_URL'])?$_GET['Return_URL']:$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']);
					$ReadFile = TERRIBL_get_URL($TERRIBL_HTTP_REFERER);
					if (strlen($ReadFile) > 0) {
						$ReturnOne = '1';
						if (strpos($ReadFile, '://'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']) > 0)
							$img_src = 'checked.gif';
						else
							$ReturnOne = '0';
					}
				}
				$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'] = $TERRIBL_HTTP_REFERER;
				$SAFE_REFERER = "'".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'])."'";//)(strpos($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'], '&q=&esrc=s')>0?"REPLACE(`StatReferer`, '/url?', '/search?')":;
				$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'] = $TERRIBL_REFERER_Parts;
				$MySQL = "INSERT INTO `wp_terribl_stats` (`StatMonth`, `StatCreated`, `StatModified`, `StatFirstUserAgent`, `StatUserAgent`, `StatFirstRemoteAddr`, `StatRemoteAddr`, `StatReferer`, `$Visits_Impressions`, `StatDomain`, `StatRequestURI`) VALUES ('".date("Y-m")."-01', '".$now."', '".$now."', '".$StatUserAgent."', '".$StatUserAgent."', '".$StatRemoteAddr."', '".$StatRemoteAddr."', $SAFE_REFERER, 1, '".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'][2])."', '".mysql_real_escape_string($StatRequestURI)."') ON DUPLICATE KEY UPDATE `StatModified`='".$now."', `StatUserAgent`='".$StatUserAgent."', `StatRemoteAddr`='".$StatRemoteAddr."', `StatReferer`=IF(`StatReturn`>0,`StatReferer`,$SAFE_REFERER), `$Visits_Impressions`=$ReturnOne";
//TERRIBL_debug("TERRIBL INSERT test\n$SQL_Error\nSQL:$MySQL");//only used for debugging.
				@mysql_query($MySQL);
				if (mysql_errno()) {
					$SQL_Error = mysql_error();
					if (substr($SQL_Error, 0, 6) == "Table " && substr($SQL_Error, -14) == " doesn't exist")
						TERRIBL_install();
					else TERRIBL_debug("TERRIBL MySQL INSERT\n$SQL_Error\nSQL:$MySQL");//only used for debugging.
				}
			}
		} elseif (((isset($_GET['Impression_URI']) && ($StatRequestURI == $_GET['Impression_URI'])) || (isset($_GET['shareable']) && ($StatRequestURI == substr(str_replace('shareable=', '', $SHAREABLE_script_URI), 0, -1)))) && ($Visits_Impressions == 'StatImpressions') && isset($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']) && !isset($_SESSION['chk_'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']])) {
			$_SESSION['chk_'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']] = 0;
		}
	}
$_SESSION['eli_debug_microtime']['TERRIBL_init_end'] = microtime(true);
}
class TERRIBL_Widget_Class extends WP_Widget {
	function TERRIBL_Widget_Class() {
		global $TERRIBL_plugin_dir;
$_SESSION['eli_debug_microtime']['TERRIBL_Widget_Class_Widget_Class_start'] = microtime(true);
		$this->WP_Widget($TERRIBL_plugin_dir.'-Widget', __('In-Bound Links'), array('classname' => 'widget_'.$TERRIBL_plugin_dir, 'description' => __('A TERRIBL Widget - Track Every Referer and Return In-Bound Links')));
		$this->alt_option_name = 'widget_'.$TERRIBL_plugin_dir;
$_SESSION['eli_debug_microtime']['TERRIBL_Widget_Class_Widget_Class_end'] = microtime(true);
	}
	function widget($args, $instance) {
		global $TERRIBL_SQL_SELECT, $TERRIBL_plugin_dir, $TERRIBL_images_path, $TERRIBL_settings_array;
$_SESSION['eli_debug_microtime']['TERRIBL_Widget_Class_widget_start'] = microtime(true);
		$LIs = '';
		extract($args);
		if (!$instance['title'])
			$instance['title'] = "In-Bound Links";
		if (!$instance['riblfer'])
			$instance['riblfer'] = "yes";
		if (!is_numeric($instance['number']))
			$instance['number'] = 5;
		if (isset($instance['riblfer']) && $instance['riblfer'] == "yes" && isset($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']) && isset($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts']) && is_array($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts']) && count($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts']) > 2) {
			if (isset($TERRIBL_settings_array['auto_return']) && $TERRIBL_settings_array['auto_return'] == "yes" && ($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']) && !isset($_SESSION['chk_'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']]))
				$img_chk = '<img border=0 id="TERRIBL_IMG_CHK" src="'.$TERRIBL_images_path.'index.php?Return_URL='.urlencode($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']).'&Impression_URI='.urlencode(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:(isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:(isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:'/'))).'" />';
			$LIs .= '<li class="TERRIBL-Link">'.$img_chk.'<a target="_blank" title="You got here from '.$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'].'" href="'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'].'" rel="bookmark">'.$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'][2]."</a></li>\n";
		}// else echo 'ERR: '.$TERRIBL_plugin_dir.'REFERER_Parts='.(isset($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'])?(is_array($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'])?print_r($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'],true):'!array'):'!set');//only used for debugging.
		$MySQL = str_replace(" FROM wp_terribl_stats GROUP", ", (SELECT StatReferer FROM wp_terribl_stats AS pastReferer WHERE StatDomain = wp_terribl_stats.StatDomain ORDER BY StatModified DESC LIMIT 1) AS `From<br />URL` FROM wp_terribl_stats WHERE StatReturn > 0 AND StatDomain NOT IN (SELECT `BlockDomain` FROM `wp_terribl_blocked`) AND StatDomain != '".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'][2])."' GROUP", $TERRIBL_SQL_SELECT);
		$result = mysql_query($MySQL);
		if (mysql_errno()) {
			$SQL_Error = mysql_error();
			if (substr($SQL_Error, 0, 6) == "Table " && substr($SQL_Error, -14) == " doesn't exist")
				TERRIBL_install();
			else TERRIBL_debug("$SQL_Error\nSQL:$MySQL");//only used for debugging.//rem this line out
		} else {
			if (($rs = mysql_fetch_assoc($result)) && ($instance['number'] > 0)) {
				$li=0;	
				do {
					$li++;
					$SafeReferer = explode('wp-admin', $rs['From<br />URL'].'wp-admin');
					$LIs .= '<li class="TERRIBL-Link"><a target="_blank" title="'.$rs['Referring<br />Site'].' linked here on '.$rs['Last<br />Referral'].'" href="'.$SafeReferer[0].'" rel="bookmark">'.$rs['Referring<br />Site']."</a>&nbsp;(".$rs['In-Bound<br />Clicks'].")</li>\n";
				} while (($rs = mysql_fetch_assoc($result)) && ($li < $instance['number']));
			}
		}
		if (strlen($LIs) > 0)
			echo $before_widget.$before_title.$instance["title"].$after_title."<ul>\n".$LIs."</ul>\n".$after_widget;
$_SESSION['eli_debug_microtime']['TERRIBL_Widget_Class_widget_end'] = microtime(true);
	}
	function flush_widget_cache() {
		global $TERRIBL_plugin_dir;
		wp_cache_delete('widget_'.$TERRIBL_plugin_dir, 'widget');
	}
	function update($new, $old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['number'] = (int) $new['number'];
		$instance['riblfer'] = strip_tags($new['riblfer']);
		return $instance;
	}
	function form( $instance ) {
$_SESSION['eli_debug_microtime']['TERRIBL_Widget_Class_form_start'] = microtime(true);
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$riblfer = isset($instance['riblfer']) ? esc_attr($instance['riblfer']) : 'yes';
		echo '<p><label for="'.$this->get_field_id('title').'">'.__('Alternate Widget Title').':</label>
		<input type="text" name="'.$this->get_field_name('title').'" id="'.$this->get_field_id('title').'" value="'.$title.'" /></p>
		<p><input type="checkbox" name="'.$this->get_field_name('riblfer').'" id="'.$this->get_field_id('riblfer').'" value="yes"'.($riblfer=="yes"?" checked":"").' /><label for="'.$this->get_field_id('riblfer').'">'.__('Display a Link to the Current Referer').':</label></p>
		<p><label for="'.$this->get_field_id('number').'">Number of Older Referer to Display:</label>
		<input type="text" size="2" name="'.$this->get_field_name('number').'" id="'.$this->get_field_id('number').'" value="'.$number.'" /></p>';
$_SESSION['eli_debug_microtime']['TERRIBL_Widget_Class_form_end'] = microtime(true);
	}
}
class SHAREABLE_Widget_Class extends WP_Widget {
	function SHAREABLE_Widget_Class() {
$_SESSION['eli_debug_microtime']['SHAREABLE_Widget_Class_Widget_Class_start'] = microtime(true);
		$this->WP_Widget('SHAREABLE-Widget', __('SHAREABLE Link'), array('classname' => 'widget_SHAREABLE', 'description' => __('A small form with link code that visitors can copy to share a link to the page or post they are on.')));
		$this->alt_option_name = 'widget_SHAREABLE';
$_SESSION['eli_debug_microtime']['SHAREABLE_Widget_Class_Widget_Class_end'] = microtime(true);
	}
	function widget($args, $instance) {
		global $TERRIBL_plugin_dir, $SHAREABLE_script_URI;
$_SESSION['eli_debug_microtime']['SHAREABLE_Widget_Class_widget_start'] = microtime(true);
		extract($args);
		if (!isset($instance['title']))
			$instance['title'] = 'Share This Page';
		echo $before_widget;
		if (strlen($instance['title']) > 0)
			echo $before_title.$instance['title'].$after_title;
		echo '<script>
function select_text_box(ta_id) {
	ta_element = document.getElementById(ta_id);
	ta_element.style.display=\'block\';
	ta_element.focus();
	if(ta_element.setSelectionRange)
	   ta_element.setSelectionRange(0, ta_element.value.length);
	else {
	   var r = ta_element.createTextRange();
	   r.collapse(true);
	   r.moveEnd(\'character\', ta_element.value.length);
	   r.moveStart(\'character\', 0);
	   r.select();   
	}
}
</script><div style="text-align: left; position: relative;" onmouseover="select_text_box(\'copythis\');" onmouseout="document.getElementById(\'copythis\').style.display=\'none\';">'.SHAREABLE_link().'<textarea id="copythis" onclick="select_text_box(\'copythis\');" style="border: solid #00C 2px; position: absolute; top: -20px; left: -20px; height: 60px; width: 110%; display: none; word-break: break-all;" title="Copy the HTML in this box and paste it onto your site" readonly>'.htmlentities('<script src="'.$SHAREABLE_script_URI.TERRIBL_sexagesimal().'"></script>').'</textarea></div>'.$after_widget;
$_SESSION['eli_debug_microtime']['SHAREABLE_Widget_Class_widget_end'] = microtime(true);
	}
	function flush_widget_cache() {
		wp_cache_delete('widget_SHAREABLE', 'widget');
	}
	function update($new, $old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		return $instance;
	}
	function form( $instance ) {
$_SESSION['eli_debug_microtime']['SHAREABLE_Widget_Class_form_start'] = microtime(true);
		$title = isset($instance['title']) ? esc_attr($instance['title']) : 'Share This Page';
		echo '<p><label for="'.$this->get_field_id('title').'">'.__('Optional Widget Title').':</label><br />
		<input type="text" name="'.$this->get_field_name('title').'" id="'.$this->get_field_id('title').'" value="'.$title.'" /></p>To customize your Shareable Link go to: <a href="admin.php?page=SHAREABLE-settings">Link Settings</a>';
$_SESSION['eli_debug_microtime']['SHAREABLE_Widget_Class_form_end'] = microtime(true);
	}
}
function TERRIBL_set_plugin_action_links($links_array, $plugin_file) {
	if ($plugin_file == substr(__file__, (-1 * strlen($plugin_file)))) {
		$_SESSION['eli_debug_microtime']['TERRIBL_set_plugin_action_links'] = microtime(true);
		$links_array = array_merge(array('<a href="admin.php?page=SHAREABLE-settings">'.__( 'Settings' ).'</a>'), $links_array);
	}
	return $links_array;
}
function TERRIBL_set_plugin_row_meta($links_array, $plugin_file) {
	if ($plugin_file == substr(__file__, (-1 * strlen($plugin_file)))) {
		$_SESSION['eli_debug_microtime']['TERRIBL_set_plugin_row_meta'] = microtime(true);
		$links_array = array_merge($links_array, array('<a target="_blank" href="http://wordpress.org/extend/plugins/terribl/faq/">'.__( 'FAQ' ).'</a>','<a target="_blank" href="http://wordpress.org/tags/terribl">'.__( 'Support' ).'</a>','<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8VWNB5QEJ55TJ">'.__( 'Donate' ).'</a>'));
	}
	return $links_array;
}
add_filter('plugin_row_meta', $TERRIBL_plugin_dir.'_set_plugin_row_meta', 1, 2);
add_filter('plugin_action_links', $TERRIBL_plugin_dir.'_set_plugin_action_links', 1, 2);
$encode .= 'e';
$TERRIBL_plugin_home='http://wordpress.ieonly.com/';
$TERRIBL_images_path = plugins_url('/images/', __FILE__);
$TERRIBL_updated_images_path = 'wp-content/plugins/update/images/';
$TERRIBL_Logo_IMG='TERRIBL-16x16.gif';//<a href=\"javascript:document.TERRIBL_Form.submit();\" onclick=\"document.getElementById(\'auto_what\').value=\'show\';document.getElementById(\'manual_add\').value=\'',StatDomain,'\';\">Show</a>
$TERRIBL_SQL_SELECT = "SELECT `StatDomain` AS `Referring<br />Site`, MAX(`StatModified`) AS `Last<br />Referral`, SUM(`StatVisits`) AS `In-Bound<br />Clicks`, SUM(`StatImpressions`) AS `In-Bound<br />Impressions` FROM wp_terribl_stats GROUP BY StatDomain ORDER BY SUM(`StatImpressions`) DESC, IF(SUM(StatReturn)>0,1,0) DESC, SUM(`StatVisits`) DESC, `Last<br />Referral` DESC";//, (SELECT `StatRequestURI` FROM wp_terribl_stats AS pastReferer WHERE StatDomain = wp_terribl_stats.StatDomain ORDER BY StatModified DESC LIMIT 1) AS `In-Bound<br />URI`
register_activation_hook(__FILE__,$TERRIBL_plugin_dir.'_install');
add_action('widgets_init', create_function('', 'return register_widget("TERRIBL_Widget_Class");'));
add_action('widgets_init', create_function('', 'return register_widget("SHAREABLE_Widget_Class");'));
add_action('init', $TERRIBL_plugin_dir.'_init');
add_action('admin_menu', $TERRIBL_plugin_dir.'_menu');
$_SESSION['eli_debug_microtime']['end_include(TERRIBL)'] = microtime(true);
?>