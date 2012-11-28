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

require_once(e_HANDLER.'comment_class.php'); // Necessary for comments
$cobj = new comment;

// Check query
if(e_QUERY){
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$action_id = intval($tmp[1]); // Intval to protect from SQL Injection
	$page_id = intval($tmp[2]); // Used for page id of prod
	unset($tmp);
}
// Extra check
if (strlen($action) > 0 && !in_array($action, array("edit", "cat", "prodpage", "mcat", "prod", "allcat", "catpage", "blanks", "mcatpage", "datasheet", "quotation")) && $action != "") {
	// Get out of here: incoming action is not an expected one
	header("Location: ".e_BASE); // Redirect to the home page; in next version a specific error message
	//$ns -> tablerender ('Error encountered', 'Sorry, unexpected action '.$action.' specified.'); // require_once(FOOTERF);
	exit();
}
// Another extra check on action id
if (strlen($action_id) > 0 && $action_id < 1 && $action_id != "") {
	header("Location: ".e_BASE); // Redirect to the home page; in next version a specific error message
	//$ns -> tablerender ('Error encountered', 'Sorry, unexpected action id '.$action_id.' specified.'); // require('FOOTERF');
	exit();
}
// Another extra check on page id
if (strlen($page_id) > 0 && $page_id < 1 && $page_id != "") {
	header("Location: ".e_BASE); // Redirect to the home page; in next version a specific error message
	//$ns -> tablerender ('Error encountered', 'Sorry, unexpected page id '.$page_id.' specified.'); // require('FOOTERF');
	exit();
}

if ($action == 'datasheet')
{
	$sql -> db_Select("easyshop_items", "download_datasheet_filename", "item_id=".intval($action_id));
	if ($row = $sql-> db_Fetch())
	{
		header("Location: ".e_PLUGIN."easyshop/datasheets/".$row['download_datasheet_filename']);
		exit();
	}
}

if ($action == 'quotation')
{
	// Reset the current shopping basket contents
	unset($_SESSION['shopping_cart']);
	unset($_SESSION['sc_total']);
	// Proceed quotation like an email_order
	$_POST['email_order'] = 1;
	$item_qty = 1; // Fixed quantity
	$action_id = $_POST['item_id'];
	// Fetch details per product
	$sql -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_id=".intval($action_id));
	if ($row = $sql-> db_Fetch()){
		$item_id = $row['item_id'];
		$category_id = $row['category_id'];
		$item_image = $row['item_image'];
		$item_name = $row['item_name'];
		$item_description = $row['item_description'];
		$item_price = number_format($row['item_price'], 2, '.', '');
		$sku_number = $row['sku_number'];
		$shipping_first_item = $row['shipping_first_item'];
		$shipping_additional_item = $row['shipping_additional_item'];
		$handling_override = $row['handling_override'];
		$item_out_of_stock = $row['item_out_of_stock'];
		$item_out_of_stock_explanation = $row['item_out_of_stock_explanation'];
		$prod_prop_1_id = $row['prod_prop_1_id'];
		$prod_prop_2_id = $row['prod_prop_2_id'];
		$prod_prop_3_id = $row['prod_prop_3_id'];
		$prod_prop_4_id = $row['prod_prop_4_id'];
		$prod_prop_5_id = $row['prod_prop_5_id'];
		$prod_discount_id = $row['prod_discount_id'];
		$item_instock = $row['item_instock'];
		$item_track_stock = $row['item_track_stock'];
		$db_id = $row['item_id'];
		$download_datasheet = $row['download_datasheet']; // v1.7
		$item_quotation = $row['item_quotation']; // v1.7
	}	
    // Fill the basket with selected product
    if (!array_key_exists($item_id, $_SESSION['shopping_cart'])) {
      // Key for item id does not exists; item needs to be added to the array
      $_SESSION['shopping_cart'][$item_id] = array('item_name'=>$tp->toDB($item_name), 'quantity'=>intval($item_qty), 'item_price'=>(double)$item_price, 'sku_number'=>$tp->toDB($sku_number), 'shipping'=>(double)$shipping, 'shipping2'=>(double)$shipping2, 'handling'=>(double)$handling, 'db_id'=> intval($db_id));
      // Handling costs are calculated once per each basket
      $_SESSION['sc_total']['handling'] += (double)$handling;
        // IPN addition - check  to see if we're tracking stock, if so put stock amount into SESSION ARRAY
         if ($item_track_stock == 2){
            $_SESSION['shopping_cart'][$item_id]['item_instock'] = $tp->toDB($item_instock);
            $_SESSION['shopping_cart'][$item_id]['item_track_stock'] = $tp->toDB($item_track_stock);
         }    
    }
    else if (!isset($track_stock) || isset($allow_add)){
      // IPN addition check quantity against item_instock
      // Key for item id does exist; only quantity needs to raised
      $_SESSION['shopping_cart'][$item_id]['quantity'] += intval($item_qty);
    }
	// Fill basket totals
	$_SESSION['sc_total']['items'] = ($_SESSION['sc_total']['items']) + 1;
	$_SESSION['sc_total']['sum']   = ($_SESSION['sc_total']['sum']) + ($_SESSION['shopping_cart'][$action_id]['item_price']);
	// Only additional shipping need to be added (quantity is always higher than 1)
	$_SESSION['sc_total']['shipping2'] += (double)$_SESSION['shopping_cart'][$action_id]['shipping2'];
	$_SESSION['sc_total']['quotation'] = $item_quotation;
}

//-----------------------------------------------------------------------------+
//---------------------- Get and Set Defaults ---------------------------------+
//-----------------------------------------------------------------------------+

// Keep sessions alive when user uses back button of browser
// session_cache_limiter('public');
// Stop caching for all browsers
//session_cache_limiter('nocache');
// Start a session to catch the basket
//session_start();

// global $session_id;
// $session_id = session_id();
require_once('easyshop_class.php');
// Get the shortcodes that are used in the templates
include(e_PLUGIN."easyshop/easyshop_shortcodes.php");
// Determine the main category template
if (file_exists(THEME."easyshop_template.php"))
{
	require_once(THEME."easyshop_template.php");
}
else
{
	require_once(e_PLUGIN."easyshop/templates/easyshop_template.php");
}

// $session_id = Security::get_session_id();

// Debug info
// print_r ($_SESSION['shopping_cart']);
// print_r ("<br />");
// print_r ($_SESSION['sc_total']);
// print_r ("<br />");

// Set the totals to zero if there is no session variable
if(!isset($_SESSION['sc_total'])) {
	$_SESSION['sc_total']['items'] = 0;
	$_SESSION['sc_total']['sum']   = 0;
}

// Retrieve shop preferences just once
$sql = new db;
$sql -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
if ($row = $sql-> db_Fetch()){
	$store_name = $row['store_name'];
	$store_address_1 = $row['store_address_1'];
	$store_address_2 = $row['store_address_2'];
	$store_city = $row['store_city'];
	$store_state = $row['store_state'];
	$store_zip = $row['store_zip'];
	$store_country = $row['store_country'];
	$paypal_email = $row['paypal_email'];
	$paypal_currency_code = $row['paypal_currency_code'];
	$support_email = $row['support_email'];
	$store_image_path = $row['store_image_path'];
	$store_welcome_message = $row['store_welcome_message'];
	$store_info = $row['store_info'];
	$payment_page_style = $row['payment_page_style'];
	$payment_page_image = $row['payment_page_image'];
	$add_to_cart_button = $row['add_to_cart_button'];
	$view_cart_button = $row['view_cart_button'];
	$popup_window_height = $row['popup_window_height'];
	$popup_window_width = $row['popup_window_width'];
	$cart_background_color = $row['cart_background_color'];
	$thank_you_page_title = $row['thank_you_page_title'];
	$thank_you_page_text = $row['thank_you_page_text'];
	$num_category_columns = $row['num_category_columns'];
	$categories_per_page = $row['categories_per_page'];
	$num_item_columns = $row['num_item_columns'];
	$items_per_page = $row['items_per_page'];
	$sandbox = $row['sandbox'];
	$set_currency_behind = $row['set_currency_behind'];
	$minimum_amount = number_format($row['minimum_amount'], 2, '.', '');
	$always_show_checkout = $row['always_show_checkout'];
	$email_order = $row['email_order'];
	$product_sorting = $row['product_sorting'];
	$page_devide_char = $row['page_devide_char'];
	$enable_comments = $row['enable_comments'];
	$show_shopping_bag = $row['show_shopping_bag'];
	$print_shop_address = $row['print_shop_address'];
	$print_shop_top_bottom = $row['print_shop_top_bottom'];
	$print_discount_icons = $row['print_discount_icons'];
	$enable_ipn = $row['enable_ipn']; // IPN addition 
	$enable_number_input = $row['enable_number_input'];
	$print_special_instr = $row['print_special_instr'];
	$email_info_level = $row['email_info_level'];
	$email_additional_text = $row['email_additional_text'];
	$num_main_category_columns = $row['num_main_category_columns'];
	$main_categories_per_page = $row['main_categories_per_page'];
}

// Check admin setting to set currency behind amount
// 0 = currency before amount (default), 1 = currency behind amount
if ($set_currency_behind == '') {($set_currency_behind = 0);}

// Check admin setting to set minimum amount
// Checkout button is only shown if total amount is above this minimum
if ($minimum_amount == '') {($minimum_amount = 0);}

