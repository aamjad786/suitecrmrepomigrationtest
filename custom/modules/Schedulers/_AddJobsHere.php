<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

array_push($job_strings, 'tele_sales_reports');
/*********************************Telesales Disposition Report****************************/
function tele_sales_disposition(){
	global $db;
	$from_date  = date("Y-m-01 00:00:00");
    $to_date    = date("Y-m-d H:i:s", strtotime("+1 month", strtotime($from_date)));
    $from_date  = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($from_date)));
    $to_date    = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($to_date)));
    $month      = date("F");
    
	
	 $ls_query = "SELECT count( o.id ) AS count, o.lead_source, o.assigned_user_id, oc.disposition_c,LTRIM( RTRIM( CONCAT( IFNULL( u.first_name, '' ) , ' ', IFNULL( u.last_name, '' ) ) ) ) AS name
				FROM leads o
				JOIN leads_cstm oc ON o.id = oc.id_c
				LEFT JOIN users u on u.id = o.assigned_user_id
				AND u.deleted = 0
				WHERE o.deleted =0
				AND o.date_entered
				BETWEEN '$from_date'
				AND '$to_date'
				GROUP BY o.assigned_user_id, o.lead_source, oc.disposition_c";
				
		//echo $ls_query;
				
	$ls_result = $db->query($ls_query);
	$lead_source_data = array();
	while($ls_row = $db->fetchByAssoc($ls_result)) {
		$ls = $ls_row['lead_source'];
		$count = $ls_row['count'];
		$assigned_user_id = $ls_row['assigned_user_id'];
		$disposition_c = $ls_row['disposition_c'];
		$name = $ls_row['name'];
		
		if($ls == 'Web Site'){
			$lead_source_data[$assigned_user_id]['Web Site']['lead_source'] = "Web Site";
			$lead_source_data[$assigned_user_id]['Web Site']['month'] = $month;
			$lead_source_data[$assigned_user_id]['Web Site']['assigned_user_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['Web Site']['user_profile_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['Web Site']['name'] = $name;
			$lead_source_data[$assigned_user_id]['Web Site']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$assigned_user_id]['Web Site']['user_type'] = "CAM";
			$lead_source_data[$assigned_user_id]['Web Site']['total_enquiries'] +=$count;
			if($disposition_c == ''){
				$lead_source_data[$assigned_user_id]['Web Site']['new_unattempted'] += $count;
			} else if($disposition_c == 'Not contactable'){
				$lead_source_data[$assigned_user_id]['Web Site']['not_contactable'] += $count;
			} else if($disposition_c == 'Wrong number'){
				$lead_source_data[$assigned_user_id]['Web Site']['wrong_number'] += $count;
			} else if($disposition_c == 'Call_back'){
				$lead_source_data[$assigned_user_id]['Web Site']['call_back'] += $count;
			}else if($disposition_c == 'Follow_up'){
				$lead_source_data[$assigned_user_id]['Web Site']['follow_ups'] += $count;
			}else if($disposition_c == 'Dropped'){
				$lead_source_data[$assigned_user_id]['Web Site']['dropped'] += $count;
			}else if($disposition_c == 'Interested'){
				$lead_source_data[$assigned_user_id]['Web Site']['interested'] += $count;
			}else if($disposition_c == 'Pick-up generation'){
				$lead_source_data[$assigned_user_id]['Web Site']['pick_up_generation'] += $count;
			}
		}
		if($ls == 'OBD Campaign'){
			$lead_source_data[$assigned_user_id]['OBD Campaign']['lead_source'] = "OBD Campaign";
			$lead_source_data[$assigned_user_id]['OBD Campaign']['month'] = $month;
			$lead_source_data[$assigned_user_id]['OBD Campaign']['assigned_user_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['OBD Campaign']['user_profile_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['OBD Campaign']['name'] = $name;
			$lead_source_data[$assigned_user_id]['OBD Campaign']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$assigned_user_id]['OBD Campaign']['user_type'] = "CAM";
			$lead_source_data[$assigned_user_id]['OBD Campaign']['total_enquiries'] +=$count;
			if($disposition_c == ''){
				$lead_source_data[$assigned_user_id]['OBD Campaign']['new_unattempted'] += $count;
			} else if($disposition_c == 'Not contactable'){
				$lead_source_data[$assigned_user_id]['OBD Campaign']['not_contactable'] += $count;
			} else if($disposition_c == 'Wrong number'){
				$lead_source_data[$assigned_user_id]['OBD Campaign']['wrong_number'] += $count;
			} else if($disposition_c == 'Call_back'){
				$lead_source_data[$assigned_user_id]['OBD Campaign']['call_back'] += $count;
			}else if($disposition_c == 'Follow_up'){
				$lead_source_data[$assigned_user_id]['OBD Campaign']['follow_ups'] += $count;
			}else if($disposition_c == 'Dropped'){
				$lead_source_data[$assigned_user_id]['OBD Campaign']['dropped'] += $count;
			}else if($disposition_c == 'Interested'){
				$lead_source_data[$assigned_user_id]['OBD Campaign']['interested'] += $count;
			}else if($disposition_c == 'Pick-up generation'){
				$lead_source_data[$assigned_user_id]['OBD Campaign']['pick_up_generation'] += $count;
			}
		}
		if($ls == 'Missed Calls Website'){
			$lead_source_data[$assigned_user_id]['Missed Calls Website']['lead_source'] = "Missed Calls Website";
			$lead_source_data[$assigned_user_id]['Missed Calls Website']['month'] = $month;
			$lead_source_data[$assigned_user_id]['Missed Calls Website']['assigned_user_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['Missed Calls Website']['user_profile_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['Missed Calls Website']['name'] = $name;
			$lead_source_data[$assigned_user_id]['Missed Calls Website']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$assigned_user_id]['Missed Calls Website']['user_type'] = "CAM";
			$lead_source_data[$assigned_user_id]['Missed Calls Website']['total_enquiries'] +=$count;
			if($disposition_c == ''){
				$lead_source_data[$assigned_user_id]['Missed Calls Website']['new_unattempted'] += $count;
			} else if($disposition_c == 'Not contactable'){
				$lead_source_data[$assigned_user_id]['Missed Calls Website']['not_contactable'] += $count;
			} else if($disposition_c == 'Wrong number'){
				$lead_source_data[$assigned_user_id]['Missed Calls Website']['wrong_number'] += $count;
			} else if($disposition_c == 'Call_back'){
				$lead_source_data[$assigned_user_id]['Missed Calls Website']['call_back'] += $count;
			}else if($disposition_c == 'Follow_up'){
				$lead_source_data[$assigned_user_id]['Missed Calls Website']['follow_ups'] += $count;
			}else if($disposition_c == 'Dropped'){
				$lead_source_data[$assigned_user_id]['Missed Calls Website']['dropped'] += $count;
			}else if($disposition_c == 'Interested'){
				$lead_source_data[$assigned_user_id]['Missed Calls Website']['interested'] += $count;
			}else if($disposition_c == 'Pick-up generation'){
				$lead_source_data[$assigned_user_id]['Missed Calls Website']['pick_up_generation'] += $count;
			}
		}
		if($ls == 'Missed Calls SMS'){
			$lead_source_data[$assigned_user_id]['Missed Calls SMS']['lead_source'] = "Missed Calls SMS";
			$lead_source_data[$assigned_user_id]['Missed Calls SMS']['month'] = $month;
			$lead_source_data[$assigned_user_id]['Missed Calls SMS']['assigned_user_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['Missed Calls SMS']['user_profile_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['Missed Calls SMS']['name'] = $name;
			$lead_source_data[$assigned_user_id]['Missed Calls SMS']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$assigned_user_id]['Missed Calls SMS']['user_type'] = "CAM";
			$lead_source_data[$assigned_user_id]['Missed Calls SMS']['total_enquiries'] +=$count;
			if($disposition_c == ''){
				$lead_source_data[$assigned_user_id]['Missed Calls SMS']['new_unattempted'] += $count;
			} else if($disposition_c == 'Not contactable'){
				$lead_source_data[$assigned_user_id]['Missed Calls SMS']['not_contactable'] += $count;
			} else if($disposition_c == 'Wrong number'){
				$lead_source_data[$assigned_user_id]['Missed Calls SMS']['wrong_number'] += $count;
			} else if($disposition_c == 'Call-back'){
				$lead_source_data[$assigned_user_id]['Missed Calls SMS']['call_back'] += $count;
			}else if($disposition_c == 'Follow_up'){
				$lead_source_data[$assigned_user_id]['Missed Calls SMS']['follow_ups'] += $count;
			}else if($disposition_c == 'Dropped'){
				$lead_source_data[$assigned_user_id]['Missed Calls SMS']['dropped'] += $count;
			}else if($disposition_c == 'Interested'){
				$lead_source_data[$assigned_user_id]['Missed Calls SMS']['interested'] += $count;
			}else if($disposition_c == 'Pick-up generation'){
				$lead_source_data[$assigned_user_id]['Missed Calls SMS']['pick_up_generation'] += $count;
			}
		}
		if($ls == 'Facebook'){
			$lead_source_data[$assigned_user_id]['Facebook']['lead_source'] = "Facebook";
			$lead_source_data[$assigned_user_id]['Facebook']['month'] = $month;
			$lead_source_data[$assigned_user_id]['Facebook']['assigned_user_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['Facebook']['user_profile_id'] = $assigned_user_id;
			$lead_source_data[$assigned_user_id]['Facebook']['name'] = $name;
			$lead_source_data[$assigned_user_id]['Facebook']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$assigned_user_id]['Facebook']['user_type'] = "CAM";
			$lead_source_data[$assigned_user_id]['Facebook']['total_enquiries'] +=$count;
			if($disposition_c == ''){
				$lead_source_data[$assigned_user_id]['Facebook']['new_unattempted'] += $count;
			} else if($disposition_c == 'Not contactable'){
				$lead_source_data[$assigned_user_id]['Facebook']['not_contactable'] += $count;
			} else if($disposition_c == 'Wrong number'){
				$lead_source_data[$assigned_user_id]['Facebook']['wrong_number'] += $count;
			} else if($disposition_c == 'Call_back'){
				$lead_source_data[$assigned_user_id]['Facebook']['call_back'] += $count;
			}else if($disposition_c == 'Follow_up'){
				$lead_source_data[$assigned_user_id]['Facebook']['follow_ups'] += $count;
			}else if($disposition_c == 'Dropped'){
				$lead_source_data[$assigned_user_id]['Facebook']['dropped'] += $count;
			}else if($disposition_c == 'Interested'){
				$lead_source_data[$assigned_user_id]['Facebook']['interested'] += $count;
			}else if($disposition_c == 'Pick-up generation'){
				$lead_source_data[$assigned_user_id]['Facebook']['pick_up_generation'] += $count;
			}
		}
		 //Cluster Managers target calculation
   $cm_query  = "SELECT urs.reports_to_id, urs.id
                FROM users urs
                JOIN users u ON urs.reports_to_id = u.id
                AND u.deleted =0
                AND u.status = 'Active'
                JOIN acl_roles_users aru ON aru.user_id = u.id
                AND aru.deleted =0
                JOIN acl_roles ar ON ar.id = aru.role_id
                AND ar.deleted =0
                AND ar.name = 'Call Center Manager'
                WHERE urs.deleted =0
                AND urs.status = 'Active'";
    $cm_result = $db->query($cm_query);
    while ($cm_row = $db->fetchByAssoc($cm_result)) {
        if (!isset($reports_to_id) && $reports_to_id != $cm_row['reports_to_id']) {
			$user_id = $cm_row['id'];
            $reports_to_id = $cm_row['reports_to_id'];
            $user          = new User();
            $user->retrieve($reports_to_id);
            $data_set[$reports_to_id]['name']             = $user->full_name;
            $data_set[$reports_to_id]['month']            = $month;
            $data_set[$reports_to_id]['user_profile_id']  = $reports_to_id;
            $data_set[$reports_to_id]['assigned_user_id'] = $reports_to_id;
            $data_set[$reports_to_id]['user_type']        = "TS";
            
            //lead source
            $lead_source_data[$reports_to_id]['Web Site']['lead_source'] = "Web Site";
			$lead_source_data[$reports_to_id]['Web Site']['month'] = $month;
			$lead_source_data[$reports_to_id]['Web Site']['assigned_user_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['Web Site']['user_profile_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['Web Site']['name'] = $name;
			$lead_source_data[$reports_to_id]['Web Site']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$reports_to_id]['Web Site']['user_type'] = "TS";
			$lead_source_data[$reports_to_id]['Web Site']['total_enquiries'] +=$count;
			
			$lead_source_data[$reports_to_id]['OBD Campaign']['lead_source'] = "OBD Campaign";
			$lead_source_data[$reports_to_id]['OBD Campaign']['month'] = $month;
			$lead_source_data[$reports_to_id]['OBD Campaign']['assigned_user_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['OBD Campaign']['user_profile_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['OBD Campaign']['name'] = $name;
			$lead_source_data[$reports_to_id]['OBD Campaign']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$reports_to_id]['OBD Campaign']['user_type'] = "TS";
			$lead_source_data[$reports_to_id]['OBD Campaign']['total_enquiries'] +=$count;
			
			$lead_source_data[$reports_to_id]['Missed Calls Website']['lead_source'] = "Missed Calls Website";
			$lead_source_data[$reports_to_id]['Missed Calls Website']['month'] = $month;
			$lead_source_data[$reports_to_id]['Missed Calls Website']['assigned_user_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['Missed Calls Website']['user_profile_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['Missed Calls Website']['name'] = $name;
			$lead_source_data[$reports_to_id]['Missed Calls Website']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$reports_to_id]['Missed Calls Website']['user_type'] = "TS";
			$lead_source_data[$reports_to_id]['Missed Calls Website']['total_enquiries'] +=$count;
			
			$lead_source_data[$reports_to_id]['Missed Calls SMS']['lead_source'] = "Missed Calls SMS";
			$lead_source_data[$reports_to_id]['Missed Calls SMS']['month'] = $month;
			$lead_source_data[$reports_to_id]['Missed Calls SMS']['assigned_user_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['Missed Calls SMS']['user_profile_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['Missed Calls SMS']['name'] = $name;
			$lead_source_data[$reports_to_id]['Missed Calls SMS']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$reports_to_id]['Missed Calls SMS']['user_type'] = "TS";
			$lead_source_data[$reports_to_id]['Missed Calls SMS']['total_enquiries'] +=$count;
			
			$lead_source_data[$reports_to_id]['Facebook']['lead_source'] = "Facebook";
			$lead_source_data[$reports_to_id]['Facebook']['month'] = $month;
			$lead_source_data[$reports_to_id]['Facebook']['assigned_user_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['Facebook']['user_profile_id'] = $reports_to_id;
			$lead_source_data[$reports_to_id]['Facebook']['name'] = $name;
			$lead_source_data[$reports_to_id]['Facebook']['campaign_dispostion'] = $disposition_c;
			$lead_source_data[$reports_to_id]['Facebook']['user_type'] = "TS";
			$lead_source_data[$reports_to_id]['Facebook']['total_enquiries'] +=$count;
            
        }
        $user_id = $cm_row['id'];
        
		//lead source
		$lead_source_data[$reports_to_id]['Web Site']['new_unattempted'] += (isset($lead_source_data[$user_id]['Web Site']['new_unattempted']) ? $lead_source_data[$user_id]['Web Site']['new_unattempted'] : 0);
		$lead_source_data[$reports_to_id]['Web Site']['not_contactable'] += (isset($lead_source_data[$user_id]['Web Site']['not_contactable']) ? $lead_source_data[$user_id]['Web Site']['not_contactable'] : 0);
		$lead_source_data[$reports_to_id]['Web Site']['wrong_number'] += (isset($lead_source_data[$user_id]['Web Site']['wrong_number']) ? $lead_source_data[$user_id]['Web Site']['wrong_number'] : 0);
		$lead_source_data[$reports_to_id]['Web Site']['call_back'] += (isset($lead_source_data[$user_id]['Web Site']['call_back']) ? $lead_source_data[$user_id]['Web Site']['call_back'] : 0);
		$lead_source_data[$reports_to_id]['Web Site']['follow_ups'] += (isset($lead_source_data[$user_id]['Web Site']['follow_ups']) ? $lead_source_data[$user_id]['Web Site']['follow_ups'] : 0);
		$lead_source_data[$reports_to_id]['Web Site']['dropped'] += (isset($lead_source_data[$user_id]['Web Site']['dropped']) ? $lead_source_data[$user_id]['Web Site']['dropped'] : 0);
		$lead_source_data[$reports_to_id]['Web Site']['interested'] += (isset($lead_source_data[$user_id]['Web Site']['interested']) ? $lead_source_data[$user_id]['Web Site']['interested'] : 0);
		$lead_source_data[$reports_to_id]['Web Site']['pick_up_generation'] += (isset($lead_source_data[$user_id]['Web Site']['pick_up_generation']) ? $lead_source_data[$user_id]['Web Site']['pick_up_generation'] : 0);
		
		$lead_source_data[$reports_to_id]['OBD Campaign']['new_unattempted'] += (isset($lead_source_data[$user_id]['OBD Campaign']['new_unattempted']) ? $lead_source_data[$user_id]['OBD Campaign']['new_unattempted'] : 0);
		$lead_source_data[$reports_to_id]['OBD Campaign']['not_contactable'] += (isset($lead_source_data[$user_id]['OBD Campaign']['not_contactable']) ? $lead_source_data[$user_id]['OBD Campaign']['not_contactable'] : 0);
		$lead_source_data[$reports_to_id]['OBD Campaign']['wrong_number'] += (isset($lead_source_data[$user_id]['OBD Campaign']['wrong_number']) ? $lead_source_data[$user_id]['OBD Campaign']['wrong_number'] : 0);
		$lead_source_data[$reports_to_id]['OBD Campaign']['call_back'] += (isset($lead_source_data[$user_id]['OBD Campaign']['call_back']) ? $lead_source_data[$user_id]['OBD Campaign']['call_back'] : 0);
		$lead_source_data[$reports_to_id]['OBD Campaign']['follow_ups'] += (isset($lead_source_data[$user_id]['OBD Campaign']['follow_ups']) ? $lead_source_data[$user_id]['OBD Campaign']['follow_ups'] : 0);
		$lead_source_data[$reports_to_id]['OBD Campaign']['dropped'] += (isset($lead_source_data[$user_id]['OBD Campaign']['dropped']) ? $lead_source_data[$user_id]['OBD Campaign']['dropped'] : 0);
		$lead_source_data[$reports_to_id]['OBD Campaign']['interested'] += (isset($lead_source_data[$user_id]['OBD Campaign']['interested']) ? $lead_source_data[$user_id]['OBD Campaign']['interested'] : 0);
		$lead_source_data[$reports_to_id]['OBD Campaign']['pick_up_generation'] += (isset($lead_source_data[$user_id]['OBD Campaign']['pick_up_generation']) ? $lead_source_data[$user_id]['OBD Campaign']['pick_up_generation'] : 0);
		
		$lead_source_data[$reports_to_id]['Missed Calls Website']['new_unattempted'] += (isset($lead_source_data[$user_id]['Missed Calls Website']['new_unattempted']) ? $lead_source_data[$user_id]['Missed Calls Website']['new_unattempted'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls Website']['not_contactable'] += (isset($lead_source_data[$user_id]['Missed Calls Website']['not_contactable']) ? $lead_source_data[$user_id]['Missed Calls Website']['not_contactable'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls Website']['wrong_number'] += (isset($lead_source_data[$user_id]['Missed Calls Website']['wrong_number']) ? $lead_source_data[$user_id]['Missed Calls Website']['wrong_number'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls Website']['call_back'] += (isset($lead_source_data[$user_id]['Missed Calls Website']['call_back']) ? $lead_source_data[$user_id]['Missed Calls Website']['call_back'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls Website']['follow_ups'] += (isset($lead_source_data[$user_id]['Missed Calls Website']['follow_ups']) ? $lead_source_data[$user_id]['Missed Calls Website']['follow_ups'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls Website']['dropped'] += (isset($lead_source_data[$user_id]['Missed Calls Website']['dropped']) ? $lead_source_data[$user_id]['Missed Calls Website']['dropped'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls Website']['interested'] += (isset($lead_source_data[$user_id]['Missed Calls Website']['interested']) ? $lead_source_data[$user_id]['Missed Calls Website']['interested'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls Website']['pick_up_generation'] += (isset($lead_source_data[$user_id]['Missed Calls Website']['pick_up_generation']) ? $lead_source_data[$user_id]['Missed Calls Website']['pick_up_generation'] : 0);
		
		$lead_source_data[$reports_to_id]['Missed Calls SMS']['new_unattempted'] += (isset($lead_source_data[$user_id]['Missed Calls SMS']['new_unattempted']) ? $lead_source_data[$user_id]['Missed Calls SMS']['new_unattempted'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls SMS']['not_contactable'] += (isset($lead_source_data[$user_id]['Missed Calls SMS']['not_contactable']) ? $lead_source_data[$user_id]['Missed Calls SMS']['not_contactable'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls SMS']['wrong_number'] += (isset($lead_source_data[$user_id]['Missed Calls SMS']['wrong_number']) ? $lead_source_data[$user_id]['Missed Calls SMS']['wrong_number'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls SMS']['call_back'] += (isset($lead_source_data[$user_id]['Missed Calls SMS']['call_back']) ? $lead_source_data[$user_id]['Missed Calls SMS']['call_back'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls SMS']['follow_ups'] += (isset($lead_source_data[$user_id]['Missed Calls SMS']['follow_ups']) ? $lead_source_data[$user_id]['Missed Calls SMS']['follow_ups'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls SMS']['dropped'] += (isset($lead_source_data[$user_id]['Missed Calls SMS']['dropped']) ? $lead_source_data[$user_id]['Missed Calls SMS']['dropped'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls SMS']['interested'] += (isset($lead_source_data[$user_id]['Missed Calls SMS']['interested']) ? $lead_source_data[$user_id]['Missed Calls SMS']['interested'] : 0);
		$lead_source_data[$reports_to_id]['Missed Calls SMS']['pick_up_generation'] += (isset($lead_source_data[$user_id]['Missed Calls SMS']['pick_up_generation']) ? $lead_source_data[$user_id]['Missed Calls SMS']['pick_up_generation'] : 0);
		
		$lead_source_data[$reports_to_id]['Facebook']['new_unattempted'] += (isset($lead_source_data[$user_id]['Facebook']['new_unattempted']) ? $lead_source_data[$user_id]['Facebook']['new_unattempted'] : 0);
		$lead_source_data[$reports_to_id]['Facebook']['not_contactable'] += (isset($lead_source_data[$user_id]['Facebook']['not_contactable']) ? $lead_source_data[$user_id]['Facebook']['not_contactable'] : 0);
		$lead_source_data[$reports_to_id]['Facebook']['wrong_number'] += (isset($lead_source_data[$user_id]['Facebook']['wrong_number']) ? $lead_source_data[$user_id]['Facebook']['wrong_number'] : 0);
		$lead_source_data[$reports_to_id]['Facebook']['call_back'] += (isset($lead_source_data[$user_id]['Facebook']['call_back']) ? $lead_source_data[$user_id]['Facebook']['call_back'] : 0);
		$lead_source_data[$reports_to_id]['Facebook']['follow_ups'] += (isset($lead_source_data[$user_id]['Facebook']['follow_ups']) ? $lead_source_data[$user_id]['Facebook']['follow_ups'] : 0);
		$lead_source_data[$reports_to_id]['Facebook']['dropped'] += (isset($lead_source_data[$user_id]['Facebook']['dropped']) ? $lead_source_data[$user_id]['Facebook']['dropped'] : 0);
		$lead_source_data[$reports_to_id]['Facebook']['interested'] += (isset($lead_source_data[$user_id]['Facebook']['interested']) ? $lead_source_data[$user_id]['Facebook']['interested'] : 0);
		$lead_source_data[$reports_to_id]['Facebook']['pick_up_generation'] += (isset($lead_source_data[$user_id]['Facebook']['pick_up_generation']) ? $lead_source_data[$user_id]['Facebook']['pick_up_generation'] : 0);
		
		 
		
		
    } //End of CM calculations
	
	
	//print_r($lead_source_data);
	foreach($lead_source_data as $ls_set){
		foreach($ls_set as $data){
			$user_id = $data['user_profile_id'];
			$lead_source = $data['lead_source'];
			$th      = new scrm_Telesales_History();
			$query   = "select id from scrm_telesales_history t where t.user_profile_id = '$user_id' and t.deleted = 0 and t.month = '$month' AND t.lead_source = '$lead_source'";
			//echo $query;
			$result  = $db->query($query);
			if ($row = $db->fetchByAssoc($result)) {
				$th_id = $row['id'];
				$th->retrieve($th_id);
			}
			$th->name                   = $data['name'];
			$th->assigned_user_id       = $data['assigned_user_id'];
			$th->month                  = $data['month'];
			$th->user_profile_id        = $data['user_profile_id'];
			$th->lead_source			= $data['lead_source'];
			$th->user_type			    = $data['user_type'];
			$th->total_enquiries		= $data['total_enquiries'];
			$th->campaign_dispostion	= (isset($data['campaign_dispostion']) ? $data['campaign_dispostion'] : 'New');
			
			$th->new_unattempted		= (isset($data['new_unattempted']) ? $data['new_unattempted'] : 0);
			$th->not_contactable	= (isset($data['not_contactable']) ? $data['not_contactable'] : 0);
			$th->wrong_number	= (isset($data['wrong_number']) ? $data['wrong_number'] : 0);
			$th->call_back	= (isset($data['call_back']) ? $data['call_back'] : 0);
			$th->follow_ups	= (isset($data['follow_ups']) ? $data['follow_ups'] : 0);
			$th->dropped	= (isset($data['dropped']) ? $data['dropped'] : 0);
			$th->interested	= (isset($data['interested']) ? $data['interested'] : 0);
			$th->pick_up_generation	= (isset($data['pick_up_generation']) ? $data['pick_up_generation'] : 0);
			$th->save();	
			}
		}
	}
/*********************************END - Telesales Disposition *************************/

/********************************Telesales CAM wise Leads*********************************/ 
	echo $ls_query = "SELECT count( o.id ) AS count, o.primary_address_city, o.assigned_user_id, oc.disposition_c,oc.sub_disposition_c,LTRIM( RTRIM( CONCAT( IFNULL( u.first_name, '' ) , ' ', IFNULL( u.last_name, '' ) ) ) ) AS name
				FROM leads o
				JOIN leads_cstm oc ON o.id = oc.id_c
				LEFT JOIN users u on u.id = o.assigned_user_id
				AND u.deleted = 0 JOIN acl_roles_users aru on aru.user_id=u.id AND aru.deleted=0
				WHERE o.deleted =0 and o.primary_address_city IS NOT NULL AND aru.role_id='978da784-78e3-5c78-ff7a-57e10a137412'
				AND o.date_entered
				BETWEEN '2016-01-31 18:30:00'
				AND '2017-04-30 18:30:00'
				GROUP BY o.assigned_user_id, o.primary_address_city";
	 
				
		//echo $ls_query;
				
	$ls_result = $db->query($ls_query);
	$city_data = array();
	while($ls_row = $db->fetchByAssoc($ls_result)) {
		
		$city = $ls_row['primary_address_city'];
		$count = $ls_row['count'];
		$assigned_user_id = $ls_row['assigned_user_id'];
		$disposition_c = $ls_row['disposition_c'];
		$sub_disposition_c = $ls_row['sub_disposition_c'];
		$city_data[$assigned_user_id][$city]['total_enquiries'] +=  $count;
		$city_data[$assigned_user_id][$city]['month'] = $month;
		$city_data[$assigned_user_id][$city]['city'] = $city;
		$city_data[$assigned_user_id][$city]['assigned_user_id'] = $assigned_user_id;
		$city_data[$assigned_user_id][$city]['name'] = $ls_row['name'];
		
		if($disposition_c == ''){
				$city_data[$assigned_user_id][$city]['new_unattempted'] += $count;
			} else if($disposition_c == 'Not contactable'){
				$city_data[$assigned_user_id][$city]['not_contactable'] += $count;
			} else if($disposition_c == 'Call_back'){
				$city_data[$assigned_user_id][$city]['call_back'] += $count;
			}else if($disposition_c == 'Follow_up'){
				$city_data[$assigned_user_id][$city]['follow_ups'] += $count;
			}else if($disposition_c == 'Dropped'){
				$city_data[$assigned_user_id][$city]['dropped'] += $count;
			}else if($disposition_c == 'Interested' && $sub_disposition_c=='Lead generated'){
				echo $opp_query = "select count(*) as count,o.sales_stage,sum( o.amount ) AS disbursal_amount,o.assigned_user_id FROM opportunities o where o.deleted=0 and o.assigned_user_id='$assigned_user_id' group by o.sales_stage";
				$opp_result = $db->query($opp_query);
				while($opp_row = $db->fetchByAssoc($opp_result)){
					$sales_stage = $opp_row['sales_stage'];
					$disbursal_amount = $opp_row['disbursal_amount'];
					$opp_count = $opp_row['count'];
					$assigned_user_id_opp = $opp_row['assigned_user_id'];
					if($sales_stage == 'Submitted'){
						$city_data[$assigned_user_id_opp][$city]['logins'] += $opp_count;
					}else if($sales_stage == 'Sanctioned'){
						echo 'test';
						$city_data[$assigned_user_id_opp][$city]['sanctioned'] += $opp_count;
 					}else if($sales_stage == 'Rejected'){
						$city_data[$assigned_user_id_opp][$city]['rejects'] += $opp_count;
 					}else if($sales_stage == 'Disbursed'){
						$city_data[$assigned_user_id_opp][$city]['disbursed'] += $opp_count;
					}
					$city_data[$assigned_user_id][$city]['disbursal_amount'] += $dispbusal_amount;
				}
								
			}

		}
		
	foreach($city_data as $ls_set){
		foreach($ls_set as $data){
			$user_id = $data['assigned_user_id'];
			$city = $data['city'];
			$th      = new scrm_Telesales_History();
			$query   = "select id from scrm_telesales_history t where t.user_profile_id = '$user_id' and t.deleted = 0 and t.month = '$month' AND t.city = '$city'";
			//echo $query;
			$result  = $db->query($query);
			if ($row = $db->fetchByAssoc($result)) {
				$th_id = $row['id'];
				$th->retrieve($th_id);
			}
			$th->name                   	= $data['name'];
			$th->assigned_user_id       	= $data['assigned_user_id'];
			$th->month                 		= $data['month'];
			$th->user_profile_id       		= $data['assigned_user_id'];
			$th->city			        	= $data['city'];
			//$th->user_type			    = $data['user_type'];
			$th->total_enquiries			= $data['total_enquiries'];
			
			$th->new_unattempted		    = (isset($data['new_unattempted']) ? $data['new_unattempted'] : 0);
			$th->not_contactable			= (isset($data['not_contactable']) ? $data['not_contactable'] : 0);
			$th->call_back					= (isset($data['call_back']) ? $data['call_back'] : 0);
			$th->follow_ups					= (isset($data['follow_ups']) ? $data['follow_ups'] : 0);
			$th->dropped					= (isset($data['dropped']) ? $data['dropped'] : 0);
			$th->logins						= (isset($data['logins']) ? $data['logins'] : 0);
			$th->sanctioned					= (isset($data['sanctioned']) ? $data['sanctioned'] : 0);
			$th->rejects					= (isset($data['rejects']) ? $data['rejects'] : 0);
			$th->disbursed					= (isset($data['disbursed']) ? $data['disbursed'] : 0);
			$th->disbursal_value			= (isset($data['disbursal_value']) ? $data['disbursal_value'] : 0);
			$th->login_leads_count 			= (($data['logins'] != 0) ? ($data['logins'] - $data['total_enquiries']) : 0);
			$th->disbursal_leads_count 		= (($data['disbursed'] != 0) ? ($data['disbursed'] - $data['total_enquiries']) : 0);
			$th->disbursal_average  		= (($data['disbursed'] != 0) ? ($data['disbursal_value'] / $data['disbursed']) : 0);
			$th->save();	
			}
		}
/*********************************END - CAM wise Leads************************************/
return true;
}

?>
