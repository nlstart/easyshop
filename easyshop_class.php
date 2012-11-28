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
class ShopMail
{
  function easyshop_sendemail($send_to, $subject, $message, $headers2, $attachment_name) {
    $domain_name = General::parseUrl(e_SELF); // Parse the current url
    $domain_name = $domain_name[host]; // Retrieve the host name from the parsed array
    require_once(e_HANDLER.'mail.php');
    // $bcc_mail = "yourmailaccount@yourdomain.tld";
    if (!sendemail($send_to, $subject, $message, $to_name, "no-reply@".$domain_name, "EasyShop", $attachment_name, "", $bcc_mail)) {
  			return FALSE;
    }	else { // E-mail was send succesfully
  			return TRUE;
    }
  }

  function easyshop_senddownloads($array, $to_email)
  {
    $address = $to_email;
    // Loop throught the basket to detect dowloadable products
    foreach($array as $id => $item) {
      $sql = new db;
      $sql -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_id=$item[db_id]");
      if ($row = $sql-> db_Fetch()){
        $download_product  = $row['download_product'];
        $download_filename = $row['download_filename'];
    		// Check if this is a downloadable product with valid download filename
    		if ($download_product == 2 && strlen($download_filename) > 0) {
    		$scrambled_name = intval($item[db_id]).$download_filename;
    		$attachment_name_scrambled = "downloads/".MD5($scrambled_name);
    		$attachment_name = "downloads/".$download_filename;
    		// Create temporary original file to download with unscrambled name
    		if (!copy($attachment_name_scrambled, $attachment_name)) {
    			$message = EASYSHOP_CLASS_02." $attachment_name_scrambled...\n";
    		}
    		$subject = EASYSHOP_CLASS_03." ".$item[item_name]." ".date("Y-m-d");
    		$message = EASYSHOP_CLASS_04.": ".$download_filename."\n";
    		// $message .= "Download filename scrambled: ".$attachment_name_scrambled."\n"; // debug info
    		// $message .= "Download filename: ".$attachment_name; // debug info
    			if(!ShopMail::easyshop_sendemail($address, $subject, $message, $header, $attachment_name)) {
    				$message = EASYSHOP_CLASS_05;  // Sending downloadable product failed
    			}
    			// Delete the temporary download file
    			unlink($attachment_name);
    	 	} // End of the if downloadable product with valid download filename
      } // End of the if product fetch
	  // Make sure the variables are empty before going into the while loop again
      $download_product  = "";
      $download_filename = "";	  
    }
  }

  function easyshop_sendalert($product_id, $newstock, $minimum_level, $alert_type)
  // Send alerts to shop owner
  // Parameters: 
  // $product_id = the item_id that the alert is send for
  // $newstock = actual number of product in stock including this purchase
  // $minimum_level = minimum number of product that should be in stock
  // $alert_type = 	1 : mimimum stock level alert 
  //				2 : customer paid for more products than in stock
  //				3 : product is out of stock
  {
    // Determine admin e-mail address from e107 preferences
	global $pref;
	$to_email = (!(isset($pref['siteadminemail']) && strlen($pref['siteadminemail'])==0)?$pref['replyto_email']:$pref['siteadminemail']); // Keep 0.7.8 compatible
	// Retrieve product data
	$product_id = intval($product_id);
	$sql = new db;
	$sql -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_id=$product_id");
	if ($row = $sql-> db_Fetch()){
		// Set subject and message for each alert type
		if ($alert_type == "1") { // Alert 1: stock is below minimum level of this product
			$subject = EASYSHOP_CLASS_06." ".$row['item_name']; 
			$message = EASYSHOP_CLASS_08." <a href='".SITEURL.e_PLUGIN."easyshop/easyshop.php?prod.".$product_id."'>".$row['item_name']."</a>!<br /><br />
					".EASYSHOP_CLASS_09.": $minimum_level<br />
					".EASYSHOP_CLASS_10.": $newstock";
		}
		if ($alert_type == "2") { // Alert 2: last buyer purchased more of this product than actual in stock
			$subject = EASYSHOP_CLASS_07." ".$row['item_name']; 
			$message = EASYSHOP_CLASS_11." <a href='".SITEURL.e_PLUGIN."easyshop/easyshop.php?prod.".$product_id."'>".$row['item_name']."</a>!<br /><br />
					".EASYSHOP_CLASS_09.": $minimum_level<br />
					".EASYSHOP_CLASS_10.": $newstock";
		}
		if ($alert_type == "3") { // Alert 3: product is out of stock
			$subject = EASYSHOP_CLASS_12." ".$row['item_name']; 
			$message = EASYSHOP_CLASS_13." <a href='".SITEURL.e_PLUGIN."easyshop/easyshop.php?prod.".$product_id."'>".$row['item_name']."</a>!<br />";
		}
		// Send alert
		ShopMail::easyshop_sendemail($to_email, $subject, $message, $header, $attachment_name);
	}
  }
  
}

