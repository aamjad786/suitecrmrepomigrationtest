<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
$hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'send email on modifications', 'custom/modules/Cases/data_sync.php', 'DataSync', 'CheckUpdatedFields'); //added
$hook_array['before_save'][] = Array(1, 'Close if any website call back alerts exist', 'custom/modules/Cases/data_sync.php', 'DataSync', 'markAlertsAsRead'); //added
$hook_array['before_save'][] = Array(1, 'Preserve bean data before save', 'custom/modules/Cases/data_sync.php', 'DataSync', 'getPreviousUser'); //added
// $hook_array['before_save'][] = Array(1, 'Unfunded App id TDS case check', 'custom/modules/Cases/data_sync.php', 'DataSync', 'checkUnfundedTDScase'); //added
$hook_array['before_save'][] = Array(1, 'resolution comment check during case closure', 'custom/modules/Cases/data_sync.php', 'DataSync', 'resolutioncheck'); //added
$hook_array['before_save'][] = Array(1, 'Check is Ambit or not', 'custom/modules/Cases/data_sync.php', 'DataSync', 'checkAmbit'); //added
$hook_array['before_save'][] = Array(2, 'Cases push feed', 'modules/Cases/SugarFeeds/CaseFeed.php','CaseFeed', 'pushFeed'); 
$hook_array['before_save'][] = Array(3, 'Fetch Processor Name from AS API', 'custom/modules/Cases/data_sync.php', 'DataSync', 'processorName'); //added
// $hook_array['before_save'][] = Array(3, 'Check weather to auto classify email or not', 'custom/modules/Cases/data_sync.php', 'DataSync', 'classify'); //added
// $hook_array['before_save'][] = Array(4, 'Assign date attained', 'custom/modules/Cases/data_sync.php', 'DataSync', 'assignDateAction'); //added

$hook_array['before_save'][] = Array(10, 'Save case updates', 'custom/modules/AOP_Case_Updates/CustomCaseUpdatesHook.php','CustomCaseUpdatesHook', 'saveUpdate'); // modified
//$hook_array['before_save'][] = Array(11, 'Save case events', 'modules/AOP_Case_Events/CaseEventsHook.php','CaseEventsHook', 'saveUpdate');
$hook_array['before_save'][] = Array(11, 'Save custom case events', 'modules/AOP_Case_Events/CustomCaseEventsHook.php','CustomCaseEventsHook', 'saveUpdate'); 
$hook_array['before_save'][] = Array(12, 'Case closure prep', 'custom/modules/AOP_Case_Updates/CustomCaseUpdatesHook.php','CustomCaseUpdatesHook', 'closureNotifyPrep'); 
$hook_array['before_save'][] = Array(77, 'updateGeocodeInfo', 'modules/Cases/CasesJjwg_MapsLogicHook.php','CasesJjwg_MapsLogicHook', 'updateGeocodeInfo'); 

$hook_array['after_save'] = Array(); 

$hook_array['after_save'][] = Array(10, 'Send contact case closure email', 'custom/modules/AOP_Case_Updates/CustomCaseUpdatesHook.php','CustomCaseUpdatesHook', 'closureNotify'); 
$hook_array['after_save'][] = Array(12, 'send email on insert', 'custom/modules/Cases/data_sync.php', 'DataSync', 'checkInsertedFields'); //added
$hook_array['after_save'][] = Array(12, 'send email CCO suspicious trans', 'custom/modules/Cases/data_sync.php', 'DataSync', 'suspicioustrans'); //added
$hook_array['after_save'][] = Array(15, 'count no. of edits for category and subcategory', 'custom/modules/Cases/data_sync.php', 'DataSync', 'edit_count'); //added
$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Cases/CasesJjwg_MapsLogicHook.php','CasesJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 
$hook_array['after_save'][] = Array(100, 'Save CS Team updated category and sub category', 'custom/modules/Cases/data_sync.php', 'DataSync', 'tempCategoryStore'); //added
$hook_array['after_save'][] = Array(103, 'Save the old case type', 'custom/modules/Cases/data_sync.php', 'DataSync', 'caseTypeSave');
$hook_array['after_save'][] = Array(105, 'Save the case owner', 'custom/modules/Cases/data_sync.php', 'DataSync', 'caseOwnerSave');
//$hook_array['after_save'][] = Array(130, 'SNS function to call email tagging lambda', 'custom/modules/Cases/EmailAutomation.php', 'EmailAutomation', 'call_sns'); //added

$hook_array['after_relationship_add'] = Array(); 
$hook_array['after_relationship_add'][] = Array(9, 'Assign account', 'custom/modules/AOP_Case_Updates/CustomCaseUpdatesHook.php','CustomCaseUpdatesHook', 'assignAccount'); 
$hook_array['after_relationship_add'][] = Array(10, 'Send contact case email', 'custom/modules/AOP_Case_Updates/CustomCaseUpdatesHook.php','CustomCaseUpdatesHook', 'creationNotify'); 
$hook_array['after_relationship_add'][] = Array(77, 'addRelationship', 'modules/Cases/CasesJjwg_MapsLogicHook.php','CasesJjwg_MapsLogicHook', 'addRelationship'); 
$hook_array['after_retrieve'] = Array(); 
$hook_array['after_retrieve'][] = Array(10, 'Filter HTML', 'custom/modules/AOP_Case_Updates/CustomCaseUpdatesHook.php','CustomCaseUpdatesHook', 'filterHTML'); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_relationship_delete'][] = Array(77, 'deleteRelationship', 'modules/Cases/CasesJjwg_MapsLogicHook.php','CasesJjwg_MapsLogicHook', 'deleteRelationship'); 

?>