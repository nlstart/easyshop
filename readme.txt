#==============================================================================+
# EasyShop - an easy e107 shop plugin with Paypal, PayPal IPN or e-mail checkout
# originally distributed as jbShop - by Jesse Burns aka Jakle
#
# Plugin Support Website: [link=http://e107.webstartinternet.com]http://e107.webstartinternet.com[/link]
#
# A plugin for the e107 Website System; visit [link=http://e107.org]http://e107.org[/link]
# For more plugins visit: [link=http://www.e107coders.org]http://www.e107coders.org[/link]
#
# Author: nlstart
#==============================================================================+
Thank you for using EasyShop. You can show your appreciation and support future development by [link=https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=nlstart%40webstartinternet%2ecom&item_name=NLSTART%20Plugins&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=EN&bn=PP%2dDonationsBF&charset=UTF%2d8]donate via PayPal[/link] to NLSTART.
Alternatively, send me something from [link=https://www.amazon.com/gp/registry/wishlist/KA5YB4XJZYCW/]my Amazon wishlist[/link] to keep me motivated!

Get all out of the EasyShop plugin: buy the [link=http://shop.webstartinternet.com/e107_plugins/easyshop/easyshop.php?prod.3]EasyShop 1.4 Manual[/link].

Purpose of the EasyShop plugin
==============================
GOAL: Create an easy to set up web shop within e107 that integrates with PayPal checkout or e-mail.
Currently HTML PayPal Website Payments Default, PayPal IPN and e-mail checkouts are supported.

Features:
- use PayPal or e-mail the order to website administrator
- predefined all 16 PayPal supported currencies
- create unlimited main categories
- create unlimited categories
- set user class to view category
- set user class to purchase from category
- create unlimited categories per main category
- create unlimited products per product category
- Category and Product overview layout: set the number of column and total shown per page
- create unlimited product properties like sizes, colors etc
- create unlimited product discount codes with percentage/price with optional validation on class, dates and promotional codes
- price delta per product property
- various settings display settings
- handling cost per first product
- separate handling cost other same product
- sending costs per product
- separate sending costs other same product
- multiple images per product
- keep track of book stock (with PayPal IPN only)
- minimum stock level alerts by e-mail
- create downloadable products
- define automatic user class promotion per product (with PayPal IPN only)
- admin decides if buyers can enter directly a number of products or buy one at a time
- attach up to 5 properties per product (size, color etc.)
- attach 1 product discount code per product
- displays random active products in a menu as 'Featured product'
- displays a list of active categories and active products in a menu as 'Product Categories'
- caches selected products during session until user clicks checkout
- customers can maintain their basket before checkout
- checkout directly from the 'Featured product' menu, the basket or category main page
- integrated e107 search functionality
- optional integrated e107 comments functionality for logged in members
- upload of pictures through admin menu
- XHTML 1.1 compliant
- build-in security checks for safe shopping basket
- extensive e-mail override handling options (customers can leave a note for seller, seller can add additional text to e-mail, e-mail information level)
- templated shop front end

What the EasyShop plugin does NOT:
- NO invoice functionality
- NO VAT handling
- NO hidden codes to promote PayPal

Prerequisites:
==============
Before actually using PayPal Shopping Cart functionality on your website, you will need the following:

REQUIRED
 * e107 core v0.7.7 (or newer) installed.
 * A PayPal Premier or Business account
 * The PayPal verified email address at which you will receive payments
 * At least one active product group defined in EasyShop
 * At least one active product with a price defined in EasyShop

OPTIONAL
 * Optional product details (including product id, product images, shipping and sales tax rates)

Installation:
=============

Important Release Candidate information
=======================================
Release Candidates (recognisable on the abbreviation RC in the download name) are meant to be prelimary distributes before the actual release. Release Candidates of plugins - use at your own risk.
All release candidates are tested on e107 v0.7.8 and v.0.7.25. Possible new e107 features might be used and therefore this module might not function correctly on earlier versions, however for e107 0.7.7 this module is expected to run okay. It is strongly advised to test this module before implementing it on a live website.

1. Fresh install:
=================
a. Unzip the files.
b. Upload the EasyShop plugin files into your 'e107_plugins' folder. Although 'Upload plugin' from the Admin section might work, uploading your plugin files by using an FTP client program is recommended.
c. When working on Linux or Unix based server set the CHMOD settings of directories to 755 and set CHMOD of all .php files to 644.
d. Login as an administrator into e107, go to Plugin Manager, install EasyShop and you can start defining the settings.

2. Upgrading
============
2a. from jbShop v1.1:
First, download a copy of EasyShop 1.2 first, perform the upgrade following the readme.txt instructions from there. After a succesful conversion, overwrite the EasyShop 1.2 files with the EasyShop 1.3 files, go to Admin Area > Plugin Manager > perform the upgrade for EasyShop.

2b. from EasyShop v1.2x:
Overwrite the EasyShop 1.2x files with the EasyShop 1.31 files, go to Admin Area > Plugin Manager > perform the upgrade for EasyShop. NOTE: this means that EasyShop 1.2x or 1.3 installations have to install and upgrade to 1.31 first before installing 1.4.

2c. from EasyShop v1.3
Overwrite the EasyShop 1.3 files with the EasyShop 1.31 files, go to Admin Area > Plugin Manager > perform the upgrade for EasyShop. NOTE: this means that EasyShop 1.2x or 1.3 installations have to install and upgrade to 1.31 first before installing 1.4.

2d. from EasyShop v1.31 till v1.33
Overwrite the EasyShop 1.3x files with the EasyShop 1.34 files, go to Admin Area > Plugin Manager > perform the upgrade for EasyShop. NOTE: this means that EasyShop 1.31 till 1.33 installations have to install and upgrade to 1.34 first before installing 1.4

2e. from EasyShop v1.34
Overwrite the EasyShop 1.34 files with the EasyShop 1.43 files, go to Admin Area > Plugin Manager > perform the upgrade for EasyShop.

2f. from EasyShop v1.4 till v1.43
Overwrite the EasyShop 1.4x files with the EasyShop 1.54 files, go to Admin Area > Plugin Manager > perform the upgrade for EasyShop.

2g. from EasyShop v1.5x till v1.54
Overwrite the EasyShop 1.5x files with the EasyShop 1.61 files, go to Admin Area > Plugin Manager > perform the upgrade for EasyShop.

2h. from EasyShop v1.6x till v1.61
Overwrite the EasyShop 1.6x files with the EasyShop 1.7 files, go to Admin Area > Plugin Manager > perform the upgrade for EasyShop.

Quick Upgrading troubleshooting
===============================
If your upgrade to a newer EasyShop version fails in the Plugin Manager, always perform: Admin Area > Database > Check Database validity > Click the box from 'easyshop' and click on the button 'Start Verify'. Select all checkboxes with an error and click the button 'Fix errors' at the bottom of the screen.
Note: e107 0.7.8 will give permanent errors on the indexes; this is a bug in the validity check and will not harm the working of the EasyShop plugin.

3. Language Support:
====================
English, Bulgarian, Dutch, French, German, Hungarian, Italian, Norwegian, Persian, Polish, Portuguese (Brazilian), Russian, Spanish
Note: English is included in the default installation. Additional language files can be downloaded from [link=http://e107.webstartinternet.com]http://e107.webstartinternet.com[/link].

You are encouraged to translate EasyShop into your own native language. Contact me through my [link=http://e107.webstartinternet.com/contact.php]contact page[/link] if you want to send a finished and tested translation.


Styling your EasyShop
=====================
The following style classes have been introduced to style the Main Category Name, Category Name or Product Name to your own personal preference:
1. .easyshop_main_cat_name: style the description of the main category  (introduced in 1.4)
2. .easyshop_cat_name: style the description of the category
3. .easyshop_prod_name: style the description of the product
4. .easyshop_prod_box: style the description of the left box at product details page
5. .easyshop_prod_img: style the image within the left box at product details page
6. .easyshop_nr_of_prod: style the number of products element at the category/product details page (introduced in 1.4)

If you do not specify the styles the size, color, background etc. will be as your regular style settings.
Example to add to your style.css of your theme (which will set the font size to twelve pixels for all of the above mentioned descriptions):
.easyshop_prod_name, .easyshop_cat_name, .easyshop_main_cat_name {
  font-size: 12px;
}
Example to change the border style color to white:
fieldset {
  border-color: #000;
}
Example to center your product image on the product detail page:
.easyshop_prod_img {
  margin-left: auto;
  margin-right: auto;
}


For theme developers or advanced e107 users:
If you want to change the layout of the shop you can copy the file easyshop_templates.php file from the templates folder to your theme folder. Create your changes in the theme copy; the theme template will prevail above the default template.
The following templates are available:
ES_STORE_CONTAINER: presentation of the EasyShop store header/footer
ES_MCAT_TEMPLATE: presentation of a main category
ES_ALL_MCAT_TEMPLATE: presentation of all main categories 
ES_ALLCAT_TEMPLATE: presentation of all categories (automatically displayed when there are no main categories at all of called with easyshop.php?allcat)
ES_CAT_TEMPLATE: presentation of a category
ES_PROD_TEMPLATE: presentation of a product


Known Bugs
==========
- Search of comments on products (search_comments.php) doesn't work.
- easyshop.php: email override without main categories returned to easyshop?allcat without rendering Information level correctly
  Work around solution: create one or more main categories.

Changelog:
==========
 Version 1.7 (EasyShop, August 6, 2012)
 * Goals for 1.7:
	- New functionality for support of datasheet per product (Note: make sure your e107_admin/filetypes.php supports the extension for your datasheet)
	- New functionality for support of quotation per product (Note: make sure your e107 mail functionality is properly working in Admin > Mail > Options)
 * New/Added Features: 
	- created new folder 'datasheets' to save all datasheets in a separate folder
	- admin_config: new checkbox for datasheet display functionality
	- admin_config: new upload button for uploading datasheet functionality (make sure admin/filetypes.php supports pdf files)
	- easyshop.php: added datasheet to product details display
	- easyshop.php: if product quotation is checked; then display quotation button and suppress price on both product (?prod.) and category (?cat.) level
	- easyshop_shortcodes.php: added datasheet and quotation functionality (on product and category level)
	- templates/easyshop_template.php: added datasheet and quotation functionality
	- eayshop_latest_menu.php: don't display the price for a quotation product
 * Minor Changes:
	- easyshop_sql.php: adjustments in product database to support datasheet and quotation
	- easyshop.php: fixed bug for presenting main categories ordering by main_category_order
	- easyshop.php: fixed bug for presenting mcat multiple rows
	- plugin.php: adjusted for update to 1.7
	- easyshop_ver.php: adjusted for version 1.7
	- English.php: added EASYSHOP_CONF_ITM_56 until EASYSHOP_CONF_ITM_61 to support datasheet functionality
	- English.php: added EASYSHOP_CONF_ITM_62 and EASYSHOP_SHOP_94 until EASYSHOP_SHOP_98 to support quotation functionality

 Version 1.61 (EasyShop, June 13, 2011)
 * Minor Changes:
	- easyshop_basket.php: fixed typo causing error when working with discounts
	- easyshop_ver.php: adjusted for version 1.61

 Version 1.6 (EasyShop, June 03, 2011)
 * Sub-goals for release 1.6:
	- improved security
	- reduce number of admin application files
	- support secundary PayPal address as the EasyShop address
 * New/Added Features: 
	- admin_config.php: new setting for primary PayPal e-mail address
	- easyshop_basket.php: better vetting fixes
	- validate.php: check if receiver_email equals primary PayPal e-mail address
 * Minor Changes:
	- admin_categories.php: include insert, edit and delete functionalities, improved XHTML usage
	- admin_config.php: include insert, edit and delete functionalities, improved XHTML usage
	- admin_general_preferences.php: include insert, edit and delete functionalities, improved XHTML usage
	- admin_main_categories.php: include insert, edit and delete functionalities, improved XHTML usage
	- easyshop_sql: database changes for new functionality
	- easyshop_ver.php: adjusted for version 1.6  
	- languages/English.php: new language terms for new functionality
	- plugin.php: removes redundant program admin_categories_edit.php
	- plugin.php: removes redundant program admin_config_edit.php
	- plugin.php: removes redundant program admin_general_preferences_edit.php
	- plugin.php: removes redundant program admin_main_categories_edit.php
	- plugin.php: update database changes
	- track_checkout.php: fixed filling session id in initial processing shop record
	- track_checkout.php: fixed redirect at continue shopping button

 Version 1.54 (EasyShop, August 26, 2010):
 * Bugs Fixed:
   - easyshop.php: fixed clash with e-token functionality of e107 core 0.7.23
   - easyshop_class.php: fixed clash with e-token functionality of e107 core 0.7.23
   - easyshop_class.php: Finally fix the form close issues once and for all
   - easyshop_class.php: fixed new version check location in function getCurrentVersion()
   - easyshop_latest_menu.php: fixed clash with e-token functionality of e107 core 0.7.23
   - easyshop_specials_menu.php: fixed clash with e-token functionality of e107 core 0.7.23
   - easyshop_basket.php: fixed clash with e-token functionality of e107 core 0.7.23
   - track_checkout.php: fixed clash with e-token functionality of e107 core 0.7.23
 * Minor Changes:
   - easyshop_ver.php: adjusted for version 1.54

 Version 1.53 (EasyShop, October 6, 2009):
 * Bugs Fixed:
   - easyshop.php: fixed missing item_id from category level to basket for IPN checkout
   - easyshop_basket.php: fixed calculation of additional shipping costs for multiple products (bugtracker #88)
 * Minor Changes:
	- easyshop_ver.php: adjusted for version 1.53

 Version 1.52 (EasyShop, September 9, 2009):
 * Bugs Fixed:
   - easyshop.php: fix for selecting correct template on shop header/footer
 * Minor Changes:
	- easyshop_ver.php: adjusted for version 1.52

 Version 1.51 (EasyShop, September 7, 2009):
 * New/Added Features:
   - templates/easyshop_template.php: templates to display (main) categories and products
   - easyshop_shortcodes.php: shortcodes to support templated display
 * Altered Features:
   - easyshop.php: changes to support templates
   - easyshop_class.php: changes to support templates
 * Bugs Fixed:
   - easyshop_class.php: fixed correct page link presentation for allcat parameter
   - easyshop_class.php: fixed missing closing form element in function show_checkout (for XHTML compliancy)
   - easyshop_class.php: fixed inproper input elements in function show_checkout (for XHTML compliancy)
   - easyshop.php: fixed presentation of SKU number on product details page
 * Minor Changes:
	- plugin.php: adjusted for version 1.51 (upgrade assuming that 1.5 is installed)
	- easyshop_ver.php: adjusted for version 1.51

 Version 1.5 (EasyShop, August 17, 2009)
 * Sub-goals for release 1.5:
	- new functionality
 * New/Added Features:
	- admin_categories.php: new setting for shopping class per category
	- admin_config.php: new setting for automatic promotion class per product
	- admin_config.php: new setting for minimum stock alert level per product
	- easyshop.php: implemented category shopping class
	- ipn_functions.php: implemented auto promotion to user class from product when payment is completed (IPN only)
	- ipn_functions.php: implemented send alert when minimum stock level is reached (IPN only)
 * Bugs Fixed:
	- ipn_functions.php: function process_items, fixed passing wrong format of amounts to PayPal for countries with non-English notation
	- ipn_functions.php: function easyshop_sendalert, improved determination of admin e-mail address for e107 0.7.8
	- easyshop_class.php: function easyshop_sendalert, fixed incorrect urls in e-mail alerts for products running out of stock
	- easyshop_class.php: function multiple_paging, fixed bug for incorrect page indication in admin pages
 * Minor Changes:
	- plugin.php: update database changes
	- easyshop_sql: database changes for new functionality
	- languages/English.php: new language terms for new functionality
	- images/userclass_16.png: new image for auto promo class
	- easyshop_ver.php: adjusted for version 1.5

 Version 1.43 (EasyShop, July 29, 2009)
 * Sub-goals for release 1.43:
	- bugfixing
 * Bugs Fixed:
	- admin_*.php: put permissions check right after class2 (caused redirect of admins to main index page)
	- easyshop_basket.php: changed solution for empty discount code (bugfix #75)
 * Minor Changes:
	- easyshop_ver.php: adjusted for version 1.43

 Version 1.42 (EasyShop, July 23, 2009)
 * Sub-goals for release 1.42:
	- bugfixing
 * Bugs Fixed:
	- easyshop_basket.php: fixed calculation of shipping costs (bugtracker #81)
	- easyshop_basket.php: fixed calculation of additional shipping costs
	- easyshop_menu.php: removed BB-code tags in alt text of image
	- easyshop_specials_menu.php: removed BB-code tags in alt text of image
	- easyshop_latest_menu.php: removed BB-code tags in alt text of image
 * Minor Changes:
	- general: more efficient way of calling language file
	- general: consistent way of calling included files
	- general: XHTML changed proper end slash of all br tags
	- general: changed exit; to exit();
	- easyshop_ver.php: removed obsolete call of class2 that could generate (hidden) PHP errors
	- plugin.php: adjusted for version 1.42

Version 1.41 (EasyShop, June 13, 2009)
 * Sub-goals for release 1.41:
	- bugfixing
 * New/Added Features:
	- easyshop_latest_menu.php: new menu to display latest product addition
	- ipn_functions.php: when minimum stock level of 1 is reached for stock products the admin will receive an alert by e-mail
	- ipn_functions.php: when user purchased more than there is in stock the the admin will receive an alert by e-mail
	- ipn_functions.php: when product is switched to 'out of stock' the admin will receive an alert by e-mail
	- easyshop_class.php: new function easyshop_alerts to send e-mails to admin
    - e_status.php: added link to shop monitor
	* Altered Features:
   - includes/prototype.js: upgrade from 1.6.0.2 to 1.6.0.3
 * Bugs Fixed:
   - easyshop.php: (bugtracker #78) sending digital download as email attachment fails when IPN is on (works for email override setting)
   - enabledJS.js: changed url behavior
   - easyshop.php: fixed email override to ignore address data when user is logged in
   - easyshop.php: fixed email override address data language terms EASYSHOP_SHOP_90 and EASYSHOP_SHOP_91
   - easyshop_class.php: improved function easyshop_senddownloads
   - easyshop_specials_menu.php: fixed presentation if no image is connected to product
   - e_meta.php: disabled javascript calls
   - e_status.php: suppress presentation if there are no IPN orders
   - track_checkout.php: implemented missing currency sign
   - includes/ipn_functions.php: fixed missing currency signs per bought product in orders reports
   - includes/ipn_functions.php: (bugtracker #78) update stock is now based on product id instead of name and SKU-number
   - includes/enabledJS.js: fixed url in function enabledJS()
 * Minor Changes:
   - languages/English.php: added new language terms for easyshop_latest_menu.php
   - languages/English.php: added new language terms for easyshop_class.php 
   - easyshop_ver.php: adjusted for version 1.41
   - plugin.php: adjusted for version 1.41

Version 1.4 (EasyShop, May 27, 2009)
 * Sub-goals for release 1.4:
   - code efficiency
   - add new functionality: PayPal Instant Payment Notification (IPN)
   - add new functionality: automatic product bookstock calculation (with IPN)
   - special thanks for this release go to KVN, jburns131, JVR and Igor
 * New/Added Features:
   - admin_config.php: added button to upload images directly
   - admin_config.php: added button to upload download products directly
   - admin_config.php: new product feature: Track stock of this product (only with IPN)
   - admin_config.php: new product feature: Current number of this product in stock (only with IPN)
   - admin_config.php: new product feature: Download product (only with IPN)
   - admin_config.php: save download product secured as MD5 speckled file
   - admin_config.php: new shop feature: leave a note for seller (only with e-mail override)
   - admin_config.php: new product feature: Save multiple images per product
   - admin_general_preferences.php: Settings: new option to enable user input of number of products
   - admin_general_preferences.php: Settings: more shopping bag images (blue, green, red, yellow, orange, white, black)
   - admin_general_preferences.php: PayPal info: new option to enable PayPal IPN; thanks KVN
   - admin_general_preferences.php: PayPal info: new option to enable note to seller (only with e-mail override)
   - admin_general_preferences.php: PayPal info: new option to enable login, e-mail and/or address in setting 'Info level'
   - admin_general_preferences.php: PayPal info: new option to enable additional text in e-mail (only with e-mail override)
   - admin_general_preferences.php: IPN Monitor settings: new option to define 'Monitor clean shop days'
   - admin_general_preferences.php: IPN Monitor settings: new option to define 'Monitor clean check days'
   - admin_general_preferences.php: Layout: new tab with shop layout settings
   - admin_general_preferences.php: Layout: settings for showing number of products per page and colums (moved from admin_config.php)
   - admin_general_preferences.php: Layout: settings for showing number of categories per page and colums (moved from admin_categories.php)
   - admin_general_preferences.php: Layout: new settings for showing number of main categories per page and colums
   - admin_monitor.php: new lists to view IPN orders; thanks KVN
   - admin_overview.php: new program to view downloadable products
   - easyshop.php: product details rotates images when multiple images are available
   - easyshop_class.php: e-mails send out by generic e107 mail handler (easyshop_smtp.php is obsolete)
   - easyshop_class.php: sends note to seller if indicated in basket (only with e-mail override)
   - easyshop_class.php: new function class Tabs to support tabs presentation
   - easyshop_menu.php: selects a random image if there are multiple images for the product
   - easyshop_specials_menu.php: selects a random image if there are multiple images for the product
   - easyshop_specials_menu.php: shows line-trough old price and new price; shows end date of discount
   - e_status.php: new program to show number of PayPal IPN orders in the current year (only with IPN)
   - track_checkout.php: new program to track product changes during shopping and keep track of stock; thanks KVN
   - validate.php: new program to validate PayPal IPN orders; thanks KVN
   - includes\ipn_functions.php: new program to assist with PayPal IPN related functions; thanks KVN
   - admin_menu.php: new menu for IPN log viewer
   - admin_logviewer.php: new program that can view and clear the ipn.log file
   - tabs.css: new style sheet to style the tabs on admin_general_preferences.php
 * Altered Features:
   - admin_general_preferences.php: use tabs to display all options more orderly
   - easyshop.php: added style #easyshop_main_cat_name to Main Category Name
   - easyshop.php: main category presentation based on new settings of preferences at layout tab
   - easyshop_ver.php: security related: outsiders can't determine anymore which EasyShop version you are running
 * Bugs Fixed:
   - admin_categories.php: link to product maintenance fixed
   - admin_main_categories.php: removed non-existing link for main categories
   - admin_general_preferences.php: removed hard coded English texts; thanks Igor
   - admin_monitor.php: removed hard coded English texts; thanks Igor
   - easyshop.php: category with empty image field doesn't show properly in main category view
   - easyshop.php: bugfix #75: removed redundant discount calculation for product price
   - easyshop.php: fixed broken image link: product details only shows product image if there is one
   - easyshop.php: (main) categories and product displays following columns and paging settings properly
   - easyshop_basket.php: bugfix #75: fixed error that discount would not be calculated when discount code was empty
   - easyshop_class.php: removed hard coded text "Mail to admin"
   - easyshop_class.php: fixed wrong urls when easyshop_menu was shown on non-EasyShop pages
   - easyshop_class.php: fixed paging bug on main product level
   - easyshop_menu.php: removed too many spaces around currency signs
   - easyshop_menu.php: fixed broken image link: only displays product image if there is one
   - easyshop_specials_menu: removed too many spaces around currency signs
   - help.php: removed hard coded English text; thanks Igor
   - English.php: new language terms to support new functionality
 * Minor Changes:
   - plugin.php: fixed for correct upgrade to 1.4
   - easyshop_sql: changed database structure to support new functionality
   - easyshop_smtp.php: has become obsolete

Version 1.34 (EasyShop, March 03, 2009)
 * Bugs Fixed:
   - admin_categories_edit.php: fixed error Incorrect integer value: '' for column 'category_main_id' at row 1 when no main categories existed
 * Minor Changes:
   - plugin.php: fixed for correct upgrade to 1.34

Version 1.33 (EasyShop, February 27, 2009)
 * New/Added Features:
   - admin_discounts.php: improved checks on from and till date
   - languages\English.php: new language terms EASYSHOP_ADMIN_DISC_35 till EASYSHOP_ADMIN_DISC_38
 * Bugs Fixed:
   - admin_config.php: added check on existing discount id to prevent error when it doesn't exist.
   - admin_config_edit.php: MySQL dbInsert returned #1265 - Data truncated for column 'shipping_first_item' at row 1
     sanatized the price fields and return them as 0.00 when they are empty before updating the database
   - admin_config_edit.php: wrapped integer values with intval() before dbInsert
 * Minor Changes:
   - plugin.php: changed image folder in pre-defined Shop Preferences from img_demo/ to images/
   - plugin.php: fixed for correct upgrade to 1.33

Version 1.32 (EasyShop, October 31, 2008)
 * Bugs Fixed:
   - easyshop.php: added security checks
   - easyshop_basket.php: added security check
 * Minor Changes:
   - plugin.php: fixed for correct upgrade to 1.32  (upgrade directly is possible for 1.2x and 1.3x users as well)
 * Notes:
   - Security release; highly recommended to install this release to protect from SQL injection exploits
   - No language terms have been changed or added; language packs of EasyShop v1.31 can still be used.

Version 1.31 (EasyShop, August, 27, 2008)
 * New/Added Features:
   - easyshop.php: added style #easyshop_main_cat_name to Main Category Name
   - easyshop.php: added style #easyshop_cat_name to Category Name
   - easyshop.php: added style #easyshop_prod_name to Product Name
   - easyshop.php: added style #easyshop_prod_box to style the Product Detail left box
   - easyshop.php: added style #easyshop_prod_img to style the Product Image within Product Detail left box
   - easyshop.php: added extra line breaks between category image and description
   - easyshop.php: added style='padding:0 10px; margin-left:10px;' to legend form element; thanks mcpeace
   - easyshop.php: added display of sku number at product details page
   - admin_general_preferences_edit.php: added check on valid number format of minimum amount
 * Altered Features:
   - none
 * Bugs Fixed:
   - admin_general_preferences.php: proper formatting of minimum order amount
   - admin_general_preferences_edit.php: insert of record #1 should be fixed properly
   - easyshop_class.php: security: fixed check on variable because static already sets the variable; thanks KVN
   - easyshop.php: deleted obsolete `-sign from line 746; thanks mcpeace
   - easyshop.php: various HTML style improvements; thanks mcpeace
   - easyshop.php: proper formatting of minimum order amount
   - easyshop.php: added missing end div tag to allcat view mode for proper rendering
   - easyshop_sql.php: table easyshop_preferences; changed field minimum_amount from INT into FLOAT
   - easyshop_basket.php: handling of properties with spaces; thanks KVN
 * Minor Changes:
   - easyshop_class.php: typo in getCurrentVersion adjusted; thanks KVN
   - admin_check_update.php: typo in getCurrentVersion adjusted
   - plugin.php: fixed for correct upgrade to 1.31

Version 1.3 (EasyShop, July, 26 2008):
 * Sub-goals for release 1.3:
   - code efficiency
   - add more flexibility: more options in preferences
   - add more flexibility: main categories
   - add more flexibility: product properties (e.g. color, size etc.)
   - add more flexibility: product price discounts (with or without voucher code)
   - security
   - XHTML 1.1 compliant
   - integrated with core e107 comments functionality
   - integrated with core e107 class functionality
   - integrated with core e107 front page
 * New/Added Features:
   - easyshop_sql: new database structure
   - plugin.php: update to new database structure
   - admin_config.php: in product overview added a link to view product in shop front page
   - admin_categories.php: enable class per category so shop owner can have class related categories
   - admin_general_preferences.php: admin setting to set currency behind amount
   - admin_general_preferences.php: admin setting to set minimum amount
   - admin_general_preferences.php: admin setting to display checkout button always
   - admin_general_preferences.php: admin setting for alternative product sorting (not active yet)
   - admin_general_preferences.php: admin setting for page dividing character
   - admin_general_preferences.php: admin setting to set size of icon width as presented in admin (main) categories and products
   - admin_general_preferences.php: PayPal info for Cancel page title and Cancel page text
   - admin_general_preferences.php: admin setting to enable the e107 comments function so visitors can comment products
   - admin_general_preferences.php: admin setting to enable background shopping bag image in easyshop_menu
   - admin_general_preferences_edit.php: trailing slash for image path added in case it is missing
   - admin_general_preferences_edit.php: for some v1.2x users the automatic creation of the default record did not work ;
     the application will create record #1 in those cases.
   - admin_properties.php: new admin program to maintain properties
   - admin_discounts.php: new admin program to maintain discounts
   - admin_main_categories.php: new admin program to maintain main categories
   - admin_main_categories_edit.php: new admin program to maintain main categories
   - admin_monitor.php: added row with out-of-stock products
   - admin_monitor.php: added row with categories without main category
   - admin_monitor.php: added row with active products with discount
   - admin_monitor.php: added row with active products with one or more properties
   - cancelled.php: new program for (future IPN) cancelled orders
   - easyshop.php: security: users current session ID is checked before displaying checkout button to prevent XSS vulnerabilities
   - easyshop.php: security: shop support e-mail address is hidden in inline javascript to protect it from e-mail harvasting
   - easyshop.php: security: checks if user belongs to the allowed class when viewing specific category or product
   - easyshop.php: when user is logged in the user id will be passed towards PayPal; the administrator will receive this in the order confirmation e-mail
   - easyshop.php: when user is logged in as admin an edit icon is presented to go directly to maintenance product
   - easyshop_menu.php: security: implemented Singleton Pattern to prevent injections
   - easyshop_specials_menu.php: new menu that shows randomly all products with a discount
   - easyshop_basket.php: security: implemented Singleton Pattern to prevent injections
   - easyshop_class.php: efficiency: new program to call some generic functions
   - easyshop_smtp.php: new program to send e-mail confirmation to site admin
   - admin_upload.php: new admin program to maintain image folder
   - search_comments.php: new program to search for EasyShop comments NOTE: not functioning yet!
   - e_frontpage.php: new program that makes EasyShop also selectable in the e107 FrontPage program
 * Altered Features:
   - admin_categories.php: when active main categories are present; select main category from list in edit/create mode
   - easyshop.php: multipaging and calculations for checkout are done from the easyshop_class
   - easyshop.php: shorter url handling
   - easyshop.php: when showing the basket the front page isn't shown any more
   - easyshop_menu.php: calculations for checkout are done from the easyshop_class
   - easyshop_menu.php: adjusted according shorter url handling
   - easyshop_list_menu.php: adjusted according shorter url handling
   - admin_check_update.php: moved some functionalities to easyshop_class.php
   - admin_categories.php: efficient way to determine drop down list of selected number of categories
 * Bugs Fixed:
   - easyshop.php: better handling minimum amount
   - admin_config.php: adding new and editing existing products can only select active categories
   - thank_you.php: reset the shopping basket after succesful paypal transaction
   - admin_monitor.php: proper active menu indication
   - easyshop_menu.php: fixed text 'price' now included from language file
   - easyshop_menu.php: removed html td tags on price line for better display of menu
 * Minor Changes:
   - HTML output of programs adjusted to XHTML 1.1 compliant (not for admin modules)
   - all admin_ programs: ensured that programs are loaded in admin theme with setting $eplug_admin = true; before calling class2

Version 1.21 (EasyShop, 12 March 2008)
 * New/Added Features:
   - None
 * Altered Features:
   - None
 * Bugs Fixed:
   - includes/config.php: changed table names to lower case
   - plugin.php: added conversion script to convert case sensitive database names to lower case database names
 * Minor Changes:
   - None

Version 1.2 (EasyShop, April 2007):
RC1: 24 April 2007, RC2: 31 May 2007, RC3: 05 June 2007, RC4: 16 October 2007, RC5: 25 October 2007, RC6: 03 January 2008, RC7: 10 March 2008 final version 1.2
Since jbShop ended at version 1.11, I wanted to continue the sequel. That's why the very first version of EasyShop starts with version number 1.2.
 * Sub-goals for release 1.2:
   - make plugin more e107 compliant
   - make plugin language independent
   - no database conversion from jbShop 1.11
 * New/Added Features:
   - EasyShop forum [link=http://e107.webstartinternet.com]http://e107.webstartinternet.com[/link]
   - EasyShop bugtracker [link=http://e107.webstartinternet.com]http://e107.webstartinternet.com[/link]
   - rewritten all code for independent language use
   - added help function for administrative menu
   - added currency code for Yen
   - added currency codes to support all PayPal currencies: AUD, CHF, CZK, DDK, HKD, HUF, NOK, NZD, PLN, SEK, SGD (RC4)
   - the 16 supported currencies are: AUD Australian Dollar, CAD Canadian Dollar, CHF Swiss Franc, CZK Czech Koruna,
     DKK Danish Krone, EUR Euro, GBP Pound Sterling, HKD Hong Kong Dollar, HUF Hungarian Forint, JPY Japanese Yen,
     NOK Norwegian Krone, NZD New Zealand Dollar, PLN Polish Zloty, SEK Swedish Krona, SGD Singapore Dollar, USD U.S. Dollar (since RC4)
   - admin_readme.php displays readme.txt properly from menu in e107 style
   - added check to display message if there are no active product categories
   - added check to display message if there are no active products within a product category
   - added easyshop_menu.php that randomly displays active products in a menu
   - easyshop_menu.php: shows link to view the shopping basket before checkout (RC4)
   - easyshop_menu.php: added checkout button from the menu (RC6)
   - easyshop_menu.php: build in variable set_currency_behind = 0/1 for displaying currency before or after amount for future admin setting (RC6)
   - easyshop_list_menu.php: new menu that shows product links sorted by category (RC7)
   - added checks on products maintenance to have prices with 2 decimals
   - added admin_monitor.php to display shop summary overview
   - added jbshop/plugin.php to provide automatic conversion/rename of database tables from jbShop to EasyShop
   - admin_categories: added display of number of products in the category overview
   - admin_categories: added use of BB code in description field (RC2)
   - admin_config: added display of total and inactive number of products in the shop inventory
   - admin_check_update.php: new program that checks for updates on the NLSTART server (RC7)
   - added functionality to directly access products from category overview (if any available)
   - jbShop performed on each click on 'add to cart' the interface was sending an add form message to PayPal
     this resulted in a new PayPal browser window/tab on each 'add to cart' click.
     EasyShop buffers and saves all 'add to cart' clicks of each session and only interfaces to PayPal by hitting the 'view cart' button.
   - easyshop.php: displays additional costs in product detail overview in case they are above zero
   - easyshop.php: display of categories in HTML format (RC2)
   - easyshop.php: display of shopping basket with e_QUERY ?edit (called from easyshop_menu.php) (RC4)
   - easyshop.php: build in variable set_currency_behind = 0/1 for displaying currency before or after amount for future admin setting (RC4)
   - easyshop.php: build in variable minimum_amount so that checkout button is only shown if total amount is above this minimum (RC4)
   - easyshop.php: build in variable always_show_checkout = 0/1, in case it is 1 the checkout button is always shown, otherwise checkout button will be shown if there is at least one product ordered (RC4)
   - easyshop.php: show checkout button directly from the shopping basket (RC6)
   - easyshop.php: show checkout button directly from category main page (RC6)
   - easyshop.php: removed redundant &amp;url= from category to product overview links (RC6)
   - easyshop_basket.php: shopping cart contains possibilty to delete a product row, reset complete cart or continue shopping (RC4)
   - easyshop_basket.php: build in possibility to add or minus the quantity of ordered products (RC4)
   - easyshop_menu.php: show checkout button directly from the featured product menu (RC6)
   - easyshop_ver.php: new file that contains the current version of easyshop (RC7)
   - admin_check_update.php: new program that checks if an update of EasyShop is available (RC7)
   - e_search.php: added integration with e107 search functionality (RC7)
   - search/search_parser.php: added integration with e107 search functionality (RC7)
   - added Dutch language support (RC4)
   - added Portuguese language support (RC4); thanks to catarina
   - added Norwegian language support (RC4); thanks to Fivestar
   - added French language support (RC5); thanks to Lolo
   - added Russian language support (RC6); thanks to Igor&amp;
   - added Spanish language support (RC7); thanks to DelTree
   - added Italian language support (RC7); thanks to DuMaZone
 * Altered Features:
   - jbShop renamed to EasyShop
   - admin_menu: use of show_admin_menu function to make it more e107 style
   - admin_menu: added admin_check_update.php to menu (RC7)
   - admin_categories: edit/delete product categories in e107 style
   - admin_categories: add/edit category is capable of setting active flag directly (RC5)
   - admin_categories_edit: add/edit category is capable of setting active flag directly (RC5)
   - admin_config: display product price in overview per category
   - admin_config: add/edit/delete products in e107 style
   - admin_config: add/edit product is capable of setting active flag directly (RC5)
   - admin_config_edit: add/edit product is capable of setting active flag directly (RC5)
   - deleted hardcoded resize functionality of categories and products
     (this might be a possible problem for converted jbShop users with e.g. different sized or large images; these users will have to change their images)
   - select category and product image by clicking on miniaturized icon
   - jbShop used 'Add to cart' and 'View cart' buttons from PayPal website which caused delay in performance of the website.
     Besides that this also was blocking full language independency.
     EasyShop renders buttons from the theme css class style 'button'
   - display of prices is made consistent in English style with 2 decimals and no thousand separator
   - easyshop.php: added name='submit' to input class='button' (RC5); thanks secretr
   - easyshop.php: changed link for 'Continue shopping' to return to previous page (instead to start of easyshop.php) (RC6)
   - easyshop.php: category paging was hardcoded and limited to 10 pages. Proper paging function created for unlimited pages (RC6)
   - easyshop.php: product paging was hardcoded and limited to 10 pages. Proper paging function created for unlimited pages (RC6)
   - plugin.php: reads list of database table from easyshop_sql.php (RC7)
 * Bugs Fixed:
   - easyshop.php: e107_plugin directory was hardcoded
   - thank_you.php: e107_plugin directory was hardcoded
   - thank_you.php: customer shopping basket wasn't cleared (RC7)
   - plugin.php: create link in link menu e107_plugin directory was hardcoded
   - plugin.php: removed update $upgrade_alter_tables for flawless upgrade (old remainder of jbShop) (RC6)
   - admin_categories, admin_config, admin_general_preferences, admin_menu, admin_monitor, admin_readme, easyshop, easyshop_basket,
     easyshop_menu, help: fixed fatal errors on language include (RC5); thanks to secretr
   - admin_config: removed several &url tags of hyperlinks as they are not needed (RC4)
   - admin_menu.php: class2.php and auth.php included
   - admin_menu.php: added check for current user admin permissions
   - admin_monitor.php: fixed short php code into proper php code (RC7)
   - easyshop_menu.php: changed & sign in URL's into &amp; for XHTML compliancy (RC6)
   - easyshop_menu.php: Prevent a PHP warning: Division by zero (RC7)
   - easyshop.php, admin_config.php: non XHTML compatible >> text string replaced by &raquo; (right angle quote)
   - easyshop.php: disabled session_cache_limiter('public') (RC5); thanks secretr
   - easyshop.php: enabled session_cache_limiter('nocache') (RC6); thanks mygoggie/secretr
   - easyshop.php: changed & sign in URL's into &amp; for XHTML compliancy (RC6)
   - easyshop.php: login from product details page was referring to paypal checkout due to missing form tag (RC6)
   - easyshop.php: another missing form tag situation solved in the checkout function (RC7)
   - header redirect admin_config_edit.php when editing products didn't jump back to category properly after updating the record
   - replaced mysql_real_escape_string to $tp->toDB when saving variables. This respects various e107 (rights) settings and also avoids XSS and other injection vulnerabilities.
   - show delete function conditionally (only when there are no products in the category) to avoid orphanized products in database
   - admin_readme.php: made readable on Unix platforms by reading lowercase file name (RC2)
   - easyshop.php: fixed wrong return message of no categories if no products were available (RC2)
   - easyshop.php: when clicking on categories while showing details the link is incorrect. Corrected previous line 661. (RC4)
   - easyshop.php: removed borders around product images (RC4)
   - easyshop.php: force a space in the cell of SKU number for proper border display when field is empty (RC6)
   - easyshop.php: fixed presentation of currency sign before and after with variable $set_currency_behind (RC6)
   - easyshop.php: on multiple category pages the actual page will was presented as link too (RC6)
   - easyshop.php: on multiple product pages the second page had always hardcoded category id #1 (RC6)
   - easyshop.php: general settings only read once instead of three times (RC6)
   - easyshop.php: applied correct currency settings while showing the basket (RC7)
   - easyshop.php: return to original page of category or product details after adding product to basket (RC7)
   - easyshop_basket.php: fixed wrong calculations with shipping and handling costs for basket plus and minus (RC7)
   - easyshop_basket.php: fixed wrong calculations with handling costs when ordering more than one product (RC7)
   - easyshop_menu.php: will function correctly when other plugins are active (RC6)
   - admin_general_preferences, admin_config and admin_categories: include handler ren_help.php (for showing help at BBcode buttons) (RC3)
 * Minor Changes:
   - restyled and updated readme.txt
   - various small bugfixes
   - used different language terminology; e.g. products instead of items and shop instead of store (some small fixes on that in RC2)
   - changed and convert to smaller logos (16/32 pixels)
   - code optimization for use of checkout button from function show_checkout (RC6)
   - code optimization for use of pagination of categories and products (RC6)
   - code optimization for use of shop address header (RC6)
   - Dutch.php: replaced special characters with correct HTML tags in Dutch language file (RC6)
   - easyshop.php: preparations for flexible page devider character from future settings (RC7)

Version 1.11 (jbShop, 01 May 2006):
 * New/Added Features:
   - None
 * Altered Features:
   - None
 * Bugs Fixed:
   - Fixed the broken 'Add to Cart' and 'View Cart' links on the 'Item Details' page
 * Minor Changes:
   - None

Version 1.1 (jbShop, 29 Apr 2006):
 * New/Added Features:
   - Added Multiple Currencies: Canadian Dollars, Euro and Pound Stirling
   - Added testing features which allow you to test transactions using the 'Paypal Sandbox'
 * Altered Features:
   - None
 * Bugs Fixed:
   - Fixed mySQL v5 compatibility issues that prevented users from entering/saving data
 * Minor Changes:
   - Fixed Display Issue: Incorrect image size for categories and items
   - Fixed other minor display issues
   - Did some minor code cleanup

Version 1.0 (jbShop, 24 April 2006):
   - Initial Release


Future roadmap
==============
* monitor the buglist / features list on [link=http://e107.webstartinternet.com]http://e107.webstartinternet.com[/link]
* publish languages files that are handed over by the community
* use templates for shop

License
=======
EasyShop is distributed as free open source code released under the terms and conditions of the [link=external=http://www.gnu.org/licenses/gpl.txt]GNU General Public License[/link].