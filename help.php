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
	$helptitle  = EASYSHOP_ADMIN_HELP_00;

	$helpcapt[] = EASYSHOP_ADMIN_HELP_01;
	$helptext[] = EASYSHOP_ADMIN_HELP_02;

	$helpcapt[] = EASYSHOP_ADMIN_HELP_03;
	$helptext[] = EASYSHOP_ADMIN_HELP_04;

	$helpcapt[] = EASYSHOP_ADMIN_HELP_05;
	$helptext[] = EASYSHOP_ADMIN_HELP_06;
	
	$helpcapt[] = EASYSHOP_ADMIN_HELP_07;
	$helptext[] = EASYSHOP_ADMIN_HELP_08;

	$helpcapt[] = EASYSHOP_ADMIN_HELP_97;
	$helptext[] = EASYSHOP_ADMIN_HELP_98;
	
	$helpcapt[] = EASYSHOP_ADMIN_HELP_99A;
	$helptext[] = "<a href='http://shop.webstartinternet.com/e107_plugins/easyshop/easyshop.php?prod.3' alt='' title=''>".EASYSHOP_ADMIN_HELP_99B."</a>";

	$text2 = "";
	for ($i=0; $i<count($helpcapt); $i++) {
		$text2 .= "<b>".$helpcapt[$i]."</b><br />";
	   $text2 .= $helptext[$i]."<br /><br />";
	};

   $ns -> tablerender($helptitle, $text2);
?>