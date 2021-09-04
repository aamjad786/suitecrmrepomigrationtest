<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
$hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['after_save'] = Array(); 

$hook_array['before_save'][] = Array(0, 'Preserving old bean data', 'custom/modules/Opportunities/BeforeSave.php','BeforeSaveOpportunity', 'store_assigned');


// $hook_array['after_save'][] = Array(1,'Assigning the cluster manager of the corresponding city to the opportunity','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','city_cluster_mapping');
// $hook_array['after_save'][] = Array(1,'Insta Opportunities to be assigned to the NG ID of the CAM entered in the application form','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','cam_mapping_insta');
// $hook_array['after_save'][] = Array(1,'Assigning the cluster manager of the corresponding city to the insta opportunity','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','city_cluster_mapping_insta');
// $hook_array['after_save'][] = Array(1,'Assigning the cluster manager of the corresponding dsa id to the dsa opportunity','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','dsa_spoc_assign');
// $hook_array['after_save'][] = Array(3,'Send Appointment SMS','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','send_appointment_smstocustomer');
// $hook_array['after_save'][] = Array(4,'Adding first assigned user to change logs','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','audit_first_assigned');
// $hook_array['after_save'][] = Array(4,'Assigning user email','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','assignmentMail');
// $hook_array['after_save'][] = Array(5,'Send the Customer an SMS','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','assignmentCustomerSMS');

// $hook_array['after_save'][] = Array(1000,'Assign user from logs if they get unassigned due to any reason','custom/modules/Opportunities/AfterSave.php','AfterSaveOpportunity','assign_from_logs');

?>
