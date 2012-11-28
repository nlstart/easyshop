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
// Heavily modified version of filemanager.php

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
$pageid = 'admin_menu_08';

$imagedir = e_IMAGE."filemanager/";

/*
$e_sub_cat = 'easyshop';
require_once("auth.php");

$pubfolder = (str_replace("../","",e_QUERY) == str_replace("../","",e_PLUGIN."easyshop/images/")) ? TRUE : FALSE;


$imagedir = e_IMAGE."filemanager/";

	$dir_options[0] = EASYSHOP_UPLOAD_47;
	$dir_options[1] = EASYSHOP_UPLOAD_35;
	$dir_options[2] = EASYSHOP_UPLOAD_40;


	$adchoice[0] = e_PLUGIN."easyshop/";
	$adchoice[1] = e_FILE;
	$adchoice[2] = e_IMAGE."newspost_images/";


$path = str_replace("../", "", e_QUERY);
if (!$path) {
	$path = str_replace("../", "", $adchoice[0]);
}

if($path == "/")
{
	$path = $adchoice[0];
	echo "<b>Debug</b> ".$path." <br />";
}
//*/
// ===============================================


foreach($_POST['deleteconfirm'] as $key=>$delfile){
	// check for delete.
	if (isset($_POST['selectedfile'][$key]) && isset($_POST['deletefiles'])) {
		if (!$_POST['ac'] == md5(ADMINPWCHANGE)) {
			exit();
		}
		//$destination_file = e_BASE.$delfile;
		$destination_file = $delfile;
		if (@unlink($destination_file)) {
			$message .= EASYSHOP_UPLOAD_26." '".$destination_file."' ".EASYSHOP_UPLOAD_27.".<br />";
		} else {
			$message .= EASYSHOP_UPLOAD_28." '".$destination_file."'.<br />";
		}
	}

  /*
  // EasyShop admin_upload.php won't support file moves
	// check for move to downloads or downloadimages.
	if (isset($_POST['selectedfile'][$key]) && (isset($_POST['movetodls'])) ){
	$newfile = str_replace($path,"",$delfile);

	// Move file to whatever folder.
		if (isset($_POST['movetodls'])){

			$newpath = $_POST['movepath'];

			//if (rename(e_BASE.$delfile,$newpath.$newfile)){
			if (rename($delfile,$newpath.$newfile)){
				$message .= EASYSHOP_UPLOAD_38." ".$newpath.$newfile."<br />";
			} else {
				$message .= EASYSHOP_UPLOAD_39." ".$newpath.$newfile."<br />";
				$message .= (!is_writable($newpath)) ? $newpath.LAN_NOTWRITABLE : "";
			}
		}
	}
	*/
}

if (isset($_POST['upload'])) {
	if (!$_POST['ac'] == md5(ADMINPWCHANGE)) {
		exit();
	}
	$pref['upload_storagetype'] = "1";
	require_once(e_HANDLER."upload_handler.php");
	$files = $_FILES['file_userfile'];
	foreach($files['name'] as $key => $name) {
		if ($files['size'][$key]) {
			//$uploaded = file_upload(e_BASE.$_POST['upload_dir'][$key]);
			$uploaded = file_upload($_POST['upload_dir'][$key]);
		}
	}
}

if (isset($message)) {
	$ns->tablerender("", "<div style=\"text-align:center\"><b>".$message."</b></div>");
}

//if (strpos(e_QUERY, ".") && !is_dir(realpath(e_BASE.$path))){
//	echo "<iframe style=\"width:100%\" src=\"".e_BASE.e_QUERY."\" height=\"300\" scrolling=\"yes\"></iframe><br /><br />";
// EasyShop adjustment for displaying the image: exclude the ../ from showing!
if (e_QUERY != "" && substr(e_QUERY,-3) != "../" ) {
	echo "<iframe style=\"width:100%\" src=\"".e_QUERY."\" height=\"300\" scrolling=\"yes\"></iframe><br /><br />";
	if (!strpos(e_QUERY, "/")) {
		$path = "";
	} else {
		$path = substr($path, 0, strrpos(substr($path, 0, -1), "/"))."/";
	}
}

