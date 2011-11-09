<?php
/*
Plugin Name: Track Every Referer and Return In-Bound Links - TERRIBL
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/terribl-widget/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/
Description: This plugin is not terrible it's TERRIBL. It simply Tracks Every Referer and Returns In-Bound Links. Place the Widget on your sidebar to display a link to the HTTP_REFERER and any other sites that you would like to trade links with.
Version: 1.1.11.08
*/
$_SESSION['eli_debug_microtime']['include(TERRIBL)'] = microtime(true);
$TERRIBL_Version='1.1.11.08';
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
	if (version_compare($wp_version, "2.6", "<")) {
		deactivate_plugins(basename(__FILE__));
		wp_die(__("This Plugin requires WordPress version 2.6 or higher"));
	} else {
		$MySQL = "CREATE TABLE IF NOT EXISTS `wp_terribl_stats` (
  `StatMonth` date NOT NULL,
  `StatCreated` datetime NOT NULL,
  `StatModified` datetime NOT NULL,
  `StatFirstUserAgent` varchar(255) NOT NULL,
  `StatUserAgent` varchar(255) NOT NULL,
  `StatFirstRemoteAddr` varchar(16) NOT NULL,
  `StatRemoteAddr` varchar(16) NOT NULL,
  `StatReferer` varchar(255) NOT NULL,
  `StatRequestURI` varchar(255) NOT NULL,
  `StatVisits` int(11) unsigned NOT NULL default '0',
  `StatDomain` varchar(50) NOT NULL default '',
  `StatImpressions` bigint(20) NOT NULL default '0',
  `StatReturn` int(11) unsigned NULL default NULL,
  PRIMARY KEY  (`StatDomain`,`StatMonth`,`StatRequestURI`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		@mysql_query($MySQL);
		if (mysql_errno())
			echo '<li>ERROR: '.mysql_error().'<li>Try running the following SQL command as a Database Admin:<br><textarea disabled="yes" cols="65" rows="15">'.$MySQL.'</textarea>';
	}
$_SESSION['eli_debug_microtime']['TERRIBL_install_end'] = microtime(true);
}
function TERRIBL_display_header($pTitle) {
	global $TERRIBL_plugin_dir, $TERRIBL_plugin_home;
$_SESSION['eli_debug_microtime']['TERRIBL_display_header_start'] = microtime(true);
	echo '<style>
	.rounded-corners {margin: 10px; padding: 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border: 1px solid #000000;}
	.shadowed-box {box-shadow: -3px 3px 3px #666666; -moz-box-shadow: -3px 3px 3px #666666; -webkit-box-shadow: -3px 3px 3px #666666;}
	.shadowed-text {text-shadow: #0000FF -1px 1px 1px;}
	#right-sidebar {float: right; width: 230px;}
	#main-section {margin-right: 250px;}
	</style>
	<h1>ELI\'s TERRIBL '.$pTitle.'</h1>
	<div id="right-sidebar">
	<div id="" class="shadowed-box rounded-corners" style="background-color: #CCCCCC;"><center><h3 class="shadowed-text">TERRIBL Links</h3>
<table><tr><td>
<li><a target="_blank" href="'.$TERRIBL_plugin_home.'category/my-plugins/terribl-widget/">TERRIBL URI</a>
<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($TERRIBL_plugin_dir).'/faq/">TERRIBL FAQs</a>
<li><a target="_blank" href="http://wordpress.org/extend/plugins/'.strtolower($TERRIBL_plugin_dir).'/stats/">TERRIBL Stats</a>
</td></tr></table>
</center></div>
	<div id="" class="shadowed-box rounded-corners" style="background-color: #CCCCCC;"><center><h3 class="shadowed-text">TERRIBL Author</h3>Feed My Family:<br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
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
$_SESSION['eli_debug_microtime']['TERRIBL_display_header_end'] = microtime(true);
}
function TERRIBL_display_File($dFile) {
$_SESSION['eli_debug_microtime']['TERRIBL_display_File_start'] = microtime(true);
	if (file_exists(dirname(__FILE__).'/'.strtolower($dFile).'.txt')) {
		echo '<h2>'.$dFile.' File</h2><textarea disabled="yes" width="100%" style="width: 100%;" rows="20" class="shadowed-box">';
		include(strtolower($dFile).'.txt');
		echo '</textarea><br />';
	}
$_SESSION['eli_debug_microtime']['TERRIBL_display_File_end'] = microtime(true);
}
function TERRIBL_Settings() {
	global $TERRIBL_SQL_SELECT, $TERRIBL_plugin_dir;
$_SESSION['eli_debug_microtime']['TERRIBL_Settings_start'] = microtime(true);
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
	$Impression_URL = plugins_url('/images/', __FILE__).'index.php?Impression_URI=/'.$TERRIBL_settings_array['auto_root'];
	echo 'Copy the HTML in this box and give it to others who wish to link to your site<br /><textarea width="100%" style="width: 100%;" rows="3" class="shadowed-box"><a target="_blank" href="http://'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'].'/'.$TERRIBL_settings_array['auto_root'].'"><img border=0 src="'.$Impression_URL.'" /></a></textarea><br /><br /><form method="POST" name="MonthForm">Default Path to Send In-Bound Visiters to (for Site Root leave blank):<br />http://'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'].'/<input type="text" name="auto_root" value="'.$TERRIBL_settings_array['auto_root'].'" /><input type="submit" value="Update" /><br /><br />Automatically add In-Bound Referers to the "In-Bound Links" Widget?<br /><input type="radio" name="auto_return" value="yes"'.($TERRIBL_settings_array['auto_return']=="yes"?" checked":"").' onchange="document.MonthForm.submit();" />yes &nbsp; <input type="radio" name="auto_return" value="no"'.($TERRIBL_settings_array['auto_return']=="yes"?"":" checked").' onchange="document.MonthForm.submit();" />no<input type="hidden" name="MonthOf" value="'.$_POST['MonthOf'].'" /><br /><br />Kick off your In-Bound Widget by manually adding a site here:<br />http://<input type="text" name="manual_add" id="manual_add" value="wordpress.ieonly.com" /><input type="hidden" id="auto_what" name="auto_what" value="" /><input type="submit" value="Add Site Link to Widget" onclick="document.getElementById(\'auto_what\').value=\'add\';" />
	<h2>In-Bound Link Stats</h2>
	<table border=0 cellspacing=0><tr><td>';
	if (isset($_POST['manual_add']) && strlen($_POST['manual_add']) > 0 && isset($_POST['auto_what']) && strlen($_POST['auto_what']) > 0) {
		if ($_POST['auto_what']=='add')
			@mysql_query("INSERT INTO `wp_terribl_stats` (`StatMonth`, `StatCreated`, `StatModified`, `StatFirstUserAgent`, `StatUserAgent`, `StatFirstRemoteAddr`, `StatRemoteAddr`, `StatReferer`, `StatVisits`, `StatImpressions`, `StatDomain`, `StatReturn`, `StatRequestURI`) VALUES ('".date("Y-m")."-01', '".date("Y-m-d")."', '".date("Y-m-d")."', 'None', 'None', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['REMOTE_ADDR']."', 'http://".mysql_real_escape_string($_POST['manual_add'])."', 0, 0, '".$_POST['manual_add']."', 1, '/".$TERRIBL_settings_array['auto_root']."') ON DUPLICATE KEY UPDATE `StatReturn`=1");
		if ($_POST['auto_what']=='block')
			@mysql_query("UPDATE `wp_terribl_stats` SET `StatReturn`=NULL WHERE `StatDomain`='".$_POST['manual_add']."'");
	}
	$MySQL = "SELECT MONTHNAME(StatMonth) AS MonthOf, StatMonth FROM `wp_terribl_stats` GROUP BY StatMonth ORDER BY StatMonth DESC LIMIT 12";
	$result = mysql_query($MySQL);
	while ($rs = mysql_fetch_assoc($result))
		echo '<input type="submit" value="'.$rs['MonthOf'].'" onclick="document.MonthForm.MonthOf.value=\''.$rs['StatMonth'].'\';" style="'.($_POST['MonthOf']==$rs['StatMonth']?'background-color: #33FF33; ':'').'float: right;" />';
	echo '</td></tr></table><br />';
	$MySQL = str_replace("FROM wp_terribl_stats GROUP", "FROM wp_terribl_stats WHERE StatMonth = '".$_POST['MonthOf']."' GROUP", $TERRIBL_SQL_SELECT);
	$result = mysql_query($MySQL);
	if (mysql_errno()) echo '<li>ERROR: '.mysql_error().'<li>SQL:<br><textarea disabled="yes" cols="65" rows="15">'.$MySQL.'</textarea>';//only used for debugging.
	else {
		if ($rs = mysql_fetch_assoc($result)) {
			echo '<table border=1 cellspacing=0><tr>';
			foreach ($rs as $field => $value)
				echo '<td>&nbsp;<b>'.$field.'</b>&nbsp;</td>';
			do {
				echo '</tr><tr>';
				foreach ($rs as $field => $value)
					echo '<td>&nbsp;'.$value.'&nbsp;</td>';
			} while ($rs = mysql_fetch_assoc($result));
			echo '</tr></table>';
		} else
			echo '<li>No Stats Info Available At This Time!';
	}
	echo '</form>';
$_SESSION['eli_debug_microtime']['TERRIBL_Settings_end'] = microtime(true);
//TERRIBL_debug();//only used for debugging.//rem this line out
}
function TERRIBL_readme_license() {
$_SESSION['eli_debug_microtime']['TERRIBL_readme_license_start'] = microtime(true);
	TERRIBL_display_header('Readme & License');
	TERRIBL_display_File('Readme');
	TERRIBL_display_File('License');
	echo '</div></div>';
$_SESSION['eli_debug_microtime']['TERRIBL_readme_license_end'] = microtime(true);
}
function TERRIBL_menu() {
	global $TERRIBL_plugin_dir, $TERRIBL_Version, $TERRIBL_plugin_home, $TERRIBL_Logo_IMG;
$_SESSION['eli_debug_microtime']['TERRIBL_menu_start'] = microtime(true);
	$TERRIBL_updated_images_path = 'wp-content/plugins/UPDATE/images/';
	$TERRIBL_settings_array = get_option($TERRIBL_plugin_dir.'_settings_array');
	$Logo_URL = plugins_url('/images/', __FILE__).$TERRIBL_Logo_IMG;
	$img_path = basename(__FILE__);
	$Logo_Path = 'images/'.$TERRIBL_Logo_IMG;
	$Full_plugin_logo_URL = get_option('siteurl');
	$Full_plugin_logo_URL = $TERRIBL_plugin_home.$TERRIBL_updated_images_path.$img_path.'?v='.$TERRIBL_Version.'&p='.$TERRIBL_plugin_dir.'&d='.
	urlencode($Full_plugin_logo_URL);
	$Logo_URL = $Full_plugin_logo_URL;
	$base_page = $TERRIBL_plugin_dir.'-settings';
	if (function_exists('add_object_page'))
		add_object_page(__('ELI\'s TERRIBL Settings'), __('TERRIBL'), 'administrator', $base_page, $TERRIBL_plugin_dir.'_settings', $Logo_URL);
	else
		add_menu_page(__('ELI\'s TERRIBL Settings'), __('TERRIBL'), 'administrator', $base_page, $TERRIBL_plugin_dir.'_settings', $Logo_URL);
	add_submenu_page($base_page, __('ELI\'s TERRIBL Settings Page'), __('Settings &amp; Stats'), 'administrator', $base_page, $TERRIBL_plugin_dir.'_settings');
	add_submenu_page($base_page, __('ELI\'s TERRIBL - Readme &amp; License File'), __('Readme &amp; License'), 'administrator', $TERRIBL_plugin_dir.'-readme-license', $TERRIBL_plugin_dir.'_readme_license');
$_SESSION['eli_debug_microtime']['TERRIBL_menu_end'] = microtime(true);
}
function TERRIBL_debug() {
	echo 'debug:<pre>';
	print_r($_SESSION['eli_debug_microtime']);
	echo 'END;</pre>';
	$_SESSION['eli_debug_microtime']=array();
}
function TERRIBL_init() {
	global $TERRIBL_plugin_dir, $Visits_Impressions;
$_SESSION['eli_debug_microtime']['TERRIBL_init_start'] = microtime(true);
	$TERRIBL_settings_array = get_option($TERRIBL_plugin_dir.'_settings_array');
	if (!isset($TERRIBL_settings_array['auto_return'])) 
		$TERRIBL_settings_array['auto_return'] = "yes";
	$_SESSION[$TERRIBL_plugin_dir.'MonthOf'] = date("Y-m")."-01";
	$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST'] = (isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:"Your Domain"));
	update_option($TERRIBL_plugin_dir.'_settings_array', $TERRIBL_settings_array);
	if (isset($_SERVER['HTTP_REFERER']) && (!(isset($_SERVER['REQUEST_URI']) && substr(str_replace('/', '', strtolower($_SERVER['REQUEST_URI'].'/NOT')), 0, 3) == 'wp-') || $Visits_Impressions == 'StatImpressions')) {
		$TERRIBL_HTTP_REFERER = $_SERVER['HTTP_REFERER'];
		$TERRIBL_REFERER_Parts = explode('/', $TERRIBL_HTTP_REFERER.'//Unknown Domain');
//echo '<li>TERRIBL_HTTP_REFERER='.$TERRIBL_HTTP_REFERER;//only used for debugging.
		if ($TERRIBL_REFERER_Parts[2] != $_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']) {
			if ($Visits_Impressions != 'StatImpressions')
				$Visits_Impressions = 'StatVisits';
			$StatRequestURI = ((isset($_GET['Impression_URI']) && $Visits_Impressions == 'StatImpressions')?$_GET['Impression_URI']:(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:(isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:(isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:$_SESSION[$TERRIBL_plugin_dir.'HTTP_HOST']))));
			$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'] = $TERRIBL_HTTP_REFERER;
			$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'] = $TERRIBL_REFERER_Parts;
//echo '<li>TERRIBL_REFERER_Parts='.(is_array($TERRIBL_REFERER_Parts)?print_r($TERRIBL_REFERER_Parts,true):'!array');//only used for debugging.
			$StatReturn = ($TERRIBL_settings_array['auto_return']=="yes"?"0":"NULL");
			$now = date("Y-m-d H:i:s");
			$StatUserAgent = mysql_real_escape_string(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'Unknown USER_AGENT');
			$StatRemoteAddr = (isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'.Unknown.ADDR.');
			$MySQL = "INSERT INTO `wp_terribl_stats` (`StatMonth`, `StatCreated`, `StatModified`, `StatFirstUserAgent`, `StatUserAgent`, `StatFirstRemoteAddr`, `StatRemoteAddr`, `StatReferer`, `$Visits_Impressions`, `StatDomain`, `StatReturn`, `StatRequestURI`) VALUES ('".date("Y-m")."-01', '".$now."', '".$now."', '".$StatUserAgent."', '".$StatUserAgent."', '".$StatRemoteAddr."', '".$StatRemoteAddr."', '".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'])."', 1, '".$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'][2]."', (SELECT IF(LENGTH(StatDomain)>0, StatReturn, ".$StatReturn.") FROM wp_terribl_stats AS pastReferer WHERE StatDomain = '".$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'][2]."' ORDER BY StatModified DESC LIMIT 1), '".$StatRequestURI."') ON DUPLICATE KEY UPDATE `StatModified`='".$now."', `StatUserAgent`='".$StatUserAgent."', `StatRemoteAddr`='".$StatRemoteAddr."', `StatReferer`='".mysql_real_escape_string($_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'])."', `$Visits_Impressions`=`$Visits_Impressions`+1";
			@mysql_query($MySQL);
			if (mysql_errno()) mail("wordpress@ieonly.com", "TERRIBL SQL INSERT", mysql_error().'\nSQL:'.$SQL."\n".print_r(array('POST'=>$_POST, 'SESSION'=>$_SESSION, 'SERVER'=>$_SERVER), true));//only used for debugging.//rem this line out
		}
	}
//echo '<li>ERR: '.$TERRIBL_plugin_dir.'REFERER_Parts='.(isset($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'])?(is_array($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'])?print_r($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'],true):'!array'):'!set');//only used for debugging.
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
		global $TERRIBL_SQL_SELECT, $TERRIBL_plugin_dir;
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
			$LIs .= '<li class="TERRIBL-Link"><a target="_blank" title="You got here from '.$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'].'" href="'.$_SESSION[$TERRIBL_plugin_dir.'HTTP_REFERER'].'" rel="bookmark">'.$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'][2]."</a></li>\n";
		}// else echo 'ERR: '.$TERRIBL_plugin_dir.'REFERER_Parts='.(isset($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'])?(is_array($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'])?print_r($_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'],true):'!array'):'!set');//only used for debugging.
		$MySQL = str_replace("FROM wp_terribl_stats GROUP", "FROM wp_terribl_stats WHERE StatReturn IS NOT NULL AND StatDomain != '".$_SESSION[$TERRIBL_plugin_dir.'REFERER_Parts'][2]."' GROUP", $TERRIBL_SQL_SELECT);
		$result = mysql_query($MySQL);
		if (!mysql_errno()) {
			if (($rs = mysql_fetch_assoc($result)) && ($instance['number'] > 0)) {
				$li=0;	
				do {
					$li++;
//`StatDomain` AS `Referring Site`, `StatRequestURI` AS `In-Bound URI`, `StatModified` AS `Last Referral`, `StatImpressions` AS `In-Bound Impressions`, `StatVisits` AS `In-Bound Clicks`
					$SafeReferer = explode('wp-admin', $rs['From Page'].'wp-admin');
					$LIs .= '<li class="TERRIBL-Link"><a target="_blank" title="'.$rs['Referring Site'].' linked here on '.$rs['Last Referral'].'" href="'.$SafeReferer[0].'" rel="bookmark">'.$rs['Referring Site']."</a>&nbsp;(".$rs['In-Bound Clicks'].")</li>\n";
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
		$title = isset($instance['title']) ? esc_attr($instance['title']) : 'In-Bound Links';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$riblfer = isset($instance['riblfer']) ? esc_attr($instance['riblfer']) : 'yes';
		echo '<p><label for="'.$this->get_field_id('title').'">'.__('Widget Title').':</label>
		<input type="text" name="'.$this->get_field_name('title').'" id="'.$this->get_field_id('title').'" value="'.$title.'" /></p>
		<p><label for="'.$this->get_field_id('riblfer').'">'.__('Display a Link to the Current Referer').':</label>
		<input type="checkbox" name="'.$this->get_field_name('riblfer').'" id="'.$this->get_field_id('riblfer').'" value="yes"'.($riblfer=="yes"?" checked":"").' />yes</p>
		<p><label for="'.$this->get_field_id('number').'">Number of Older Referer to Display:</label>
		<input type="text" size="2" name="'.$this->get_field_name('number').'" id="'.$this->get_field_id('number').'" value="'.$number.'" /></p>';
$_SESSION['eli_debug_microtime']['TERRIBL_Widget_Class_form_end'] = microtime(true);
	}
}
$TERRIBL_SQL_SELECT = "SELECT IF(MAX(`StatReturn`) IS NULL, 'Site Blocked!', CONCAT('<input type=\"submit\" value=\"Block This Site!\" onclick=\"document.getElementById(\'auto_what\').value=\'block\';document.getElementById(\'manual_add\').value=\'',StatDomain,'\';\" />')) AS `Widget Action`, `StatDomain` AS `Referring Site`, (SELECT StatReferer FROM wp_terribl_stats AS pastReferer WHERE StatDomain = wp_terribl_stats.StatDomain ORDER BY StatModified DESC LIMIT 1) AS `From Page`, (SELECT `StatRequestURI` FROM wp_terribl_stats AS pastReferer WHERE StatDomain = wp_terribl_stats.StatDomain ORDER BY StatModified DESC LIMIT 1) AS `In-Bound URI`, MAX(`StatModified`) AS `Last Referral`, SUM(`StatImpressions`) AS `In-Bound Impressions`, SUM(`StatVisits`) AS `In-Bound Clicks` FROM wp_terribl_stats GROUP BY StatDomain ORDER BY MAX(StatReturn) DESC, `In-Bound Clicks` DESC, `In-Bound Impressions` DESC, `Last Referral` DESC";
$TERRIBL_plugin_home='http://wordpress.ieonly.com/';
$TERRIBL_Logo_IMG='ELI-16x16.gif';
register_activation_hook(__FILE__,$TERRIBL_plugin_dir.'_install');
add_action('widgets_init', create_function('', 'return register_widget("TERRIBL_Widget_Class");'));
add_action('init', $TERRIBL_plugin_dir.'_init');
add_action('admin_menu', $TERRIBL_plugin_dir.'_menu');
$_SESSION['eli_debug_microtime']['end_include(TERRIBL)'] = microtime(true);
?>
