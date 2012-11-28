<?php
/*
+------------------------------------------------------------------------------+
|     EasyShop - an easy e107 web shop  | adapted by nlstart
|     formerly known as
|	jbShop - by Jesse Burns aka jburns131 aka Jakle
|	Plugin Support Site: e107.webstartinternet.com
|
|	For the e107 website system visit http://e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+------------------------------------------------------------------------------+
*/
define("PAGE_NAME", "EasyShop");
// module plugin.php
define("EASYSHOP_DESC", "An easy to set up web shop with PayPal checkout.");
define("EASYSHOP_URL", "http://e107.webstartinternet.com");
define("EASYSHOP_CAPTION", "Configure EasyShop");
define("EASYSHOP_LINKNAME", "Online Shop");
define("EASYSHOP_DONE1", "Installation");
define("EASYSHOP_DONE2", "successfull...");
define("EASYSHOP_DONE3", "Thank you for upgrading to");

// module admin_menu.php
define("EASYSHOP_MENU_00", "EasyShop Options");
define("EASYSHOP_MENU_01", "Shop Inventory");
define("EASYSHOP_MENU_02", "Product Main Categories");
define("EASYSHOP_MENU_03", "Product Categories");
define("EASYSHOP_MENU_04", "General Preferences");
define("EASYSHOP_MENU_05", "Shop Monitor");
define("EASYSHOP_MENU_06", "Product Properties");
define("EASYSHOP_MENU_07", "Picture uploads");
define("EASYSHOP_MENU_08", "Check for updates");
define("EASYSHOP_MENU_09", "Readme.txt");
define("EASYSHOP_MENU_10", "Product Discounts");
define("EASYSHOP_MENU_11", "Download-products overview");
define("EASYSHOP_MENU_12", "IPN log overview");

// module admin_config.php
define("EASYSHOP_CONF_GEN_01", "Maintain Shop Inventory");

define("EASYSHOP_CONF_CAT_00", "Product Categories");
define("EASYSHOP_CONF_CAT_01", "No active product categories present in database.");
define("EASYSHOP_CONF_CAT_02", "Page"); // category page
define("EASYSHOP_CONF_CAT_03", "Click on a product category to view products.");
define("EASYSHOP_CONF_CAT_04", "Total products:");
define("EASYSHOP_CONF_CAT_05", "Inactive:");

define("EASYSHOP_CONF_ITM_00", "Add product");
define("EASYSHOP_CONF_ITM_01", "Please create a Product Category before entering products.");
define("EASYSHOP_CONF_ITM_02", "Number of product columns:");
define("EASYSHOP_CONF_ITM_03", "Products per page:");
define("EASYSHOP_CONF_ITM_04", "Apply changes");
define("EASYSHOP_CONF_ITM_05", "Product category");
define("EASYSHOP_CONF_ITM_06", "Product name");
define("EASYSHOP_CONF_ITM_07", "Product description");
define("EASYSHOP_CONF_ITM_08", "Product SKU Number");
define("EASYSHOP_CONF_ITM_09", "SKU = Stock Keeping Unit");
define("EASYSHOP_CONF_ITM_10", "Product price");
define("EASYSHOP_CONF_ITM_11", "Shipping cost for first product");
define("EASYSHOP_CONF_ITM_12", "Will only work if you have 'Override' checked in the 'Shipping Calculations' section of your Paypal profile.");
define("EASYSHOP_CONF_ITM_13", "Shipping cost for each additional product");
define("EASYSHOP_CONF_ITM_14", "Handling cost");
define("EASYSHOP_CONF_ITM_15", "Product image");
define("EASYSHOP_CONF_ITM_16", "(max. size: 200 x 250)");
define("EASYSHOP_CONF_ITM_17", "Enter the name of the image you would like to associate with this product. This image should be stored in the 'Store Image Path' folder defined in the <a href='admin_general_preferences.php'>'General Preferences'</a>. <br />You can also single click on a miniature icon to fill the name. Maximum size is indicative.");
define("EASYSHOP_CONF_ITM_18", "Active?");
define("EASYSHOP_CONF_ITM_19", "Out of stock");
define("EASYSHOP_CONF_ITM_20", "Sorting order");
define("EASYSHOP_CONF_ITM_21", "Out of stock explanation");
define("EASYSHOP_CONF_ITM_22", "Edit product");
define("EASYSHOP_CONF_ITM_23", "Delete product");
define("EASYSHOP_CONF_ITM_24", "Product inventory");
define("EASYSHOP_CONF_ITM_25", "No existing products in this Product Category. <br />Please create one or more products for this Product Category.");
define("EASYSHOP_CONF_ITM_26", "ALERT! There are no active products in this Product Category! Your webshop will display this as an empty category. <br /> Make at least one product active to actually display your products.");
define("EASYSHOP_CONF_ITM_27", "Actions");
define("EASYSHOP_CONF_ITM_28", "Property");
define("EASYSHOP_CONF_ITM_29", "Discount");
define("EASYSHOP_CONF_ITM_30", "Discount end date is passed!");
define("EASYSHOP_CONF_ITM_31", "Discount start date is in the future!");
define("EASYSHOP_CONF_ITM_32", "View product in shop front page");
define("EASYSHOP_CONF_ITM_33", "Track stock of this product");
define("EASYSHOP_CONF_ITM_34", "<b>Note:</b> IPN must be enabled for this option to function");
define("EASYSHOP_CONF_ITM_35", "Current number of this product in stock");
define("EASYSHOP_CONF_ITM_36", "Download product");
define("EASYSHOP_CONF_ITM_37", "Upload a file to download product folder");
define("EASYSHOP_CONF_ITM_38", "Upload");
define("EASYSHOP_CONF_ITM_39", "Select product download file");
define("EASYSHOP_CONF_ITM_40", "Selected download file");
define("EASYSHOP_CONF_ITM_41", "To remove selected download file; uncheck option 'Download product' above and click button 'Apply changes'");
define("EASYSHOP_CONF_ITM_42", "Stored secure as file");
define("EASYSHOP_CONF_ITM_43", "Upload an image to image folder");
define("EASYSHOP_CONF_ITM_44", "Total images");
define("EASYSHOP_CONF_ITM_45", "Promote user to class after payment");
define("EASYSHOP_CONF_ITM_46", "Auto promotion class");
define("EASYSHOP_CONF_ITM_47", "Minimum stock level alert");
define("EASYSHOP_CONF_ITM_56", "Display datasheet for this product"); // v1.7
define("EASYSHOP_CONF_ITM_57", "Upload a file to datasheet folder"); // v1.7
define("EASYSHOP_CONF_ITM_58", "Upload"); // v1.7
define("EASYSHOP_CONF_ITM_59", "Select product datasheet file"); // v1.7
define("EASYSHOP_CONF_ITM_60", "Selected datasheet file"); // v1.7
define("EASYSHOP_CONF_ITM_61", "To remove selected datasheet file; uncheck option 'Display datasheet for this product' above and click button 'Apply changes'"); // v1.7
define("EASYSHOP_CONF_ITM_62", "Quotation product"); // v1.7

