<?php

require_once('include/entryPoint.php');
require_once 'custom/CustomLogger/CustomLogger.php';

class Cases_functions{
    private $log;
    function __construct() {
        $this->log = fopen("Logs/Cases_functions.log", "a");
        $this->logger_case_assign_role = new CustomLogger('CaseAssignmentExcecutiveRole');
        $this->logger_case_assigned_to_admin = new CustomLogger('CasesAssignedToAdmin');
    }
    function __destruct() {
        fclose($this->log);
    }
	/*
	* This function saves escalated mail ids in email_to_c,email_cc_c
	*/
	function populateToAndCC($case_bean, $to_arr, $cc_arr){
		if(empty($case_bean)){
			return;
		}
		foreach($to_arr as $to){
			if(!empty($case_bean->email_to_c)){
				$pos = strpos($case_bean->email_to_c,$to);
				if($pos===false){
					$case_bean->email_to_c .= ";".$to;
				}
			}else{
				$case_bean->email_to_c = $to;
			}
		}
		foreach($cc_arr as $cc){
			if(!empty($case_bean->email_cc_c)){
				$pos = strpos($case_bean->email_cc_c,$cc);
				if($pos===false){
					$case_bean->email_cc_c .= ";".$cc;
				}
			}else{
				$case_bean->email_cc_c = $cc;
			}
		}
		$case_bean->save();
	}
        
    function casesAuthentication() {
        global $current_user, $sugar_config;
        $roles = ACLRole::getUserRoleNames($current_user->id);
        $permitted_users = $sugar_config['casesAuthentication_permitted_user'];
        if ($current_user->is_admin || in_array($current_user->user_name, $permitted_users) || $this->isCustomerManagerUser($roles) || in_array("Admin", $roles)) {
            return true;
        } else {
            return false;
        }
    }

    function isCustomerManagerUser($roles) {
        $results = false;
        
        if (!empty($results)) {
            foreach ($roles as $role) {
                if (stripos($role, "Case Manager") !== false) {
                    $results = true;
                    break;
                }
            }
        }
        return $results;
    }


    function getUserIdForThisRole($role_name){
    	global $db;
    	//Customer support executive Assignment
    	//Customer support executive Assignment Dynamic
    	$query = "
            SELECT u.id FROM users u
            LEFT JOIN acl_roles_users aru ON aru.user_id = u.id
            LEFT JOIN acl_roles au ON aru.role_id = au.id
            WHERE au.name = '$role_name'
            AND au.deleted = 0
            AND aru.deleted = 0
            AND u.deleted = 0
    	";
    	$results = $db->query($query);
        $this->logger_case_assign_role->log('debug', "Role name : " . $role_name);
        $this->logger_case_assign_role->log('debug', "Fetched Results from DB : " . print_r($results,true));
    	$user_id_list = array();
    	while ($row = $db->fetchByAssoc($results)) {
    		if(!empty($row['id'])){
    			array_push($user_id_list, $row['id']);
    		}
    	}
    	$this->logger_case_assign_role->log('debug', "Fetched Users - Array Count : " . sizeof($user_id_list));
    	return $user_id_list;
    }

    function getQueryList($input_list){
        $modified_input_list = array();
        $query_string = "";
        foreach ($input_list as $input) {
            array_push($modified_input_list, "'" . $input . "'");
        }
        $query_string = implode(",", $modified_input_list);
        return $query_string;
    }
    /*
    *	This function helps scheduler to update cases assignment 'Customer support executive Assignment Dynamic' to happen on a particular 	*		date based on the db value. 
    *		If no abscense is found, Update the role with all users found in Default 'Customer support executive Assignment'
    */
    function updateCaseAssignmentExcecutiveRole(){
        
        $this->logger_case_assign_role->log('debug', "--- START In UpdateCaseAssignmentExcecutiveRole in  ScheduledTasks at". date('Y-m-d H:i:s')."------");

        global $db, $sugar_config;
        $return_status = true;
    	$default_user_id_list = array();
    	$default_user_id_list = $this->getUserIdForThisRole($sugar_config['Customer_support_executive_Assignment']);
    	$default_user_id_list_str = $this->getQueryList($default_user_id_list);

    	if(empty($default_user_id_list_str)){
    		$this->logger_case_assign_role->log('debug', "Fetched Users count is zero. Ending the job. ");
            $this->logger_case_assign_role->log('debug', "-------------Scheduler ends---------------");
    		return false;
    	}
    	$date = date('Y-m-d');
        $query = "
	    	SELECT id, user_id, attendance_date, attendance_status, date_created, date_modified FROM cases_agents_attendance
			WHERE attendance_date = '$date'
			AND user_id in ($default_user_id_list_str)
			ORDER BY date_modified DESC";

        $this->logger_case_assign_role->log('debug', "check users on leave query: " . $query);
		$results = $db->query($query);

		//multiple values for a user on a same day can be found because of re upload. So to handle that we use visited array.
		$visited_users_id = array();
		$leave_users_id = array();

        //remove users who are on leave that day.
		while ($row = $db->fetchByAssoc($results)) {
			if(!in_array($row['user_id'], $visited_users_id)){
				array_push($visited_users_id, $row['user_id']);
				if($row['attendance_status'] == 'L'){
					array_push($leave_users_id, $row['user_id']);
				}
			}
		}
        
		$cases_to_be_assigned_to = array_diff($default_user_id_list, $leave_users_id);

        $this->logger_case_assign_role->log('debug', "Default User ID list: ".print_r($default_user_id_list,true));
        $this->logger_case_assign_role->log('debug', "On leave User ID list: ".print_r($leave_users_id,true));
        $this->logger_case_assign_role->log('debug', "User ID list for cases to be assigned : ".print_r($cases_to_be_assigned_to,true));

        $role_id = $this->getRoleIdFromName($sugar_config['Customer_support_executive_Assignment_Dynamic']);
        $return_status = $return_status and $this->removeUsersAssignedToRole($role_id);
        $return_status = $return_status and $this->addUsersToRole($cases_to_be_assigned_to, $role_id);

        $this->logger_case_assign_role->log('debug', "-------------Scheduler ends---------------");
        return $return_status;
    }

