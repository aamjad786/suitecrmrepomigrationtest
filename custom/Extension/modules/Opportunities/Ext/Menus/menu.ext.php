<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

global $current_user;

if (!is_admin($current_user)){
	unset($module_menu[0]);
}
$module_menu [] = Array("index.php?module=Opportunities&action=Spoc_mapping_marketing", $mod_strings['LNK_SPOC_MAPPING'],"Spoc Mapping Marketing");

$module_menu [] = Array("index.php?module=Opportunities&action=spoc_mapping_alliance", $mod_strings['LNK_SPOC_MAPPING_ALLIANCE'],"Spoc Mapping Alliance");
?>
