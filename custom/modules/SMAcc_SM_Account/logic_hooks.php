<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 

$hook_array['after_save'] = Array(); 
//$hook_array['after_save'][] = Array(02, 'Save notes as comment', 'modules/SMAcc_SM_Account/afterSave.php','afterSave', 'noteCreation'); 
$hook_array['after_save'][] = Array(02, 'Welcome call failure email', 'custom/modules/SMAcc_SM_Account/controller.php','SMAcc_SM_AccountController', 'welcomeCallFailureEmail'); 
$hook_array['after_save'][] = Array(03, 'NPS Automative Servey link email', 'modules/SMAcc_SM_Account/afterSave.php','afterSave', 'npsSurveyAutomationLink'); 
?>