// module admin_config_edit.php
define("EASYSHOP_CONFEDIT_ITM_00", "Delete product");
define("EASYSHOP_CONFEDIT_ITM_01", "Are you sure you want to delete this product?");
define("EASYSHOP_CONFEDIT_ITM_02", "Yes");
define("EASYSHOP_CONFEDIT_ITM_03", "No");
define("EASYSHOP_CONFEDIT_ITM_04", "Price invalid (2 decimals maximum).");
define("EASYSHOP_CONFEDIT_ITM_05", "Shipping cost for first product invalid (2 decimals maximum).");
define("EASYSHOP_CONFEDIT_ITM_06", "Shipping cost for each additional product invalid (2 decimals maximum).");
define("EASYSHOP_CONFEDIT_ITM_07", "Handling cost invalid (2 decimals maximum).");
define("EASYSHOP_CONFEDIT_ITM_08", "Back");
define("EASYSHOP_CONFEDIT_ITM_09", "Error");
define("EASYSHOP_CONFEDIT_ITM_10", "Item name is mandatory.");

// module admin_main_categories.php
define("EASYSHOP_MCAT_00", "Maintain Product Main Categories");
define("EASYSHOP_MCAT_01", "Existing Product Main Categories");
define("EASYSHOP_MCAT_02", "No product main categories present in database.");
define("EASYSHOP_MCAT_03", "Create Product Main Category");
define("EASYSHOP_MCAT_04", "Product main category name");
define("EASYSHOP_MCAT_05", "Product main category description");
define("EASYSHOP_MCAT_06", "Product main category image");
define("EASYSHOP_MCAT_07", "Enter the name of the image you would like to associate with this main category. This image should be stored in the 'Store image path' folder defined in the <a href='admin_general_preferences.php'>'General Preferences'</a>. <br />You can also single click on a miniature icon to fill the name. Maximum size is indicative.");
define("EASYSHOP_MCAT_08", "(max. size 80 x 100)");
define("EASYSHOP_MCAT_09", "Create product main category");
define("EASYSHOP_MCAT_10", "Product main category presentation");
define("EASYSHOP_MCAT_11", "Product main category columns");
define("EASYSHOP_MCAT_12", "Main categories per page");
define("EASYSHOP_MCAT_13", "Apply changes");
define("EASYSHOP_MCAT_14", "Sorting order");
define("EASYSHOP_MCAT_15", "Active?");
define("EASYSHOP_MCAT_16", "Edit product main category");
define("EASYSHOP_MCAT_17", "Delete product main category");
define("EASYSHOP_MCAT_18", "Edit Product Main Category");
define("EASYSHOP_MCAT_19", "Actions");
define("EASYSHOP_MCAT_20", "ALERT! There are no active Product Main Categories! Product Main Categories are optional; your webshop will just start at level Categories.");
define("EASYSHOP_MCAT_21", "# Products");
define("EASYSHOP_MCAT_22", "# Categories");

// module admin_main_categories_edit.php
define("EASYSHOP_MCATEDIT_01", "Delete Product Main Category");
define("EASYSHOP_MCATEDIT_02", "Are you sure you want to delete this Product Main Category?");
define("EASYSHOP_MCATEDIT_03", "Yes");
define("EASYSHOP_MCATEDIT_04", "No");

// module admin_categories.php
define("EASYSHOP_CAT_00", "Maintain Product Categories");
define("EASYSHOP_CAT_01", "Existing Product Categories");
define("EASYSHOP_CAT_02", "No product categories present in database.");
define("EASYSHOP_CAT_03", "Create Product Category");
define("EASYSHOP_CAT_04", "Product category name");
define("EASYSHOP_CAT_05", "Product category description");
define("EASYSHOP_CAT_06", "Product category image");
define("EASYSHOP_CAT_07", "Enter the name of the image you would like to associate with this category. This image should be stored in the 'Store image path' folder defined in the <a href='admin_general_preferences.php'>'General Preferences'</a>. <br />You can also single click on a miniature icon to fill the name. Maximum size is indicative.");
define("EASYSHOP_CAT_08", "(max. size 80 x 100)");
define("EASYSHOP_CAT_09", "Create product category");
define("EASYSHOP_CAT_10", "Product category presentation");
define("EASYSHOP_CAT_11", "Product category columns");
define("EASYSHOP_CAT_12", "Categories per page");
define("EASYSHOP_CAT_13", "Apply changes");
define("EASYSHOP_CAT_14", "Sorting order");
define("EASYSHOP_CAT_15", "Active?");
define("EASYSHOP_CAT_16", "Edit product category");
define("EASYSHOP_CAT_17", "Delete product category");
define("EASYSHOP_CAT_18", "Edit Product Category");
define("EASYSHOP_CAT_19", "Actions");
define("EASYSHOP_CAT_20", "ALERT! There are no active Product Categories! Your webshop will be displayed empty. <br /> Make at least one category active to actually display your products.");
define("EASYSHOP_CAT_21", "# Products");
define("EASYSHOP_CAT_22", "Main Category");
define("EASYSHOP_CAT_23", "Visible for class");
define("EASYSHOP_CAT_24", "Class");
define("EASYSHOP_CAT_25", "Shopping class");

// module admin_categories_edit.php
define("EASYSHOP_CATEDIT_01", "Delete Product Category");
define("EASYSHOP_CATEDIT_02", "Are you sure you want to delete this Product Category?");
define("EASYSHOP_CATEDIT_03", "Yes");
define("EASYSHOP_CATEDIT_04", "No");