// Check admin setting to display checkout button always
// 0 = no, only show when at least 1 product is ordered, 1 = yes, always show checkout button
if ($always_show_checkout == '') {($always_show_checkout = 0);}

// Check admin setting to display page devide character
if ($page_devide_char == '') {($page_devide_char = "&raquo;");}

// Check admin setting to e-mail order to admin
// E-mail to admin overrules the checkout to PayPal!
// 0 = no e-mail to admin, 1 = e-mail order to admin
if ($email_order == '') {($email_order = 0);} // Introduced in 1.2 RC6, functioning since 1.3!

// Format the shop welcome message once
$store_welcome_message = $tp->toHTML($store_welcome_message, true);

// Define actual currency and position of currency character once
$sql -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "currency_active=2");
if ($row = $sql-> db_Fetch()){
	$unicode_character = $row['unicode_character'];
	$paypal_currency_code = $row['paypal_currency_code'];
}

// Determine currency before or after amount
if ($set_currency_behind == 1) {
	// Print currency after amount
	$unicode_character_before = "";
	$unicode_character_after = "&nbsp;".$unicode_character;
}
else {
	$unicode_character_before = "&nbsp;".$unicode_character."&nbsp;";
	$unicode_character_after = "";
	// Print currency before amount in all other cases
}

// Set values for variables $existing_tems and active_items
if ($action == "cat" || $action == "prodpage")
{
	if ($sql -> db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE category_id=".$action_id) > 0) {
		$existing_items = 1;
	}
	if ($sql -> db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE category_id=".$action_id." AND item_active_status=2") > 0) {
		$active_items = 1;
	}
}

// Set presentation defaults
if ($num_item_columns			== '') {($num_item_columns				=  3);}
if ($items_per_page				== '') {($items_per_page				= 25);}
if ($num_category_columns		== '') {($num_category_columns			=  3);}
if ($categories_per_page		== '') {($categories_per_page			= 25);}
if ($num_main_category_columns	== '') {($num_main_category_columns		=  3);}
if ($main_categories_per_page	== '') {($main_categories_per_page		= 25);}

// Determine the variable $column_width
$column_width = Shop::switch_columns($num_item_columns);

//-----------------------------------------------------------------------------+
//--------------- Get visitors name and e-mail address ------------------------+
//-----------------------------------------------------------------------------+
// Check incoming e-mail address
if ($_POST['email_order'] == 1 && isset($_POST['to_email'])) {
	// Check the provided e-mail address
	if(check_email($_POST['to_email'])){
		// E-mail is valid
		$_SESSION['sc_total']['to_email'] = $_POST['to_email'];
	} else {
		// Not a valid e-mail address
		unset($_SESSION['sc_total']['to_email']);
	}
}
// Check incoming name (must be larger than 3 characters)
if ($_POST['email_order'] == 1 && isset($_POST['to_name'])) {
	// Check the provided name
	if(strlen($_POST['to_name']) > 3){
		// Name is valid
		$_SESSION['sc_total']['to_name'] = $_POST['to_name'];
	} else {
		// Not a valid name
		unset($_SESSION['sc_total']['to_name']);
	}
}
if ($_POST['email_order'] == 1 && ($email_info_level == 1 || $email_info_level == 2)) {
	if(trim($_POST['to_address1'])!="") { $_SESSION['sc_total']['to_address1'] = $_POST['to_address1'];} else {unset($_SESSION['sc_total']['to_address1']);}
	if(trim($_POST['to_address2'])!="") { $_SESSION['sc_total']['to_address2'] = $_POST['to_address2'];} else {unset($_SESSION['sc_total']['to_address2']);}
	if(trim($_POST['to_zipcode'])!="")  { $_SESSION['sc_total']['to_zipcode']  = $_POST['to_zipcode'];}  else {unset($_SESSION['sc_total']['to_zipcode']);}
	if(trim($_POST['to_city'])!="")     { $_SESSION['sc_total']['to_city']     = $_POST['to_city'];}     else {unset($_SESSION['sc_total']['to_city']);}
	if(trim($_POST['to_telephone'])!=""){ $_SESSION['sc_total']['to_telephone']= $_POST['to_telephone'];}else {unset($_SESSION['sc_total']['to_telephone']);}
	if(trim($_POST['to_mobile'])!="")   { $_SESSION['sc_total']['to_mobile']   = $_POST['to_mobile'];}   else {unset($_SESSION['sc_total']['to_mobile']);}
}
// Determine if form to get visitors name and e-mail must be shown
if ( ($_POST['email_order'] == 1 && !USER && (!isset($_SESSION['sc_total']['to_email']) || !isset($_SESSION['sc_total']['to_name'])))
    || ($_POST['email_order'] == 1 && ($email_info_level == 1 || $email_info_level == 2) && ($_SESSION['sc_total']['to_address1'] == ""
    || $_SESSION['sc_total']['to_telephone']=="" || $_SESSION['sc_total']['to_email']=="" || $_SESSION['sc_total']['to_name']=="" || $_SESSION['sc_total']['to_zipcode']=="" || $_SESSION['sc_total']['to_city']=="") ) ) {
	// Perform an extra security check
	//if ($session_id != session_id()) { // Get out of here: incoming session id is not equal than current session id
	//  header("Location: ".e_BASE); // Redirect to the home page
	//  exit();
	//}
	// User has clicked on checkout but is not logged in and has not provided a name and e-mail yet
	$get_address_text .= "
 	<div style='text-align:center;'>
		<div style='width:100%'>
				<center>
					<table border='0' cellspacing='15' width='100%'>
						<tr>
							<td>";

	if ($email_info_level <> 1) {
		// Show you're currently not logged in not when leave e-mail and address is appropriate
		$get_address_text .= "
    								<div style='text-align:center;'>".EASYSHOP_SHOP_65."</div>
  								<br />";
	}
  
	$get_address_text .=
								(($action=='quotation')?EASYSHOP_SHOP_96:EASYSHOP_SHOP_66)."<br />
                <br />
                <br />";

	// Do something with email_info_level
	//  '0' = Login or leave e-mail
	//	'1' = Leave e-mail and address
	//	'2' = Login or Leave e-mail and address
	//  '3' = Login required
	if ($email_info_level <> 1) {
		// Do not show login or signup when leave e-mail and address is appropriate
		$get_address_text .= "
                  ".EASYSHOP_SHOP_67."<br />
                  <br />";
		if ($email_info_level != 3) {
			$get_address_text .= EASYSHOP_SHOP_68."<br />";
		}

		$get_address_text .= "
                  <br />
                  <ul>
                    <li>".EASYSHOP_SHOP_69." <a href='".e_BASE."login.php'>".EASYSHOP_SHOP_70."</a></li><br />
    								<li>".EASYSHOP_SHOP_71." <a href='".e_BASE."signup.php'>".EASYSHOP_SHOP_72."</a></li><br />
  								</ul>
  								<br />";
	}
  
	if ($email_info_level == 1) {
		$get_address_text .= EASYSHOP_SHOP_85."<br />";
	} elseif ($email_info_level != 3)  {
		$get_address_text .= EASYSHOP_SHOP_73."<br />";
	}
  
	if ($email_info_level != 3) {
	$get_address_text .= "
                <div>
  								<form method='post' action='".e_SELF."'>
  								<fieldset>
                    <table>
                    <tr>
                      <td valign='top'>".EASYSHOP_SHOP_74.":</td>
                      <td valign='top'><input class='tbox' size='25' type='text' name='to_name' value='".$_SESSION['sc_total']['to_name']."' />*<br />".EASYSHOP_SHOP_75."</td>
                    </tr>
                    <tr>
                      <td>".EASYSHOP_SHOP_76.":</td>
                      <td><input class='tbox' size='25' type='text' name='to_email' value='".$_SESSION['sc_total']['to_email']."' />*</td>
                    </tr>
                    ";
                      

		if ($email_info_level == 1 || $email_info_level == 2) {
			$get_address_text .= "
                    <tr><td>".EASYSHOP_SHOP_86.":</td>
                    <td><input class='tbox' size='25' type='text' name='to_address1' value='".$_SESSION['sc_total']['to_address1']."' />*</td>
                    </tr>
                    <tr><td>".EASYSHOP_SHOP_87.":</td>
                    <td><input class='tbox' size='25' type='text' name='to_address2' value='".$_SESSION['sc_total']['to_address2']."' /></td>
                    </tr>
                    <tr><td>".EASYSHOP_SHOP_88.":</td>
                    <td><input class='tbox' size='25' type='text' name='to_zipcode' value='".$_SESSION['sc_total']['to_zipcode']."' />*</td>
                    </tr>
                    <tr><td>".EASYSHOP_SHOP_89.":</td>
                    <td><input class='tbox' size='25' type='text' name='to_city' value='".$_SESSION['sc_total']['to_city']."' />*</td>
                    </tr>
                    <tr><td>".EASYSHOP_SHOP_90.":</td>
                    <td><input class='tbox' size='25' type='text' name='to_telephone' value='".$_SESSION['sc_total']['to_telephone']."' />*</td>
                    </tr>
                    <tr><td>".EASYSHOP_SHOP_91.":</td>
                    <td><input class='tbox' size='25' type='text' name='to_mobile' value='".$_SESSION['sc_total']['to_mobile']."' /></td>
                    </tr>
                    <tr><td colspan='2'>".EASYSHOP_SHOP_92."</td></tr>
                        ";
		}

		$get_address_text .= "
                    </table>
    								<input type='hidden' name='email_order' value='1'/>
                    <div style='text-align:center;'><input class='button' name='submit' type='submit' value='".EASYSHOP_SHOP_77."'/></div>
                  </fieldset>
                  </form>
                </div>";
  }
  
  $get_address_text .= "
                <br />
              </td>
            </tr>
          </table>
        </center>
    </div>
  </div>";
	// Render the value of $get_address_text in a table.
	$title = EASYSHOP_SHOP_78;
	$ns -> tablerender($title, $get_address_text);
	require_once(FOOTERF);
	exit();
}

