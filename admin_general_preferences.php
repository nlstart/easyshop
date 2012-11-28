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
// Include ren_help for display_help (while showing BBcodes)
require_once(e_HANDLER.'ren_help.php');
// Include the easyshop class to show tabs
require_once('easyshop_class.php');

// Load the tabs style css
$text .= General::easyshop_theme_head();

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_07';

if ($_POST['edit_preferences'] == '1') {
	// Add a trailing slash to the path in case there is none
	if (substr($_POST['store_image_path'],-1) != "/") {
	  $_POST['store_image_path'] = $_POST['store_image_path']."/";
	}
	
	// Ensure that print_special_instr is 'Off' when email_order is 'Off'
	if ($_POST['email_order'] <> '1') {
	  $_POST['print_special_instr'] = '0';
	}
	
	// Count of preference record with store_id 1
	$pref_records = $sql->db_Count(DB_TABLE_SHOP_PREFERENCES, "(*)", "WHERE store_id=1");

	// Update if record 1 is available
	if ($pref_records == 1) {
		// Change Shop Preferences
		$sql->db_Update(DB_TABLE_SHOP_PREFERENCES,
		"store_name='".$tp->toDB($_POST['store_name'])."',
		support_email='".$tp->toDB($_POST['support_email'])."',
		store_address_1='".$tp->toDB($_POST['store_address_1'])."',
		store_address_2='".$tp->toDB($_POST['store_address_2'])."',
		store_city='".$tp->toDB($_POST['store_city'])."',
		store_state='".$tp->toDB($_POST['store_state'])."',
		store_zip='".$tp->toDB($_POST['store_zip'])."',
		store_country='".$tp->toDB($_POST['store_country'])."',
		store_welcome_message='".$tp->toDB($_POST['store_welcome_message'])."',
		store_info='".$tp->toDB($_POST['store_info'])."',
		store_image_path='".$tp->toDB($_POST['store_image_path'])."',
		num_category_columns = '".$tp->toDB(intval($_POST['num_category_columns']))."',
		categories_per_page = '".$tp->toDB(intval($_POST['categories_per_page']))."',
		num_item_columns = '".$tp->toDB(intval($_POST['num_item_columns']))."',
		items_per_page = '".$tp->toDB(intval($_POST['items_per_page']))."',
		paypal_email='".$tp->toDB($_POST['paypal_email'])."',
		popup_window_height='".$tp->toDB($_POST['popup_window_height'])."',
		popup_window_width='".$tp->toDB($_POST['popup_window_width'])."',
		cart_background_color='".$tp->toDB($_POST['cart_background_color'])."',
		thank_you_page_title='".$tp->toDB($_POST['thank_you_page_title'])."',
		thank_you_page_text='".$tp->toDB($_POST['thank_you_page_text'])."',
		thank_you_page_email='".$tp->toDB($_POST['thank_you_page_email'])."',
		payment_page_style='".$tp->toDB($_POST['payment_page_style'])."',
		payment_page_image='".$tp->toDB($_POST['payment_page_image'])."',
		sandbox=1,
		set_currency_behind='".$tp->toDB($_POST['set_currency_behind'])."',
		minimum_amount='".intval($tp->toDB($_POST['minimum_amount']))."',
		always_show_checkout='".$tp->toDB($_POST['always_show_checkout'])."',
		email_order='".$tp->toDB($_POST['email_order'])."',
		product_sorting='".$tp->toDB($_POST['product_sorting'])."',
		page_devide_char='".$tp->toDB($_POST['page_devide_char'])."',
		icon_width='".intval($tp->toDB($_POST['icon_width']))."',
		cancel_page_title='".$tp->toDB($_POST['cancel_page_title'])."',
		cancel_page_text='".$tp->toDB($_POST['cancel_page_text'])."',
		enable_comments='".$tp->toDB($_POST['enable_comments'])."',
		show_shopping_bag='".$tp->toDB($_POST['show_shopping_bag'])."',
		print_shop_address = '".$tp->toDB($_POST['print_shop_address'])."',
		print_shop_top_bottom = '".$tp->toDB($_POST['print_shop_top_bottom'])."',
		print_discount_icons = '".$tp->toDB($_POST['print_discount_icons'])."',
		shopping_bag_color = '".$tp->toDB(intval($_POST['shopping_bag_color']))."',
		enable_ipn = '".$tp->toDB(intval($_POST['enable_ipn']))."',
		enable_number_input = '".$tp->toDB(intval($_POST['enable_number_input']))."',
		print_special_instr = '".$tp->toDB(intval($_POST['print_special_instr']))."',
		email_info_level = '".$tp->toDB(intval($_POST['email_info_level']))."',
		email_additional_text = '".$tp->toDB($_POST['email_additional_text'])."',
		monitor_clean_shop_days = '".$tp->toDB(intval($_POST['monitor_clean_shop_days']))."',
		monitor_clean_check_days = '".$tp->toDB(intval($_POST['monitor_clean_check_days']))."',
		num_main_category_columns = '".$tp->toDB(intval($_POST['num_main_category_columns']))."',
		main_categories_per_page = '".$tp->toDB(intval($_POST['main_categories_per_page']))."',
		paypal_primary_email = '".$tp->toDB($_POST['paypal_primary_email'])."'
		WHERE
		store_id=1");
	  if (isset($_POST['sandbox'])) {
		if ($_POST['sandbox'] == '2') {
			$sql->db_Update(DB_TABLE_SHOP_PREFERENCES, "sandbox='2' WHERE store_id=1");
		}
	  }
	  $sql->db_Update(DB_TABLE_SHOP_CURRENCY, "currency_active='1'");
	  $sql->db_Update(DB_TABLE_SHOP_CURRENCY, "currency_active='2' WHERE currency_id=".$tp->toDB($_POST['currency_id']));
	} else {
		// Insert record 1; for some 1.21 users the predefined record in easyshop_preferences was not created on install
		$arg= "ALTER TABLE #easyshop_preferences AUTO_INCREMENT = 1";
		// Autoincrement will make this record number 1... // Bugfix of 1.3 where I tried to fill in value '1' hardcoded, which MySQL doesn't like
		$sql->db_Select_gen($arg,false);
		$sql -> db_Insert(DB_TABLE_SHOP_PREFERENCES,
		"",
		$tp->toDB($_POST['store_name']),
		$tp->toDB($_POST['support_email']),
		$tp->toDB($_POST['store_address_1']),
		$tp->toDB($_POST['store_address_2']),
		$tp->toDB($_POST['store_city']),
		$tp->toDB($_POST['store_state']),
		$tp->toDB($_POST['store_zip']),
		$tp->toDB($_POST['store_country']),
		$tp->toDB($_POST['store_welcome_message']),
		$tp->toDB($_POST['store_info']),
		$tp->toDB($_POST['store_image_path']),
		$tp->toDB(intval($_POST['num_category_columns'])),
		$tp->toDB(intval($_POST['categories_per_page'])),
		$tp->toDB(intval($_POST['num_item_columns'])),
		$tp->toDB(intval($_POST['items_per_page'])),
		$tp->toDB($_POST['paypal_email']),
		$tp->toDB($_POST['popup_window_height']),
		$tp->toDB($_POST['popup_window_width']),
		$tp->toDB($_POST['cart_background_color']),
		$tp->toDB($_POST['thank_you_page_title']),
		$tp->toDB($_POST['thank_you_page_text']),
		$tp->toDB($_POST['thank_you_page_email']),
		$tp->toDB($_POST['payment_page_style']),
		$tp->toDB($_POST['payment_page_image']),
		"",
		"",
		1,
		$tp->toDB($_POST['set_currency_behind']),
		$tp->toDB(intval($_POST['minimum_amount'])),
		$tp->toDB($_POST['always_show_checkout']),
		$tp->toDB($_POST['email_order']),
		$tp->toDB($_POST['product_sorting']),
		$tp->toDB($_POST['page_devide_char']),
		$tp->toDB(intval($_POST['icon_width'])),
		$tp->toDB($_POST['cancel_page_title']),
		$tp->toDB($_POST['cancel_page_text']),
		$tp->toDB($_POST['enable_comments']),
		$tp->toDB($_POST['show_shopping_bag']),
		$tp->toDB($_POST['print_shop_address']),
		$tp->toDB($_POST['print_shop_top_bottom']),
		$tp->toDB($_POST['print_discount_icons']),
		$tp->toDB(intval($_POST['shopping_bag_color'])),
		$tp->toDB(intval($_POST['enable_ipn'])),
		$tp->toDB(intval($_POST['enable_number_input'])),
		$tp->toDB(intval($_POST['print_special_instr'])),
		$tp->toDB(intval($_POST['email_info_level'])),
		$tp->toDB($_POST['email_additional_text']),
		$tp->toDB(intval($_POST['monitor_clean_shop_days'])),
		$tp->toDB(intval($_POST['monitor_clean_check_days'])),
		$tp->toDB(intval($_POST['num_main_category_columns'])),
		$tp->toDB(intval($_POST['main_categories_per_page'])),
		$tp->toDB($_POST['paypal_primary_email'])
	  );
	}
	header("Location: ".e_SELF);
	exit();
}

// Creation of currencies can be skipped if there are 16 currencies
$sql = new db;
if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY) < 16) {
  // Check for each PayPal currency if it is missing and add it to the currency file
  /*
  Supported PayPal currencies:
  01. AUD Australian Dollar
  02. CAD Canadian Dollar
  03. CHF Swiss Franc
  04. CZK Czech Koruna
  05. DKK Danish Krone
  06. EUR Euro
  07. GBP Pound Sterling
  08. HKD Hong Kong Dollar
  09. HUF Hungarian Forint
  10. JPY Japanese Yen
  11. NOK Norwegian Krone
  12. NZD New Zealand Dollar
  13. PLN Polish Zloty
  14. SEK Swedish Krona
  15. SGD Singapore Dollar
  16. USD U.S. Dollar
  */
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'AUD'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_30." (&#36;AU)',
			'AUD',
			'&#36;AU',
			'1',
			'2',
			'1'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'CAD'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_18." (C&#36;)',
			'CAD',
			'C&#36;',
			'1',
			'2',
			'2'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'CHF'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_31." (SFr.)',
			'CHF',
			'SFr.',
			'1',
			'2',
			'3'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'CZK'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_32." (K&#10d;)',
			'CZK',
			'K&#10d;',
			'1',
			'2',
			'4'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'DKK'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_33." (kr.)',
			'DKK',
			'Dkr.',
			'1',
			'2',
			'5'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'EUR'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_19." (&#8364;)',
			'EUR',
			'&#8364;',
			'1',
			'2',
			'6'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'GBP'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_20." (&#163;)',
			'GBP',
			'&#163;',
			'1',
			'2',
			'7'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'HKD'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_34." (HK&#36;)',
			'HKD',
			'HK&#36;',
			'1',
			'2',
			'8'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'HUF'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_35." (Ft)',
			'HUF',
			'Ft',
			'1',
			'2',
			'9'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'JPY'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_29." (&#165;)',
			'JPY',
			'&#165;',
			'1',
			'2',
			'10'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'NOK'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_36." (Nkr.)',
			'NOK',
			'Nkr.',
			'1',
			'2',
			'11'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'NZD'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_37." (NZ&#36;)',
			'NZD',
			'NZ&#36;',
			'1',
			'2',
			'12'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'PLN'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_38." (P&#142;)',
			'PLN',
			'P&#142;',
			'1',
			'2',
			'13'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'SEK'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_39." (Skr.)',
			'SEK',
			'Skr.',
			'1',
			'2',
			'14'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'SGD'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_40." (S&#36;)',
			'SGD',
			'S&#36;',
			'1',
			'2',
			'15'");
	}
	if ($sql->db_Count(DB_TABLE_SHOP_CURRENCY, "(*)", "WHERE paypal_currency_code = 'USD'") != 1) {
	    $sql->db_Insert(DB_TABLE_SHOP_CURRENCY,
	        "0,
			'".EASYSHOP_GENPREF_17." (&#36;)',
			'USD',
			'&#36;',
			'2',
			'2',
			'16'");
	}
}

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
    $thank_you_page_email = $row['thank_you_page_email'];
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
    $icon_width = $row['icon_width'];
    $cancel_page_title = $row['cancel_page_title'];
    $cancel_page_text = $row['cancel_page_text'];
    $enable_comments = $row['enable_comments'];
    $show_shopping_bag = $row['show_shopping_bag'];
    $print_shop_address = $row['print_shop_address'];
    $print_shop_top_bottom = $row['print_shop_top_bottom'];
    $print_discount_icons = $row['print_discount_icons'];
    $shopping_bag_color = $row['shopping_bag_color'];
    $enable_ipn = $row['enable_ipn']; // IPN addition
    $enable_number_input = $row['enable_number_input'];
    $print_special_instr = $row['print_special_instr'];
    $email_info_level = $row['email_info_level'];
    $email_additional_text = $row['email_additional_text'];
    $monitor_clean_shop_days = $row['monitor_clean_shop_days'];
    $monitor_clean_check_days = $row['monitor_clean_check_days'];
    $num_main_category_columns = $row['num_main_category_columns'];
    $main_categories_per_page = $row['main_categories_per_page'];
	$paypal_primary_email = $row['paypal_primary_email']; // PayPal Primary e-mail address
}

