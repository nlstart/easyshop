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
global $tp;
// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
// include define tables info
require_once(e_PLUGIN."easyshop/includes/config.php"); // It's important to point to the correct plugin folder!

require_once('easyshop_class.php');
// $session_id = Security::get_session_id(); // Get the session id by using Singleton pattern

// Pick last active product from an active product category (only pick categories that user is entitled to see)
$today = time();
$sql = new db;
$arg="SELECT *
      FROM #easyshop_items
      LEFT JOIN #easyshop_item_categories
      ON #easyshop_items.category_id = #easyshop_item_categories.category_id
      LEFT JOIN #easyshop_discount
      ON #easyshop_items.prod_discount_id = #easyshop_discount.discount_id
      WHERE category_active_status = '2' AND item_active_status = '2' AND (category_class IN (".USERCLASS_LIST.")) 
      ORDER BY item_id DESC";

$sql->db_Select_gen($arg,false);
if ($row = $sql-> db_Fetch() and ($row["item_id"] > 0)){
    $category_id = $row["category_id"];
	$item_id = $row["item_id"];
	$item_name = $row["item_name"];
	$item_description = strip_tags($tp->toHTML($row["item_description"], true));		
	$item_image = $row["item_image"];
	$item_active_status = $row["item_active_status"];
    $item_price = $row["item_price"];

	$discount_id = $row["discount_id"];
	$discount_class = $row["discount_class"];
	$discount_valid_from = $row["discount_valid_from"];
	$discount_valid_till = $row["discount_valid_till"];
	$discount_code = $row["discount_code"];
	$discount_flag = $row["discount_flag"];
	$discount_percentage = $row["discount_percentage"];
	$discount_price = $row["discount_price"];
	$property_prices = $row["property_prices"];
	$item_quotation = $row["item_quotation"];

    // Retrieve shop settings
    $sql -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
    if ($row = $sql-> db_Fetch()){
        $store_image_path = $row['store_image_path'];
        $set_currency_behind = $row['set_currency_behind'];
    }

    // Check admin setting to set currency behind amount
    // 0 = currency before amount (default), 1 = currency behind amount
    if ($set_currency_behind == '') {($set_currency_behind = 0);}

    // Define position of currency character
    $sql -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "currency_active=2");
    if($row = $sql-> db_Fetch()){
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

	$item_image = explode(",",$item_image);
	shuffle($item_image); // A random image of a product will be displayed if there are multiple images
    // NOTE: image directories are always supposed to be a folder under the easyshop directory (!)
	if ($item_image[0]<>'' or $item_image[0] <> NULL) { // Only display images when we have them
		$prodlink = "<img style='border-style:none;' src='".e_PLUGIN."easyshop/".$store_image_path.$item_image[0]."' alt='$item_description' title='$item_description'/>";
	} else { // Show the description when there are no images
		$prodlink = $item_description;	
	}
    $urllink  = e_PLUGIN."easyshop/easyshop.php?prod.$item_id"; // got rid of long urls";
    
    // Function include_disc returns an array! [0] is for $text and [1] is for $item_price!
    $temp_array = Shop::include_disc($discount_id, $discount_class, $discount_valid_from, $discount_valid_till,
                               $discount_code, $item_price, $discount_flag, $discount_percentage, $discount_price,
                               $property_prices, $unicode_character_before, $unicode_character_after, $print_discount_icons);
    //$discount_text .= $temp_array[0];
    $new_item_price = $temp_array[1];
    unset($temp_array);

    $text = "
    <table style='text-align:center;'>
      <tr>
        <td class='forumheader3' style='colspan:2; text-align:center;'>$item_name</td>
      </tr>
      <tr>
        <td class='forumheader3' style='colspan:2; text-align:center;'><a href='$urllink' title='$item_description'>$prodlink</a></td>
      </tr>";

	if ($item_quotation == '2')
	{	// Don't display the price for a quotation product // v1.6m
	}
    elseif ($discount_id > 0) { // Show discount price info when there is one
		$text .= "	  
		  <tr>
			<td class='forumheader3' style='colspan:2; text-align:center;'>".EASYSHOP_PUBLICMENU_09.$unicode_character_before."<span style='text-decoration: line-through;'>".number_format($item_price, 2, '.', '')."</span>&nbsp;".$unicode_character_after."&nbsp;".$unicode_character_before.number_format($new_item_price, 2, '.', '')."$unicode_character_after</td>
		  </tr>";
		  if ($discount_valid_till > 0 && $discount_id > 0) {
			$text .= "
			  <tr>
				<td class='forumheader3' style='colspan:2; text-align:center;'>".EASYSHOP_PUBLICMENU4_11.": ".date("d-m-Y",$discount_valid_till)."</td>
			  </tr>";
		   }
	} else { // Show normal price info
		$text .= "	  
		  <tr>
			<td class='forumheader3' style='colspan:2; text-align:center;'>".EASYSHOP_PUBLICMENU_09.$unicode_character_before.number_format($item_price, 2, '.', '')."$unicode_character_after</td>
		  </tr>";
	}
	
    $text .= "
    </table>
    ";
} else {  // End of if check on fetched category_id
  // Inform about no access to any category
  $text = "
      <table style='text-align:center;'>
      <tr><td>
      ".EASYSHOP_PUBLICMENU4_10."
      </td></tr>
      </table>
      ";
}

$caption = EASYSHOP_PUBLICMENU4_01;
$ns -> tablerender($caption, $text);
?>