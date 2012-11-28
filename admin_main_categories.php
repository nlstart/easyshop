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
// Include ren_help for display_help (while showing BBcodes)
require_once(e_HANDLER.'ren_help.php');

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
require_once('includes/config.php');

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_02';

require_once(e_HANDLER.'userclass_class.php');

function tokenizeArray($array) {
    unset($GLOBALS['tokens']);
    $delims = "~";
    $word = strtok( $array, $delims );
    while ( is_string( $word ) ) {
        if ( $word ) {
            global $tokens;
            $tokens[] = $word;
        }
        $word = strtok ( $delims );
    }
}

if ($_POST['create_main_category'] == '1') {
// Create new Product Category
    if (isset($_POST['main_category_active_status']))
    {
        $main_category_active_status = 2;
    } else
    {
        $main_category_active_status = 1;
    }
    $sql -> db_Insert(DB_TABLE_SHOP_MAIN_CATEGORIES,
        "0,
		'".$tp->toDB($_POST['main_category_name'])."',
		'".$tp->toDB($_POST['main_category_description'])."',
		'".$tp->toDB($_POST['main_category_image'])."',
		'".$tp->toDB($main_category_active_status)."',
		1") or die(mysql_error());
    header("Location: ".e_SELF);
    exit();

} else if ($_POST['main_category_dimensions'] == '1') {
    $sql->db_Update(DB_TABLE_SHOP_PREFERENCES,
    "categories_per_page='".$tp->toDB($_POST['categories_per_page'])."',
	num_category_columns='".$tp->toDB($_POST['num_category_columns'])."'
	WHERE
	store_id=1");
    header("Location: ".e_SELF);
    exit();

} else if ($_POST['change_main_order'] == '1') {
    // Change category order
    for ($x = 0; $x < count($_POST['main_category_order']); $x++) {
        tokenizeArray($_POST['main_category_order'][$x]);
        $newCategoryOrderArray[$x] = $tokens;
    }

    for ($x = 0; $x < count($newCategoryOrderArray); $x++) {
        $sql -> db_Update(DB_TABLE_SHOP_MAIN_CATEGORIES,
            "main_category_order=".$tp->toDB($newCategoryOrderArray[$x][1])."
            WHERE main_category_id=".$tp->toDB($newCategoryOrderArray[$x][0]));
    }

    // Change category active status
    $sql -> db_Update(DB_TABLE_SHOP_MAIN_CATEGORIES, "main_category_active_status=1");
    
    foreach ($_POST['main_category_active_status'] as $value) {
    	$sql -> db_Update(DB_TABLE_SHOP_MAIN_CATEGORIES, "main_category_active_status=2 WHERE main_category_id=".$tp->toDB($value));
    }

    header("Location: ".e_SELF);
    exit();

} else if ($_POST['edit_main_category'] == '2') {
    // Edit Product Category
    if (isset($_POST['main_category_active_status']))
    {
        $main_category_active_status = 2;
    } else
    {
        $main_category_active_status = 1;
    }
    $sql -> db_Update(DB_TABLE_SHOP_MAIN_CATEGORIES,
        "main_category_name='".$tp->toDB($_POST['main_category_name'])."',
		main_category_description='".$tp->toDB($_POST['main_category_description'])."',
		main_category_image='".$tp->toDB($_POST['main_category_image'])."',
		main_category_active_status='".$tp->toDB($main_category_active_status)."'
		WHERE main_category_id=".$tp->toDB($_POST['main_category_id']));
    header("Location: ".e_SELF);
    exit();

} else if ($_GET['delete_main_category'] == '1') {
  	// Verify deletion before actual delete
    $text = "
    <br /><br />
    <div style='text-align:center;'>
        ".EASYSHOP_MCATEDIT_02."
        <br /><br />
        <table width='100'>
            <tr>
                <td>
                    <a href='".e_SELF."?delete_main_category=2&main_category_id=".$_GET['main_category_id']."' alt=''>".EASYSHOP_MCATEDIT_03."</a>
                </td>
                <td>
                    <a href='".e_SELF."' alt=''>".EASYSHOP_MCATEDIT_04."</a>
                </td>
            </tr>
        </table>
    </div>";

    // Render the value of $text in a table.
    $title = "<b>".EASYSHOP_MCATEDIT_01."</b>";
    $ns -> tablerender($title, $text);

} else if ($_GET['delete_main_category'] == '2') {
	// Variable delete_main_category = 2 if answer equals Yes
	$MainCategoryId = $tp->toDB($_GET['main_category_id']);

    // Delete category from tables
    $sql -> db_Delete(DB_TABLE_SHOP_MAIN_CATEGORIES, "main_category_id=".intval($MainCategoryId));
    header("Location: ".e_SELF);
    exit();
}


// Build array with all images to choose from
$sql = new db;
$sql->db_Select(DB_TABLE_SHOP_PREFERENCES);
while($row = $sql-> db_Fetch()){
    $store_image_path = $row['store_image_path'];
    $icon_width = $row['icon_width'];
}
require_once(e_HANDLER.'file_class.php');
$fl = new e_file;
if($image_array = $fl->get_files(e_PLUGIN."easyshop/".$store_image_path, ".gif|.jpg|.png|.GIF|.JPG|.PNG","standard",2)){
	sort($image_array);
}
if ($icon_width == '' OR $icon_width < 1) {$icon_width = 16;} // Default of icon width is 16 pixels width

// Edit or Maintain a single category
if ($_GET['edit_main_category'] == 1) {
	
	//*
	$sql -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "main_category_id=".intval($_GET['main_category_id']));
	while($row = $sql-> db_Fetch()){
	    $main_category_id = $row['main_category_id'];
	    $main_category_name = $row['main_category_name'];
    	$main_category_description = $row['main_category_description'];
    	$main_category_image = $row['main_category_image'];
    	$main_category_active_status = $row['main_category_active_status'];
    	$main_category_order = $row['main_category_order'];
	}
	
	$text .= "
	<form name='good' method='POST' action='".e_SELF."'>
		<div style='text-align:center; width:80%'>
			<fieldset>
				<table border='0' cellspacing='15' width='100%'>
					<tr>
						<td>
							<b>".EASYSHOP_MCAT_04."</b>
						</td>
						<td>
							<input class='tbox' size='25' type='text' name='main_category_name' value='".$main_category_name."' />
						</td>
					</tr>
					<tr>
						<td valign='top'>
							<b>".EASYSHOP_MCAT_05."</b>
						</td>
						<td>
							<textarea class='tbox' cols='50' rows='7' name='main_category_description' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>".$main_category_description."</textarea><br />".display_help('helpa')."
						</td>
					</tr>
					<tr>
						<td valign='top'>
							<b>".EASYSHOP_MCAT_06."</b>
							<br />
							".EASYSHOP_MCAT_07."
						</td>
						<td valign='top'>
							<input type='text' class='tbox' id='main_category_image' name='main_category_image' value='".$main_category_image."' /> ".EASYSHOP_MCAT_08."<br />";
	// Show icons with width 16 of the array of images and put name in variable $main_category_image
	foreach($image_array as $icon)
	{
					$text  .= "<a href=\"javascript:insertext('" . $icon['fname'] . "','main_category_image','catimg')\"><img src='" . $icon['path'] . $icon['fname'] . "' style='border:0' alt='' width='".$icon_width."' /></a> ";
	}

	$text .= "
						</td>
					</tr>
					<tr>
					  <td>
						<b>".EASYSHOP_MCAT_15."</b>
					  </td>
					  <td>";

	// Display the check box for active status (active = 2)
	if ($main_category_active_status == 2) {
			$text .= "
						<input type='checkbox' name='main_category_active_status' value='2' checked='checked' />";
	} else {
			$text .= "
						<input type='checkbox' name='main_category_active_status' value='1' />";
	}

	$text .= "
					  </td>
					</tr>
				</table>
				<br />
				<div style='text-align:center;'>
					<input type='hidden' name='main_category_id' value='".intval($_GET['main_category_id'])."' />
					<input type='hidden' name='edit_main_category' value='2' />
					<input class='button' type='submit' value='".EASYSHOP_MCAT_13."' />
				</div>
				<br />
			</fieldset>
		</div>
	</form>";
	
	// Render the value of $text in a table.
	$title = EASYSHOP_MCAT_18;
	$ns -> tablerender($title, $text);
	
} else {
  // Initial screen with Maintain Categories

  // Determine if there are no categories
	if($sql -> db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES) > 0) {
		$no_categories = 1;
	}

  // Check if there are active categories
  // active_status = 1 --> active 'off'
  // active_status = 2 --> active 'on'
	if($sql -> db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, '(*)', 'WHERE main_category_active_status = 2') == 0) {
		$no_active_categories = 1;
	}
	
  //  Retrieve the records from the database
	$sql -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES);
	while($row = $sql-> db_Fetch()){
		$main_category_id = $row['main_category_id'];
		$main_category_name = $row['main_category_name'];
		$main_category_description = $row['main_category_description'];
		$main_category_image = $row['main_category_image'];
		$main_category_active_status = $row['main_category_active_status'];
		$main_category_order = $row['main_category_order'];
	}
	
	$sql -> db_Select(DB_TABLE_SHOP_PREFERENCES);
	while($row = $sql-> db_Fetch()){
		$store_name = $row['store_name'];
		$store_address_1 = $row['store_address_1'];
		$store_address_2 = $row['store_address_2'];
		$store_city = $row['store_city'];
		$store_state = $row['store_state'];
		$store_zip = $row['store_zip'];
		$store_country = $row['store_country'];
		$paypal_email = $row['paypal_email'];
		$support_email = $row['support_email'];
		$store_image_path = $row['store_image_path'];

		$store_welcome_message = $row['store_welcome_message'];
		$store_info = $row['store_info'];
		$payment_page_style = $row['payment_page_style'];
		$payment_page_image = $row['payment_page_image'];
		$add_to_cart_button = $row['add_to_cart_button'];
		$view_cart_button = $row['view_cart_button'];
		$popup_window_height = $row['popup_window_height'];
		$popup_window_width = $row['popup_window_width'];
		$cart_background_color = $row['cart_background_color'];
		$thank_you_page_title = $row['thank_you_page_title'];

		$thank_you_page_text = $row['thank_you_page_text'];
		$num_category_columns = $row['num_category_columns'];
		$categories_per_page = $row['categories_per_page'];
		$num_item_columns = $row['num_item_columns'];
		$items_per_page = $row['items_per_page'];
	}

	$text .= "
	<form name='good' method='POST' action='".e_SELF."'>
		<div style='text-align:center;'>
				<fieldset>
					<legend>
						".EASYSHOP_MCAT_01."
					</legend>";
          // Show a message if there are no categories to display
					if ($no_categories == null) {
						$text .= "
						<br />
						<div style='text-align:center;'>
							<span class='smalltext'>
								".EASYSHOP_MCAT_02."
							</span>
						</div>
						<br />";
					} else {
						$text .= "
						<div style='text-align:center;'>
						  <table style='".ADMIN_WIDTH."' class='fborder'>
							<tr>
									<td class='fcaption'><b>".EASYSHOP_MCAT_06."</b></td>
									<td class='fcaption'><b>".EASYSHOP_MCAT_04."</b></td>
									<td class='fcaption'><span style='text-align:center;'><b>".EASYSHOP_MCAT_14."</b></span></td>
									<td class='fcaption'><span style='text-align:center;'><b>".EASYSHOP_MCAT_15."</b></span></td>
									<td class='fcaption'><span style='text-align:center;'><b>".EASYSHOP_MCAT_19."</b></span></td>
								</tr>";
								// While there are records available; fill the rows to display them all in the userdefined order
								// First query: select the categories
								$sql -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "ORDER BY main_category_order", "no-where");
								while($row = $sql-> db_Fetch()){
                  // Second query: Count the number of products in the category
                  $sql2 = new db;
									$prod_cat_count = $sql2 -> db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE main_category_id='".$row['main_category_id']."'");

									$text .= "
									<tr>
										<td class='forumheader3'>";
										// Show the category image if it is available
										if ($row['main_category_image'] == '') {
											$text .= "
											&nbsp;";
										} else {
											$text .= "
											<img src='$store_image_path".$row['main_category_image']."' alt='".$row['main_category_image']."' title='".$row['main_category_image']."' />
											<br />
											"; // .$row['main_category_image'];
										}
										$text .= "
										</td>
										<td class='forumheader3'>";
										
                    // Show link to product inventory for the specific category only if there are products in the category
										// if ($prod_cat_count > 0) { $text .= "<a href='admin_config.php?mcat.".$row['main_category_id']."'>"; }
										$text .= $row['main_category_name'];
										// End tag of the conditional link
                    // if ($prod_cat_count > 0) { $text .= "</a>"; }
                    
                    $text .= "
										</td>
										<td class='forumheader3'>
											<div style='text-align:center;'>
						                        <select class='tbox' name='main_category_order[]'>";
            						            // Third query: Build the selection list with order numbers
						                        $sql3 = new db;
						                        $num_rows = $sql3 -> db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, "(*)");
						                        $count = 1;
						                        while ($count <= $num_rows) {
						                            if ($row['main_category_order'] == $count) {
						                                $text .= "
						                                <option value='".$row['main_category_id']."~".$count."' selected='selected'>".$count."</option>";
						                            } else {
						                                $text .= "
						                                <option value='".$row['main_category_id']."~".$count."'>".$count."</option>";
						                            }
						                        $count++;
						                        }
						                        $text .= "
						                        </select>";
						
						                    $text .= "
						                    </div>
										</td>
										<td class='forumheader3'>
											<div style='text-align:center;'>";

  										// Display the check box for active status (active = 2)
											if ($row['main_category_active_status'] == 2) {
												$text .= "
												<input type='checkbox' name='main_category_active_status[]' value='".$row['main_category_id']."' checked='checked' />";
											} else {
												$text .= "
												<input type='checkbox' name='main_category_active_status[]' value='".$row['main_category_id']."' />";
											}

                      // Show the number of products in the category
											$text .= "
											</div>
										</td>";

  										// Show the edit and delete icons
											$text .= "
										<td class='forumheader3'>
											<div style='text-align:center;'>
											<a href='".e_SELF."?edit_main_category=1&main_category_id=".intval($row['main_category_id'])."' alt='".EASYSHOP_MCAT_16."'>".ADMIN_EDIT_ICON."</a>
                      &nbsp;";
                      // Show delete icon conditionally (only when there are no products in the category)
                      if ($prod_cat_count == 0) {
											$text .= "
											<a href='".e_SELF."?delete_main_category=1&main_category_id=".intval($row['main_category_id'])."' alt='".EASYSHOP_MCAT_17."'>".ADMIN_DELETE_ICON."</a>";
											}
											
											$text .= "
											</div>
										</td>
									</tr>";
								}
								
							$text .= "
							</table>
						</div>
						<br />
						<div style='text-align:center;'>
							<input type='hidden' name='change_main_order' value='1'>
							<input class='button' type='submit' value='".EASYSHOP_MCAT_13."'>
						</div>
						<br />";

            if ($no_active_categories == 1) {
							$text .= "<img src='".e_IMAGE."admin_images/docs_16.png' title='' alt='' /> ".EASYSHOP_MCAT_20;
            }
						
					}
				$text .= "
				</fieldset>
		</div>
	</form>
	<br />";

  // Create a new category
	$text .= "
	<form name='good' method='POST' action='".e_SELF."'>
		<div style='text-align:center; width:80%'>
			<fieldset>
				<legend>
					".EASYSHOP_MCAT_03."
				</legend>
				<table border='0' cellspacing='15' width='100%'>
					<tr>
						<td>
							<b>".EASYSHOP_MCAT_04."</b>
						</td>
						<td>
							<input class='tbox' size='25' type='text' name='main_category_name'>
						</td>
					</tr>
					<tr>
						<td valign='top'>
							<b>".EASYSHOP_MCAT_05."</b>
						</td>
						<td>
							<textarea class='tbox' cols='50' rows='7' name='main_category_description' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'></textarea><br />".display_help('helpb')."
						</td>
					</tr>
					<tr>
						<td valign='top'>
							<b>".EASYSHOP_MCAT_06."</b>
							<br />
							".EASYSHOP_MCAT_07."
						</td>
						<td valign='top'>
							<input type='text' class='tbox' id='main_category_image' name='main_category_image' value='".$main_category_image."' /> ".EASYSHOP_MCAT_08."<br />";
	// Show icons with width 16 of the array of images and put name in variable $main_category_image
	foreach($image_array as $icon)
	{
		$text  .= 			"<a href=\"javascript:insertext('" . $icon['fname'] . "','main_category_image','catimg')\"><img src='" . $icon['path'] . $icon['fname'] . "' style='border:0' alt='' width='".$icon_width."' /></a> ";
	}

	$text .= "
						</td>
					</tr>
					<tr>
					  <td>
						<b>".EASYSHOP_MCAT_15."</b>
					  </td>
					  <td>
						<input type='checkbox' name='main_category_active_status' value='1' />
					  </td>
					</tr>
				</table>
				<br />
				<div style='text-align:center;'>
					<input type='hidden' name='create_main_category' value='1' />
					<input class='button' type='submit' value='".EASYSHOP_MCAT_09."' />
				</div>
				<br />
			</fieldset>
		</div>
	</form>";

	// Render the value of $text in a table.
	$title = EASYSHOP_MCAT_00;
	$ns -> tablerender($title, $text);
}

require_once(e_ADMIN.'footer.php');
?>