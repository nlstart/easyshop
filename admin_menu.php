<?php
/*
+------------------------------------------------------------------------------+
| EasyShop - an easy e107 web shop  | adapted by nlstart
| formerly known as
|	jbShop - by Jesse Burns aka jburns131 aka Jakle
|	Plugin Support Site: e107.webstartinternet.com
|
|	For the e107 website system visit http://e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+------------------------------------------------------------------------------+
*/
// Ensure this program is loaded in admin theme before calling class2
$eplug_admin = true;

// class2.php is the heart of e107, always include it first to give access to e107 constants and variables
require_once('../../class2.php');
// Check to see if the current user has admin permissions for this plugin
if ( ! getperms('P')) { header('location:'.e_BASE.'index.php'); exit(); }

// Include auth.php rather than header.php ensures an admin user is logged in
require_once(e_ADMIN.'auth.php');

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
// set the pageid for the menu as global variable (first pageid is set by admin_config.php)
global $pageid;

$action = basename($_SERVER['PHP_SELF'], ".php");

$var['admin_menu_01']['text'] = EASYSHOP_MENU_01;
$var['admin_menu_01']['link'] = "admin_config.php";

$var['admin_menu_02']['text'] = EASYSHOP_MENU_02;
$var['admin_menu_02']['link'] = "admin_main_categories.php";

$var['admin_menu_03']['text'] = EASYSHOP_MENU_03;
$var['admin_menu_03']['link'] = "admin_categories.php";

$var['admin_menu_04']['text'] = EASYSHOP_MENU_06;
$var['admin_menu_04']['link'] = "admin_properties.php";

$var['admin_menu_05']['text'] = EASYSHOP_MENU_10;
$var['admin_menu_05']['link'] = "admin_discounts.php";

$var['admin_menu_06']['text'] = EASYSHOP_MENU_05;
$var['admin_menu_06']['link'] = "admin_monitor.php";

$var['admin_menu_07']['text'] = EASYSHOP_MENU_04;
$var['admin_menu_07']['link'] = "admin_general_preferences.php";

$var['admin_menu_08']['text'] = EASYSHOP_MENU_07;
$var['admin_menu_08']['link'] = "admin_upload.php";

$var['admin_menu_09']['text'] = EASYSHOP_MENU_11;
$var['admin_menu_09']['link'] = "admin_overview.php";

$var['admin_menu_97']['text'] = EASYSHOP_MENU_12;
$var['admin_menu_97']['link'] = "admin_logviewer.php";

$var['admin_menu_98']['text'] = EASYSHOP_MENU_08;
$var['admin_menu_98']['link'] = "admin_check_update.php";

$var['admin_menu_99']['text'] = EASYSHOP_MENU_09;
$var['admin_menu_99']['link'] = "admin_readme.php";

show_admin_menu(EASYSHOP_MENU_00, $pageid, $var);
?>