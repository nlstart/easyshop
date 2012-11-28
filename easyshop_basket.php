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
require_once('includes/ipn_functions.php');

// Check query
if(e_QUERY){
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$action_id = urldecode($tmp[1]); // KVN temp fix for space issue in properties
	unset($tmp);
}

// Keep sessions alive when user uses back button of browser
session_cache_limiter('public');
// Start a session to catch the basket
session_start();

require_once('easyshop_class.php');
// $session_id = Security::get_session_id(); // Get the session id by using Singleton pattern

//if ($session_id != session_id()) { // Get out of here: incoming session id is not equal than current session id
//  header("Location: ".e_BASE); // Redirect to the home page
//  exit();
//}

// Set the totals to zero if there is no session variable
if(!isset($_SESSION['sc_total'])) {
  $_SESSION['sc_total']['items']    = 0;
  $_SESSION['sc_total']['sum']      = 0;
  $_SESSION['sc_total']['shipping'] = 0;
  $_SESSION['sc_total']['shipping2']= 0;
  $_SESSION['sc_total']['handling'] = 0;
}

// Reset the arrays when easyshop_basket.php?reset is called
if ($action == 'reset') {
  unset($_SESSION['shopping_cart']);
  unset($_SESSION['sc_total']);
  // Manipulate return target location back to main application
  $target=str_replace('easyshop_basket.php', 'easyshop.php', e_SELF);
  header("Location: ".$target);
  exit();
}

// Delete a product row
if ($action == 'delete') {
  // Delete the product from the totals
  $_SESSION['sc_total']['items'] = ($_SESSION['sc_total']['items']) - ($_SESSION['shopping_cart'][$action_id]['quantity']);
  $_SESSION['sc_total']['sum']   = ($_SESSION['sc_total']['sum']) - (($_SESSION['shopping_cart'][$action_id]['quantity']) * ($_SESSION['shopping_cart'][$action_id]['item_price']));
  $_SESSION['sc_total']['shipping'] = ($_SESSION['sc_total']['shipping']) - ($_SESSION['shopping_cart'][$action_id]['shipping']);
  $_SESSION['sc_total']['handling'] = ($_SESSION['sc_total']['handling']) - ($_SESSION['shopping_cart'][$action_id]['handling']);
  // Shippings costs for 2 items and more are conditioned
  if ((integer)($_SESSION['shopping_cart'][$action_id]['quantity']) > 1) {
    $_SESSION['sc_total']['shipping2'] = ($_SESSION['sc_total']['shipping2']) - ((($_SESSION['shopping_cart'][$action_id]['quantity'])-1) *(double)$_POST['shipping2']);
  }
  // Delete the actual product row from the shopping cart
  unset($_SESSION['shopping_cart'][$action_id]);
  // Manipulate return target location back to edit basket mode
  $target=('easyshop.php?edit');
  header("Location: ".$target);
  exit();
}

// Minus on a product row
if ($action == 'minus') {
  // Minus 1 of the product from the totals
  $_SESSION['sc_total']['items'] = ($_SESSION['sc_total']['items']) - 1;
  $_SESSION['sc_total']['sum']   = ($_SESSION['sc_total']['sum']) - ($_SESSION['shopping_cart'][$action_id]['item_price']);
  // Only additional shipping need to be deducted (quantity is always higher than 1)
  $_SESSION['sc_total']['shipping2'] -= (double)$_SESSION['shopping_cart'][$action_id]['shipping2'];
  // Minus 1 of the actual product row from the shopping cart
  $_SESSION['shopping_cart'][$action_id]['quantity'] = $_SESSION['shopping_cart'][$action_id]['quantity'] - 1;
  // Manipulate return target location back to edit basket mode
  $target=('easyshop.php?edit');
  header("Location: ".$target);
  exit();
}

// Add on a product row
if ($action == 'add') {
  // Add 1 of the product from the totals
  $_SESSION['sc_total']['items'] = ($_SESSION['sc_total']['items']) + 1;
  $_SESSION['sc_total']['sum']   = ($_SESSION['sc_total']['sum']) + ($_SESSION['shopping_cart'][$action_id]['item_price']);
  // Only additional shipping need to be added (quantity is always higher than 1)
  $_SESSION['sc_total']['shipping2'] += (double)$_SESSION['shopping_cart'][$action_id]['shipping2'];
  // Add 1 of the actual product row to the shopping cart
  $_SESSION['shopping_cart'][$action_id]['quantity'] = $_SESSION['shopping_cart'][$action_id]['quantity'] + 1;
  // Manipulate return target location back to edit basket mode
  $target=('easyshop.php?edit');
  header("Location: ".$target);
  exit();
}