// module admin_general_preferences.php
define("EASYSHOP_GENPREF_00", "General Preferences");
define("EASYSHOP_GENPREF_01", "Contact info");
define("EASYSHOP_GENPREF_02", "Shop name:");
define("EASYSHOP_GENPREF_03", "Address line 1:");
define("EASYSHOP_GENPREF_04", "Address line 2:");
define("EASYSHOP_GENPREF_05", "City:");
define("EASYSHOP_GENPREF_06", "State:");
define("EASYSHOP_GENPREF_07", "Zip code:");
define("EASYSHOP_GENPREF_08", "Country:");
define("EASYSHOP_GENPREF_09", "Support e-mail address:");
define("EASYSHOP_GENPREF_10", "Shop welcome message:");
define("EASYSHOP_GENPREF_11", "In this message you can welcome your EasyShop customers and inform them about e.g. sales conditions or current promotions.");
define("EASYSHOP_GENPREF_12", "Image path: ..plugins/easyshop/");
define("EASYSHOP_GENPREF_13", "Path to the image folder under EasyShop plugin folder that holds all of your product (categories) images. Make sure you use a trailing forward slash.");
define("EASYSHOP_GENPREF_14", "PayPal info");
define("EASYSHOP_GENPREF_15", "PayPal e-mail address:");
define("EASYSHOP_GENPREF_16", "PayPal currency:");
define("EASYSHOP_GENPREF_17", "US Dollar"); // Dollar-sign = &#36;
define("EASYSHOP_GENPREF_18", "Canadian Dollar"); // Can Dollar-sign = C&#36;
define("EASYSHOP_GENPREF_19", "Euro"); // Euro-sign = &#8364;
define("EASYSHOP_GENPREF_20", "Pound Sterling"); // Pound-sign = &#163;
define("EASYSHOP_GENPREF_21", "Thank you page title:");
define("EASYSHOP_GENPREF_22", "Thank you page text:");
define("EASYSHOP_GENPREF_23", "Text shown to your EasyShop customers after finalizing their PayPal transaction.");
define("EASYSHOP_GENPREF_24", "Payment PayPal page style:");
define("EASYSHOP_GENPREF_25", "Enter the name of the 'Custom Payment Page' of your PayPal profile.");
define("EASYSHOP_GENPREF_26", "Enable test mode:");
define("EASYSHOP_GENPREF_27", "This will direct all transactions to the 'Paypal Sandbox'. A PayPal Developer Account is obligatory.");
define("EASYSHOP_GENPREF_28", "Apply changes");
define("EASYSHOP_GENPREF_29", "Japanese Yen"); // Yen-sign = &#165;
define("EASYSHOP_GENPREF_30", "Australian Dollar"); // Aus Dollar-sign = &#36;AU
define("EASYSHOP_GENPREF_31", "Swiss Franc"); // Swiss Franc-sign = SFr.
define("EASYSHOP_GENPREF_32", "Czech Koruna"); // Czech Koruna-sign = K&#10d;
define("EASYSHOP_GENPREF_33", "Danish Krone"); // Danish Krone-sign = Dkr.
define("EASYSHOP_GENPREF_34", "Hong Kong Dollar"); // Hong Kong Dollar-sign = HK&#36;
define("EASYSHOP_GENPREF_35", "Hungarian Forint"); // Hungarian Forint-sign = Ft
define("EASYSHOP_GENPREF_36", "Norwegian Krone"); // Norwegian Krone-sign = Nkr.
define("EASYSHOP_GENPREF_37", "New Zealand Dollar"); // New Zealand Dollar-sign = NZ&#36;
define("EASYSHOP_GENPREF_38", "Polish Zloty"); // Polish Zloty-sign = P&#142;
define("EASYSHOP_GENPREF_39", "Swedish Krona"); // Swedish Krona-sign = Skr.
define("EASYSHOP_GENPREF_40", "Singapore Dollar"); // Singapore Dollar-sign = S&#36;
define("EASYSHOP_GENPREF_41", "Cancel page title:");
define("EASYSHOP_GENPREF_42", "Cancel page text:");
define("EASYSHOP_GENPREF_43", "Text shown to your EasyShop customers after cancelling their PayPal transaction.");
define("EASYSHOP_GENPREF_44", "Settings");
define("EASYSHOP_GENPREF_45", "Set currency sign behind amount");
define("EASYSHOP_GENPREF_46", "No = currency sign before amount");
define("EASYSHOP_GENPREF_47", "Yes = currency sign behind amount");
define("EASYSHOP_GENPREF_48", "No");
define("EASYSHOP_GENPREF_49", "Yes");
define("EASYSHOP_GENPREF_50", "Minimum order amount");
define("EASYSHOP_GENPREF_51", "If not filled in the minimum order amount equals 0.00 (default)");
define("EASYSHOP_GENPREF_52", "Customers can not proceed to checkout if they didn't reach the minimum order amount.");
define("EASYSHOP_GENPREF_53", "Always show checkout button");
define("EASYSHOP_GENPREF_54", "Otherwise at least one product or enough orders to reach the minimum amount must be in the basket");
define("EASYSHOP_GENPREF_55", "Page devide character");
define("EASYSHOP_GENPREF_56", "Character '&raquo;' is used by default if nothing is filled in.");
define("EASYSHOP_GENPREF_57", "Icon size");
define("EASYSHOP_GENPREF_58", "Size of selectable icons by admin at maintenance screens of (main) categories and products.");
define("EASYSHOP_GENPREF_59", "Default is '16' if field is left empty or smaller than 1.");
define("EASYSHOP_GENPREF_60", "Enable product comments");
define("EASYSHOP_GENPREF_61", "Show shopping bag image in easyshop_menu");
define("EASYSHOP_GENPREF_62", "Display shop address");
define("EASYSHOP_GENPREF_63", "Print shop text");
define("EASYSHOP_GENPREF_64", "Top");
define("EASYSHOP_GENPREF_65", "Bottom");
define("EASYSHOP_GENPREF_66", "Shopping bag color");
define("EASYSHOP_GENPREF_67", "Blue");
define("EASYSHOP_GENPREF_68", "Green");
define("EASYSHOP_GENPREF_69", "Override PayPal form with e-mail");
define("EASYSHOP_GENPREF_70", "Print special discount icons");
define("EASYSHOP_GENPREF_71", "5%, 10%, 20%, 50% and at special prices");
define("EASYSHOP_GENPREF_72", "Enable Paypal IPN");
define("EASYSHOP_GENPREF_73", "IMPORTANT - Before enabling IPN in EasyShop you have to setup Paypal:");
define("EASYSHOP_GENPREF_74", "1. Login at your PayPal account and go to My account - Profile - Instant Payment Notification Preferences");
define("EASYSHOP_GENPREF_75", "2. ensure 'IPN' is on and place your root website URL in the 'IPN' URL box");
define("EASYSHOP_GENPREF_76", "3. Click on Save.");
define("EASYSHOP_GENPREF_77", "<i>Note:</i> Paypal IPN will only work on a public server - it will not work on a 'Localhost'");
define("EASYSHOP_GENPREF_78", "Enable user input of number of products");
define("EASYSHOP_GENPREF_79", "By enabling this option shop visitors can enter the number of ordered products, instead of adding 1 at the time.");
define("EASYSHOP_GENPREF_80", "Enable special instructions in shopping basket");
define("EASYSHOP_GENPREF_81", "Visitors are able to supply special instructions that are mailed to shop owner.");
define("EASYSHOP_GENPREF_82", "Only possible when e-mail override is activated.");
define("EASYSHOP_GENPREF_83", "Orange");
define("EASYSHOP_GENPREF_84", "Red");
define("EASYSHOP_GENPREF_85", "Yellow");
define("EASYSHOP_GENPREF_86", "White");
define("EASYSHOP_GENPREF_87", "Black");
define("EASYSHOP_GENPREF_88", "Information level");
define("EASYSHOP_GENPREF_89", "Login or leave e-mail");
define("EASYSHOP_GENPREF_90", "Leave e-mail and address");
define("EASYSHOP_GENPREF_91", "Login or Leave e-mail and address");
define("EASYSHOP_GENPREF_92", "Login required");
define("EASYSHOP_GENPREF_93", "Additional text in e-mail");
define("EASYSHOP_GENPREF_94", "IPN Monitor");
define("EASYSHOP_GENPREF_95", "Monitor clean shop days");
define("EASYSHOP_GENPREF_96", "Monitor clean check days");
define("EASYSHOP_GENPREF_97", "Presentation");
define("EASYSHOP_GENPREF_98", "Product main category columns");
define("EASYSHOP_GENPREF_99", "Main categories per page");

