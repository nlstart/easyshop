<?php 
if (!defined('e107_INIT')) { exit; }
include_once(e_HANDLER.'shortcode_handler.php');
global $tp;
$easyshop_shortcodes = $tp->e_sc->parse_scbatch(__FILE__);
/*
// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_HEADER
	$item = getcachedvars('easyshop_store_header');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_LINK
	$in_array = getcachedvars('easyshop_mcat_link');
	$item = "<a href='".$in_array[0]."?mcat.".$in_array[1]."'><b>".$in_array[2]."</b></a>";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_NOTFOUND
	$item = getcachedvars('easyshop_mcat_notfound');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_CAT_IMAGE
	$in_array = getcachedvars('easyshop_cat_image');
	if ($in_array == '&nbsp;')
	{
		$item = $in_array;
	}
	else
	{
		$item = "<a href='".$in_array[0]."?cat.".$in_array[1]."'><img src='".$in_array[2].$in_array[3]."' style='border-style:none;' alt='' /></a>";
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_CAT_NAME_LINK
	$in_array = getcachedvars('easyshop_cat_name');
	$item = "<a href='".$in_array[0]."?cat.".$in_array[1]."'>".$in_array[2]."</a>";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_DESCR
	$item = getcachedvars('easyshop_cat_descr');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_TOTAL_PRODS_IN_CAT
	$item = getcachedvars('easyshop_total_prods_in_cat');
	($item <> 1)? $prod_text = EASYSHOP_SHOP_43 : $prod_text = EASYSHOP_SHOP_44;
	$item = $item." ".$prod_text." ".EASYSHOP_SHOP_45;
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ZERO_CAT
	$item = getcachedvars('easyshop_zero_cat');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PAGING
	$item = getcachedvars('easyshop_paging');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_FOOTER
	$item = getcachedvars('easyshop_store_footer');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ROW_BREAK
	$item = getcachedvars('easyshop_row_break');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_CONTAINER
	$item = getcachedvars('easyshop_mcat_container');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_SHOW_CHECKOUT
	$item = getcachedvars('easyshop_show_checkout');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_NAME
	$item = getcachedvars('easyshop_store_name');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_ADDRESS1
	$item = getcachedvars('easyshop_store_address1');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_ADDRESS2
	$item = getcachedvars('easyshop_store_address2');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_CITY
	$item = getcachedvars('easyshop_store_city');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_CONDITIONALBREAK
	$item = getcachedvars('easyshop_store_conditionalbreak');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_STATE
	$item = getcachedvars('easyshop_store_state');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_ZIP
	$item = getcachedvars('easyshop_store_zip');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_CONDITIONALBREAK2
	$item = getcachedvars('easyshop_store_conditionalbreak2');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_COUNTRY
	$item = getcachedvars('easyshop_store_country');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_EMAIL
	$in_array = getcachedvars('easyshop_store_email');
	if (strlen(trim($in_array)) > 0)
	{
		$item = "<a href=\"#\" onclick=\"JavaScript:window.location='mailto:'+'".$in_array[0]."'+'@'+'".$in_array[1]."'+'".$in_array[2]."'\">".EASYSHOP_SHOP_47."</a>";
		return $item;
	}
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_STORE_WELCOME_MESSAGE
	$item = getcachedvars('easyshop_store_welcome_message');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_NAME_LINK
	$in_array = getcachedvars('easyshop_mcat_name');
	$item = "<a href='".$in_array[0]."?mcat.".$in_array[1]."'>".$in_array[2]."</a>";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALL_MCAT_CONTAINER
	$item = getcachedvars('easyshop_all_mcat_container');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_IMAGE
	$in_array = getcachedvars('easyshop_mcat_image');
	if ($in_array == '&nbsp;')
	{
		$item = $in_array;
	}
	else
	{
		$item = "<a href='".$in_array[0]."?mcat.".$in_array[1]."'><img src='".$in_array[2].$in_array[3]."' style='border-style:none;' alt='' /></a>";
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_DESCR
	$in_array = getcachedvars('easyshop_mcat_descr');
	$item = $in_array[0]."<br />(".$in_array[1].")";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_CONDITIONALBREAK
	$item = getcachedvars('easyshop_mcat_conditionalbreak');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_MCAT_LOOSE_TITLE
	$in = getcachedvars('easyshop_mcat_loose_title');
	$item = "<a href='".e_SELF."?blanks'><b>".EASYSHOP_SHOP_46."</b></a><br />(".$in.")";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALL_MCAT_LOOSE_CONTAINER
	$item = getcachedvars('easyshop_all_mcat_loose_container');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_TITLE
	$in = getcachedvars('easyshop_allcat_action');
	if ($in == "blanks") {
		$item .= "<a href='easyshop.php'>".EASYSHOP_SHOP_40."</a> &raquo;";
	}
	$item .= "<b>";
	if ($in == "blanks") {
		$item .= "&nbsp;".EASYSHOP_SHOP_46;
	} else {
		$item .= EASYSHOP_SHOP_03;
	}
	$item .= "</b>";	
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_NO_CATEGORIES
	$item = getcachedvars('easyshop_allcat_no_categories');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_CONTAINER
	$item = getcachedvars('easyshop_allcat_container');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_CAT_NAME_LINK
	$in_array = getcachedvars('easyshop_allcat_cat_name_link');
	$item = "<a href='".e_SELF."?cat.".$in_array[0]."'>".$in_array[1]."</a>";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_CONDITIONALBREAK
	$item = getcachedvars('easyshop_allcat_conditionalbreak');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_CAT_IMAGE
	$in_array = getcachedvars('easyshop_allcat_cat_image');
	if ($in_array[2] == '') {
		$item .= "&nbsp;";
	} else {
		$item .= "<a href='".e_SELF."?cat.".$in_array[0]."'><img src='".$in_array[1].$in_array[2]."' style='border-style:none;' alt='' /></a>";
	}	
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_CAT_DESCRIPTION
	$item = getcachedvars('easyshop_allcat_cat_description');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_TOTAL_PROD_PER_CAT
	$in = getcachedvars('easyshop_allcat_total_prod_per_cat');
	($in <> 1)? $prod_text = EASYSHOP_SHOP_43 : $prod_text = EASYSHOP_SHOP_44;
	$item = $in ." ".$prod_text." ".EASYSHOP_SHOP_45;
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_CLASS_SPECIFIC
	$item = getcachedvars('easyshop_allcat_class_specific');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_TABLE_TD_END
	$item = getcachedvars('easyshop_allcat_table_td_end');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_SHOW_CHECKOUT
	$item = getcachedvars('easyshop_allcat_show_checkout');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ALLCAT_PAGING
	$item = getcachedvars('easyshop_allcat_paging');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_MCAT_LINK
	$in_array = getcachedvars('easyshop_cat_mcat_link');
	$item = '';
	if ($in_array[0] > 0 )
	{
		$item = "<a href='".e_SELF."?mcat.".$in_array[0]."'><b>".$in_array[1]."</b></a>";
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_CATNAME
	$item = getcachedvars('easyshop_cat_catname');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_NO_PRODUCTS
	$item = getcachedvars('easyshop_cat_no_products');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_PROD_IMAGE
	$in_array = getcachedvars('easyshop_cat_prod_image');
	if ($in_array[0] == '') {
		$item .= "&nbsp;";
	} else {
		// NOTE: in_array[0] is an array containing the images! Always display first image:
		$item .= "<a href='".e_SELF."?prod.".$in_array[1]."'><img src='".$in_array[2].$in_array[0][0]."' style='border-style:none;' alt='' /></a>";
	}	
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_CONTAINER
	$item = getcachedvars('easyshop_cat_container');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_PROD_IMAGE_MORE
	$in_array = getcachedvars('easyshop_cat_prod_image_more');
	if ($in_array[0] > 1) {
		$item = "<a href='".e_SELF."?prod.".$in_array[1]."'>".EASYSHOP_SHOP_84."</a>";
	} else {
		$item = "";
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_PROD_LINK
	$in_array = getcachedvars('easyshop_cat_prod_link');
	$item = "<a href='".e_SELF."?prod.".$in_array[0]."'>".$in_array[1]."</a>";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_SHOW_CHECKOUT
	$item = getcachedvars('easyshop_cat_show_checkout');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_OUT_OF_STOCK
	$in_array = getcachedvars('easyshop_cat_out_of_stock');
	$item = "";
	if ($in_array[0] == 2) {
		$item = "<div style='color: red'><b>".EASYSHOP_SHOP_07."</b></div><b>".$in_array[1]."<b>";	
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_PROD_PRICE
	$in_array = getcachedvars('easyshop_cat_prod_price');
	if ($in_array[3] <> '2')
	{	// Don't display the price for a quotation product // v1.6m
		$item = EASYSHOP_SHOP_10.": ".$in_array[0].number_format($in_array[1], 2, '.', '').$in_array[2];
		return $item;
	}
SC_END


// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_PROD_DETAILS_LINK
	$in_array = getcachedvars('easyshop_cat_prod_details_link');
	$item = "<a href='".e_SELF."?prod.".$in_array[0]."'>".$in_array[1]."</a>";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_ADD_TO_CART
	$item = getcachedvars('easyshop_cat_add_to_cart');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ADMIN_ICON
	if (ADMIN && getperms("P")) 
	{
		$in_array = getcachedvars('easyshop_admin_icon');
		$item = "<a href='admin_config.php?edit_item=1&amp;item_id=".$in_array[0]."&amp;category_id=".$in_array[1]."'><img style='border:0' src='".e_PLUGIN."easyshop/images/edit_16.png' alt='".EASYSHOP_CONF_ITM_22."' title='".EASYSHOP_CONF_ITM_22."'/></a>";
		return $item;
	}
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_TABLE_TD_END
	$item = getcachedvars('easyshop_cat_table_td_end');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_CONDITIONALBREAK
	$item = getcachedvars('easyshop_cat_conditionalbreak');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_NO_PRODUCTS
	$item = getcachedvars('easyshop_cat_no_products');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_MCAT_LINK
	$in_array = getcachedvars('easyshop_prod_mcat_link');
	$item = '';
	if ($in_array[0] > 0)
	{
		$item = "<a href='".e_SELF."?mcat.".$in_array[0]."'><b>".$in_array[1]."</b></a>";
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_CAT_LINK
	$in_array = getcachedvars('easyshop_prod_cat_link');
	$item = "<a href='".e_SELF."?cat.".$in_array[0]."'><b>".$in_array[1]."</b></a>";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_BREADCRUM
	$item = getcachedvars('easyshop_prod_breadcrum');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_IMAGE
	$in_array = getcachedvars('easyshop_prod_image');
	$item = "<img name='Prod_Image' src='".$in_array[0].$in_array[1][0]."' border='0'>";
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_NAME
	$item = getcachedvars('easyshop_prod_name');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_SKU_NUMBER
	$in = getcachedvars('easyshop_prod_sku_number');
	$item = '';
	if ($in <> "")
	{
		$item = EASYSHOP_SHOP_21.":&nbsp;".$in;
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_DESCRIPTION
	$item = getcachedvars('easyshop_prod_description');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_PRICE
	$in_array = getcachedvars('easyshop_prod_price');
	if ($in_array[3] <> '2')
	{	// Don't display the price for a quotation product // v1.6m
		$item = "<b>".EASYSHOP_SHOP_10.":</b> ".$in_array[0].number_format($in_array[1], 2, '.', '').$in_array[2];
		return $item;
	}
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_COSTS_SHIPPING_FIRST_ITEM
	$in_array = getcachedvars('easyshop_prod_costs_shipping_first_item');
	$item = '';
	if ($in_array[1] > 0)
	{
		$item = "<b>".EASYSHOP_SHOP_12.":</b> ".$in_array[0].number_format($in_array[1], 2, '.', '').$in_array[2];
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_COSTS_ADDITIONAL_ITEM
	$in_array = getcachedvars('easyshop_prod_costs_additional_item');
	$item = '';
	if ($in_array[1] > 0)
	{
		$item = "<b>".EASYSHOP_SHOP_13.":</b> ".$in_array[0].number_format($in_array[1], 2, '.', '').$in_array[2];
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_COSTS_HANDLING
	$in_array = getcachedvars('easyshop_prod_costs_handling');
	$item = '';
	if ($in_array[1] > 0)
	{
		$item = "<b>".EASYSHOP_SHOP_14.":</b> ".$in_array[0].number_format($in_array[1], 2, '.', '').$in_array[2];
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_OUT_OF_STOCK
	$in_array = getcachedvars('easyshop_prod_out_of_stock');
	$item = '';
	if ($in_array[0] == 2)
	{
		$item = "	<div style='color: red'>
						<b>".EASYSHOP_SHOP_07."</b>
					</div>
					<b>".$in_array[1]."<b>";
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_NON_EXTISTANT
	$in = getcachedvars('easyshop_prod_non_extistant');
	$item = '';
	if ($in = 0)
	{
		$item = EASYSHOP_SHOP_15;
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_ADD_TO_CART
	$item = getcachedvars('easyshop_add_to_cart');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_SHOW_CHECKOUT
	$item = getcachedvars('easyshop_prod_show_checkout');
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_ADMIN_ICON
	$in_array = getcachedvars('easyshop_admin_icon');
	$item = '';
	if (ADMIN && getperms("P"))
	{
		$item = "<a href='admin_config.php?edit_item=1&amp;item_id=".$in_array[0]."&amp;category_id=".$in_array[1]."'><img style='border:0' src='".e_PLUGIN."easyshop/images/edit_16.png' alt='".EASYSHOP_CONF_ITM_22."' title='".EASYSHOP_CONF_ITM_22."'/></a>";
	}
	return $item;
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_DOWNLOAD_DATASHEET_LINK
	$in = getcachedvars('easyshop_download_datasheet_filename');
	if(isset($in) && $in > 0)
	{
		$img_display = e_IMAGE."filemanager/pdf.png";
		if(!stristr($in, '.pdf'))
		{
			$img_display = e_PLUGIN."easyshop/images/arrowup_16.gif";
		}
		$item = "<a href='".e_SELF."?datasheet.".$in."' alt=''><img style='border:0' src='".$img_display."' alt='' /></a>&nbsp;<a href='".e_SELF."?datasheet.".$in."' alt=''>".EASYSHOP_SHOP_98."</a>";
		return $item;
	}
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_PROD_QUOTATION
	$in_array = getcachedvars('easyshop_item_quotation');
	if(isset($in_array[0]) && $in_array[0] == 2)
	{
		$item = "
		<form method='post' action='".e_SELF."?quotation'>
			<div style='text-align:center;'>
				<input type='hidden' name='item_id' value='".$in_array[1]."' />
				<input class='button' name='submit' type='submit' value='".EASYSHOP_SHOP_97."' />
			</div>
		</form>";
		return $item;
	}
SC_END

// ------------------------------------------------
SC_BEGIN EASYSHOP_CAT_PROD_QUOTATION
	$in_array = getcachedvars('easyshop_cat_prod_quotation');
	if(isset($in_array[0]) && $in_array[0] == 2)
	{
		$item = "
		<form method='post' action='".e_SELF."?quotation'>
			<div style='text-align:center;'>
				<input type='hidden' name='item_id' value='".$in_array[1]."' />
				<input class='button' name='submit' type='submit' value='".EASYSHOP_SHOP_97."' />
			</div>
		</form>";
		return $item;
	}
SC_END

*/
?>