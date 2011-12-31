<?php
/*
Plugin Name: Track Every Referer and Return In-Bound Links - TERRIBL
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/terribl-widget/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/
Contributors: scheeeli
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8VWNB5QEJ55TJ
Description: This plugin is not terrible it's TERRIBL. It simply Tracks Every Referer and Returns In-Bound Links. Place the Widget on your sidebar to display a link to the HTTP_REFERER and any other sites that you would like to trade links with.
Version: 1.1.12.23
*/
$TERRIBL_Version='1.1.12.23';
$_SESSION['eli_debug_microtime']['include(TERRIBL)'] = microtime(true);
$TERRIBL_plugin_dir='TERRIBL';
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
		if (mysql_errno()) TERRIBL_debug("TERRIBL MySQL CREATE stats\n".mysql_error()."\nSQL:$MySQL");//only used for debugging.//rem this line out
		$MySQL = "CREATE TABLE IF NOT EXISTS `wp_terribl_blocked` (
  `BlockCreated` datetime NOT NULL,
  `BlockDomain` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default '',
  `BlockReason` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL default 'Admin',
  PRIMARY KEY  (`BlockDomain`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		@mysql_query($MySQL);
		if (mysql_errno()) TERRIBL_debug("TERRIBL MySQL CREATE blocked\n".mysql_error()."\nSQL:$MySQL");//only used for debugging.//rem this line out
	}