// Check incoming properties before filling the basket
for ($n = 1; $n < 6; $n++){
  $prod_prop = "prod_prop_".$n;
  $prop_name = "prop".$n."_name";
  $prop_list = "prop".$n."_list";
  $prop_prices = "prop".$n."_prices";
  if (isset($_POST[$prod_prop])) {
    if (trim($_POST[$prod_prop]) == "") {
      // Notify user that property is not filled
      $text = "
    	<div style='width:100%; text-align:center;'>
    	<table border='0' cellspacing='1'>
      <tr>
        <br /><br />
          ".$_POST[$prop_name]." ".EASYSHOP_BASKET_01.".
        <br /><br />
      </tr>
      <tr>
          <td>
              <a href='javascript:history.go(-1);'>".EASYSHOP_BASKET_02."</a>
          </td>
      </tr>
      </table>
      </div>
      ";
      // Render the value of $text in a table.
      $title = "<b>".EASYSHOP_BASKET_03."</b>";
      $ns -> tablerender($title, $text);
      require_once(FOOTERF);
      exit();
    } else {
      // Property is filled in correctly by user
      // Create property array
      ${"prop".$n."_array"} = explode(",", $_POST[$prop_list]);
      // Search the key of the chosen property value
      $key = array_search(trim($_POST[$prod_prop]), ${"prop".$n."_array"});
      // Create price array
      ${"price".$n."_array"} = explode(",", $_POST[$prop_prices]);
      // Adjust the price with the corresponding price
      $_POST['item_price'] = (double)$_POST['item_price'] + ${"price".$n."_array"}[$key];
      // Adjust the item id
      $_POST['item_id'] = intval($_POST['item_id']).trim($_POST[$prod_prop]);
      // Adjust item name
      $_POST['item_name'] = $_POST['item_name']." ".trim($_POST[$prod_prop]);
    }
  }
}

// Check on incoming discount before filling the basket
// if ($_POST['discount_code'] <> "" or !isset($_POST['discount_code'])) { // Only activate when discount code is filled in //Bugfix #75
$sql = new db;
$sql -> db_Select(DB_TABLE_SHOP_DISCOUNT, "*", "discount_id=".intval($_POST['discount_id'])); // Security fix with intval
if ($row = $sql-> db_Fetch()){
    $discount_id = $row['discount_id'];
    // $discount_name = $row['discount_name'];
    // $discount_class = $row['discount_class'];
    $discount_flag = $row['discount_flag'];
    $discount_price = number_format($row['discount_price'], 2, '.', '');
    $discount_percentage = number_format($row['discount_percentage'], 2, '.', '');
    $discount_valid_from = $row['discount_valid_from'];
    $discount_valid_till = $row['discount_valid_till'];
    $discount_code = $row['discount_code'];
	//} Removed due to Bugfix#75
	$no_discount_code = false;
	if (!isset($_POST['discount_code']) && $discount_code == "") { // Set variable to true when no discount code is available: Bugfix #75
		$no_discount_code = true;
	}
	// Check if the code input matches the real discount code
	if ($_POST['discount_code'] == $discount_code || $no_discount_code === true) { // The discount code is correct!
		// Adjust the item id for uniqueness in the basket
		$_POST['item_id'] = $tp->toDB($_POST['item_id']).trim($discount_id);
		// Adjust item name
		$discount_text = EASYSHOP_BASKET_04."&nbsp;";
		if ($discount_flag == 1) { // Discount percentage
			$discount_text .= trim($discount_percentage)."%";
		} else { // Discount amount
			$discount_text .= $tp->toDB($_POST['unicode_character_before']).trim($discount_price).$tp->toDB($_POST['unicode_character_after']);
		}
		$_POST['item_name'] = $tp->toDB($_POST['item_name'])." ".trim($discount_text);
		// Adjust item price
		if ($discount_flag == 1) { // Discount percentage
			$_POST['item_price'] = number_format(($_POST['item_price'] -  ( ( $discount_percentage / 100) * $_POST['item_price'])), 2, '.', '');
		}
		else { // Discount amount
			$_POST['item_price'] = number_format(($_POST['item_price'] - $discount_price), 2, '.', '');
		}
		// Protection against a discount that makes the price negative
		if ($_POST['item_price'] < 0) {
			$_POST['item_price'] = 0;
		}
	} // The discount will not be applied if the wrong code is entered
}