class Security
{
  function get_session_id()
  {
    static $session_id;
    if ( $session_id == "" ) // 1.31 fix: setting static already sets the variable; thanks KVN
    {
      $session_id = session_id();
    }
    return $session_id;
  }
}

class General
{
  function multiple_paging($total_pages,$items_per_page,$action,$action_id,$page_id,$page_devider)
  // Parameters: $total_pages = the total pages that must be paginated
  // $items_per_page = the number of items represented per page
  // $action = action from url, e.g. catpage or prodpage
  // $action_id = action_id from url
  // $page_devider is the page devide character
  {
    if (trim($page_id) <> "" or $page_id > 0) {
     $f_action_id = $page_id; // For prodpage or catpage the $page_id is the page indicator
    } else {
     $f_action_id = $action_id; // For mcatpage the $action_id is the page indicator
    }
	if ($action == "mcatpage" || $action == "catpage") {
     $f_action_id = $action_id; // For mcatpage and catpage the $action_id is the page indicator
	}
    $last_page = intval(($total_pages + $items_per_page - 1) / $items_per_page); // Rounded last page number
    if ($last_page > 1 ) { // Suppress page indication if there is only one page
      $page_count = 1;
      if ($f_action_id == "" or $f_action_id < 1 or $f_action_id == null) {
        $f_action_id = 1; // Set initial page if no page parameter or illegal parameter is given
      }
      while ($page_count <= $last_page) { // For each page counter display a page
		if ( $page_count == $f_action_id ) { // If it is the page itself, no link
        //if ( $page_count == $last_page) { // If it is the page itself, no link
          $page_text .= " ".EASYSHOP_SHOP_05." ".$page_count." ".$page_devider;
        } else { // This is a different page than the current one, provide a link
          //$offset = $items_per_page * ($page_count - 1);
          if ($action == "catpage" or $action == "allcat") {
          $page_text .= " <a href='".e_SELF."?catpage.".$page_count."'>".EASYSHOP_SHOP_05." ".$page_count."</a> ".$page_devider;
          }
          if ($action == "cat" or $action == "prodpage") {
          $page_text .= " <a href='".e_SELF."?prodpage.".$action_id.".".$page_count."'>".EASYSHOP_SHOP_05." ".$page_count."</a> ".$page_devider;
          }
          if ($action == "blanks") {
          $page_text .= " <a href='".e_SELF."?blanks.".$page_count."'>".EASYSHOP_SHOP_05." ".$page_count."</a> ".$page_devider;
          }
          if ($action == "mcat") {
          $page_text .= " <a href='".e_SELF."?mcat.".$action_id.".".$page_count."'>".EASYSHOP_SHOP_05." ".$page_count."</a> ".$page_devider;
          }		  
          if ($action == "" or $action == "mcatpage") {
          $page_text .= " <a href='".e_SELF."?mcatpage.".$page_count."'>".EASYSHOP_SHOP_05." ".$page_count."</a> ".$page_devider;
          }
        }
        // Some debug info
        //$page_text .= " lastpage: $last_page, items per page: $items_per_page, page_count: $page_count, total_pages: $total_pages <br /> ";
        //$page_text .= " f_action_id: $f_action_id page_id: $page_id <br /> ";
        $page_count++;
      }
      $page_text = substr($page_text, 0, -(strlen($page_devider))); // Remove length of last divider character from page string
    }
    return $page_text;
  }
  
  function determine_offset($f_action,$f_action_id,$f_items_per_page)
  // Parameters: $action = action from url, e.g. catpage or prodpage
  // $action_id = action_id from url
  // $items_per_page = the number of items represented per page
  {
    if ($f_action == null ) {
      $f_offset = 0;
    } else {
        if ($f_action_id == null or $f_action_id <= 0 or $f_action_id == "") {
          $f_offset = 0;
      } else {
          $f_offset = $f_items_per_page * ($f_action_id - 1);
      }
    }
    return $f_offset;
  }
  
  function validateDecimal($f_value) {
  // Parameter: $f_value = value to be checked on maximum of 2 decimals
    if (!ereg("^[+-]?[0-9]*\.?[0-9]{0,2}$", $f_value)) {
    // Not a decimal;
    return false;
    }
    return true;
  }

  function getCommentTotal($pluginid, $id) {
     // Get number of comments for an item.
     // This method returns the number of comments for the supplied plugin/item id.
     // @param   string   a unique ID for this plugin, maximum of 10 character
     // @param   int      id of the item comments are allowed for
     // @return  int      number of comments for the supplied parameters
    global $pref, $e107cache, $tp;
    $query = "where comment_item_id='$id' AND comment_type='$pluginid'";
    $mysql = new db();
    return $mysql->db_Count("comments", "(*)", $query);
  }
  
  function getCurrentVersion(){
  $current_version = strtolower(trim(file_get_contents('http://e107.webstartinternet.com/files/downloads/easyshop_ver.php')));
  return $current_version;
  }
  
  function getEasyShopDownloadDir() {
  $download_dir = "http://e107.webstartinternet.com/download.php?list.5";
  return $download_dir;
  }
  