// Start form frame
$text .= "
<form name='general_preferences' method='POST' action='".e_SELF."'>
<!-- <fieldset>
	<legend>
		".EASYSHOP_GENPREF_01."
	</legend>-->";

// Preferences consists of five parts: Shop info, Settings, PayPal info, IPN Monitor settings, Presentation Settings
// 1. Shop Contact Info
$text1 .= "
	<table border='0' class='tborder' cellspacing='15'>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_02."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='25' type='text' name='store_name' value='$store_name' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_03."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='35'  type='text' name='store_address_1' value='$store_address_1' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_04."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='35'  type='text' name='store_address_2' value='$store_address_2' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_05."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='25'  type='text' name='store_city' value='$store_city' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_06."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='2' maxlength='2' type='text' name='store_state' value='$store_state' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_07."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='12' maxlength='10'  type='text' name='store_zip' value='$store_zip' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_08."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='3' maxlength='3'  type='text' name='store_country' value='$store_country' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_09."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='25'  type='text' name='support_email' value='$support_email' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px' valign='top'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_10."
				</span>
				<br />
				".EASYSHOP_GENPREF_11."
				
			</td>
			<td class='tborder' style='width: 200px'>
				<textarea class='tbox' cols='50' rows='7' name='store_welcome_message' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>$store_welcome_message</textarea><br />".display_help('helpa')."
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_12."
				</span>
				<br />
				".EASYSHOP_GENPREF_13."
			</td>
			<td class='tborder' style='width: 200px' valign='top'>
				<input class='tbox' size='35' type='text' name='store_image_path' value='$store_image_path' />
			</td>
		</tr>
	</table>
