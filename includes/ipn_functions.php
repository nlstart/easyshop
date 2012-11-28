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
function shop_pref($action = array())
/** Quick function to get the store's general preferences... keeps main code tidy
 if you pass the preference array to this function it will update the database with the provided values (also handy)
*/
{
  $sql_pref = new db();
  switch ($action) {
    case (!NULL):
    if ($sql_pref -> db_Update("easyshop_preferences",
     "store_name               = '".$action['store_name']."',
      store_address_1          = '".$action['store_address_1']."',
      store_address_2          = '".$action['store_address_2']."',
      store_city               = '".$action['store_city']."',
      store_state              = '".$action['store_state']."',
      store_zip                = '".$action['store_zip']."',
      store_country            = '".$action['store_country']."',
      paypal_email             = '".$action['paypal_email']."',
      paypal_currency_code     = '".$action['paypal_currency_code']."',
      support_email            = '".$action['support_email']."',
      store_image_path         = '".$action['store_image_path']."',
      store_welcome_message    = '".$action['store_welcome_message']."',
      store_info               = '".$action['store_info']."',
      payment_page_style       = '".$action['payment_page_style']."',
      payment_page_image       = '".$action['payment_page_image']."',
      add_to_cart_button       = '".$action['add_to_cart_button']."',
      view_cart_button         = '".$action['view_cart_button']."',
      popup_window_height      = '".$action['popup_window_height']."',
      popup_window_width       = '".$action['popup_window_width']."',
      cart_background_color    = '".$action['cart_background_color']."',
      thank_you_page_title     = '".$action['thank_you_page_title']."',
      thank_you_page_text      = '".$action['thank_you_page_text']."',
      num_category_columns     = '".$action['num_category_columns']."',
      categories_per_page      = '".$action['categories_per_page']."',
      num_item_columns         = '".$action['num_item_columns']."',
      items_per_page           = '".$action['items_per_page']."',
      sandbox                  = '".$action['sandbox']."',
      set_currency_behind      = '".$action['set_currency_behind']."',
      minimum_amount           = '".intval($action['minimum_amount'])."',
      always_show_checkout     = '".$action['always_show_checkout']."',
      email_order              = '".$action['email_order']."',
      product_sorting          = '".$action['product_sorting']."',
      page_devide_char         = '".$action['page_devide_char']."',
      enable_ipn               = '".intval($action['enable_ipn'])."',
      enable_number_input      = '".intval($action['enable_number_input'])."',
      print_special_instr      = '".intval($action['print_special_instr'])."',
      email_info_level         = '".intval($action['email_info_level'])."',
      email_additional_text    = '".$action['email_additional_text']."',
      monitor_clean_shop_days  = '".intval($action['monitor_clean_shop_days'])."',
      monitor_clean_check_days = '".intval($action['monitor_clean_check_days'])."',
      num_main_category_columns= '".intval($action['num_main_category_columns'])."',
      main_categories_per_page = '".intval($action['main_categories_per_page'])."',
      paypal_primary_email 	   = '".$action['paypal_primary_email']."'
      WHERE store_id = '1'")
    )
    {
       //$sql_pref -> db_Close();
       return TRUE;
    }else{
       //$sql_pref -> db_Close();
       return FALSE;
    }
    break;
         
  default:
    $sql_pref -> db_Select("easyshop_preferences", "*", "store_id=1");
    if($row = $sql_pref-> db_Fetch()){
      $shoppref['store_name']               = $row['store_name'];
      $shoppref['store_address_1']          = $row['store_address_1'];
      $shoppref['store_address_2']          = $row['store_address_2'];
      $shoppref['store_city']               = $row['store_city'];
      $shoppref['store_state']              = $row['store_state'];
      $shoppref['store_zip']                = $row['store_zip'];
      $shoppref['store_country']            = $row['store_country'];
      $shoppref['paypal_email']             = $row['paypal_email'];
      $shoppref['paypal_currency_code']     = $row['paypal_currency_code'];
      $shoppref['support_email']            = $row['support_email'];
      $shoppref['store_image_path']         = $row['store_image_path'];
      $shoppref['store_welcome_message']    = $row['store_welcome_message'];
      $shoppref['store_info']               = $row['store_info'];
      $shoppref['payment_page_style']       = $row['payment_page_style'];
      $shoppref['payment_page_image']       = $row['payment_page_image'];
      $shoppref['add_to_cart_button']       = $row['add_to_cart_button'];
      $shoppref['view_cart_button']         = $row['view_cart_button'];
      $shoppref['popup_window_height']      = $row['popup_window_height'];
      $shoppref['popup_window_width']       = $row['popup_window_width'];
      $shoppref['cart_background_color']    = $row['cart_background_color'];
      $shoppref['thank_you_page_title']     = $row['thank_you_page_title'];
      $shoppref['thank_you_page_text']      = $row['thank_you_page_text'];
      $shoppref['num_category_columns']     = $row['num_category_columns'];
      $shoppref['categories_per_page']      = $row['categories_per_page'];
      $shoppref['num_item_columns']         = $row['num_item_columns'];
      $shoppref['items_per_page']           = $row['items_per_page'];
      $shoppref['sandbox']                  = $row['sandbox'];
      $shoppref['set_currency_behind']      = $row['set_currency_behind'];
      $shoppref['minimum_amount']           = $row['minimum_amount'];
      $shoppref['always_show_checkout']     = $row['always_show_checkout'];
      $shoppref['email_order']              = $row['email_order'];
      $shoppref['product_sorting']          = $row['product_sorting'];
      $shoppref['page_devide_char']         = $row['page_devide_char'];
      $shoppref['enable_ipn']               = $row['enable_ipn'];
      $shoppref['enable_number_input']      = $row['enable_number_input'];
      $shoppref['print_special_instr']      = $row['print_special_instr'];
      $shoppref['email_info_level']         = $row['email_info_level'];
      $shoppref['email_additional_text']    = $row['email_additional_text'];
      $shoppref['monitor_clean_shop_days']  = $row['monitor_clean_shop_days'];
      $shoppref['monitor_clean_check_days'] = $row['monitor_clean_check_days'];
      $shoppref['num_main_category_columns']= $row['num_main_category_columns'];
      $shoppref['main_categories_per_page'] = $row['main_categories_per_page'];
      $shoppref['paypal_primary_email'] 	= $row['paypal_primary_email'];
	  }
    //$sql_pref -> db_Close();
    return $shoppref;  
    break;
    }
} 

function transaction($action, $itemdata= array(), $fielddata = array(), $payment_status=NULL, $from_time=NULL, $to_time=NULL)
/**
* @desc Function to pull transactions from database.
 1. new - processes and then creates a new row in database with itemdata and fielddata identified with Session variable and timestamp
 2. update - processes and then updates database with itemdata and fielddata into one row (NOTE: remember to pass the session variable into $field['custom']
 3. all - returns all rows in a database into an array (NOTE: does NOT unserialize itemdata!!!!!)
 4. FORCE_NEW - introduced to allow faulty/fraudulent transactions to be stored in database.
 5. delete - delete specific $payment_status in time period - return false on error
 6. no case - if none of the above, assume a specific phpsessionid has been specified - return false on error
 future - include a case that has a time period option (sessions in date range) - will be useful for a reporting feature 
 */
{
    switch ($action) {
    case "new":
        // serializes the items list so it can be stored in a single field in database
        $tempitemdata = serialize($itemdata);
        isset($payment_status)? NULL : $payment_status = "ES_processing";
        $sqlnew = new db();
        // check that phpsessionid AND payment_status is unique to table - !!! if not this is an update .. NOT new
        if(!$sqlnew -> db_Select("easyshop_ipn_orders","*", "phpsessionid = '".$fielddata['custom']."' AND (payment_status = 'ES_processing' OR payment_status='ES_shopping')")){
          if($sqlnew -> db_Insert("easyshop_ipn_orders",
            array(
                  "mc_gross"         => $fielddata['mc_gross'],
                  "mc_currency"      => $fielddata['mc_currency'],
                  "receiver_email"   => $fielddata['receiver_email'],
                  "phpsessionid"     => $fielddata['custom'],
                  "phptimestamp"     => time(),
                  "payment_status"   => $payment_status,
                  "all_items"        => $tempitemdata,
				  "ipn_user_id"		 => USERID
				  ))){
                    //$sqlnew -> db_Close();
                    return TRUE;
                  }else{
                    //$sqlnew -> db_Close();
                    return FALSE;
                  }
        } else {
          $sqlnew -> db_Update("easyshop_ipn_orders",
                   "mc_gross           ='". $fielddata['mc_gross']."',
                    mc_currency        ='". $fielddata['mc_currency']."',
                    receiver_email     ='". $fielddata['receiver_email']."',
                    phpsessionid       ='". $fielddata['custom']."',
                    phptimestamp       ='". time()."',
                    payment_status     ='". $payment_status."',
                    all_items          ='". $tempitemdata."'
                    WHERE phpsessionid = '".$fielddata['custom']."' AND (payment_status = 'ES_processing' OR payment_status='ES_shopping')");
        }
        break;
    case "update":
      $sqlupdate = new db();
      $tempitemdata = serialize($itemdata);
      $payment_status= "ES_processing"; // This will always be the case with an update!!
	  /// START NEW
	  // Save the original all_items field as it was at creation
	  $sqlupdate -> db_Select("easyshop_ipn_orders", "*", "phpsessionid = '".$fielddata['custom']."'AND payment_status ='".$payment_status."'");
	  if($row = $sqlupdate->db_Fetch()) 
	  {
		$current_all_items = $row['all_items'];
	  }
	  /// END NEW
      if($sqlupdate -> db_Update("easyshop_ipn_orders",
                        "
                        receiver_email   = '".$fielddata['receiver_email']."',
                        payment_status   = '".$fielddata['payment_status']."',
                        pending_reason   = '".$fielddata['pending_reason']."',
                        payment_date     = '".$fielddata['payment_date']."',
                        mc_gross         = '".$fielddata['mc_gross']."',
                        tax              = '".$fielddata['tax']."',
                        mc_currency      = '".$fielddata['mc_currency']."',
                        txn_id           = '".$fielddata['txn_id']."',
                        txn_type         = '".$fielddata['txn_type']."',
                        first_name       = '".$fielddata['first_name']."',
                        last_name        = '".$fielddata['last_name']."',
                        address_street   = '".$fielddata['address_street']."',
                        address_city     = '".$fielddata['address_city']."',
                        address_state    = '".$fielddata['address_state']."',
                        address_zip      = '".$fielddata['address_zip']."',
                        address_country  = '".$fielddata['address_country']."',
                        address_status   = '".$fielddata['address_status']."',
                        payer_email      = '".$fielddata['payer_email']."',
                        payer_status     = '".$fielddata['payer_status']."',
                        payment_type     = '".$fielddata['payment_type']."',
                        notify_version   = '".$fielddata['notify_version']."',
                        verify_sign      = '".$fielddata['verify_sign']."',
                        test_ipn         = '".$fielddata['test_ipn']."',
                        all_items        = '".$current_all_items."',
                        phptimestamp     = '".time()."'
                        WHERE phpsessionid = '".$fielddata['custom']."' AND payment_status ='".$payment_status."'"
                        )){
                          //$sqlupdate -> db_Close();
                          return TRUE;
                        }else{
                          //$sqlupdate -> db_Close();
                          return FALSE;
                        }
          break;
    case "delete":
      if (isset($payment_status)){
         if($payment_status == "EScheck_"){
             // Reformat for MySQL LIKE nomenclature
             $db_payment_status = "payment_status LIKE 'EScheck\_%'";
         } else {
             // If not specified assume they specified a transaction
             $db_payment_status = "payment_status = '".$payment_status."'";
         }
      } else {
          $db_payment_status = ""; // $payment_status not set? fail the db_Delete
      }
      isset($from_time) ? $from_time = " AND phptimestamp <= '".$from_time ."'" :  $from_time ="";
      $sql_delete = new db();
      if($sql_delete -> db_Delete("easyshop_ipn_orders", $db_payment_status.$from_time, FALSE)){
          //$sql_delete -> db_Close();
          return TRUE;
      } else {
          //$sql_delete -> db_Close();
          return FALSE;
      }
      break;
    case "FORCE_NEW" :
      $tempitemdata = serialize($itemdata);
      $sqlforcenew = new db();

      if($sqlforcenew -> db_Insert("easyshop_ipn_orders",
                array(
                  "receiver_email"   => $fielddata['receiver_email'],
                  "payment_status"   => $fielddata['payment_status'],
                  "pending_reason"   => $fielddata['pending_reason'],
                  "payment_date"     => $fielddata['payment_date'],
                  "mc_gross"         => $fielddata['mc_gross'],
                  "tax"              => $fielddata['tax'],
                  "mc_currency"      => $fielddata['mc_currency'],
                  "txn_id"           => $fielddata['txn_id'],
                  "txn_type"         => $fielddata['txn_type'],
                  "first_name"       => $fielddata['first_name'],
                  "last_name"        => $fielddata['last_name'],
                  "address_street"   => $fielddata['address_street'],
                  "address_city"     => $fielddata['address_city'],
                  "address_state"    => $fielddata['address_state'],
                  "address_zip"      => $fielddata['address_zip'],
                  "address_country"  => $fielddata['address_country'],
                  "address_status"   => $fielddata['address_status'],
                  "payer_email"      => $fielddata['payer_email'],
                  "payer_status"     => $fielddata['payer_status'],
                  "payment_type"     => $fielddata['payment_type'],
                  "notify_version"   => $fielddata['notify_version'],
                  "verify_sign"      => $fielddata['verify_sign'],
                  "test_ipn"         => $fielddata['test_ipn'],
                  "all_items"        => $tempitemdata,
                  "phpsessionid"     => $fielddata['custom'],
                  "phptimestamp"     => time(),
				  "ipn_user_id"		 => USERID				  
                  ))){
                      //$sqlforcenew -> db_Close();
                      return TRUE;
                  } else {
                      //$sqlforcenew -> db_Close();
                      return FALSE;
                  }
      break;
    default :
      $sql_sessionid = new db();

      isset($payment_status)? $db_payment_status = "AND payment_status = '".$payment_status."'"
              : $db_payment_status = " AND payment_status = 'ES_processing'";

      if ($sql_sessionid -> db_Select("easyshop_ipn_orders","*","phpsessionid = '".$action."'".$db_payment_status)){
          $transaction = array();
          while($row =  $sql_sessionid -> db_Fetch()){
              foreach ($row as $key => $value) {
                  if(!is_int($key)){
                    $transaction[$key] = $value;
                  }
              }
          }
      } else {
        //$sql_sessionid -> db_Close();
        return FALSE;
        break;
      }
      //$sql_sessionid -> db_Close();
      return $transaction;
      break;
    }
}

function report($action = "all", $limit = 5, $from = NULL, $to = NULL, $phpsessionid = NULL, $txn_id = NULL, $payer_email = NULL)
/**
 Function to automatically generate a report pending on passed variables.
 Output will be a 3 level array - $report[report_type][report_number][report_table], [report_array], [report_count]
 This has potential to be a big script but that's okay, as only the admin will be able to run it currently :)
*/
{
 $shop_pref = shop_pref();
 $set_currency_behind = $shop_pref['set_currency_behind'];
  // Define actual currency and position of currency character once
  $sql_rep = new db();
  $sql_rep -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "currency_active=2");
  if ($row = $sql_rep-> db_Fetch()){
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

 //!isset($action)      ? $action = "all" : $action = "";
 $action == "all"     ? $action = ""                                            : $action = " payer_status = '".$action." '";
 isset($from)         ? $from = " (phptimestamp >= '".$from."' "                : $from= "";
 isset($to)           ? $to = " AND phptimestamp <= '".$to."') "                : $to = "";
 isset($phpsessionid) ? $phpsessionid = " phpsessionid = '".$phpsessionid."' "  : $phpsessionid = "";
 isset($txn_id)       ? $txn_id = " txn_id = '".$txn_id."' "                    : $txn_id = "";
 isset($payer_email)  ? $payer_email = " payer_email = '".$payer_email." '"     : $payer_email = "";
 
 $completed = $processing = $shopping = $escheck = $totals = $rxemail = $dupltxn = $various = 0;
 $arg = "1 " . $action . $phpsessionid . $txn_id . $payer_email . $from . $to . " ORDER BY phptimestamp DESC";

 $count_ipn_rows = $sql_rep -> db_Count("easyshop_ipn_orders");
 if ($count_ipn_rows > 0) {
   $sql_rep -> db_Select("easyshop_ipn_orders","*",$arg);
   while ($row = $sql_rep-> db_Fetch()) {
     $row['items'] = unserialize($row['all_items']);
      if(preg_match("/^EScheck_totals_/", $row['payment_status'])){
        $thiscase = "totals";
      }elseif (preg_match("/^EScheck_rxemail_/", $row['payment_status'])){
        $thiscase = "rxemail";
      }elseif (preg_match("/^EScheck_dupltxn_/", $row['payment_status'])){
        $thiscase = "dupltxn";
      }elseif (preg_match("/^EScheck_/", $row['payment_status'])){
        $thiscase = "EScheck";
      }else {
        $thiscase = $row['payment_status'];
      }

      $text = "";
      isset($row['phpsessionid']) ? $trans_sessionid = $row['phpsessionid'] : $trans_sessionid = $row['custom'];
      $text .= "<tr><td class='forumheader'>
                <b>".EASYSHOP_IPN_01."</b>: ".$row['first_name']." ".$row['last_name']." (".$row['payer_status'].")<br />
                <b>".EASYSHOP_IPN_02."</b>: ".$row['address_name']."  (".$row['address_status'].") <br />
                         ".$row['address_street']."     <br />
                         ".$row['address_zip']."        <br />
                         ".$row['address_city']."       <br />
                         ".$row['address_state']."      <br />
                         ".$row['address_country']."    <br />
                <b>".EASYSHOP_IPN_03."</b>: <a href='mailto:".$row['payer_email']."'>".$row['payer_email']."</a><br />
                <br />
                <b>".EASYSHOP_IPN_04."</b><br />
                ".EASYSHOP_IPN_05." : ".$row['payment_status']."<br />
                ".EASYSHOP_IPN_06." : ".$row['reason_code']."<br />
                ".EASYSHOP_IPN_07." : ".$row['pending_reason']."<br />
                ".EASYSHOP_IPN_08." : ".$row['txn_id']."<br />
                ".EASYSHOP_IPN_09." : ".$trans_sessionid."<br />
                ".EASYSHOP_IPN_10." : ".$row['payment_date']."<br />
                ".EASYSHOP_IPN_11." : ".date("M d Y H:i:s",$row['phptimestamp'])."<br />
                ".EASYSHOP_IPN_12." : ".$unicode_character_before.$row['mc_gross'].$unicode_character_after."<br /></td>
                ";
      $text .="<td class='forumheader2' style='vertical-align: top;'><table style='border:0;cellspacing:15;width:100%;'>
               <tr><td class='forumheader2'><b> ".EASYSHOP_IPN_13." </b></td>
                   <td class='forumheader2'><b> ".EASYSHOP_IPN_14." </b></td>
                   <td class='forumheader2'><b> ".EASYSHOP_IPN_15." </b></td>
                   <td class='forumheader2'><b> ".EASYSHOP_IPN_16." </b></td>
                   <td class='forumheader2'><b> ".EASYSHOP_IPN_17." </b></td>
                   <td class='forumheader2'><b> ".EASYSHOP_IPN_18." </b></td></tr>  ";
      $itemcount = 1;
      $item = $row['items'];

      //preg_match("/^ES_/",$row['payment_status']) ? $paypalfix = "_" : $paypalfix = ""; // Paypal is inconsistent in it's variable naming
	  $paypalfix = "_"; // Paypal Completed order changes; not necessary anymore to make this flexible
      $paypalfix == "" ? $notpaypalfix="_" : $notpaypalfix = "_" ;  // mc_gross_n exists when other variables are item_number(n)

      while (isset($item["item_name".$paypalfix.$itemcount]) || isset($item["item_number".$paypalfix.$itemcount])){
        $text .="<tr><td>".$itemcount."</td>
               <td>".$item["item_name".$paypalfix.$itemcount]."</td>
               <td>".$item["item_number".$paypalfix.$itemcount]."</td>
               <td>".$unicode_character_before.($item["mc_handling".$paypalfix.$itemcount] + $item["mc_shipping".$paypalfix.$itemcount]).$unicode_character_after."</td>
               <td>".$item["quantity".$paypalfix.$itemcount]."</td>
               <td>".$unicode_character_before.$item["amount".$paypalfix.$itemcount].$unicode_character_after."</td></tr>";
        $itemcount ++;
      }
      $text .="</table></td><br />";

      switch ($thiscase) {
        case "Completed":
          $completed ++;
          $report['Completed'][$completed]['report_array'] = $row;
          $report['Completed']['report_count'] = $completed;
          $full_text = "<table class='fborder' width='90%'>
                     <tr><td>
                     <div style='text-align:left;'> <b>".EASYSHOP_IPN_19.": '".$thiscase."'  ".EASYSHOP_IPN_20.": ".$completed." </b></div>
                     ".$text."</tr></table>";
          $report['Completed'][$completed]['report_table'] = $full_text;
          break;

        case "ES_processing":
          $processing ++;
          $report['ES_processing'][$processing]['report_array'] = $row;
          $report['ES_processing']['report_count'] = $processing;
          $full_text = "<table border='1'  style='border: 1px thin;' cellspacing='5' width='100%'>
                     <tr><td>
                     <div style='text-align:left;'> <b>".EASYSHOP_IPN_19.": '".$thiscase."'  ".EASYSHOP_IPN_20.": ".$processing." </b></div>
                     ".$text."</tr></table>";
          $report['ES_processing'][$processing]['report_table'] = $full_text;
          break;

        case "ES_shopping":
          $shopping ++;
          $report['ES_shopping'][$shopping]['report_array'] = $row;
          $report['ES_shopping']['report_count'] = $shopping;
          $full_text = "<table border='1'  style='border: 1px thin;' cellspacing='5' width='100%'>
                     <tr><td>
                     <div style='text-align:left;'> <b>".EASYSHOP_IPN_19.": '".$thiscase."'  ".EASYSHOP_IPN_20.": ".$shopping." </b></div>
                     ".$text."</tr></table>";
          $report['ES_shopping'][$shopping]['report_table'] = $full_text;
          break;

        case "EScheck":
          $escheck ++;
          $report['EScheck'][$escheck]['report_array'] = $row;
          $report['EScheck']['report_count'] = $escheck;
          $full_text = "<table border='1'  style='border: 1px thin;' cellspacing='5' width='100%'>
                     <tr><td>
                     <div style='text-align:left;'> <b>".EASYSHOP_IPN_19.": '".$thiscase."'  ".EASYSHOP_IPN_20.": ".$escheck." </b></div>
                     ".$text."</tr></table>";
          $report['EScheck'][$escheck]['report_table'] = $full_text;
          break;

        case "totals":
          $totals ++;
          $report['totals'][$totals]['report_array'] = $row;
          $report['totals']['report_count'] = $totals;
          $full_text = "<table border='1'  style='border: 1px thin;' cellspacing='5' width='100%'>
                     <tr><td>
                     <div style='text-align:left;'> <b>".EASYSHOP_IPN_19.": '".$thiscase."'  ".EASYSHOP_IPN_20.": ".$totals." </b></div>
                     ".$text."</tr></table>";
          $report['totals'][$totals]['report_table'] = $full_text;
          break;

        case "rxemail":
          $rxemail ++;
          $report['rxemail'][$rxemail]['report_array'] = $row;
          $report['rxemail']['report_count'] = $rxemail;
          $full_text = "<table border='1'  style='border: 1px thin;' cellspacing='5' width='100%'>
                     <tr><td>
                     <div style='text-align:left;'> <b>".EASYSHOP_IPN_19.": '".$thiscase."'  ".EASYSHOP_IPN_20.": ".$rxemail." </b></div>
                     ".$text."</tr></table>";
          $report['rxemail'][$rxemail]['report_table'] = $full_text;
          break;

        case "dupltxn":
          $dupltxn ++;
          $report['dupltxn'][$dupltxn]['report_array'] = $row;
          $report['dupltxn']['report_count'] = $dupltxn;
          $full_text = "<table border='1'  style='border: 1px thin;' cellspacing='5' width='100%'>
                     <tr><td>
                     <div style='text-align:left;'> <b>".EASYSHOP_IPN_19.": '".$thiscase."'  ".EASYSHOP_IPN_20.": ".$dupltxn." </b></div>
                     ".$text."</tr></table>";
          $report['dupltxn'][$dupltxn]['report_table'] = $full_text;
          break;

        default:
          $various ++;
          $report['various'][$various]['report_array'] = $row;
          $report['various']['report_count'] = $various;
          $full_text = "<table border='1'  style='border: 1px thin;' cellspacing='5' width='100%'>
                     <tr><td>
                     <div style='text-align:left;'> <b>".EASYSHOP_IPN_19.": '".$thiscase."'  ".EASYSHOP_IPN_20.": ".$various." </b></div>
                     ".$text."</tr></table>";
          $report['various'][$various]['report_table'] = $full_text;
  	     	break;
      } // End of switch cases
    } // End of while fetching rows for sql_report
  } // End of condition to start the above when count > 0
  return $report;
}

function process_items($itemarray = array())
/**
 Takes the $_SESSION items array
 creates an array with the standard form text generated with paypal variables in $array in $itemdata['form']
 creates an array with all the items id and values needed for storing in database in $itemdata['db']
*/
{
	$cart_count = 1;
	$text = "";               
	// For each product in the shopping cart array write PayPal details
	foreach($itemarray as $id => $item)
	{
		$text .= "
		<input type='hidden' name='item_name_".$cart_count."' value='".$item['item_name']."'>
		<input type='hidden' name='item_number_".$cart_count."' value='".$item['sku_number']."'>
		<input type='hidden' name='amount_".$cart_count."' value='".number_format($item['item_price'], 2, '.', '')."'>
		<input type='hidden' name='quantity_".$cart_count."' value='".$item['quantity']."'>
		<input type='hidden' name='shipping_".$cart_count."' value='".number_format($item['shipping'], 2, '.', '')."'>
		<input type='hidden' name='shipping2_".$cart_count."' value='".number_format($item['shipping2'], 2, '.', '')."'>
		<input type='hidden' name='handling_".$cart_count."' value='".number_format($item['handling'], 2, '.', '')."'>
		<input type='hidden' name='db_id_".$cart_count."' value='".$item['db_id']."'>
		";
		$tempitemdata["item_name_".$cart_count]    = $item["item_name"];
		$tempitemdata["item_number_".$cart_count]  = $item["sku_number"];
		$tempitemdata["quantity_".$cart_count]     = $item["quantity"];
		$tempitemdata["amount_".$cart_count]       = $item["item_price"];
		$tempitemdata["mc_shipping_".$cart_count]  = $item["mc_shipping"];
		$tempitemdata["mc_shipping2_".$cart_count] = $item["mc_shipping2"];
		$tempitemdata["mc_handling_".$cart_count]  = $item["mc_handling"];
		$tempitemdata["tax_".$cart_count]          = $item["tax"];
		$tempitemdata["mc_gross_".$cart_count]     = $item["total"];
		$tempitemdata["db_id_".$cart_count]        = $item["db_id"];
		$cart_count++;
	}
    $itemdata['form'] = $text."<br />";
    $itemdata['db'] = $tempitemdata;            
    return $itemdata;
} 

function update_stock($txn_id = NULL, $phpsessionid = NULL)
/**
 This will only be called on a valid completed transaction from Paypal.
 This function calls the transaction, extracts the ITEM array and
 checks each item to see if tracking is enabled, if so the items stock
 is reduced by the ITEM array quantity and if at zero, the out of stock flag is set.
 If below zero there is an error and someone has paid for an non-existent item :)
 We are heavily dependant on the steps in the payment process to ensure this risk is minimized !!
*/
{
	global $pref;
    $sqlcheck = new db();
    $trans_array = transaction($phpsessionid, 0, 0, "Completed");
    $items_array = unserialize($trans_array['all_items']);
    $count = 1;
	$temp_array = "";   
	while ($items_array["db_id_".$count]){	
		if($sqlcheck -> db_Select("easyshop_items","*", "item_id = '".$items_array["db_id_".$count]."'")){
            while ($row = $sqlcheck -> db_Fetch()){                            
				if ($row['item_track_stock'] == 2){  // Is this a tracked stock item?
					if ($row['item_instock'] >= $items_array["quantity".$count]){                               
						$newstock =  $row['item_instock'] - $items_array["quantity_".$count];
						if($row['item_minimum'] == 0 || $row['item_minimum'] == NULL)
						{	// Minimum level is flexible, but with bottom-level 1.
							$minimum_level = 1;
						}
						else
						{
							$minimum_level = $row['item_minimum'];
						}
						if ($newstock <= $minimum_level && $newstock <> 0){ // Minimum level is reached; send e-mail alert
							ShopMail::easyshop_sendalert($row['item_id'], $newstock, $minimum_level, 1); // Alert-type = 1
						}
						if ($newstock == 0){
							$sqlcheck -> db_Update("easyshop_items", "item_instock = '".$newstock."', item_out_of_stock = '2'
									WHERE item_id = '".$items_array["db_id_".$count]."'");							
							ShopMail::easyshop_sendalert($row['item_id'], $newstock, $minimum_level, 3); // Alert-type = 3

						} else {
							$sqlcheck -> db_Update("easyshop_items", "item_instock = '".$newstock."'
									WHERE item_id = '".$items_array["db_id_".$count]."'");
						}								 
					} else {
						// There is a problem; client has paid for more items than are in stock
						// Raise out of stock flag and send email
						$sqlcheck -> db_Update("easyshop_items", "item_instock = '0', item_out_of_stock = '2'
									WHERE item_id = '".$items_array["db_id_".$count]."'");
						ShopMail::easyshop_sendalert($row['item_id'], $newstock, $minimum_level, 2); // Alert-type = 2
					}    
				}
				if ($row['prod_promo_class'] <> 255 && $row['prod_promo_class'] <> 0 && $trans_array['ipn_user_id'] > 0)
				{	// Auto promotion of user
					$sqlcheck2 = new db;
					$sqlcheck3 = new db;
					require_once(e_HANDLER.'userclass_class.php');
					$promo_class_name = r_userclass_name($row['prod_promo_class']);
					$sqlcheck2 -> db_Select("user","*","user_id='".$trans_array['ipn_user_id']."'");
					if ($row2 = $sqlcheck2 -> db_Fetch())
					{
						$user_name = $row2['user_name'];
						$class_extant = explode(',', $row2['user_class']);
						foreach($class_extant as $key => $value)
						if (intval($value) == 0)
						{
							unset($class_extant[$key]);
						}
						if ($row['prod_promo_class'] <> 255 && $row['prod_promo_class'] <> 0 && !in_array($row['prod_promo_class'], $class_extant))
						{
							$class_extant[] = $row['prod_promo_class'];
						}
						$new_array = array_unique($class_extant);
						sort($new_array);
						$class_list = implode(',', $new_array);
						$sqlcheck3->db_Update("user", "user_class='".$class_list."' where user_id='".$trans_array['ipn_user_id']."'");
						$mailto = (!(isset($pref['siteadminemail']) && strlen($pref['siteadminemail'])==0)?$pref['replyto_email']:$pref['siteadminemail']); // Keep 0.7.8 compatible
						$subject = $pref['sitename'].": ".EASYSHOP_IPN_30; // [USERNAME] upgraded to class [PROMOCLASS]
						$subject = str_replace("[USERNAME]", $user_name, $subject);
						$subject = str_replace("[PROMOCLASS]", $promo_class_name, $subject);
						$message = EASYSHOP_IPN_31."<br /><br />";
						$message .= EASYSHOP_IPN_32."<br /><br />";
						$message .= EASYSHOP_IPN_33."<br />";
						$message .= "<br />".EASYSHOP_IPN_34." ".$pref['siteurl'];
						$message .= "<br /><br />&copy; ".date("Y")." EasyShop";
						$message = str_replace("[USERNAME]", $row2['user_name'], $message);
						$message = str_replace("[USERLINK]", SITEURL."user.php?id.".$trans_array['ipn_user_id'], $message);
						$message = str_replace("[PROMOCLASS]", r_userclass_name($row['prod_promo_class']), $message);
						$message = str_replace("[TRANSACTIONID]", $trans_array['txn_id'], $message);
						$message = str_replace("[PRODUCTPRICE]", $items_array["amount_".$count], $message);
						$message = str_replace("[PRODUCTQTY]", $items_array["quantity_".$count], $message);
						$message = str_replace("[CURRENCY]", $trans_array['mc_currency'], $message);
						$message = str_replace("[GROSSAMOUNT]", $trans_array['mc_gross'], $message);
						$message = str_replace("[PAYMENTDATE]", $trans_array['payment_date'], $message);
						$message = str_replace("[PRODLINK]", SITEURL.e_PLUGIN."easyshop/easyshop.php?prod.".$items_array["db_id_".$count], $message);
						$message = str_replace("[PRODUCTNAME]", $items_array["item_name_".$count], $message);
						$user_message = EASYSHOP_IPN_35;
						$user_message .= "<br />".EASYSHOP_IPN_36;
						$user_message .= "<br />".EASYSHOP_IPN_34." ".$pref['siteurl'];
						$user_message .= "<br /><br />&copy; ".date("Y")." EasyShop";
						$user_message = str_replace("[PRODUCTNAME]", $items_array["item_name_".$count], $user_message);
						$user_message = str_replace("[PROMOCLASS]", r_userclass_name($row['prod_promo_class']), $user_message);
						//ShopMail::easyshop_sendemail($mailto, $subject, $message, $headers2, $attachment_name);
						if ($row2['user_class'] <> $class_list) // Compare old to new
						{	// Only send an e-mail if the user_class array actually changed						
							ShopMail::easyshop_sendemail($mailto, $subject, $message, $headers2, $attachment_name); // Mail to admin
							ShopMail::easyshop_sendemail($row2['user_email'], $subject, $user_message, $headers2, $attachment_name); // Mail to user
						}
					}		
				}
				$temp_array = Array ( $row['item_id'] => Array ( "item_name" => $items_array["item_name_".$count], "db_id" => $row['item_id'] ) ) ;
			}
        } else {
			// This item does not exist!!!
			//$sqlcheck -> db_Close();
			return FALSE;
		}    
		$count ++;
    }
    // Send downloads
    $to_email = $trans_array['payer_email'];
    ShopMail::easyshop_senddownloads($temp_array, $to_email);
    //$sqlcheck -> db_Close();
    return TRUE;
}

function refresh_cart()
/**
 This function will check the shopping cart against the items table
 If there is a mismatch (e.g. product has been made inactive)
 the session variable is updated directly by the function
*/
{
    // Stop caching for all browsers
    session_cache_limiter('nocache');
    // Start a session to catch the basket
    session_start();

    $items = $_SESSION['shopping_cart'];
    $sql_refresh = new db();

    $text = $_SESSION['status'] = "";

    foreach ($items as $value) {  // In each following step, $value will represent the historic value and $_SESSION will be updated with new value
            $sql_refresh -> db_Select("easyshop_items", "*", "item_id=".$value['db_id']);
            $row = $sql_refresh -> db_Fetch();
            
            // If it has property... don't refresh line!
            if (!($row['prod_prop_1_id'] || $row['prod_prop_2_id'] 
                || $row['prod_prop_3_id'] || $row['prod_prop_4_id'] || $row['prod_prop_5_id'])){    
                // Check if item has been renamed - change cart details
                if(($row['item_name'] <> $value['item_name']) || ($row['sku_number'] <> $value['sku_number'])){
                    $row['item_name'] <> $value['item_name'] ? $text .= $value['item_name']." ".EASYSHOP_IPN_28." ".$row['item_name'].". ".EASYSHOP_IPN_29."<br/>" : NULL;
                    $row['item_sku'] <> $value['item_sku'] ? $text .= $value['sku_number']." ".EASYSHOP_IPN_28." ".$row['sku_number'].". ".EASYSHOP_IPN_29."<br/>": NULL;
                    $_SESSION['shopping_cart'][$value['db_id']]['item_name'] = $row['item_name'];                                        
                    $_SESSION['shopping_cart'][$value['db_id']]['sku_number'] = $row['sku_number'];
                }  
                // Check if item's in stock and track stock info has changed - update cart (we don't delete entries here!!
                if(($row['item_track_stock'] <> $value['item_track_stock']) || ($row['item_instock'] <> $value['item_instock'])){
                    $row['item_track_stock'] <> $value['item_track_stock'] ? $_SESSION['shopping_cart'][$value['db_id']]['item_track_stock'] = $row['item_track_stock']:  $_SESSION['shopping_cart'][$value['db_id']]['item_instock'] = $row['item_instock'];
                // No update of text here, done in the quantity check later... if quantity is reduced we mention it's due to stock
                } 

                // Check price,handling,shipping and quantity and update totals accordingly - alert client
                if(($row['item_price'] <> $value['item_price']) || ($row['shipping_first_item'] == $value['shipping'])
                    || ($row['shipping_additional_item'] == $value['shipping2']) || ($row['handling_override'] == $value['handling'])
                    || ($row['item_instock'] < $value['quantity'])){
                        // Update any quantity change and alert client, when item track stock settings is true
                        if(($row['item_instock'] < $value['quantity']) && ($row['item_track_stock'] == 2)){
                            $text .= EASYSHOP_IPN_21a." ".$value['item_name']." ".EASYSHOP_IPN_21b." ".$row['item_instock'].". ".EASYSHOP_IPN_22."<br/>";
                            $_SESSION['shopping_cart'][$value['db_id']]['quantity'] = $row['item_instock']; // Adjust the shopping cart
                            $_SESSION['sc_total']['items'] = ($_SESSION['sc_total']['items']) - $value['quantity'] + $row['item_instock']; // Update total count too!
                        }
                        // Update any item_price change and alert client
                        if($row['item_price'] <> $value['item_price']){
                            $text .= $row['item_name']." ".EASYSHOP_IPN_PRICEFROM." ".$value['item_price']." ".EASYSHOP_IPN_PRICETO." ".$row['item_price']."<br/>";
                            $_SESSION['shopping_cart'][$value['db_id']]['item_price'] = $row['item_price'];
                        }
                        // Update any shipping, shipping2 or handling change and alert client
                        if($row['shipping_first_item'] <> $value['shipping']){
                            $text .= EASYSHOP_IPN_23." ".$row['item_name']
                            ." ".EASYSHOP_IPN_PRICEFROM." ".$value['shipping']
                            ." ".EASYSHOP_IPN_PRICETO." ".$row['shipping_first_item']."<br/>";
                            $_SESSION['shopping_cart'][$value['db_id']]['shipping'] = $row['shipping_first_item'];
                        }
                        if($row['shipping_additional_item'] <> $value['shipping2']){
                            $text .= EASYSHOP_IPN_24." ".$row['item_name']
                            ." ".EASYSHOP_IPN_PRICEFROM." ".$value['shipping2']
                            ." ".EASYSHOP_IPN_PRICETO." ".$row['shipping_additional_item']."<br/>";
                            $_SESSION['shopping_cart'][$value['db_id']]['shipping2'] = $row['shipping_additional_item'];
                        }
                        if($row['handling_override'] <> $value['handling']){
                            $text .= EASYSHOP_IPN_25." ".$row['item_name']
                            ." ".EASYSHOP_IPN_PRICEFROM." ".$value['handling']
                            ." ".EASYSHOP_IPN_PRICETO." ".$row['handling_override']."<br/>";
                            $_SESSION['shopping_cart'][$value['db_id']]['handling'] = $row['handling_override'];
                        } 
                    }
                    // Check if item is Out of Stock or inactive? update sc_items  (delete item last!!)
                    if (($row['item_out_of_stock'] == 2) || ($row['item_active_status'] <> 2)){  
                      $row['item_out_of_stock'] == 2 ? $text .= $row['item_name']." ".EASYSHOP_IPN_26." ".EASYSHOP_IPN_22."<br/>"
                                                     : $text .= $row['item_name']." ".EASYSHOP_IPN_27." ".EASYSHOP_IPN_22."<br/>";
                                                        
                      $_SESSION['sc_total']['items'] = $_SESSION['sc_total']['items'] - $value['quantity'];
                      $_SESSION['shopping_cart'][$value['db_id']]['quantity'] = 0;
                      $_SESSION['shopping_cart'][$value['db_id']]['handling'] = 0;                  
                      $_SESSION['shopping_cart'][$value['db_id']]['shipping'] = 0;
                      $_SESSION['shopping_cart'][$value['db_id']]['shipping2'] = 0;
                    }
                    // Perform calculations with above figures to update the sc_totals
                    $old_sum = $_SESSION['sc_total']['sum'];
                    $item_total = $_SESSION['sc_total']['items'];
                    $old_handling_total = $_SESSION['sc_total']['handling'];
                    $old_shipping_total = $_SESSION['sc_total']['shipping'];
                    $old_shipping2_total = $_SESSION['sc_total']['shipping2'];
                    // Calculate new sc_totals values
                    $new_sum = ($old_sum - ($value['quantity'] * $value['item_price']))
                                + ($_SESSION['shopping_cart'][$value['db_id']]['quantity'] * $_SESSION['shopping_cart'][$value['db_id']]['item_price']);
                    $new_handling_total = ($old_handling_total - $value['handling']) + $_SESSION['shopping_cart'][$value['db_id']]['handling'];
                    $new_shipping_total = ($old_shipping_total - $value['shipping']) + $_SESSION['shopping_cart'][$value['db_id']]['shipping'];            
                    $new_shipping2_total = ($old_shipping2_total - ($value['shipping2'] * ($value['quantity']-1))) 
                                + ($_SESSION['shopping_cart'][$value['db_id']]['shipping2'] * ($_SESSION['shopping_cart'][$value['db_id']]['quantity']-1));
                    // Update card with new values
                    $_SESSION['sc_total']['sum']       = $new_sum;
                    $_SESSION['sc_total']['handling']  = $new_handling_total;
                    $_SESSION['sc_total']['shipping']  = $new_shipping_total;
                    $_SESSION['sc_total']['shipping2'] = $new_shipping2_total;
                    if(($row['item_out_of_stock'] == 2) || ($row['item_active_status'] <> 2)) {
						unset($_SESSION['shopping_cart'][$value['db_id']]);
                    }
                    // Also remove product from shopping cart when ordered quantity equals zero
                    if ($_SESSION['shopping_cart'][$value['db_id']]['quantity'] <= 0) {
						unset($_SESSION['shopping_cart'][$value['db_id']]);
                    }
            } // End of if with properties
    } // End of for each item
    $_SESSION['status'] = $text;
}     
?>