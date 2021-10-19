<?php
	array_push($job_strings, 'CalculateCaseTimings');
	require_once 'custom/CustomLogger/CustomLogger.php';

	function calculateDiffrenceInHours($from_date, $to_date){
		$logger = new CustomLogger('CalculateCaseTimings');
		$GLOBALS['log']->debug("from date : $from_date, to date : $to_date");
		$from_date 	= strtotime($from_date);
		$to_date  	= strtotime($to_date);
		$diff 		= $from_date - $to_date;
		$diff_hrs 	= $diff/(60*60);
		$logger->log('debug', "from date : $from_date, to date : $to_date, Diffrence in hours : $diff_hrs");
		return $diff_hrs;
	}

	function calculateTotalPoints($date_created, $date_modified, $tat){
		$logger = new CustomLogger('CalculateCaseTimings');
		$total_points = 0;
		$diff_hrs     = calculateDiffrenceInHours($date_modified, $date_created);
		if($diff_hrs<$tat){
			$total_points = 100;
		}
		else{
			$total_points = 50;
		}
		$logger->log('debug', "from date : $date_created, to date : $date_modified, tat points : $total_points");
		return $total_points;
	}

	function getCurrentDbTime(){
		$now = TimeDate::getInstance()->nowDb();
		return $now;
	}

	function fetchUserById($id){
		$logger = new CustomLogger('CalculateCaseTimings');
		$department 	= "";
		$user 			= BeanFactory::getBean("Users", $id);
		$department 	= $user->department;
		$logger->log('debug', "User ID : " . $id . " NGID : " . $user->user_name ." Full Name : " . $user->full_name ." Department : " . $user->department);
		return $user;
	}

	function isAlreadyFinedForThisCase($dept_fined_case,$case,$department){
		if(isset($dept_fined_case[$case_id]) && in_array($department, $dept_fined_case[$case_id])){
			$insert_data['tat_points_dept'] = 0;
		}
		else{
			$dept_fined_case[$case_id] = array();
			array_push($dept_fined_case[$case_id], $department);
			$insert_data['tat_points_dept'] = -20;	
		}
	}

	function allocateTatPointsForUsers($insert_data_list, $total_points){
		$logger = new CustomLogger('CalculateCaseTimings');
		$no_users_involved = sizeof($insert_data_list);
		($is_tat_1 = $total_points==100?'y':'n');
		$max_individual_points = 0.0;
		$max_individual_points = $total_points/$no_users_involved;

		$logger->log('debug', "List size : " . $no_users_involved . " Points to be shared : " . $total_points);
		$logger->log('debug', "max individual_points : " . $max_individual_points);

		$db_insert_data_list = array();
		$dept_fined_case = array();   
		foreach ($insert_data_list as $old_user_id => $insert_data) {
			$db_insert_data  = "";
			if($total_points == 50 && $insert_data['hours_taken']>24){
				$insert_data['tat_points'] = 0;
				if(isset($dept_fined_case[$case_id]) && in_array($department, $dept_fined_case[$case_id])){
					$logger->log('debug', "Already Fined Department $department, no need to fine again for case $case_id");
					$insert_data['tat_points_dept'] = 0.0;
				}
				else{
					$logger->log('debug', "Fined Department $department, Fine : -20");
					$dept_fined_case[$case_id] = array();
					array_push($dept_fined_case[$case_id], $department);
					$insert_data['tat_points_dept'] = -20.0;	
				}
			}
			else{
				$insert_data['tat_points'] = $max_individual_points;
				$insert_data['tat_points_dept'] = $max_individual_points;
			}
			$db_insert_data = "(
				'". $insert_data['id'] 				."',
				'". $insert_data['case_id'] 		."',
				'". $insert_data['old_user_id'] 	."',
				'". $insert_data['department'] 		."',
				'". $insert_data['hours_taken']		."',
				'". $insert_data['tat_points']		."',
				'". $insert_data['tat_points_dept']	."',
				'". $is_tat_1						."',
				NOW(),NOW()								
			)";	
			//NOW() for date created and entered
			array_push($db_insert_data_list, $db_insert_data);
		}
		//$insert_data = "('$id', '$case_id', '$old_user_id', '$department', '$hours_taken', '$tat_points')";
		return $db_insert_data_list;
	}
	
	function insertConsolidatedTimingRecords($case_id, $insert_data_list){
		$logger = new CustomLogger('CalculateCaseTimings');
		global $db;
		$return = false;
		$insert_data_list 	= implode(",", $insert_data_list);
		$query 	= "
			INSERT INTO cases_rewards (id, case_id, user_id, department, hours_taken, tat_points, tat_points_dept, within_tat_1, date_created, date_modified) 
			VALUES $insert_data_list";
		$results = $db->query($query);
		if($results){
			$logger->log('debug', "Successfully inserted consolidated timing records for case_id : $case_id");
			$return = true;
		} 
		else{
			$logger->log('debug', "Failed to insert consolidated timing records for case_id : $case_id");
            $return = false;
		}
		return $return;
	}

	function fetchAndUpdateTimingsForACase($case_id, $date_entered, $total_points){
		$logger = new CustomLogger('CalculateCaseTimings');
		global $db;
		$return = true;
		require_once('include/utils.php');
		$query = "SELECT id, parent_id, date_created, field_name, before_value_string, after_value_string FROM cases_audit WHERE parent_id = '$case_id' ORDER BY date_created ASC, field_name DESC";
		$logger->log('debug', "query :: " . $query);
		$results 					= $db->query($query);
		$last_user_assigned_time 	= $date_entered; //last_user_Assigned to calc diffrence in hrs btw old & new assigned user
		$insert_data_list 			= array();
		$hours_taken_by_user 		= array();
		while($row = $db->fetchByAssoc($results)){
			$logger->log('debug', "case id : " . $case_id . ", case_audit_id : " . $row['id']);
			$old_user_id 	= "";
			$new_user_id 	= "";
			$date_created	= "";
			$hours_taken	= "";
			$insert_data 	= array();
			$user_bean		= "";
			if($row['field_name'] == "assigned_user_id"){
				$logger->log('debug', "assigned_user_id changed");
				$old_user_id 	= $row['before_value_string'];
				$new_user_id 	= $row['after_value_string'];
				$date_created	= $row['date_created'];
				$hours_taken	= calculateDiffrenceInHours($date_created, $last_user_assigned_time);
				$id = create_guid();
				$logger->log('debug', "guid for case_rewards : $id");
				if(empty($old_user_id)){	
					$old_user_id 	= 1;
					$department		= 1;
					$ngid 			= 1;
				}
				else{
					$user_bean		= fetchUserById($old_user_id);
					$department 	= $user_bean->department;
				}
				if(isset($hours_taken_by_user[$old_user_id])){
					$hours_taken_by_user[$old_user_id] = $hours_taken;
				}
				else{
					$hours_taken_by_user[$old_user_id] += $hours_taken;
				}
				$insert_data = array(
					'id' 			=> $id,
					'case_id'		=> $case_id,
					'old_user_id' 	=> $old_user_id,
					'department' 	=> $department,
					'hours_taken' 	=> $hours_taken,
					'tat_points' 	=> 0 
				);
				$last_user_assigned_time = $date_created;
				//Dont include CS team for score calculation
				if(	$insert_data['department'] == 'Customer Support' || $insert_data['department'] == 'Customer Experience'
					|| $insert_data['department'] == 'Customer Service'){
						$logger->log('debug', "User belongs to cs team. Dont include in calculation");
					continue;
				}
				if(!empty($insert_data)){
					if(isset($insert_data_list[$old_user_id])){
						$insert_data_list[$old_user_id]['hours_taken'] += $insert_data['hours_taken'];
					}
					else{
						$insert_data_list[$old_user_id] = $insert_data;
					}
				}
				else{
					$logger->log('debug', "Error: insert_data is empty");
				}
			}
		}
		if(!empty($insert_data_list)){
			$insert_data_list = allocateTatPointsForUsers($insert_data_list, $total_points);
			$return = insertConsolidatedTimingRecords($case_id, $insert_data_list);
		}
		return $return;
	}

	//Scheculer Starting point - Rewards calculation logic
	function CalculateCaseTimings(){
		try{
			$logger = new CustomLogger('CalculateCaseTimings');
			$logger->log('debug', "---Start function::CalculateCaseTimings() at - ".date('Y-m-d H:i:s')."---");

			global $db;
			$return 		= true;
			$now 			= getCurrentDbTime();
			$one_day_before = new DateTime($now);
			$one_day_before->modify('-1 day');
			$one_day_before = $one_day_before->format("Y-m-d H:i:s");
			//$one_day_before = "2018-07-01 00:00:00";
			//$now = "2018-07-16 02:59:59";
			// $tatArr 		= retArray();
			$logger->log('debug', "now : $now, one day before : $one_day_before");
			$query = "SELECT c.id, c.date_entered, c.date_modified, c.state, cs.case_subcategory_c FROM cases c 
			LEFT JOIN cases_cstm cs on c.id = cs.id_c
			WHERE c.state = 'Closed' AND c.date_modified BETWEEN '$one_day_before' AND '$now' ORDER BY date_entered";
			$logger->log('debug', "query :: " . $query);
			$cases = $db->query($query);
			$logger->log('debug', "# of new closed cases :: " . $cases->num_rows);
			while($row = $db->fetchByAssoc($cases)){
				$GLOBALS['log']->debug("case id : " . $row['id']);
				$id 				= "";
				$date_entered 		= "";
				$state 				= "";
				$closed_date		= "";
				$case_subcategory 	= "";
				$total_points		= "";
				$id 				= $row['id'];
				$date_entered 		= $row['date_entered'];
				$state 				= $row['state'];
				$closed_date		= $row['date_modified'];	// when a case is closed, it wouldnt get modified unless reopened
				$case_subcategory_c 	= $row['case_subcategory_c'];
				//instead of hardcoded tat, fetch from scrm_Cases
			    $bean  = BeanFactory::getBean("scrm_Cases");
			    $query = "scrm_cases.deleted=0 and scrm_cases.sub_issue_type = '$case_subcategory_c'";
			    $logger->log('debug', "query - ".$query);
			    $items = $bean->get_full_list('',$query);
			    $l1 = 0;
			    if(!empty($items)){
					$item = $items[0];
					$l1 = $item->tat_1;
			  	}
				$logger->log('debug', "ID : $id, DATE_ENTERED : $date_entered, CLOSED DATE : $closed_date, CASE_SUBCATEGORY_C : $case_subcategory_c");
				if(!empty($l1)){
					$tat = $l1 * 24; //l1 gives days 
					$logger->log('debug', "TAT for this category is $tat");
				}
				else{
					$logger->log('debug', "TAT not available for this case");
					continue;
				}
				$total_points = calculateTotalPoints($date_entered, $closed_date, $tat);
				$return = $return && fetchAndUpdateTimingsForACase($id, $date_entered, $total_points);
			}
			return $return;
		}
		catch(Exception $e){
			$logger->log('debug', "Exception in case timings calculation for rewards : " . $e->getMessage());
			return false;
		}
	}
?>