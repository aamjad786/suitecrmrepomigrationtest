<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/

/*********************************************************************************

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings,$app_strings,$current_user, $sugar_config;

$module_menu=array();
if(ACLController::checkAccess('Cases', 'edit', true))
	$module_menu [] = Array("index.php?module=Cases&action=EditView&return_module=Cases&return_action=DetailView", $mod_strings['LNK_NEW_CASE'],"CreateCases");
if(ACLController::checkAccess('Cases', 'list', true))
	$module_menu [] = Array("index.php?module=Cases&action=index&return_module=Cases&return_action=DetailView", $mod_strings['LNK_CASE_LIST'],"Cases");
if(ACLController::checkAccess('Cases', 'import', true))
	$module_menu[] =Array("index.php?module=Import&action=Step1&import_module=Cases&return_module=Cases&return_action=index", $mod_strings['LNK_IMPORT_CASES'],"Import", 'Cases');

$module_menu [] = Array("index.php?module=Cases&action=Social_impact_score", $mod_strings['LNK_SOCIAL_IMPACT_SCORE'],"Social Impact Score");
$module_menu [] = Array("index.php?module=Cases&action=agent_attendance_upload", $mod_strings['LNK_AGENT_ATTENDANCE_UPLOAD'],"Agent Attendance Upload");

$isAdmin            = $current_user->is_admin;
$user = $sugar_config['maker_checker_menu_permitted_user']; 
if(in_array(strtolower($current_user->user_name),$user) || $isAdmin==1){

	$module_menu [] = Array("index.php?module=Cases&action=maker_checker", $mod_strings['LNK_MAKER_CHECKER'],"Maker & Checker Dashboard");
	$module_menu [] = Array("index.php?module=Cases&action=maker_checker_history", $mod_strings['LNK_MAKER_CHECKER_HISTORY'],"Maker Checker History");
}

// $module_menu [] = Array("index.php?module=Cases&action=email_status","Email Delivery Status","Email Delivery Status");
$module_menu [] = Array("index.php?module=Cases&action=social_impact_score_upload", $mod_strings['LNK_IMPORT_SOCIAL_IMPACT_SCORE'],"Import Social Impact Score");
$module_menu [] = Array("index.php?module=scrm_Custom_Reports&action=DocumentRequestsReport", 'Document Requests',"Document Requests");
$module_menu [] = Array("index.php?module=Cases&action=Upload_nps_csat", 'NPS AND CSAT',"NPS AND CSAT");


?>