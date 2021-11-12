<?php

require_once 'custom/CustomLogger/CustomLogger.php';
array_push($job_strings, 'updateAdrenalinUserInfo');
global $logger;
$logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
//Scheculer Main Function
function updateAdrenalinUserInfo($override_last_run_date=null)
{
    global $logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $results = false;

    $logger->log('debug', '<======================STARTED==========================>');

    $last_run_date = fetchLastRunDate("function::updateAdrenalinUserInfo");

    $logger->log('debug', "Last Run Date :: $last_run_date");

    $last_run_date = DateTime::createFromFormat('Y-m-d H:i:s', $last_run_date);
    $last_run_date = $last_run_date->format('YmdHis');
    // $last_run = "2008-05-05 01:01:01";
    //Comment below line if we dont want to fetch full user list
    //$override_last_run_date = "20080505010101";

    if (!empty($override_last_run_date)) {
        $logger->log('debug', "------------------------------------------------------------------------------------------");
        $logger->log('debug', "Overriden User Provided last run date :: " . $override_last_run_date);
        $logger->log('debug', "------------------------------------------------------------------------------------------");
        $last_run_date = $override_last_run_date;
    }
    $logger->log('debug', "Last run date formated:: " . $last_run_date);
    // Actual Fetching started
    $userInfo = fetchUserInfoFromAdrenalin($last_run_date);

    if (empty($userInfo)) {

        $logger->log('debug', 'UerInfo fetched from adrealin is empty. No updated Records found');
        $logger->log('debug', '<======================END==========================>');

        return true;
    }
    
    $results = updateUserDetails($userInfo);

    //=======================================Need to uncomment ==================================================== 
    
    //job running for the first time, fetch all user details
    
    // if ($last_run_date == "20180505010101") {
    //     $userInfo = fetchUserInfoFromAdrenalin("20080505010101");
    // }
    updateAdrenalinCacheTable($userInfo, $last_run_date);

    $logger->log('debug', '<======================END==========================>');

    return $results;
}

function fetchLastRunDate($function_name){
    global $db, $logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $logger->log('debug',  'fetchLastRunDate called');

    $last_run_query = "select last_run from schedulers where job = '$function_name' and deleted = 0 and status = 'Active'";
    $last_run_result = $db->query($last_run_query);
    
    $last_run_date = '';
    while ($last_run_row = $db->fetchByAssoc($last_run_result)) {
        $last_run_date = $last_run_row['last_run'];
    }

    //if job is created & running for the first time. Modify the starting date if neccesssary.
    if (empty($last_run_date)) {
        $last_run_date = "2018-05-05 01:01:01";
    }
    return $last_run_date;
}

function fetchUserInfoFromAdrenalin($last_run_date){
    global $logger,$sugar_config;;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $logger->log('debug', 'fetchUserInfoFromAdrenalin Called....!');
    try {
        // $curl   = curl_init();
        // $url    = getenv('Adrenalin_Api'); //.$last_run_date."?type=json"; // $sugar_config['Adrenalin Api'];
        // curl_setopt_array($curl, array(
        //     CURLOPT_PORT => "",
        //     CURLOPT_URL => "$url" . $last_run_date . "?type=json",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => array(
        //         "cache-control: no-cache",
        //         "content-type: application/json"
        //     ),
        // ));
        
        // $results = curl_exec($curl);
        // $err = curl_error($curl);
        
        // curl_close($curl);
        
        $url            = getenv('Adrenalin_Api'). $last_run_date . "?type=json";
        $headers        = array(
            "cache-control: no-cache",
            "content-type: application/json"
        );
        $max_redirects  = 10;
        $timeout        = 30;

        require_once('custom/include/CurlReq.php');
        $curl_req       = new CurlReq();

        $result         = $curl_req->curl_req($url, 'get', '', $headers, '', '', $max_redirects, $timeout, true);
        $results   	    = $result['response'];
        $err            = $result['error'];

        if ($err) {
            $logger->log('error', 'cURL Error:' . $err);
        }
        $logger->log('debug', "curl URL : $url" . $last_run_date . "?type=json");
        $logger->log('debug', 'Adrenaline API Response: ' . var_export($results, true));
        
        $results = (json_decode($results, true));
        
        return $results;

    } catch (Exception $e) {
        $logger->log('error', 'Exception occured in fecthing adrenalin update user info' . $e->getMessage());
    }
}

