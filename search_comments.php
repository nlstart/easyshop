<?php
// check called correctly
if (!defined('e107_INIT')) { exit(); }
// get the language file for your plugin
include_lan(e_PLUGIN . "easyshop/languages/" . e_LANGUAGE . ".php");
// The title of the plugin to be displayed in the main admin search page
$comments_title = "EasyShop";
// the id that is used to identify comments for this plugin in the e107_comments table
$comments_type_id = "EasyShop";
// fields to be returned from the search for this plugin
$comments_return['easyshop'] = "shp.item_id, shp.category_id,shp.item_name";
// a join from the comments table to your table in order that the search query can identify and return
// both the comment and the record in your plugin to which it refers
$comments_table['easyshop'] = "LEFT JOIN #easyshop_items AS shp ON c.comment_type='easyshop'
AND shp.item_id = c.comment_item_id";
// function to handle the results which are then displayed
// notice that the name has com_search_ prefixing the plugin dname
function com_search_easyshop($row) {
	global $con;
	// convert the comments datestamp
	$datestamp = $con -> convert_date($row['comment_datestamp'], "long");
	// link to the plugins record that has the comment made on it
	$res['link'] = e_PLUGIN."easyshop/easyshop.php?prod.".$row['item_id'];
	// pre title for example "comment found in -"
	$res['pre_title'] = "Comment found in: ";
	// the title or name of the plugin record
	$res['title'] = $row['item_name'];
	// the contents of the comment
	$res['summary'] = $row['comment_comment'];
	// get the user name of the commentator
	preg_match("/([0-9]+)\.(.*)/", $row['comment_author'], $user);
	// detailed information to be passed back, in this case a link to membership details of the commentator
	$res['detail'] = LAN_SEARCH_7."<a href='user.php?id.".$user[1]."'>".$user[2]."</a>".LAN_SEARCH_8.$datestamp;
	return $res;
}
?>