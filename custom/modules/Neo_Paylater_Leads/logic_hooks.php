<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['after_save'] = Array(); 


// $hook_array['before_save'][] = Array(3, 'Change Lead Status based on disposition', 'custom/modules/Leads/BeforeSaveLead.php','BeforeSaveLead', 'change_lead_status');


$hook_array['before_save'][] = Array(2, 'addToCallDisposition', 'custom/modules/Neo_Paylater_Leads/add_call_disposition.php','Class_Disposition_Add', 'fun_Disposition_Save'); 


// $hook_array['after_save'][] = Array(6, '', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'save_on_acc_con_opp');


?>
