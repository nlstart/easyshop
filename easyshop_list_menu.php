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
if (!defined('e107_INIT')) { exit(); }

// Get language file (assume that the English language file is always present)
include_lan(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');
// include define tables info
require_once(e_PLUGIN."easyshop/includes/config.php"); // It's important to point to the correct plugin folder!

$sql = new db;
// Count active categories
$active_cat_count = $sql->db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status = '2' AND (category_class IN (".USERCLASS_LIST.")) ");
// Count active main categories
$active_main_count = $sql->db_Count(DB_TABLE_SHOP_MAIN_CATEGORIES, "(*)", "WHERE main_category_active_status = '2' ");

$l_text .= "<table>";

// Select all active main categories
if ($active_main_count > 0) {
  $sql0 = new db;
  $sql0 -> db_Select(DB_TABLE_SHOP_MAIN_CATEGORIES, "*", "main_category_active_status = '2'");
  while($row0 = $sql0-> db_Fetch()){
    $main_category_id    = $row0['main_category_id'];
  	$main_category_name  = $row0['main_category_name'];

    $sql10 = new db; // Count all active categories of fetched main category
    $active_cat_count = $sql10->db_Count(DB_TABLE_SHOP_ITEM_CATEGORIES, "(*)", "WHERE category_active_status = '2' AND category_main_id = '$main_category_id' AND (category_class IN (".USERCLASS_LIST.")) ");
    $l_text .= "<tr><td><a href='".e_PLUGIN."easyshop/easyshop.php?mcat.$main_category_id'><b>".$main_category_name."</b></a> ($active_cat_count)</td></tr>";

    // Select all active categories of the fetched main category
    $sql1 = new db;
    $sql1 -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_active_status = '2' AND category_main_id='".$main_category_id."'  AND (category_class IN (".USERCLASS_LIST.")) " );
    while($row1 = $sql1-> db_Fetch()){
      $category_id    = $row1['category_id'];
    	$category_name  = $row1['category_name'];

      $sql2 = new db; // Count all active products of the fetched category
      $active_prod_count = $sql2->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status = '2' AND category_id=$category_id");
     	$l_text .= "<tr><td>&nbsp;&nbsp;<a href='".e_PLUGIN."easyshop/easyshop.php?cat.$category_id'><b>".$category_name."</b></a> ($active_prod_count)</td></tr>";

      $l_text .= "<tr><td><ul>";
    	$sql3 = new db; // Select all active products of the fetched category
      $sql3 -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_active_status = '2' AND category_id=$category_id ORDER BY item_order");
      while($row3 = $sql3-> db_Fetch()){
      	$item_id          = $row3['item_id'];
      	$item_name        = $row3['item_name'];

      	$l_text .="<li><a href='".e_PLUGIN."easyshop/easyshop.php?prod.$item_id'>".$item_name."</a></li>";
      } // End of while for products
      $l_text .= "</ul></td></tr>";
    } // End of while for categories
  } // End of while for main categories
} // End of if active main categories count > 0

// Select all active categories without main category  (Remain backwards compatible with EasyShop 1.2 AND main category is not mandatory)
$sql1 = new db;
$sql1 -> db_Select(DB_TABLE_SHOP_ITEM_CATEGORIES, "*", "category_active_status = '2' AND category_main_id='' AND (category_class IN (".USERCLASS_LIST.")) " );
while($row1 = $sql1-> db_Fetch()){
  $category_id    = $row1['category_id'];
	$category_name  = $row1['category_name'];

  $sql2 = new db; // Count all active products of the fetched category
  $active_prod_count = $sql2->db_Count(DB_TABLE_SHOP_ITEMS, "(*)", "WHERE item_active_status = '2' AND category_id=$category_id");
 	$l_text .= "<tr><td>&nbsp;&nbsp;<a href='".e_PLUGIN."easyshop/easyshop.php?cat.$category_id'><b>".$category_name."</b></a> ($active_prod_count)</td></tr>";

  ($active_prod_count>0)?($l_text .= "<tr><td><ul>"):""; // For valid XHTML 1.1
	$sql3 = new db; // Select all active products of the fetched category
  $sql3 -> db_Select(DB_TABLE_SHOP_ITEMS, "*", "item_active_status = '2' AND category_id=$category_id ORDER BY item_order");
  while($row3 = $sql3-> db_Fetch()){
  	$item_id          = $row3['item_id'];
  	$item_name        = $row3['item_name'];

  	$l_text .="<li><a href='".e_PLUGIN."easyshop/easyshop.php?prod.$item_id'>".$item_name."</a></li>";
  } // End of while for products
  ($active_prod_count>0)?$l_text .= "</ul></td></tr>":""; // For valid XHTML 1.1

} // End of while for categories

$l_text .= "</table>";

$caption = "<div style='text-align:center;'>".EASYSHOP_PUBLICMENU2_01." (".(($active_main_count > 0)?$active_main_count:$active_cat_count).")</div>";
$ns -> tablerender($caption, $l_text);
?>