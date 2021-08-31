<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'Update escalaion name and email', 'modules/scrm_Cases/Escalation_Functions.php', 'Escalation_Functions', 'UpdateNameAndEmail'); 

$hook_array['after_save'] = Array(); 
$hook_array['before_save'][] = Array(99, 'Send update notification', 'modules/scrm_Cases/Escalation_Functions.php', 'Escalation_Functions', 'sendUpdateNotification'); 
?>