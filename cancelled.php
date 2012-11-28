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

// class2.php is the heart of e107, always include it first to give access to e107 constants and variables
require_once('../../class2.php');

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
// use HEADERF for USER PAGES and e_ADMIN."auth.php" for admin pages
require_once(HEADERF);

require_once('includes/config.php');
require_once(e_HANDLER."mail.php");

$sql = new db;
$sql -> db_Select(DB_TABLE_SHOP_PREFERENCES);
while($row = $sql-> db_Fetch()){
    $store_name = $row['store_name'];
    $store_address_1 = $row['store_address_1'];
    $store_address_2 = $row['store_address_2'];
    $store_city = $row['store_city'];
    $store_state = $row['store_state'];
    $store_zip = $row['store_zip'];
    $store_country = $row['store_country'];
    $support_email = $row['support_email'];
    $store_welcome_message = $row['store_welcome_message'];
    $store_info = $row['store_info'];
    $cancel_page_title = $row['cancel_page_title'];
    $cancel_page_text = $row['cancel_page_text'];
}

// Reset the shopping basket arrays when cancelled page is called
unset($_SESSION['shopping_cart']);
unset($_SESSION['sc_total']);

$text .= "
<br />
<form name='good' method='POST' action='easyshop.php'>
	<center>
		<div style='width:100%'>
				<br />
				<center>
					<table border='0' cellspacing='15' width='100%'>
						<tr>
							<td>
								<center>
									<b>$cancel_page_text</b>
									<br />
									<b><a href='".e_PLUGIN."easyshop/easyshop_basket.php?reset'>".EASYSHOP_CANCEL_01."</a></b>
									<br />
									<b><a href='".SITEURL."'>".EASYSHOP_CANCEL_02."</a></b>
									<br />
								</center>
							</td>
						</tr>
					</table>
				</center>
				<br />
		</div>
	</center>
</form>
<br />
";

// Render the value of $text in a table.
$title = "$cancel_page_title";
$ns -> tablerender($title, $text);

// use FOOTERF for USER PAGES and e_ADMIN.'footer.php' for Admin pages.
require_once(FOOTERF);
?>