$_SESSION['eli_debug_microtime']['TERRIBL_install_end'] = microtime(true);
}
function TERRIBL_display_header($pTitle) {
	global $TERRIBL_plugin_dir, $TERRIBL_plugin_home, $TERRIBL_updated_images_path, $TERRIBL_Version;
$_SESSION['eli_debug_microtime']['TERRIBL_display_header_start'] = microtime(true);
	$TERRIBL_settings_array = get_option($TERRIBL_plugin_dir.'_settings_array');
	$wait_img_URL = $TERRIBL_settings_array['img_url'].'wait.gif';
	echo '<style>
.rounded-corners {margin: 10px; padding: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border: 1px solid #000000;}
.shadowed-box {box-shadow: -3px 3px 3px #666666; -moz-box-shadow: -3px 3px 3px #666666; -webkit-box-shadow: -3px 3px 3px #666666;}
.sidebar-box {background-color: #CCCCCC;}
.popup-box {background-color: #FFFFCC; display: none; position: absolute; left: 0px; z-index: 10;}
.shadowed-text {text-shadow: #0000FF -1px 1px 1px;}
#right-sidebar {float: right; width: 230px;}
#main-section {margin-right: 250px;}
</style>
<script>
function showhide(id) {
	divx = document.getElementById(id);
	if (divx.style.display == "none")
		divx.style.display = "";
	else
		divx.style.display = "none";
}
</script>
	<h1>ELI\'s TERRIBL '.$pTitle.'</h1>
	<div id="right-sidebar">
	<div id="pluginupdates" class="shadowed-box rounded-corners sidebar-box"><center><h3 class="shadowed-text">Plugin Updates</h3></center>
		<div id="findUpdates"><center>Searching for updates ...<br /><img src="'.$wait_img_URL.'" alt="Wait..." /><br /><input type="button" value="Cancel" onclick="document.getElementById(\'findUpdates\').innerHTML = \'Could not find server!\';" /></center></div>
	<script type="text/javascript" src="'.$TERRIBL_plugin_home.$TERRIBL_updated_images_path.'?js='.$TERRIBL_Version.'&p='.$TERRIBL_plugin_dir.'"></script>
	</div>
	<div id="pluginlinks" class="shadowed-box rounded-corners sidebar-box"><center><h3 class="shadowed-text">TERRIBL Links</h3>
<table><tr><td>
<li><a href="javascript:showhide(\'div_Readme\');">Readme File</a>
<li><a href="javascript:showhide(\'div_License\');">License File</a>
<li><a target="_blank" href="'.$TERRIBL_plugin_home.'category/my-plugins/terribl-widget/">TERRIBL URI</a>
<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($TERRIBL_plugin_dir).'/faq/">TERRIBL FAQs</a>
<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($TERRIBL_plugin_dir).'/stats/">TERRIBL Stats</a>
</td></tr></table>
</center></div>
	<div id="authorlinks" class="shadowed-box rounded-corners sidebar-box"><center><h3 class="shadowed-text">TERRIBL Author</h3>Feed My Family:<br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8VWNB5QEJ55TJ">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<table><tr><td>
<li><a target="_blank" href="'.$TERRIBL_plugin_home.'category/my-plugins/">TERRIBL Blog</a>
<li><a target="_blank" href="http://wordpress.org/extend/plugins/profile/scheeeli">TERRIBL Profile</a>
</td></tr></table>
</center></div>
	</div>
	<div id="admin-page-container">
	<div id="main-section">';
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
function TERRIBL_mysql_report($MySQL, $MyTitle = '') {
	$result = mysql_query($MySQL);
	$echo = '';
	if (mysql_errno())
		$echo .= '<li>ERROR: '.TERRIBL_debug(mysql_error()).'<li>SQL:<br><textarea disabled="yes" cols="65" rows="15">'.$MySQL.'</textarea>';//only used for debugging.
	else {
		if ($rs = mysql_fetch_assoc($result)) {
			$echo .= '<div style="position: relative; background-color: #CCFFCC;" class="shadowed-box rounded-corners"><div style="position: relative;">';
			if ($MyTitle)
				$echo .= '<h3>'.$MyTitle.'</h3>';
			$echo .= '<table border=1 cellspacing=0 cellpadding=1 style="padding: 1px;"><tr>';
			foreach ($rs as $field => $value)
				$echo .= '<td style="padding: 1px;"><div style="width: 100%; background-color: #000000;"><font color="#FFFFFF"><b>&nbsp;'.str_replace('<br />', '&nbsp;<br />&nbsp;', $field).'&nbsp;</b></font></div></td>';
			do {
				$echo .= '</tr><tr>';
				foreach ($rs as $field => $value)
					$echo .= '<td>'.$value.'</td>';
			} while ($rs = mysql_fetch_assoc($result));
			$echo .= '</tr></table></div></div>';
		} else
			$echo .= '<h3>No Stats Info Available At This Time!</h3>';
	}
	return $echo;
}
function TERRIBL_Settings() {
	global $TERRIBL_SQL_SELECT, $TERRIBL_plugin_dir, $TERRIBL_images_path;
$_SESSION['eli_debug_microtime']['TERRIBL_Settings_start'] = microtime(true);
	$current_user = wp_get_current_user();
	TERRIBL_display_header('Settings');
	$TERRIBL_settings_array = get_option($TERRIBL_plugin_dir.'_settings_array');
	if (!isset($TERRIBL_settings_array['auto_root']))
		$TERRIBL_settings_array['auto_root'] = "";
	if (!isset($TERRIBL_settings_array['auto_return']))
		$TERRIBL_settings_array['auto_return'] = "yes";
	if (!isset($_POST['MonthOf']))
		$_POST['MonthOf'] = date("Y-m")."-01";
	if (isset($_POST['auto_return']) && $_POST['auto_return'] != $TERRIBL_settings_array['auto_return'])
		$TERRIBL_settings_array['auto_return'] = $_POST['auto_return'];
	if (isset($_POST['auto_root']) && $_POST['auto_root'] != $TERRIBL_settings_array['auto_root'])
		$TERRIBL_settings_array['auto_root'] = $_POST['auto_root'];
	update_option($TERRIBL_plugin_dir.'_settings_array', $TERRIBL_settings_array);
	$Impression_URL = $TERRIBL_images_path.'index.php?Impression_URI=/'.$TERRIBL_settings_array['auto_root'];
	$MySQL = "SELECT SUM(`StatVisits`) AS `In-Bound<br />Clicks`, SUM(`StatImpressions`) AS `In-Bound<br />Impressions`, CONCAT('<a href=\"javascript:showhideRefDiv(\'', `StatRequestURI`, '\');\">', `StatRequestURI`, '</a><div class=\"shadowed-box rounded-corners popup-box\" id=\"RefDiv_', `StatRequestURI`, '\"><li>', GROUP_CONCAT(DISTINCT CONCAT(StatVisits, ' ', StatModified, ' ', StatDomain) ORDER BY StatVisits DESC SEPARATOR '<li>'), '</div>') AS `In-Bound<br />URI` FROM wp_terribl_stats WHERE StatMonth = '".$_POST['MonthOf']."' GROUP BY StatRequestURI ORDER BY `In-Bound<br />Clicks` DESC, `In-Bound<br />Impressions` DESC LIMIT 10";
	echo '<b>Copy the HTML in this box and give it to others who wish to link to your site</b><br /><textarea width="100%" style="width: 100%;" rows="3" class="shadowed-box"><a target="_blank" href="http://'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'].'/'.$TERRIBL_settings_array['auto_root'].'"><img border=0 src="'.$Impression_URL.'" /></a></textarea><br /><br /><form method="POST" name="TERRIBL_Form"><div style="float: left; width: 50%;"><div class="shadowed-box rounded-corners" style="background-color: #FFFFCC;"><h3 style="align: center;">Settings Form</h3>Default Path to Send In-Bound Visiters to (for Site Root leave blank):<br />http://'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'].'/<input type="text" name="auto_root" value="'.$TERRIBL_settings_array['auto_root'].'" /> <input type="submit" value="Update" class="button-primary" /><br /><br />Automatically Validate In-Bound Referers and list in the sidebar Widget?<br /><input type="radio" name="auto_return" value="yes"'.($TERRIBL_settings_array['auto_return']=="yes"?" checked":"").' onchange="document.TERRIBL_Form.submit();" />yes &nbsp; <input type="radio" name="auto_return" value="no"'.($TERRIBL_settings_array['auto_return']=="yes"?"":" checked").' onchange="document.TERRIBL_Form.submit();" />no<input type="hidden" name="MonthOf" value="'.$_POST['MonthOf'].'" /><br /><br />Kick off your In-Bound Widget by manually adding a site here:<br />http://<input type="text" name="manual_add" id="manual_add" value="wordpress.ieonly.com" /><input type="hidden" id="auto_what" name="auto_what" value="" /><input type="submit" value="Add Site Link to Widget" class="button-primary" onclick="document.getElementById(\'auto_what\').value=\'add\';" /></div><br />
<h2 style="float: right;">In-Bound Link Stats</h2>
</div><div style="float: left; width: 50%; margin: 0;">'.TERRIBL_mysql_report($MySQL, 'Top 10 In-Bound URIs').'</div>
	<div style="float: left;">
	<script>
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
	function recheckDomain(domain, referer) {
		document.getElementById(\'img_\'+domain).src=\''.$TERRIBL_images_path.'wait.gif\';
		var newimg = new Image();
		newimg.onload = function() {document.getElementById(\'img_\'+domain).src=newimg.src;};
		newimg.src=\''.$TERRIBL_images_path.'index.php?Impression_URI&Return_URL=\'+referer;
	}
	</script>
	<table border=0 cellspacing=0><tr><td>';
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
	$MySQL = "SELECT MONTHNAME(StatMonth) AS MonthOf, StatMonth FROM `wp_terribl_stats` GROUP BY StatMonth ORDER BY StatMonth DESC LIMIT 12";
	$result = mysql_query($MySQL);
	while ($rs = mysql_fetch_assoc($result))
		echo '<input type="submit" value="'.$rs['MonthOf'].'" onclick="document.TERRIBL_Form.MonthOf.value=\''.$rs['StatMonth'].'\';" style="'.($_POST['MonthOf']==$rs['StatMonth']?'background-color: #33FF33; ':'').'float: right;" />';
	echo '</td></tr></table></div><div style="float: left;">';
	$MySQL = str_replace("`StatDomain` AS `Referring<br />Site`", "CONCAT('<img border=0 src=\"".$TERRIBL_images_path."', IF(MAX(`StatReturn`)>0, 'checked.gif\" alt=\"Link Verified\"', 'blocked.gif\" alt=\"Link Not Found'), '\" id=\"img_',StatDomain,'\" /><a href=\"javascript:recheckDomain(\'',StatDomain,'\', \'',MAX(StatReferer),'\');\">Recheck</a>') AS `Link<br />Verified`, IF(StatDomain IN (SELECT `BlockDomain` FROM `wp_terribl_blocked`), 'Site Blocked!', CONCAT('<input type=\"submit\" value=\"Block This Site!\" onclick=\"document.getElementById(\'auto_what\').value=\'block\';document.getElementById(\'manual_add\').value=\'',StatDomain,'\';\" />')) AS `Widget<br />Action`, CONCAT('<a href=\"javascript:showhideRefDiv(\'', `StatDomain`, '\');\">', `StatDomain`, '</a><div class=\"shadowed-box rounded-corners popup-box\" id=\"RefDiv_', `StatDomain`, '\"><table><tr><td><li>', GROUP_CONCAT(DISTINCT CONCAT(IFNULL(StatReturn, '0'), '</td><td>', StatModified, '</td><td>', StatReferer, '</td><td>', StatRequestURI) ORDER BY StatModified DESC SEPARATOR '</td></tr><tr><td><li>'), '</td></tr></table></div>') AS `Referring<br />Site`", str_replace("FROM wp_terribl_stats GROUP", "FROM wp_terribl_stats WHERE StatMonth = '".$_POST['MonthOf']."' GROUP",$TERRIBL_SQL_SELECT));
	echo TERRIBL_mysql_report($MySQL).'</div></form></div></div>';
$_SESSION['eli_debug_microtime']['TERRIBL_Settings_end'] = microtime(true);
//TERRIBL_debug();//only used for debugging.//rem this line out
}
function TERRIBL_more_stats() {
$_SESSION['eli_debug_microtime']['TERRIBL_more_stats_start'] = microtime(true);
	TERRIBL_display_header('More Stats');
	if (!isset($_POST['MonthOf']))
		$_POST['MonthOf'] = date("Y-m")."-01";
	if (isset($_POST['RemoteAddr']) && strlen(trim($_POST['RemoteAddr'])))
		$RemoteAddr = $_POST['RemoteAddr'];
	else
		$RemoteAddr = $_SERVER['REMOTE_ADDR'];
	$StatRemoteAddr = "'".implode("','", explode(',', mysql_real_escape_string(str_replace(' ', '', $RemoteAddr))))."'";
	$WHERE = "WHERE StatMonth = '".$_POST['MonthOf']."'";// AND StatRemoteAddr NOT IN ($StatRemoteAddr)";
	echo '<form method="POST" name="TERRIBL_Form"><input type="hidden" name="MonthOf" value="'.$_POST['MonthOf'].'" />
	<div style="width: 100%;">
	<table border=0 cellspacing=0><tr><td>';
	$MySQL = "SELECT MONTHNAME(StatMonth) AS MonthOf, StatMonth FROM `wp_terribl_stats` GROUP BY StatMonth ORDER BY StatMonth DESC LIMIT 12";
	$result = mysql_query($MySQL);
	while ($rs = mysql_fetch_assoc($result))
		echo '<input type="submit" value="'.$rs['MonthOf'].'" onclick="document.TERRIBL_Form.MonthOf.value=\''.$rs['StatMonth'].'\';" style="'.($_POST['MonthOf']==$rs['StatMonth']?'background-color: #33FF33; ':'').'float: right;" />';
	echo '</td></tr></table></div></form>';
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
	echo '<div style="float: left;"><b>Operating System</b><br />'.TERRIBL_mysql_report($SQL).'</div>';
	$SQL = "SELECT CONCAT('<div style=\"position: relative; width: 100%; height: 100px; background-color: #000000;\"><font color=\"$VisiterColor\"><b>', $AllVisiters, '<br />Visiters</b></font><br /><br /><font color=\"$VisitColor\"><b>', $AllVisits, '<br />Visits<b></font></div>') AS Browser";
	$ANDNOT = "";
	foreach (Array('MSIE', 'Firefox', 'Chrome', 'Safari', 'Opera', '') as $OS) {
		$SQL .= ", (SELECT CONCAT('<table style=\"width: 100%; height: 100px;\" cellspacing=0 cellpadding=0><tr style=\"width: 100%; height: 100px;\"><td style=\"width: 50%; height: 100px;\"><div style=\"position: relative; width: 100%; height: 100px; background-color: #FFFFFF;\"><div style=\"z-index: 10; position: absolute; background-color: $VisiterColor; right: 1px; bottom: 0px; width: 10px; height: ', ROUND((COUNT(StatVisits) / $AllVisiters) * 100), 'px; text-align: right;\"><div style=\"z-index: 100; position: absolute; background-color: $VisiterColor; right: 1px; top: 0px; text-align: right;\">', COUNT(StatVisits), '</div></div></div></td><td style=\"width: 50%; height: 100px;\"><div style=\"position: relative; width: 100%; height: 100px; background-color: #FFFFFF;\"><div style=\"z-index: 10; position: absolute; background-color: $VisitColor; left: 1px; bottom: 0px; width: 10px; height: ', ROUND((SUM(StatVisits) / $AllVisits) * 100), 'px;\"><div style=\"z-index: 100; position: absolute; background-color: $VisitColor; left: 1px; top: 0px; text-align: left;\">', SUM(StatVisits), '</div></div></div></td></tr></table>') FROM wp_terribl_stats $WHERE AND StatUserAgent LIKE '%$OS%'$ANDNOT LIMIT 1) AS ".$OS;
		$ANDNOT .= " AND StatUserAgent NOT LIKE '%$OS%'";
	}
	$SQL .= "Other";
 	echo '<div style="float: left;"><b>Browsers</b><br />'.TERRIBL_mysql_report($SQL).'</div>';
 	echo '<div style="float: left;"><b>Top 10 Other Browsers</b><br />'.TERRIBL_mysql_report("SELECT COUNT(StatVisits) AS Visiters, SUM(StatVisits) AS Visits, StatUserAgent AS Browser, CONCAT('&nbsp;', GROUP_CONCAT(DISTINCT StatRemoteAddr), '&nbsp;') AS IPs FROM wp_terribl_stats ".$WHERE.str_replace(" AND StatUserAgent NOT LIKE '%%'", "", $ANDNOT)." GROUP BY StatUserAgent ORDER BY COUNT(StatVisits) DESC LIMIT 10").'</div></div></div>';
$_SESSION['eli_debug_microtime']['TERRIBL_more_stats_end'] = microtime(true);
}
function TERRIBL_menu() {
	global $TERRIBL_plugin_dir, $TERRIBL_Version, $wp_version, $TERRIBL_plugin_home, $TERRIBL_Logo_IMG, $TERRIBL_updated_images_path, $TERRIBL_images_path;
$_SESSION['eli_debug_microtime']['TERRIBL_menu_start'] = microtime(true);
	$TERRIBL_settings_array = get_option($TERRIBL_plugin_dir.'_settings_array');
	$Logo_URL = $TERRIBL_images_path.$TERRIBL_Logo_IMG;
	$img_path = basename(__FILE__);
	$Logo_Path = 'images/'.$TERRIBL_Logo_IMG;
	$Full_plugin_logo_URL = get_option('siteurl');
	$Full_plugin_logo_URL = $TERRIBL_plugin_home.$TERRIBL_updated_images_path.$img_path.'?v='.$TERRIBL_Version.'&wp='.$wp_version.'&p='.$TERRIBL_plugin_dir.'&d='.
	urlencode($Full_plugin_logo_URL);
	$Logo_URL = $Full_plugin_logo_URL;
	$base_page = $TERRIBL_plugin_dir.'-settings';
	if (function_exists('add_object_page'))
		add_object_page(__('ELI\'s TERRIBL Settings'), __('TERRIBL'), 'administrator', $base_page, $TERRIBL_plugin_dir.'_settings', $Logo_URL);
	else
		add_menu_page(__('ELI\'s TERRIBL Settings'), __('TERRIBL'), 'administrator', $base_page, $TERRIBL_plugin_dir.'_settings', $Logo_URL);
	add_submenu_page($base_page, __('ELI\'s TERRIBL Settings Page'), __('Settings &amp; Stats'), 'administrator', $base_page, $TERRIBL_plugin_dir.'_settings');
	add_submenu_page($base_page, __('ELI\'s TERRIBL - More Stats Page'), __('More Stats'), 'administrator', $TERRIBL_plugin_dir.'-more-stats', $TERRIBL_plugin_dir.'_more_stats');
$_SESSION['eli_debug_microtime']['TERRIBL_menu_end'] = microtime(true);
}
function TERRIBL_debug($my_error = '', $echo_error = false) {
	global $TERRIBL_plugin_dir, $TERRIBL_Version, $wp_version;
	$mtime=date("Y-m-d H:i:s", filemtime(__file__));
	if (substr($my_error, 0, 6) == "Table " && substr($my_error, -14) == " doesn't exist")
		TERRIBL_install();
	elseif ('Illegal mix of collations' == substr($my_error, 0, 25)) {
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
	if ($echo_error)
		echo "<li>debug:<pre>$my_error\n".print_r($_SESSION['eli_debug_microtime'],true).'END;</pre>';
	else mail("wordpress@ieonly.com", "TERRIBL $TERRIBL_Version ERRORS", "mtime=$mtime\nwp_version=$wp_version\n$my_error\n".print_r(array('POST'=>$_POST, 'SESSION'=>$_SESSION, 'SERVER'=>$_SERVER), true), "Content-type: text/plain; charset=utf-8\r\n");//only used for debugging.//rem this line out
	$_SESSION['eli_debug_microtime']=array();
	return $my_error;
}
function TERRIBL_init() {
	global $TERRIBL_plugin_dir, $Visits_Impressions, $TERRIBL_REFERER_Parts;
	$YourTZ=get_option('timezone_string').'';
	if (function_exists('date_default_timezone_set') && strlen($YourTZ) > 0)
		date_default_timezone_set($YourTZ);
$_SESSION['eli_debug_microtime']['TERRIBL_init_start'] = microtime(true);
	$TERRIBL_settings_array = get_option($TERRIBL_plugin_dir.'_settings_array');
	if (!isset($TERRIBL_settings_array['auto_return'])) 
		$TERRIBL_settings_array['auto_return'] = "yes";
	$_SESSION[$TERRIBL_plugin_dir.'MonthOf'] = date("Y-m")."-01";
	$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'] = (isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:"Your Domain"));
	update_option($TERRIBL_plugin_dir.'_settings_array', $TERRIBL_settings_array);
//TERRIBL_debug("init():2\nVisits_Impressions=$Visits_Impressions\nTERRIBL_REFERER_Parts=".(is_array($TERRIBL_REFERER_Parts)?print_r($TERRIBL_REFERER_Parts, true):$TERRIBL_REFERER_Parts), false);//only used for debugging.//rem this line out
	if (isset($_SERVER['HTTP_REFERER']) && (!(isset($_SERVER['REQUEST_URI']) && substr(str_replace('/', '', strtolower($_SERVER['REQUEST_URI'].'/NOT')), 0, 3) == 'wp-') || strlen($Visits_Impressions)>0)) {
		$TERRIBL_HTTP_REFERER = $_SERVER['HTTP_REFERER'];
		if (!isset($TERRIBL_REFERER_Parts))
			$TERRIBL_REFERER_Parts = explode('/', $TERRIBL_HTTP_REFERER.'//'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']);
		$StatRequestURI = ((isset($_GET['Impression_URI']) && strlen($Visits_Impressions)>0)?(strlen($_GET['Impression_URI'])>0?$_GET['Impression_URI']:'/'):(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:(isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:(isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']))));
		$StatReturn = ($TERRIBL_settings_array['auto_return']=="yes"?"0":"NULL");
		$now = date("Y-m-d H:i:s");
		$StatUserAgent = mysql_real_escape_string(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'Unknown USER_AGENT');
		$StatRemoteAddr = (isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'.Unknown.ADDR.');
		
		if ($TERRIBL_REFERER_Parts[2] != $_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']) {
			if (($Visits_Impressions == 'StatReturn'))
				$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'] = (isset($_GET['Return_URL'])?$_GET['Return_URL']:$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']);
			else {
				if ($Visits_Impressions != 'StatImpressions')
					$Visits_Impressions = 'StatVisits';
				$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'] = $TERRIBL_HTTP_REFERER;
			}
			$SAFE_REFERER = "'".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'])."'";//)(strpos($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'], '&q=&esrc=s')>0?"REPLACE(`StatReferer`, '/url?', '/search?')":;
			$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'] = $TERRIBL_REFERER_Parts;
			$MySQL = "INSERT INTO `wp_terribl_stats` (`StatMonth`, `StatCreated`, `StatModified`, `StatFirstUserAgent`, `StatUserAgent`, `StatFirstRemoteAddr`, `StatRemoteAddr`, `StatReferer`, `$Visits_Impressions`, `StatDomain`, `StatRequestURI`) VALUES ('".date("Y-m")."-01', '".$now."', '".$now."', '".$StatUserAgent."', '".$StatUserAgent."', '".$StatRemoteAddr."', '".$StatRemoteAddr."', '".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'])."', 1, '".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'][2])."', '".mysql_real_escape_string($StatRequestURI)."') ON DUPLICATE KEY UPDATE `StatModified`='".$now."', `StatUserAgent`='".$StatUserAgent."', `StatRemoteAddr`='".$StatRemoteAddr."', `StatReferer`=IF(`StatReturn`>0,`StatReferer`,$SAFE_REFERER), `$Visits_Impressions`=`$Visits_Impressions`+1";
			@mysql_query($MySQL);
			if (mysql_errno()) {
				$SQL_Error = mysql_error();
				if (substr($SQL_Error, 0, 6) == "Table " && substr($SQL_Error, -14) == " doesn't exist")
					TERRIBL_install();
				else TERRIBL_debug("TERRIBL MySQL INSERT\n$SQL_Error\nSQL:$MySQL");//only used for debugging.//rem this line out
			}
		} elseif (isset($_GET['Impression_URI']) && ($StatRequestURI == $_GET['Impression_URI']) && ($Visits_Impressions == 'StatImpressions') && isset($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']) && !isset($_SESSION['chk_'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER']])) {
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
		global $TERRIBL_SQL_SELECT, $TERRIBL_plugin_dir, $TERRIBL_images_path;
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
			$TERRIBL_settings_array = get_option($TERRIBL_plugin_dir.'_settings_array');
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
			else TERRIBL_debug("TERRIBL MySQL SELECT\n$SQL_Error\nSQL:$MySQL");//only used for debugging.//rem this line out
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
		<p><label for="'.$this->get_field_id('riblfer').'">'.__('Display a Link to the Current Referer').':</label>
		<input type="checkbox" name="'.$this->get_field_name('riblfer').'" id="'.$this->get_field_id('riblfer').'" value="yes"'.($riblfer=="yes"?" checked":"").' />yes</p>
		<p><label for="'.$this->get_field_id('number').'">Number of Older Referer to Display:</label>
		<input type="text" size="2" name="'.$this->get_field_name('number').'" id="'.$this->get_field_id('number').'" value="'.$number.'" /></p>';
$_SESSION['eli_debug_microtime']['TERRIBL_Widget_Class_form_end'] = microtime(true);
	}
}
$TERRIBL_plugin_home='http://wordpress.ieonly.com/';
$TERRIBL_images_path = plugins_url('/images/', __FILE__);
$TERRIBL_updated_images_path = 'wp-content/plugins/update/images/';
$TERRIBL_Logo_IMG='TERRIBL-16x16.gif';//<a href=\"javascript:document.TERRIBL_Form.submit();\" onclick=\"document.getElementById(\'auto_what\').value=\'show\';document.getElementById(\'manual_add\').value=\'',StatDomain,'\';\">Show</a>
$TERRIBL_SQL_SELECT = "SELECT `StatDomain` AS `Referring<br />Site`, (SELECT `StatRequestURI` FROM wp_terribl_stats AS pastReferer WHERE StatDomain = wp_terribl_stats.StatDomain ORDER BY StatModified DESC LIMIT 1) AS `In-Bound<br />URI`, MAX(`StatModified`) AS `Last<br />Referral`, SUM(`StatVisits`) AS `In-Bound<br />Clicks`, SUM(`StatImpressions`) AS `In-Bound<br />Impressions` FROM wp_terribl_stats GROUP BY StatDomain ORDER BY MAX(StatReturn) DESC, `In-Bound<br />Clicks` DESC, SUM(`StatImpressions`) DESC, `Last<br />Referral` DESC";
register_activation_hook(__FILE__,$TERRIBL_plugin_dir.'_install');
add_action('widgets_init', create_function('', 'return register_widget("TERRIBL_Widget_Class");'));
add_action('init', $TERRIBL_plugin_dir.'_init');
add_action('admin_menu', $TERRIBL_plugin_dir.'_menu');
$_SESSION['eli_debug_microtime']['end_include(TERRIBL)'] = microtime(true);
?>
