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
|    Aug 2008 :- IPN API and basic Stock Tracking functions
+------------------------------------------------------------------------------+
*/
// class2.php is the heart of e107, always include it first to give access to e107 constants and variables
require_once('../../class2.php');

// Include auth.php rather than header.php ensures an admin user is logged in
require_once(HEADERF);
require_once('includes/config.php');

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
// Read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
require_once('includes/ipn_functions.php');
require_once('easyshop_class.php');

foreach ($_POST as $key => $value) {
	$value = urlencode(stripslashes($value));
	$req .= "&$key=$value";
}
$log = fopen("ipn.log", "a");
fwrite($log, "\n\nipn - " . gmstrftime ("%b %d %Y %H:%M:%S", time()));

// Retrieve the sandbox setting from the shop preferences
$sql = new db;
$sql -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
if ($row = $sql-> db_Fetch()) {
	$sandbox = $row['sandbox'];
	$paypal_primary_email = $row['paypal_primary_email'];
}
if ($sandbox == 2) {
	$actionDomain = "www.sandbox.paypal.com";
} else {
	$actionDomain = "www.paypal.com";
}

// Post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Host: ".$actionDomain."\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

$fp = fsockopen ('ssl://'.$actionDomain, "443", $errno, $errstr, 30);
if (!$fp) {
	// HTTP ERROR: Failed to open connection
	fwrite($log, "\n".EASYSHOP_VAL_01."\n ".EASYSHOP_VAL_02.":". $errno .", ".EASYSHOP_VAL_03.":". $errstr);
} else {
	fputs ($fp, $header . $req);
	fwrite($log, "\n ".EASYSHOP_VAL_04); // Written POST to paypal
	while (!feof($fp)) 
	{
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) 
		{
			fwrite($log, "\n ".EASYSHOP_VAL_05); // Paypal response VERIFIED
			// Loop through the $_POST array and store all vars to arrays $fielddata and $itemdata.
			$sql = new db;
			$sql2 = new db;
			$fielddata = array();
			$itemdata = array();
			foreach($_POST as $key => $value){ // Arrange fields and items into seperate arrays
				$value = $tp -> toDB($value);
				if (ereg( "[0-9]{1,3}$",$key)) { // Any item with one or more digits is an item
					$itemdata[$key] = $value;    // not sure how handling2 will be received !!
				} else {                         // Else it's a generic field for the transaction
					$fielddata[$key] = $value;
				}
			}
			// Check if the payment_status is Completed
			if ($fielddata['payment_status'] == "Completed"){
				// Check if txn_id has not been previously processed
				$needle = $fielddata['txn_id']; // Assign needle to $needle for pre PHP 4.2
				$stored_trans = transaction("all", $itemdata, $fielddata, "ES_processing"); // Get all transactions (limit to 3 day window in future?)
				if (!in_array($needle,$stored_trans )){
					// Check if receiver_email is your Primary PayPal e-mail
					$this_trans = transaction($fielddata['custom'],null,null, "ES_processing"); // Get the specific transaction
				  
					if ($fielddata['receiver_email'] == $this_trans['receiver_email'] || $fielddata['business'] == $this_trans['receiver_email'] ||
						$fielddata['receiver_email'] == $paypal_primary_email  || $fielddata['business'] == $paypal_primary_email ||
						$this_trans['receiver_email'] == $paypal_primary_email  || $this_trans['business'] == $paypal_primary_email){	  
						// Check if totals and currency used are as expected
						if(($this_trans['mc_gross'] == $fielddata['mc_gross']) && ($this_trans['mc_currency'] == $fielddata['mc_currency'])) {
							transaction("update", $itemdata, $fielddata, "ES_processing");
							$stock_updated = update_stock($fielddata['txn_id'], $fielddata['custom']);
							!$stock_updated? fwrite($log, "\n ".EASYSHOP_VAL_06.":".$fielddata['custom']."\n \n") : fwrite($log, "\n ".EASYSHOP_VAL_07." \n \n"); // Message: Stock update failed with session id OR Stock updated successfully
						} else { // Totals or currency doesn't match - user intervention required - update monitor - send admin email?
							$fielddata['payment_status'] = "EScheck_totals_".$fielddata['payment_status'];
							transaction("FORCE_NEW", $itemdata, $fielddata);
							fwrite($log, "\n ".EASYSHOP_VAL_08.":".$fielddata['mc_gross']."\n \n"); // mc_gross doesn't match rxd mc_gross
							// Totals or currency doesn't match - user intervention required - update monitor - send admin email?
						}
					} else {
						// Receiver e-mail doesn't match - could be fraudulent - update monitor - send admin email?
						$fielddata['payment_status'] = "EScheck_rxemail_".$fielddata['payment_status'];
						transaction("FORCE_NEW", $itemdata, $fielddata);
						if ( $fielddata['receiver_email'] == "") {
							// Local Entry has already been Completed or doesn't exist
							// This could be a fraudalent entry or more likely 'a double hit' on the confirm order button!
							// Customer may need a refund/Credit Card chargeback!
							fwrite($log, "\n ".EASYSHOP_VAL_09." \n
							".EASYSHOP_VAL_10."\n
							".EASYSHOP_VAL_11."\n \n");
						} else {
							fwrite($log, "\n ".EASYSHOP_VAL_12.": this_transreceiver: ".$this_trans['receiver_email']." fielddata_receiver:".$fielddata['receiver_email']."\n \n
										  \n ".EASYSHOP_VAL_12.": this_business: ".$this_trans['business']." fielddata_business:".$fielddata['business']."\n \n
										  \n ".EASYSHOP_VAL_12.": paypal_primary_email: ".$paypal_primary_email."\n \n"); // Business Email mismatched rxd email // Receiver Email mismatched rxd email
						}
					}	
				} else {
					// This is a duplicate txn_id - possibly fraudulent - update monitor - send admin email?
					$fielddata['payment_status'] = "EScheck_dupltxn_".$fielddata['payment_status'];
					transaction("FORCE_NEW", $itemdata, $fielddata );
					fwrite($log, "\n ".EASYSHOP_VAL_13."\n \n"); // Duplicate txn_id!
				}
			} else {
				// Store transaction and update store monitor of incomplete transaction - send admin an email also?
				$fielddata['payment_status'] = "EScheck_".$fielddata['payment_status'];
				if(transaction("update", $itemdata, $fielddata, "ES_processing")){
					fwrite($log, "\n ".EASYSHOP_VAL_14.":".$fielddata['payment_status']."\n \n"); // Payment status not 'Completed' status
				} else {
					transaction("FORCE_NEW", $itemdata, $fielddata);
					// Payment status not 'Completed' status
					// LOCAL ENTRY NOT PRESENT!
					fwrite($log, "\n ".EASYSHOP_VAL_14.":".$fielddata['payment_status']."\n
					".EASYSHOP_VAL_15."\n \n");
				}
			}
			// if logfile is enabled... user must make sure it's secure a future option perhaps
			// fwrite($log, "\n".(print_r($fielddata, true))."\n".(print_r($itemdata, true)));      
		} else if (strcmp ($res, "INVALID") == 0) {
			// Paypal response 'INVALID'; log for manual investigation
			fwrite($log, "\n ".EASYSHOP_VAL_16."\n \n");
		}
	}
	fclose ($fp);
}
fclose($log);
require_once(FOOTERF);
?>