//-----------------------------------------------------------------------------+
//----------------------- E-mail the order  -----------------------------------+
//-----------------------------------------------------------------------------+
if ($_POST['email_order'] == 1 && (USER || (isset($_SESSION['sc_total']['to_name']) && isset($_SESSION['sc_total']['to_email']) ))) {
	// Perform an extra security check
	//if ($session_id != session_id()) { // Get out of here: incoming session id is not equal than current session id
	//  header("Location: ".e_BASE); // Redirect to the home page
	//  exit();
	//}
	// Receive the setting email_order=1 from the checkout form (or the get visitors name form)
	// User has clicked on checkout and is logged in or has provided a name and e-mail
	$sender_name  = ((isset($pref['replyto_name']))?$pref['replyto_name']:$pref['siteadmin']);        // Keep 0.7.8 compatible
	$sender_email = ((isset($pref['replyto_email']))?$pref['replyto_email']:$pref['siteadminemail']); // Keep 0.7.8 compatible
	if (USER) {
		$sql = new db;
		$arg="SELECT *
			 FROM #user
			 WHERE user_id = ".intval(USERID); // Security fix
		$sql->db_Select_gen($arg,false);
		if($row = $sql-> db_Fetch()){
			$to_id     = $row['user_id'];
			$to_name   = $row['user_name'];
			$to_email  = $row['user_email'];
		}
	} else {
		$to_name   = $_SESSION['sc_total']['to_name'];  // This value is checked
		$to_email  = $_SESSION['sc_total']['to_email']; // This value is checked
		if ($email_info_level == 1 || $email_info_level == 2) {
			$to_address1 = $_SESSION['sc_total']['to_address1'];
			$to_address2 = $_SESSION['sc_total']['to_address2'];
			$to_zipcode  = $_SESSION['sc_total']['to_zipcode'];
			$to_city     = $_SESSION['sc_total']['to_city'];
			$to_telephone= $_SESSION['sc_total']['to_telephone'];
			$to_mobile   = $_SESSION['sc_total']['to_mobile'];
		}
	}
	$pref_sitename = $pref['sitename'];
	$special_instr_text = $_POST['special_instr_text'];
	$temp_message = MailOrder($unicode_character_before, $unicode_character_after, $pref_sitename, $sender_name, $sender_email, $to_name, $to_email, $print_special_instr, $special_instr_text, $to_id, $email_info_level, $to_address1, $to_address2, $to_zipcode, $to_city, $to_telephone, $to_mobile, $email_additional_text);
	// function returns an array; [0] is the message and [1] is $mail_result at success set to 1
	$mail_message = $temp_message[0];
	$mail_result  = $temp_message[1];
	unset($temp_message);
	if ($mail_result == 1) { // Succesfull e-mail has been send
		// Manipulate location to thank you page (where shop basket will be emptied)
		$target=('thank_you.php');
		header("Location: ".$target);
		exit();
	}
	$mail_text .= "
 	<div style='text-align:center;'>
		<div style='width:100%'>
				<center>
					<table border='0' cellspacing='15' width='100%'>
						<tr>
							<td>
								<center>".$mail_message."</center>
								<br />".$mail_header."
							</td>
						</tr>
					</table>
				</center>
		</div>
	</div>";

	// Render the value of $mail_text in a table.
	$title = EASYSHOP_SHOP_61;
	$ns -> tablerender($title, $mail_text);
}

//-----------------------------------------------------------------------------+
//---------------------- Edit Shopping Basket ---------------------------------+
//-----------------------------------------------------------------------------+
// Show Shopping Cart if easyshop.php?edit is called
if ($action == 'edit') {
	// Perform an extra security check
	//if ($session_id != session_id()) { // Get out of here: incoming session id is not equal than current session id
	//  header("Location: ".e_BASE); // Redirect to the home page
	//  exit();
	//}
	$count_items = count($_SESSION['shopping_cart']);     // Count number of different products in basket
	$sum_quantity = $_SESSION['sc_total']['items'];       // Display cached sum of total quantity of items in basket
	$sum_shipping = $_SESSION['sc_total']['shipping'];    // Display cached sum of shipping costs for 1st item
	$sum_shipping2 = $_SESSION['sc_total']['shipping2'];  // Display cached sum of shipping costs for additional items (>1)
	$sum_handling = $_SESSION['sc_total']['handling'];    // Display cached sum of handling costs
	$sum_shipping_handling = number_format(($sum_shipping + $sum_shipping2 + $sum_handling), 2, '.', ''); // Calculate total handling and shipping price
	$sum_price = number_format(($_SESSION['sc_total']['sum'] + $sum_shipping_handling), 2, '.', ''); // Display cached sum of total price of items in basket + shipping + handling costs
	$average_price = number_format(($sum_price / $sum_quantity), 2, '.', ''); // Calculate the average price per product

	// When total quantity is zero hide the basket
	if ($sum_quantity == 0) {
		// Manipulate return target location back to edit basket mode
		$target=('easyshop.php');
		header("Location: ".$target);
		exit();
	}
	$text2 = "";
	$text2 .= "
	<div>
		<br />".EASYSHOP_PUBLICMENU_02."
	</div>";

	// Fill the Cart with products from the basket
	$count_items = count($_SESSION['shopping_cart']); // Count number of different products in basket
	$array = $_SESSION['shopping_cart'];
	// Show products in a sequence starting at 1
	$cart_count = 1;
	// Set the header
	$text2 .= "
	<div style='text-align:center;'>
		<table border='0' cellspacing='1'>
			<tr>
				<td class='tbox'>".EASYSHOP_SHOP_21."</td>
				<td class='tbox'>".EASYSHOP_SHOP_22."</td>
				<td class='tbox'>".EASYSHOP_SHOP_23."</td>
				<td class='tbox'>".EASYSHOP_SHOP_24."</td>
				<td class='tbox'>".EASYSHOP_SHOP_25."</td>
				<td class='tbox'>".EASYSHOP_SHOP_26."</td>
				<td class='tbox'>".EASYSHOP_SHOP_27."</td>
				<td class='tbox'>".EASYSHOP_SHOP_28."</td>
			</tr>";

	// For each product in the shopping cart array write PayPal details
    foreach($array as $id => $item) {
		// Debug info
		// echo "{$id}, {$item['item_name']}, {$item['quantity']}, {$item['item_price']}, {$item['sku_number']}, {$item['shipping']}, {$item['shipping2']}, {$item['handling']}";
		$display_sku_number = $item['sku_number'];
		if ($item['sku_number'] == "") {
			$display_sku_number = "&nbsp;"; // Force a space in the cell for proper border display
		}
		$text2 .= "
				<tr>
					<td class='tbox'>".$display_sku_number."</td>
					<td class='tbox'>".$tp->toHTML($item['item_name'], true)."</td>
					<td class='tbox'>".$unicode_character_before.number_format($item['item_price'], 2, '.', '').$unicode_character_after."</td>
					<td class='tbox'>".$item['quantity']."</td>
					<td class='tbox'>".$unicode_character_before.number_format($item['shipping'], 2, '.', '').$unicode_character_after."</td>
					<td class='tbox'>".$unicode_character_before.number_format($item['shipping2'], 2, '.', '').$unicode_character_after."</td>
					<td class='tbox'>".$unicode_character_before.number_format($item['handling'], 2, '.', '').$unicode_character_after."</td>
					<td class='tbox'>
						<a href='easyshop_basket.php?delete.".$id."'><img src='".e_IMAGE."admin_images/delete_16.png' style='border-style:none;' alt='".EASYSHOP_SHOP_29."' title='".EASYSHOP_SHOP_29."'/></a>&nbsp;";
		
		// IPN addition - If Quantity is still less than available stock show add option
		if ((!isset($item['item_track_stock'])) || ($item['quantity'] < $item['item_instock'])) {
			$text2 .= "
						<a href='easyshop_basket.php?add.".$id."'><img src='".e_IMAGE."admin_images/up.png' border='noborder' alt='".EASYSHOP_SHOP_33."' title='".EASYSHOP_SHOP_33."'/></a>&nbsp;";
		} 

		// If quantity equals 1 don't show minus option
		if ($item['quantity'] > 1) {
			$text2 .= "
						<a href='easyshop_basket.php?minus.".$id."'><img src='".e_IMAGE."admin_images/down.png' style='border-style:none;' alt='".EASYSHOP_SHOP_34."' title='".EASYSHOP_SHOP_34."'/></a>";
		}

		$text2 .= "
					</td>
				</tr>";
		$cart_count++;
	}

	$text2 .= "
		</table>
		<br />".EASYSHOP_SHOP_16." ".$sum_quantity."
		<br />".EASYSHOP_SHOP_17." ".$count_items."
		<br />".EASYSHOP_SHOP_18." ".$unicode_character_before.$sum_price.$unicode_character_after."
		<br />".EASYSHOP_SHOP_19." ".$unicode_character_before.$average_price.$unicode_character_after;
	if ($sum_shipping_handling > 0) {
		$text2 .= "
		<br />".EASYSHOP_SHOP_20." ".$unicode_character_before.$sum_shipping_handling.$unicode_character_after;
	}

	// Reset and continue shopping possibility
	$text2 .= "
		<div style='text-align:center;'>
			<a href=easyshop_basket.php?reset>".EASYSHOP_SHOP_30."</a> |
			<a href='javascript:history.go(-1);'>".EASYSHOP_SHOP_31."</a><br />";

	// Retrieve from the post value of the instructions text to pass to checkout form
	$special_instr_text = $_POST['special_instr_text'];
	$text2 .= Shop::show_checkout($session_id, $special_instr_text);

	$text2 .= "
		</div>
	</div>";

	// Render the value of $text in a table.
	$title = EASYSHOP_SHOP_32;
	$ns -> tablerender($title, $text2);
}

