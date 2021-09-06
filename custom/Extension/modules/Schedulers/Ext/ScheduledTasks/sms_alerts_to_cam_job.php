<?php

    array_push($job_strings, 'sms_alerts_to_cam_job');
    function sms_alerts_to_cam_job()
    {
	   require_once('custom/include/SendSMS.php');	
       global $db;
       
       $last_run_query = "select last_run from schedulers where name = 'SMS Alerts to CAM' and deleted = 0 and status = 'Active'";
       $last_run_result = $db->query($last_run_query);
       $last_run_date = '';
       while($last_run_row = $db->fetchByAssoc($last_run_result)){ 
		   $last_run_date = $last_run_row['last_run'];
	   }
       
        $start_date = (empty($last_run_date)?date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime(date("Y-m-d H:i:s")))):date("Y-m-d H:i:s", strtotime($last_run_date)));
       $end_date = date("Y-m-d H:i:s", strtotime("+15 minutes", strtotime($start_date)));
       
       $next_start_date = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($start_date)));
       $next_end_date = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($end_date)));
       
       echo $calls_query = "SELECT o.name, oc.pickup_appointment_date_c, ltrim( rtrim( concat( ifnull( u.first_name, '' ) , ' ', ifnull( u.last_name, '' ) ) ) ) AS user_name, u.phone_mobile
						FROM opportunities o
						JOIN opportunities_cstm oc ON oc.id_c = o.id
						AND ((oc.pickup_appointment_date_c
						BETWEEN '$start_date'
						AND '$end_date')
						OR (oc.pickup_appointment_date_c
						BETWEEN '$next_start_date'
						AND '$next_end_date'))
						JOIN users u ON u.id = o.assigned_user_id
						AND u.deleted =0
						AND u.status = 'Active'
						WHERE o.deleted =0";
       $calls_result = $db->query($calls_query);
       $data = array();
       $i = 0;
       while($calls_row = $db->fetchByAssoc($calls_result)){
		   $data[$i]['opp_name'] = $calls_row['name'];
		   $data[$i]['pickup_time'] = $calls_row['pickup_appointment_date_c'];
		   $data[$i]['user_name'] = $calls_row['user_name'];
		   $data[$i]['mobile'] = $calls_row['phone_mobile'];
		   $i++;
	   }
	   if(!empty($data)){
		   foreach($data as $d){
			   if($d['mobile'] != ''){
				   $cam_name = $d['user_name'];
				   $opp_name = $d['opp_name'];
				   $pickup_time = $d['pickup_time'];
				   $mobile = $d['mobile'];
				   
				   $message = "Hi $cam_name, new opportunity $opp_name has been assigned to you on $pickup_time";
            
                   $mobile_no = "91" . substr($mobile, -10);
            
				   $sms = new SendSMS();
				   $sms->send_sms_to_user($tag_name="Cust_CRM_11", $mobile_no, $message, null, 'sms_alerts_to_cam_job');
			   }
		   }
	   }
        return true;
    }
