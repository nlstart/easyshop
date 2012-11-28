<?php
if (!defined('e107_INIT')) { exit(); }
//This is set to the table name you have decided to use.
$e_comment['eplug_comment_ids'] = "easyshop";

//This is set to the location you'd like the user to return to after replying to a comment.
$e_comment['reply_location'] = e_PLUGIN."easyshop/easyshop.php?prod.{NID}";

//A name for your plugin. It will be used in links to comments, in list_new/new.php.
$e_comment['plugin_name'] = "EasyShop";

//The path of the plugin folder
$e_comment['plugin_path'] = "easyshop";

//This is the name of the field in your plugin's db table that corresponds to it's name or title.
$e_comment['db_title'] = "item_name";

//This is the name of the field in your plugin's db table that correspond to it's unique id number.
$e_comment['db_id'] = "item_id";

//qry must be set with a select_gen query.
//the main reason would be to check if a category from another table has a class restriction
//the id of the item should be provided as {NID}
//returned fields should at least contain the 'link_id' and 'db_id' fields set above
$e_comment['qry'] = "
SELECT *
FROM #easyshop_items
WHERE item_id='{NID}' AND item_active_status = 2 ";
?>