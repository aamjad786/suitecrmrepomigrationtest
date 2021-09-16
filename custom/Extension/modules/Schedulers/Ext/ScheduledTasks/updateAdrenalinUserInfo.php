<?php

array_push($job_strings, 'updateAdrenalinUserInfo');

 function fetchUserInfoFromAdrenalin($last_run_date){
     $myfile=fopen("Logs/updateAdrenalineUser.log",'a');
	$GLOBALS['log']->debug('fetchUserInfoFromAdrenalin');
	try {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => "",
            CURLOPT_URL => "https://hrcloud.myadrenalin.com/WebAPI/NEOGROWTH/D26E59DDF39740B2B6789C26A1BBFBC5/DT_668/API0001/".$last_run_date."?type=json",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
        $results = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            fwrite($myfile, "\n".'cURL Error #:' . $err);
        }
        $results = (json_decode($results, true));
        return $results;
	} catch (Exception $e) {
        fwrite($myfile, "\n".'---Exception occured in fecthing adrenalin update user info' . $e->getMessage());
	}
}

function fetchLastRunDate($function_name){
   global $db;
   $myfile = fopen("Logs/updateAdrenalinUserInfo.log", "a");
   fwrite($myfile, "\n".'fetchLastRunDate');
   $last_run_query = "select last_run from schedulers where job = '$function_name' and deleted = 0 and status = 'Active'";
   $last_run_result = $db->query($last_run_query);
   $last_run_date = '';
   while($last_run_row = $db->fetchByAssoc($last_run_result)){ 
		$last_run_date = $last_run_row['last_run'];
   }
   //
   //if job is created & running for the first time. Modify the starting date if neccesssary.
   if(empty($last_run_date)){
   	$last_run_date = "2018-05-05 01:01:01";
   }
   return $last_run_date;    	
}

function getUserBean($user_name){
    $bean = BeanFactory::getBean('Users');
    $query = 'users.deleted=0 and users.user_name = "'.$user_name.'"';
    $items = $bean->get_full_list('',$query);
    if(!empty($items)){
        $items[0]->load_relationship('aclroles');
        return $items[0];
    }
    return null;
}

function fetchSgId($department_name){
    $myfile = fopen("Logs/updateAdrenalinUserInfo.log", "a");
    global $db;
    $departmentToSgMap = array(
        'SALES'          => 'Sales Team',
        );
    $sg_id = "";
    $department_name = strtoupper($department_name);
    if(isset($departmentToSgMap[$department_name])){
        $department_name = $departmentToSgMap[$department_name];
        $query = "select id from securitygroups where name = '$department_name'";
        $results = $db->query($query);
        while($row = $db->fetchByAssoc($results)){
            $sg_id = $row['id'];
        }
    }
    else{
        fwrite($myfile, "\nNo such sg for " . $department_name . " SG Updation failed\n");
    }
    return $sg_id;
}