<!-- </fieldset> -->
<br />";
	
// 2. Settings
$text2 .= "
<!-- <fieldset>
    <legend>
      ".EASYSHOP_GENPREF_44."
    </legend> -->
	<table border='0' class='tborder' cellspacing='15'>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_45."
				</span>
				<br />
				".EASYSHOP_GENPREF_46."<br />
				".EASYSHOP_GENPREF_47."
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='set_currency_behind'>
				<option value='0' ";
				if ($set_currency_behind == '0' or $set_currency_behind == '') {
					$text2 .= "selected='selected'";
				}
				$text2 .= ">".EASYSHOP_GENPREF_48."</option>
				<option value='1' ";
				if ($set_currency_behind == '1') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				  ">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_50."
				</span>
				<br />
				".EASYSHOP_GENPREF_51."<br />
				".EASYSHOP_GENPREF_52."
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='25'  type='text' name='minimum_amount' value='$minimum_amount' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_53."
				</span>
				<br />
				".EASYSHOP_GENPREF_54."
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='always_show_checkout'>
				<option value='0' ";
				if ($always_show_checkout == '0' or $always_show_checkout == '') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
					">".EASYSHOP_GENPREF_48."</option>
					<option value='1' ";
				if ($always_show_checkout == '1') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_55."
				</span>
				<br />
				".EASYSHOP_GENPREF_56."
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='6'  type='text' name='page_devide_char' value='$page_devide_char' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_57."
				</span>
				<br />
				".EASYSHOP_GENPREF_58."<br />
				".EASYSHOP_GENPREF_59."
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='3'  type='text' name='icon_width' value='$icon_width' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_60."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='enable_comments'>
				<option value='0' ";
				if ($enable_comments == '0' or $enable_comments == '') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_48."</option>
				<option value='1' ";
				if ($enable_comments == '1') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>

		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_61."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='show_shopping_bag'>
				<option value='0' ";
				if ($show_shopping_bag == '0' or $show_shopping_bag == '') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_48."</option>
				<option value='1' ";
				if ($show_shopping_bag == '1') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>";
		
		if ($show_shopping_bag == '1') { // Ask for bag color only if it switched on
			$text2 .= "
    		<tr>
    			<td class='tborder' style='width: 200px'>
    				<span class='smalltext' style='font-weight: bold'>
             ".EASYSHOP_GENPREF_66."
    				</span>
    			</td>
    			<td class='tborder' style='width: 200px'>
    				<select class='tbox' name='shopping_bag_color'>
    				<option value='0' ";
    				if ($shopping_bag_color == '0' or $shopping_bag_color == '') {
    					$text2 .= "selected='selected'";
    				}
    				$text2 .=
    				">".EASYSHOP_GENPREF_67."</option>
    				<option value='1' ";
    				if ($shopping_bag_color == '1') {
    					$text2 .= "selected='selected'";
    				}
    				$text2 .=
    				">".EASYSHOP_GENPREF_68."</option>
    				<option value='2' ";
    				if ($shopping_bag_color == '2') {
    					$text2 .= "selected='selected'";
    				}
    				$text2 .=
    				">".EASYSHOP_GENPREF_83."</option>
    				<option value='3' ";
    				if ($shopping_bag_color == '3') {
    					$text2 .= "selected='selected'";
    				}
    				$text2 .=
    				">".EASYSHOP_GENPREF_84."</option>
    				<option value='4' ";
    				if ($shopping_bag_color == '4') {
    					$text2 .= "selected='selected'";
    				}
    				$text2 .=
    				">".EASYSHOP_GENPREF_85."</option>
    				<option value='5' ";
    				if ($shopping_bag_color == '5') {
    					$text2 .= "selected='selected'";
    				}
    				$text2 .=
    				">".EASYSHOP_GENPREF_86."</option>
    				<option value='6' ";
    				if ($shopping_bag_color == '6') {
    					$text2 .= "selected='selected'";
    				}
    				$text2 .=
    				">".EASYSHOP_GENPREF_87."</option>
					</select>
    			</td>
    		</tr>";
		} // 0=blue, 1=green, 2=orange, 3=red, 4=yellow, 5=white, 6=black
    // End of if show graphical basket equals true

		$text2 .= "
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_63."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='print_shop_top_bottom'>
				<option value='0' ";
				if ($print_shop_top_bottom == '0' or $print_shop_top_bottom == '') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_64."</option>
				<option value='1' ";
				if ($print_shop_top_bottom == '1') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_65."</option>
				</select>
			</td>
		</tr>

		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_62."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='print_shop_address'>
				<option value='0' ";
				if ($print_shop_address == '0' or $print_shop_address == '') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_48."</option>
				<option value='1' ";
				if ($print_shop_address == '1') {
					$text2 .= "selected='selected'";
				}
				$text2 .=
				">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>

		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
         ".EASYSHOP_GENPREF_70." <br />(".EASYSHOP_GENPREF_71.")
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='print_discount_icons'>
				<option value='0' "; if($print_discount_icons == '0' or $print_discount_icons == '') {$text2 .= "selected='selected'";} $text2 .= ">".EASYSHOP_GENPREF_48."</option>
				<option value='1' "; if($print_discount_icons == '1') {$text2 .= "selected='selected'";} $text2 .= ">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
        ".EASYSHOP_GENPREF_78."
				</span>
				<br />
        ".EASYSHOP_GENPREF_79."
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='enable_number_input'>
				<option value='0' "; if($enable_number_input == '0' or $enable_number_input == '') {$text2 .= "selected='selected'";} $text2 .= ">".EASYSHOP_GENPREF_48."</option>
				<option value='1' "; if($enable_number_input == '1') {$text2 .= "selected='selected'";} $text2 .= ">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>

	</table>