// Retrieve shop preferences to get image path
$sql = new db;
$sql -> db_Select(DB_TABLE_SHOP_PREFERENCES, "*", "store_id=1");
while($row = $sql-> db_Fetch()){
  $store_image_path = $row['store_image_path'];
}

$path = e_PLUGIN."easyshop/".$store_image_path;

$files = array();
$dirs = array();
$path = explode("?", $path);
$path = $path[0];
$path = explode(".. ", $path);
$path = $path[0];

//if ($handle = opendir(e_BASE.$path)) {
if ($handle = opendir($path)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {

			//if (getenv('windir') && is_file(e_BASE.$path."\\".$file)) {
			if (getenv('windir') && is_file($path."\\".$file)) {
				//if (is_file(e_BASE.$path."\\".$file)) {
				if (is_file($path."\\".$file)) {
					$files[] = $file;
				} else {
					$dirs[] = $file;
				}
			} else {
				//if (is_file(e_BASE.$path."/".$file)) {
				if (is_file($path."/".$file)) {
					$files[] = $file;
				} else {
					$dirs[] = $file;
				}
			}
		}
	}
}
// EasyShop modification; add an upload 'directory'; so we can upload from showing the list of images
$dirs[] = "..";
closedir($handle);

if (count($files) != 0) {
	sort($files);
}
if (count($dirs) != 0) {
	sort($dirs);
}

if (count($files) == 1) {
	$cstr = EASYSHOP_UPLOAD_12;
} else {
	$cstr = EASYSHOP_UPLOAD_13;
}

if (count($dirs) == 1) {
	$dstr = EASYSHOP_UPLOAD_14;
} else {
	$dstr = EASYSHOP_UPLOAD_15;
}

$pathd = $path;

/*
$text = "<div style='text-align:center'>\n
	<form method='post' action='".e_SELF."?".e_QUERY."'>\n
	<table style='".ADMIN_WIDTH."' class='fborder'>\n
	<tr>\n\n

	<td style='width:70%' class='forumheader3'>\n
	".EASYSHOP_UPLOAD_32."
	</td>\n
	<td class='forumheader3' style='text-align:center; width:30%'>\n
	<select name='admin_choice' class='tbox' onchange=\"location.href=this.options[selectedIndex].value\">\n";


	foreach($dir_options as $key=>$opt){
		$select = (str_replace("../","",$adchoice[$key]) == e_QUERY) ? "selected='selected'" : "";
		$text .= "<option value='".e_SELF."?".str_replace("../","",$adchoice[$key])."' $select>".$opt."</option>\n";
	}

$text .= "</select>\n
	</td>\n
	</tr>\n\n

	<tr style='vertical-align:top'>\n
	<td colspan='2'  style='text-align:center' class='forumheader'>\n
	<input class='button' type='submit' name='updateoptions' value='".EASYSHOP_UPLOAD_33."' />\n
	</td>\n
	</tr>\n\n

	</table>\n
	</form>\n
	</div>";
$ns->tablerender(EASYSHOP_UPLOAD_34, $text);
*/

$text = "<form enctype=\"multipart/form-data\" action=\"".e_SELF.(e_QUERY ? "?".e_QUERY : "")."\" method=\"post\">
	<div style=\"text-align:center\">
	<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\" />
	<table class='fborder' style=\"".ADMIN_WIDTH."\">";

$text .= "<tr>
	<td style=\"width:5%\" class=\"fcaption\">&nbsp;</td>
	<td style=\"width:30%\" class=\"fcaption\"><b>".EASYSHOP_UPLOAD_17."</b></td>
	<td class=\"fcaption\"><b>".EASYSHOP_UPLOAD_18."</b></td>
	<td style=\"width:30%\" class=\"fcaption\"><b>".EASYSHOP_UPLOAD_19."</b></td>
	<td class=\"fcaption\"><b>".LAN_OPTIONS."</b></td>
	</tr>";