function updateSecurityGroupForNewUser($user_bean, $userInfo){
    $myfile = fopen("Logs/updateAdrenalinUserInfo.log", "a");
    global $db;
    $query = "select securitygroup_id from securitygroups_users where user_id = '$user_bean->id'";
    $results = $db->query($query);
    $user_bean->load_relationship('SecurityGroups');
    $add_sg_id = fetchSgId($user_bean->department);
    while($row = $db->fetchByAssoc($results)){
        if(empty($row['securitygroup_id'])){
            fwrite($myfile, "\n"."securitygroup_id is null for $user_bean->id"."\n");
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
            fwrite($myfile, "Added ".$user_bean->id." to ".$add_sg_id);
        }
        else{
            fwrite($myfile, "\n---Unable add User to the given Security Group. Some error.");
        }
    }
    return true;

}
/*
    This function 
        ->updates reporting, email, designation, designation based :: roles & security group
            And Department
        ->Pull user from AD if not in CRM

    It doesnt compare previous values before updating. 
*/
function updateUserDetailsUtil($user_info, $pullUser){
    global $db;
    $myfile = fopen("Logs/updateAdrenalinUserInfo.log", "a");
    $user_info=array_change_key_case($user_info,CASE_LOWER);
    $usr = getUserBean(trim($user_info[strtolower('EMPLOYEE CODE')]));
    $mgr = getUserBean(trim($user_info[strtolower('REPORTING MANAGER')]));
    //Uncomment below pull user script once AD Utility works
	// if(!$usr){
	// 	if($pullUser->insertUser($usr_ng_id))
//               $usr = getUserBean($usr_ng_id);
//       }
	// if(!$mgr){
	// 	if($pullUser->insertUser($mgr_ng_id))
//               $mgr = getUserBean($mgr_ng_id);
//       }
    if($usr){
        $usr->load_relationship('aclroles');
        if($mgr){
            $usr->reports_to_id=$mgr->id;
            fwrite($myfile, "\n".'Added '.$usr->user_name.' reporting to '. $mgr->user_name);
        }
        else{
            fwrite($myfile, "\n".'---Failed reporting update for '.$usr->user_name.'. manager not present in CRM');   
        }
        if(isset($user_info[strtolower('DESIGNATION NAME')]) && !empty($user_info[strtolower('DESIGNATION NAME')])){
            $usr->designation = $user_info[strtolower('DESIGNATION NAME')];
            fwrite($myfile, "\n".'Added '.$usr->user_name.' designation '. $usr->designation);
        }
        else{
            fwrite($myfile, "\n".'---Failed designation update for '.$usr->user_name.'. no designation value in adrenalin data');   
        }
        if(isset($user_info[strtolower('Employee Exit Date')]) && !empty($user_info[strtolower('Employee Exit Date')])){
            $usr->last_date = date("Y-m-d H:i:s",strtotime($user_info[strtolower('Employee Exit Date')]));
            fwrite($myfile, "\n".'Added '.$usr->user_name.' Employee Exit Date '. $usr->last_date);
        }
        else{
            fwrite($myfile, "\n".'---Failed Employee Exit Date update for '.$usr->user_name.'. no Employee Exit Date value in adrenalin data');   
        }
        if(isset($user_info[strtolower('Reporting Manager emailID')]) && !empty($user_info[strtolower('Reporting Manager emailID')])){
            $usr->reportsto_email = $user_info[strtolower('Reporting Manager emailID')];
            fwrite($myfile, "\n".'Added '.$usr->user_name.' Reporting Manager emailID '. $usr->reportsto_email);
        }
        else{
            fwrite($myfile, "\n".'---Failed Reporting Manager emailID update for '.$usr->user_name.'. no Reporting Manager emailID value in adrenalin data');   
        }
        if(isset($user_info[strtolower('Reporting Manager designation')]) && !empty($user_info[strtolower('Reporting Manager designation')])){
            $usr->reportsto_designation = $user_info[strtolower('Reporting Manager designation')];
            fwrite($myfile, "\n".'Added '.$usr->user_name.' Reporting Manager designation '. $usr->reportsto_designation);
        }
        else{
            fwrite($myfile, "\n".'---Failed Reporting Manager designation update for '.$usr->user_name.'. no Reporting Manager designation value in adrenalin data');   
        }
        // if(empty(trim($usr->department)) || $usr->department == 'null'){
            if(isset($user_info[strtolower('DEPARTMENT NAME')]) && !empty($user_info[strtolower('DEPARTMENT NAME')])){
                if($usr->department != $user_info[strtolower('DEPARTMENT NAME')]){
                    fwrite($myfile, "\n".'Updating department from  '.$usr->department.' to '. $user_info[strtolower('DEPARTMENT NAME')]);
                    $usr->department = trim($user_info[strtolower('DEPARTMENT NAME')]);
                    fwrite($myfile, "\n".'Added '.$usr->user_name.' department '. $usr->department);
                }
            }
            else{
                fwrite($myfile, "\n".'---Failed department update for '.$usr->user_name.'. no department value in adrenalin data');
            }
            if(isset($user_info[strtolower('Sub Department')]) && !empty($user_info[strtolower('Sub Department')])){
                if($usr->sub_department != $user_info[strtolower('Sub Department')]){
                    fwrite($myfile, "\n".'Updating Sub Department from  '.$usr->sub_department.' to '. $user_info[strtolower('Sub Department')]);
                    $usr->sub_department = trim($user_info[strtolower('Sub Department')]);
                    fwrite($myfile, "\n".'Added '.$usr->user_name.' Sub department '. $usr->sub_department);
                }
            }
            else{
                fwrite($myfile, "\n".'---Failed Sub Department update for '.$usr->user_name.'. no Sub Department value in adrenalin data');
            }
        // }
        // else{
        //     fwrite($myfile, "\n"."Department is already present in CRM. User's department ".$usr->department);
        // }
        // if(empty(trim($usr->email1)) || $usr->email1 == 'null'){
            if(isset($user_info[strtolower('MAIL ID')]) && !empty($user_info[strtolower('MAIL ID')])){
                $mail_id_from_adrenalin = $user_info[strtolower('MAIL ID')];
                fwrite($myfile, "\nBefore trimming :: $mail_id_from_adrenalin");
                $trimmed_mail_id = preg_replace('/^\d+/u', '', $mail_id_from_adrenalin);
                fwrite($myfile, "\nAfter replace mail id is :: $trimmed_mail_id");
                $usr->email1 = $trimmed_mail_id;
            }
            else{
                fwrite($myfile, "\nMailed ID is empty - Error - failed");
            }
        // }
        // else{
        //     fwrite($myfile, "\n"."Mailed ID is already present in CRM. User's Mailed ID ".$usr->email1);
        // }
        if(strcasecmp($usr->department , "Sales") == 0){
            if(updateRoleForNewUser($usr,$user_info)){
                fwrite($myfile, "\n".'Added roles to the user '.$usr->user_name);
            }
            else{
                fwrite($myfile, "\n".'---Failed to add roles to the user '.$usr->user_name);
            }
            if(updateSecurityGroupForNewUser($usr,$user_info)){
                fwrite($myfile, "\n".'Added SG to the user '.$usr->user_name);
            }
            else{
                fwrite($myfile, "\n".'---Failed to add SG to the user '.$usr->user_name);
            }
        }
        if($user_info[strtolower('ACTIVE')] == "Inactive" && $usr->status != "Inactive"){
            fwrite($myfile, "\n".'Marked user as Inactive & Terminated :: '.$user_info[strtolower('EMPLOYEE CODE')]);
            $usr->status = "Inactive";
            $usr->employee_status = "Terminated";
        }

        $usr->address_city = $user_info[strtolower('LOCATION NAME')];
        $usr->address_state = $user_info[strtolower('REGION')];
        $usr->joining_date =  date("Y-m-d H:i:s",strtotime($user_info[strtolower('DATE OF JOINING')]));
        $usr->save();

        $q="select count(*) as count from  acl_roles_users where role_id='6fc94b5c-be92-de95-c481-5e26c223cfea' and user_id='$usr->id' and deleted=0";
        $result=$db->query($q);
        $count=0;
        while (($row = $db->fetchByAssoc($result)) != null) {
            $count=$row['count'];
        }
        if(empty($count) || $count==0 ||! isset($count))
        {
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
                if(empty($count) || $count==0 ||! isset($count))
                {

                    $query="update user_preferences SET contents = 'YTo0OntzOjEwOiJ1c2VyX3RoZW1lIjtzOjY6IlN1aXRlUiI7czo4OiJ0aW1lem9uZSI7czoxMjoiQXNpYS9Lb2xrYXRhIjtzOjI6InV0IjtpOjE7czo2OiJDYWxsc1EiO2E6MTE6e3M6NjoibW9kdWxlIjtzOjU6IkNhbGxzIjtzOjY6ImFjdGlvbiI7czo1OiJpbmRleCI7czoxMzoic2VhcmNoRm9ybVRhYiI7czoxMjoiYmFzaWNfc2VhcmNoIjtzOjU6InF1ZXJ5IjtzOjQ6InRydWUiO3M6Nzoib3JkZXJCeSI7czowOiIiO3M6OToic29ydE9yZGVyIjtzOjA6IiI7czoxMDoibmFtZV9iYXNpYyI7czoxMToiOTEyMjYyNTg3NDAiO3M6MjM6ImN1cnJlbnRfdXNlcl9vbmx5X2Jhc2ljIjtzOjE6IjAiO3M6MjA6ImZhdm9yaXRlc19vbmx5X2Jhc2ljIjtzOjE6IjAiO3M6MTU6Im9wZW5fb25seV9iYXNpYyI7czoxOiIwIjtzOjY6ImJ1dHRvbiI7czo2OiJTZWFyY2giO319' WHERE assigned_user_id='".$usr->id."' and category='global'";      
                    $db->query($query);
                }

        $designations=array(strtolower("Associate Manager - Customer Acquisition"),strtolower("Senior Associate Manager - Customer Acquisition"),strtolower("Area Sales Manager"),strtolower("Senior Area Sales Manager"));
            if (in_array(strtolower($usr->designation),$designations)){
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
        fwrite($myfile, "\n".'User not present in CRM. Try Login :: '.$user_info[strtolower('EMPLOYEE CODE')]);
        fwrite($myfile, "\n".'Failed to update :: '.$user_info[strtolower('EMPLOYEE CODE')].' reporting to '. $user_info[strtolower('REPORTING MANAGER')]);
    }
}

function updateUserDetails($userInfo){
    $myfile = fopen("Logs/updateAdrenalinUserInfo.log", "a");
	$results = true;
	require_once('PullUser.php');
	$pullUser = new PullUser();
	foreach ($userInfo as $user) {
		try{
			updateUserDetailsUtil($user, $pullUser);	
		}
		catch(Exception $e){
            fwrite($myfile, "\n".'---Failed to pull and update user data :: ' . trim($user['EMPLOYEE CODE']));
			$results = false;
		}
	}
	return $results;
}
//Scheculer Starting point 
function updateAdrenalinUserInfo($override_last_run_date=null)
{
    $myfile = fopen("Logs/updateAdrenalinUserInfo.log", "a");
    fwrite($myfile, "\n-------------updateAdrenalinUserInfo::Starts------------\n");
    global $timedate;
    fwrite($myfile, "\n"."time - ".$timedate->now());
    $results = false;
    $last_run_date = fetchLastRunDate("function::updateAdrenalinUserInfo");
    fwrite($myfile, "\n"."Last Run Date :: $last_run_date"); 
    $last_run_date = DateTime::createFromFormat('Y-m-d H:i:s',$last_run_date);
    $last_run_date = $last_run_date->format('YmdHis');
    // $last_run = "2008-05-05 01:01:01";
    //Comment below line if we dont want to fetch full user list
    //$override_last_run_date = "20080505010101";
    if(!empty($override_last_run_date)){
        fwrite($myfile, "\n---------------------------------------------------------------------------------------------");
        fwrite($myfile, "\n"."Overriden User Provided last run date :: " . $override_last_run_date);
        fwrite($myfile, "\n---------------------------------------------------------------------------------------------");
        $last_run_date = $override_last_run_date;
    }
    fwrite($myfile, "\n"."Last run date formated:: ". $last_run_date);
    $userInfo = fetchUserInfoFromAdrenalin($last_run_date);
    if(empty($userInfo)){
        fwrite($myfile, "\n".'userInfo fetched from adrealin is empty. No updated Records found');
    	return true;
    }	
    $results = updateUserDetails($userInfo); 
    //job running for the first time, fetch all user details
    if($last_run_date == "20180505010101"){
        $userInfo = fetchUserInfoFromAdrenalin("20080505010101");
    }
    updateAdrenalinCacheTable($userInfo,$last_run_date);
    fwrite($myfile, "\n-------------updateAdrenalinUserInfo::Ends------------\n");
    fclose($myfile);
    return $results;
}

function updateAdrenalinCacheTable($userInfo,$last_run_date){
    $myfile = fopen("Logs/updateAdrenalinUserInfo.log", "a");
    fwrite($myfile, "\n".'updateAdrenalinCacheTable');
	global $db;
	try{
		foreach ($userInfo as $user) {
			$employee_code = trim($user['EMPLOYEE CODE']);
			$company_name = trim($user['COMPANY NAME']);
			$mail_id = trim($user['MAIL ID']);
			$reporting_manager = trim($user['REPORTING MANAGER']);
			$designation_name = trim($user['DESIGNATION NAME']);
   			$department_name = trim($user['DEPARTMENT NAME']);
            $modified_on = DateTime::createFromFormat('YmdHis',$last_run_date);
   			$modified_on = $modified_on->format('Y-m-d H:i:s');

			$query = "insert into adrenalin_user_info 
			(employee_code, company_name, mail_id, 
			reporting_manager, designation_name, department_name, modified_on) 
			values('$employee_code','$company_name','$mail_id','$reporting_manager', '$designation_name', '$department_name','$modified_on')
			ON DUPLICATE KEY UPDATE 
				company_name='$company_name',
				mail_id='$mail_id',
				reporting_manager='$reporting_manager',
				designation_name='$designation_name',
                department_name='$department_name',
				modified_on='$modified_on'
				";
			$result = $db->query($query);
			if($result){
                fwrite($myfile, "\n".'adrenalin cache table update success');
			}
            else{
                fwrite($myfile, "\n".'---Failed to insert data into adrenalin_user_info :: employee code :: ' . trim($user['EMPLOYEE CODE']));
            }
		}
	}	
	catch(Exception $ex){
        fwrite($myfile, "\n".'---Failed to insert data into adrenalin_user_info :: ' . trim($user['EMPLOYEE CODE']));
	}
}

function fetchRoleIdFromName($role_name){
    global $db;
    $query = "select id from acl_roles where name = '$role_name'";
    $results = $db->query($query);
    $role_id = "";
    while($row = $db->fetchByAssoc($results)){
        $role_id = $row['id'];
    }
    return $role_id;
}

	  function updateRoleForNewUser($user, $userInfo){
    $user = getUserBean($userInfo['EMPLOYEE CODE']);
    $myfile = fopen("Logs/updateAdrenalinUserInfo.log", "a");
    $designationToRoleMap = array(
        'Associate Manager - Customer Acquisition'          => 'Customer Acquisition Manager',
        'Area Sales Manager'                                => 'Customer Acquisition Manager',
        'Area Manager - Renewals'                           => 'Customer Acquisition Manager',
        'Area Collection Manager'                           => 'Customer Acquisition Manager',

        'Channel Sales Manager'                             => 'Customer Acquisition Manager',        
        'Channel Manager-FS2'                               => 'Customer Acquisition Manager',

        'Executive - Telecalling'                           => 'Customer Acquisition Manager',

        'Relationship Manager - Telesales'                  => 'Customer Acquisition Manager',
        'Senior Associate Manager - Customer Acquisition'   => 'Customer Acquisition Manager',
        'Senior Area Sales Manager'                         => 'Customer Acquisition Manager',
        'Senior Executive - Bank Coordination'              => 'Customer Acquisition Manager',
        'Senior Executive - Sales Coordinator'              => 'Customer Acquisition Manager',


        'City Manager - Sales'                              => 'City Manager',
        

        'Channel Development Manager - Insurance'           => 'Cluster Manager',
        'Cluster Manager - Sales'                           => 'Cluster Manager',
        'Cluster Manager - Direct Sales'                    => 'Cluster Manager',
        'Cluster Credit Manager'                            => 'Cluster Manager',
        'Cluster Manager - Renewals'                        => 'Cluster Manager',
        'Cluster Manager-Direct Sales'                      => 'Cluster Manager',
        'Cluster Manager - FS2'                             => 'Cluster Manager',
        'Senior Manager - Sales Training'                   => 'Cluster Manager',


        'Regional Sales Manager'                            => 'Regional Manager',
        'Regional Manager - Finance & Accounts'             => 'Regional Manager',
        'Regional Credit Manager'                           => 'Regional Manager',
        'Regional Manager - Collection'                     => 'Regional Manager',

        'Manager- Sales Force Automation'                   => 'Regional Manager',
        'Manager - Sales Operations'                        => 'Regional Manager',                

        'Associate Vice President - Sales'                  => 'Regional Manager',
        'Associate Vice President- Telesales'               => 'Regional Manager',
        'Assistant Vice President - Direct Sales'           => 'Regional Manager',
        'Assistant Vice President - Business Alliances'     => 'Regional Manager',
        'Senior Manager - Sales'                            => 'Regional Manager',
        'Senior Manager - Direct Sales'                     => 'Regional Manager',
        'Senior Manager - Merchant Account'                 => 'Regional Manager',
        'Senior Manager - Collections'                      => 'Regional Manager',
        'Senior Manager - Technology'                       => 'Regional Manager',
        'Senior Manager - Human Resource'                   => 'Regional Manager',
        'Senior Manager - Marketing'                        => 'Regional Manager', 
        'Senior Manager - Sales and Strategy'               => 'Regional Manager', 
        'Senior Manager - Sales & Strategy'                 => 'Regional Manager', 
        'Strategic Alliance'                                => 'Regional Manager', 
        'Manager - Sales Operations & Analytics'            => 'Regional Manager',


        'Assistant Vice President - Sales'                  => 'Zonal Manager',
        'Manager- Business Alliance'                        => 'Zonal Manager',
        'Manager - Business Alliances'                      => 'Zonal Manager',
        'National Sales Manager - Corporate Channel'        => 'Zonal Manager',
        'Sales Coordinator'                                 => 'Zonal Manager',
        'Senior Vice President - Sales'                     => 'Zonal Manager',
        'Zonal Manager - Finance & Accounts'                => 'Zonal Manager',
        'Zonal Sales Manager'                               => 'Zonal Manager',
        'Zonal Business Manager'                            => 'Zonal Manager'

        );
    try{
      $role = $designationToRoleMap[trim($userInfo['DESIGNATION NAME'])];
      if(empty($role)){
        fwrite($myfile, "\n---DESIGNATION NOT FOUND" . $userInfo['DESIGNATION NAME'] . "\n");
        //fwrite($myfile, "\nNG ID = $user->user_name \n");
        //fwrite($myfile, "\nNAME  = $user->full_name \n");
      }
      $roleID = fetchRoleIdFromName($role);
      if (empty($roleID)) {
          fwrite($myfile, "\n---No such role called " . $role . " Role Updation failed\n");
          return false;
      }
      fwrite($myfile, "\n" .'designation To Role Map :: ' . trim($userInfo['DESIGNATION NAME']) . "==>" . $role . "==>" . $roleID. "\n");
      $user->load_relationship('aclroles');
      $status = $user->aclroles->add($roleID);
      if($status){
        $GLOBALS['log']->debug('Added '.$user->id.' to '.$roleID);
      }
      else{
        fwrite($myfile, "\n---Unable add user to the given role. Some error. $user->id\n");
        return false;
      }
    }
    catch (Exception $e) {
      fwrite($myfile, "\n---Exception occured in UserRoleAssignment execution" . $e->getMessage() . "\n");
      return false;
    }
    return true;
  }