// Filling basket from category = C; return to category overview
// Filling basket from product  = P; return to product overview
if ($_POST['fill_basket'] == 'C' or $_POST['fill_basket'] == 'P') {
    // refresh_cart(); // IPN addition // might screw up the session variables
    // IPN addition - sets two variables to help keep coding neat later on
	$_POST['item_id'] = intval($_POST['item_id']); // Security enhancement
    isset($_POST['item_id'])? $action_id=$_POST['item_id']: NULL;
    isset($_SESSION['shopping_cart'][$action_id]['item_track_stock'])
        && ($_SESSION['shopping_cart'][$action_id]['quantity']) < ($_SESSION['shopping_cart'][$action_id]['item_instock'])?
            $allow_add = TRUE:
            $allow_add = NULL;
    isset($_SESSION['shopping_cart'][$action_id]['item_track_stock'])?
            $track_stock = TRUE:
            $track_stock = NULL;

    // Fill the basket with selected product
    if (!array_key_exists($_POST['item_id'], $_SESSION['shopping_cart'])) {
      // Key for item id does not exists; item needs to be added to the array
      $_SESSION['shopping_cart'][$_POST['item_id']] = array('item_name'=>$tp->toDB($_POST['item_name']), 'quantity'=>intval($_POST['item_qty']), 'item_price'=>(double)$_POST['item_price'], 'sku_number'=>$tp->toDB($_POST['sku_number']), 'shipping'=>(double)$_POST['shipping'], 'shipping2'=>(double)$_POST['shipping2'], 'handling'=>(double)$_POST['handling'], 'db_id'=> intval($_POST['db_id']));
      // Handling costs are calculated once per each basket
      $_SESSION['sc_total']['handling'] += (double)$_POST['handling'];
        // IPN addition - check  to see if we're tracking stock, if so put stock amount into SESSION ARRAY
         if ($_POST['item_track_stock'] == 2){
            $_SESSION['shopping_cart'][$_POST['item_id']]['item_instock'] = $tp->toDB($_POST['item_instock']);
            $_SESSION['shopping_cart'][$_POST['item_id']]['item_track_stock'] = $tp->toDB($_POST['item_track_stock']);
         }    
    }
    else if (!isset($track_stock) || isset($allow_add)){
      // IPN addition check quantity against item_instock
      // Key for item id does exist; only quantity needs to raised
      $_SESSION['shopping_cart'][$_POST['item_id']]['quantity'] += intval($_POST['item_qty']);
    }
    
    if (!isset($track_stock) || isset($allow_add)){  // IPN addition - don't increment if quantity is at max stock level
        // Fill the sc_total array
        $previous_nr_of_items = $_SESSION['shopping_cart']['item_id']['quantity']; // Fix bug #88
        $_SESSION['sc_total']['items'] += $_POST['item_qty'];
        $_SESSION['sc_total']['sum'] += (double)$_POST['item_price'] * intval($_POST['item_qty']);
        // Extra shippings costs are conditioned (only calculate for first product)
        if ((integer)($_SESSION['shopping_cart'][$_POST['item_id']]['quantity']) >= 1 and $previous_nr_of_items == 0) { // Fix bug #81
          $_SESSION['sc_total']['shipping'] += (double)$_POST['shipping'];
        }
		// PayPal charges shipping2 costs for all items above quantity of 2
		if ((integer)($_SESSION['shopping_cart'][$_POST['item_id']]['quantity']) > 1) {
			if ($previous_nr_of_items == 0) {
				$_SESSION['sc_total']['shipping2'] += (double)$_POST['shipping2'] * (intval($_POST['item_qty'])-1);
			}
			else {
				$_SESSION['sc_total']['shipping2'] += (double)$_POST['shipping2'] * intval($_POST['item_qty']);
			}
		} 
    }

    // Close the session (before a location redirect: otherwise the variables may not display correctly)
    session_write_close();
    // Return to original url
    header("Location: ".$tp->toDB($_POST['return_url']));
    exit();
}
// use FOOTERF for USER PAGES and e_ADMIN.'footer.php' for admin pages
// require_once(FOOTERF);
?>