function updateUserDetails($userInfo){
    global $logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
	$results = true;
	
    foreach ($userInfo as $user) {
        try {
            updateUserDetailsUtil($user);
        } catch (Exception $e) {
            $logger->log('error', 'Failed to Pull or Update user data: ' . trim($user['EMPLOYEE CODE']));
            $results = false;
        }
	}
	return $results;
}

function updateUserDetailsUtil($user_info){
    global $db, $logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    
    $user_info=array_change_key_case($user_info,CASE_LOWER);
    
    $usr = getUserBean(trim($user_info[strtolower('EMPLOYEE CODE')]));
    
    $mgr = getUserBean(trim($user_info[strtolower('REPORTING MANAGER')]));

    //Uncomment below pull user script once AD Utility works
    /*require_once('PullUser.php');
	$pullUser = new PullUser();
    $logger->log('debug', 'Pull user-----------' . $pullUser);
	 if(!$usr){
	 	if($pullUser->insertUser($usr_ng_id))
                   $usr = getUserBean($usr_ng_id);
                   $logger->log('debug', 'Pull user id-----------' . $usr);       
           }
	 if(!$mgr){
	 	if($pullUser->insertUser($mgr_ng_id))
                   $mgr = getUserBean($mgr_ng_id);
           }*/

    if($usr){

        $usr->load_relationship('aclroles');

        // ==============Setting Up User Fields From Adrinaline Response =============

        // Updating Reporting Field
        if ($mgr) {
            $usr->reports_to_id = $mgr->id;
            $logger->log('debug', 'Added ' . $usr->user_name . ' reporting to ' . $mgr->user_name);
        } else {
            $logger->log('error', 'Failed reporting update for ' . $usr->user_name . '. manager not present in CRM');
        }

        // Updating Designation Field
        if (isset($user_info[strtolower('DESIGNATION NAME')]) && !empty($user_info[strtolower('DESIGNATION NAME')])) {
            $usr->designation_c = $user_info[strtolower('DESIGNATION NAME')];
            $logger->log('debug', 'Added ' . $usr->user_name . ' designation ' . $usr->designation_c);
        } else {
            $logger->log('error', 'Failed designation update for ' . $usr->user_name . '. no designation value in adrenalin data');
        }

        // Updating Employee Exit Date Field
        if (isset($user_info[strtolower('Employee Exit Date')]) && !empty($user_info[strtolower('Employee Exit Date')])) {
            $usr->last_date_c = date("Y-m-d H:i:s", strtotime($user_info[strtolower('Employee Exit Date')]));
            $logger->log('debug', 'Added ' . $usr->user_name . ' Employee Exit Date ' . $usr->last_date_c);
        } else {
            $logger->log('error', 'Failed Employee Exit Date update for ' . $usr->user_name . '. no Employee Exit Date value in adrenalin data');
        }

        // Updating Reporting Manager emailID Field
        if (isset($user_info[strtolower('Reporting Manager emailID')]) && !empty($user_info[strtolower('Reporting Manager emailID')])) {
            $usr->reportsto_email_c = $user_info[strtolower('Reporting Manager emailID')];
            $logger->log('debug', 'Added ' . $usr->user_name . ' Reporting Manager emailID ' . $usr->reportsto_email_c);
        } else {
            $logger->log('error', '---Failed Reporting Manager emailID update for ' . $usr->user_name . '. no Reporting Manager emailID value in adrenalin data');
        }

        // Updating Reporting Manager designation Field
        if (isset($user_info[strtolower('Reporting Manager designation')]) && !empty($user_info[strtolower('Reporting Manager designation')])) {
            $usr->reportsto_designation_c = $user_info[strtolower('Reporting Manager designation')];
            $logger->log('debug', 'Added ' . $usr->user_name . ' Reporting Manager designation ' . $usr->reportsto_designation_c);
        } else {
            $logger->log('error', 'Failed Reporting Manager designation update for ' . $usr->user_name . '. no Reporting Manager designation value in adrenalin data');
        }

        // Updating DEPARTMENT NAME Field
        if (isset($user_info[strtolower('DEPARTMENT NAME')]) && !empty($user_info[strtolower('DEPARTMENT NAME')])) {
            if ($usr->department != $user_info[strtolower('DEPARTMENT NAME')]) {
                $logger->log('debug', 'Updating department from  ' . $usr->department . ' to ' . $user_info[strtolower('DEPARTMENT NAME')]);
                $usr->department = trim($user_info[strtolower('DEPARTMENT NAME')]);
                $logger->log('debug', 'Added ' . $usr->user_name . ' department ' . $usr->department);
            }
        } else {
            $logger->log('error', 'Failed department update for ' . $usr->user_name . '. no department value in adrenalin data');
        }

        // Updating Sub Department Field
        if (isset($user_info[strtolower('Sub Department')]) && !empty($user_info[strtolower('Sub Department')])) {
            if ($usr->sub_department_c != $user_info[strtolower('Sub Department')]) {
                $logger->log('debug', 'Updating Sub Department from  ' . $usr->sub_department_c . ' to ' . $user_info[strtolower('Sub Department')]);
                $usr->sub_department_c = trim($user_info[strtolower('Sub Department')]);
                $logger->log('debug', 'Added ' . $usr->user_name . ' Sub department ' . $usr->sub_department_c);
            }
        } else {
            $logger->log('error','Failed Sub Department update for ' . $usr->user_name . '. no Sub Department value in adrenalin data'
            );
        }

        // Updating MAIL ID Field
        if (isset($user_info[strtolower('MAIL ID')]) && !empty($user_info[strtolower('MAIL ID')])) {

            $mail_id_from_adrenalin = $user_info[strtolower('MAIL ID')];
            $logger->log('debug', "Before trimming :: $mail_id_from_adrenalin");

            $trimmed_mail_id = preg_replace('/^\d+/u', '', $mail_id_from_adrenalin);
            $logger->log('debug', "After replace mail id is :: $trimmed_mail_id");

            $usr->email1 = $trimmed_mail_id;
        } else {
            $logger->log('error', "Failed Mailed ID update. Mailed ID is empty.");
        }

        // Updating User Role
        if (updateRoleForNewUser($usr, $user_info)) {
            $logger->log('debug', 'Added roles to the user ' . $usr->user_name);
        } else {
            $logger->log('error', 'Failed to add roles to the user ' . $usr->user_name);
        }

        // Updating Security Group
        if (updateSecurityGroupForNewUser($usr, $user_info)) {
            $logger->log('debug', 'Added SG to the user ' . $usr->user_name);
        } else {
            $logger->log('error', 'Failed to add Security Group to the user ' . $usr->user_name);
        }
        
        // Updating User status based on Employee is active or not
        if($user_info[strtolower('ACTIVE')] == "Inactive" && $usr->status != "Inactive"){
            $logger->log('debug', 'Marked user as Inactive & Terminated :: '.$user_info[strtolower('EMPLOYEE CODE')]);
            $usr->status = "Inactive";
            $usr->employee_status = "Terminated";
        }

        $usr->address_city = $user_info[strtolower('LOCATION NAME')];
        $usr->address_state = $user_info[strtolower('REGION')];
        $usr->joining_date_c =  date("Y-m-d H:i:s",strtotime($user_info[strtolower('DATE OF JOINING')]));

        $usr->save();
        
        // ==============Setting Up User Fields From Adrinaline Response END ============= 
        
        // These are Bug fixes need to re visit

        $q="select count(*) as count from  acl_roles_users where role_id='6fc94b5c-be92-de95-c481-5e26c223cfea' and user_id='$usr->id' and deleted=0";
        $result=$db->query($q);
        $count=0;
        while (($row = $db->fetchByAssoc($result)) != null) {
            $count=$row['count'];
        }
        if(empty($count) || $count==0 ||! isset($count)) {
            $roleid=create_guid();
            $query="insert into acl_roles_users (id,role_id,user_id,deleted) values ('$roleid','6fc94b5c-be92-de95-c481-5e26c223cfea','$usr->id',0)";
            $db->query($query);
        }

        $q="select count(*) as count from  acl_roles_users where role_id='978da784-78e3-5c78-ff7a-57e10a137412' and user_id='$usr->id' and deleted=0";
        $result=$db->query($q);
        $count=0;
        while (($row = $db->fetchByAssoc($result)) != null) {
            $count=$row['count'];
        }
        if(empty($count) || $count==0 ||! isset($count)) {
            $query="update user_preferences SET contents = 'YTo0OntzOjEwOiJ1c2VyX3RoZW1lIjtzOjY6IlN1aXRlUiI7czo4OiJ0aW1lem9uZSI7czoxMjoiQXNpYS9Lb2xrYXRhIjtzOjI6InV0IjtpOjE7czo2OiJDYWxsc1EiO2E6MTE6e3M6NjoibW9kdWxlIjtzOjU6IkNhbGxzIjtzOjY6ImFjdGlvbiI7czo1OiJpbmRleCI7czoxMzoic2VhcmNoRm9ybVRhYiI7czoxMjoiYmFzaWNfc2VhcmNoIjtzOjU6InF1ZXJ5IjtzOjQ6InRydWUiO3M6Nzoib3JkZXJCeSI7czowOiIiO3M6OToic29ydE9yZGVyIjtzOjA6IiI7czoxMDoibmFtZV9iYXNpYyI7czoxMToiOTEyMjYyNTg3NDAiO3M6MjM6ImN1cnJlbnRfdXNlcl9vbmx5X2Jhc2ljIjtzOjE6IjAiO3M6MjA6ImZhdm9yaXRlc19vbmx5X2Jhc2ljIjtzOjE6IjAiO3M6MTU6Im9wZW5fb25seV9iYXNpYyI7czoxOiIwIjtzOjY6ImJ1dHRvbiI7czo2OiJTZWFyY2giO319' WHERE assigned_user_id='".$usr->id."' and category='global'";      
            $db->query($query);
        }

        $designations=array(strtolower("Associate Manager - Customer Acquisition"),strtolower("Senior Associate Manager - Customer Acquisition"),strtolower("Area Sales Manager"),strtolower("Senior Area Sales Manager"));
        if (in_array(strtolower($usr->designation_c),$designations)){
            $q="select count(*) as count from  acl_roles_users where role_id='978da784-78e3-5c78-ff7a-57e10a137412' and user_id='$usr->id' and deleted=0";
            $result=$db->query($q);
            $count=0;
            while (($row = $db->fetchByAssoc($result)) != null) {
                $count=$row['count'];
            }
            if(empty($count) || $count==0 ||! isset($count))
            {
                $roleid=create_guid();
                $query="insert into acl_roles_users values('$roleid','978da784-78e3-5c78-ff7a-57e10a137412','$usr->id','','0')";
                $db->query($query);
            }
        }

	}else{
        $logger->log('debug','User not present in CRM. Try Login :: '.$user_info[strtolower('EMPLOYEE CODE')]);
        $logger->log('debug','Failed to update :: '.$user_info[strtolower('EMPLOYEE CODE')].' reporting to '. $user_info[strtolower('REPORTING MANAGER')]);
    }
}

