<?php
require_once 'custom/CustomLogger/CustomLogger.php';
global $logger;
$logger= new CustomLogger('targetslogichook');
global $logger;
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(77, 'updateGeocodeInfo', 'modules/Prospects/ProspectsJjwg_MapsLogicHook.php','ProspectsJjwg_MapsLogicHook', 'updateGeocodeInfo'); 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Prospects/ProspectsJjwg_MapsLogicHook.php','ProspectsJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 


$logger->log('debug', '<======================STARTED==========================>');//~ $hook_array['after_save'][] = Array(12, 'addToCallDisposition', 'custom/modules/Prospects/add_call_disposition.php','Class_Disposition_Add', 'fun_Disposition_Save');
$hook_array['after_save'][] = Array(2, 'Convert target to lead', 'custom/modules/Prospects/AfterSaveTarget.php','AfterSaveTarget','convert_target_lead');
$logger->log('debug', '<======================end convert==========================>');
//~ $hook_array['process_record'] = Array(); 
//~ $hook_array['process_record'][] = Array(77, 'add Salutation', 'modules/Prospects/ProspectsJjwg_MapsLogicHook.php','ProspectsJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 
$logger->log('debug', '<======================STARTED before save==========================>');
$hook_array['before_save'][] = Array(1, 'Target Date Assigned to', 'custom/modules/Prospects/BeforeSave.php','BeforeSave','target_date_assigned_to'); 
$logger->log('debug', '<======================end before save==========================>');
$logger->log('debug', '<======================STARTED process record==========================>');
$hook_array['process_record'] = Array(); 
$hook_array['process_record'][] = Array(1, 'primary address city', 'custom/modules/Prospects/ProcessRecord.php','ProcessRecord', 'primary_address_city');
$logger->log('debug', '<======================end process record==========================>');

?>
