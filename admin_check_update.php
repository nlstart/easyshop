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

// easyshop_class is involved to get some info
require_once('easyshop_class.php');

// Include auth.php rather than header.php ensures an admin user is logged in
require_once(e_ADMIN.'auth.php');

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_98';

// Get the EasyShop version files
$easyshop_latest_version = General::getCurrentVersion(); // Retrieve current version from NLSTART
require_once('easyshop_ver.php'); // Contains this local installation EasyShop version number
//$easyshop_current_version = strtolower(trim(file_get_contents(e_PLUGIN.'easyshop/'.'easyshop_ver.php')));
$easyshop_current_version = THIS_VERSION;

$text .= EASYSHOP_CHECK_01.": ".$easyshop_current_version . "<br />".EASYSHOP_CHECK_02 .": ". $easyshop_latest_version . "<br />";
$text .= "<br /><br />";

if ($easyshop_current_version == "" or $easyshop_latest_version == "") {
  $text .= EASYSHOP_CHECK_03;
} else {
  if ($easyshop_current_version < $easyshop_latest_version) {
      $text .= EASYSHOP_CHECK_04; // There is an update
      $text .= "<br />";
      $text .= EASYSHOP_CHECK_05."&nbsp;"."<a href='".General::getEasyShopDownloadDir()."'>http://e107.webstartinternet.com</a>";
  } elseif ( $easyshop_current_version > $easyshop_latest_version ) {
      $text .= EASYSHOP_CHECK_06; // Current version higher than latest version: Impossible!
  } elseif ( $easyshop_current_version == $easyshop_latest_version ) {
      $text .= EASYSHOP_CHECK_07; // Current version is up to date
  }
}

// Render the value of $text in a table.
$ns->tablerender(EASYSHOP_CHECK_00, $text);
require_once(e_ADMIN.'footer.php');
?>