//-----------------------------------------------------------------------------+
//---------------------- Display a Category -----------------------------------+
//-----------------------------------------------------------------------------+
if ($action == "cat" || $action == "prodpage") {
	if ($sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_id=".$action_id." AND (category_class IN (".USERCLASS_LIST.")) ")){
		if($row = $sql-> db_Fetch()){
			$category_name = $row['category_name'];
			$category_main_id  = $row['category_main_id'];
			$category_order_class = $row['category_order_class'];
		}
	} else {
		// No access to this category
		define("e_PAGETITLE", PAGE_NAME);
		require_once(HEADERF);
		$ns->tablerender(EASYSHOP_SHOP_48,"<div style='text-align:center'>".EASYSHOP_SHOP_49."</div>");
		require_once(FOOTERF);
		exit();
	}

	if ($category_main_id <> "") {
		$sql -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "main_category_id=".$category_main_id);
		while($row = $sql-> db_Fetch()){
			$main_category_name = $row['main_category_name'];
		}
	}
	// Determine the offset to display
	$item_offset = General::determine_offset($action,$page_id,$items_per_page);

	// Print the shop at the 'top' if the setting is not set to 'bottom' (value 1)
	if ($print_shop_top_bottom != '1') {
		$es_store_header = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_header', $es_store_header);	
	}
  
	if (isset($main_category_name)) {
		$easyshop_cat_mcat_link = array($category_main_id,$main_category_name);
		cachevars('easyshop_cat_mcat_link', $easyshop_cat_mcat_link);
	}

	cachevars('easyshop_cat_catname', $category_name);
	if ($existing_items == null) {
		cachevars('easyshop_cat_no_products', EASYSHOP_SHOP_06);
	} else {
		// Total of active product items
		$sql3 = new db;
		$total_items = $sql3 -> db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status=2 AND category_id=".$action_id);

		$count_rows = 0;
		$sql -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_active_status=2 AND category_id=".$action_id." ORDER BY item_order LIMIT $item_offset, $items_per_page");
		while($row = $sql-> db_Fetch()){
			$item_id = $row['item_id'];
			$category_id = $row['category_id'];
			$item_image = $row['item_image'];
			$item_name = $row['item_name'];
			$item_description = $row['item_description'];
			$item_price = number_format($row['item_price'], 2, '.', '');
			$sku_number = $row['sku_number'];
			$shipping_first_item = $row['shipping_first_item'];
			$shipping_additional_item = $row['shipping_additional_item'];
			$handling_override = $row['handling_override'];
			$item_out_of_stock = $row['item_out_of_stock'];
			$item_out_of_stock_explanation = $row['item_out_of_stock_explanation'];
			$prod_prop_1_id = $row['prod_prop_1_id'];
			$prod_prop_2_id = $row['prod_prop_2_id'];
			$prod_prop_3_id = $row['prod_prop_3_id'];
			$prod_prop_4_id = $row['prod_prop_4_id'];
			$prod_prop_5_id = $row['prod_prop_5_id'];
			$prod_discount_id = $row['prod_discount_id'];
			$item_quotation = $row['item_quotation'];
			$db_id = $row['item_id'];

			for ($n = 1; $n < 6; $n++){
				// Clear properties (for next products in same category)
				${"prop".$n."_name"} = "";
				${"prop".$n."_list"} = "";
				${"prop".$n."_prices"} = "";
				${"prop".$n."_array"} = "";
				${"price".$n."_array"} = "";
				$sql2 = new db;
				$sql2 -> db_Select(DB_TABLE_SHOP_PROPERTIES, "*", "property_id=".${"prod_prop_".$n."_id"});
				while($row2 = $sql2-> db_Fetch()){
					if ($row2['prop_display_name'] <> "" or $row2['prop_display_name'] <> 0){
                  	    ${"prop".$n."_name"} = $row2['prop_display_name'];
                  	    ${"prop".$n."_list"} = $row2['prop_list'];
                  	    ${"prop".$n."_prices"} = $row2['prop_prices'];
					}
				}
			}

			if ($prod_discount_id <> "") {
				$sql3 = new db;
				$sql3 -> db_Select(DB_TABLE_SHOP_DISCOUNT, "*", "discount_id=".$prod_discount_id);
				if ($row3 = $sql3-> db_Fetch()){
					$discount_id = $row3['discount_id'];
					$discount_name = $row3['discount_name'];
					$discount_class = $row3['discount_class'];
					$discount_flag = $row3['discount_flag'];
					$discount_price = $row3['discount_price'];
					$discount_percentage = $row3['discount_percentage'];
					$discount_valid_from = $row3['discount_valid_from'];
					$discount_valid_till = $row3['discount_valid_till'];
					$discount_code = $row3['discount_code'];
				}
			}
			if ($discount_valid_till == 0) {
				$discount_valid_till = 9999999999; // Set end date far away
			}

			if ($item_image == '') {
			} else {
				$item_image = explode(",",$item_image);
				$arrayLength = count($item_image);
			  // Only show the first image in the category
			}
			$easyshop_cat_prod_image = array($item_image,$item_id,$store_image_path);
			cachevars('easyshop_cat_prod_image', $easyshop_cat_prod_image);
			// Display text 'view more images' if there are multiple images
			$easyshop_cat_prod_image_more = array($arrayLength,$item_id);
			cachevars('easyshop_cat_prod_image_more', $easyshop_cat_prod_image_more);

			$easyshop_cat_prod_link = array($item_id,$item_name);
			cachevars('easyshop_cat_prod_link', $easyshop_cat_prod_link);

			$easyshop_cat_prod_price = array($unicode_character_before,$item_price,$unicode_character_after,$item_quotation);
			cachevars('easyshop_cat_prod_price', $easyshop_cat_prod_price);
												
			$easyshop_cat_prod_details_link = array($item_id, EASYSHOP_SHOP_11);
			cachevars('easyshop_cat_prod_details_link', $easyshop_cat_prod_details_link);
			
			cachevars('easyshop_cat_prod_quotation', ''); // v1.7
			if ($item_quotation == '2') { // v1.7
				$easyshop_cat_prod_quotation = array($item_quotation,$item_id);
				cachevars('easyshop_cat_prod_quotation', $easyshop_cat_prod_quotation);
				cachevars('easyshop_cat_add_to_cart', ""); // Clear the easyshop_cat_add_to_cart variable! 
			}
			elseif ($item_out_of_stock == 2) {
				$easyshop_cat_out_of_stock = array($item_out_of_stock, $item_out_of_stock_explanation);
				cachevars('easyshop_cat_out_of_stock', $easyshop_cat_out_of_stock);
				cachevars('easyshop_cat_add_to_cart', ""); // Clear the easyshop_cat_add_to_cart variable!
			} else {
				// Add to Cart at Category page
				cachevars('easyshop_cat_out_of_stock', ""); // Clear the easyshop_cat_out_of_stock variable!
				$fill_basket = "C"; // To indicate that add to cart is started from Categories page
				$easyshop_cat_add_to_cart = Forms::add_to_cart_form($prop1_list, $prop1_array, $prop1_prices,$prop1_name,
								  $prop2_list, $prop2_array, $prop2_prices,$prop2_name,
								  $prop3_list, $prop3_array, $prop3_prices,$prop3_name,
								  $prop4_list, $prop4_array, $prop4_prices,$prop4_name,
								  $prop5_list, $prop5_array, $prop5_prices,$prop5_name,
								  $prop6_list, $prop6_array, $prop6_prices,$prop6_name,
								  $unicode_character_before, $unicode_character_after, $item_price,
								  $discount_id, $discount_class, $discount_valid_from, $discount_valid_till,
								  $discount_code, $discount_flag, $discount_percentage, $discount_price,
								  $property_prices, $unicode_character_before, $unicode_character_after, $print_discount_icons,
								  $item_id, $item_name, $sku_number, $shipping_first_item, $shipping_additional_item, $handling_override,
								  $category_id, $item_instock, $item_track_stock, $enable_ipn, $db_id,
								  $category_order_class, $enable_number_input, $fill_basket);
				cachevars('easyshop_cat_add_to_cart', $easyshop_cat_add_to_cart);
			}
			if (ADMIN && getperms("P")) { // Show admin icon when administrator
				$easyshop_admin_icon = array($item_id,$category_id);
				cachevars('easyshop_admin_icon', $easyshop_admin_icon);
			}

			cachevars('easyshop_cat_table_td_end', "&nbsp;");
			$count_rows++;

			if ($count_rows == $num_item_columns) {
				cachevars('easyshop_cat_conditionalbreak', "&nbsp;");
				$count_rows = 0;
			}
			else {
				cachevars('easyshop_cat_conditionalbreak', ""); // Clear the easyshop_cat_conditionalbreak variable!
			}
			// To avoid confusion for the next to be fetched product; unset most important variables
			$easyshop_cat_container .= $tp->parseTemplate($ES_CAT_CONTAINER, FALSE, $easyshop_shortcodes);
			unset($item_id, $category_id, $item_image, $item_name, $item_description, $item_price, $sku_number,
				  $shipping_first_item, $shipping_additional_item, $handling_override, $item_out_of_stock, $item_out_of_stock_explanation,
				  $prod_prop_1_id, $prod_prop_2_id, $prod_prop_3_id, $prod_prop_4_id, $prod_prop_5_id,
				  $prod_discount_id, $discount_id, $arrayLength);
			unset($easyshop_cat_prod_image_more, $easyshop_cat_addcart, $easyshop_cat_add_to_cart, $easyshop_cat_prod_quotation);
		} // End of while fetch
		cachevars('easyshop_cat_container', $easyshop_cat_container);

		if ($active_items == null) {
			cachevars('easyshop_cat_no_products', EASYSHOP_SHOP_06);
		} else {
			$easyshop_cat_show_checkout = Shop::show_checkout($session_id); // Code optimisation: make use of function show_checkout
			cachevars('easyshop_cat_show_checkout', $easyshop_cat_show_checkout);
		} // End of Else for show Categorie with active products

		$easyshop_paging = General::multiple_paging($total_items,$items_per_page,$action,$action_id,$page_id,$page_devide_char);
		cachevars('easyshop_paging', $easyshop_paging);						
	}
	// Print the shop at the 'bottom' if the setting is set to 'bottom' (value 1)
	if ($print_shop_top_bottom == '1') {
		$es_store_footer = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_footer', $es_store_footer);	
	}
	$text = $tp->parseTemplate($ES_CAT_TEMPLATE, FALSE, $easyshop_shortcodes);
	// Render the value of $text in a table.
	$title = EASYSHOP_SHOP_00;
	$ns -> tablerender($title, $text);
}