function getUserBean($user_name){
    global $logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $bean = BeanFactory::getBean('Users');

    $query = 'users.deleted=0 and users.user_name = "'.$user_name.'"';
    $logger->log('debug', "Getuserbean Query: ".$query);

    $items =$bean->get_full_list('',$query);
    $logger->log('debug', "Getuserbean Result: ".json_encode($items));
    
    if(!empty($items)){
      //  $logger->log('debug', "Getuserbean Result: ".print_r($items[0],true));
        $items[0]->load_relationship('aclroles');
        
        return $items[0];
    }
    return null;
}

// Functions Realted to User Role Assignment
function updateRoleForNewUser($user, $userInfo) {
    global $logger, $sugar_config;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $user = getUserBean($userInfo[strtolower('EMPLOYEE CODE')]);
    $logger->log('debug', "updateRoleForNewUser: " . $userInfo['EMPLOYEE CODE'] );

    $designationToRoleMap = $sugar_config['designationToRoleMap'];

    try {

        $role = $designationToRoleMap[trim($userInfo[strtolower('DESIGNATION NAME')])];

        if (empty($role)) {
            $logger->log('error', "DESIGNATION NOT FOUND" . $userInfo['DESIGNATION NAME'] . "\n"); 
        }

        $roleID = fetchRoleIdFromName($role);

        if (empty($roleID)) {
            $logger->log('error', "No such role called " . $role . " Role Updation failed");
            return false;
        }

        $logger->log('debug', 'designation To Role Map :: ' . trim($userInfo['DESIGNATION NAME']) . "==>" . $role . "==>" . $roleID);
        
       $user->load_relationship('aclroles');

    $status = $user->aclroles->add($roleID);
        
        if($status){
            $logger->log('debug', 'Added '.$user->id.' to '.$roleID);
        }
        else{
            $logger->log('error', "Unable add user to the given role. Some error. $user->id");
            return false;
        }

    } catch (Exception $e) {
        $logger->log('error', "Exception occured in UserRoleAssignment execution" . $e->getMessage() . "\n");
        return false;
    }

    return true;
}

