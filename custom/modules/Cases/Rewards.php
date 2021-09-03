<?php

class Rewards{

	var $dept_db_map ;


    function __construct(){
    	$this->dept_db_map = array(
			"Administration"		=> "administration",
			"Alliances" 			=> "alliances",
			"Analytics" 			=> "analytics",
			"Business Excellence" 	=> "business_excellence",
			"Collection" 			=> "collection",
			"Collections" 			=> "collections",
			"Compliance" 			=> "compliance",
			"Corporate Affairs" 	=> "corporate_affairs",
			"Corporate Services" 	=> "corporate_services",
			"Credit" 				=> "credit",
			"Customer Experience" 	=> "customer_experience",
			"Customer Support" 		=> "customer_support",
			"Digital Marketing" 	=> "digital_marketing" ,
			"Engineering" 			=> "engineering",
			"Epayments" 			=> "epayments",
			"Finance & Accounts"	=> "finance_and_accounts",
			"HR" 					=> "hr",
			"Human Resource" 		=> "hr",
			"IT" 					=> "it",
			"IT Support" 			=> "it_support",
			"Legal" 				=> "legal",
			"Marketing" 			=> "marketing",
			"Operations" 			=> "operations",
			"Product" 				=> "products",
			"Products" 				=> "products",
			"Renewals" 				=> "renewals",
			"RePortal" 				=> "rePortal",
			"Sales" 				=> "sales",
			"Strategy" 				=> "strategy",
			"Telesales" 			=> "telesales",
			"Treasury" 				=> "treasury",
    	);
    }

	function updateRewards($bean, $event, $arguments){

		if(empty($bean->fetched_row)){
            $this->createNewRewardsRow($bean->id);
            return;
        }
        if($bean->fetched_row['state'] == "closed" && $bean->state=="open"){
        	//reopen logic
        	$this->updateRewardsRow($bean->id,null,null,$now);
        }
        if($bean->fetched_row['state'] != "closed" && $bean->state=="closed"){
        	//close logic
        	$this->updateRewardsRow($bean->id,null,null,$now);
        	$assigned_user_id = $bean->assigned_user_id;
        }
        $old_assigned_user_id = "";
        $new_assigned_user_id = $bean->assigned_user_id;
        if(isset($bean->fetched_rel_row['assigned_user_id'])){
        	$old_assigned_user_id = $bean->fetched_rel_row['assigned_user_id'];	
        }	
		$GLOBALS['log']->debug("cases id : $bean->id old user id : $old_assigned_user_id new user id : $new_assigned_user_id");
		if($old_assigned_user_id != $new_assigned_user_id){
			$new_user = BeanFactory::getBean('Users',$new_assigned_user_id);
			$old_user = BeanFactory::getBean('Users',$old_assigned_user_id);
			$GLOBALS['log']->debug("cases id : $bean->id assigned user changed $old_user->full_name to $new_user->full_name");
			
			$old_user_department = "";
			if($old_user && !empty($old_user->department)){
				$old_user_department = $old_user->department;
			}
			$new_user_department = "";
			if($new_user && !empty($new_user->department)){
				$new_user_department = $new_user->department;
			}
			$GLOBALS['log']->debug("cases id : $bean->id assigned department changed $old_user->department to $new_user->department");
			
			if($new_user && ($new_user_department!=$old_user_department)){
				$now = $this->getCurrentTime();
				$time_taken = $this->calculateTimeTakenByDepartment($bean->id,$now);
				$GLOBALS['log']->debug("cases id : " . $bean->id . " Time Taken : " . $time_taken);
				if(empty($time_taken)){
					$GLOBALS['log']->debug("Time taken is 0 for this cases_rewards id : " . $bean->id);
				}
				$this->updateRewardsRow($bean->id,$old_user_department,$time_taken,$now);
			}
		}
		//die();
	}

	function updateRewardsRow($id, $department, $time_taken, $now){
		global $db;
		$GLOBALS['log']->debug("cases id : " . $bean->id . " department : " . $department . " time_taken $time_taken hrs, now: $now");
		if(!empty($department) && isset($this->dept_db_map[$department])){
			$GLOBALS['log']->debug("cases id : " . $bean->id . " department : " . $department . " dept_db_map : " . $this->dept_db_map[$department]);
			$department = $this->dept_db_map[$department];
		}
		if(empty($department)){
			$query 	= "update cases_rewards set last_user_changed = '$now'  where id='$id'";
		}
		else{
			$query 	= "update cases_rewards set $department = $department+$time_taken, last_user_changed = '$now'  where id='$id'";
		}
		$results = $db->query($query);
		if($results){
            $GLOBALS['log']->debug("success updated time for cases_rewards $id, department $department, time_taken $time_taken hrs");
		}
        else{
            $GLOBALS['log']->fatal("failed updated time for cases_rewards $id, department $department, time_taken $time_taken hrs");
        }
	}

	function createNewRewardsRow($id){
		global $db;
		$now = $this->getCurrentTime();
		$query 		= "insert into cases_rewards (id,last_user_changed) values ('$id','$now')";
		$results 	= $db->query($query);
		if($results){
            $GLOBALS['log']->debug('success created rewards record for the case id : ' . $id);
		}
        else{
            $GLOBALS['log']->fatal('Failed to insert data into cases_rewards id : ' . $id);
        }
	}

	function calculateTimeTakenByDepartment($id,$now){
		$last_user_changed = "";
		$last_user_changed = $this->getLastUserChangedTime($id);
		$last_user_changed  = strtotime($last_user_changed);
		$now = strtotime($now);
		$GLOBALS['log']->debug("cases id : " . $id . " Last user changed time : " . $last_user_changed . " Now : " . $now);
		if(empty($last_user_changed) || empty($now)){
			return "0";
		}
		$diff = $now - $last_user_changed;
		$diff_hrs = $diff/(60*60);
		$GLOBALS['log']->debug("cases id : " . $id . " Last user changed time : " . $last_user_changed . " Diffrence in hours : " . $diff_hrs);
		return $diff_hrs;
	}

	function getLastUserChangedTime($id){
		global $db;
		$last_user_changed = "";
		$query 		= "select last_user_changed from cases_rewards where id='$id'";
		$results	= $db->query($query);
	    $row        = $db->fetchByAssoc($results);
	    $last_user_changed     = $row['last_user_changed'];
	    return $last_user_changed;
	}

	function getCurrentTime(){
		$now = TimeDate::getInstance()->now();
		$now = DateTime::createFromFormat('m/d/Y H:i',$now);
		$now = $now->format('Y-m-d H:i:s');
		$GLOBALS['log']->debug("cases id : " . $id . " Last user changed time (from DB) : " . $last_user_changed);
		return $now;
	}
}
?>