//-----------------------------------------------------------------------------+
//-------------------- Display a MAIN Category --------------------------------+
//-----------------------------------------------------------------------------+
  if ($action == "mcat" ) {
	// Count the number of categories with the given mcat id
	$total_categories = $sql->db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status=2 AND category_main_id=".$action_id." AND (category_class IN (".USERCLASS_LIST.")) ");

	if ($total_categories > 0) 
	{
		$sql -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "main_category_id=".$action_id);
		while($row = $sql-> db_Fetch()){
			$main_category_id = $row['main_category_id'];
			$main_category_name = $row['main_category_name'];
			$main_category_description = $row['main_category_description'];
			$main_category_image = $row['main_category_image'];
			$main_category_active_status = $row['main_category_active_status'];
		}
	}
	// Determine the offset to display
	$item_offset = General::determine_offset($action,$page_id,$main_categories_per_page);

	// Print the shop at the 'top' if the setting is not set to 'bottom' (value 1)
	if ($print_shop_top_bottom != '1') {
		$es_store_header = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_header', $es_store_header);	
	}

	if (isset($main_category_id)) {
		$es_mcat_link = array($_GET['url'],$main_category_id,$main_category_name);
		cachevars('easyshop_mcat_link', $es_mcat_link);	
	}
	if (!isset($main_category_id) && ($total_categories > 0)) {
		cachevars('easyshop_mcat_notfound', EASYSHOP_SHOP_42);
	} else {
		$count_rows = 0;
		$sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_active_status=2 AND category_main_id=".$action_id." AND (category_class IN (".USERCLASS_LIST.")) ORDER BY category_order LIMIT $item_offset, $main_categories_per_page");
		while($row = $sql-> db_Fetch()){
			if ($row['category_image'] == '') {
				$easyshop_cat_image = "&nbsp;";
			} else {
				$easyshop_cat_image = array(e_SELF,$row['category_id'],$store_image_path,$row['category_image']);
			}		
			cachevars('easyshop_cat_image', $easyshop_cat_image);

			$easyshop_cat_name = array(e_SELF,$row['category_id'],$row['category_name']);
			cachevars('easyshop_cat_name', $easyshop_cat_name);	

			$easyshop_cat_descr = $tp->toHTML($row['category_description'], true);
			cachevars('easyshop_cat_descr', $easyshop_cat_descr);										

			// Count the total of products per category
			$sql2 = new db;
			$total_products_category = $sql2->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status = '2' AND category_id=".$row['category_id']);
			cachevars('easyshop_total_prods_in_cat', $total_products_category);										
		
			$count_rows++;
			if ($count_rows == $num_category_columns) {
				$count_rows = 0;
				cachevars('easyshop_row_break', "&nbsp;");
			}
			else {
				cachevars('easyshop_row_break', ""); // Clear the easyshop_row_break variable!
			}
			
			$easyshop_mcat_container .= $tp->parseTemplate($ES_MCAT_CONTAINER, FALSE, $easyshop_shortcodes);
		}
		cachevars('easyshop_mcat_container', $easyshop_mcat_container);
		if ($total_categories == null || $total_categories == 0) {
			$easyshop_zero_cat = EASYSHOP_SHOP_04;
			cachevars('easyshop_zero_cat', $easyshop_zero_cat);	
		} else {
			$easyshop_show_checkout = Shop::show_checkout($session_id); // Code optimisation: make use of function show_checkout
			cachevars('easyshop_show_checkout', $easyshop_show_checkout);
		} // End of Else for show Categorie with active products
		$easyshop_paging = General::multiple_paging($total_categories,$main_categories_per_page,$action,$action_id,$page_id,$page_devide_char);
		cachevars('easyshop_paging', $easyshop_paging);
	}
	// Print the shop at the 'bottom' if the setting is set to 'bottom' (value 1)
	if ($print_shop_top_bottom == '1') {
		$es_store_footer = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_footer', $es_store_footer);	
	}
	// Parse the template
	$text .= $tp->parseTemplate($ES_MCAT_TEMPLATE, FALSE, $easyshop_shortcodes);
	// Render the value of $text in a table.
	$title = EASYSHOP_SHOP_00;
	$ns -> tablerender($title, $text);
}