if ($path != e_FILE) {
	if (substr_count($path, "/") == 1) {
		$pathup = e_SELF;
	} else {
		$pathup = e_SELF."?".substr($path, 0, strrpos(substr($path, 0, -1), "/"))."/";
	}
  /*
 // EasyShop admin_upload.php won't support going to higher folders (because it doesn't make sense and for security reasons)
  $text .= "<tr><td colspan=\"5\" class=\"forumheader3\"><a href=\"".$pathup."\"><img src=\"".$imagedir."updir.png\" alt=\"".EASYSHOP_UPLOAD_30."\" style=\"border:0\" /></a>
		<a href=\"admin_upload.php\"><img src=\"".$imagedir."home.png\" alt=\"".EASYSHOP_UPLOAD_16."\" style=\"border:0\" /></a>
		</td>
		</tr>";
  */
}

$c = 0;
while ($dirs[$c]) {
	$dirsize = dirsize($path.$dirs[$c]);
	$text .= "<tr>
		<td class=\"forumheader3\" style=\"vertical-align:middle; text-align:center; width:5%\">
		<a href=\"".e_SELF."?".$path.$dirs[$c]."/\"><img src=\"".$imagedir."folder.png\" alt=\"".$dirs[$c]." ".EASYSHOP_UPLOAD_31."\" style=\"border:0\" /></a>
		</td>
		<td style=\"width:30%\" class=\"forumheader3\">
		<a href=\"".e_SELF."?".$path.$dirs[$c]."/\">".$dirs[$c]."</a>
		</td>
		<td class=\"forumheader3\">".$dirsize."
		</td>
		<td class=\"forumheader3\">&nbsp;</td>
		<td class=\"forumheader3\">";
	//if (FILE_UPLOADS && is_writable(e_BASE.$path.$dirs[$c])) {
	if (FILE_UPLOADS && is_writable($path.$dirs[$c])) {
    if (substr($path.$dirs[$c],-3) == "/..") {
      // For the root path we strip the last three characters (/..)
      $dirname = substr($path.$dirs[$c],0,-3);
    } else { // other directories show the correct path already
      $dirname = $path.$dirs[$c];
    }
		$text .= "<input class=\"button\" type=\"button\" name=\"erquest\" value=\"".EASYSHOP_UPLOAD_21."\" onclick=\"expandit(this)\" />
			<div style=\"display:none;\">
			<input class=\"tbox\" type=\"file\" name=\"file_userfile[]\" size=\"50\" />
			<input class=\"button\" type=\"submit\" name=\"upload\" value=\"".EASYSHOP_UPLOAD_22."\" />
			<input type=\"hidden\" name=\"upload_dir[]\" value=\"".$dirname."\" />
			</div>";
	} else {
		$text .= "&nbsp;";
	}
	$text .= "</td>
		</tr>
		";
	$c++;
}

