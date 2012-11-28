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

if (!defined('e107_INIT')) { exit; }

$advanced_where = "	#easyshop_item_categories.category_active_status = '2' AND
           					#easyshop_items.item_active_status = '2' AND
          					#easyshop_item_categories.category_class IN (".USERCLASS_LIST.")";

// The fields that will be returned by the SQL
$return_fields = "item_name, item_description, item_id, item_active_status, sku_number, item_out_of_stock_explanation";

// The fields that can be search for matches
$search_fields = array("item_name",
                       "item_description",
                       "sku_number",
                       "item_out_of_stock_explanation");

// A weighting for the importance of finding a match in each of the search fields
$weights = array("1.2", "1.0", "0.9", "0.8");

// Message to be displayed when no matches found
$no_results = LAN_198;

// The SQL WHERE clause, if any / also add the advanced where if that variable is declared
$where = "1 ".($advanced_where ? "AND ".$advanced_where : "")." AND "; // thanks to Luceos

// The SQL ORDER BY columns as a keyed array
$order = array('item_id' => DESC);

// The table(s) to be searched
$table = "easyshop_items LEFT JOIN #easyshop_item_categories
          ON #easyshop_items.category_id = #easyshop_item_categories.category_id ";

// Perform the search
$ps = $sch->parsesearch($table, $return_fields, $search_fields, $weights,
                        'search_easyshop', $no_results, $where, $order);

// Assign the results to specific variables
$text .= $ps['text'];
$results = $ps['results'];

// A callback function (name is passed to the parsesearch() function above)
// It is passed a single row from the DB result set
function search_easyshop($row) {
   global $pref;
   global $con;

   // Populate as many of the $res array keys as is sensible for the plugin
   $res['link'] = e_PLUGIN."easyshop/easyshop.php?prod.".$row["item_id"];
   $res['pre_title'] = "";
   $res['title'] = $row["sku_number"]." : ".$row["item_name"];
   $res['pre_summary'] = "";
   $res['summary'] = $row['item_out_of_stock_explanation'];
   $res['detail'] = $row['item_description'];
   return $res;
}
?>