//-----------------------------------------------------------------------------+
//----------------------- Display a Product -----------------------------------+
//-----------------------------------------------------------------------------+
if ($action == "prod") {
	if($sql -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status=2  AND (category_class IN (".USERCLASS_LIST.")) ") > 0) {
		$no_categories = 1;
	}
	// Fetch details per product
	$sql -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_id=".$action_id);
	if ($row = $sql-> db_Fetch()){
		$item_id = $row['item_id'];
		$category_id = $row['category_id'];
		$item_image = $row['item_image'];
		$item_name = $row['item_name'];
		$item_description = $row['item_description'];
		$item_price = number_format($row['item_price'], 2, '.', '');
		$sku_number = $row['sku_number'];
		$shipping_first_item = $row['shipping_first_item'];
		$shipping_additional_item = $row['shipping_additional_item'];
		$handling_override = $row['handling_override'];
		$item_out_of_stock = $row['item_out_of_stock'];
		$item_out_of_stock_explanation = $row['item_out_of_stock_explanation'];
		$prod_prop_1_id = $row['prod_prop_1_id'];
		$prod_prop_2_id = $row['prod_prop_2_id'];
		$prod_prop_3_id = $row['prod_prop_3_id'];
		$prod_prop_4_id = $row['prod_prop_4_id'];
		$prod_prop_5_id = $row['prod_prop_5_id'];
		$prod_discount_id = $row['prod_discount_id'];
		// IPN addition adding item_instock, track stock and database ID to checkout data
		$item_instock = $row['item_instock'];
		$item_track_stock = $row['item_track_stock'];
		$db_id = $row['item_id'];
		$download_datasheet = $row['download_datasheet']; // v1.7
		$item_quotation = $row['item_quotation']; // v1.7
	}

	if ($sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_id=".$category_id." AND (category_class IN (".USERCLASS_LIST.")) ")){
		if ($row = $sql-> db_Fetch()){
			$category_name = $row['category_name'];
			$category_main_id  = $row['category_main_id'];
			$category_order_class = $row['category_order_class'];
		}
	} else {
		// No access to this category
		define("e_PAGETITLE", PAGE_NAME);
		require_once(HEADERF);
		$ns->tablerender(EASYSHOP_SHOP_48,"<div style='text-align:center'>".EASYSHOP_SHOP_49."</div>");
		require_once(FOOTERF);
		exit();
	}

	if ($category_main_id <> "") {
		$sql -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "main_category_id=".$category_main_id);
		if ($row = $sql-> db_Fetch()){
			$main_category_name = $row['main_category_name'];
		}
	}

	for ($n = 1; $n < 6; $n++){
		$sql -> db_Select(DB_TABLE_SHOP_PROPERTIES, "*", "property_id=".${"prod_prop_".$n."_id"});
		if ($row = $sql-> db_Fetch()){
			${"prop".$n."_name"} = $row['prop_display_name'];
			${"prop".$n."_list"} = $row['prop_list'];
			${"prop".$n."_prices"} = $row['prop_prices'];
		}
	}
  
	if ($prod_discount_id <> "") {
		$sql -> db_Select(DB_TABLE_SHOP_DISCOUNT, "*", "discount_id=".$prod_discount_id);
		if ($row = $sql-> db_Fetch()){
			$discount_id = $row['discount_id'];
			$discount_name = $row['discount_name'];
			$discount_class = $row['discount_class'];
			$discount_flag = $row['discount_flag'];
			$discount_price = $row['discount_price'];
			$discount_percentage = $row['discount_percentage'];
			$discount_valid_from = $row['discount_valid_from'];
			$discount_valid_till = $row['discount_valid_till'];
			$discount_code = $row['discount_code'];
		}
	}
  
	if ($discount_valid_till == 0) {
		$discount_valid_till = 9999999999; // set end date far away
	}

	// Print the shop at the 'top' if the setting is not set to 'bottom' (value 1)
	if ($print_shop_top_bottom != '1') {
		$es_store_header = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_header', $es_store_header);	
	}

	if ($category_main_id <> "0") {
		$easyshop_prod_mcat_link = array($category_main_id, $main_category_name);
		cachevars('easyshop_prod_mcat_link', $easyshop_prod_mcat_link);
	}

	cachevars('easyshop_download_datasheet_filename', '');
	if ($download_datasheet == "2") { // v1.7
		cachevars('easyshop_download_datasheet_filename', $item_id);
	}
   
 	$item_image_list = explode(",",$item_image);
 	$arrayLength = count($item_image_list);

	$easyshop_prod_cat_link = array($category_id, $category_name);
	cachevars('easyshop_prod_cat_link', $easyshop_prod_cat_link);
	
	cachevars('easyshop_prod_breadcrum', $item_name);
	if (strlen($item_image)>0) { // Only display images when we have them
		// Display multiple images in JavaScript SlideShow
		$text .='
			<SCRIPT LANGUAGE="JavaScript">
			<!--
			/* EasyShop JavaScript Slideshow */
			//set image paths
			src = [';
			for ($i = 0; $i < $arrayLength; $i++){
			  $text .= '"'.$store_image_path.$item_image_list[$i].'",';
			}
			$text.='
			]
			//set corresponding urls
			//url = [""]

			//set duration for each image
			duration = 4;

			//core of image switching
			prod_img=[]; ct=0;
			function switch_prod_img() {
			var n=(ct+1)%src.length;
			if (prod_img[n] && (prod_img[n].complete || prod_img[n].complete==null)) {
			document["Prod_Image"].src = prod_img[ct=n].src;
			}
			prod_img[n=(ct+1)%src.length] = new Image;
			prod_img[n].src = src[n];
			setTimeout("switch_prod_img()",duration*1000);
			}
			function doLink(){
			location.href = url[ct];
			} onload = function(){
			if (document.images)
			switch_prod_img();
			}
			//-->
			</SCRIPT>
			';
		$easyshop_prod_image = array($store_image_path,$item_image_list);
		cachevars('easyshop_prod_image', $easyshop_prod_image);
	}
	cachevars('easyshop_prod_name', $item_name);
							
	// Display the SKU number if it is filled in
	if ($sku_number <> "") {
		cachevars('easyshop_prod_sku_number', $sku_number);
	}

	cachevars('easyshop_prod_description', $tp->toHTML($item_description, true));
	
	$easyshop_prod_price = array($unicode_character_before,$item_price,$unicode_character_after,$item_quotation); // v1.7
	cachevars('easyshop_prod_price', $easyshop_prod_price);
			
	// Conditionally print additional costs if they are more than zero
	if ($shipping_first_item > 0 ){
		$easyshop_prod_costs_shipping_first_item = array($unicode_character_before,$shipping_first_item,$unicode_character_after);
		cachevars('easyshop_prod_costs_shipping_first_item', $easyshop_prod_costs_shipping_first_item);
	}

	if ($shipping_additional_item > 0 ){
		$easyshop_prod_costs_additional_item = array($unicode_character_before,$shipping_additional_item,$unicode_character_after);
		cachevars('easyshop_prod_costs_additional_item', $easyshop_prod_costs_additional_item);
	}

	if ($handling_override > 0 ){
		$easyshop_prod_costs_handling = array($unicode_character_before,$handling_override,$unicode_character_after);
		cachevars('easyshop_prod_costs_handling', $easyshop_prod_costs_handling);			
	}
    
	if ($item_quotation == 2) {
		$easyshop_item_quotation = array($item_quotation,$item_id);
		cachevars('easyshop_item_quotation', $easyshop_item_quotation);
	} elseif ($item_out_of_stock == 2) {
		$easyshop_prod_out_of_stock = array($item_out_of_stock, $item_out_of_stock_explanation);
		cachevars('easyshop_prod_out_of_stock', $easyshop_prod_out_of_stock);
	} else {
		$prop1_count = $sql->db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE item_id=".$action_id." AND (category_class IN (".USERCLASS_LIST.")) ");
		if ($prop1_count = 0) {
			// Error that should not happen! Indicate that item_id does not exists.
			cachevars('easyshop_prod_non_extistant', $prop1_count);
		}
		// Add to Cart at Product Details page
		$fill_basket = "P"; // To indicate that add to cart is started from Product Details page
		$easyshop_add_to_cart = Forms::add_to_cart_form($prop1_list, $prop1_array, $prop1_prices,$prop1_name,
								  $prop2_list, $prop2_array, $prop2_prices,$prop2_name,
								  $prop3_list, $prop3_array, $prop3_prices,$prop3_name,
								  $prop4_list, $prop4_array, $prop4_prices,$prop4_name,
								  $prop5_list, $prop5_array, $prop5_prices,$prop5_name,
								  $prop6_list, $prop6_array, $prop6_prices,$prop6_name,
								  $unicode_character_before, $unicode_character_after, $item_price,
								  $discount_id, $discount_class, $discount_valid_from, $discount_valid_till,
								  $discount_code, $discount_flag, $discount_percentage, $discount_price,
								  $property_prices, $unicode_character_before, $unicode_character_after, $print_discount_icons,
								  $item_id, $item_name, $sku_number, $shipping_first_item, $shipping_additional_item, $handling_override,
								  $category_id, $item_instock, $item_track_stock, $enable_ipn, $db_id,
								  $category_order_class, $enable_number_input, $fill_basket);
		cachevars('easyshop_add_to_cart', $easyshop_add_to_cart);
	} // End of the Else for an active product in the Details view

	// View Cart at Product Details page
	$easyshop_prod_show_checkout = Shop::show_checkout($session_id);
	cachevars('easyshop_prod_show_checkout', $easyshop_prod_show_checkout);

	if (ADMIN && getperms("P")) { // Show admin icon when administrator
		$easyshop_admin_icon = array($item_id,$category_id);
		cachevars('easyshop_admin_icon', $easyshop_admin_icon);
	}
	// Print the shop at the 'bottom' if the setting is set to 'bottom' (value 1)
	if ($print_shop_top_bottom == '1') {
		$es_store_footer = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_footer', $es_store_footer);	
	}

	$text .= $tp->parseTemplate($ES_PROD_TEMPLATE, FALSE, $easyshop_shortcodes); // Extend the $text variable (that contains javascript when there are images)

	if ($enable_comments == 1) { // Show comment totals or 'Be the first to comment etc' when total is zero when setting is enabled
		if (General::getCommentTotal(easyshop, $item_id) == 0) {
		  $text .= "<br />".EASYSHOP_SHOP_38;
		} else {
		  $text .= "<br />".EASYSHOP_SHOP_39.": ".General::getCommentTotal(easyshop, $item_id);
		}
	}
	// Render the value of $text in a table.
	$title = EASYSHOP_SHOP_00;
	$ns -> tablerender($title, $text);

	if ($enable_comments == 1) { // Show comments and input comments form when setting is enabled
		// Show comments input section
		$comment_to = $item_id;
		$comment_sub = "Re: " . $tp->toFORM($item_name, false);
		$cobj->compose_comment("easyshop", "comment", $comment_to, $width, $comment_sub, $showrate = false);
		if (isset($_POST['commentsubmit']))
		{
		   $cobj->enter_comment($_POST['author_name'], $_POST['comment'], "easyshop", $comment_to, $pid, $_POST['subject']);
		   $target=('easyshop.php?prod.'.$item_id);
		   header("Location: ".$target);
		}
	}
}

