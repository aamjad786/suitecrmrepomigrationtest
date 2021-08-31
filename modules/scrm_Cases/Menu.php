<?php

global $mod_strings,$app_strings;

if(ACLController::checkAccess('Cases', 'edit', true))
$module_menu [] = Array("index.php?module=scrm_Cases&action=EditView&return_module=scrm_Cases&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"CreateCases");

if(ACLController::checkAccess('Cases', 'list', true))
$module_menu [] = Array("index.php?module=scrm_Cases&action=index", $mod_strings['LNK_LIST'],"Cases");


$module_menu [] = Array("index.php?module=scrm_Cases&action=CaseEscalationMatrix&return_module=scrm_Cases&return_action=DetailView", $mod_strings['LNK_CASE_ESCALATION'],"CaseEscalationMatrix");

$module_menu [] = Array("index.php?module=scrm_Cases&action=CaseEscalationMatrixUpdate&return_module=scrm_Cases&return_action=DetailView", $mod_strings['LNK_CASE_ESCALATION_UPDATE'],"CaseEscalationMatrixUpdate");

?>