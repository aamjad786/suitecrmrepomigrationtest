<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

global $current_user;

if (!is_admin($current_user)){
	unset($module_menu[0]);
}

?>