  function Array_Clean($str,&$array) {
    // Cleans a given string from an array
    // @param   string    the string that you want to delete from an array
    // @param   array     the name of the array you want to apply the clean function
      if (in_array($str,$array)==true) {
        foreach ($array as $key=>$value) {
          if ($value==$str) unset($array[$key]);
        }
      }
  }
  
  function CreateRandomDiscountCode() {
   // Letter O (uppercase o) is not included; can be confused with zero (0)
   // The letter l (lowercase L), and the number 1 have been removed, as they can be easily mixed up
    $chars = "AaBbCcDdEeFfGgHhIiJjKkLMmNnoPpQqRrSsTtUuVvWwXxYyZz023456789,.;:#$%*=+[]";
    srand((double)microtime()*1000000);
    $i = 0;
    $random_discount_code = '' ;
    while ($i <= 5) { // Create a 6 character random code
        $num = rand() % 69;
        $tmp = substr($chars, $num, 1);
        $random_discount_code = $random_discount_code . $tmp;
        $i++;
    }
    return htmlspecialchars($random_discount_code);
  }
  
  function parseUrl($url) {
    $r  = "^(?:(?P<scheme>\w+)://)?";
    $r .= "(?:(?P<login>\w+):(?P<pass>\w+)@)?";
    $r .= "(?P<host>(?:(?P<subdomain>[-\w\.]+)\.)?" . "(?P<domain>[-\w]+\.(?P<extension>\w+)))";
    $r .= "(?::(?P<port>\d+))?";
    $r .= "(?P<path>[\w/]*/(?P<file>\w+(?:\.\w+)?)?)?";
    $r .= "(?:\?(?P<arg>[\w=&]+))?";
    $r .= "(?:#(?P<anchor>\w+))?";
    $r = "!$r!";  // Delimiters
    preg_match ( $r, $url, $out );
    return $out;
  }
  
  function easyshop_theme_head() {
   return "<link rel='stylesheet' href='".e_PLUGIN."easyshop/tabs.css' />\n";
  }
} // End of class General

