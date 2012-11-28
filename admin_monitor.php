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
// IPN addition
include_once('includes/ipn_functions.php');

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_06';

$sql = new db;
// Wrap the shop monitor page in a table
$text ="<table border='0' width='95%' cellpadding='3'>";
$text .= "<tr>
				<td valign='top' align='left' width='45%'>
					<center>
					<table class='fborder' width='90%'>
						<tr>
							<td class='fcaption' colspan='2' width='100%'>
								".EASYSHOP_MONITOR_01."
							</td>
						</tr>";

// Display active Product Main Categories header
$text .= "<tr>
				<td class='forumheader'>
					".EASYSHOP_MONITOR_13."
				</td>
				<td class='forumheader2'>";
// Count active Product Main Categories
$text .= $sql->db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, "(*)", "WHERE main_category_active_status = '2'");
$text .="</td>
			</tr>";

// Display inactive Product Main Categories header
$text .= "<tr>
				<td class='forumheader'>
					".EASYSHOP_MONITOR_14."
				</td>
				<td class='forumheader2'>";
// Count inactive Product Categories
$text .= $sql->db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, "(*)", "WHERE main_category_active_status = '1'");
$text .="</td>
			</tr>";

// Display active Product Categories header
$text .= "<tr>
				<td class='forumheader'>
					".EASYSHOP_MONITOR_05."
				</td>
				<td class='forumheader2'>";
// Count active Product Categories
$text .= $sql->db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status = '2'");
$text .="</td>
			</tr>";

// Display active Product Categories without Main Category header
$text .= "<tr>
				<td class='forumheader'>
					".EASYSHOP_MONITOR_16."
				</td>
				<td class='forumheader2'>";
// Count active Product Categories without Main Category
$text .= $sql -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status = 2 AND category_main_id= ''");
$text .="</td>
			</tr>";

// Display inactive Product Categories header
$text .= "<tr>
				<td class='forumheader'>
					".EASYSHOP_MONITOR_06."
				</td>
				<td class='forumheader2'>";
// Count inactive Product Categories
$text .= $sql->db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status = '1'");
$text .="</td>
			</tr>";

// Display active products header
$text .= "<tr>
				<td class='forumheader'>
					".EASYSHOP_MONITOR_02."
				</td>
				<td class='forumheader2'>";
// Count active products
$prod_count = $sql->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status = '2'");
// If active product count is zero than display error message
if(!$prod_count){$text .= "<a href='admin_config.php'>".EASYSHOP_MONITOR_03."</a>";}
else { $text .= $prod_count;}
$text .="</td>
			</tr>";
			
// Display active products with discount header
$text .= "<tr>
				<td class='forumheader'>
          - ".EASYSHOP_MONITOR_17."
				</td>
				<td class='forumheader2'>";
// Count active products with discounts
$prod_discount_count = $sql->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status = '2' AND prod_discount_id > '0'");
// If active product count with discounts is zero than display NONE
if(!$prod_discount_count){$text .= EASYSHOP_MONITOR_18;}
else { $text .= $prod_discount_count;}
$text .="</td>
			</tr>";

// Display active products with property header
$text .= "<tr>
				<td class='forumheader'>
          - ".EASYSHOP_MONITOR_19."
				</td>
				<td class='forumheader2'>";
// Count active products with properties
$prod_property_count = $sql->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status = '2' AND (prod_prop_1_id > '0' OR prod_prop_2_id > '0' OR prod_prop_3_id > '0' OR prod_prop_4_id > '0' OR prod_prop_5_id > '0')");
// If active product count with property is zero than display NONE
if(!$prod_property_count){$text .= EASYSHOP_MONITOR_18;}
else { $text .= $prod_property_count;}
$text .="</td>
			</tr>";

// Display inactive products header
$text .= "<tr>
				<td class='forumheader'>
					".EASYSHOP_MONITOR_04."
				</td>
				<td class='forumheader2'>";
// Count inactive products
$text .= $sql->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status = '1'");
$text .="</td>
			</tr>";

// Display out-of-stock products header
$text .= "<tr>
				<td class='forumheader'>
        ".EASYSHOP_MONITOR_15."
				</td>
				<td class='forumheader2'>";
// Count out-of-stock products
$text .= $sql->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_out_of_stock = '2'");
$text .="</td>
			</tr>";

// Retrieve all shop preferences once in array
$shop_pref = shop_pref();
			
// Display number of images header
// Build array with all images to choose from
$store_image_path = $shop_pref['store_image_path'];
require_once(e_HANDLER.'file_class.php');
$fl = new e_file;
if($image_array = $fl->get_files(e_PLUGIN."easyshop/".$store_image_path, ".gif|.jpg|.png|.GIF|.JPG|.PNG","standard",2)){
	sort($image_array);
}
$image_count = count($image_array);