// module admin_properties.php
define("EASYSHOP_ADMIN_PROP_00", "EasyShop Properties");
define("EASYSHOP_ADMIN_PROP_01", "Properties Overview");
define("EASYSHOP_ADMIN_PROP_02", "No properties available.");
define("EASYSHOP_ADMIN_PROP_03", "Create Property");
define("EASYSHOP_ADMIN_PROP_04", "Property name");
define("EASYSHOP_ADMIN_PROP_05", "Property list");
define("EASYSHOP_ADMIN_PROP_06", "Separate choices with comma, no space<br/>e.g. S,M,L,XL,XXL");
define("EASYSHOP_ADMIN_PROP_07", "Create property");
define("EASYSHOP_ADMIN_PROP_08", "Property information saved");
define("EASYSHOP_ADMIN_PROP_09", "Actions");
define("EASYSHOP_ADMIN_PROP_10", "Edit");
define("EASYSHOP_ADMIN_PROP_11", "Delete");
define("EASYSHOP_ADMIN_PROP_12", "Maintain property");
define("EASYSHOP_ADMIN_PROP_13", "Update property");
define("EASYSHOP_ADMIN_PROP_14", "Delete Property");
define("EASYSHOP_ADMIN_PROP_15", "Are you sure you want to delete this property?");
define("EASYSHOP_ADMIN_PROP_16", "Yes");
define("EASYSHOP_ADMIN_PROP_17", "No");
define("EASYSHOP_ADMIN_PROP_18", "To delete an existing entry just make it blank");
define("EASYSHOP_ADMIN_PROP_19", "Price delta: e.g. +1 adds one to the product price<br/>-1 subtracts one off the product price.");
define("EASYSHOP_ADMIN_PROP_20", "Price delta");
define("EASYSHOP_ADMIN_PROP_21", "Cancel");
define("EASYSHOP_ADMIN_PROP_22", "Price delta invalid (2 decimals maximum).");
define("EASYSHOP_ADMIN_PROP_23", "Back");
define("EASYSHOP_ADMIN_PROP_24", "Error");
define("EASYSHOP_ADMIN_PROP_25", "Property name must be specified.");

// module admin_discounts.php
define("EASYSHOP_ADMIN_DISC_00", "EasyShop Discounts");
define("EASYSHOP_ADMIN_DISC_01", "Discounts Overview");
define("EASYSHOP_ADMIN_DISC_02", "No discounts available.");
define("EASYSHOP_ADMIN_DISC_03", "Create Discount");
define("EASYSHOP_ADMIN_DISC_04", "Discount name");
define("EASYSHOP_ADMIN_DISC_05", "Discount class");
define("EASYSHOP_ADMIN_DISC_06", "Discount method");
define("EASYSHOP_ADMIN_DISC_07", "Discount price/percentage");
define("EASYSHOP_ADMIN_DISC_08A", "Price");
define("EASYSHOP_ADMIN_DISC_08B", "Percentage");
define("EASYSHOP_ADMIN_DISC_09", "Valid from (yyyy/mm/dd)");
define("EASYSHOP_ADMIN_DISC_10", "Valid until (yyyy/mm/dd)");
define("EASYSHOP_ADMIN_DISC_11", "Discount code");
define("EASYSHOP_ADMIN_DISC_12", "Create discount");
define("EASYSHOP_ADMIN_DISC_13", "Discount information saved");
define("EASYSHOP_ADMIN_DISC_14", "Actions");
define("EASYSHOP_ADMIN_DISC_15", "Edit");
define("EASYSHOP_ADMIN_DISC_16", "Delete");
define("EASYSHOP_ADMIN_DISC_17", "Maintain discount");
define("EASYSHOP_ADMIN_DISC_18", "Update discount");
define("EASYSHOP_ADMIN_DISC_19", "Delete discount");
define("EASYSHOP_ADMIN_DISC_20", "Are you sure you want to delete this discount?");
define("EASYSHOP_ADMIN_DISC_21", "Yes");
define("EASYSHOP_ADMIN_DISC_22", "No");
define("EASYSHOP_ADMIN_DISC_23", "To delete an existing entry just make it blank");
define("EASYSHOP_ADMIN_DISC_24", "Discount percentage of more than 100% not allowed!");
define("EASYSHOP_ADMIN_DISC_25", "Negative discount value not allowed; <br/>the discount amount will be subtracted from the product price automatically.");
define("EASYSHOP_ADMIN_DISC_26", "Cancel");
define("EASYSHOP_ADMIN_DISC_27", "Price delta invalid (2 decimals maximum).");
define("EASYSHOP_ADMIN_DISC_28", "Back");
define("EASYSHOP_ADMIN_DISC_29", "Error");
define("EASYSHOP_ADMIN_DISC_30", "Discount name must be specified.");
define("EASYSHOP_ADMIN_DISC_31", "[select date]");
define("EASYSHOP_ADMIN_DISC_32", "Discount");
define("EASYSHOP_ADMIN_DISC_33", "From");
define("EASYSHOP_ADMIN_DISC_34", "Until");
define("EASYSHOP_ADMIN_DISC_35", "Random discount code suggestion:");
define("EASYSHOP_ADMIN_DISC_36", "Date valid from must be filled in.");
define("EASYSHOP_ADMIN_DISC_37", "Date valid until must be filled in.");
define("EASYSHOP_ADMIN_DISC_38", "Date valid until may not be before date valid from.");

