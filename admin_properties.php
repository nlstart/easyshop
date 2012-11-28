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

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
require_once('includes/config.php');

// Load the easyshop class
require_once('easyshop_class.php');

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_04';

// Check URL query
if(e_QUERY){
	$tmp = explode(".", e_QUERY); // Divide the URL query in separate arrays e.g. admin_properties.php?edit.5
	$action = $tmp[0];    // e.g. $action = 'edit'
	$action_id = $tmp[1]; // e.g. $action_id = '5'
	$page_id = $tmp[2];   // e.g. $page_id = '3'  (not used in admin_properties)
	unset($tmp); // unset the arrays, so next time URL query will be determined as new
}

// ----------------------------------------------------------------------------+
// ---------------------- Create and update records ---------------------------+
// ----------------------------------------------------------------------------+
if (isset($_POST['update_prop']) or isset($_POST['create_new'])) { // Update the fields from the edit or create mode
  // Check valid prices in the array first
  foreach ($_POST['prop_prices'] as $key=>$value) {
    if (General::validateDecimal($value)) {
    // This is a valid price with 2 decimals
    } else {
    // Alert: Invalid price! (just alert once)
    $text = EASYSHOP_ADMIN_PROP_22."<br />";
    }
  }
  // Check if property name is filled in
  if (strlen(trim($_POST['prop_display_name'])) == 0 ) {
    // Alert: name is invalid!
    $text .= EASYSHOP_ADMIN_PROP_25."<br />";
  }

  if ($text <> "") {
    $text .= "<br /><center><input class='button' type=button value='".EASYSHOP_ADMIN_PROP_23."' onClick='history.go(-1)'></center>";
   	// Render the value of $text in a table.
    $title = EASYSHOP_ADMIN_PROP_24;
    $ns -> tablerender($title, $text);
    require_once(e_ADMIN.'footer.php');
    // Leave on error
    exit();
  }
  $sql = new db;
  if (isset($_POST['create_new'])) { // Create a new record in Properties table
    $sql -> db_Insert(easyshop_properties,
    "0,
    '".$tp->toDB($_POST['prop_display_name'])."',
		'".$tp->toDB($_POST['prop_list'])."',
		''
    ") or die(mysql_error());
  }
  if (isset($_POST['update_prop'])) { // Update the existing record in Properties table
    $prop_list = $_POST['prop_list'];
    $prop_prices = $_POST['prop_prices'];
    // Clean the property array from empty elements
    General::Array_Clean("",$prop_list);
    // Transfer the prop_list array back to a string separated by comma's
    $prop_list = implode(",", $prop_list);
    $prop_prices = implode(",", $prop_prices);
    // Actual update
    $sql -> db_Update(easyshop_properties,
    "prop_display_name='".$tp->toDB($_POST['prop_display_name'])."',
		prop_list='".$tp->toDB($prop_list)."',
		prop_prices='".$tp->toDB($prop_prices)."'
		WHERE property_id='".$tp->toDB($_POST['property_id'])."'");
  }
  $upd_message = EASYSHOP_ADMIN_PROP_08; // Property information saved message
}

// Displays the update message to confirm data is stored in database
if (isset($upd_message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$upd_message."</b></div>");
  header("Location: admin_properties.php");
}

// ----------------------------------------------------------------------------+
// ---------------------------- Delete records --------------------------------+
// ----------------------------------------------------------------------------+
if ($action == 'delete') {
  	// Verify deletion before actual delete
    $delete_text = "
    <br /><br />
    <center>
        ".EASYSHOP_ADMIN_PROP_15."
        <br /><br />
        <table width='100'>
            <tr>
                <td>
                    <a href='admin_properties.php?delete_final.".$action_id."'>".EASYSHOP_ADMIN_PROP_16."</a>
                </td>
                <td>
                    <a href='admin_properties.php'>".EASYSHOP_ADMIN_PROP_17."</a>
                </td>
            </tr>
        </table>
    </center>";

    // Render the value of $delete_text in a table.
    $title = "<b>".EASYSHOP_ADMIN_PROP_14."</b>";
    $ns -> tablerender($title, $delete_text);

}
if ($action == 'delete_final') {
	// Variable delete_final is set if answer equals Yes
    // Delete property from property table
    $sql -> db_Delete(easyshop_properties, "property_id=$action_id");
    // Delete property from product table fields
    $sql->db_Update(easyshop_items, "prod_prop_1_id='' WHERE prod_prop1_id=$action_id");
    $sql->db_Update(easyshop_items, "prod_prop_1_id='' WHERE prod_prop1_id=$action_id");
    $sql->db_Update(easyshop_items, "prod_prop_1_id='' WHERE prod_prop1_id=$action_id");
    $sql->db_Update(easyshop_items, "prod_prop_1_id='' WHERE prod_prop1_id=$action_id");
    $sql->db_Update(easyshop_items, "prod_prop_1_id='' WHERE prod_prop1_id=$action_id");
    header("Location: admin_properties.php");
}

// ----------------------------------------------------------------------------+
// ---------------------- Edit or Maintain Property ---------------------------+
// ----------------------------------------------------------------------------+
if ($action == 'edit') {
	$arg="SELECT *
        FROM #easyshop_properties
        WHERE property_id = $action_id";
  $sql->db_Select_gen($arg,false);
	while($row = $sql-> db_Fetch()){
	    $property_id = $row['property_id'];
	    $prop_display_name = $row['prop_display_name'];
    	$prop_list = $row['prop_list'];
    	$prop_prices = $row['prop_prices'];
	}
	
	$edit_text .= "
	<form name='update_prop' method='POST' action='".e_SELF."'>
		<center>
			<div style='width:80%'>
				<fieldset>
					<legend>
						".EASYSHOP_ADMIN_PROP_12."
					</legend>
					<table border='0' cellspacing='15' width='100%'>
						<tr>
							<td>
								<b>".EASYSHOP_ADMIN_PROP_04."</b>
							</td>
							<td>
								<input class='tbox' size='25' type='text' name='prop_display_name' value='".$prop_display_name."'/>
							</td>
						</tr>
						<tr>
							<td valign='top'>
								<b>".EASYSHOP_ADMIN_PROP_05."</b><br />
								".EASYSHOP_ADMIN_PROP_18."<br />
								".EASYSHOP_ADMIN_PROP_19."
							</td>
							<td>
								<b>".EASYSHOP_ADMIN_PROP_04."</b></td><td><b>".EASYSHOP_ADMIN_PROP_20."</b></td>
							";

      // Explode puts all listed string elements (separated by comma) in an array
      $prop_array = explode(",", $prop_list);
      $price_array = explode(",", $prop_prices);
      $arrayLength = count($prop_array);
      for ($i = 0; $i < $arrayLength; $i++){
          $edit_text .= "<tr><td></td><td><input class='tbox' size='25' type='text' name='prop_list[]' value='".$prop_array[$i]."'/></td>";
          $edit_text .= "<td><input class='tbox' size='25' type='text' name='prop_prices[]' value='".number_format($price_array[$i], 2, '.', '')."'/></td></tr>";
      }
      // Add a blank input field on top of the current list
      $j = $arrayLength + 1;
      $edit_text .= "<tr><td></td><td><input class='tbox' size='25' type='text' name='prop_list[]' value='".$prop_array[$j]."'/></td>";
      $edit_text .= "<td><input class='tbox' size='25' type='text' name='prop_prices[]' value='".$price_array[$j]."'/></td></tr>";

	$edit_text .= "
							</td>
						</tr>
					</table>
				<br />
				<center>
          <input type='hidden' name='update_prop' value='1'/>
          <input type='hidden' name='prop_array_length' value ='".$arrayLength."'/>
          <input type='hidden' name='property_id' value='".$property_id."'/>
					<input class='button' type='submit' value='".EASYSHOP_ADMIN_PROP_13."'/>
					&nbsp;<a href='admin_properties.php'>".EASYSHOP_ADMIN_PROP_21."</a>
				</center>
				<br />
				</fieldset>
			</div>
		</center>
	</form>";

	// Render the value of $edit_text in a table.
	$title = EASYSHOP_ADMIN_PROP_12;
	$ns -> tablerender($title, $edit_text);
	
} else {
  // --------------------------------------------------------------------------+
  // ----------------------- Overview Properties ------------------------------+
  // --------------------------------------------------------------------------+

  // Determine if there are no properties
	if($sql -> db_Count(easyshop_properties) > 0) {
		$no_properties = 1;
	}

	$text .= "
  <form name='overview_prop' method='POST' action='".e_SELF."'>
		<center>
				<fieldset>
					<legend>
						".EASYSHOP_ADMIN_PROP_01."
					</legend>";
          // Show a message if there are no properties to display
					if ($no_properties == null) {
						$text .= "
						<br />
						<center>
							<span class='smalltext'>
								".EASYSHOP_ADMIN_PROP_02."
							</span>
						</center>
						<br />";
					} else {
						$text .= "
						<center>
						  <table style='".ADMIN_WIDTH."' class='fborder'>
							<tr>
									<td class='fcaption'><b>".EASYSHOP_ADMIN_PROP_04."</b></td>
									<td class='fcaption'><b>".EASYSHOP_ADMIN_PROP_05."</b></td>
									<td class='fcaption'><center><b>".EASYSHOP_ADMIN_PROP_09."</b></center></td>
								</tr>";
								// Select the properties in the alphabetical order
								$sql -> db_Select(easyshop_properties, "*", "ORDER BY prop_display_name", "no-where");
								// While there are records available; fill the rows
								while($row = $sql-> db_Fetch()){
									$text .= "
									<tr>
										<td class='forumheader3'>".$row['prop_display_name']."</td>
										<td class='forumheader3'>".$row['prop_list']."</td>
										";

  										// Show the edit and delete icons
											$text .= "
										<td class='forumheader3'>
											<center>
											<a href='admin_properties.php?edit.".$row['property_id']."' alt='".EASYSHOP_ADMIN_PROP_10."'>".ADMIN_EDIT_ICON."</a>
                      &nbsp;
											<a href='admin_properties.php?delete.".$row['property_id']."' alt='".EASYSHOP_ADMIN_PROP_11."'>".ADMIN_DELETE_ICON."</a>";

											$text .= "
											</center>
										</td>
									</tr>";
								}
									
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
  // ----------------------- Create New Property ------------------------------+
  // --------------------------------------------------------------------------+
	$text .= "
	<form name='create_new' method='POST' action='".e_SELF."'>
		<center>
			<div style='width:80%'>
				<fieldset>
					<legend>
						".EASYSHOP_ADMIN_PROP_03."
					</legend>
					<table border='0' cellspacing='15' width='100%'>
						<tr>
							<td>
								<b>".EASYSHOP_ADMIN_PROP_04."</b>
							</td>
							<td>
								<input class='tbox' size='25' type='text' name='prop_display_name'/>
							</td>
						</tr>
						<tr>
							<td valign='top'>
								<b>".EASYSHOP_ADMIN_PROP_05."</b><br />
								".EASYSHOP_ADMIN_PROP_06."
							</td>
							<td>
								<textarea class='tbox' cols='50' rows='7' name='prop_list'></textarea><br />
							</td>
						</tr>
					</table>
				<br />
				<center>
          <input type='hidden' name='create_new' value='1'/>
					<input class='button' type='submit' value='".EASYSHOP_ADMIN_PROP_07."'/>
				</center>
				<br />
				</fieldset>
			</div>
		</center>
	</form>
	<br />";
	
// Render the value of $text in a table.
$title = EASYSHOP_ADMIN_PROP_00;
$ns -> tablerender($title, $text);
}

require_once(e_ADMIN.'footer.php');
?>