    function addUsersToRole($cases_to_be_assigned_to, $role_id){
        $return_status = true;
        foreach ($cases_to_be_assigned_to as $user_id) {
            $this->logger_case_assign_role->log('debug', "User ID : " . $user_id);
            $bean = BeanFactory::getBean('Users',$user_id);
            $this->logger_case_assign_role->log('debug', "User Name : " . $bean->name);
            $bean->load_relationship('aclroles');
            $status = $bean->aclroles->add($role_id);    
            $this->logger_case_assign_role->log('debug', "status after adding role : " . $status);   
            $return_status = $return_status and $status;
        }
        $this->logger_case_assign_role->log('debug', "Final return status after adding role : " . $return_status); 
        return $return_status;
    }


    function removeUsersAssignedToRole($role_id){
        $this->logger_case_assign_role->log('debug', "Remove all users assigned to this Role id : " . $role_id);
        require_once('modules/ACLRoles/ACLRole.php');
        $role = new ACLRole();
        $role->retrieve($role_id);
        $this->logger_case_assign_role->log('debug', "Role Name : " . $role->name);
        $role_users = $role->get_linked_beans( 'users','User');
        $return_status = true;
        foreach ($role_users as $user) {
            $this->logger_case_assign_role->log('debug', "User ID : " . $user->id);
            $user->load_relationship('aclroles');
            $status = $user->aclroles->delete($user, $role_id);
            $this->logger_case_assign_role->log('debug', "status after deleting role : " . $status);
            $return_status = $return_status and $status;
        }
        $this->logger_case_assign_role->log('debug', "Final return status after deleting role : " . $return_status); 
        return $return_status;
    }


