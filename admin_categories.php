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

// Include userclass_class.php which is necessary for function r_userclass (dropdown of classes)
require_once(e_HANDLER.'form_handler.php');
require_once(e_HANDLER.'userclass_class.php');
require_once(e_HANDLER.'file_class.php');

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
require_once('includes/config.php');

// Set the active menu option for admin_menu.php
$pageid = 'admin_menu_03';

// START - HANDLE INSERT/EDIT/DELETE
require_once(e_ADMIN.'auth.php');
require_once('includes/config.php');

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

if ($_POST['create_category'] == '1') {
// Create new Product Category
    if (isset($_POST['category_active_status']))
    {
        $category_active_status = 2;
    } else
    {
        $category_active_status = 1;
    }
    $sql -> db_Insert(DB_TABLE_SHOP_ITEM_CATEGORIES,
        "0,
		'".$tp->toDB($_POST['category_name'])."',
		'".$tp->toDB($_POST['category_description'])."',
		'".$tp->toDB($_POST['category_image'])."',
		'".intval($tp->toDB($category_active_status))."',
		1,
		'".intval($tp->toDB($_POST['category_main_id']))."',
		'".intval($tp->toDB($_POST['category_class']))."',
		'".intval($tp->toDB($_POST['category_order_class']))."'
		") or die(mysql_error());
    header("Location: ".e_SELF);
    exit();

} else if ($_POST['category_dimensions'] == '1') {
    $sql->db_Update(DB_TABLE_SHOP_PREFERENCES,
    "categories_per_page='".$tp->toDB($_POST['categories_per_page'])."',
	num_category_columns='".$tp->toDB($_POST['num_category_columns'])."'
	WHERE
	store_id=1");
    header("Location: ".e_SELF);
    exit();
} else if ($_POST['change_order'] == '1') {
    // Change category order
    for ($x = 0; $x < count($_POST['category_order']); $x++) {
        tokenizeArray($_POST['category_order'][$x]);
        $newCategoryOrderArray[$x] = $tokens;
    }
    for ($x = 0; $x < count($newCategoryOrderArray); $x++) {
        $sql -> db_Update(DB_TABLE_SHOP_ITEM_CATEGORIES,
            "category_order=".$tp->toDB($newCategoryOrderArray[$x][1])."
            WHERE category_id=".$tp->toDB($newCategoryOrderArray[$x][0]));
    }
    // Change category active status
    $sql -> db_Update(DB_TABLE_SHOP_ITEM_CATEGORIES, "category_active_status=1");
    
    foreach ($_POST['category_active_status'] as $value) {
    	$sql -> db_Update(DB_TABLE_SHOP_ITEM_CATEGORIES, "category_active_status=2 WHERE category_id=".$tp->toDB($value));
    }
    header("Location: ".e_SELF);
    exit();
} else if ($_POST['edit_category'] == '2') {
    // Edit Product Category
    if (isset($_POST['category_active_status']))
    {
        $category_active_status = 2;
    } else
    {
        $category_active_status = 1;
    }

    $sql -> db_Update(DB_TABLE_SHOP_ITEM_CATEGORIES,
		"category_name='".$tp->toDB($_POST['category_name'])."',
		category_description='".$tp->toDB($_POST['category_description'])."',
		category_image='".$tp->toDB($_POST['category_image'])."',
		category_active_status='".intval($tp->toDB($category_active_status))."',
		category_main_id='".intval($tp->toDB($_POST['category_main_id']))."',
		category_class='".intval($tp->toDB($_POST['category_class']))."',
		category_order_class='".intval($tp->toDB($_POST['category_order_class']))."'
		WHERE category_id='".intval($tp->toDB($_POST['category_id']))."'");
    header("Location: ".e_SELF);
    exit();
} else if ($_GET['delete_category'] == '1') {
  	// Verify deletion before actual delete
    $text = "
    <br /><br />
    <div style='text-align:center;'>
        ".EASYSHOP_CATEDIT_02."
        <br /><br />
        <table width='100'>
            <tr>
                <td>
                    <a href='".e_SELF."?delete_category=2&category_id=".intval($_GET['category_id'])."'>".EASYSHOP_CATEDIT_03."</a>
                </td>
                <td>
                    <a href='".e_SELF."'>".EASYSHOP_CATEDIT_04."</a>
                </td>
            </tr>
        </table>
    </div>";

    // Render the value of $text in a table.
    $title = "<b>".EASYSHOP_CATEDIT_01."</b>";
    $ns -> tablerender($title, $text);
} else if ($_GET['delete_category'] == '2') {
	// Variable delete_category = 2 if answer equals Yes
	$categoryId = $tp->toDB($_GET['category_id']);
    // Delete category from tables
    $sql -> db_Delete(DB_TABLE_SHOP_ITEM_CATEGORIES, "category_id=".intval($categoryId));
    header("Location: ".e_SELF);
    exit();
}
// END   - HANDLE INSERT/EDIT/DELETE

// START MAIN ADMIN CATEGORIES
// Build array with all images to choose from
$sql = new db;
$sql->db_Select(DB_TABLE_SHOP_PREFERENCES);
while($row = $sql-> db_Fetch()){
    $store_image_path = $row['store_image_path'];
    $icon_width = $row['icon_width'];
}
//require_once(e_HANDLER.'file_class.php');
$fl = new e_file;
if($image_array = $fl->get_files(e_PLUGIN."easyshop/".$store_image_path, ".gif|.jpg|.png|.GIF|.JPG|.PNG","standard",2)){
	sort($image_array);
}
if ($icon_width == '' OR $icon_width < 1) {$icon_width = 16;} // Default of icon width is 16 pixels width

// Count the active main activities
$main_cat_count = $sql->db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, "(*)", "WHERE main_category_active_status = '2'");

// Edit or Maintain a single category
if ($_GET['edit_category'] == 1) {
	
	//*
	$sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_id=".intval($_GET['category_id']));
	while($row = $sql-> db_Fetch()){
	    $category_id = $row['category_id'];
	    $category_name = $row['category_name'];
    	$category_description = $row['category_description'];
    	$category_image = $row['category_image'];
    	$category_active_status = $row['category_active_status'];
    	$category_order = $row['category_order'];
    	$category_main_id = $row['category_main_id'];
    	$category_class = $row['category_class'];
    	$category_order_class = $row['category_order_class'];
	}
	
	$text .= "
	<form id='cat_edit' method='post' action='".e_SELF."'>
		<div style='text-align:center;'>
			<div style='width:80%'>
				<fieldset>
					<table border='0' cellspacing='15' width='100%'>";

  // Only display Main Category selection if there are active Main Categories
  if ($sql->db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, "(*)", "WHERE main_category_active_status = '2'") > 0) {
	$text .= "
						<tr>
							<td>
								<b>".EASYSHOP_CAT_22."</b>
							</td>
							<td>
								<select class='tbox' name='category_main_id'>";
		                        $sql2 = new db;
		                        $sql2 -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "WHERE main_category_active_status = '2' ORDER BY main_category_order", false); // Select only active main categories
		                        // Add a blank option too: main category is not mandatory
                      			$text .= "<option value='' selected='selected'></option>";
		                        while ($row2 = $sql2->db_Fetch()) {
		                        	if ($row2['main_category_id'] == $category_main_id) {
	                        			$text .= "
		                                <option value='".$row2['main_category_id']."' selected='selected'>".$row2['main_category_name']."</option>";
		                        	} else {
	                        			$text .= "
		                                <option value='".$row2['main_category_id']."'>".$row2['main_category_name']."</option>";
		                        	}
		                        }
		                        $text .= "
		                        </select>
							</td>
						</tr>";
	}
	
	$text .= "
						<tr>
							<td>
								<b>".EASYSHOP_CAT_04."</b>
							</td>
							<td>
								<input class='tbox' size='25' type='text' name='category_name' value='".$category_name."' />
							</td>
						</tr>
						<tr>
							<td valign='top'>
								<b>".EASYSHOP_CAT_05."</b>
							</td>
							<td>
								<textarea class='tbox' cols='50' rows='7' name='category_description' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>".$category_description."</textarea><br />".display_help('helpa')."
							</td>
						</tr>
						<tr>
							<td valign='top'>
								<b>".EASYSHOP_CAT_06."</b>
								<br />
								".EASYSHOP_CAT_07."
							</td>
							<td valign='top'>
                <input type='text' class='tbox' id='category_image' name='category_image' value='".$category_image."' /> ".EASYSHOP_CAT_08."<br />";
                // Show icons with width 16 of the array of images and put name in variable $category_image
            		foreach($image_array as $icon)
                {
                $text  .= "<a href=\"javascript:insertext('" . $icon['fname'] . "','category_image','catimg')\"><img src='" . $icon['path'] . $icon['fname'] . "' style='border:0' alt='' width='".$icon_width."' /></a> ";
                }

          $text .= "
							</td>
						</tr>
            <tr>
              <td>
                <b>".EASYSHOP_CAT_15."</b>
              </td>
              <td>
						";

						// Display the check box for active status (active = 2)
						if ($category_active_status == 2) {
								$text .= "
								<input type='checkbox' name='category_active_status' value='2' checked='checked' />";
						} else {
								$text .= "
								<input type='checkbox' name='category_active_status' value='1' />";
						}

    	      $text .= "
              </td>
            </tr>

      			<tr>
					<td valign='top'>
      						<b>".EASYSHOP_CAT_23."</b>
      				</td>
					<td valign='top'>
      					".r_userclass("category_class", $category_class, "off", "public,guest,member,nobody,main,admin,classes")."
      				</td>
       			</tr>
      			<tr>
					<td valign='top'>
      						<b>".EASYSHOP_CAT_25."</b>
      				</td>
					<td valign='top'>
      					".r_userclass("category_order_class", $category_order_class, "off", "public,guest,member,nobody,main,admin,classes")."
      				</td>
       			</tr>

					</table>
				<br />
				<div style='text-align:center;'>
					<input type='hidden' name='category_id' value='".$_GET['category_id']."' />
					<input type='hidden' name='edit_category' value='2' />
					<input class='button' type='submit' value='".EASYSHOP_CAT_13."' />
				</div>
				<br />
				</fieldset>
			</div>
		</div>
	</form>";
	
	// Render the value of $text in a table.
	$title = EASYSHOP_CAT_18;
	$ns -> tablerender($title, $text);
	
} else {
  // Initial screen with Maintain Categories

  // Determine if there are no categories
	if($sql -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES) > 0) {
		$no_categories = 1;
	}

  // Check if there are active categories
  // active_status = 1 --> active 'off'
  // active_status = 2 --> active 'on'
	if($sql -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, '(*)', 'WHERE category_active_status = 2') == 0) {
		$no_active_categories = 1;
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
	<form id='cat_edit' method='post' action='".e_SELF."'>
		<div style='text-align:center;'>
				<fieldset>
					<legend>
						".EASYSHOP_CAT_01."
					</legend>";
          // Show a message if there are no categories to display
					if ($no_categories == null) {
						$text .= "
						<br />
						<div style='text-align:center;'>
							<span class='smalltext'>
								".EASYSHOP_CAT_02."
							</span>
						</div>
						<br />";
					} else {
						$text .= "
						<div style='text-align:center;'>
						  <table style='".ADMIN_WIDTH."' class='fborder'>
							<tr>
									<td class='fcaption'><b>".EASYSHOP_CAT_06."</b></td>
									<td class='fcaption'><b>".EASYSHOP_CAT_04."</b></td>";

            // Fill extra column if there are active main categories
            if ($main_cat_count > 0) {
  						$text .= "<td class='fcaption'><div style='text-align:center;'><b>".EASYSHOP_CAT_22."</b></div></td>";
						} else {
  						$text .= "<td class='fcaption'></td>";
						}
									
						$text .= "
									<td class='fcaption'><div style='text-align:center;'><b>".EASYSHOP_CAT_14."</b></div></td>
									<td class='fcaption'><div style='text-align:center;'><b>".EASYSHOP_CAT_15."</b></div></td>
									<td class='fcaption'><div style='text-align:center;'><b>".EASYSHOP_CAT_21."</b></div></td>
									<td class='fcaption'><div style='text-align:center;'><b>".EASYSHOP_CAT_23."</b></div></td>
									<td class='fcaption'><div style='text-align:center;'><b>".EASYSHOP_CAT_25."</b></div></td>
									<td class='fcaption'><div style='text-align:center;'><b>".EASYSHOP_CAT_19."</b></div></td>
								</tr>";
								// While there are records available; fill the rows to display them all in the userdefined order
								// First query: select the categories
								$sql -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "ORDER BY category_order", "no-where");
								while($row = $sql-> db_Fetch()){
                  // Second query: Count the number of products in the category
                  $sql2 = new db;
									$prod_cat_count = $sql2 -> db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE category_id='".$row['category_id']."'");

									$text .= "
									<tr>
										<td class='forumheader3'>";
										// Show the category image if it is available
										if ($row['category_image'] == '') {
											$text .= "
											&nbsp;";
										} else {
											$text .= "
											<img src='$store_image_path".$row['category_image']."' alt='".$row['category_image']."' title='".$row['category_image']."' /> <!-- height='100' width='80' /> -->
											<br />
											"; // .$row['category_image'];
										}
										$text .= "
										</td>
										<td class='forumheader3'>";
										
                    // Show link to product inventory for the specific category only if there are products in the category
										if ($prod_cat_count > 0) { $text .= "<a href='admin_config.php?cat.".$row['category_id']."'>"; }
										$text .= $row['category_name'];
										// End tag of the conditional link
                    if ($prod_cat_count > 0) { $text .= "</a>"; }
                    
                    $text .= "
										</td>";
										
                    // Fill extra column if there are active main categories
                    if ($main_cat_count > 0) {
                      // Retrieve Main Category desciption
                      $main_category_name = ""; // Reset to blank for each category
                      $sql3 = new db;
                      $sql3 -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "WHERE main_category_id = ".$row['category_main_id'], false);
                      while($row3 = $sql3-> db_Fetch()){
                        $main_category_name = $row3['main_category_name'];
          						}
          						$text .= "<td class='forumheader3'><div style='text-align:center;'>".$main_category_name."</div></td>";
        						} else {
          						$text .= "<td class='forumheader3'></td>";
        						}
										
										$text .= "
										<td class='forumheader3'>
											<div style='text-align:center;'>
						                        <select class='tbox' name='category_order[]'>";
            						            // Third query: Build the selection list with order numbers
						                        $sql3 = new db;
						                        $num_rows = $sql3 -> db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)");
						                        $count = 1;
						                        while ($count <= $num_rows) {
						                            if ($row['category_order'] == $count) {
						                                $text .= "
						                                <option value='".$row['category_id']."~".$count."' selected='selected'>".$count."</option>";
						                            } else {
						                                $text .= "
						                                <option value='".$row['category_id']."~".$count."'>".$count."</option>";
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
											if ($row['category_active_status'] == 2) {
												$text .= "
												<input type='checkbox' name='category_active_status[]' value='".$row['category_id']."' checked='checked' />";
											} else {
												$text .= "
												<input type='checkbox' name='category_active_status[]' value='".$row['category_id']."' />";
											}

                      // Show the number of products in the category
											$text .= "
											</div>
										</td>
										<td class='forumheader3'><div style='text-align:center;'>".$prod_cat_count."</div>
										</td>";
										
                    // Show class description
                    $text .="<td class='forumheader3'>".r_userclass_name($row['category_class'])."</td>";
                    // Show class_order description
                    $text .="<td class='forumheader3'>".r_userclass_name($row['category_order_class'])."</td>";

  										// Show the edit and delete icons
											$text .= "
										<td class='forumheader3'>
											<div style='text-align:center;'>
											<a href='".e_SELF."?edit_category=1&category_id=".$row['category_id']."' alt='".EASYSHOP_CAT_16."'>".ADMIN_EDIT_ICON."</a>
                      &nbsp;";
                      // Show delete icon conditionally (only when there are no products in the category)
                      if ($prod_cat_count == 0) {
											$text .= "
											<a href='".e_SELF."?delete_category=1&category_id=".$row['category_id']."' alt='".EASYSHOP_CAT_17."'>".ADMIN_DELETE_ICON."</a>";
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
							<input type='hidden' name='change_order' value='1' />
							<input class='button' type='submit' value='".EASYSHOP_CAT_13."' />
						</div>
						<br />";

            if ($no_active_categories == 1) {
							$text .= "<img src='".e_IMAGE."admin_images/docs_16.png' title='' alt='' /> ".EASYSHOP_CAT_20;
            }
						
					}
				$text .= "
				</fieldset>
		</div>
	</form>
	<br />";

  // Create a new category
	$text .= "
	<form id='cat_new' method='post' action='".e_SELF."'>
		<div style='text-align:center;'>
			<div style='width:80%'>
				<fieldset>
					<legend>
						".EASYSHOP_CAT_03."
					</legend>
					<table border='0' cellspacing='15' width='100%'>";

  // Only display Main Category selection if there are active Main Categories
  if ($sql->db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, "(*)", "WHERE main_category_active_status = '2'") > 0) {
	$text .= "
						<tr>
							<td>
								<b>".EASYSHOP_CAT_22."</b>
							</td>
							<td>
								<select class='tbox' name='category_main_id'>";
		                        $sql2 = new db;
		                        $sql2 -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "WHERE main_category_active_status = '2' ORDER BY main_category_order", false); // Select only active main categories
                      			$text .= "<option value='' selected='selected'></option>"; // Add a blank option too
		                        while ($row2 = $sql2->db_Fetch()) {
		                        	if ($row2['main_category_id'] == $category_main_id) {
	                        			$text .= "
		                                <option value='".$row2['main_category_id']."' selected='selected'>".$row2['main_category_name']."</option>";
		                        	} else {
	                        			$text .= "
		                                <option value='".$row2['main_category_id']."'>".$row2['main_category_name']."</option>";
		                        	}
		                        }
		                        $text .= "
		                        </select>
							</td>
						</tr>";
	}
					
  $text .= "
						<tr>
							<td>
								<b>".EASYSHOP_CAT_04."</b>
							</td>
							<td>
								<input class='tbox' size='25' type='text' name='category_name' />
							</td>
						</tr>
						<tr>
							<td valign='top'>
								<b>".EASYSHOP_CAT_05."</b>
							</td>
							<td>
								<textarea class='tbox' cols='50' rows='7' name='category_description' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'></textarea><br />".display_help('helpb')."
							</td>
						</tr>
						<tr>
							<td valign='top'>
								<b>".EASYSHOP_CAT_06."</b>
								<br />
								".EASYSHOP_CAT_07."
							</td>
							<td valign='top'>
                <input type='text' class='tbox' id='category_image' name='category_image' value='".$category_image."' /> ".EASYSHOP_CAT_08."<br />";
                // Show icons with width 16 of the array of images and put name in variable $category_image
            		foreach($image_array as $icon)
                {
                $text  .= "<a href=\"javascript:insertext('" . $icon['fname'] . "','category_image','catimg')\"><img src='" . $icon['path'] . $icon['fname'] . "' style='border:0' alt='' width='".$icon_width."' /></a> ";
                }

          $text .= "
							</td>
						</tr>
						<tr>
							<td>
								<b>".EASYSHOP_CAT_15."</b>
							</td>
							<td>
								<input type='checkbox' name='category_active_status' value='1' />
							</td>
						</tr>
						<tr>
							<td valign='top'>
								<b>".EASYSHOP_CAT_23."</b>
							</td>
							<td valign='top'>
								".r_userclass("category_class", $category_class, "off", "public,guest,member,nobody,main,admin,classes")."
							</td>
						</tr>
						<tr>
							<td valign='top'>
								<b>".EASYSHOP_CAT_25."</b>
							</td>
							<td valign='top'>
								".r_userclass("category_order_class", $category_order_class, "off", "public,guest,member,nobody,main,admin,classes")."
							</td>
						</tr>
					</table>
					<br />
					<div style='text-align:center;'>
						<input type='hidden' name='create_category' value='1' />
						<input class='button' type='submit' value='".EASYSHOP_CAT_09."' />
					</div>
					<br />
				</fieldset>
			</div>
		</div>
	</form>";
	
	// Render the value of $text in a table.
	$title = EASYSHOP_CAT_00;
	$ns -> tablerender($title, $text);
}
// END MAIN ADMIN CATEGORIES
require_once(e_ADMIN.'footer.php');
?>