$text .= "<tr>
				<td class='forumheader'>
				".EASYSHOP_MONITOR_20."	".$store_image_path."
				</td>
				<td class='forumheader2'>";
// Display count number of images
$text .= $image_count;
$text .="</td>
			</tr>";
			
// Display number of files in downloads folder
$fl = new e_file;
if($downloads_array = $fl->get_files(e_PLUGIN."easyshop/downloads", ".*","standard",2)){
	sort($downloads_array);
}
$downloads_count = count($downloads_array);
$text .= "<tr>
				<td class='forumheader'>
        ".EASYSHOP_MONITOR_41."
				</td>
				<td class='forumheader2'>";
// Display count number of images
$text .= $downloads_count;
$text .="</td>
			</tr>
      </table>";
// Close the HTML wrap table
$text .="</center></td></tr></table>";

// IPN addition - introduce basic reporting
$enable_ipn = $shop_pref['enable_ipn'];
if ($enable_ipn == '2') { // Only show report if IPN is activated
	$result_text = "";
	if (isset($_GET['report'])) { // Activate the IPN orders clean options
	  $one_day      = 24 * 60 * 60; // Length of one day in seconds: hrs* mins * secs
	  $current_time = time();
	  $cutoff_time  = $current_time - ($one_day * $_GET['days']);
	  // Should we clean EScheck entries? i.e. they have been checked by admin and any fraudulent activities sorted
	  if( $_GET['report'] == "clean_check"){ 
		if($_GET['check']<>0){
		  $check_del = transaction("delete", NULL, NULL, "EScheck_", $cutoff_time, $current_time);
		  $check_del ? $result_text .= EASYSHOP_MONITOR_21."<br />" :
					   $result_text .= EASYSHOP_MONITOR_22."<br />" ;
		}else{
		  $result_text .= EASYSHOP_MONITOR_23."<br />" ;
		}  
	  } // End of cleaning to be checked entries
	  // Should we clean ES_shopping/processing entries? -is older than 3 days too little ?!??!?!?
	  if($_GET['report'] == "clean_shop"){
	    // Check to clean the shopping entries
		if($_GET['shop']<>0){
		  $check_del = transaction("delete", NULL, NULL, "ES_shopping", $cutoff_time, $current_time);
		  $check_del ? $result_text .= EASYSHOP_MONITOR_24."<br />" :
					   $result_text .= EASYSHOP_MONITOR_25." ".$_GET['days']." ".EASYSHOP_MONITOR_26."<br />" ;
		} else {
		  $result_text .= EASYSHOP_MONITOR_27."<br />" ;
		} // End of cleaning shopping entries
	    // Check to clean the processing entries
		if($_GET['proc']<>0){
		  $check_del = transaction("delete", NULL, NULL, "ES_processing", $cutoff_time, $current_time);

		  $check_del ? $result_text .= EASYSHOP_MONITOR_28."<br />" :
					   $result_text .= EASYSHOP_MONITOR_29." ".$_GET['days']." ".EASYSHOP_MONITOR_26."<br />" ;
		} else {
		  $result_text .= EASYSHOP_MONITOR_30."<br />" ;
		} // End of cleaning processing entries
	  } //End of clean_shop
	} // End of cleaning

	// Retrieve the report array
	$report = report();
	$reporttext ="<table class='fborder' width='90%'><tr><td>";
	if (isset($report['Completed']['report_count'])){
	  $completed = "<br /><div onclick='expandit(\"Completed\");'><span class='button'> ".EASYSHOP_MONITOR_31." </span></div><br /><span id='Completed' style='display:none;'>";
	  for($i=1;$i<=$report['Completed']['report_count'];$i++){
		$completed .= $report['Completed'][$i]['report_table'];
	  }
	  $completed .="</span>";
	} else {  $completed=""; }

	if (isset($report['ES_processing']['report_count'])){
			$ES_processing = "<br /><div onclick='expandit(\"ES_processing\");'><span class='button'> ".EASYSHOP_MONITOR_32." </span></div><br /><span id='ES_processing' style='display:none;'>";
			for($i=1;$i<=$report['ES_processing']['report_count'];$i++){
				$ES_processing .= $report['ES_processing'][$i]['report_table'];
			}
			$ES_processing .="</span>";
	} else {  
		$ES_processing=""; 
		$report['ES_processing']['report_count'] = 0;
	}

	if (isset($report['ES_shopping']['report_count'])){
			$ES_shopping = "<br /><div onclick='expandit(\"ES_shopping\");'><span class='button'> ".EASYSHOP_MONITOR_33." </span></div><br /><span id='ES_shopping' style='display:none;'>";
			for($i=1;$i<=$report['ES_shopping']['report_count'];$i++){
				$ES_shopping .= $report['ES_shopping'][$i]['report_table'];
			}
			$ES_shopping .="</span>";
	} else {  
		$ES_shopping="";
		$report['ES_shopping']['report_count'] = 0;
	}

	if (isset($report['EScheck']['report_count'])){
			$EScheck = "<br /><div onclick='expandit(\"EScheck\");'><span class='button'> ".EASYSHOP_MONITOR_34." </span></div><br /><span id='EScheck' style='display:none;'>";
			for($i=1;$i<=$report['EScheck']['report_count'];$i++){
				$EScheck .= $report['EScheck'][$i]['report_table'];
			}
			$EScheck .="</span>";
	} else {
		$EScheck=""; 
		$report['EScheck']['report_count'] = 0;
	}

	if (isset($report['totals']['report_count'])){
			$totals = "<br /><div onclick='expandit(\"totals\");'> <span class='button'> ".EASYSHOP_MONITOR_35." </span></div><br /><span id='totals' style='display:none;'>";
			for($i=1;$i<=$report['totals']['report_count'];$i++){
				$totals .= $report['totals'][$i]['report_table'];
			}
			$totals .="</span>";
	} else {  $totals=""; }

	if (isset($report['rxemail']['report_count'])){
			$rxemail = "<br /><div onclick='expandit(\"rxemail\");'> <span class='button'> ".EASYSHOP_MONITOR_36." </span></div><br /><span id='rxemail' style='display:none;'>";
			for($i=1;$i<=$report['rxemail']['report_count'];$i++){
				$rxemail .= $report['rxemail'][$i]['report_table'];
			}
			$rxemail .="</span>";
	} else {  $rxemail=""; }

	if (isset($report['dupltxn']['report_count'])){
			$dupltxn = "<br /><div onclick='expandit(\"dupltxn\");'><span class='button'> ".EASYSHOP_MONITOR_37." </span></div><br /><span id='dupltxn' style='display:none;'>";
			for($i=1;$i<=$report['dupltxn']['report_count'];$i++){
				$dupltxn .= $report['dupltxn'][$i]['report_table'];
			}
			$dupltxn .="</span>";
	} else {  $dupltxn=""; }

	if (isset($report['various']['report_count'])){
			$various = "<br /><div onclick='expandit(\"various\");'><span class='button'> VARIOUS </span></div><br /><span id='various' style='display:none;'>";
			for($i=1;$i<=$report['various']['report_count'];$i++){
				$various .= $report['various'][$i]['report_table'];
			}
			$various .="</span>";
	} else {  $various=""; }
			
	$reporttext .= $completed . $ES_processing . $ES_shopping . $EScheck . $totals . $rxemail . $dupltxn .$various;
	
	$monitor_clean_shop_days  = $shop_pref['monitor_clean_shop_days'];
	$monitor_clean_check_days = $shop_pref['monitor_clean_check_days'];
	if($monitor_clean_shop_days == "" || $monitor_clean_shop_days == NULL){ $monitor_clean_shop_days =  3; } // Default is 3 days
	if($monitor_clean_check_days == "" || $monitor_clean_check_days == NULL){ $monitor_clean_check_days =  7; } // Default is 7 days

	$reporttext .= "
	<div style='text-align:center;'>
	  <br /><span class='button'><b>
	  <a href='".e_SELF."?report=clean_shop&days=".$monitor_clean_shop_days."&shop=".$report['ES_shopping']['report_count']."&proc=".$report['ES_processing']['report_count']."'>&nbsp;&nbsp;".EASYSHOP_MONITOR_38." $monitor_clean_shop_days ".EASYSHOP_MONITOR_39."&nbsp;&nbsp;</a>
	  </b></span>&nbsp;&nbsp;&nbsp;&nbsp;
	  <span class='button'><b>
	  <a href='".e_SELF."?report=clean_check&days=".$monitor_clean_check_days."&check=".$report['EScheck']['report_count']."'>&nbsp;&nbsp;".EASYSHOP_MONITOR_40." $monitor_clean_check_days ".EASYSHOP_MONITOR_39."&nbsp;&nbsp;</a>
	  </b></span>
	</div>
	</td></tr></table>
	<div style='text-align:center;'>".$result_text."</div>";
	$text .= $reporttext;
} // End of conditional showing report

// Render the value of $text in a table.
$ns->tablerender(EASYSHOP_MONITOR_00, $text);
require_once(e_ADMIN.'footer.php');
?>