<?php
require_once 'modules/Cases/Case.php';
require_once 'custom/CustomLogger/CustomLogger.php';

class CustomCase extends aCase
{
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
            concat(first_name,' ',last_name) as created_by_user,
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

    function call_sns(){

		try{
        $date = date('Y-m-d H:i:s');
        $myfile=fopen("Logs/snscall.log","a");
        fwrite($myfile,"\n$date\n");
            $SnSclient = new SnsClient([
                //'profile' => 'SNS',
                'region' => 'ap-south-1',
                'version' => '2010-03-31'
            ]);
                $subject=$this->name;
                $description=$this->description;
                $number=$this->case_number;
            $message='{"subject": "'.$subject.'","description": "","description_html": "'.$description.'","case_id": '.$number.'}';
            $topic = 'arn:aws:sns:ap-south-1:854483613921:Invoke-CRM-Email-Automation-Lambda-UAT';

                $result = $SnSclient->publish([
                    'Message' => $message,
                    'TopicArn' => $topic,
                ]);
                fwrite($myfile,print_r($result,true));
            } catch (AwsException $e) {
                // output error message if fails
				error_log($e->getMessage());
				fwrite($myfile,$e);
		}
		catch (Exception $e) {
			// output error message if fails
			error_log($e->getMessage());
			fwrite($myfile,$e);
	    }
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