<!-- </fieldset> -->
<br />";
  
// 3. PayPal info
$text3 .= "
<!-- <fieldset>
	<legend>
		".EASYSHOP_GENPREF_14."
	</legend> -->
	<table border='0' class='tborder' cellspacing='15'>

		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_69."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='email_order'>
				<option value='0' ";
				if ($email_order == '0' or $email_order == '') {
					$text3 .= "selected='selected'";
				}
				$text3 .=
				">".EASYSHOP_GENPREF_48."</option>
				<option value='1' ";
				if ($email_order == '1') {
					$text3 .= "selected='selected'";
				}
				$text3 .=
				">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>";

    $email_order <> '1' ? $enabled_text = " disabled = 'true' " : $enabled_text = "";
    $text3 .=
		"<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
        ".EASYSHOP_GENPREF_80."
				</span>
				<br />
        ".EASYSHOP_GENPREF_81."<br />
        ".EASYSHOP_GENPREF_82."
			</td>
			<td class='tborder' style='width: 200px' valign='top'>
				<select class='tbox' $enabled_text name='print_special_instr'>
				<option value='0' "; if($print_special_instr == '0' or $print_special_instr == '' or $email_order <> '1') {$text3 .= "selected='selected'";} $text3 .= ">".EASYSHOP_GENPREF_48."</option>
				<option value='1' "; if($print_special_instr == '1' and $email_order == '1' ) {$text3 .= "selected='selected'";} $text3 .= ">".EASYSHOP_GENPREF_49."</option>
				</select>
			</td>
		</tr>

    <tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
        ".EASYSHOP_GENPREF_88."
				</span>
				<br />
        ".EASYSHOP_GENPREF_82."
			</td>
			<td class='tborder' style='width: 200px' valign='top'>
				<select class='tbox' $enabled_text name='email_info_level'>
				<option value='0' "; if($email_info_level == '0' or $email_info_level == '' and $email_order == '1') {$text3 .= "selected='selected'";} $text3 .= ">".EASYSHOP_GENPREF_89."</option>
				<option value='1' "; if($email_info_level == '1' and $email_order == '1' ) {$text3 .= "selected='selected'";} $text3 .= ">".EASYSHOP_GENPREF_90."</option>
				<option value='2' "; if($email_info_level == '2' and $email_order == '1' ) {$text3 .= "selected='selected'";} $text3 .= ">".EASYSHOP_GENPREF_91."</option>
				<option value='3' "; if($email_info_level == '3' and $email_order == '1' ) {$text3 .= "selected='selected'";} $text3 .= ">".EASYSHOP_GENPREF_92."</option>
				</select>
      </td>
		</tr>
		
		<tr>
			<td class='tborder' style='width: 200px' valign='top'>
				<span class='smalltext' style='font-weight: bold'>
         ".EASYSHOP_GENPREF_93."
				</span>
				<br />
        ".EASYSHOP_GENPREF_82."
			</td>
			<td class='tborder' style='width: 200px'>
				<textarea class='tbox' cols='50' rows='7' $enabled_text name='email_additional_text'>$email_additional_text</textarea>
			</td>
		</tr>


		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_15."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='25'  type='text' name='paypal_email' value='$paypal_email' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					Primary PayPal address
				</span><br />
				    IPN validate will return your Primary PayPal e-mail address;<br />
					For correct IPN validation: fill in your primary PayPal e-mail here.
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='25'  type='text' name='paypal_primary_email' value='$paypal_primary_email' />
			</td>
		</tr>		

		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_16."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<select class='tbox' name='currency_id'>";
						
				$sql2 = new db;
				$sql2 -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "ORDER BY currency_order", "no-where");
				while ($row2 = $sql2->db_Fetch()) {
					if($row2['currency_active'] == '2') {
						$text3 .= "
						<option value='".$row2['currency_id']."' selected='selected'>".$row2['display_name']."</option>";
					} else {
						$text3 .= "
						<option value='".$row2['currency_id']."'>".$row2['display_name']."</option>";
					}
				}
				$text3 .= "
				</select>
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_21."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='40'  type='text' name='thank_you_page_title' value='$thank_you_page_title' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px' valign='top'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_22."
				</span>
				<br />
				".EASYSHOP_GENPREF_23."
			</td>
			<td class='tborder' style='width: 200px'>
				<textarea class='tbox' cols='50' rows='7' name='thank_you_page_text'>$thank_you_page_text</textarea>
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_41."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='40'  type='text' name='cancel_page_title' value='$cancel_page_title' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px' valign='top'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_42."
				</span>
				<br />
				".EASYSHOP_GENPREF_43."
			</td>
			<td class='tborder' style='width: 200px'>
				<textarea class='tbox' cols='50' rows='7' name='cancel_page_text'>$cancel_page_text</textarea>
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_24."
				</span>
				<br />
				".EASYSHOP_GENPREF_25."
			</td>
			<td class='tborder' style='width: 200px' valign='top'>
				<input class='tbox' size='25'  type='text' name='payment_page_style' value='$payment_page_style' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_26."
				</span>
				<br />
				".EASYSHOP_GENPREF_27."
			</td>
			<td class='tborder' style='width: 200px' valign='top'>";
				if ($sandbox == '2') {
					$text3 .= "
					<input class='tbox' size='25'  type='checkbox' name='sandbox' value='2' checked='checked' />";
				} else {
					$text3 .= "
					<input class='tbox' size='25'  type='checkbox' name='sandbox' value='2' />";
				}
			$text3 .= "
			</td>
		</tr>";