// module help.php
define("EASYSHOP_ADMIN_HELP_00", "EasyShop Help");
define("EASYSHOP_ADMIN_HELP_01", "Shop Inventory");
define("EASYSHOP_ADMIN_HELP_02", "Add or modify your products within the Shop Inventory. The existence of at least one <i><b>active</b></i> product category is mandatory. Each product has an optional product image. Define various price information per product.");
define("EASYSHOP_ADMIN_HELP_03", "Product Categories");
define("EASYSHOP_ADMIN_HELP_04", "Add or modify your product categories. It is possible to define a product category image. Change number of columns for the product category presentation. <br />By default new product categories and new products are not active; you have to set them to active before they appear in your EasyShop web page.");
define("EASYSHOP_ADMIN_HELP_05", "General Preferences");
define("EASYSHOP_ADMIN_HELP_06", "Manage your general shop data, other settings and PayPal link information.");
define("EASYSHOP_ADMIN_HELP_07", "Shop Monitor");
define("EASYSHOP_ADMIN_HELP_08", "Quick summary view of your shop.");
define("EASYSHOP_ADMIN_HELP_97", "ReadMe.txt"); // also used in admin_readme.php
define("EASYSHOP_ADMIN_HELP_98", "View the text file to read detailed release information and version history.");
define("EASYSHOP_ADMIN_HELP_99A", "All the help you need");
define("EASYSHOP_ADMIN_HELP_99B", "EasyShop Manual");

