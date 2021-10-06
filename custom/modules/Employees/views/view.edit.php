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


class EmployeesViewEdit extends ViewEdit {
    var $useForSubpanel = true;
 	function __construct(){
 		parent::__construct();
 	}

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function EmployeesViewEdit(){
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if(isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        }
        else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


 	function display() {
		$Role ='0';
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
		
       	if(is_admin($GLOBALS['current_user']) || $Role=='1') {
            $json = getJSONobj();
            require_once('include/QuickSearchDefaults.php');
            $qsd = QuickSearchDefaults::getQuickSearchDefaults();
            $sqs_objects = array('EditView_reports_to_name' => $qsd->getQSUser());
            $sqs_objects['EditView_reports_to_name']['populate_list'] = array('reports_to_name', 'reports_to_id');
            $quicksearch_js = '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '; enableQS();</script>';

            $this->ss->assign('REPORTS_TO_JS', $quicksearch_js);
			$this->ss->assign('EDIT_REPORTS_TO', true);
        }


       //retrieve employee bean if it is not already in focus
         if(empty($this->bean->id)  && !empty($_REQUEST['record'])){
            $this->bean->retrieve($_REQUEST['record']);
         }
         //populate values for non admin users
        if(!empty($this->bean->id) && !($Role=='1')) {
            global $app_list_strings;
            if( !empty($this->bean->status) && !($Role=='1')) {
                $this->ss->assign('STATUS_READONLY',$app_list_strings['user_status_dom'][$this->bean->status]); }
            if( !empty($this->bean->employee_status) ) {
                $this->ss->assign('EMPLOYEE_STATUS_READONLY', $app_list_strings['employee_status_dom'][$this->bean->employee_status]);
            }
            if( !empty($this->bean->reports_to_id) && !($Role=='1')) {
                $reportsToUser = get_assigned_user_name($this->bean->reports_to_id);
                $reportsToUserField = "<input type='text' name='reports_to_name' id='reports_to_name' value='{$reportsToUser}' disabled>\n";
                $reportsToUserField .= "<input type='hidden' name='reports_to_id' id='reports_to_id' value='{$this->bean->reports_to_id}'>";
                $this->ss->assign('REPORTS_TO_READONLY', $reportsToUserField);
            }
            if( !empty($this->bean->title) && !($Role=='1')) {
                $this->ss->assign('TITLE_READONLY', $this->bean->title);
            }
            if( !empty($this->bean->department) && !($Role=='1')) {
                $this->ss->assign('DEPT_READONLY', $this->bean->department);
            }
        }

 		parent::display();
 	}
}
?>
