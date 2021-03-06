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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/MySettings/TabController.php');
require_once('include/SugarFields/SugarFieldHandler.php');

$tabs_def = urldecode(isset($_REQUEST['display_tabs_def']) ? $_REQUEST['display_tabs_def'] : '');
$DISPLAY_ARR = array();
parse_str($tabs_def,$DISPLAY_ARR);
global $current_user,$db;
		$Role='0';
		$role = ACLRole::getUserRoleNames($current_user->id);
	 
		  if($role[0] == "Call Center Manager") {
			$rec_id = $this->bean->id;;
			$query = "select ar.name from acl_roles ar join acl_roles_users aru on aru.role_id = ar.id and aru.deleted = 0 join users u on u.id = aru.user_id and u.deleted = 0  and u.id = '$rec_id' where ar.deleted = 0";
			$result = $db->query($query);
			$name = '';
			while($row = $db->fetchByAssoc($result)){
				$name = $row['name'];
			}
			if($name == "Call Center Agent"){
				$Role ='1';
				}
			
		}
		
		$current_user_id = $current_user->id;
			$query = "select id from users where reports_to_id = '$current_user_id' and deleted = 0";
			$result = $db->query($query);
			$user_ids = array();
			while($row = $db->fetchByAssoc($result)){
				$user_ids[] = $row['id'];
			}
			$id = $this->bean->id;;
			if(in_array($id, $user_ids)) {
				 $Role ='1';
				}
		
//there was an issue where a non-admin user could use a proxy tool to intercept the save on their own Employee
//record and swap out their record_id with the admin employee_id which would cause the email address
//of the non-admin user to be associated with the admin user thereby allowing the non-admin to reset the password
//of the admin user.
if(isset($_POST['record']) && !is_admin($GLOBALS['current_user']) && !$GLOBALS['current_user']->isAdminForModule('Employees') && ($_POST['record'] != $GLOBALS['current_user']->id) &&!($Role=='1'))
{
    sugar_die("Unauthorized access to administration.");
}
elseif (!isset($_POST['record']) && !is_admin($GLOBALS['current_user']) && !$GLOBALS['current_user']->isAdminForModule('Employees')&&!($Role=='1'))
{
    sugar_die ("Unauthorized access to user administration.");
}

$focus = new Employee();

$focus->retrieve($_POST['record']);

//rrs bug: 30035 - I am not sure how this ever worked b/c old_reports_to_id was not populated.
$old_reports_to_id = $focus->reports_to_id;

populateFromRow($focus,$_POST);

$focus->save();
$return_id = $focus->id;


if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Employees";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);


header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");


function populateFromRow(&$focus,$row){


	//only employee specific field values need to be copied.
	$e_fields=array('first_name','last_name','reports_to_id','description','phone_home','phone_mobile','phone_work','phone_other','phone_fax','address_street','address_city','address_state','address_country','address_country', 'address_postalcode', 'messenger_id','messenger_type');
	if ( is_admin($GLOBALS['current_user']) ) {
        $e_fields = array_merge($e_fields,array('title','department','employee_status'));
    }
    // Also add custom fields
    $sfh = new SugarFieldHandler();
    foreach ($focus->field_defs as $fieldName => $field ) {
        if ( isset($field['source']) && $field['source'] == 'custom_fields' ) {
            //$e_fields[] = $fieldName;
            $type = !empty($field['custom_type']) ? $field['custom_type'] : $field['type'];
           $sf = $sfh->getSugarField($type);
           if ($sf != null)
           {
               $sf->save($focus, $_POST, $fieldName, $field, '');
           }
           else
           {
               $GLOBALS['log']->fatal("Field '$fieldName' does not have a SugarField handler");
           }
        }
    }
    $nullvalue='';
    
    
    /*
	foreach($e_fields as $field)
	{
		$rfield = $field; // fetch returns it in lowercase only
		if(isset($row[$rfield]))
		{
			$focus->$field = $row[$rfield];
		}
	}
	* 
	* */
}
?>
