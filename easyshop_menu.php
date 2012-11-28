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
$session_id = Security::get_session_id(); // Get the session id by using Singleton pattern

// Determine that the easyshop_menu is shown; used in easyshop_class function show_checkout
$_SESSION['easyshop_menu'] = true;

// Randomly pick an active product from an active product category (only pick categories that user is entitled to see)
$sql = new db;
$arg="SELECT *
      FROM #easyshop_items
      LEFT JOIN #easyshop_item_categories
      ON #easyshop_items.category_id = #easyshop_item_categories.category_id
      WHERE category_active_status = '2' AND item_active_status = '2' AND (category_class IN (".USERCLASS_LIST."))
      ORDER BY RAND()";
$sql->db_Select_gen($arg,false);
if ($row = $sql-> db_Fetch()){
    $category_id = $row["category_id"];
		$item_id = $row["item_id"];
		$item_name = $row["item_name"];
		$item_description = strip_tags($tp->toHTML($row["item_description"], true));		
		$item_image = $row["item_image"];
		$item_active_status = $row["item_active_status"];
    $item_price = $row["item_price"];

    // Retrieve shop settings
    $sql -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
    if ($row = $sql-> db_Fetch()){
        $store_image_path = $row['store_image_path'];
        $set_currency_behind = $row['set_currency_behind'];
        $show_shopping_bag = $row['show_shopping_bag'];
        $shopping_bag_color = $row['shopping_bag_color'];
    }
    // Determine shopping bag color
    // 0=blue, 1=green, 2=orange, 3=red, 4=yellow, 5=white, 6=black
    if ($show_shopping_bag == '1') { // This only needs to be determined when bag is shown
      // Blue color is selected or color is not defined or color has improper value
      if ($shopping_bag_color == '0' or strlen(trim($shopping_bag_color))== 0 or $shopping_bag_color > 6) {
        $bag_color = "_blue";
      }
      if ($shopping_bag_color == '1') { // Green color is selected
        $bag_color = "_green";
      }
      if ($shopping_bag_color == '2') { // Orange color is selected
        $bag_color = "_orange";
      }
      if ($shopping_bag_color == '3') { // Red color is selected
        $bag_color = "_red";
      }
      if ($shopping_bag_color == '4') { // Yellow color is selected
        $bag_color = "_yellow";
      }
      if ($shopping_bag_color == '5') { // White color is selected
        $bag_color = "_white";
      }
      if ($shopping_bag_color == '6') { // Black color is selected
        $bag_color = "_black";
      }
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

    $text = "
    <table style='text-align:center;'>
      <tr>
        <td class='forumheader3' style='colspan:2; text-align:center;'>$item_name</td>
      </tr>
      <tr>
        <td class='forumheader3' style='colspan:2; text-align:center;'><a href='$urllink' title='$item_description'>$prodlink</a></td>
      </tr>
      <tr>
        <td class='forumheader3' style='colspan:2; text-align:center;'>".EASYSHOP_PUBLICMENU_09.$unicode_character_before.number_format($item_price, 2, '.', '').$unicode_character_after."</td>
      </tr>
    </table>
    ";

    // Create 'total arrays' for total number of products and sum of the prices
    // Variables sum_price, average_price and sum_shipping_handling are displayed in english notation without thousands seperator (by number_format)
    $count_items = count($_SESSION['shopping_cart']);     // Count number of different products in basket
    $sum_quantity = $_SESSION['sc_total']['items'];       // Display cached sum of total quantity of items in basket
    $sum_shipping = $_SESSION['sc_total']['shipping'];    // Display cached sum of shipping costs for 1st item
    $sum_shipping2 = $_SESSION['sc_total']['shipping2'];  // Display cached sum of shipping costs for additional items (>1)
    $sum_handling = $_SESSION['sc_total']['handling'];    // Display cached sum of handling costs
    $sum_shipping_handling = number_format(($sum_shipping + $sum_shipping2 + $sum_handling), 2, '.', ''); // Calculate total handling and shipping price
    $sum_price = number_format(($_SESSION['sc_total']['sum'] + $sum_shipping_handling), 2, '.', ''); // Display cached sum of total price of items in basket + shipping + handling costs
    if ($sum_quantity > 0 ) { // Prevent a PHP warning: Division by zero
      $average_price = number_format(($sum_price / $sum_quantity), 2, '.', ''); // Calculate the average price per product
    }

    // If there is something in the shopping cart: show the 'total arrays'
    if ( $count_items > 0 ) {
      // Conditionally show shopping bag image
      if ($show_shopping_bag == '1') {
      $text .= "
      <table style='text-align:center;'>
      <tr>
        <td style='text-align:center; font-size: 36px; background-image: url(".e_PLUGIN_ABS."easyshop/images/shopping_bag".$bag_color.".gif); background-repeat: no-repeat; background-position: center; width: 70px; height: 90px;'>
          $sum_quantity
        </td>
      </tr>
      </table>";
      }

      $text .= "
      <table style='text-align:center;'>
      <tr>
        <td style='text-align:left;'>
          <br />".EASYSHOP_PUBLICMENU_02."
          <br />".EASYSHOP_PUBLICMENU_03." ".$sum_quantity."
          <br />".EASYSHOP_PUBLICMENU_04." ".$count_items."
          <br />".EASYSHOP_PUBLICMENU_05." ".$unicode_character_before.$sum_price.$unicode_character_after."
          <br />".EASYSHOP_PUBLICMENU_06." ".$unicode_character_before.$average_price.$unicode_character_after."
          ";
        if ($sum_shipping_handling > 0) {
          $text .= "
            <br />".EASYSHOP_PUBLICMENU_07." ".$unicode_character_before.$sum_shipping_handling.$unicode_character_after."
          ";
        }

      // Add the checkout button produced by function show_checkout
      $text .= "<div style='text-align:center;'>".Shop::show_checkout($session_id)."</div>";

      $text .= "
        </td>
      </tr>
      </table>";
    }
} else {  // End of if check on fetched category_id
  // Inform about no access to any category
  $text = "
      <table style='text-align:center;'>
      <tr><td>
      ".EASYSHOP_PUBLICMENU_10."
      </td></tr>
      </table>
      ";
}

$_SESSION['easyshop_menu'] = false;

$caption = EASYSHOP_PUBLICMENU_01;
$ns -> tablerender($caption, $text);
?>