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

// Custom Login Hooks

$hook_array['before_save'][] = Array(1, 'Check 10 Digit Mobile', 'custom/modules/Leads/BeforeSaveLead.php','BeforeSaveLead', 'checkMobileNumber');
// $hook_array['before_save'][] = Array(2, 'Dedup Check For Lead', 'custom/modules/Leads/BeforeSaveLead.php','BeforeSaveLead', 'checkDuplicateLead'); 
$hook_array['before_save'][] = Array(3, 'Sanity For Filed Values', 'custom/modules/Leads/BeforeSaveLead.php','BeforeSaveLead', 'fieldSanity');
$hook_array['before_save'][] = Array(4, 'Update Opp fields if UTM fields are updated', 'custom/modules/Leads/BeforeSaveLead.php','BeforeSaveLead', 'utmFieldsUpdate');


$hook_array['after_save'][] = Array(2, 'addToCallDisposition', 'custom/modules/Leads/AddCallDisposition.php','Disposition', 'saveDisposition'); 
$hook_array['after_save'][] = Array(3, 'Auto Convert Lead to Account, Opportunities and Contacts', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'autoConvertionOfLead');
// $hook_array['after_save'][] = Array(5, 'Business vintage years calculations', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'business_vintage_years');
// $hook_array['after_save'][] = Array(6, '', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'save_on_acc_con_opp');
// $hook_array['after_save'][] = Array(7, 'update opportunity when lead is created through crmapi', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'update_opp');
// $hook_array['after_save'][] = Array(200, 'adding first assigned user to change log', 'custom/modules/Leads/AfterSaveLead.php','AfterSaveLead', 'audit_first_assigned');





?>