function fetchRoleIdFromName($role_name){
    global $db, $logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $query = "select id from acl_roles where name = '$role_name'";
    $results = $db->query($query);

    $logger->log('debug', "FetchRoleIdFromName Result: " . json_encode($results));

    $role_id = "";
    while ($row = $db->fetchByAssoc($results)) {
        $role_id = $row['id'];
        $logger->log('debug', "RoleIdFromName:" . $role_id);
    }

    return $role_id;
   
}

// Functions Realted to Security Group Assignment
function updateSecurityGroupForNewUser($user_bean, $userInfo){
    global $db, $logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $query = "select securitygroup_id from securitygroups_users where user_id = '$user_bean->id'";
    $results = $db->query($query);

    $user_bean->load_relationship('SecurityGroups');
    
    // Fetching Security Group by department
    $add_sg_id = fetchSgId($user_bean->department);
    
    while($row = $db->fetchByAssoc($results)){
        if(empty($row['securitygroup_id'])){
            $logger->log('error',"securitygroup_id is null for $user_bean->id"."\n");
            continue;
        }
        if(!empty($add_sg_id) && $add_sg_id == $row['securitygroup_id']){
            continue;
        }
        //DONT DELETE THE EXISTING SECURITY GROUPS
        // $status = $user_bean->SecurityGroups->delete($user_bean, $row['securitygroup_id']);
        // if($status){
        //     fwrite($myfile, "\n".'Deleted SG '.$user_bean->id.' to '.$row['securitygroup_id'] . "\n");
        // }
        // else{
        //     fwrite($myfile, "\n---Unable delete SG for $user_bean->user_name . Some error. $user_bean->id\n");
        // }
    }
    if(!empty($add_sg_id)){
        
        $status = $user_bean->SecurityGroups->add($add_sg_id);
        
        if($status){
            $logger->log('debug',"Added ".$user_bean->id." to ".$add_sg_id);
        }
        else{
            $logger->log('debug',"Unable add User to the given Security Group. Some error.");
        }
    }
    return true;

}