class Shop
{
  function switch_columns($p_num_item_columns) {
  // Returns column width percentage variable $column_width
  // @param   integer    the number of item columns from the settings
		switch ($p_num_item_columns) {
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
		return $column_width;
  }

  function show_checkout($p_session_id, $p_special_instr_text) {
	// Default checkout method with PayPal (IPN)
    // Parameter $p_session_id is used to check the users' current session ID to prevent XSS vulnarabilities
    // Parameter $p_special_instr_text is used to pass e-mail special instructions for seller
    //if ($p_session_id != session_id()) { // Get out of here: incoming session id is not equal than current session id
    // header("Location: ".e_BASE); // Redirect to the home page
    // exit();
    //}

    // Check query
    if(e_QUERY){
    	$tmp = explode(".", e_QUERY);
    	$action = $tmp[0];
    	unset($tmp);
    }

  	$sql2 = new db;
  	$sql2 -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
  	if ($row2 = $sql2-> db_Fetch()){
  		$sandbox               = $row2['sandbox'];
    	$paypal_email          = $row2['paypal_email'];
    	$payment_page_style    = $row2['payment_page_style'];
    	$paypal_currency_code  = $row2['paypal_currency_code'];
    	$set_currency_behind   = $row2['set_currency_behind'];
		$minimum_amount        = intval($row2['minimum_amount']);
		$always_show_checkout  = $row2['always_show_checkout'];
		$email_order           = $row2['email_order'];
		$show_shopping_bag     = $row2['show_shopping_bag'];
		$print_special_instr   = $row2['print_special_instr'];
		$enable_ipn 		   = $row2['enable_ipn']; // IPN addition
  	}

    $sql3 = new db;
  	$sql3 -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "currency_active=2");
  	if ($row3 = $sql3-> db_Fetch()){
  		$unicode_character 	  = $row3['unicode_character'];
  		$paypal_currency_code = $row3['paypal_currency_code'];
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
    if ($email_order == 1) {
   		$actionDomain = e_SELF;
    } else {
    	if ($sandbox == 2) {
    		$actionDomain = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    	} else {
    		$actionDomain = "https://www.paypal.com/cgi-bin/webscr";
    	}
    }

    // Display check out button    
    $f_text = ""; // initialise
	if(($enable_ipn == 2) && ($_SESSION['sc_total']['items'] > 0) && $email_order <> 1){   // IPN addition if IPN_enabled - use AJAX
		$f_text .= "
            <form action='track_checkout.php' method='POST'>
				<!-- <span id='checkoutbutton'> -->
				<div>
					<input type='hidden' name='phpsessionid' value='".session_id()."'/>
					<input type='hidden' name='source_url' value='".urlencode(e_SELF.(e_QUERY ? "?".e_QUERY : ""))."'/>
					<input class='button' type='submit' value='".EASYSHOP_CLASS_01."'/>
				</div>
				<!-- </span> -->
            </form>";            
	} else {
		$f_text = ""; // Initialize variable
		// <form target='_blank' action='$paypalDomain/cgi-bin/webscr' method='post'> leads to XHTML incompatibility caused by target
		$f_text .= "
			<form action='$actionDomain' method='post'>
				<input type='hidden' name='cmd' value='_cart'/>
				<input type='hidden' name='upload' value='1'/>
				<input type='hidden' name='business' value='$paypal_email'/>
				<input type='hidden' name='page_style' value='$payment_page_style'/>";

		// Fill the Cart with products from the basket
		$count_items = count($_SESSION['shopping_cart']); // Count number of different products in basket
		$array = $_SESSION['shopping_cart'];

		$cart_count = 1; // PayPal requires to pass multiple products in a sequence starting at 1
		// For each product in the shopping cart array write PayPal details
		foreach($array as $id => $item) {
			$f_text .= "
				<input type='hidden' name='item_name_".$cart_count."' value='".$item['item_name']."'/>
				<input type='hidden' name='item_number_".$cart_count."' value='".$item['sku_number']."'/>
				<input type='hidden' name='amount_".$cart_count."' value='".$item['item_price']."'/>
				<input type='hidden' name='quantity_".$cart_count."' value='".$item['quantity']."'/>
				<input type='hidden' name='shipping_".$cart_count."' value='".$item['shipping']."'/>
				<input type='hidden' name='shipping2_".$cart_count."' value='".$item['shipping2']."'/>
				<input type='hidden' name='handling_".$cart_count."' value='".$item['handling']."'/>
				<input type='hidden' name='db_id_".$cart_count."' value='".$item['db_id']."' />";
			$cart_count++;
		}

		$thanks_page = str_replace('easyshop.php', 'thank_you.php', e_SELF); // Set thanks page to correct value
		$f_text .= "
				<input type='hidden' name='currency_code' value='$paypal_currency_code'/>
				<input type='hidden' name='no_note' value='1'/>
				<input type='hidden' name='lc' value='US'/>
				<input type='hidden' name='bn' value='PP-ShopCartBF'/>
				<input type='hidden' name='rm' value='1'/>
				<input type='hidden' name='return' value='".$thanks_page."'/>";
	}

	if((!$enable_ipn == 2 || $email_order == 1) && ($_SESSION['sc_total']['items'] > 0)){ // nlstart fix: here too! :)  ### IPN addition if IPN_enabled - use AJAX
		// in case setting always show checkout button is off
		if ($always_show_checkout == 0) {
		// When there are items in the shopping cart, display 'Go to checkout' button
  			if ($_SESSION['sc_total']['items'] > 0) {
				// Only show 'Go to checkout' if total amount is above minimum amount
				if ($_SESSION['sc_total']['sum'] > $minimum_amount) {
					if ($email_order == 1) {
						// Only show enter special instructions if setting is 'On'
						if ($print_special_instr == 1) {
							// Only show special instruction text form in basket edit mode
							if ($action == "edit" && $_SESSION['easyshop_menu'] == false) {
								$f_text .= "
				<table border='0' class='tborder' cellspacing='5'>
					<tr>
						<td class='tborder' style='width: 200px' valign='top'>
							<span class='smalltext' style='font-weight: bold'>
								".EASYSHOP_SHOP_82."
							</span>
							<br />
							".EASYSHOP_SHOP_83."
						</td>
						<td class='tborder' style='width: 200px'>
							<textarea class='tbox' cols='50' rows='2' name='special_instr_text'>$p_special_instr_text</textarea>
						</td>
					</tr>
				</table>";
							} else { // In the easyshop_menu, main cat, cat and product level display a link
								$f_text .= "
				<div style='text-align:center;'>
					<a href='".e_PLUGIN_ABS."easyshop/easyshop.php?edit'>".EASYSHOP_SHOP_82."</a><br /><br />
				</div>";
							}
						}
						$f_text .= "
				<input type='hidden' name='email_order' value='1'/>
				<input class='button' type='submit' value='".EASYSHOP_SHOP_09."'/>";
					}
					if ($enable_ipn == 0 && $email_order <> 1) { // Enable standard checkout button 
    					$f_text .= "
				<input class='button' type='submit' value='".EASYSHOP_SHOP_09."'/>";
					}
  				} else { // Minimum amount has not been reached; inform the shopper
					$f_text .= 
			EASYSHOP_SHOP_36." : ".$unicode_character_before.number_format($minimum_amount, 2, '.', '').$unicode_character_after." <br />
            ".EASYSHOP_SHOP_37." : ".$unicode_character_before.number_format(($minimum_amount - $_SESSION['sc_total']['sum']), 2, '.', '').$unicode_character_after; 
  				}
			}
		}
		// in case setting always display checkout button is on
		if ($always_show_checkout == 1) {
  			$f_text .= "
				<input class='button' type='submit' value='".EASYSHOP_SHOP_09."'/>";
		}
	}
	$f_text .= "
			</form>
			<br />"; // Finally fix the form close issues once and for all v1.54 ;-)

    // Show 'Manage your basket link'
   	if ($_SESSION['sc_total']['items'] > 0 AND $action != "edit") {
    	$f_text .= "
		<div style='text-align:center;'>
			<a href='".e_PLUGIN_ABS."easyshop/easyshop.php?edit'>".EASYSHOP_SHOP_35."</a>";
		// Conditionally show cart icon (dependent on show shopping bag flag)
		if ($show_shopping_bag == '1') {
			$f_text .= "
			&nbsp;<a href='".e_PLUGIN_ABS."easyshop/easyshop.php?edit'><img style='border:0;' src='".e_PLUGIN_ABS."easyshop/images/cart.gif' alt='".EASYSHOP_SHOP_35."'/></a>";
		}  
    	$f_text .= "
		</div>";
    }
    /* // Some debug info
    print_r($_SESSION['shopping_cart']);
    print ("<br />");
    print_r($_SESSION['sc_total']);
    print ("<br />");
    print_r($_SESSION['sc_total']['shipping']);
    print ("<br />");
    print_r($_SESSION['sc_total']['shipping2']);
    print ("<br />");
    print_r($_SESSION['sc_total']['handling']);
    print ("<br />");
    */
    return $f_text;
  }
  
  function show_ipn_checkout($p_session_id) {
    // Parameter $p_session_id is used to check the users' current session ID to prevent XSS vulnarabilities
    //if ($p_session_id != session_id()) { // Get out of here: incoming session id is not equal than current session id
	//	header("Location: ".e_BASE); // Redirect to the home page
	//	exit();
    //}
    // Check query
    if(e_QUERY){
    	$tmp = explode(".", e_QUERY);
    	$action = $tmp[0];
    	unset($tmp);
    }

  	$sql2 = new db;
  	$sql2 -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
  	while($row2 = $sql2-> db_Fetch()){
  		$sandbox = $row2['sandbox'];
    	$paypal_email = $row2['paypal_email'];
    	$payment_page_style = $row2['payment_page_style'];
    	$paypal_currency_code = $row2['paypal_currency_code'];
    	$set_currency_behind = $row2['set_currency_behind'];
		$minimum_amount = intval($row2['minimum_amount']);
		$always_show_checkout = $row2['always_show_checkout'];
		$email_order = $row2['email_order'];
  	}

    $sql3 = new db;
  	$sql3 -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "currency_active=2");
  	while($row3 = $sql3-> db_Fetch()){
  		$unicode_character = $row3['unicode_character'];
  		$paypal_currency_code = $row3['paypal_currency_code'];
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
  	if ($sandbox == 2) {
  		$paypalDomain = "https://www.sandbox.paypal.com";
  	} else {
  		$paypalDomain = "https://www.paypal.com";
  	}

    // Display check out button
    // <form target='_blank' action='$paypalDomain/cgi-bin/webscr' method='post'> leads to XHTML incompatible caused by target
  	$f_text .= "
  	<form action='$paypalDomain/cgi-bin/webscr' method='post'>
		<div>
			<input type='hidden' name='cmd' value='_xclick' />
			<input type='hidden' name='upload' value='1' />
			<input type='hidden' name='business' value='$paypal_email' />
			<input type='hidden' name='page_style' value='$payment_page_style' />";

    // Fill the Cart with products from the basket
    $count_items = count($_SESSION['shopping_cart']); // Count number of different products in basket
    $array = $_SESSION['shopping_cart'];
    // PayPal requires to pass multiple products in a sequence starting at 1
    $cart_count = 1;
    // Set thanks page to correct value
    $thanks_page = str_replace('easyshop.php', 'thank_you.php', e_SELF);
    $cancel_page = str_replace('easyshop.php', 'cancelled.php', e_SELF);
    $ipn_notify_page = str_replace('easyshop.php', 'ipn_notify.php', e_SELF);

    // For each product in the shopping cart array write PayPal details
    foreach($array as $id => $item) {
        $f_text .= "
			<input type='hidden' name='item_name_".$cart_count."' value='".$item['item_name']."' />
			<input type='hidden' name='item_number_".$cart_count."' value='".$item['sku_number']."' />
			<input type='hidden' name='amount_".$cart_count."' value='".$item['item_price']."' />
			<input type='hidden' name='quantity_".$cart_count."' value='".$item['quantity']."' />
			<input type='hidden' name='shipping_".$cart_count."' value='".$item['shipping']."' />
			<input type='hidden' name='shipping2_".$cart_count."' value='".$item['shipping2']."' />
			<input type='hidden' name='handling_".$cart_count."' value='".$item['handling']."' />
			<input type='hidden' name='db_id_".$cart_count."' value='".$item['db_id']."' />";
        $cart_count++;
    }

    $f_text .= "
        <input type='hidden' name='currency_code' value='$paypal_currency_code' />
        <input type='hidden' name='no_note' value='1' />
        <input type='hidden' name='lc' value='US' />
        <input type='hidden' name='bn' value='PP-ShopCartBF' />
        <input type='hidden' name='rm' value='1' />
        <input type='hidden' name='notify_url' value='$ipn_notify_page' />
        <input type='hidden' name='return' value='".$thanks_page."' />
        <input type='hidden' name='cancel_return' value='".$cancel_page."' />
    ";

    if (USER) { // If user is logged in we also include the user id
      $f_text .="<input type='hidden' name='custom' value='".USERID."' />";
    }

    if ($email_order == 0) {
      // in case setting always show checkout button is off
      if ($always_show_checkout == 0) {
      // When there are items in the shopping cart, display 'Go to checkout' button
  			if ($_SESSION['sc_total']['items'] > 0) {
  			  // Only show 'Go to checkout' if total amount is above minimum amount
          if ($_SESSION['sc_total']['sum'] > $minimum_amount) {
  					$f_text .= "
              <input class='button' type='submit' value='".EASYSHOP_SHOP_09."'/>
            </div>
  					</form>
  					<br />";
  				} else { // Minimum amount has not been reached; inform the shopper
  				  $f_text .= EASYSHOP_SHOP_36." : ".$unicode_character_before.number_format($minimum_amount, 2, '.', '').$unicode_character_after." <br />
            ".EASYSHOP_SHOP_37." : ".$unicode_character_before.number_format(($minimum_amount - $_SESSION['sc_total']['sum']), 2, '.', '').$unicode_character_after." <br />";
  				}
        }
      } else { // RC6 Fix for proper closing the form tag
        $f_text .= "</div></form><br />";
      }
    } else { // e-mail the order to admin
      $f_text .= "<a class='button' href='function MailOrder($array)'>".EASYSHOP_SHOP_79."</a></form><br />";
    }
    // in case setting always display checkout button is on
    //else
    if ($always_show_checkout == 1) {
  			$f_text .= "
			<input class='button' type='submit' value='".EASYSHOP_SHOP_09."'/>
  			</form>
  			<br />";
    }
    // Show 'Manage your basket link'
   	if ($_SESSION['sc_total']['items'] > 0 AND $action != "edit") {
    	$f_text .= "
      <div style='text-align:center;'><a href='easyshop.php?edit'>".EASYSHOP_SHOP_35."</a></div>
    	";
    }
	else
	{
		$f_text .= "
		</div>
		</form>
		<br />";
	}
    /* // Some debug info
    print_r($_SESSION['shopping_cart']);
    print ("<br />");
    print_r($_SESSION['sc_total']);
    print ("<br />");
    print_r($_SESSION['sc_total']['shipping']);
    print ("<br />");
    print_r($_SESSION['sc_total']['shipping2']);
    print ("<br />");
    print_r($_SESSION['sc_total']['handling']);
    print ("<br />");
    */
    return $f_text;
  }
  
  function include_prop($prop1_list, $prop1_array, $prop1_prices,$prop1_name,
                        $prop2_list, $prop2_array, $prop2_prices,$prop2_name,
                        $prop3_list, $prop3_array, $prop3_prices,$prop3_name,
                        $prop4_list, $prop4_array, $prop4_prices,$prop4_name,
                        $prop5_list, $prop5_array, $prop5_prices,$prop5_name,
                        $prop6_list, $prop6_array, $prop6_prices,$prop6_name,
                        $unicode_character_before, $unicode_character_after, $item_price ) {
    // Function provides the property select boxes for category and product details and signals if property prices have been used
    for ($n = 1; $n < 6; $n++){
     if (${"prop".$n."_list"} <> "") {
        ${"prop".$n."_array"} = explode(",", ${"prop".$n."_list"});
        if (${"prop".$n."_prices"} <> "") {
          ${"price".$n."_array"} = explode(",", ${"prop".$n."_prices"});
        }
        $text .= "<b>".${"prop".$n."_name"}.":</b> "; // Name of property list
        $text .= "<select class='tbox' name='prod_prop_$n'>";
        // Add an empty value for the property list; check in easyshop_basket if value is selected
        $text .= "<option value=' ' selected='selected'>&nbsp;</option>";
        $arrayLength = count(${"prop".$n."_array"});
        for ($i = 0; $i < $arrayLength; $i++){
            $text .= "<option value='".${"prop".$n."_array"}[$i]."'>".${"prop".$n."_array"}[$i];
            // Display different price if corresponding price delta in properties is found
            if (${"price".$n."_array"}[$i] <> 0) {
              $text .= "&nbsp;".$unicode_character_before.number_format(($item_price+${"price".$n."_array"}[$i]), 2, '.', '')."&nbsp;".$unicode_character_after;
              $property_prices = 1; // There is at least one or more property price detected; use in discounts
            }
            $text .= "</option>";
        }
        $text .= "</select><br />";
     }
   }
   return array($text, $property_prices);
  }

  function include_disc ($discount_id, $discount_class, $discount_valid_from, $discount_valid_till,
                         $discount_code, $item_price, $discount_flag, $discount_percentage, $discount_price,
                         $property_prices, $unicode_character_before, $unicode_character_after, $print_discount_icons){
    // Function provides the discount handling for category and product details and returns discount price (when no discount code is applied)
    // Include selected discount in the product form
    if (isset($discount_id)) { // Include the product discount if it is filled in
      $text .=  "<input type='hidden' name='discount_id' value='".$discount_id."'/>";
      // Determine if user class if applicable for this discount
      if (check_class($discount_class)) {
        // Determine if discount date is valid
        $today = time(); // Record the current date/time stamp
        if ($today > $discount_valid_from and $today < $discount_valid_till) { // This moment is between start and end date of discount
          if ($discount_code <> "") { // Ask the discount code to activate discount
            $text .= "<b>".EASYSHOP_SHOP_50.":</b><br /> <input class='tbox' size='25' type='text' name='discount_code' /><br />"; // Discount code
          } else { // Apply the discount straight away; no discount code needed
            // Adjust item price
            $old_item_price = number_format($item_price, 2, '.', '');
            if ($discount_flag == 1) { // Discount percentage
              $item_price = number_format($item_price -  ( ( $discount_percentage / 100) * $item_price ), 2, '.', '');
            }
            else { // Discount amount
              $item_price = $item_price - $discount_price;
            }
            // Protection against a discount that makes the price negative
            if ($item_price < 0) {
              $item_price = 0;
            }
            if ($property_prices != 1) { // Without variable property prices we can indicate the new price
              // Display From For text
              $text .= EASYSHOP_SHOP_51." ".$unicode_character_before.$old_item_price.$unicode_character_after." ".EASYSHOP_SHOP_52." ".$unicode_character_before.number_format($item_price.$unicode_character_after, 2, '.', '')."<br />";
            } else { // Only able to tell there will be a discount due to unknown property selection with price delta
              $text .= EASYSHOP_SHOP_53." ";
              if ($discount_flag == 1) { // Discount percentage
                $text .= $discount_percentage."%<br />";
              }
              else { // Discount amount
                $text .= $unicode_character_before.number_format($discount_price, 2, '.', '').$unicode_character_after."<br />";
              }
            } // End else/if of property price indications
          } // End else/if of applying the discount immediately

          // Do something special for 'special' percentages when print_discount_icons flag is set
          if ($print_discount_icons == 1){
            $display_text = EASYSHOP_SHOP_53." ".(($discount_percentage>0)?$discount_percentage."%":$unicode_character_before.number_format($discount_price, 2, '.', '').$unicode_character_after);
              if ($discount_flag == 1 AND strstr("_5_10_20_50_", "_".$discount_percentage."_")) {
              $text .= "&nbsp;<img src='".e_PLUGIN_ABS."easyshop/images/offer_".$discount_percentage.".gif' style='height:22px' alt='$display_text' title='$display_text' />";
            } else {
              $text .= "&nbsp;<img src='".e_PLUGIN_ABS."easyshop/images/special_offer.gif' style='height:22px' alt='$display_text' title='$display_text' />";
            }
          } // End if print_discount_icons

        } // End if date is valid: don't calculate the discount if the date is invalid
      } // End if user class is valid: don't calculate the discount if the user class is invalid
    } // End if when discount_id is found
  return array($text,$item_price);
  } // End of function include_disc

}

class Tabs {
	var $name;
	var $tabs;
	var $active;
	var $current;

