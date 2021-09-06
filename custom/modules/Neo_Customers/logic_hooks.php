<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'Assign leads to respective people', 'modules/Neo_Customers/Renewals_functions.php', 'Renewals_functions', 'Adddisposition');
$hook_array['before_save'][] = Array(2, 'Update Dispositions', 'modules/Neo_Customers/Renewals_functions.php', 'Renewals_functions', 'updateDispositionBasedOnQueueType');
?>