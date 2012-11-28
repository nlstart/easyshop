<?php

if (!defined('e107_INIT')) { exit(); }

@include_once(e_PLUGIN.'easyshop/languages/'.e_LANGUAGE.'.php');

$front_page['easyshop'] = array('page' => $PLUGINS_DIRECTORY.'easyshop/easyshop.php', 'title' => PAGE_NAME);

?>