	function __construct($name){
		$this->name = $name;
	}

	function start($name){
		if (empty($this->active)){ $this->active = $name; }
		$this->current = $name;
		ob_start();
	}

	function end(){
		$this->tabs[$this->current] = ob_get_contents();
		ob_end_clean();
	}

	function run(){
		if (count($this->tabs) > 0){
			$text .= "<DIV CLASS='tabs'>\n";
			$jsClear = "";
			foreach($this->tabs as $tabname => $tabcontent){
				$tabid = "tab_".$this->name."_$tabname";
				$contentid = "tabcontent_".$this->name."_$tabname";
				$jsClear .= "\tdocument.getElementById('$tabid').className = 'tab_inactive';\n";
				$jsClear .= "\tdocument.getElementById('$contentid').style.display = 'none';\n";
			}
			$text .= "<script type=\"text/javascript\">\n";
			$text .= "function tab_".$this->name."(id){\n";
			$text .= "$jsClear";
			$text .= "\tdocument.getElementById('tab_".$this->name."_'+id).className = 'tab_active';\n";
			$text .= "\tdocument.getElementById('tabcontent_".$this->name."_'+id).style.display = '';\n";
			$text .= "}\n";
			$text .= "</script>\n";
			foreach($this->tabs as $tabname => $tabcontent){
				$tabid = "tab_".$this->name."_$tabname";
				$contentid = "tabcontent_".$this->name."_$tabname";
				$text .= "<DIV CLASS='";
				if ($this->active == $tabname){ $text .= "tab_active"; }else{ $text .= "tab_inactive"; }
				$text .= "' ID='$tabid' ";
				$text .= "onClick=\"tab_".$this->name."('$tabname');\">$tabname</DIV>\n";
			}
			$text .= "<DIV STYLE='float: left; clear:both;'></DIV>\n";
			foreach($this->tabs as $tabname => $tabcontent){
				$contentid = "tabcontent_".$this->name."_$tabname";
				$text .= "<DIV ID = '$contentid' CLASS='tab_content' STYLE='display: ";
				if ($this->active == $tabname){ $text .= "block"; }else{ $text .= "none"; }
				$text .= ";'>$tabcontent</DIV>\n";
			}
			$text .= "</DIV>\n";
			$text .= "<DIV STYLE='clear: both;'></DIV>\n";
			return $text;
		}
	}
} // End of class Tabs
class Forms
{
	function add_to_cart_form($prop1_list, $prop1_array, $prop1_prices,$prop1_name,
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
								  $category_order_class, $enable_number_input, $fill_basket)
	{
		$text .= "
			<br />
			<form method='post' action='easyshop_basket.php'>
				<div>";

		// Include selected properties in the product form
		// Function include_prop returns an array! [0] is for $text and [1] is for $property_prices!
		$temp_array = Shop::include_prop($prop1_list, $prop1_array, $prop1_prices,$prop1_name,
								   $prop2_list, $prop2_array, $prop2_prices,$prop2_name,
								   $prop3_list, $prop3_array, $prop3_prices,$prop3_name,
								   $prop4_list, $prop4_array, $prop4_prices,$prop4_name,
								   $prop5_list, $prop5_array, $prop5_prices,$prop5_name,
								   $prop6_list, $prop6_array, $prop6_prices,$prop6_name,
								   $unicode_character_before, $unicode_character_after, $item_price);
		$text .= $temp_array[0];
		$property_prices = $temp_array[1];
		unset($temp_array);

		// Include selected discount in the product form
		// Function include_disc returns an array! [0] is for $text and [1] is for $item_price!
		$temp_array = Shop::include_disc($discount_id, $discount_class, $discount_valid_from, $discount_valid_till,
								   $discount_code, $item_price, $discount_flag, $discount_percentage, $discount_price,
								   $property_prices, $unicode_character_before, $unicode_character_after, $print_discount_icons);
		$text .= $temp_array[0];
		// $item_price = $temp_array[1]; // Bugfix #75
		unset($temp_array);

		// Include also currency sign to send it to the basket
		// Send the product data to the basket
		$text .= "
				<input type='hidden' name='unicode_character_before' value='".$unicode_character_before."'/>
				<input type='hidden' name='unicode_character_after' value='".$unicode_character_after."'/>
				<input type='hidden' name='item_id' value='".$item_id."'/>
				<input type='hidden' name='item_name' value='".$item_name."'/>
				<input type='hidden' name='sku_number' value='".$sku_number."'/>
				<input type='hidden' name='item_price' value='".number_format($item_price, 2, '.', '')."'/>
				<input type='hidden' name='shipping' value='".number_format($shipping_first_item, 2, '.', '')."'/>
				<input type='hidden' name='shipping2' value='".number_format($shipping_additional_item, 2, '.', '')."'/>
				<input type='hidden' name='handling' value='".number_format($handling_override, 2, '.', '')."'/>
				<input type='hidden' name='category_id' value='".$category_id."'/>";
                                
		// IPN addition to include stock tracking option
		if ($item_track_stock== 2 && $enable_ipn == 2)
		{   
			$text .= "
				<input type='hidden' name='item_instock' value='".$item_instock."'>
				<input type='hidden' name='item_track_stock' value='".$item_track_stock."'>";                                      
		}
                            
		// IPN addition to include Item's database ID into session variable
		$text .= "
				<input type='hidden' name='db_id' value='".$db_id."'>
				<input type='hidden' name='fill_basket' value='".$fill_basket."'/>";

		// Include properties lists hidden in the form
		for ($n = 1; $n < 6; $n++)
		{
			$propname = "prop".$n."_name";
			$proplist = "prop".$n."_list";
			$propprices = "prop".$n."_prices";
			$text .= "
				<input type='hidden' name='$propname' value='".${"prop".$n."_name"}."'/>
				<input type='hidden' name='$proplist' value='".${"prop".$n."_list"}."'/>
				<input type='hidden' name='$propprices' value='".${"prop".$n."_prices"}."'/>";
		}

		// Include user id if user is logged in
		if(USER)
		{
			$text .= "
				<input type='hidden' name='custom' value='".USERID."'/>";
		}
                
		if(check_class($category_order_class))
		{	// Only display number and checkout button if user is member of order_class
			if ($enable_number_input == '1') 
			{	// Shop visitor can specify number of products
				$text .= "
				<div class='easyshop_nr_of_prod'>
					".EASYSHOP_SHOP_80.":&nbsp;<input name='item_qty' type='text' value='1' size='2' />
				</div>";
			}
			else 
			{	// Shop adds one product at each click on add button
				$text .= "
				<input type='hidden' name='item_qty' value='1' />";
			}

			$text .= "
				<input type='hidden' name='return_url' value='".e_SELF.(e_QUERY ? '?'.e_QUERY : '')."'/>
				<input class='button' type='submit' value='".EASYSHOP_SHOP_08."'/>";
		}
		$text .= "
				</div>
			</form>";
		return $text;
	}
} // End of class Forms
?>