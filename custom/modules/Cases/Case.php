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













// Case is used to store customer information.
class aCase extends Basic {
        var $field_name_map = array();
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $case_number;
	var $resolution;
	var $description;
	var $name;
	var $status;
	var $priority;


	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	// These are related
	var $bug_id;
	var $account_name;
	var $account_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;
	var $account_name1;

	var $table_name = "cases";
	var $rel_account_table = "accounts_cases";
	var $rel_contact_table = "contacts_cases";
	var $module_dir = 'Cases';
	var $object_name = "Case";
	var $importable = true;
	/** "%1" is the case_number, for emails
	 * leave the %1 in if you customize this
	 * YOU MUST LEAVE THE BRACKETS AS WELL*/
	var $emailSubjectMacro = '[CASE:%1]';

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('bug_id', 'assigned_user_name', 'assigned_user_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');

	var $relationship_fields = Array('account_id'=>'accounts', 'bug_id' => 'bugs',
									'task_id'=>'tasks', 'note_id'=>'notes',
									'meeting_id'=>'meetings', 'call_id'=>'calls', 'email_id'=>'emails',
									);

    public function __construct() {
		parent::__construct();
		global $sugar_config;
		if(!$sugar_config['require_accounts']){
			unset($this->required_fields['account_name']);
		}

		$this->setupCustomFields('Cases');
        foreach ($this->field_defs as $name => $field) {
            $this->field_name_map[$name] = $field;
        }
	}

	/**
	 * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
	 */
	function aCase(){
		$deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
		if(isset($GLOBALS['log'])) {
			$GLOBALS['log']->deprecated($deprecatedMessage);
		}
		else {
			trigger_error($deprecatedMessage, E_USER_DEPRECATED);
		}
		self::__construct();
	}

	var $new_schema = true;

	function get_summary_text()
	{
		return "$this->name";
	}

	function save_relationship_changes($is_update, $exclude = array())
	{
		parent::save_relationship_changes($is_update, $exclude);

		if (!empty($this->contact_id)) {
			$this->set_case_contact_relationship($this->contact_id);
		}
	}

	function set_case_contact_relationship($contact_id)
	{
		global $app_list_strings;
		$default = $app_list_strings['case_relationship_type_default_key'];
		$this->load_relationship('contacts');
		$this->contacts->add($contact_id,array('contact_role'=>$default));
	}

	function fill_in_additional_list_fields()
	{
		parent::fill_in_additional_list_fields();
		/*// Fill in the assigned_user_name
		//$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$account_info = $this->getAccount($this->id);
		$this->account_name = $account_info['account_name'];
		$this->account_id = $account_info['account_id'];*/
	}

	function fill_in_additional_detail_fields()
	{
		parent::fill_in_additional_detail_fields();
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

        if(!empty($this->id)) {
		    $account_info = $this->getAccount($this->id);
            if(!empty($account_info)) {
                $this->account_name = $account_info['account_name'];
                $this->account_id = $account_info['account_id'];
            }
        }
	}


	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		$this->load_relationship('contacts');
		$query_array=$this->contacts->getQuery(true);

		//update the select clause in the retruned query.
		$query_array['select']="SELECT contacts.id, contacts.first_name, contacts.last_name, contacts.title, contacts.email1, contacts.phone_work, contacts_cases.contact_role as case_role, contacts_cases.id as case_rel_id ";