// module easyshop.php
define("EASYSHOP_SHOP_00", "Online Shop");
define("EASYSHOP_SHOP_01", "Address:");
define("EASYSHOP_SHOP_02", "Support:");
define("EASYSHOP_SHOP_03", "Categories");
define("EASYSHOP_SHOP_04", "No categories available.");
define("EASYSHOP_SHOP_05", "Page");
define("EASYSHOP_SHOP_06", "No products available.");
define("EASYSHOP_SHOP_07", "Out Of Stock");
define("EASYSHOP_SHOP_08", "Add to cart");
define("EASYSHOP_SHOP_09", "Go to checkout");
define("EASYSHOP_SHOP_10", "Price");
define("EASYSHOP_SHOP_11", "[Product Details]");
define("EASYSHOP_SHOP_12", "Shipping costs first product");
define("EASYSHOP_SHOP_13", "Shipping costs additional products");
define("EASYSHOP_SHOP_14", "Handling costs");
define("EASYSHOP_SHOP_15", "Invalid item_id. Probably the EasyShop database is corrupt.");
define("EASYSHOP_SHOP_16", "Total products:");
define("EASYSHOP_SHOP_17", "Variety of products:");
define("EASYSHOP_SHOP_18", "Total price:");
define("EASYSHOP_SHOP_19", "Average price per product:");
define("EASYSHOP_SHOP_20", "Shipping &amp; handling costs:");
define("EASYSHOP_SHOP_21", "Item number");
define("EASYSHOP_SHOP_22", "Item name");
define("EASYSHOP_SHOP_23", "Item price");
define("EASYSHOP_SHOP_24", "Quantity");
define("EASYSHOP_SHOP_25", "Shipping costs");
define("EASYSHOP_SHOP_26", "Additional shipping costs");
define("EASYSHOP_SHOP_27", "Handling costs");
define("EASYSHOP_SHOP_28", "Actions");
define("EASYSHOP_SHOP_29", "Delete");
define("EASYSHOP_SHOP_30", "Reset basket");
define("EASYSHOP_SHOP_31", "Continue shopping");
define("EASYSHOP_SHOP_32", "Shopping cart");
define("EASYSHOP_SHOP_33", "Add");
define("EASYSHOP_SHOP_34", "Subtract");
define("EASYSHOP_SHOP_35", "Manage your basket");
define("EASYSHOP_SHOP_36", "Minimum amount to spent");
define("EASYSHOP_SHOP_37", "You have to order extra");
define("EASYSHOP_SHOP_38", "Be the first to comment this product!");
define("EASYSHOP_SHOP_39", "Total comments");
define("EASYSHOP_SHOP_40", "Main Categories");
define("EASYSHOP_SHOP_41", "No main categories available.");
define("EASYSHOP_SHOP_42", "Main category was not found.");
define("EASYSHOP_SHOP_43", "products");
define("EASYSHOP_SHOP_44", "product");
define("EASYSHOP_SHOP_45", "in this category");
define("EASYSHOP_SHOP_46", "Categories without Main Category");
define("EASYSHOP_SHOP_47", "= e-mail =");
define("EASYSHOP_SHOP_48", "Class restricted access only");
define("EASYSHOP_SHOP_49", "You do not belong to a class that has access to this category");
define("EASYSHOP_SHOP_50", "Promotional discount code");
define("EASYSHOP_SHOP_51", "From");
define("EASYSHOP_SHOP_52", "for");
define("EASYSHOP_SHOP_53", "Discount");
define("EASYSHOP_SHOP_54", "Class specific category");
define("EASYSHOP_SHOP_55", "Order e-mail failed");
define("EASYSHOP_SHOP_56", "Order e-mail succeeded");
define("EASYSHOP_SHOP_57", "Please fill in all fields correctly.");
define("EASYSHOP_SHOP_58", "At"); // text in e-mail, followed by date
define("EASYSHOP_SHOP_59", "you ordered the following:");
define("EASYSHOP_SHOP_60", "Error");  // e-mail error
define("EASYSHOP_SHOP_61", "Mail results");
define("EASYSHOP_SHOP_62", "order");
define("EASYSHOP_SHOP_63", "Order e-mail to admin failed");
define("EASYSHOP_SHOP_64", "Following order confirmation e-mail has been send to:");
define("EASYSHOP_SHOP_65", "You are currently not logged in!");
define("EASYSHOP_SHOP_66", "In order to send you a confirmation e-mail of your order, we need your e-mail address.");
define("EASYSHOP_SHOP_67", "Please consider one of the following possibilities:");
define("EASYSHOP_SHOP_68", "If you are a regular visitor, or intend to come back more often:");
define("EASYSHOP_SHOP_69", "You already have a user account on this website?");
define("EASYSHOP_SHOP_70", "Login");
define("EASYSHOP_SHOP_71", "No user yet?");
define("EASYSHOP_SHOP_72", "Sign up");
define("EASYSHOP_SHOP_73", "Otherwise, provide your name and e-mail address right here:");
define("EASYSHOP_SHOP_74", "Name");
define("EASYSHOP_SHOP_75", "(Minimum of 4 characters required)");
define("EASYSHOP_SHOP_76", "E-mail");
define("EASYSHOP_SHOP_77", "Continue sending my order");
define("EASYSHOP_SHOP_78", "Your contact information");
define("EASYSHOP_SHOP_79", "Mail to admin");
define("EASYSHOP_SHOP_80", "Number of products");
define("EASYSHOP_SHOP_81", "Send from IP address");
define("EASYSHOP_SHOP_82", "Special instructions for seller");
define("EASYSHOP_SHOP_83", "Any instructions you fill in here will be added to your order");
define("EASYSHOP_SHOP_84", "View more images");
define("EASYSHOP_SHOP_85", "Please provide your name, e-mail account and address:");
define("EASYSHOP_SHOP_86", "Address line 1");
define("EASYSHOP_SHOP_87", "Address line 2");
define("EASYSHOP_SHOP_88", "Zip code");
define("EASYSHOP_SHOP_89", "City");
define("EASYSHOP_SHOP_90", "Telephone");
define("EASYSHOP_SHOP_91", "Mobile");
define("EASYSHOP_SHOP_92", "Fields marked with * are mandatory.");
define("EASYSHOP_SHOP_93", "Logged in user display name");
define("EASYSHOP_SHOP_94", "quotation"); // v1.7
define("EASYSHOP_SHOP_95", "you requested a quotation about the following:"); // v1.7
define("EASYSHOP_SHOP_96", "In order to send you a quotation e-mail of the product you are interested in, we need your e-mail address."); // v1.7
define("EASYSHOP_SHOP_97", "Get quotation"); // v1.7
define("EASYSHOP_SHOP_98", "Download datasheet"); // v1.7

// module easyshop_class.php
define("EASYSHOP_CLASS_01", "Go to checkout");
define("EASYSHOP_CLASS_02", "Failed to copy");
define("EASYSHOP_CLASS_03", "Download product");
define("EASYSHOP_CLASS_04", "Downloadable product purchase");
define("EASYSHOP_CLASS_05", "Sending downloadable product failed.");
define("EASYSHOP_CLASS_06", "EASYSHOP ALERT: almost out of");
define("EASYSHOP_CLASS_07", "EASYSHOP ALERT: sold too much of");
define("EASYSHOP_CLASS_08", "You are almost out of product");
define("EASYSHOP_CLASS_09", "Minimum level");
define("EASYSHOP_CLASS_10", "New stock level");
define("EASYSHOP_CLASS_11", "Last buyer purchased more than actual in stock of");
define("EASYSHOP_CLASS_12", "EASYSHOP ALERT: out of");
define("EASYSHOP_CLASS_13", "Status has been switched to 'out of stock' for product");

// module track_checkout.php
define("EASYSHOP_TRACK_01", "SKU number");
define("EASYSHOP_TRACK_02", "Product name");
define("EASYSHOP_TRACK_03", "Product price");
define("EASYSHOP_TRACK_04", "Quantity");
define("EASYSHOP_TRACK_05", "Shipping costs");
define("EASYSHOP_TRACK_06", "Additional shipping costs");
define("EASYSHOP_TRACK_07", "Handling costs");
define("EASYSHOP_TRACK_08", "Confirm order");
define("EASYSHOP_TRACK_09", "Total products:");
define("EASYSHOP_TRACK_10", "Variety of products:");
define("EASYSHOP_TRACK_11", "Total price:");
define("EASYSHOP_TRACK_12", "Average price per product:");
define("EASYSHOP_TRACK_13", "Shipping &amp; handling costs:");
define("EASYSHOP_TRACK_14", "Confirm order");
define("EASYSHOP_TRACK_15", "Continue shopping");

// module thank_you.php
define("EASYSHOP_THANKS_00", "Thank you"); // not in use
define("EASYSHOP_THANKS_01", "Return to the online Shop.");
define("EASYSHOP_THANKS_02", "Return to the home page.");

// module easyshop_menu.php
define("EASYSHOP_PUBLICMENU_01", "Featured product");
define("EASYSHOP_PUBLICMENU_02", "Your shopping cart contains:");
define("EASYSHOP_PUBLICMENU_03", "Total products:");
define("EASYSHOP_PUBLICMENU_04", "Variety of products:");
define("EASYSHOP_PUBLICMENU_05", "Total price:");
define("EASYSHOP_PUBLICMENU_06", "Average price per product:");
define("EASYSHOP_PUBLICMENU_07", "Shipping &amp; handling costs:");
define("EASYSHOP_PUBLICMENU_08", "Manage your basket");
define("EASYSHOP_PUBLICMENU_09", "Price");
define("EASYSHOP_PUBLICMENU_10", "You do not have access to any category");