$c = 0;
while ($files[$c]) {
	$img = substr(strrchr($files[$c], "."), 1, 3);
	if (!$img || !preg_match("/css|exe|gif|htm|jpg|js|php|png|txt|xml|zip/i", $img)) {
		$img = "def";
	}
	//$size = parsesize(filesize(e_BASE.$path."/".$files[$c]));
	$size = parsesize(filesize($path."/".$files[$c]));
	$text .= "<tr>
		<td class=\"forumheader3\" style=\"vertical-align:middle; text-align:center; width:5%\">
		<img src=\"".$imagedir.$img.".png\" alt=\"".$files[$c]."\" style=\"border:0\" />
		</td>
		<td style=\"width:30%\" class=\"forumheader3\">
		<a href=\"".e_SELF."?".$path.$files[$c]."\">".$files[$c]."</a>
		</td>";
	$gen = new convert;
	//$filedate = $gen -> convert_date(filemtime(e_BASE.$path."/".$files[$c]), "forum");
	$filedate = $gen -> convert_date(filemtime($path."/".$files[$c]), "forum");
	$text .= "<td style=\"width:10%\" class=\"forumheader3\">".$size."</td>
		<td style=\"width:30%\" class=\"forumheader3\">".$filedate."</td>
		<td class=\"forumheader3\">";

  // EasyShop modification: suppress the delete option for index.html and .htaccess
  if ($files[$c] != "index.html" and $files[$c] != ".htaccess") {
    $text .= "<input  type=\"checkbox\" name=\"selectedfile[$c]\" value=\"1\" />";
  }
	$text .="<input type=\"hidden\" name=\"deleteconfirm[$c]\" value=\"".$path.$files[$c]."\" />";

	$text .="</td>
		</tr>";
	$c++;
}

	$text .= "<tr><td colspan='5' class='forumheader' style='text-align:right'>";

  /*
  // EasyShop admin_upload.php won't support file moves
	if ($pubfolder || e_QUERY == ""){
        require_once(e_HANDLER.'file_class.php');
		$fl = new e_file;
		$dl_dirlist = $fl->get_dirs(e_DOWNLOAD);
		$movechoice = array();
        $movechoice[] = e_DOWNLOAD;
		foreach($dl_dirlist as $dirs){
        	$movechoice[] = e_DOWNLOAD.$dirs."/";
		}
		sort($movechoice);
		$movechoice[] = e_FILE."downloadimages/";
		if(e_QUERY != str_replace("../","",e_FILE."public/")){
        	$movechoice[] = e_FILE."public/";
		}
		if(e_QUERY != str_replace("../","",e_FILE."downloadthumbs/")){
        	$movechoice[] = e_FILE."downloadthumbs/";
		}
		if(e_QUERY != str_replace("../","",e_FILE."misc/")){
        	$movechoice[] = e_FILE."misc/";
		}
		if(e_QUERY != str_replace("../","",e_IMAGE)){
        	$movechoice[] = e_IMAGE;
		}
		if(e_QUERY != str_replace("../","",e_IMAGE."newspost_images/")){
        	$movechoice[] = e_IMAGE."newspost_images/";
		}




        $text .= EASYSHOP_UPLOAD_48."&nbsp;<select class='tbox' name='movepath'>\n";
        foreach($movechoice as $paths){
        	$text .= "<option value='$paths'>".str_replace("../","",$paths)."</option>\n";
		}
		$text .= "</select>&nbsp;";
		$text .="<input class=\"button\" type=\"submit\" name=\"movetodls\" value=\"".EASYSHOP_UPLOAD_50."\" onclick=\"return jsconfirm('".$tp->toJS(EASYSHOP_UPLOAD_49)."') \" />
		";
	}
	*/

	$text .= "<input class=\"button\" type=\"submit\" name=\"deletefiles\" value=\"".EASYSHOP_UPLOAD_43."\" onclick=\"return jsconfirm('".$tp->toJS(EASYSHOP_UPLOAD_46)."') \" />
		</td></tr></table>
		<input type='hidden' name='ac' value='".md5(ADMINPWCHANGE)."' />
		</div>
		</form>";

$ns->tablerender(EASYSHOP_UPLOAD_29.": <b>root/".$pathd."</b>&nbsp;&nbsp;[ ".count($dirs)." ".$dstr.", ".count($files)." ".$cstr." ]", $text);

function dirsize($dir) {
	$_SERVER["DOCUMENT_ROOT"].e_HTTP.$dir;
	$dh = @opendir($_SERVER["DOCUMENT_ROOT"].e_HTTP.$dir);
	$size = 0;
	while ($file = @readdir($dh)) {
		if ($file != "." and $file != "..") {
			$path = $dir."/".$file;
			if (is_file($_SERVER["DOCUMENT_ROOT"].e_HTTP.$path)) {
				$size += filesize($_SERVER["DOCUMENT_ROOT"].e_HTTP.$path);
			} else {
				$size += dirsize($path."/");
			}
		}
	}
	@closedir($dh);
	return parsesize($size);
}

function parsesize($size) {
	$kb = 1024;
	$mb = 1024 * $kb;
	$gb = 1024 * $mb;
	$tb = 1024 * $gb;
	if ($size < $kb) {
		return $size." b";
	}
	else if($size < $mb) {
		return round($size/$kb, 2)." kb";
	}
	else if($size < $gb) {
		return round($size/$mb, 2)." mb";
	}
	else if($size < $tb) {
		return round($size/$gb, 2)." gb";
	} else {
		return round($size/$tb, 2)." tb";
	}
}

require_once(e_ADMIN.'footer.php');
?>