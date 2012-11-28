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

// Include the class for showing a calender
require_once(e_HANDLER."calendar/calendar_class.php");
$cal = new DHTML_Calendar(true);
function headerjs()
{
	global $cal;
	return $cal->load_files();
}

// Include auth.php rather than header.php ensures an admin user is logged in
require_once(e_ADMIN.'auth.php');

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
require_once('includes/config.php');

// Load the easyshop class
require_once('easyshop_class.php');

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_05';

// Check URL query
if(e_QUERY){
	$tmp = explode(".", e_QUERY); // Divide the URL query in separate arrays e.g. admin_discounts.php?edit.5
	$action = $tmp[0];    // e.g. $action = 'edit'
	$action_id = $tmp[1]; // e.g. $action_id = '5'
	$page_id = $tmp[2];   // e.g. $page_id = '3'  (not used in admin_discounts)
	unset($tmp); // unset the arrays, so next time URL query will be determined as new
}

// Include userclass_class.php which is necessary for function r_userclass (dropdown of classes)
require_once(e_HANDLER.'form_handler.php');
require_once(e_HANDLER.'userclass_class.php');
require_once(e_HANDLER.'file_class.php');

// Define actual currency and position of currency character once
$sql = new db;
$sql -> db_Select(DB_TABLE_SHOP_CURRENCY, "*", "currency_active=2");
if ($row = $sql-> db_Fetch()){
	$unicode_character = $row['unicode_character'];
	$paypal_currency_code = $row['paypal_currency_code'];
}