// module easyshop_list_menu.php
define("EASYSHOP_PUBLICMENU2_01", "Product Categories");

// module easyshop_specials_menu.php
define("EASYSHOP_PUBLICMENU3_01", "Specials");
define("EASYSHOP_PUBLICMENU3_10", "No specials could be found.");
define("EASYSHOP_PUBLICMENU3_11", "Discount valid till");

// module easyshop_latest_menu.php
define("EASYSHOP_PUBLICMENU4_01", "Latest product");
define("EASYSHOP_PUBLICMENU4_10", "No latest product could be found.");
define("EASYSHOP_PUBLICMENU4_11", "Discount valid till");

// module easyshop_basket.php
define("EASYSHOP_BASKET_00", "Basket");
define("EASYSHOP_BASKET_01", "not filled"); // Used in: <Property name> not filled.
define("EASYSHOP_BASKET_02", "Back to shop");
define("EASYSHOP_BASKET_03", "Error");
define("EASYSHOP_BASKET_04", "Discount");

// module easyshop_monitor.php
define("EASYSHOP_MONITOR_00", "EasyShop monitor");
define("EASYSHOP_MONITOR_01", "Inventory overview");
define("EASYSHOP_MONITOR_02", "Active products");
define("EASYSHOP_MONITOR_03", "Warning: currently there are no products in the database.");
define("EASYSHOP_MONITOR_04", "Inactive products");
define("EASYSHOP_MONITOR_05", "Active Product Categories");
define("EASYSHOP_MONITOR_06", "Inactive Product Categories");
// for future use (not used in EasyShop 1.3)
define("EASYSHOP_MONITOR_07", "Order info");
define("EASYSHOP_MONITOR_08", "Orders waiting for approval");
define("EASYSHOP_MONITOR_09", "Total completed orders");
define("EASYSHOP_MONITOR_10", "Total offline orders");
define("EASYSHOP_MONITOR_11", "Total orders");
define("EASYSHOP_MONITOR_12", "Total online orders");
// end of future use
define("EASYSHOP_MONITOR_13", "Active Product Main Categories");
define("EASYSHOP_MONITOR_14", "Inactive Product Main Categories");
define("EASYSHOP_MONITOR_15", "Out of stock products");
define("EASYSHOP_MONITOR_16", "Active Product Categories without Main Category");
define("EASYSHOP_MONITOR_17", "of which active products with discount");
define("EASYSHOP_MONITOR_18", "None");
define("EASYSHOP_MONITOR_19", "of which active products with one or more properties");
define("EASYSHOP_MONITOR_20", "Total number of images in folder");
define("EASYSHOP_MONITOR_21", "EScheck entries deleted successfully");
define("EASYSHOP_MONITOR_22", "There was a problem deleting EScheck entries");
define("EASYSHOP_MONITOR_23", "There are no EScheck entries to delete ");
define("EASYSHOP_MONITOR_24", "ES_shopping entries deleted successfully");
define("EASYSHOP_MONITOR_25", "No ES_shopping entries older than"); // followed by # days and term 26
define("EASYSHOP_MONITOR_26", "days to delete");
define("EASYSHOP_MONITOR_27", "There are no ES_shopping entries to delete");
define("EASYSHOP_MONITOR_28", "ES_processing entries deleted successfully");
define("EASYSHOP_MONITOR_29", "No ES_processing entries older than"); // followed by # days and term 26
define("EASYSHOP_MONITOR_30", "There are no ES_processing entries to delete");
define("EASYSHOP_MONITOR_31", "'Completed Transactions' Report");
define("EASYSHOP_MONITOR_32", "'Transactions being processed' Report");
define("EASYSHOP_MONITOR_33", "'Current Shoppers' Report");
define("EASYSHOP_MONITOR_34", "'Accounts requiring attention'");
define("EASYSHOP_MONITOR_35", "'Transactions failing the Totals check' - probably Fraudulent");
define("EASYSHOP_MONITOR_36", "'Transactions failing the Easyshop Email check' - could be a double entry error or possibly Fraudulent");
define("EASYSHOP_MONITOR_37", "'Transactions failing the Paypal check' - probably Fraudulent");
define("EASYSHOP_MONITOR_38", "Delete Shopping/Processing transactions >"); // followed by # days and term 39
define("EASYSHOP_MONITOR_39", "days old");
define("EASYSHOP_MONITOR_40", "Delete all ES check transactions >"); // followed by # days and term 39
define("EASYSHOP_MONITOR_41", "Total number of files in folder downloads/");

// module admin_logviewer.php
define("EASYSHOP_LOG_00", "EasyShop IPN log viewer");
define("EASYSHOP_LOG_01", "Cannot open file");
define("EASYSHOP_LOG_02", "The file");
define("EASYSHOP_LOG_03", "is not writable");
define("EASYSHOP_LOG_04", "Back");
define("EASYSHOP_LOG_05", "Error during clearing IPN log file");
define("EASYSHOP_LOG_06", "Clear IPN log file");

// module admin_check_update.php
define("EASYSHOP_CHECK_00", "Check EasyShop update");
define("EASYSHOP_CHECK_01", "Current EasyShop version");
define("EASYSHOP_CHECK_02", "Latest EasyShop version");
define("EASYSHOP_CHECK_03", "Unable to retrieve versions properly");
define("EASYSHOP_CHECK_04", "There is an update for EasyShop available!");
define("EASYSHOP_CHECK_05", "Check the website");
define("EASYSHOP_CHECK_06", "Your current version is higher than latest version! Impossible!");
define("EASYSHOP_CHECK_07", "You are using the latest version.");