		$query='';
		foreach ($query_array as $qstring) {
			$query.=' '.$qstring;
		}
	    $temp = Array('id', 'first_name', 'last_name', 'title', 'email1', 'phone_work', 'case_role', 'case_rel_id');
		return $this->build_related_list2($query, new Contact(), $temp);
	}

	function get_list_view_data(){
		global $current_language;
		$app_list_strings = return_app_list_strings_language($current_language);

		$temp_array = $this->get_list_view_array();
		$temp_array['NAME'] = (($this->name == "") ? "<em>blank</em>" : $this->name);
        $temp_array['PRIORITY'] = empty($this->priority)? "" : (!isset($app_list_strings[$this->field_name_map['priority']['options']][$this->priority]) ? $this->priority : $app_list_strings[$this->field_name_map['priority']['options']][$this->priority]);
        $temp_array['STATUS'] = empty($this->status)? "" : (!isset($app_list_strings[$this->field_name_map['status']['options']][$this->status]) ? $this->status : $app_list_strings[$this->field_name_map['status']['options']][$this->status]);
		$temp_array['ENCODED_NAME'] = $this->name;
		$temp_array['CASE_NUMBER'] = $this->case_number;
		$temp_array['SET_COMPLETE'] =  "<a href='index.php?return_module=Home&return_action=index&action=EditView&module=Cases&record=$this->id&status=Closed'>".SugarThemeRegistry::current()->getImage("close_inline","title=".translate('LBL_LIST_CLOSE','Cases')." border='0'",null,null,'.gif',translate('LBL_LIST_CLOSE','Cases'))."</a>";
		//$temp_array['ACCOUNT_NAME'] = $this->account_name; //overwrites the account_name value returned from the cases table.
		return $temp_array;
	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = $this->db->quote($the_query_string);
	array_push($where_clauses, "cases.name like '$the_query_string%'");
	array_push($where_clauses, "accounts.name like '$the_query_string%'");

	if (is_numeric($the_query_string)) array_push($where_clauses, "cases.case_number like '$the_query_string%'");

	$the_where = "";

	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}

	if($the_where != ""){
		$the_where = "(".$the_where.")";
	}

	return $the_where;
	}

	function set_notification_body($xtpl, $case)
	{
		global $app_list_strings;

		$xtpl->assign("CASE_SUBJECT", $case->name);
		$xtpl->assign("CASE_PRIORITY", (isset($case->priority) ? $app_list_strings['case_priority_dom'][$case->priority]:""));
		$xtpl->assign("CASE_STATUS", (isset($case->status) ? $app_list_strings['case_status_dom'][$case->status]:""));

		//To Remove html_entities from description while sending email.
		//SCRM - 24-June-2016
		//Start
                $description = $case->description;
                $description =  html_entity_decode($description);
                $description =  strip_tags( $description);
		$xtpl->assign("CASE_DESCRIPTION", $description);
		//End

		return $xtpl;
	}

		function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}

	/**
	 * retrieves the Subject line macro for InboundEmail parsing
	 * @return string
	 */
	function getEmailSubjectMacro() {
		global $sugar_config;
		return (isset($sugar_config['inbound_email_case_subject_macro']) && !empty($sugar_config['inbound_email_case_subject_macro'])) ?
			$sugar_config['inbound_email_case_subject_macro'] : $this->emailSubjectMacro;
	}

	// function create_export_query(&$order_by, &$where){
 //        $custom_join = $this->custom_fields->getJOIN(true, true);
 //        $query = "select users.user_name,users.first_name,users.last_name,cases.*,cases_cstm.*";
 //        if ($custom_join) {
 //            $query .= $custom_join['select'];
 //        }
 //        $query .= " FROM cases 
 //                     JOIN users 
 //                            ON cases.assigned_user_id=users.id ";

 //        if ($custom_join) {
 //            $query .= $custom_join['join'];
 //        }
 //        $where_auto = " cases.deleted=0 ";
 //        $query .= empty($where) ? "WHERE $where_auto" : "WHERE ($where) AND $where_auto";
 //        $query .= empty($order_by) ? "" : " ORDER BY " . $this->process_order_by($order_by, null);
 //        return $query;
 //    }

	function getAccount($case_id){
		if(empty($case_id)) return array();
	    $ret_array = array();
		$query = "SELECT acc.id, acc.name from accounts  acc, cases  where acc.id = cases.account_id and cases.id = '" . $case_id . "' and cases.deleted=0 and acc.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null){
			$ret_array['account_name'] = stripslashes($row['name']);
			$ret_array['account_id'] 	= $row['id'];
		}
		else{
			$ret_array['account_name'] = '';
			$ret_array['account_id'] 	= '';
		}
		return $ret_array;
	}

	function save($check_notify = false)
    {
    	if(!empty($this->update_text))
    		$this->update_text = htmlentities(htmlspecialchars($this->update_text));
    	// $id = parent::save($check_notify);
        return parent::save($check_notify);
    	// die("again");
    }

    function getServiceManagerForCase(){
		$userName = "";
		if (!empty($appId)) {
			global $db;
			$queryToGetuser = "SELECT first_name,last_name FROM users WHERE id IN (SELECT assigned_user_id FROM smacc_sm_account where app_id = '$appId')";
	        $result = $db->query($queryToGetuser);
	        $userData = $db->fetchByAssoc($result);
	        $userName = $userData['first_name'] . " " . $userData['last_name'];
		}
		return $userName;
	}

    function create_export_query($order_by, $where, $relate_link_join='')
    {
        $query = "
        SELECT 
            name,
            CONCAT(COALESCE(first_name, ''), ' ',COALESCE(last_name,'')) AS 'assigned_user',
            assigned_user_department_c,
            case_number,
            merchant_app_id_c,
            merchant_contact_number_c,
            merchant_email_id_c,
            merchant_establisment_c,
            merchant_name_c,
            case_location_c,
            resolution,
            attended_by_c,
            complaintaint_c,
            DATE_FORMAT(DATE_ADD(date_attended_c, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date Attended (d-m-Y)',
            DATE_FORMAT(DATE_ADD(date_resolved_c, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date Resolved (d-m-Y)',
            case_source_c,
            case_sub_source_c,
            case_category_c,
            case_subcategory_c,
            case_details_c,
            age_c,
            type,
            sub_priority_c,
            priority,
            case_action_code_c,
            state,
            closed_by_c,
            LBAL_c,
            tat_in_days_c,
            tat_status_c,
            proposed_preclosure_amount_c,
            min_preclosure_amount_c,
            escalation_level_c,
            cases.date_entered,
            DATE_FORMAT(DATE_ADD(cases.date_entered, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date created (d-m-Y)',
            cases.date_modified,
            DATE_FORMAT(DATE_ADD(cases.date_modified, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date modified (d-m-Y)',
            cases.id,
            DATE_FORMAT(DATE_ADD(cases_cstm.date_closed_c, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date Closed (d-m-Y)',
            case_subcategory_c_new_c,
            case_category_c_new_c,
            case_category_approval_c,
            maker_comment_c,
            checker_comment_c,
            maker_id_c,
            scheme_c,
			(select concat(u.first_name,' ',u.last_name) from users u where u.id=cases.created_by) as  created_by_user,
            processor_name_c,
            fi_business_c,	
            partner_name_c

                        ";
        //$query .=  $custom_join['select'];
        $query .= " FROM cases join cases_cstm on cases.id=cases_cstm.id_c";
        //$query .=  $custom_join['join'];
        //$query .= "";
        $query .= "		LEFT JOIN users
                        ON cases.assigned_user_id=users.id";
        
        $where_auto = "  cases.deleted=0
        ";


        if($where != "")
                $query .= " where $where AND ".$where_auto;
        else
                $query .= " where ".$where_auto;



        if($order_by != "")
                $query .= " ORDER BY $order_by";
        else
                $query .= " ORDER BY cases.date_entered desc";
        $GLOBALS['log']->debug("Create export query cases -> " . $query);
        // print_r($query);
        // die();

        return $query;
    }

    function listviewACLHelper(){
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		$in_group = false; //SECURITY GROUPS
		if(!empty($this->account_id)){

			if(!empty($this->account_id_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->account_id_owner;
			}
			/* BEGIN - SECURITY GROUPS */
			else {
				global $current_user;
                $parent_bean = BeanFactory::getBean('Accounts',$this->account_id);
                if($parent_bean !== false) {
                	$is_owner = $current_user->id == $parent_bean->assigned_user_id;
                }
			}
			require_once("modules/SecurityGroups/SecurityGroup.php");
			$in_group = SecurityGroup::groupHasAccess('Accounts', $this->account_id, 'view');
        	/* END - SECURITY GROUPS */
		}
			/* BEGIN - SECURITY GROUPS */
			/**
			if(!ACLController::moduleSupportsACL('Accounts') || ACLController::checkAccess('Accounts', 'view', $is_owner)){
			*/
			if(!ACLController::moduleSupportsACL('Accounts') || ACLController::checkAccess('Accounts', 'view', $is_owner, 'module', $in_group)){
        	/* END - SECURITY GROUPS */
				$array_assign['ACCOUNT'] = 'a';
			}else{
				$array_assign['ACCOUNT'] = 'span';
			}

		return $array_assign;
	}

    function getUserToAssign(){
        global $db;
        $query = "
            SELECT id FROM acl_roles
            WHERE name = 'Customer support executive Assignment Dynamic'
        ";
        $role_id = "";
        $results = $db->query($query);
        while ($row = $db->fetchByAssoc($results)) {
            if(!empty($row['id'])){
                $role_id = $row['id'];
            }
        }
        require_once('modules/ACLRoles/ACLRole.php');
        $role = new ACLRole();
        $role->retrieve($role_id);
        $role_users = $role->get_linked_beans( 'users','User');
        $users = array();
        $max_try = 0;
        foreach($role_users as $role_user){
        	$max_try++;
            array_push($users, $role_user->id);
        }
        // echo "before filter :: ";print_r($users);echo "<br>";
        // $users = $this->filterUsers($users);
        // echo "input users :: ";print_r($users);echo "<br>";
        $field = 'assigned_user_id';
        require_once('modules/AOW_WorkFlow/aow_utils.php');
        $value = "";
        $i = 0;
        while(empty($value) && ($i<$max_try)){
        	$i++;
        	$value = getRoundRobinUser($users, "case_assignment");	
        	// echo "Round Robin user $i :: " . $value . "<br>";
        	setLastUser($value, "case_assignment");
        	if(!empty($value) && $this->isUserMaxed($value)){
        		$value = "";
        	}
        }
        if(empty($value)){
        	$value = 1;
        }
        return $value;
    }

    function arrayToQueryString($users){
    	$query_string = "";
    	$query_array = array();
    	foreach ($users as $user_id) {
    		array_push($query_array, "'" . $user_id . "'");
    	}
    	$query_string = implode(",", $query_array);
    	// echo "input users query string:: ";print $query_string;echo "<br>";
    	return $query_string;
    }

    /**
     *	Check For 60 assigned cases
     */
    function isUserMaxed($user_id){
    	global $db;
    	$query = "
    		SELECT COUNT(*) as 'count' FROM cases c
    		WHERE c.state not in ('Closed','Resolved')
    		AND c.deleted = 0
    		AND assigned_user_id = '$user_id'
    	";      
    	$is_maxed = false;	
    	// echo "isUserMaxed query :: ";print $query;echo "<br>";
    	$results = $db->query($query);
    	// echo "iisUserMaxed query results :: ";print $results;echo "<br>";
    	while ($row = $db->fetchByAssoc($results)) {
    		if($row['count'] >1500 ){
    			$is_maxed = true;
    			break;
    		}
    	}
    	return $is_maxed;
    }

    function filterUsers($users){
    	$filtered_users = array();
    	$query_string = $this->arrayToQueryString($users);
    	global $db;
    	$query = "
    		SELECT distinct(assigned_user_id) FROM cases c
    		WHERE c.state != 'Closed'
    		AND assigned_user_id IN ($query_string)
    		GROUP BY assigned_user_id
    		HAVING COUNT(*) < 1500
    		ORDER BY assigned_user_id
    	";      	
    	// echo "inut user db query :: ";print $query;echo "<br>";
    	$results = $db->query($query);
    	// echo "inut user db query results :: ";print $results;echo "<br>";
    	while ($row = $db->fetchByAssoc($results)) {
    		array_push($filtered_users, $row['assigned_user_id']);
    	}
    	return $filtered_users;
    }

    /*
      modified as per our requirement, user should have less than 60 live cases assigned to him.
      Not using, round robin is being used.
    */
    function getLeastBusyUser($users, $field) {
        $counts = array();
        foreach($users as $id) {
            $c = $this->db->getOne("
            	SELECT count(*) AS c FROM ".$this->table_name.
            	" 
            	WHERE $field = '$id' 
            	AND deleted = 0
            	AND state != 'Closed'
            	");
            $counts[$id] = $c;
        }
        asort($counts);
        $countsKeys = array_flip($counts);
        $least_assigned_user_id = array_shift($countsKeys);
        if(isset($counts[$least_assigned_user_id]) && $counts[$least_assigned_user_id]>1500){
        	$least_assigned_user_id = NULL;
        }
        // print_r("$least_assigned_user_id");echo "<br>";
        return $least_assigned_user_id;
    }
}
?>