if ($enable_ipn == '2'){
    $optiontext = " <input class='tbox' size='25' type='checkbox' name='enable_ipn' value='2' checked='checked'></option>";
}else{
    $optiontext = " <input class='tbox' size='25' type='checkbox' name='enable_ipn' value='2' ></option>";
}

$text3 .= "
      <tr>
        <td class='tborder' style='width: 200px'>
        <span class='smalltext' style='font-weight: bold'>
        ".EASYSHOP_GENPREF_72."<br />
        ".EASYSHOP_GENPREF_73."<br />
        ".EASYSHOP_GENPREF_74."<br />
        ".EASYSHOP_GENPREF_75."<br />
        ".EASYSHOP_GENPREF_76."</br />
        <br />".EASYSHOP_GENPREF_77."
         </span></td>
        <td class='tborder' style='width: 200px' valign='top'>".$optiontext."</td>
      </tr>
    </table>
<!--  </fieldset> -->
";

// 4. IPN Monitor settings
if ($enable_ipn == '2') {
	if($monitor_clean_shop_days == "" || $monitor_clean_shop_days == NULL || $monitor_clean_shop_days == 0)   { $monitor_clean_shop_days  =  3; } // Default is 3 days
	if($monitor_clean_check_days == "" || $monitor_clean_check_days == NULL || $monitor_clean_check_days == 0){ $monitor_clean_check_days =  7; } // Default is 7 days
	$text4 .= "
		<table>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_95."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='3'  type='text' name='monitor_clean_shop_days' value='$monitor_clean_shop_days' />
			</td>
		</tr>
		<tr>
			<td class='tborder' style='width: 200px'>
				<span class='smalltext' style='font-weight: bold'>
					".EASYSHOP_GENPREF_96."
				</span>
			</td>
			<td class='tborder' style='width: 200px'>
				<input class='tbox' size='3'  type='text' name='monitor_clean_check_days' value='$monitor_clean_check_days' />
			</td>
		</tr>		
		</table>
		";
}

