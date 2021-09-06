<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'Leads push feed', 'modules/Leads/SugarFeeds/LeadFeed.php','LeadFeed', 'pushFeed'); 
$hook_array['before_save'][] = Array(77, 'updateGeocodeInfo', 'modules/Leads/LeadsJjwg_MapsLogicHook.php','LeadsJjwg_MapsLogicHook', 'updateGeocodeInfo'); 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Leads/LeadsJjwg_MapsLogicHook.php','LeadsJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 

// Custome Login Hooks

$hook_array['before_save'][] = Array(1, 'check 10 digit mobile', 'custom/modules/Leads/BeforeSaveLead.php','BeforeSaveLead', 'check_digits');
$hook_array['before_save'][] = Array(2, 'check mobile number exists', 'custom/modules/Leads/BeforeSaveLead.php','BeforeSaveLead', 'check_duplicate_lead'); 
$hook_array['before_save'][] = Array(3, 'Change Lead Status based on disposition', 'custom/modules/Leads/BeforeSaveLead.php','BeforeSaveLead', 'change_lead_status');


$hook_array['after_save'][] = Array(2, 'addToCallDisposition', 'custom/modules/Leads/add_call_disposition.php','Class_Disposition_Add', 'fun_Disposition_Save'); 
$hook_array['after_save'][] = Array(3, 'Convert Lead to Account, Opportunities and Contacts when lead status changed to Verified', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'convert_lead_acc_con_opp');
$hook_array['after_save'][] = Array(5, 'Business vintage years calculations', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'business_vintage_years');
$hook_array['after_save'][] = Array(6, '', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'save_on_acc_con_opp');
$hook_array['after_save'][] = Array(7, 'update opportunity when lead is created through crmapi', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'update_opp');
$hook_array['after_save'][] = Array(200, 'adding first assigned user to change log', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'audit_first_assigned');





?>