<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'send email on modifications', 'custom/modules/neo_Paylater_Open/payLaterOpenBeforeSaveEvents.php', 'payLaterOpenBeforeSaveEvents', 'sendEmailToCustomer');



$hook_array['after_save'] = Array(); 

$hook_array['after_save'][] = Array(1, 'Delete record on status change to closed', 'custom/modules/neo_Paylater_Open/payLaterOpenAfterSaveEvents.php', 'payLaterOpenAfterSaveEvents', 'DeleteRecordOnStatusChangeToClosed');

$hook_array['after_relationship_add'] = Array(); 


$hook_array['after_retrieve'] = Array(); 



?>