function fetchSgId($department_name){
    global $db, $logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $sg_id = "";

    $departmentToSgMap = array(
        'SALES'          => 'Sales Team',
    );

    $department_name = strtoupper($department_name);

    if (isset($departmentToSgMap[$department_name])) {
        $department_name = $departmentToSgMap[$department_name];
        $query = "select id from securitygroups where name = '$department_name'";

        $results = $db->query($query);
        while ($row = $db->fetchByAssoc($results)) {
            $sg_id = $row['id'];
            $logger->log('debug', "Security Group ID: " . $sg_id);
        }
    } else {
        $logger->log('debug', "No such Security Group for " . $department_name . " SG Updation failed");
    }

    return $sg_id;
}

function updateAdrenalinCacheTable($userInfo,$last_run_date=null){
    global $db ,$logger;
    $logger = new CustomLogger('UpdateAdrenalinUserInfoScheduler');
    $logger->log('debug','UpdateAdrenalinCacheTable Started: ');

    try {
        foreach ($userInfo as $user) {

            $employee_code = trim($user['EMPLOYEE CODE']);
            $company_name = trim($user['COMPANY NAME']);
            $mail_id = trim($user['MAIL ID']);
            $reporting_manager = trim($user['REPORTING MANAGER']);
            $designation_name = trim($user['DESIGNATION NAME']);
            $department_name = trim($user['DEPARTMENT NAME']);
             //$modified_on = $modified_on->format('Y-m-d H:i:s');
            // $modified_on = DateTime::createFromFormat('YmdHis', $last_run_date)->format('Y-m-d h:i:s'); 
            $query = "insert into adrenalin_user_info 
                      (employee_code, company_name, mail_id, reporting_manager, designation_name, department_name, modified_on) 
			          values('$employee_code','$company_name','$mail_id','$reporting_manager', '$designation_name', '$department_name',CURRENT_TIMESTAMP())
			          ON DUPLICATE KEY UPDATE 
				        company_name='$company_name',
				        mail_id='$mail_id',
				        reporting_manager='$reporting_manager',
				        designation_name='$designation_name',
                        department_name='$department_name',
				        modified_on=CURRENT_TIMESTAMP()
				    ";
                    $logger->log('debug','Insert Query: '.$query);
            $result = $db->query($query);
            if ($result) {
                $logger->log('debug','Adrenalin cache table update success');
            } else {
                $logger->log('debug','Failed to insert data into adrenalin_user_info :: employee code :: ' . trim($user['EMPLOYEE CODE']));
            }
        }
    } catch (Exception $ex) {
        $logger->log('error','Failed to insert data into adrenalin_user_info :: ' . trim($user['EMPLOYEE CODE']));
    }
}

// updateAdrenalinUserInfo();