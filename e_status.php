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
if (!defined('e107_INIT')) { exit(); }
// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
require_once('includes/ipn_functions.php');

// Put shop preferences into an array
$shoppref = shop_pref();

// Determine current year
$shop_year = intval(date("Y", time()));

if ($shoppref['enable_ipn'] == 2) { // Only display status when PayPal IPN is enabled
  $count = $sql -> db_Count("easyshop_ipn_orders", "(*)", "WHERE YEAR(FROM_UNIXTIME(phptimestamp))= $shop_year AND payment_status=\"Completed\" ");
  if ($count == "" || $count == NULL){
    $count = 0;
  }
  if ($count > 0){
  $text .= "<div style='padding-bottom: 2px;'><img src='".e_PLUGIN."easyshop/images/logo_16.png' style='width: 16px; height: 16px; vertical-align: bottom' alt='' /> $shop_year ".EASYSHOP_STS_01.": <a href='".e_BASE.e_PLUGIN_ABS."easyshop/admin_monitor.php'>".$count."</a></div>";
  }
}
?>