//-----------------------------------------------------------------------------+
//----------------------- Show All Categories ---------------------------------+
//-----------------------------------------------------------------------------+
if($action == "allcat" || $action == "catpage" || $action == "blanks") {
	$add_where = '';
	if ($action == "blanks") {
		$add_where = " AND category_main_id= '' ";
	}
	$categories_count = $sql -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status = 2 ".$add_where." AND (category_class IN (".USERCLASS_LIST."))");
	if($categories_count > 0) {
		$no_categories = 1;
	}
	// Print the shop at the 'top' if the setting is not set to 'bottom' (value 1)
	if ($print_shop_top_bottom != '1') {
		$easyshop_store_header = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_header', $easyshop_store_header);		
	}
	// Determine the offset to display
	$category_offset = General::determine_offset($action,$action_id,$categories_per_page);
	cachevars("easyshop_allcat_action", $action);
	if (!isset($no_categories)) {
		cachevars('easyshop_allcat_no_categories', EASYSHOP_SHOP_04);
	} else {
		$count_rows = 0;
		$sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_active_status=2 $add_where AND (category_class IN (".USERCLASS_LIST.")) ORDER BY category_order LIMIT $category_offset, $categories_per_page");
		while($row = $sql-> db_Fetch()){
			$easyshop_allcat_cat_name_link = array($row['category_id'],$row['category_name']);
			cachevars('easyshop_allcat_cat_name_link', $easyshop_allcat_cat_name_link);

			$easyshop_allcat_cat_image = array($row['category_id'],$store_image_path, $row['category_image']);
			cachevars('easyshop_allcat_cat_image', $easyshop_allcat_cat_image);

			$easyshop_allcat_cat_description = $tp->toHTML($row['category_description'], true);
			cachevars('easyshop_allcat_cat_description', $easyshop_allcat_cat_description);

			// Count the total of products per category
			$sql2 = new db;
			$total_products_category = $sql2->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status=2 AND category_id=".$row['category_id']);
			// Display 'product' or 'products' (takes place in the shortcode)
			cachevars('easyshop_allcat_total_prod_per_cat', $total_products_category);
			// Display if category if class specific
			if ($row['category_class'] > 0 ) {
				cachevars('easyshop_allcat_class_specific', EASYSHOP_SHOP_54);
			}

			cachevars('easyshop_allcat_table_td_end', "&nbsp;");
			$count_rows++;

			if ($count_rows == $num_category_columns) {
				cachevars('easyshop_allcat_conditionalbreak', "&nbsp;");
				$count_rows = 0;
			}
			else {
				cachevars('easyshop_allcat_conditionalbreak', ""); // Clear the easyshop_allcat_conditionalbreak variable!
			}
			$easyshop_allcat_container .= $tp->parseTemplate($ES_ALLCAT_CONTAINER, FALSE, $easyshop_shortcodes);;
		}
		cachevars('easyshop_allcat_container', $easyshop_allcat_container);

		$total_categories = $sql -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status=2 ".$add_where." AND (category_class IN (".USERCLASS_LIST."))");
		$easyshop_allcat_paging = General::multiple_paging($total_categories,$categories_per_page,$action,$action_id,$page_id,$page_devide_char);
		cachevars('easyshop_allcat_paging', $easyshop_allcat_paging);

	}
	$easyshop_allcat_show_checkout = Shop::show_checkout($session_id);
	cachevars('easyshop_allcat_show_checkout', $easyshop_allcat_show_checkout);

    // Print the shop at the 'bottom' if the setting is set to 'bottom' (value 1)
	if ($print_shop_top_bottom == '1') {
		$easyshop_store_footer = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_footer', $easyshop_store_footer);		
	}

	$text = $tp->parseTemplate($ES_ALLCAT_TEMPLATE, FALSE, $easyshop_shortcodes);
	// Render the value of $text in a table.
	$title = EASYSHOP_SHOP_00;
	$ns -> tablerender($title, $text);
}

//-----------------------------------------------------------------------------+
//-------------------- Show All MAIN Categories -------------------------------+
//-----------------------------------------------------------------------------+
if($action == "" || $action == "mcatpage") {
	$main_categories = ($sql -> db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, "(*)", "WHERE main_category_active_status = 2") > 0);
	// Print the shop at the 'top' if the setting is not set to 'bottom' (value 1)
	if ($print_shop_top_bottom != '1') {
		$easyshop_store_header = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_header', $easyshop_store_header);		
	}
	// Determine the offset to display
	$main_category_offset = General::determine_offset($action,$action_id,$main_categories_per_page);
	if ($main_categories < 1) {
		// Redirect to easyshop.php?allcat if there are no main categories (backwards compatability for 1.2 functionality)
        header("Location: "."easyshop.php?allcat");
	} else {
		$count_rows = 0;
        $sql5 = new db;
		// Only display main category records in use
		$arg5= "SELECT DISTINCT category_main_id, main_category_id, main_category_name, main_category_image, main_category_description
		   FROM #easyshop_item_categories, #easyshop_main_categories
		   WHERE category_main_id=main_category_id AND main_category_active_status=2
		   ORDER BY main_category_order, main_category_name
		   LIMIT $main_category_offset, $main_categories_per_page";
        $sql5->db_Select_gen($arg5,false);
		while($row5 = $sql5-> db_Fetch()){
			$easyshop_mcat_name = array(e_SELF,$row5['main_category_id'],$row5['main_category_name']);
			cachevars('easyshop_mcat_name',$easyshop_mcat_name);							
			if ($row5['main_category_image'] == '') {
				$easyshop_mcat_image = "&nbsp;";
			} else {
				$easyshop_mcat_image = array(e_SELF,$row5['main_category_id'],$store_image_path,$row5['main_category_image']);
			}
			cachevars('easyshop_mcat_image', $easyshop_mcat_image);
            // Count active Product Categories with the current fetched Main Category and show them additionally below description
            $sql8 = new db;
            $cat_with_this_main = $sql8 -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status=2 AND category_main_id=".$row5['main_category_id']." AND (category_class IN (".USERCLASS_LIST.")) ");
			$easyshop_mcat_descr = array($tp->toHTML($row5['main_category_description'], true),$cat_with_this_main);
			cachevars('easyshop_mcat_descr', $easyshop_mcat_descr);
			$count_rows++;
			if ($count_rows == $num_main_category_columns) {
				cachevars('easyshop_mcat_conditionalbreak', "&nbsp;");
				$count_rows = 0;
			}
			else {
				cachevars('easyshop_mcat_conditionalbreak', ""); // Clear the easyshop_mcat_conditionalbreak variable!
			}
			$easyshop_all_mcat_container .= $tp->parseTemplate($ES_ALL_MCAT_CONTAINER, FALSE, $easyshop_shortcodes);
		} // End of while of fetching all main categories in use
		cachevars('easyshop_all_mcat_container', $easyshop_all_mcat_container);
								
		// Count active Product Categories without Main Category and show them additionally on last page
		$sql7 = new db;
		$cat_without_main = $sql7 -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status=2 AND category_main_id='' AND (category_class IN (".USERCLASS_LIST.")) ");
		if ($cat_without_main > 0) {
			cachevars('easyshop_mcat_loose_title', $cat_without_main);
			$count_rows++;
			$easyshop_all_mcat_loose_container = $tp->parseTemplate($ES_ALL_MCAT_LOOSE_CONTAINER, FALSE, $easyshop_shortcodes);
			cachevars('easyshop_all_mcat_loose_container', $easyshop_all_mcat_loose_container);
        } // End of if $cat_without_main

        $sql6 = new db;
		// Only display main category records in use
		$arg6 ="SELECT DISTINCT category_main_id, main_category_id, main_category_name, main_category_image, main_category_description
		FROM #easyshop_item_categories, #easyshop_main_categories
		WHERE category_main_id=main_category_id AND main_category_active_status=2";
        $sql6->db_Select_gen($arg6,false);
		while($row6 = $sql6-> db_Fetch()){
            $count_total_categories++;
        }
        $total_categories = $count_total_categories;
		$easyshop_paging = General::multiple_paging($total_categories,$main_categories_per_page,$action,$action_id,$page_id,$page_devide_char);
		cachevars('easyshop_paging', $easyshop_paging);
	} // End of else
	$easyshop_show_checkout = Shop::show_checkout($session_id); // Code optimisation: make use of function show_checkout
	cachevars('easyshop_show_checkout', $easyshop_show_checkout);

	// Print the shop at the 'bottom' if the setting is set to 'bottom' (value 1)
	if ($print_shop_top_bottom == '1') {
		$easyshop_store_footer = print_store_header($store_name,$store_address_1,$store_address_2,$store_city,$store_state,$store_zip,$store_country,$support_email,$store_welcome_message,$print_shop_address);
		cachevars('easyshop_store_footer', $easyshop_store_footer);		
	}

	$text = $tp->parseTemplate($ES_ALL_MCAT_TEMPLATE, FALSE, $easyshop_shortcodes);
	// Render the value of $text in a table.
	$title = EASYSHOP_SHOP_00;
	$ns -> tablerender($title, $text);
} // End of if to show main categories