// Get some settings from preference table
$sql -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
if ($row = $sql-> db_Fetch()){
    $set_currency_behind = $row['set_currency_behind'];
    $print_discount_icons = $row['print_discount_icons'];
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

// ----------------------------------------------------------------------------+
// ---------------------- Create and update records ---------------------------+
// ----------------------------------------------------------------------------+
if (isset($_POST['update_disc']) or isset($_POST['create_new'])) { // Update the fields from the edit or create mode
  // Check valid prices in the array first
  foreach ($_POST['discount_price'] as $key=>$value) {
    if (General::validateDecimal($value)) {
    // This is a valid price with 2 decimals
    } else {
    // Alert: Invalid price! (just alert once)
    $text = EASYSHOP_ADMIN_DISC_27."<br />";
    }
  }
  // Check if discount name is filled in
  if (strlen(trim($_POST['discount_name'])) == 0 ) {
    // Alert: name is invalid!
    $text .= EASYSHOP_ADMIN_DISC_30."<br />";
  }
  // Check if percentage is not above 100%
  if ($_POST['discount_flag'] == 1) { // It is a percentage
    if ($_POST['discount_amount'] > 100) {
      $text .= EASYSHOP_ADMIN_DISC_24."<br />";
    }
  }
  // Check if discount (percentage or amount) is not negative
  if ($_POST['discount_amount'] < 0) {
      $text .= EASYSHOP_ADMIN_DISC_25."<br />";
  }
  // Check if date from is filled in
  if ($_POST['discount_valid_from'] < 0 or $_POST['discount_valid_from'] == EASYSHOP_ADMIN_DISC_31 ) {
      $text .= EASYSHOP_ADMIN_DISC_36."<br />";
  }
  // Check if date till is filled in
  if ($_POST['discount_valid_till'] < 0 or $_POST['discount_valid_till'] == EASYSHOP_ADMIN_DISC_31 ) {
      $text .= EASYSHOP_ADMIN_DISC_37."<br />";
  }
  // Check if date till is not before date from
  if ($_POST['discount_valid_till'] < $_POST['discount_valid_from'] ) {
      $text .= EASYSHOP_ADMIN_DISC_38."<br />";
  }
  if ($text <> "") {
    $text .= "<br /><center><input class='button' type=button value='".EASYSHOP_ADMIN_DISC_28."' onClick='history.go(-1)'></center>";
   	// Render the value of $text in a table.
    $title = EASYSHOP_ADMIN_DISC_29;
    $ns -> tablerender($title, $text);
    require_once(e_ADMIN.'footer.php');
    // Leave on error
    exit();
  }
  // Determine the price or percentage
  if ($_POST['discount_flag'] == 0) {
    $discount_price = $_POST['discount_amount'];
    $discount_percentage = 0;
  } else {
    $discount_price = 0;
    $discount_percentage = $_POST['discount_amount'];
  }

  if ($_POST['discount_valid_from'] > 0 ) {
  	$tmp = explode("/", $_POST['discount_valid_from']); // Divide the from date in separate arrays
  	$year_from = $tmp[0];    // e.g. 2008
  	$month_from = $tmp[1]; // e.g. 06
  	$day_from = $tmp[2];   // e.g. 30
  	unset($tmp); // unset the arrays, so next time tmp will be determined as new
    $_POST['discount_valid_from'] = mktime(0,0,0,$month_from,$day_from,$year_from); // From start of day
  }

  if ($_POST['discount_valid_till'] > 0 ) {
  	$tmp = explode("/", $_POST['discount_valid_till']); // Divide the till date in separate arrays
  	$year_till = $tmp[0];    // e.g. 2008
  	$month_till = $tmp[1]; // e.g. 06
  	$day_till = $tmp[2];   // e.g. 30
  	unset($tmp); // unset the arrays, so next time tmp will be determined as new
    $_POST['discount_valid_till'] = mktime(23,59,59,$month_till,$day_till,$year_till); // Till end of day
  }
  
  if (isset($_POST['create_new'])) { // Create a new record in Discounts table
    $sql -> db_Insert(easyshop_discount,
    "0,
    '".$tp->toDB($_POST['discount_name'])."',
		'".$tp->toDB($_POST['discount_class'])."',
		'".$tp->toDB($_POST['discount_flag'])."',
		'".$tp->toDB($discount_price)."',
		'".$tp->toDB($discount_percentage)."',
		'".$tp->toDB($_POST['discount_valid_from'])."',
		'".$tp->toDB($_POST['discount_valid_till'])."',
		'".$tp->toDB(trim($_POST['discount_code']))."'
    ") or die(mysql_error());
  }
  if (isset($_POST['update_disc'])) { // Update the existing record in Discounts table
    // Actual update
    $sql -> db_Update(easyshop_discount,
    "discount_name='".$tp->toDB($_POST['discount_name'])."',
		discount_class='".intval($tp->toDB($_POST['discount_class']))."',
		discount_flag='".$tp->toDB($_POST['discount_flag'])."',
		discount_price='".$tp->toDB($discount_price)."',
		discount_percentage='".$tp->toDB($discount_percentage)."',
		discount_valid_from='".$tp->toDB($_POST['discount_valid_from'])."',
		discount_valid_till='".$tp->toDB($_POST['discount_valid_till'])."',
		discount_code='".$tp->toDB(trim($_POST['discount_code']))."'
		WHERE discount_id='".$tp->toDB($_POST['discount_id'])."'");
  }
  $upd_message = EASYSHOP_ADMIN_DISC_13; // Discount information saved message
}

// Displays the update message to confirm data is stored in database
if (isset($upd_message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$upd_message."</b></div>");
  header("Location: admin_discounts.php");
}

// ----------------------------------------------------------------------------+
// ---------------------------- Delete records --------------------------------+
// ----------------------------------------------------------------------------+
if ($action == 'delete') {
  	// Verify deletion before actual delete
    $delete_text = "
    <br /><br />
    <center>
        ".EASYSHOP_ADMIN_DISC_20."
        <br /><br />
        <table width='100'>
            <tr>
                <td>
                    <a href='admin_discounts.php?delete_final.".$action_id."'>".EASYSHOP_ADMIN_DISC_21."</a>
                </td>
                <td>
                    <a href='admin_discounts.php'>".EASYSHOP_ADMIN_DISC_22."</a>
                </td>
            </tr>
        </table>
    </center>";

    // Render the value of $delete_text in a table.
    $title = "<b>".EASYSHOP_ADMIN_DISC_19."</b>";
    $ns -> tablerender($title, $delete_text);

}
if ($action == 'delete_final') {
	// Variable delete_final is set if answer equals Yes
    // Delete discount from discount table
    $sql -> db_Delete(easyshop_discount, "discount_id=$action_id");
    // Delete discount from product table fields
    $sql->db_Update(easyshop_items, "prod_discount_id='' WHERE prod_discount_id=$action_id");
    header("Location: admin_discounts.php");
}

// ----------------------------------------------------------------------------+
// ---------------------- Edit or Maintain Discount ---------------------------+
// ----------------------------------------------------------------------------+
if ($action == 'edit') {
	$arg="SELECT *
        FROM #easyshop_discount
        WHERE discount_id = $action_id";
  $sql->db_Select_gen($arg,false);
	if($row = $sql-> db_Fetch()){
    $discount_id = $row['discount_id'];
    $discount_name = $row['discount_name'];
    $discount_class = $row['discount_class'];
    $discount_flag = intval($row['discount_flag']);
    $discount_price = number_format($row['discount_price'], 2, '.', '');
    $discount_percentage = number_format($row['discount_percentage'], 2, '.', '');
    $discount_valid_from = ($row['discount_valid_from'] > 0) ? date("Y/m/d", $row['discount_valid_from']) : "";
  	$discount_valid_till = ($row['discount_valid_till'] > 0) ? date("Y/m/d", $row['discount_valid_till']) : "";
  	$discount_code = $row['discount_code'];
	}
  if ($discount_flag == 0) {
    $discount_amount = $discount_price;
  } else {
    $discount_amount = $discount_percentage;
  }
	
	$text .= "
	<form name='update_disc' method='POST' action='".e_SELF."'>
		<center>
			<div style='width:80%'>
				<fieldset>
					<legend>
						".EASYSHOP_ADMIN_DISC_17."
					</legend>";
  $text .= table_discounts($discount_id, $discount_name, $discount_class, $discount_flag, $discount_amount, $discount_valid_from, $discount_valid_till, $discount_code, $cal);
  $text .= "
				<br />
				<center>
          <input type='hidden' name='update_disc' value='1'/>
          <input type='hidden' name='discount_id' value='".$discount_id."'/>
					<input class='button' type='submit' value='".EASYSHOP_ADMIN_DISC_18."'/>
					&nbsp;<a href='admin_discounts.php'>".EASYSHOP_ADMIN_DISC_26."</a>
				</center>
				<br />
				</fieldset>
			</div>
		</center>
	</form>";

	// Render the value of $edit_text in a table.
	$title = EASYSHOP_ADMIN_DISC_17;
	$ns -> tablerender($title, $text);
	
} else {
  // --------------------------------------------------------------------------+
  // ----------------------- Overview Discounts -------------------------------+
  // --------------------------------------------------------------------------+

  // Determine if there are no discounts
	if($sql -> db_Count(easyshop_discount) > 0) {
		$no_discounts = 1;
	}

	$text .= "
  <form name='overview_disc' method='POST' action='".e_SELF."'>
		<center>
				<fieldset>
					<legend>
						".EASYSHOP_ADMIN_DISC_01."
					</legend>";
          // Show a message if there are no discounts to display
					if ($no_discounts == null) {
						$text .= "
						<br />
						<center>
							<span class='smalltext'>
								".EASYSHOP_ADMIN_DISC_02."
							</span>
						</center>
						<br />";
					} else {
						$text .= "
						<center>
						  <table style='".ADMIN_WIDTH."' class='fborder'>
							<tr>
									<td class='fcaption'><b>".EASYSHOP_ADMIN_DISC_04."</b></td>
									<td class='fcaption'><b>".EASYSHOP_ADMIN_DISC_05."</b></td>
									<td class='fcaption'><b>".EASYSHOP_ADMIN_DISC_06."</b></td>
									<td class='fcaption'><b>".EASYSHOP_ADMIN_DISC_32."</b></td>
									<td class='fcaption'><b>".EASYSHOP_ADMIN_DISC_33."</b></td>
									<td class='fcaption'><b>".EASYSHOP_ADMIN_DISC_34."</b></td>
									<td class='fcaption'><center><b>".EASYSHOP_ADMIN_DISC_11."</b></center></td>
									<td class='fcaption'><center><b>".EASYSHOP_ADMIN_DISC_14."</b></center></td>
								</tr>";
								// Select the discounts in the alphabetical order
								$sql -> db_Select(easyshop_discount, "*", "ORDER BY discount_name", "no-where");
								// While there are records available; fill the rows
								while($row = $sql-> db_Fetch()){
                  // Hide date integer values 0
								  $discount_valid_from = ($row['discount_valid_from'] > 0) ? date("Y/m/d", $row['discount_valid_from']) : "";
								  $discount_valid_till = ($row['discount_valid_till'] > 0) ? date("Y/m/d", $row['discount_valid_till']) : "";
								  // Replace disount flag by description: 0 = Price, 1 = Percentage
                  $discount_flag = ($row['discount_flag'] == 1) ? EASYSHOP_ADMIN_DISC_08B : EASYSHOP_ADMIN_DISC_08A;
                  $discount_amount = ($row['discount_flag'] == 1) ? $row['discount_percentage'] : $unicode_character_before.number_format($row['discount_price'], 2, '.', '').$unicode_character_after;
                  $perc_sign = ($row['discount_flag'] == 1) ? " %": "";
                  // Do something special for 'special' percentages when print_discount_icons flag is set
                  if ($print_discount_icons == 1 AND $row['discount_flag'] == 1 AND strstr("_5_10_20_50_", "_".$row['discount_percentage']."_")) {
                    $perc_sign .= "&nbsp;<img src='".e_PLUGIN_ABS."easyshop/images/offer_".$row['discount_percentage'].".gif' style='height:22px' />";
                  }
                  // Determine the locked status
                  if ($row['discount_code'] > "") {
                     $discount_locked = "<center><img src='".e_PLUGIN_ABS."easyshop/images/lock.gif' style='height:22px' alt='".$row['discount_code']."' title='".$row['discount_code']."'/></center>";
                  }
									$text .= "
									<tr>
										<td class='forumheader3'>".$row['discount_name']."</td>
										<td class='forumheader3'>".r_userclass_name($row['discount_class'])."</td>
										<td class='forumheader3'>".$discount_flag."</td>
										<td class='forumheader3'>".$discount_amount.$perc_sign."</td>
										<td class='forumheader3'>".$discount_valid_from."</td>
										<td class='forumheader3'>".$discount_valid_till."</td>
										<td class='forumheader3'>".$discount_locked."</td>
										";
  										// Show the edit and delete icons
											$text .= "
										<td class='forumheader3'>
											<center>
											<a href='admin_discounts.php?edit.".$row['discount_id']."' alt='".EASYSHOP_ADMIN_DISC_15."'>".ADMIN_EDIT_ICON."</a>
                      &nbsp;
											<a href='admin_discounts.php?delete.".$row['discount_id']."' alt='".EASYSHOP_ADMIN_DISC_16."'>".ADMIN_DELETE_ICON."</a>";

											$text .= "
											</center>
										</td>
									</tr>";
									// Reset the values of variables for the next fetch
									unset($discount_valid_from);
									unset($discount_valid_till);
									unset($discount_flag);
									unset($discount_amount);
									unset($discount_locked);
								} // End of while
									
							$text .= "
							</table>
						</center>";
						}
						
						$text .= "
						<br />
				</fieldset>
		</center>
	</form>
	<br />";

  // --------------------------------------------------------------------------+
  // ----------------------- Create New Discount ------------------------------+
  // --------------------------------------------------------------------------+

	$text .= "
	<form name='create_new' method='POST' action='".e_SELF."'>
		<center>
			<div style='width:80%'>
				<fieldset>
					<legend>
						".EASYSHOP_ADMIN_DISC_03."
					</legend>";
	$text .= table_discounts($discount_id, $discount_name, $discount_class, $discount_flag, $discount_amount, $discount_valid_from, $discount_valid_till, $discount_code, $cal);
  $text .= "
				<br />
				<center>
          <input type='hidden' name='create_new' value='1'/>
					<input class='button' type='submit' value='".EASYSHOP_ADMIN_DISC_12."'/>
				</center>
				<br />
				</fieldset>
			</div>
		</center>
	</form>
	<br />";

// Render the value of $text in a table.
$title = EASYSHOP_ADMIN_DISC_00;
$ns -> tablerender($title, $text);
}

function table_discounts($discount_id, $discount_name, $discount_class, $discount_flag, $discount_amount, $discount_valid_from, $discount_valid_till, $discount_code, $cal) {
  $text .= "
    <table border='0' cellspacing='15' width='100%'>
    	<tr>
    		<td>
    			<b>".EASYSHOP_ADMIN_DISC_04."</b>
    		</td>
    		<td>
    			<input class='tbox' size='25' type='text' name='discount_name' value='$discount_name'/>
    		</td>
    	</tr>
    	<tr>
    		<td valign='top'>
    			<b>".EASYSHOP_ADMIN_DISC_05."</b>
    		</td>
    		<td>
    			".r_userclass("discount_class", $discount_class, "off", "public,guest,member,nobody,main,admin,classes")."
    		</td>
    	</tr>
    	<tr>
    		<td valign='top'>
    			<b>".EASYSHOP_ADMIN_DISC_06."</b>
    		</td>
    		<td>
    			<select class='tbox' name='discount_flag' value='$discount_flag'>
             <option value='0'"; if($discount_flag == 0){ $text.=" selected ";} $text .= ">".EASYSHOP_ADMIN_DISC_08A."</option>
             <option value='1'"; if($discount_flag == 1){ $text.=" selected ";} $text .= ">".EASYSHOP_ADMIN_DISC_08B."</option>
          </select>
    		</td>
    	</tr>
    	<tr>
    		<td valign='top'>
    			<b>".EASYSHOP_ADMIN_DISC_07."</b>
    		</td>
    		<td>
    			<input class='tbox' size='25' type='text' name='discount_amount' value='".number_format($discount_amount, 2, '.', '')."'/>
    		</td>
    	</tr>
    	<tr>
    		<td valign='top'>
    			<b>".EASYSHOP_ADMIN_DISC_09."</b>
    		</td>
    		<td>";

      // Display calender icon for discount_valid_from
      unset($cal_options);
      unset($cal_attrib);
      $cal_options['firstDay'] = 0;
      $cal_options['showsTime'] = false;
      $cal_options['showOthers'] = false;
      $cal_options['weekNumbers'] = true;
      $cal_attrib['class'] = "tbox";
      $cal_attrib['name'] = "discount_valid_from";
      $cal_attrib['value'] = ($discount_valid_from > 0) ? $discount_valid_from : EASYSHOP_ADMIN_DISC_31;
      $text .= $cal->make_input_field($cal_options, $cal_attrib);

   		$text .= "
    		</td>
    	</tr>
    	<tr>
    		<td valign='top'>
    			<b>".EASYSHOP_ADMIN_DISC_10."</b>
    		</td>
    		<td>";

      // Display calender icon for discount_valid_till
      unset($cal_options);
      unset($cal_attrib);
      $cal_options['firstDay'] = 0;
      $cal_options['showsTime'] = false;
      $cal_options['showOthers'] = false;
      $cal_options['weekNumbers'] = true;
      $cal_attrib['class'] = "tbox";
      $cal_attrib['name'] = "discount_valid_till";
      $cal_attrib['value'] = ($discount_valid_till > 0) ? $discount_valid_till : EASYSHOP_ADMIN_DISC_31;
      $text .= $cal->make_input_field($cal_options, $cal_attrib);

      $text .= "
    		</td>
    	</tr>
    	<tr>
    		<td valign='top'>
    			<b>".EASYSHOP_ADMIN_DISC_11."</b>
    		</td>
    		<td>
    			<input class='tbox' size='25' type='text' name='discount_code' value='".$discount_code."'/>";

      // Suggest a discount code if there isn't one present
      if ($discount_code == "") {
      	$text .= "&nbsp;".EASYSHOP_ADMIN_DISC_35."&nbsp;<strong>".General::CreateRandomDiscountCode()."</strong>";
    	}
    			
    	$text .= "
    		</td>
    	</tr>
    </table>";
  return $text;
}

require_once(e_ADMIN.'footer.php');
?>