// module admin_upload.php and admin_overview.php
define("EASYSHOP_UPLOAD_12", "file");
define("EASYSHOP_UPLOAD_13", "files");
define("EASYSHOP_UPLOAD_14", "directory");
define("EASYSHOP_UPLOAD_15", "directories");
define("EASYSHOP_UPLOAD_16", "Root directory");
define("EASYSHOP_UPLOAD_17", "Name");
define("EASYSHOP_UPLOAD_18", "Size");
define("EASYSHOP_UPLOAD_19", "Last Modified");
define("EASYSHOP_UPLOAD_21", "Upload file to this dir");
define("EASYSHOP_UPLOAD_22", "Upload");
define("EASYSHOP_UPLOAD_26", "Deleted");
define("EASYSHOP_UPLOAD_27", "successfully");
define("EASYSHOP_UPLOAD_28", "Unable to delete");
define("EASYSHOP_UPLOAD_29", "Path");
define("EASYSHOP_UPLOAD_30", "Up level");
define("EASYSHOP_UPLOAD_31", "folder");
define("EASYSHOP_UPLOAD_32", "Select Directory");
//define("EASYSHOP_UPLOAD_38", "Successfully moved file to");
//define("EASYSHOP_UPLOAD_39", "Unable to move file to");
define("EASYSHOP_UPLOAD_43", "Delete selected files");
define("EASYSHOP_UPLOAD_46", "Please confirm that you wish to DELETE the selected files.");
define("EASYSHOP_UPLOAD_47", "User Uploads");
//define("EASYSHOP_UPLOAD_48", "Move selected to");
//define("EASYSHOP_UPLOAD_49", "Please confirm you wish to move the selected files.");
//define("EASYSHOP_UPLOAD_50", "Move");
define("EASYSHOP_UPLOAD_51", "Downloadable file linked to product");

// module includes/ipn_functions.php
define("EASYSHOP_IPN_01", "Name");
define("EASYSHOP_IPN_02", "Address");
define("EASYSHOP_IPN_03", "E-mail");
define("EASYSHOP_IPN_04", "PayPal transaction information");
define("EASYSHOP_IPN_05", "Payment status");
define("EASYSHOP_IPN_06", "Reason code");
define("EASYSHOP_IPN_07", "Pending reason");
define("EASYSHOP_IPN_08", "Txn id");
define("EASYSHOP_IPN_09", "Session id");
define("EASYSHOP_IPN_10", "Paypal date");
define("EASYSHOP_IPN_11", "Easyshop date");
define("EASYSHOP_IPN_12", "Total Amount");
define("EASYSHOP_IPN_13", "Item");
define("EASYSHOP_IPN_14", "Description");
define("EASYSHOP_IPN_15", "Number");
define("EASYSHOP_IPN_16", "Ship &amp; handling");
define("EASYSHOP_IPN_17", "Quantity");
define("EASYSHOP_IPN_18", "Total");
define("EASYSHOP_IPN_19", "Report");
define("EASYSHOP_IPN_20", "list number");
define("EASYSHOP_IPN_21a", "The available stock for"); // followed by product name
define("EASYSHOP_IPN_21b", "is currently"); // followed by stock amount
define("EASYSHOP_IPN_22", "Your cart has been updated.");
define("EASYSHOP_IPN_23", "Shipping costs for"); // followed by product name
define("EASYSHOP_IPN_24", "Shipping costs 'additional item' for "); // followed by product name
define("EASYSHOP_IPN_25", "Handling charges for "); // followed by product name
define("EASYSHOP_IPN_26", "is currently out of stock."); // preceeded by product name
define("EASYSHOP_IPN_27", "has been made inactive."); // preceeded by product name
define("EASYSHOP_IPN_PRICEFROM", "has had a price change from"); // preceeded by product name, followed by original price
define("EASYSHOP_IPN_PRICETO", "to"); // followed by current price
define("EASYSHOP_IPN_28", "has been renamed");
define("EASYSHOP_IPN_29", "Your cart has been updated");
define("EASYSHOP_IPN_30", "[USERNAME] upgraded to class [PROMOCLASS]");
define("EASYSHOP_IPN_31", "User <a href='[USERLINK]'>[USERNAME]</a> (# [USERID]) was automatically promoted to class [PROMOCLASS].");
define("EASYSHOP_IPN_32", "This promotion is based on [PRODUCTQTY] purchase of product <a href='[PRODLINK]'>[PRODUCTNAME]</a> for [PRODUCTPRICE] [CURRENCY].");
define("EASYSHOP_IPN_33", "Transaction id: [TRANSACTIONID],<br />Gross amount: [GROSSAMOUNT],<br />Payment date: [PAYMENTDATE]");
define("EASYSHOP_IPN_34", "Automated message send from");
define("EASYSHOP_IPN_35", "Thank you for your purchase of [PRODUCTNAME].");
define("EASYSHOP_IPN_36", "You are now promoted to user class [PROMOCLASS].");
define("EASYSHOP_IPN_37", "");
define("EASYSHOP_IPN_38", "");
define("EASYSHOP_IPN_39", "");
define("EASYSHOP_IPN_40", "");

// module validate.php
define("EASYSHOP_VAL_01", "Failed to open HTTP connection!");
define("EASYSHOP_VAL_02", "error number");
define("EASYSHOP_VAL_03", "error string");
define("EASYSHOP_VAL_04", "Written POST to paypal");
define("EASYSHOP_VAL_05", "Paypal response VERIFIED");
define("EASYSHOP_VAL_06", "Stock update failed with session id");
define("EASYSHOP_VAL_07", "Stock updated successfully");
define("EASYSHOP_VAL_08", "mc_gross doesn't match rxd mc_gross");
define("EASYSHOP_VAL_09", "Local Entry has already been Completed or doesn't exist");
define("EASYSHOP_VAL_10", "This could be a fraudalent entry or more likely 'a double hit' on the confirm order button!");
define("EASYSHOP_VAL_11", "Customer may need a refund/Credit Card chargeback!");
define("EASYSHOP_VAL_12", "Receiver e-mail mismatched rxd email");
define("EASYSHOP_VAL_13", "duplicate txn_id");
define("EASYSHOP_VAL_14", "payment status not 'Completed' status");
define("EASYSHOP_VAL_15", "LOCAL ENTRY NOT PRESENT!");
define("EASYSHOP_VAL_16", "Paypal response 'INVALID'");

// module e_status.php
define("EASYSHOP_STS_01", "IPN orders");

// not in use
define("EASYSHOP_00", "*");
define("EASYSHOP_PREF_00", EASYSHOP_NAME." - ".EASYSHOP_MENU_00);
define("EASYSHOP_PREF_CONFIG_00", "*");
define("EASYSHOP_PREF_FIELDS_05_0", " *");
define("EASYSHOP_PREF_DISPLAY_00", "*");
?>