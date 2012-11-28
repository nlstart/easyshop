<?php
/*
+------------------------------------------------------------------------------+
| EasyShop - an easy e107 web shop  | adapted by nlstart
| formerly known as
|    jbShop - by Jesse Burns aka jburns131 aka Jakle
|    Plugin Support Site: e107.webstartinternet.com
|
|    For the e107 website system visit http://e107.org
|
|    Released under the terms and conditions of the
|    GNU General Public License (http://gnu.org).
|    Code addition by KVN to support nlstart
|    Aug 2008 :- IPN API system, basic reporting and basic Stock Tracking functions
+------------------------------------------------------------------------------+
*/

// Close down checkout after 2 minutes!! - but NOT if there is a $_GET (i.e. an on page call to track_checkout... i.e. continue shopping!!!!
isset($_POST['source_url']) ? header('Refresh: 180; url='.urldecode($_POST['source_url']),TRUE) : NULL; 

// class2.php is the heart of e107, always include it first to give access to e107 constants and variables
require_once('../../class2.php');
require_once('includes/config.php'); 
include_once('includes/ipn_functions.php'); 
include_once("easyshop_class.php");
require_once(HEADERF); 

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
refresh_cart();

  $sql3 = new db;
    $sql3 -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "currency_active=2");
    while($row3 = $sql3-> db_Fetch()){
        $unicode_character = $row3['unicode_character'];
        $paypal_currency_code = $row3['paypal_currency_code'];
    }
	
    session_start();
	$session_id = session_id();  // v1.6 fix  
    
    if(!isset($_POST['target_url'])){
        // Fill the Cart with products from the basket
        $count_items = count($_SESSION['shopping_cart']); // Count number of different products in basket
        $array = $_SESSION['shopping_cart'];
        
        $shop_pref = shop_pref();  // Lazy way to get shop preferences array
        $items_form = process_items($array);  // Prepares Items array AND form text in one go

        $sandbox = $shop_pref['sandbox'];
        if ($sandbox == 2) {
            $paypalDomain = "https://www.sandbox.paypal.com";
        } else {
            $paypalDomain = "https://www.paypal.com";
        }
		
		$set_currency_behind = $shop_pref['set_currency_behind'];
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
		
        $temp_mcgross =  $_SESSION['sc_total']['sum'] + $_SESSION['sc_total']['handling'] 
                            + $_SESSION['sc_total']['shipping'] + $_SESSION['sc_total']['shipping2'];
        $temp_mcgross = number_format($temp_mcgross, 2, '.', '');
        
        $fielddata['mc_gross'] = $temp_mcgross; // Prepare only fields we know at this stage
        $fielddata['mc_currency'] = $paypal_currency_code;  // For the new transaction entry
        $fielddata['receiver_email'] = $shop_pref['paypal_email'];
        $fielddata['custom'] = $session_id; 

        transaction("new", $items_form['db'], $fielddata, "ES_processing"); // Create new transaction into paypal_fields table

        $text = "<div style='text-align:center; width:100%;'>
                <table border='0' cellspacing='0' width='100%'>
                  <tr>
                    <td style='text-align: center;'>".EASYSHOP_TRACK_01."</td>
                    <td style='text-align: center;'>".EASYSHOP_TRACK_02."</td>
                    <td style='text-align: center;'>".EASYSHOP_TRACK_03."</td>
                    <td style='text-align: center;'>".EASYSHOP_TRACK_04."</td>
                    <td style='text-align: center;'>".EASYSHOP_TRACK_05."</td>
                    <td style='text-align: center;'>".EASYSHOP_TRACK_06."</td>
                    <td style='text-align: center;'>".EASYSHOP_TRACK_07."</td>
                  </tr>";
                  
        foreach($array as $id => $item) {
               $text .="    
                  <tr>
                    <td style='text-align: center;'>".$item['sku_number']."</td>
                    <td style='text-align: center;'>".$tp->toHTML($item['item_name'], true)."</td>
                    <td style='text-align: center;'>".$unicode_character_before.number_format($item['item_price'], 2, '.', '').$unicode_character_after."</td>
                    <td style='text-align: center;'>".$item['quantity']."</td>
                    <td style='text-align: center;'>".$unicode_character_before.number_format($item['shipping'], 2, '.', '').$unicode_character_after."</td>
                    <td style='text-align: center;'>".$unicode_character_before.number_format($item['shipping2'], 2, '.', '').$unicode_character_after."</td>
                    <td style='text-align: center;'>".$unicode_character_before.number_format($item['handling'], 2, '.', '').$unicode_character_after."</td>
                  </tr>
                  ";
        }
                  
        $text .="</table>
        <div style='color:red;'><br /><b>".$_SESSION['status']."</b></div>
        
        ";

      	$count_items = count($_SESSION['shopping_cart']);     // Count number of different products in basket
        $sum_quantity = $_SESSION['sc_total']['items'];       // Display cached sum of total quantity of items in basket
        $sum_shipping = $_SESSION['sc_total']['shipping'];    // Display cached sum of shipping costs for 1st item
        $sum_shipping2 = $_SESSION['sc_total']['shipping2'];  // Display cached sum of shipping costs for additional items (>1)
        $sum_handling = $_SESSION['sc_total']['handling'];    // Display cached sum of handling costs
        $sum_shipping_handling = number_format(($sum_shipping + $sum_shipping2 + $sum_handling), 2, '.', ''); // Calculate total handling and shipping price
        $sum_price = number_format(($_SESSION['sc_total']['sum'] + $sum_shipping_handling), 2, '.', ''); // Display cached sum of total price of items in basket + shipping + handling costs
        $average_price = number_format(($sum_price / $sum_quantity), 2, '.', ''); // Calculate the average price per product

        $text .= "
          <br />".EASYSHOP_TRACK_09." ".$sum_quantity."
          <br />".EASYSHOP_TRACK_10." ".$count_items."
          <br />".EASYSHOP_TRACK_11." ".$unicode_character_before.$sum_price.$unicode_character_after."
          <br />".EASYSHOP_TRACK_12." ".$unicode_character_before.$average_price.$unicode_character_after."
        ";
        if ($sum_shipping_handling > 0) {
          $text .= "
            <br />".EASYSHOP_TRACK_13." ".$unicode_character_before.$sum_shipping_handling.$unicode_character_after."<br />";
        }
       // Show checkout button only when there is more than 0 products
       if ($sum_quantity > 0) {
         $text .="
         <br />
         <table border='0' cellspacing='15' width='100%'>
              <tr>
              <form action='".$paypalDomain."/cgi-bin/webscr' method='post'>
                    <input type='hidden' name='cmd' value='_cart' />
                    <input type='hidden' name='upload' value='1' />
                    <input type='hidden' name='business' value='".$shop_pref['paypal_email']."' />
                    <input type='hidden' name='page_style' value='".$shop_pref['payment_page_style']."' />
            ";

         // PayPal requires to pass multiple products in a sequence starting at 1
         $text .=  $items_form['form'];
         // Set thanks page to correct value
         $thanks_page = str_replace('track_checkout.php', 'thank_you.php', e_SELF);
         $return_url = str_replace('track_checkout.php', 'validate.php', e_SELF);

         $text .=  "<input type='hidden' name='currency_code' value='".$paypal_currency_code."' />
                    <input type='hidden' name='no_note' value='1' />
                    <input type='hidden' name='lc' value='US' />
                    <input type='hidden' name='notify_url' value = '".$return_url."' />
                    <input type='hidden' name='rm' value='2' />
                    <input type='hidden' name='return' value='".$thanks_page."' />
                    <input type='hidden' name='custom' value='".session_id()."' />
                    <div style='text-align:center';><input class='button' type='submit' value='".EASYSHOP_TRACK_14."' /></div>
              </form></table>";
         }
         // Show contine shoppping button
         $text .= "<br /><br />
				   <form action='".e_SELF."' method='post'>
				   <div style='text-align:center';>
				   <input type='hidden' name='target_url' value='".$_POST['source_url']."' />
                   <input type='submit' class='button' value='".EASYSHOP_TRACK_15."' /></a>
                   </div>
				   </form>";
    } else {
        // Client has decided to go back shopping- need to amend paypal_fields status to ES_shopping
        $trans_array = transaction($session_id, 0,0,"ES_processing"); // gets all item and fields data into $trans_array
        $trans_array['payment_status'] = "ES_shopping";          // sets new status
        $trans_array['custom'] = $session_id;                   //just in case it isn't already set!!
        $items_array = unserialize($trans_array['all_items']);  //function requires a seperate $items_array (future optimisation?)
                
        transaction("update", $items_array, $trans_array, "ES_shopping");
        header("Location: ".$tp->toDB(urldecode($_POST['target_url'])));
        exit();
    }
              
    $text .="</div> ";
      $title = EASYSHOP_TRACK_08;
      $ns -> tablerender($title, $text);
    
      require_once(FOOTERF);

    /**            keep this in case I ever figure out why AJAX isn't working for 1.3 :)
    //create the XML headers to tell JS what we want to do
  
    isset($_GET['target'])? $ajax_target = $_GET['target'] : $ajax_target="";
    isset($_GET['source'])? $ajax_source = $_GET['source'] : $ajax_source="";
    isset($_GET['arg'])? $ajax_arg = $_GET['arg'] : $ajax_arg="";

    $response = "<menu_name>".$ajax_target."</menu_name>";
    $response .= "<source_id>".$ajax_source."</source_id>";
    $response .= "<arg>".$ajax_arg."</arg>";
    
    $response .= $text;
    
    echo $response;
    **/
?>