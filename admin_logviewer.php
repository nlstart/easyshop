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

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_97';

// Check query
if(e_QUERY){
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$action_id = intval($tmp[1]); // Intval to protect from SQL Injection
  $page_id = intval($tmp[2]); // Used for page id of prod
	unset($tmp);
}

// Set the name of the IPN log file to display
$filename = "ipn.log";

// Clear the IPN log file
if ($action == "clear") {
 if (is_writable($filename)) {
    // Opening $filename in truncating write mode.
    if (!$handle = fopen($filename, 'w+')) {
         $error_text .= EASYSHOP_LOG_01." ($filename)<br />";
    }
    fclose($handle);
  } else {
    $error_text .= EASYSHOP_LOG_02." $filename ".EASYSHOP_LOG_03."<br />";
  }
  if ($error_text <> "") {
    $error_text .= "<br /><center><input class='button' type=button value='".EASYSHOP_LOG_04."' onClick='history.go(-1)'></center>";
   	// Render the value of $error_text in a table.
    $title = EASYSHOP_LOG_05;
    $ns -> tablerender($title, $error_text);
    require_once(e_ADMIN.'footer.php');
    // Leave on error
    exit();
  }
  header("Location: admin_logviewer.php");
}

// Use file_get_contents function to open, read and close the (lowercase) file name (for Unix systems)
$get_text = file_get_contents(strtolower($filename));
// Use public text parse function toHTML to convert the text string to HTML output
if (strlen($get_text)>0){
  $text .= "<div style='text-align: right;'><br /><br /><a href='".e_SELF."?clear'>".EASYSHOP_LOG_06."</a></div>";
}
$text .= $tp->toHTML($get_text, TRUE);
if (strlen($get_text)>0){
  $text .= "<div style='text-align: right;'><br /><br /><a href='".e_SELF."?clear'>".EASYSHOP_LOG_06."</a></div>";
}
// Return the text rendered in a table with a caption
$caption = EASYSHOP_LOG_00;
$ns->tablerender($caption, $text);
require_once(e_ADMIN.'footer.php');
?>