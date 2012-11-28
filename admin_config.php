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
// Include userclass_class.php which is necessary for function r_userclass
require_once(e_HANDLER.'userclass_class.php');

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
// include define tables info
require_once('includes/config.php');

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_01';

// Load the EasyShop class for pagination
require_once('easyshop_class.php');

// Check query
if(e_QUERY){
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$action_id = $tmp[1];
	$offset_id = $tmp[2]; // Used for page offset
	unset($tmp);
}


require_once(e_HANDLER.'userclass_class.php');
require_once(e_ADMIN.'auth.php');
require_once('includes/config.php');
require_once('easyshop_class.php');

// Keep the active menu option for admin_menu.php (when showing errors on prices)
$pageid = 'admin_menu_01';

function tokenizeArray($array) {
    unset($GLOBALS['tokens']);
    $delims = "~";
    $word = strtok( $array, $delims );
    while ( is_string( $word ) ) {
        if ( $word ) {
            global $tokens;
            $tokens[] = $word;
        }
        $word = strtok ( $delims );
    }
}

// Solve some MySQL 5 STRICT_TRANS_TABLES compliancy issues
if ($_POST['item_price'] == "") {
  $_POST['item_price'] = "0.00";
}
if ($_POST['shipping_first_item'] == "") {
  $_POST['shipping_first_item'] = "0.00";
}
if ($_POST['shipping_additional_item'] == "") {
  $_POST['shipping_additional_item'] = "0.00";
}
if ($_POST['handling_override'] == "") {
  $_POST['handling_override'] = "0.00";
}

// Clean the item_image array from empty elements
General::Array_Clean("",$_POST['item_image']);
// Merge the posted image names together
$_POST['item_image'] = implode(",", $_POST['item_image']);

//-----------------------------------------------------------------------------+
//---------------------- Handle file upload -----------------------------------+
//-----------------------------------------------------------------------------+
if (isset($_POST['upload'])) {
	$pref['upload_storagetype'] = "1";
	require_once(e_HANDLER."upload_handler.php");
	$files = $_FILES['file_userfile'];
	foreach($files['name'] as $key => $name) {
		if ($files['size'][$key]) {
			$uploaded = file_upload($_POST['upload_dir'][$key]);
		}
	}
}
if (isset($message)) {
	$ns->tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
  header("Location: ".e_SELF);
  exit();
}