    function getRoleIdFromName($role_name){
        global $db;
        $query = "
            SELECT id FROM acl_roles
            WHERE name = '$role_name'
        ";
        $this->logger_case_assign_role->log('debug', "Role ID fetch query : " . $query);
        $role_id = "";
        $results = $db->query($query);
        while ($row = $db->fetchByAssoc($results)) {
            if(!empty($row['id'])){
                $role_id = $row['id'];
            }
        }
        $this->logger_case_assign_role->log('debug', "Fetched Role ID : " . $role_id);
        return $role_id;
    }
    /**
     *  upload the mobile number and respective details to ozonetel
     *  irrespective of the response from api call. Return true.
     *  One execution for every 5 min
     */
    function uploadOzontelAutoScheduleCalls(){
        global $timedate;
        $response = true;
        $api_response = "";
        $api_response_code = "";
        $api_key = getenv("SCRM_OZONTEL_API_KEY");
        $url = "http://api1.cloudagent.in/cloudAgentRestAPI/index.php/AddCampaignBulkData/addBulkData/format/json";

        $logger = new CustomLogger('AutoScheduleCallsUpload');
	    $logger->log('debug', "--- START In uploadOzontelAutoScheduleCalls at ".date('Y-m-d h:i:s')."---");

        global $db, $sugar_config;
        //inserted time to sent is IST, now is GMT
        $fetch_query = "
            SELECT id, type, request_body
            FROM auto_schedule_calls
            WHERE time_to_send <= (NOW() + INTERVAL 330 MINUTE)
            AND is_sent = 0
            ORDER BY time_to_send
            LIMIT 1
        ";
        // echo "$fetch_query<br>";
        $result = $db->query($fetch_query);
        $id = "";
        $bulkData = "";
        $request_body = array();
        while ($row = $db->fetchByAssoc($result)) {
            $id = $row['id'];
            $type = $row['type'];
            $bulkData = $row['request_body'];
            print_r(json_decode($row['request_body'], true)); echo "<br><hr>";
        }
        if(empty($id)){
            $logger->log('debug', "No reports to send to ozontel, Ending the job");
            return true;    
        }
        $bulkData_array = array();
        $bulkData_array = unserialize(base64_decode($bulkData));
        // print_r($bulkData_array);echo "<br><hr>";
        $logger->log('debug', "Fetched ID : $id");
        $request_body["api_key"] = $api_key;
        $request_body["campaign_name"] = $sugar_config['ScheduleCalls_campaign_name'];
        $request_body["bulkData"] = json_encode($bulkData_array);
        // print_r($request_body); echo "<br>";
        $request_body_http_query = http_build_query($request_body);
        // print_r($request_body_http_query); echo "<br>";
        if(empty($request_body_http_query)){
            $logger->log('debug', "error while forming request_body_http_query : id=> $id");
            return false;
        }
        // die();
        require_once('custom/include/CurlReq.php');
        $cl = new CurlReq();
        $header = array("Content-Type: application/x-www-form-urlencoded");
        $api_response = $cl->curl_req($url, 'post', $request_body_http_query, $header);
        $api_response_arr = json_decode($api_response, true);
        $api_response_code = "";
        if(!empty($api_response_arr["message"]["Status"]) 
            && $api_response_arr["message"]["Status"] == "SuccessFully Updated"){
                $logger->log('debug', "API call success"); 
            $api_response_code = 200;
        }
        else{
            $logger->log('debug', "API call failed recieved failure message");   
            $logger->log('debug', "api_response : $api_response");
        }
        // echo "Response :: ";print_r($api_response);echo "<br>";
        // echo "Response array :: ";print_r($arr);echo "<br>";
        $update_query = "
            UPDATE auto_schedule_calls
            SET is_sent = 1,
                response = '$api_response',
                response_code = '$api_response_code',
                date_modified = NOW()
            WHERE id = '$id'
            ";
        $update_result = $db->query($update_query);
        if($update_result){
            $logger->log('debug', "API response update to db: Success - Query : $update_query");
        }
        else{
            $logger->log('debug', "API response update to db: Failed - Query : $update_query");
        }
        return $response;        
    }
    /**
     *  When all case agent have 60 cases in their bucket, new case will be assigned to admin.
     *  Sent a notification to mangal and dipali about the count of those cases
     */
    function notificationForCasesAssignedToAdmin(){
        $response = true;
        global $timedate, $db, $sugar_config;
        $this->logger_case_assigned_to_admin->log('debug', "-----Start in notificationForCasesAssignedToAdmin starts at " . $timedate->now() . "-----");

        $query = "
            SELECT COUNT(*) as 'count'
            FROM cases c
            WHERE state != 'Closed'
            AND assigned_user_id = '1'";

        $results = $db->query($query);
        $count = 0;
        while ($row = $db->fetchByAssoc($results)) {
           $count = $row['count'];
        }
        if(empty($count)){
            $this->logger_case_assigned_to_admin->log('debug', "No cases assigned to the admin right now");
            return $response;
        }
        $this->logger_case_assigned_to_admin->log('debug', "Number of cases assigned to the admin right now : $count");
        $to_email = array();
        $to_email_str = getenv("SCRM_CASE_EXCESS_ASSIGNMENT_NOTIFICATION");
        $to_email = explode(",", $to_email_str);
        fwrite($this->log, "\n mail sent to " . print_r($to_email,true));
        if(empty($to_email_str) || empty($to_email)){
            array_push($to_email, $sugar_config['ng_mangal_sarang']);
            array_push($to_email, $sugar_config['ng_dipali_londhe']);
            // array_push($to_email, "balayeswanth.b@neogrowth.in");
        }
        require_once('custom/include/SendEmail.php');
        $email = new SendEmail();
        $sub = "Cases Assigned to Admin";
        $now = date('Y-m-d h:i a');
        $url = (getenv('SCRM_SITE_URL')."/index.php?module=Cases&action=ListView");
        $body = "Hi Team, <br> No. of cases assigned to Admin at $now is $count <br>
        <br/>You may review this at:<br/><a href='".$url."'>".$url."</a>
        <hr>";
        $email_response = $email->send_email_to_user(   $sub,
                                                        $body,
                                                        $to_email,
                                                        null,
                                                        null,
                                                        null,
                                                        1
                                                    );
        if(empty($email_response)){
            $response = false;
        }
        return $response;
    }
}












