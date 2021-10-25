<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 

$hook_array['after_save'] = Array(); 
//~ $hook_array['after_save'][] = Array(1, 'Assigned all the target to target list assigned to user for cam', 'custom/modules/ProspectLists/assignedtarget.php','Assign_target', 'assign_target');
$hook_array['after_save'][] = Array(1, 'Assigned all the target to target list assigned to user for cam', 'custom/modules/ProspectLists/AfterSave.php','AfterSave', 'assign_targets_to_group');

$hook_array['after_relationship_add'] = Array(); 
$hook_array['after_relationship_add'][] = Array(1, 'addRelationship', 'custom/modules/ProspectLists/assignedtarget.php','Assign_target', 'addRel_assign_target'); 

$hook_array['process_record'] = Array(); 
$hook_array['process_record'][] = Array(1, 'primary address city', 'custom/modules/ProspectLists/ProcessRecord.php','ProcessRecord', 'add_new_label');


?>