//-----------------------------------------------------------------------------+
//----------------------- Add new product -------------------------------------+
//-----------------------------------------------------------------------------+
if ($_POST['add_item'] == '1') {
    // Add new Product

  // Check: name is mandatory
  if ($tp->toDB($_POST['item_name']) == "") {
     $text .= EASYSHOP_CONFEDIT_ITM_10."<br />";
  }
  // First check on valid pricing
  if (General::validateDecimal($tp->toDB($_POST['item_price']))) {
      // This is a valid price with 2 decimals
      } else {
      // invalid price alert
      $text .= EASYSHOP_CONFEDIT_ITM_04."<br />";
      }
  // Check Shipping cost for first product, too
  if (General::validateDecimal($tp->toDB($_POST['shipping_first_item']))) {
      // This is a valid price with 2 decimals
      } else {
      // invalid price alert
      $text .= EASYSHOP_CONFEDIT_ITM_05."<br />";
      }
  // Check Shipping cost for each additional product, too
  if (General::validateDecimal($tp->toDB($_POST['shipping_additional_item']))) {
      // This is a valid price with 2 decimals
      } else {
      // invalid price alert
      $text .= EASYSHOP_CONFEDIT_ITM_06."<br />";
      }
  // Check Handling cost, too
  if (General::validateDecimal($tp->toDB($_POST['handling_override']))) {
      // This is a valid price with 2 decimals
      } else {
      // invalid price alert
      $text .= EASYSHOP_CONFEDIT_ITM_07."<br />";
      }
      
      if ($text <> "") {
      $text .= "<br /><span style='text-align:center;'><input class='button' type=button value='".EASYSHOP_CONFEDIT_ITM_08."' onClick='history.go(-1)'></span>";
     	// Render the value of $text in a table.
      $title = EASYSHOP_CONFEDIT_ITM_09;
      $ns -> tablerender($title, $text);
      require_once(e_ADMIN.'footer.php');
      // Leave on error
      exit();
      }

    // Checkboxes will only post values if they are checked
    if (isset($_POST['item_active_status'])) {
        $item_active_status = 2;
    } else {
        $item_active_status = 1;
    }
    if (isset($_POST['item_track_stock'])) {
        $item_track_stock = 2;
    } else {
        $item_track_stock = 1;
    }
    if (isset($_POST['download_product'])) {
        $download_product = 2;
    } else {
        $download_product = 1;
    }
    if (isset($_POST['download_datasheet'])) {
        $download_datasheet = 2;
    } else {
        $download_datasheet = 1;
    }	
    if (isset($_POST['item_quotation'])) {
        $item_quotation = 2;
    } else {
        $item_quotation = 1;
    }	
    // Actual database insert of new product
    $sql -> db_Insert(DB_TABLE_SHOP_ITEMS,
    "0,
		'".intval($_POST['category_id'])."',
		'".$tp->toDB($_POST['item_name'])."',
		'".$tp->toDB($_POST['item_description'])."',
		'".$tp->toDB($_POST['item_price'])."',
		'".$tp->toDB($_POST['sku_number'])."',
		'".$tp->toDB($_POST['shipping_first_item'])."',
		'".$tp->toDB($_POST['shipping_additional_item'])."',
		'".$tp->toDB($_POST['handling_override'])."',
		'".$tp->toDB($_POST['item_image'])."',
    '".intval($tp->toDB($item_active_status))."',
		1,
    '',
		1,
    '".intval($_POST['prod_prop_1_id'])."',
    '".$tp->toDB($_POST['prod_prop_1_list'])."',
    '".intval($_POST['prod_prop_2_id'])."',
    '".$tp->toDB($_POST['prod_prop_2_list'])."',
    '".intval($_POST['prod_prop_3_id'])."',
    '".$tp->toDB($_POST['prod_prop_3_list'])."',
    '".intval($_POST['prod_prop_4_id'])."',
    '".$tp->toDB($_POST['prod_prop_4_list'])."',
    '".intval($_POST['prod_prop_5_id'])."',
    '".$tp->toDB($_POST['prod_prop_5_list'])."',
    '".intval($_POST['prod_discount_id'])."',
    '".intval($_POST['item_instock'])."',
    '".intval($item_track_stock)."',
    '".intval($download_product)."',
    '".$tp->toDB($_POST['download_filename'])."',
    '".intval($_POST['prod_promo_class'])."',
    '".intval($_POST['item_minimum'])."',
    '".intval($download_datasheet)."',
    '".$tp->toDB($_POST['download_datasheet_filename'])."',
    '".intval($item_quotation)."'
    ");

    // Determine last inserted record to get the item_id with mysql_insert_id()
    // For extra safety: combine item id number and download filename
    // (this way it is also possible to offer multiple downloads with the same name without problems)
    if ($download_product == 2 && strlen(trim($_POST['download_filename'])) > 0 ) {
      // Rename the download file name to scrambled file name
      $scrambled_name = intval(mysql_insert_id()).$_POST['download_filename'];
      rename(e_PLUGIN."easyshop/downloads/".$_POST['download_filename'], e_PLUGIN."easyshop/downloads/".md5($scrambled_name));
    }
    header("Location: ".e_SELF);
    exit();

} else if ($_POST['item_dimensions'] == '1') {
    $sql->db_Update(DB_TABLE_SHOP_PREFERENCES,
    "items_per_page='".intval($tp->toDB($_POST['items_per_page']))."',
     num_item_columns='".intval($tp->toDB($_POST['num_item_columns']))."'
  	 WHERE
  	 store_id='1'");
    header("Location: ".e_SELF);
    exit();

} else if ($_POST['change_order'] == '1') {
    // Change item order
    for ($x = 0; $x < count($_POST['item_order']); $x++) {
        tokenizeArray($_POST['item_order'][$x]);
        $newItemOrderArray[$x] = $tokens;
    }

    for ($x = 0; $x < count($newItemOrderArray); $x++) {
        $sql -> db_Update(DB_TABLE_SHOP_ITEMS,
            "item_order='".intval($tp->toDB($newItemOrderArray[$x][1]))."'
            WHERE item_id='".intval($tp->toDB($newItemOrderArray[$x][0]))."'");
    }

    // Change item active status
    $sql2 = new db;
    $sql2 -> db_Update(DB_TABLE_SHOP_ITEMS,
			"item_active_status='1'
			WHERE category_id='".intval($tp->toDB($_POST['category_id']))."'");

    foreach ($_POST['item_active_status'] as $value) {
    	$sql2 -> db_Update(DB_TABLE_SHOP_ITEMS,
				"item_active_status='2'
				WHERE item_id='".intval($tp->toDB($value))."'");
    }

    // Change item 'Out Of Stock' status
    $sql3 = new db;
    $sql3 -> db_Update(DB_TABLE_SHOP_ITEMS,
          "item_out_of_stock='1'
	  	     WHERE category_id='".intval($tp->toDB($_POST['category_id']))."'");

    foreach ($_POST['item_out_of_stock'] as $value) {
    	$sql3 -> db_Update(DB_TABLE_SHOP_ITEMS,
            "item_out_of_stock='2'
             WHERE item_id='".intval($tp->toDB($value))."'");
    }

    // Change item 'Out Of Stock' explanation
    $sql4 = new db;
    foreach ($_POST['item_out_of_stock_explanation'] as $key => $value) {
      $sql4 -> db_Update(DB_TABLE_SHOP_ITEMS,
            "item_out_of_stock_explanation='".$tp->toDB($value)."'
             WHERE item_id='".intval($tp->toDB($key))."'");
    }

    header("Location: ".e_SELF);
    exit();
} else if ($_POST['edit_item'] == '2') {
//-----------------------------------------------------------------------------+
//----------------------- Edit existing product -------------------------------+
//-----------------------------------------------------------------------------+
  // Pushed 'Apply Changes' button on Edit Product
  // Check: name is mandatory
  if ($tp->toDB($_POST['item_name']) == "") {
     $text .= EASYSHOP_CONFEDIT_ITM_10."<br />";
  }
  // First check on valid pricing
  if (General::validateDecimal($tp->toDB($_POST['item_price']))) {
      // This is a valid price with 2 decimals
      } else {
      // invalid price alert
      $text .= EASYSHOP_CONFEDIT_ITM_04."<br />";
      }
  // Check Shipping cost for first product, too
  if (General::validateDecimal($tp->toDB($_POST['shipping_first_item']))) {
      // This is a valid price with 2 decimals
      } else {
      // invalid price alert
      $text .= EASYSHOP_CONFEDIT_ITM_05."<br />";
      }
  // Check Shipping cost for each additional product, too
  if (General::validateDecimal($tp->toDB($_POST['shipping_additional_item']))) {
      // This is a valid price with 2 decimals
      } else {
      // invalid price alert
      $text .= EASYSHOP_CONFEDIT_ITM_06."<br />";
      }
  // Check Handling cost, too
  if (General::validateDecimal($tp->toDB($_POST['handling_override']))) {
      // This is a valid price with 2 decimals
      } else {
      // invalid price alert
      $text .= EASYSHOP_CONFEDIT_ITM_07."<br />";
      }

  if ($text <> "") {
      $text .= "<br /><span style='text-align:center;'><input class='button' type=button value='".EASYSHOP_CONFEDIT_ITM_08."' onClick='history.go(-1)'></span>";
     	// Render the value of $text in a table.
      $title = EASYSHOP_CONFEDIT_ITM_09;
      $ns -> tablerender($title, $text);
      require_once(e_ADMIN.'footer.php');
      // Leave on error
      exit();
      }

    // Checkboxes will only post values if they are checked
    if (isset($_POST['item_active_status'])) {
        $item_active_status = 2;
    } else {
        $item_active_status = 1;
    }
    if (isset($_POST['item_track_stock'])) {
        $item_track_stock = 2;
    } else {
        $item_track_stock = 1;
    }
    if (isset($_POST['item_quotation'])) {
        $item_quotation = 2;
    } else {
        $item_quotation = 1;
    }
    if (isset($_POST['download_product'])) {
        $download_product = 2;
        if (strlen(trim($_POST['stored_download_filename'])) > 0) {
          $_POST['download_filename'] = $_POST['stored_download_filename'];
        }
    } else {
        $download_product = 1;
        $_POST['download_filename'] = "";
    }
    if (isset($_POST['download_datasheet'])) {
        $download_datasheet = 2;
        if (strlen(trim($_POST['download_datasheet_filename'])) > 0) {
          $_POST['download_datasheet_filename'] = $_POST['download_datasheet_filename'];
        }
    } else {
        $download_datasheet = 1;
        $_POST['download_datasheet_filename'] = "";
    }
    $sql -> db_Update(DB_TABLE_SHOP_ITEMS,
        "category_id              = '".intval($_POST['category_id'])."',
        item_name                 = '".$tp->toDB($_POST['item_name'])."',
        item_description          = '".$tp->toDB($_POST['item_description'])."',
        sku_number                = '".$tp->toDB($_POST['sku_number'])."',
        item_price                = '".$tp->toDB($_POST['item_price'])."',
        shipping_first_item       = '".$tp->toDB($_POST['shipping_first_item'])."',
        shipping_additional_item  = '".$tp->toDB($_POST['shipping_additional_item'])."',
        handling_override         = '".$tp->toDB($_POST['handling_override'])."',
        item_image                = '".$tp->toDB($_POST['item_image'])."',
        item_active_status        = '".intval($item_active_status)."',
        prod_prop_1_id            = '".intval($_POST['prod_prop_1_id'])."',
        prod_prop_1_list          = '".$tp->toDB($_POST['prod_prop_1_list'])."',
        prod_prop_2_id            = '".intval($_POST['prod_prop_2_id'])."',
        prod_prop_2_list          = '".$tp->toDB($_POST['prod_prop_2_list'])."',
        prod_prop_3_id            = '".intval($_POST['prod_prop_3_id'])."',
        prod_prop_3_list          = '".$tp->toDB($_POST['prod_prop_3_list'])."',
        prod_prop_4_id            = '".intval($_POST['prod_prop_4_id'])."',
        prod_prop_4_list          = '".$tp->toDB($_POST['prod_prop_4_list'])."',
        prod_prop_5_id            = '".intval($_POST['prod_prop_5_id'])."',
        prod_prop_5_list          = '".$tp->toDB($_POST['prod_prop_5_list'])."',
        prod_discount_id          = '".intval($_POST['prod_discount_id'])."',
        item_track_stock          = '".intval($item_track_stock)."',
        item_instock              = '".intval($_POST['item_instock'])."',
        download_product          = '".intval($download_product)."',
        download_filename         = '".$tp->toDB($_POST['download_filename'])."',
		prod_promo_class		  = '".intval($_POST['prod_promo_class'])."',
		item_minimum		  	  = '".intval($_POST['item_minimum'])."',
        download_datasheet        = '".intval($download_datasheet)."',
        download_datasheet_filename= '".$tp->toDB($_POST['download_datasheet_filename'])."',
        item_quotation			  = '".intval($item_quotation)."'
        WHERE item_id             = '".intval($tp->toDB($_POST['item_id']))."'"); // or die (mysql_error());

        // For extra safety: combine item id number and download filename
        // (this way it is also possible to offer multiple downloads with the same name without problems)
        if ($download_product == 2 && strlen(trim($_POST['download_filename'])) > 0 ) {
          // Rename the download file name to scrambled file name
          $scrambled_name = intval($_POST['item_id']).$_POST['download_filename'];
          rename(e_PLUGIN."easyshop/downloads/".$_POST['download_filename'], e_PLUGIN."easyshop/downloads/".md5($scrambled_name));
        }
        if ($download_product == 1 && strlen(trim($_POST['stored_download_filename'])) > 0) {
          // Rename scrambled file name back to original name
          $scrambled_name = intval($_POST['item_id']).$_POST['stored_download_filename'];
          rename(e_PLUGIN."easyshop/downloads/".md5($scrambled_name), e_PLUGIN."easyshop/downloads/".$_POST['stored_download_filename']);
        }
        header("Location: ".e_SELF."?cat.".$_POST['category_id']);
        exit();

} else if ($_GET['delete_item'] == '1') {
	// Verify deletion
    $text = "
    <br /><br />
    <div style='text-align:center;'>
        ".EASYSHOP_CONFEDIT_ITM_01."
        <br /><br />
        <table width='100'>
            <tr>
                <td>
                    <a href='".e_SELF."?delete_item=2&item_id=".intval($_GET['item_id'])."&category_id=".intval($_GET['category_id'])."'>".EASYSHOP_CONFEDIT_ITM_02."</a>
                </td>
                <td>
                    <a href='".e_SELF."?cat.".intval($_GET['category_id'])."'>".EASYSHOP_CONFEDIT_ITM_03."</a>
                </td>
            </tr>
        </table>
    </div>";

    // Render the value of $text in a table.
    $title = "<b>".EASYSHOP_CONFEDIT_ITM_00."</b>";
    $ns -> tablerender($title, $text);
    //*/

} else if ($_GET['delete_item'] == '2') {
	// Delete item from tables when delete_item = 2 (user selected Yes to delete)
	$itemId = intval($tp->toDB($_GET['item_id']));
	// Retrieve download filename info from the product
	$sql -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_id=".$itemId);
	if ($row = $sql-> db_Fetch()){
		$download_product = $row['download_product'];
		$download_filename = $row['download_filename'];
	}
	if ($download_product == 2 && strlen($download_filename) > 0 ) {
    // Rename scrambled file name back to original name before deletion
    $scrambled_name = intval($itemId).$download_filename;
    rename(e_PLUGIN."easyshop/downloads/".md5($scrambled_name), e_PLUGIN."easyshop/downloads/".$download_filename);
	}
  // Actually delete the product
  $sql -> db_Delete(DB_TABLE_SHOP_ITEMS, "item_id=".$itemId);
  header("Location: ".e_SELF."?cat.".intval($_GET['category_id']));
  exit();
}

//-----------------------------------------------------------------------------+
//---------------------- Get and Set Defaults ---------------------------------+
//-----------------------------------------------------------------------------+
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

// Build array with all images to choose from
require_once(e_HANDLER.'file_class.php');
$fl = new e_file;
if($image_array = $fl->get_files(e_PLUGIN."easyshop/".$store_image_path, ".gif|.jpg|.png|.GIF|.JPG|.PNG","standard",2)){
	sort($image_array);
}
if ($icon_width == '' OR $icon_width < 1) {$icon_width = 16;} // Default of icon width is 16 pixels width

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

// Set presentation defaults
if ($num_category_columns == '') { ($num_category_columns =  3); }
if ($categories_per_page  == '') { ($categories_per_page  = 25);}
if ($num_item_columns     == '') { ($num_item_columns     =  3); }
if ($items_per_page       == '') { ($items_per_page       = 25);}

// Define actual currency and position of currency character once
$sql -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "currency_active=2");
while($row = $sql-> db_Fetch()){
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

//-----------------------------------------------------------------------------+
//-------------------- Display Selected Category ------------------------------+
//-----------------------------------------------------------------------------+
if ($action == "cat") {
//if ($_GET['choose_category'] == 1) {
    // Check if there are no products in a category
    if($sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_id=".$action_id) > 0) {
		$no_items = 1;
    }
		
	// Check if there are no active products in a category
    // item_active_status = 1 --> active 'off'
    // item_active_status = 2 --> active 'on'
    if($sql -> db_Count(DB_TABLE_SHOP_ITEMS, '(*)', 'WHERE category_id='.$action_id.' AND item_active_status = 2') == 0) {
		$no_active_items = 1;
		}

/*
	$sql -> db_Select(DB_TABLE_SHOP_ITEMS);
	while($row = $sql-> db_Fetch()){
		$category_id = $row['category_id'];
		$item_id = $row['item_id'];
		$item_name = $row['item_name'];
		$item_description = $row['item_description'];
		$item_image = $row['item_image'];
		$item_active_status = $row['item_active_status'];
		$item_out_of_stock = $row['item_out_of_stock'];
		$item_out_of_stock_explanation = $row['item_out_of_stock_explanation'];
		$item_order = $row['item_order'];
		$item_price = number_format($row['item_price'], 2, '.', '');
	}
*/

	$sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_id=".$action_id);
	while($row = $sql-> db_Fetch()){
		$category_name = $row['category_name'];
		$category_main_id = $row['category_main_id'];
	}
	
  $text .= "
	<form id='cat' method='post' action='".e_SELF."'>
		<div style='text-align:center;'>
				<fieldset>
					<legend>
						<a href='".e_SELF."'>".EASYSHOP_CONF_ITM_00."</a> &raquo; $category_name
					</legend>";
          // Display an error message if there are no records for the Product Category
					if ($no_items == null) {
						$text .= "
						<br />
						<div style='text-align:center;'>
							<span class='smalltext'>
                ".EASYSHOP_CONF_ITM_25."
							</span>
						</div>
						<br />";
					} else {
						$text .= "
						<br />
						<div style='text-align:center;'>
						  <table style='".ADMIN_WIDTH."' class='fborder'>
								<tr>
									<td class='fcaption'><b>".EASYSHOP_CONF_ITM_15."</b></td>
									<td class='fcaption'><b>".EASYSHOP_CONF_ITM_06."</b></td>
									<td class='fcaption'><span style='text-align:center;'><b>".EASYSHOP_CONF_ITM_10."</b></span></td>
									<td class='fcaption'><span style='text-align:center;'><b>".EASYSHOP_CONF_ITM_18."</b></span></td>
									<td class='fcaption'><span style='text-align:center;'><b>".EASYSHOP_CONF_ITM_19."</b></span></td>
									<td class='fcaption'><span style='text-align:center;'><b>".EASYSHOP_CONF_ITM_20."</b></span></td>
									<td class='fcaption'><span style='text-align:center;'><b>".EASYSHOP_CONF_ITM_27."</b></span></td>
								</tr>";
								
								$sql -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "category_id=".$action_id." ORDER BY item_order");
								while($row = $sql-> db_Fetch()){
									$text .= "
									<tr>
										<td class='forumheader3' valign='top'>";
										
										if ($row['item_image'] == '') {
											$text .= "
											&nbsp;";
										} else {
                  		$item_image = explode(",",$row['item_image']);
                  		$arrayLength = count($item_image);
                  		// Only show the first item_image
											$text .= "<img src='$store_image_path".$item_image[0]."' alt='".$item_image[0]."' title='".$item_image[0]."' />";
                      if ($arrayLength > 1) { // Display number of images if there are multiple images
                      $text .= "<br />".EASYSHOP_CONF_ITM_44.": $arrayLength";
                      }
										}
										if ($row['prod_promo_class'] <> 255 && $row['prod_promo_class'] <> 0)
										{
											$text .= "<br /><img src='".e_PLUGIN."easyshop/images/userclass_16.png' alt='".EASYSHOP_CONF_ITM_46."' title='".EASYSHOP_CONF_ITM_46."'/>".r_userclass_name($row['prod_promo_class']);
										}
										$text .= "
										</td>
										<td class='forumheader3' valign='top'>
											".$row['item_name']."
										</td>
										<td class='forumheader3' valign='top'>
											".number_format($row['item_price'], 2, '.', '')."
										</td>
										<td class='forumheader3' valign='top'>
											<div style='text-align:center;'>";
											
											if ($row['item_active_status'] == 2) {
												$text .= "
												<input class='tbox' type='checkbox' name='item_active_status[]' value='".$row['item_id']."' checked='checked' />";
											} else {
												$text .= "
												<input class='tbox' type='checkbox' name='item_active_status[]' value='".$row['item_id']."' />";
											}
											$text .= "
											</div>
										</td>
										<td class='forumheader3' valign='top'>
											<div style='text-align:center;'>";
											
											if ($row['item_out_of_stock'] == 2) {
												$text .= "
												<input class='tbox' type='checkbox' name='item_out_of_stock[]' value='".$row['item_id']."' checked='checked' />";
											} else {
												$text .= "
												<input class='tbox' type='checkbox' name='item_out_of_stock[]' value='".$row['item_id']."' />";
											}
											$text .= "
												<br />
												<b>".EASYSHOP_CONF_ITM_21.":<b>
												<br />
												<textarea class='tbox' cols='20' rows='3' name='item_out_of_stock_explanation[".$row['item_id']."]'>".$row['item_out_of_stock_explanation']."</textarea>
											</div>
										</td>
										<td class='forumheader3' valign='top'>
											<div style='text-align:center;'>
						                        <select class='tbox' name='item_order[]'>";
						
						                        $sql2 = new db;
						                        $num_rows = $sql2 -> db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE category_id=".$action_id);
						                        $count = 1;
						                        while ($count <= $num_rows) {
						                            if ($row['item_order'] == $count) {
						                                $text .= "
						                                <option value='".$row['item_id']."~".$count."' selected='selected'>".$count."</option>";
						                            } else {
						                                $text .= "
						                                <option value='".$row['item_id']."~".$count."'>".$count."</option>";
						                            }
						                        $count++;
						                        }
						                        $text .= "
						                        </select>";
						
						                    $text .= "
						                    </div>
										</td>
										<td class='forumheader3' valign='top'>
											<div style='text-align:center;'>
											<a href='".e_SELF."?edit_item=1&item_id=".$row['item_id']."&category_id=".$action_id."' alt='".EASYSHOP_CONF_ITM_22."' title='".EASYSHOP_CONF_ITM_22."'>".ADMIN_EDIT_ICON."</a>
                      &nbsp;
											<a href='".e_SELF."?delete_item=1&item_id=".$row['item_id']."&category_id=".$action_id."' alt='".EASYSHOP_CONF_ITM_23."' title='".EASYSHOP_CONF_ITM_23."'>".ADMIN_DELETE_ICON."</a>";

											if ($row['item_active_status'] == 2) { // Show link to Shop Front Page if product is active
                        $text .= "
                        &nbsp;
  											<a href='easyshop.php?prod.".$row['item_id']."' alt='".EASYSHOP_CONF_ITM_32."' title='".EASYSHOP_CONF_ITM_32."'><img src='".e_PLUGIN."easyshop/images/arrowup_16.gif' alt='' /></a>";
                      }
                      
											$text .= "
											</div>
										</td>
									</tr>";
								}
								
							$text .= "
							</table>
						</div>
						<br />
						<div style='text-align:center;'>
							<input type='hidden' name='category_id' value='".$action_id."'>
							<input type='hidden' name='change_order' value='1'>
							<input class='button' type='submit' value='".EASYSHOP_CONF_ITM_04."'>
						</div>
						<br />";
            // Alert if there are no active products in the category
            if ($no_active_items == 1) {
							$text .= "<img src='".e_IMAGE."admin_images/docs_16.png' title='' alt='' /> ".EASYSHOP_CONF_ITM_26;
            }

					}
				$text .= "
				</fieldset>
		</div>
	</form>";
	// Render the value of $text in a table.
	$title = EASYSHOP_CONF_ITM_24;
	$ns -> tablerender($title, $text);
} else if ($_GET['edit_item'] == 1) {
  //---------------------------------------------------------------------------+
  //-------------------------- Edit Existing Product --------------------------+
  //---------------------------------------------------------------------------+
	$sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_id=".$_GET['category_id']);
	while($row = $sql-> db_Fetch()){
	    $category_name = $row['category_name'];
	}
	// IPN addition - to pass $item_track_stock through to product array
    global $item_track_stock;
    global $item_instock;
    
	$sql -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_id=".$_GET['item_id']);
	if ($row = $sql-> db_Fetch()){
		$category_id = $row['category_id'];
		$item_id = $row['item_id'];
		$item_name = $row['item_name'];
		$item_description = $row['item_description'];
		$item_price = number_format($row['item_price'], 2, '.', '');
		$sku_number = $row['sku_number'];
		$shipping_first_item = number_format($row['shipping_first_item'], 2, '.', '');
		$shipping_additional_item = number_format($row['shipping_additional_item'], 2, '.', '');
		$handling_override = number_format($row['handling_override'], 2, '.', '');
		$item_image = $row['item_image'];
		$item_active_status = $row['item_active_status'];
		$item_order = $row['item_order'];
		$prod_prop_1_id = $row['prod_prop_1_id'];
		$prod_prop_2_id = $row['prod_prop_2_id'];
		$prod_prop_3_id = $row['prod_prop_3_id'];
		$prod_prop_4_id = $row['prod_prop_4_id'];
		$prod_prop_5_id = $row['prod_prop_5_id'];
		$prod_discount_id  = $row['prod_discount_id'];
		$item_instock = $row['item_instock'];      // IPN addition - include extra fields for stock
		$item_track_stock = $row['item_track_stock'];
		$download_product = $row['download_product'];
		$download_filename = $row['download_filename'];
		$prod_promo_class = $row['prod_promo_class'];
		$item_minimum = $row['item_minimum'];
		$download_datasheet = $row['download_datasheet'];
		$download_datasheet_filename = $row['download_datasheet_filename'];
		$item_quotation = $row['item_quotation'];
	}

	$text .= "
	 <form id='prod' enctype='multipart/form-data' method='post' action='".e_SELF."'>
		<div style='text-align:center; width:80%'>
			<fieldset>
				<legend>
					<a href='".e_SELF."'>".EASYSHOP_CONF_ITM_00."</a> &raquo; <a href='".e_SELF."?cat.".intval($_GET['category_id'])."'>$category_name</a> &raquo; ".EASYSHOP_CONF_ITM_22."
				</legend>";             
                    
	$text .= product_table($category_id, $item_id, $item_name, $item_description, $item_price, $sku_number, $shipping_first_item, $shipping_additional_item,
                         $handling_override, $item_image, $item_active_status, $item_order, $prod_prop_1_id, $prod_prop_2_id, $prod_prop_3_id,
                         $prod_prop_4_id, $prod_prop_5_id, $prod_discount_id, $image_array, $icon_width, $item_instock, $item_track_stock, $enable_ipn,
                         $download_product, $download_filename, $store_image_path, $prod_promo_class, $item_minimum, $download_datasheet, $download_datasheet_filename, $item_quotation);
                          
	$text .= "
			<br />
			<center>
				<input type='hidden' name='item_id' value='".intval($_GET['item_id'])."'>
				<input type='hidden' name='edit_item' value='2'>
				<input class='button' type='submit' value='".EASYSHOP_CONF_ITM_04."'>
			</center>
			<br />
			</fieldset>
		</div>
	</form>";	
	// Render the value of $text in a table.
	$title = EASYSHOP_CONF_GEN_01;
	$ns -> tablerender($title, $text);
}
  //---------------------------------------------------------------------------+
  //------------------------ Show Categories ----------------------------------+
  //---------------------------------------------------------------------------+
if($action == "" or $action == "catpage") {
	if($sql -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status = '2'") > 0) {
		$no_categories = 1;
	}

  // Determine the offset to display
  $category_offset = General::determine_offset($action,$action_id,$categories_per_page);

	$text .= "
		<div style='text-align:center; width:100%'>
			<fieldset>
				<legend>
					<b>".EASYSHOP_CONF_CAT_00."</b>
				</legend>
				<br />";
				if ($no_categories == null) {
					$text .= "
					<br />
					<div style='text-align:center;'>
						<span class='smalltext'>
							".EASYSHOP_CONF_CAT_01."
						</span>
					</div>
					<br />";
				} else {
					$text .= "
					<div style='text-align:center;'>
						<table border='0' cellspacing='15' width='100%'>";
							
							$text .= "
							<tr>";
							
							switch ($num_category_columns) {
								case 1:
									$column_width = '100%';
									break;
								case 2:
									$column_width = '50%';
									break;
								case 3:
									$column_width = '33%';
									break;
								case 4:
									$column_width = '25%';
									break;
								case 5:
									$column_width = '20%';
									break;
							}
							
							$count_rows = 0;
							$sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_active_status=2 ORDER BY category_order LIMIT $category_offset, $categories_per_page");
			while($row = $sql-> db_Fetch()){

								$text .= "
									<td width='$column_width'>
										<br />
										<div style='text-align:center;'>
											<a href='".e_SELF."?cat.".$row['category_id']."'><b>".$row['category_name']."</b></a>
											<br />";
									
											if ($row['category_image'] == '') {
												$text .= "
												&nbsp;";
											} else {
												$text .= "
												<a href='".e_SELF."?cat.".$row['category_id']."'><img src='$store_image_path".$row['category_image']."' /> <!-- height='100' width='80' /> --></a>
												";
											}
										
											$text .= "
											<br />
											".$tp->toHTML($row['category_description'], true)."
											<br /> ";

			  // Second query: Count the number of products in the category
			  $sql2 = new db;
								$prod_cat_count = $sql2 -> db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE category_id='".$row['category_id']."'");
			  // Third query: Count the number of inactive products in the category
			  $sql3 = new db;
								$prod_inact_cat_count = $sql3 -> db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE category_id='".$row['category_id']."' AND item_active_status='1'");
			  // Present total number of products
										$text .= "
										 ".EASYSHOP_CONF_CAT_04." ".$prod_cat_count." <br />";
			  // Present total of inactive products if there are any
			  if ($prod_inact_cat_count > 0) {
										$text .= "
				  ".EASYSHOP_CONF_CAT_05." ".$prod_inact_cat_count."
										";
			  }
			  // Keep number of returned lines the same to present categories 'aligned'
			  else {
				  $text .= "&nbsp;";
			  }

										$text .= "
										</div>
									</td>";
									$count_rows++;
									
								if ($count_rows == $num_category_columns) {
									$text .= "
									</tr>
									<tr>";
									$count_rows = 0;
								}
							}
							
						$text .= "
						</table>
					</div>
					<br />";
					
					$total_categories = $sql -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status=2");
					$action = "catpage"; // Set action hardcoded to catpage in order to view right links
					$text .= General::multiple_paging($total_categories,$categories_per_page,$action,$action_id,$page_id,$page_devide_char);
													
					$text .= "
					<br />";
				}
			$text .= "
			<img src='".e_IMAGE."admin_images/docs_16.png' title='' alt='' /> ".EASYSHOP_CONF_CAT_03."
			</fieldset>
		</div>
		<br />";

//-----------------------------------------------------------------------------+
//------------------------ New Product Entry ----------------------------------+
//-----------------------------------------------------------------------------+
	$text .= "
  <form id='add_item' enctype='multipart/form-data' method='post' action='".e_SELF."'>
		<div style='text-align:center; width:80%'>
			<fieldset>
				<legend>
					".EASYSHOP_CONF_ITM_00."
				</legend>";
				
		if($no_categories == null) {
					$text .= "
					<br />
					<div style='text-align:center;'>
						<span class='smalltext'>
							<a href='admin_categories.php'>".EASYSHOP_CONF_ITM_01."</a>
						</span>
					</div>
					<br />";
		} else {
            $text .= product_table($category_id, $item_id, $item_name, $item_description, $item_price, $sku_number, $shipping_first_item, $shipping_additional_item,
                                   $handling_override, $item_image, $item_active_status, $item_order, $prod_prop_1_id, $prod_prop_2_id, $prod_prop_3_id,
                                   $prod_prop_4_id, $prod_prop_5_id, $prod_discount_id, $image_array, $icon_width, $item_instock, $item_track_stock, $enable_ipn,
                                   $download_product, $download_filename, $store_image_path, $prod_promo_class, $item_minimum, $download_datasheet, $download_datasheet_filename, $item_quotation);

		$text .= "
					<br />
					<div style='text-align:center;'>
						<input type='hidden' name='add_item' value='1'>
						<input class='button' type='submit' value='".EASYSHOP_CONF_ITM_00."'>
					</div>
					<br />";
					}
		$text .= "
			</fieldset>
		</div>
	</form>";
	// Render the value of $text in a table.
	$title = EASYSHOP_CONF_GEN_01;
	$ns -> tablerender($title, $text);
}

function product_table($category_id, $item_id, $item_name, $item_description, $item_price, $sku_number, $shipping_first_item, $shipping_additional_item,
                       $handling_override, $item_image, $item_active_status, $item_order, $prod_prop_1_id, $prod_prop_2_id, $prod_prop_3_id,
                       $prod_prop_4_id, $prod_prop_5_id, $prod_discount_id, $image_array, $icon_width, $item_instock, $item_track_stock, $enable_ipn,
                       $download_product, $download_filename, $store_image_path, $prod_promo_class, $item_minimum, $download_datasheet, $download_datasheet_filename, $item_quotation) 
{
$text .= "
	<table border='0' cellspacing='15' width='100%'>
		<tr>
			<td>
				<b>".EASYSHOP_CONF_ITM_05.":</b>
			</td>
			<td>
				<select class='tbox' name='category_id'>";
            $sql2 = new db;
            $sql2 -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "WHERE category_active_status = '2' ORDER BY category_order", false); // Select only active categories
            while ($row2 = $sql2->db_Fetch()) {
            	if ($row2['category_id'] == $category_id) {
          			$text .= "
                    <option value='".$row2['category_id']."' selected='selected'>".$row2['category_name']."</option>";
            	} else {
          			$text .= "
                    <option value='".$row2['category_id']."'>".$row2['category_name']."</option>";
            	}
            }
        $text .= "
        </select>
			</td>
		</tr>
		<tr>
			<td>
				<b>".EASYSHOP_CONF_ITM_06.":</b>
			</td>
			<td>
				<input class='tbox' size='25' type='text' name='item_name' value='".$item_name."' />
			</td>
		</tr>
		<tr>
			<td valign='top'>
				<b>".EASYSHOP_CONF_ITM_07.":</b>
			</td>
			<td>
				<textarea class='tbox' cols='50' rows='7' name='item_description' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>".$item_description."</textarea><br />".display_help('helpa')."
			</td>
		</tr>
		<tr>
			<td>
				<b>".EASYSHOP_CONF_ITM_08.": <img src='".e_IMAGE."admin_images/docs_16.png' title='".EASYSHOP_CONF_ITM_09."' alt='".EASYSHOP_CONF_ITM_09."' /></b>
			</td>
			<td>
				<input class='tbox' size='25' type='text' name='sku_number' value='".$sku_number."' />
			</td>
		</tr>
		<tr>
			<td>
				<b>".EASYSHOP_CONF_ITM_10.":</b>
			</td>
			<td>
				<input class='tbox' size='7' type='text' name='item_price' value='".$item_price."' />
			</td>
		</tr>
		<tr>
			<td>
				<b>".EASYSHOP_CONF_ITM_11.":</b>
			</td>
			<td valign='top'>
				<input class='tbox' size='7' type='text' name='shipping_first_item' value='".$shipping_first_item."' />
			</td>
		</tr>
		<tr>
			<td colspan=2>
				<img src='".e_IMAGE."admin_images/docs_16.png' title='' alt='' /> ".EASYSHOP_CONF_ITM_12."
			</td>
		</tr>
		<tr>
			<td>
				<b>".EASYSHOP_CONF_ITM_13.":</b>
			</td>
			<td valign='top'>
				<input class='tbox' size='7' type='text' name='shipping_additional_item' value='".$shipping_additional_item."' />
			</td>
		</tr>
		<tr>
			<td colspan=2>
				<img src='".e_IMAGE."admin_images/docs_16.png' title='' alt='' /> ".EASYSHOP_CONF_ITM_12."
			</td>
		</tr>
		<tr>
			<td>
				<b>".EASYSHOP_CONF_ITM_14.":</b>
			</td>
			<td valign='top'>
				<input class='tbox' size='7' type='text' name='handling_override' value='".$handling_override."' />
			</td>
		</tr>
		<tr>";

    // Show all available images
	$item_image = explode(",",$item_image);
	$arrayLength = count($item_image);
    $j = 1;
	for ($i = 0; $i < $arrayLength; $i++){
  		$text .= "
  			<td valign='top'>
  				<b>".EASYSHOP_CONF_ITM_15." ".$j.":</b>
  			</td>
  			<td valign='top'>
				<input type='text' size='25' class='tbox' id='item_image".$i."' name='item_image[]' value='".$item_image[$i]."' /> ".EASYSHOP_CONF_ITM_16."<br />";
		// Show icons with width 16 of the array of images and put name in variable $category_image
		foreach($image_array as $icon){
            $text  .= "<a href=\"javascript:insertext('" . $icon['fname'] . "','item_image".$i."','itmimg')\"><img src='" . $icon['path'] . $icon['fname'] . "' style='border:0' alt='' width='".$icon_width."' /></a> ";
		}
		$text .= "</td></tr>";
		$j++;
	}
    // Add a blank input image field on top of the current list
    $j = $arrayLength + 1;
  		$text .= "
      		<td valign='top'>
  				<b>".EASYSHOP_CONF_ITM_15." ".$j.":</b>
  			</td>
  			<td valign='top'>
				<input type='text' size='25' class='tbox' id='item_image".$j."' name='item_image[]' value='".$item_image[$j]."' /> ".EASYSHOP_CONF_ITM_16."<br />";
		// Show icons with width 16 of the array of images and put name in variable $category_image
		foreach($image_array as $icon){
            $text  .= "<a href=\"javascript:insertext('" . $icon['fname'] . "','item_image".$j."','itmimg')\"><img src='" . $icon['path'] . $icon['fname'] . "' style='border:0' alt='' width='".$icon_width."' /></a> ";
		}
		$text .= "</td></tr>";

		// Show upload button
		$imgdirname = e_PLUGIN."easyshop/".$store_image_path;
  		$text .= "<tr><td></td><td><br /><input class=\"button\" type=\"button\" name=\"request\" value=\"".EASYSHOP_CONF_ITM_43."\" onclick=\"expandit(this)\" />
  			<div style=\"display:none;\">
  			<input class=\"tbox\" type=\"file\" name=\"file_userfile[]\" size=\"50\" />
  			<input class=\"button\" type=\"submit\" name=\"upload\" value=\"".EASYSHOP_CONF_ITM_38."\" />
  			<input type=\"hidden\" name=\"upload_dir[]\" value=\"".$imgdirname."\" />
  			</div></td></tr>";

      $text .= "
			<tr>
				<td colspan=2>
					<img src='".e_IMAGE."admin_images/docs_16.png' title='' alt='' /> ".EASYSHOP_CONF_ITM_17."
				</td>
			</tr>
    <tr>
      <td>
        <b>".EASYSHOP_CONF_ITM_18."</b>
      </td>
      <td>";
      
    		// Display the check box for active status (active = 2)
    		if ($item_active_status == 2) {
    				$text .= "<input type='checkbox' name='item_active_status' value='2' checked='checked' />";
    		} else {
    				$text .= "<input type='checkbox' name='item_active_status' value='1' />";
    		}
            
      $text .= "
      </td>
    </tr>";

    for ($n = 1; $n < 6; $n++){
    $fpropname = "prod_prop_".$n."_id";
    $text .= "
    <tr>
      <td>
        <b>".EASYSHOP_CONF_ITM_28." ".$n."</b>
      </td>
      <td>
		<select class='tbox' name='$fpropname'>
        <option value='' selected='selected'></option>";
            $sql3 = new db;
            $sql3 -> db_Select(DB_TABLE_SHOP_PROPERTIES, "*", " ORDER BY prop_display_name", false); // Select all properties
            while ($row3 = $sql3->db_Fetch()) {
              //$positioner = ${"prod_prop_".$n."_id"};
              // Show display_name and first 10 characters of the string; so it is no problem to select from same name e.g. 'Color' with different property list
            	if ($row3['property_id'] == ${"prod_prop_".$n."_id"}) {
          			$text .= "<option value='".$row3['property_id']."' selected='selected'>".$row3['prop_display_name'].": ".substr($row3['prop_list'],0,10).((strlen($row3['prop_list'])?"...":""))."</option>";
            	} else {
          			$text .= "<option value='".$row3['property_id']."'>".$row3['prop_display_name'].": ".substr($row3['prop_list'],0,10).((strlen($row3['prop_list'])?"...":""))."</option>";
            	}
            }
        $text .= "
		</select>
	  </td>
    </tr>";
    } // End of For loop for properties

    // Display discount
    $text .= "
    <tr>
      <td>
        <b>".EASYSHOP_CONF_ITM_29."</b>
      </td>
      <td>
		<select class='tbox' name='prod_discount_id'>
        <option value='' selected='selected'></option>";
            $sql4 = new db;
            $sql4 -> db_Select(DB_TABLE_SHOP_DISCOUNT, "*", " ORDER BY discount_name", false); // Select all discounts
            while ($row4 = $sql4->db_Fetch()) {
            	if ($row4['discount_id'] == $prod_discount_id) {
          			$text .= "<option value='".$row4['discount_id']."' selected='selected'>".$row4['discount_name']."</option>";
            	} else {
          			$text .= "<option value='".$row4['discount_id']."'>".$row4['discount_name']."</option>";
            	}
            }
        $text .= "
        </select>";
        if (trim($prod_discount_id) > "") {
            $sql5 = new db;
            $sql5 -> db_Select(DB_TABLE_SHOP_DISCOUNT, "*", "discount_id = ".$prod_discount_id); // Select the selected discount
            if ($row5 = $sql5->db_Fetch()) {
              if ($row5['discount_valid_till']== 0) { // Set the end date to maximum if not filled in
                $row5['discount_valid_till'] = 9999999999;
              }
              $today = time();
              // $text .= "-- Today = $today Discount_valid_till = ".$row5['discount_valid_till']." Discount_valid_from = ".$row5['discount_valid_from']."--"; // Some debug info
              if ($today > $row5['discount_valid_till']) {
                $text .= "&nbsp;".EASYSHOP_CONF_ITM_30." (".date("Y/m/d", $row5['discount_valid_till']).")";
              }
              if ($today < $row5['discount_valid_from']) {
                $text .= "&nbsp;".EASYSHOP_CONF_ITM_31." (".date("Y/m/d", $row5['discount_valid_from']).")";
              }
            }
        }
        $text .= "
      </td></tr>";

    // IPN addition - include track stock option in form
    $item_track_stock <> '2' ? $trackstock_text = " value = '1' " : $trackstock_text = " value = '2' checked='checked' ";
    $enable_ipn <> '2' ? $enabled_text = " disabled = 'true' " : $enabled_text = "";
    $text .="
    <tr>
        <td>
            <b>".EASYSHOP_CONF_ITM_33."</b><br />";
      if ($enable_ipn <> '2'){
        $text .=EASYSHOP_CONF_ITM_34;
      }

      $text .="
        </td>

        <td valign='top'>
            <input type='checkbox' name='item_track_stock' $trackstock_text $enabled_text />
        </td>
        </tr>
        <tr>
        <td>
            <b>".EASYSHOP_CONF_ITM_35."</b><br />";
        if ($enable_ipn <> '2'){
          $text .=EASYSHOP_CONF_ITM_34;
        }
        $text.="
        </td>
        <td valign='top'>
            <input class='tbox' size='7' type='text' name='item_instock' value='".$item_instock."' $enabled_text />
        </td>
    </tr>
	<tr>
        <td>
            <b>".EASYSHOP_CONF_ITM_47."</b><br />";
        if ($enable_ipn <> '2'){
          $text .=EASYSHOP_CONF_ITM_34;
        }
        $text.="
        </td>
        <td valign='top'>
            <input class='tbox' size='7' type='text' name='item_minimum' value='".$item_minimum."' $enabled_text />
        </td>
    </tr>";
    
    // Download product: only if IPN is activated
    $text .= "
    <tr><td>
    <b>".EASYSHOP_CONF_ITM_36."</b><br />
    ";
        if ($enable_ipn <> '2'){
          $text .=EASYSHOP_CONF_ITM_34;
        }
    $download_product <>'2' ? $download_product_text = " value = '1' " : $download_product_text = " value = '2' checked='checked' ";
    $text .= "
    </td><td>
           <input type='checkbox' name='download_product' $download_product_text $enabled_text />
    </td></tr>";
    
    if(strlen(trim($download_filename)) == 0) {
      // Show upload button and select box when no download file is stored yet
      $text .= "
      <tr><td>
      </td><td>";

      $dirname = e_PLUGIN."easyshop/downloads";
  		$text .= "<input class=\"button\" type=\"button\" name=\"request\" value=\"".EASYSHOP_CONF_ITM_37."\" onclick=\"expandit(this)\" />
  			<div style=\"display:none;\">
  			<input class=\"tbox\" type=\"file\" name=\"file_userfile[]\" size=\"50\" />
  			<input class=\"button\" type=\"submit\" name=\"upload\" value=\"".EASYSHOP_CONF_ITM_38."\" />
  			<input type=\"hidden\" name=\"upload_dir[]\" value=\"".$dirname."\" />
  			</div>";
      $text .= "
      </td></tr>";
      // Show select box when no download file is stored yet
  		require_once(e_HANDLER.'file_class.php');
  		$dl = new e_file;
  		$rejecfiles = array('$.','$..','/','CVS','thumbs.db','*._$',"thumb_", 'index', 'null*');
  		$downloadlist = $dl->get_files(e_PLUGIN."easyshop/downloads",$rejecthumb);

      $text .= "
      <tr><td>
           <b>".EASYSHOP_CONF_ITM_39."</b>
      </td><td>
  		   <select name='download_filename' class='tbox'>
  			<option value=''>&nbsp;</option>
  			";

  		foreach($downloadlist as $file){
        $extension = strrpos($file['fname'], ".") ? substr($file['fname'], strrpos($file['fname'], ".")) : "";
        if (strlen($extension) > 0) { // Suppress files without extension
    		  if ($file['fname'] == $download_filename) {
            $selected_text = "selected='selected'";
    		  } else {
            $selected_text ="";
    		  }
    			$text .= "<option value='".$file['fname']."' $selected_text>".$file['fname']."</option>";
        }
  		}

  		$text .= "</select>";
    } else {
      // Show stored download file
      $text .= "
      <tr><td>
           <b>".EASYSHOP_CONF_ITM_40."</b><br />
           ".EASYSHOP_CONF_ITM_41."
      </td><td>
        <input name='download_filename' value='".$download_filename."' disabled = 'true' />
      ";
    }
    // Show scramled file info
    if(strlen($download_filename) > 0 ) {
      $scramled_name = $item_id.$download_filename;
      $text .= "<br />
  		".EASYSHOP_CONF_ITM_42.": ".md5($scramled_name)."<br />";
    }
    
		$text .= "<input type='hidden' name='stored_download_filename' value='".$download_filename."' />
    </td></tr>
    ";
	
    // Promotion class: only if IPN is activated
    $text .= "
    <tr><td>
    <b>".EASYSHOP_CONF_ITM_45."</b><br />
    ";
        if ($enable_ipn <> '2'){
          $text .=EASYSHOP_CONF_ITM_34;
        }
    $download_product <>'2' ? $download_product_text = " value = '1' " : $download_product_text = " value = '2' checked='checked' ";
	require_once(e_HANDLER.'userclass_class.php');
    $text .= "
    </td><td>
			".r_userclass("prod_promo_class", $prod_promo_class, "off", "nobody,classes")."		   
   </td></tr>";	
   
	// NEW: START v1.6m
    // Datasheet functionality
    $text .= "
    <tr><td>
		<b>".EASYSHOP_CONF_ITM_56."</b><br /> ";
    $download_datasheet <> '2' ? $download_datasheet_text = " value = '1' " : $download_datasheet_text = " value = '2' checked='checked' ";
    $text .= "
    </td><td>
		<input type='checkbox' name='download_datasheet' $download_datasheet_text />
    </td></tr>";
    
    if(strlen(trim($download_datasheet_filename)) == 0) 
	{	// Show upload button and select box when no datasheet file is stored yet
		$text .= "
	<tr><td>
	</td><td>";

		$dirname = e_PLUGIN."easyshop/datasheets";
  		$text .= "<input class=\"button\" type=\"button\" name=\"request\" value=\"".EASYSHOP_CONF_ITM_57."\" onclick=\"expandit(this)\" />
  			<div style=\"display:none;\">
  			<input class=\"tbox\" type=\"file\" name=\"file_userfile[]\" size=\"50\" />
  			<input class=\"button\" type=\"submit\" name=\"upload\" value=\"".EASYSHOP_CONF_ITM_58."\" />
  			<input type=\"hidden\" name=\"upload_dir[]\" value=\"".$dirname."\" />
  			</div>";
		$text .= "
	</td></tr>";
		// Show select box when no datasheet file is stored yet
  		require_once(e_HANDLER.'file_class.php');
  		$dl = new e_file;
  		$rejecfiles = array('$.','$..','/','CVS','thumbs.db','*._$',"thumb_", 'index', 'null*');
  		$datasheetlist = $dl->get_files(e_PLUGIN."easyshop/datasheets",$rejecthumb);

		$text .= "
	<tr><td>
		<b>".EASYSHOP_CONF_ITM_59."</b>
	</td><td>
		<select name='download_datasheet_filename' class='tbox'>
			<option value=''>&nbsp;</option>";

  		foreach($datasheetlist as $file)
		{
			$extension = strrpos($file['fname'], ".") ? substr($file['fname'], strrpos($file['fname'], ".")) : "";
			if (strlen($extension) > 0) 
			{	// Suppress files without extension
				if ($file['fname'] == $download_datasheet_filename) 
				{
					$download_datasheet_selected_text = "selected='selected'";
				} else 
				{
					$download_datasheet_selected_text ="";
				}
				$text .= "<option value='".$file['fname']."' $download_datasheet_selected_text>".$file['fname']."</option>";
			}
  		}
  		$text .= "
		</select>
	</td></tr>";
    } else 
	{	// Show stored datasheet file
		$text .= "
	<tr><td>
		<b>".EASYSHOP_CONF_ITM_60."</b><br />
		".EASYSHOP_CONF_ITM_61."
	</td><td>
		<input name='download_datasheet_filename' value='".$download_datasheet_filename."' disabled = 'true' />
	</td></tr>";
    }
	
	// Display item_quotation checkbox
    $text .= "
    <tr><td>
		<b>".EASYSHOP_CONF_ITM_62."</b><br /> ";
    $item_quotation <> '2' ? $item_quotation_text = " value = '1' " : $item_quotation_text = " value = '2' checked='checked' ";
    $text .= "
    </td><td>
		<input type='checkbox' name='item_quotation' $item_quotation_text />
    </td></tr>";
	// NEW: END v1.6m
    
    $text .= "
	</table>";
return $text;
}

require_once(e_ADMIN.'footer.php');
?>