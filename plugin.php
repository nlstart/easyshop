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

// if e107 is not running we won't run this plugin program
if( ! defined('e107_INIT')){ exit(); }

// determine the plugin directory as a global variable
global $PLUGINS_DIRECTORY;

// read the database names array of this plugin from the includes/config file
@include_once('includes/config.php'); // Sometimes require_once blanked out Plugin Manager
@include_once('easyshop_ver.php');

$eplug_folder = "easyshop";
// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');

$eplug_name = "EasyShop";
$eplug_version = THIS_VERSION; // Defined in easyshop_ver.php
$eplug_author = "nlstart";
$eplug_url = EASYSHOP_URL;
$eplug_email = "nlstart@users.sourceforge.net";
$eplug_description = EASYSHOP_DESC;
$eplug_compatible = "e107v0.7+";
$eplug_compliant = TRUE;
$eplug_readme = "readme.txt";

//$eplug_folder = "easyshop";
$eplug_menu_name = "easyshop";
$eplug_conffile = "admin_config.php";
$eplug_icon = $eplug_folder."/images/logo_32.png";
$eplug_icon_small = $eplug_folder."/images/logo_16.png";
$eplug_caption = EASYSHOP_CAPTION;
$eplug_status = TRUE;

// List of preferences
// this stores a default value(s) in the preferences. 0 = Off , 1= On
// Preferences are saved with plugin folder name as prefix to make preferences unique and recognisable
$eplug_prefs = array(
		"easyshop_1" => 0
);

// List of table names -----------------------------------------------------------------------------------------------
$eplug_sql = file_get_contents(e_PLUGIN."{$eplug_folder}/{$eplug_folder}_sql.php");
preg_match_all("/CREATE TABLE (.*?)\(/i", $eplug_sql, $matches);
$eplug_table_names   = $matches[1];

// List of sql requests to create tables -----------------------------------------------------------------------------
// Apply create instructions for every table you defined in locator_sql.php --------------------------------------
// MPREFIX must be used because database prefix can be customized instead of default e107_
$eplug_tables = explode(";", str_replace("CREATE TABLE ", "CREATE TABLE ".MPREFIX, $eplug_sql));
for ($i=0; $i<count($eplug_tables); $i++) {
   $eplug_tables[$i] .= ";";
}
array_pop($eplug_tables); // Get rid of last (empty) entry

// Add pre-defined Shop Preferences into the plugin table array
array_push($eplug_tables,
"INSERT INTO ".MPREFIX."easyshop_preferences (`store_id`, `store_name`, `support_email`, `store_address_1`, `store_address_2`, `store_city`, `store_state`, `store_zip`, `store_country`, `store_welcome_message`, `store_info`, `store_image_path`, `num_category_columns`, `categories_per_page`, `num_item_columns`, `items_per_page`, `paypal_email`, `popup_window_height`, `popup_window_width`, `cart_background_color`, `thank_you_page_title`, `thank_you_page_text`, `thank_you_page_email`, `payment_page_style`, `payment_page_image`, `add_to_cart_button`, `view_cart_button`, `sandbox`, `set_currency_behind`, `minimum_amount`, `always_show_checkout`, `email_order`, `product_sorting`, `page_devide_char`, `icon_width`, `cancel_page_title`, `cancel_page_text`, `enable_comments`, `show_shopping_bag`, `print_shop_address`, `print_shop_top_bottom`, `print_discount_icons`, `shopping_bag_color`, `enable_ipn`, `enable_number_input`, `print_special_instr`, `email_info_level`, `email_additional_text`, `monitor_clean_shop_days`, `monitor_clean_check_days`, `num_main_category_columns`, `main_categories_per_page`, `paypal_primary_email`) VALUES
(1, 'My EasyShop', 'support@yourdomain.com', '1 Some St.', 'Unit 3', 'Some Town', 'OR', '01234', 'USA', 'Thank you for visiting our shop online. We have many products on sale at the moment, make sure you check them out.<br /><br />If you have any questions about our products, please feel free to e-mail us.', '', 'images/', 3, 25, 3, 25, 'someone@somewhere.com', '', '', '', 'Thank you for shopping with us', 'Your transaction has been completed, and a receipt for your purchase has been e-mailed to you.', '', 'custom_payment_page', '', '', '', 1, '0', 0, '0', '0', '', '', 0, 'Sorry', 'Your transaction failed or was canceled. Please inform the webmaster of this website in case you tried to purchase products from our shop.', '0', '0', '0', '0', '0', '0', 0, '0', '0', '0', '', '3', '7', '3', '25', '');"
);

// Create a link in main menu (yes=TRUE, no=FALSE)
$eplug_link = TRUE;
$eplug_link_name = 'EASYSHOP_LINKNAME'; // Store define value for multi-language purposes
$eplug_link_url = $PLUGINS_DIRECTORY.$eplug_folder."/easyshop.php";
$eplug_done = EASYSHOP_DONE1." ".$eplug_name." v".$eplug_version." ".EASYSHOP_DONE2;

// Upgrading from 1.61 to 1.7
$upgrade_add_prefs = "";
$upgrade_remove_prefs = "";
$upgrade_alter_tables = array(
"ALTER TABLE ".MPREFIX."easyshop_items ADD download_datasheet int(11) NOT NULL default '0' AFTER item_minimum;",
"ALTER TABLE ".MPREFIX."easyshop_items ADD download_datasheet_filename varchar(200) NOT NULL default '' AFTER download_datasheet;",
"ALTER TABLE ".MPREFIX."easyshop_items ADD item_quotation int(11) NOT NULL default '0' AFTER download_datasheet_filename"
);

// This separate function is useful as the plugin.php file is read on many occassions, 
// so this prevents upgrade only functionality from running when it shouldn't. 
if ( ! function_exists('easyshop_upgrade')) 
{  // The above line prevents the plugin from being declared twice
	function easyshop_upgrade() 
	{ // This function is executed by the e107 Plugin Manager before any upgrading action
		$path = e_PLUGIN.'easyshop/';
		if (file_exists($path.'easyshop_smtp.php'))
		{	// Remove redundant program easyshop_smtp.php
			@unlink($path.'easyshop_smtp.php');
		}
		if (file_exists($path.'admin_general_preferences_edit.php'))
		{	// Remove redundant program admin_general_preferences_edit.php
			@unlink($path.'admin_general_preferences_edit.php');
		}
		if (file_exists($path.'admin_categories_edit.php'))
		{	// Remove redundant program admin_categories_edit.php
			@unlink($path.'admin_categories_edit.php');
		}
		if (file_exists($path.'admin_config_edit.php'))
		{	// Remove redundant program admin_config_edit.php
			@unlink($path.'admin_config_edit.php');
		}
		if (file_exists($path.'admin_main_categories_edit.php'))
		{	// Remove redundant program admin_main_categories_edit.php
			@unlink($path.'admin_main_categories_edit.php');
		}
	} 
}
 
$eplug_upgrade_done = EASYSHOP_DONE3." ".$eplug_name." v".$eplug_version.".";
?>