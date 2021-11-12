<?php
require_once 'custom/CustomLogger/CustomLogger.php';
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(77, 'updateGeocodeInfo', 'modules/Prospects/ProspectsJjwg_MapsLogicHook.php','ProspectsJjwg_MapsLogicHook', 'updateGeocodeInfo'); 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Prospects/ProspectsJjwg_MapsLogicHook.php','ProspectsJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 

$hook_array['after_save'][] = Array(2, 'Convert target to lead', 'custom/modules/Prospects/AfterSaveTarget.php','AfterSaveTarget','convert_target_lead');
$hook_array['before_save'][] = Array(1, 'Target Date Assigned to', 'custom/modules/Prospects/BeforeSave.php','BeforeSave','target_date_assigned_to'); 
$hook_array['process_record'] = Array(); 
$hook_array['process_record'][] = Array(1, 'primary address city', 'custom/modules/Prospects/ProcessRecord.php','ProcessRecord', 'primary_address_city');
?>