// 5. Presentation settings
$text5 .= "
						<table border='0' cellspacing='15' width='100%'>
							<tr>
								<td class='tborder' style='width: 45%'>
									<span class='smalltext' style='font-weight: bold'>
										".EASYSHOP_GENPREF_98."
									</span>
								</td>
								<td class='tborder' style='width: 55%'>
									<select class='tbox' name='num_main_category_columns'>
										<option value='1' ".($num_main_category_columns == 1 ? "selected='selected'" : "").">1</option>
										<option value='2' ".($num_main_category_columns == 2 ? "selected='selected'" : "").">2</option>
										<option value='3' ".($num_main_category_columns == 3 ? "selected='selected'" : "").">3</option>
										<option value='4' ".($num_main_category_columns == 4 ? "selected='selected'" : "").">4</option>
										<option value='5' ".($num_main_category_columns == 5 ? "selected='selected'" : "").">5</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class='tborder' style='width: 45%'>
									<span class='smalltext' style='font-weight: bold'>
										".EASYSHOP_GENPREF_99."
									</span>
								</td>
								<td class='tborder' style='width: 55%'>
									<input class='tbox' size='3' type='text' name='main_categories_per_page' value='$main_categories_per_page' />
								</td>
							</tr>
							<tr>
                <td><hr/></td>
              </tr>
							<tr>
								<td class='tborder' style='width: 45%'>
									<span class='smalltext' style='font-weight: bold'>
										".EASYSHOP_CAT_11."
									</span>
								</td>
								<td class='tborder' style='width: 55%'>
									<select class='tbox' name='num_category_columns'>
										<option value='1' ".($num_category_columns == 1 ? "selected='selected'" : "").">1</option>
										<option value='2' ".($num_category_columns == 2 ? "selected='selected'" : "").">2</option>
										<option value='3' ".($num_category_columns == 3 ? "selected='selected'" : "").">3</option>
										<option value='4' ".($num_category_columns == 4 ? "selected='selected'" : "").">4</option>
										<option value='5' ".($num_category_columns == 5 ? "selected='selected'" : "").">5</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class='tborder' style='width: 45%'>
									<span class='smalltext' style='font-weight: bold'>
										".EASYSHOP_CAT_12."
									</span>
								</td>
								<td class='tborder' style='width: 55%'>
									<input class='tbox' size='3' type='text' name='categories_per_page' value='$categories_per_page' />
								</td>
							</tr>
							<tr>
                <td><hr/></td>
              </tr>
							<tr>
								<td class='tborder' style='width: 45%'>
									<span class='smalltext' style='font-weight: bold'>
										".EASYSHOP_CONF_ITM_02."
									</span>
								</td>
								<td class='tborder' style='width: 55%'>
									<select class='tbox' name='num_item_columns'>
										<option value='1' ".($num_item_columns == 1 ? "selected='selected'" : "").">1</option>
										<option value='2' ".($num_item_columns == 2 ? "selected='selected'" : "").">2</option>
										<option value='3' ".($num_item_columns == 3 ? "selected='selected'" : "").">3</option>
										<option value='4' ".($num_item_columns == 4 ? "selected='selected'" : "").">4</option>
										<option value='5' ".($num_item_columns == 5 ? "selected='selected'" : "").">5</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class='tborder' style='width: 45%'>
									<span class='smalltext' style='font-weight: bold'>
										".EASYSHOP_CONF_ITM_03."
									</span>
								</td>
								<td class='tborder' style='width: 55%'>
									<input class='tbox' size='3' type='text' name='items_per_page' value='$items_per_page' />
								</td>
							</tr>
						</table>
";
		
// Run the form with tabs
$tabs = new Tabs("easyshop_preferences");
  $tabs->start(EASYSHOP_GENPREF_01);
  echo $text1; // Shop contact info
  $tabs->end();

  $tabs->start(EASYSHOP_GENPREF_44);
  echo $text2; // Settings
  $tabs->end();

  $tabs->start(EASYSHOP_GENPREF_14);
  echo $text3; // PayPal Info
  $tabs->end();
  
if ($enable_ipn == '2') {
  $tabs->start(EASYSHOP_GENPREF_94);
  echo $text4; // Monitor Info
  $tabs->end();
}

  $tabs->start(EASYSHOP_GENPREF_97);
  echo $text5; // Presentation settings
  $tabs->end();

$text .= $tabs->run();

// Close the form with 'Apply Changes' button
	$text .= "
  <br />
  <center>
  	<input type='hidden' name='edit_preferences' value='1' />
  	<input class='button' type='submit' value='".EASYSHOP_GENPREF_28."' />
  </center>
  <br />
  </form>";

// Render the value of $text in a table.
$title = EASYSHOP_GENPREF_00;
$ns -> tablerender($title, $text);

require_once(e_ADMIN.'footer.php');
?>