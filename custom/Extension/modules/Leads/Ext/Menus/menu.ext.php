<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

unset($module_menu[1]);
global $current_user;

$aclRole=new ACLRole();
$role = $aclRole->getUserRoleNames($current_user->id);

if (!is_admin($current_user) && $role[0] != 'Customer Acquisition Manager'){
	unset($module_menu[0]);
}

?>
