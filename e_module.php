<?php
/*
+------------------------------------------------------------------------------+
| EasyShop - an easy e107 web shop  | adapted by nlstart
| formerly known as
|    jbShop - by Jesse Burns aka jburns131 aka Jakle
|    Plugin Support Site: e107.webstartinternet.com
|
|    For the e107 website system visit http://e107.org
|
|    Released under the terms and conditions of the
|    GNU General Public License (http://gnu.org).
|    Code addition by KVN to support nlstart
|    Aug 2008 :- IPN API and basic Stock Tracking functions
+------------------------------------------------------------------------------+
*/
// Stop caching for all browsers
session_cache_limiter('nocache');
// Start a session to catch the basket
session_start();
if(!isset($_SESSION['e_JSENABLED'])){
 // if JS is enabled on site - this will be redefined by the JS call to includes/enabledJS.php from the page :)
 $_SESSION['e_JSENABLED'] = FALSE;
}
?>