function print_store_header($p_name,$p_address_1,$p_address_2,$p_city,$p_state,$p_zip,$p_country,$p_email,$p_welcome_message,$p_print_shop_address){
	global $tp, $sc_style;
	include(e_PLUGIN."easyshop/easyshop_shortcodes.php");
	if (file_exists(THEME."easyshop_template.php"))
	{
		include(THEME."easyshop_template.php");
	}
	else
	{
		include(e_PLUGIN."easyshop/templates/easyshop_template.php");
	}
	
	if ((($p_address_1 == '') && ($p_address_2 == '') && ($p_city == '') && ($p_state == '') && ($p_zip == '') && ($p_country == '')) or $p_print_shop_address != '1') {
		$display_message = null;
	} else {
		$display_message = 1;
	}
	if ($display_message == null) {
		// Don't display address
	} else {
		cachevars('easyshop_store_name', $p_name);
		if ($p_address_1 != null){
			cachevars('easyshop_store_address1', $p_address_1);
		}
		if ($p_address_2 !=null){
			cachevars('easyshop_store_address2', $p_address_2);
		}
		if ($p_city != null){
			cachevars('easyshop_store_city', $p_city);
		}
		if (($p_address_1 == null) && ($p_address_2 == null) && ($p_city == null)) {
			cachevars('easyshop_store_conditionalbreak', "&nbsp;");
		}
		if ($p_state != null){
			cachevars('easyshop_store_state', $p_state);
		}
		if ($p_zip != null){
			cachevars('easyshop_store_zip', $p_zip);
		}
		if (($p_address_1 == null) && ($p_address_2 == null) && ($p_city == null) && ($p_state == null) && ($p_zip == null)) {
			// Don't add a line break
		} else {
			cachevars('easyshop_store_conditionalbreak2', "&nbsp;");
		}
		if ($p_country != null){
			cachevars('easyshop_store_country', $p_country);
		}
		if (strlen(trim($p_email)) > 0) {
			// Security: protect shop e-mail from e-mail harvasting
			// Method: split the contact e-mail and present it in inline javascript
			$email = split("@", $p_email); //split e-mail address at the @-sign
			  $p_email_name = $email[0]; // everything before the @-sign
			$tld = split(".", $email[1]); //split the part after the @-sign on dot-sign
			//Now use an if->else to find out if it's a subdomain or not
			if(count($tld) == 2) {
			  //Normal simple address as someone@blah.com
			  $p_email_domain = $email[0]; // domain = blah
			  $p_email_tld = $email[1]; // tld = .com
			} else { // Subdomains like someone@blah.org.uk
			  // Determine the last tld expression
			  $last_dot = strrchr(".",$email[1]);
			  $p_email_domain = substr($email[1], 0, $last_dot); // domain = blah.org
			  $p_email_tld = substr($email[1], $last_dot); // tld = .uk
			}
			// Display the splitted e-mail in an inline javascript where we join them to one e-mail address (in the shortcode)
			$easyshop_store_email = array($p_email_name,$p_email_domain,$p_email_tld);
			cachevars('easyshop_store_email', $easyshop_store_email);					
		} // End of showing e-mail when filled in
	} // End of else of displaying address
	cachevars('easyshop_store_welcome_message', $p_welcome_message);
	$sh_text = $tp->parseTemplate($ES_STORE_CONTAINER, FALSE, $easyshop_shortcodes);
	return $sh_text;
}

function MailOrder($unicode_character_before, $unicode_character_after, $pref_sitename, $sender_name, $sender_email, $to_name, $to_email, $print_special_instr, $special_instr_text, $to_id, $email_info_level, $to_address1, $to_address2, $to_zipcode, $to_city, $to_telephone, $to_mobile, $email_additional_text) {
  //if(isset($_POST['email'])){
    $check= TRUE;
  	if ($check) {
  		if ($error) {
  			$message .= "<div style='text-align:center'><b>".EASYSHOP_SHOP_60." ".$error."</b></div>";
  		} else {
			$time_stamp = date('r', time());
			$address = $to_email;  // Provide multiple To: addresses separated with comma
				$pre_subject = ((isset($pref_sitename))?"[":"");
				$post_subject = ((isset($pref_sitename))?"]":"");
				$subject = $pre_subject.$pref_sitename.$post_subject." ".(($_SESSION['sc_total']['quotation'] == 2)?EASYSHOP_SHOP_94:EASYSHOP_SHOP_62)." ".date("Y-m-d");
				$message = EASYSHOP_SHOP_58."&nbsp;".$time_stamp."&nbsp;".(($_SESSION['sc_total']['quotation'] == 2)?EASYSHOP_SHOP_95:EASYSHOP_SHOP_59)."<br />
					<div style='text-align:center;'>
						<table border='1' cellspacing='1'>
						<tr>
							<td class='tbox'>".EASYSHOP_SHOP_21."</td>
							<td class='tbox'>".EASYSHOP_SHOP_22."</td>
							<td class='tbox'>".EASYSHOP_SHOP_23."</td>
							<td class='tbox'>".EASYSHOP_SHOP_24."</td>
							<td class='tbox'>".EASYSHOP_SHOP_25."</td>
							<td class='tbox'>".EASYSHOP_SHOP_26."</td>
							<td class='tbox'>".EASYSHOP_SHOP_27."</td>
						</tr>";

			// Fill the message with products from the basket
			$count_items = count($_SESSION['shopping_cart']); // Count number of different products in basket
			$sum_quantity = $_SESSION['sc_total']['items'];       // Display cached sum of total quantity of items in basket
			$sum_shipping = $_SESSION['sc_total']['shipping'];    // Display cached sum of shipping costs for 1st item
			$sum_shipping2 = $_SESSION['sc_total']['shipping2'];  // Display cached sum of shipping costs for additional items (>1)
			$sum_handling = $_SESSION['sc_total']['handling'];    // Display cached sum of handling costs
			$sum_shipping_handling = number_format(($sum_shipping + $sum_shipping2 + $sum_handling), 2, '.', ''); // Calculate total handling and shipping price
			$sum_price = number_format(($_SESSION['sc_total']['sum'] + $sum_shipping_handling), 2, '.', ''); // Display cached sum of total price of items in basket + shipping + handling costs

			$array = $_SESSION['shopping_cart'];
			// PayPal requires to pass multiple products in a sequence starting at 1; we do as well in the mail
			$cart_count = 1;
			// For each product in the shopping cart array write PayPal details
			foreach($array as $id => $item) {
			  $display_sku_number = $item['sku_number'];
			  if ($item['sku_number'] == "") {
				$display_sku_number = "&nbsp;"; // Force a space in the cell for proper border display
			  }
			  $message .= "
						  <tr>
							  <td class='tbox'>".$display_sku_number."</td>
							  <td class='tbox'>".$item['item_name']."</td>
							  <td class='tbox'>".$unicode_character_before.$item['item_price'].$unicode_character_after."</td>
							  <td class='tbox'>".$item['quantity']."</td>
							  <td class='tbox'>".$unicode_character_before.$item['shipping'].$unicode_character_after."</td>
							  <td class='tbox'>".$unicode_character_before.$item['shipping2'].$unicode_character_after."</td>
							  <td class='tbox'>".$unicode_character_before.$item['handling'].$unicode_character_after."</td>
						  </tr>";
			  $cart_count++;
			}
			$message .= "
						</table>
					</div>
					<div style='text-align:left;'>
					<br />".EASYSHOP_SHOP_16." ".$sum_quantity."
					<br />".EASYSHOP_SHOP_18." ".$unicode_character_before.$sum_price.$unicode_character_after."
						";
						if ($sum_shipping_handling > 0) {
						  $message .= "<br />".EASYSHOP_SHOP_20." ".$unicode_character_before.$sum_shipping_handling.$unicode_character_after;
						}

			// Add special instructions
			if ($print_special_instr == '1') {
			  $message .= "<br /><br />".EASYSHOP_SHOP_82.":<br />$special_instr_text<br />";
			}
			
			// Add loggin in user info
			if (USER) {
				$message .="<br /><br />".EASYSHOP_SHOP_93.": <a href='".SITEURL."user.php?id.".$to_id."'>".USERNAME."</a> (<a href='mailto:".USEREMAIL."'>".USEREMAIL."</a>)";
			}

			// Add extra address info
			if (($email_info_level == 1 || $email_info_level == 2) && !USER) {
				$message .= "<br /><br />$to_name<br />
						  $to_address1<br />
						  $to_address2<br />
						  $to_zipcode  $to_city<br />
						  ".EASYSHOP_SHOP_90.": $to_telephone
						  ".EASYSHOP_SHOP_91.": $to_mobile<br /><br />";
			}
		   
			// Add extra admin info from seller
			if (strlen(trim($email_additional_text))>0){
				$message .= "<br /><br />
						   $email_additional_text
						   <br /><br />";
			}
			
			$message .= "</div><br /><br /><div style='text-align:center;'>&copy; <a href='http://e107.webstartinternet.com/'>EasyShop</a></div>";

			if(!ShopMail::easyshop_sendemail($address, $subject, $message, $header)) {
				$message = EASYSHOP_SHOP_55;  // Order e-mail failed
			} else {
				// Send also a copy to the shop owner
				//$address = $sender_name." <".$sender_email.">";
				$address = $sender_email;
				$message = EASYSHOP_SHOP_64." ".$to_name." (<a href'".$to_email."'>".$to_email."</a>)<br /><br />".$message; // Extra in admin mail: "Following mail has been send to"
				global $e107;
				$ip = $e107->getip();
				$message .= "<br />".EASYSHOP_SHOP_81.": ".$ip; // Add 'Send from IP address' to mail message
				if(!ShopMail::easyshop_sendemail($address, $subject, $message, $header)) {
					$message = EASYSHOP_SHOP_63;  // Order e-mail to admin failed
				} else {
					$message = EASYSHOP_SHOP_56; // Order e-mail succeeded
					$mail_result = 1;
				}
			}
			// Send downloads
			ShopMail::easyshop_senddownloads($_SESSION['shopping_cart'], $to_email);
  		}
  	} else {
  		$message = EASYSHOP_SHOP_57; // Please fill in all fields correctly
  	}
  //}
  return array($message, $mail_result);
}

// === End of BODY ===
// use FOOTERF for USER PAGES and e_ADMIN.'footer.php' for admin pages
require_once(FOOTERF);
?>