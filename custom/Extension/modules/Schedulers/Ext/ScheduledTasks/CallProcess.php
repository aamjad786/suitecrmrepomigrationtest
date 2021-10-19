<?php
require_once 'custom/CustomLogger/CustomLogger.php';
$job_strings[] = 'CallProcess';
date_default_timezone_set('Asia/Kolkata');

function CallProcess() {
	$logger = new CustomLogger('CallProcess');
	$logger->log('debug', "--- START In CallProcess in ScheduledTasks at ".date('Y-m-d h:i:s')."---");

    global $db, $sugar_config;
	$query = "select * from call_details where processed=0 and retry_count<7 and CampaignName='".$sugar_config['default_campaign_name']."' and Status='NotAnswered' and date_entered>'2020-03-22' ";
    $results = $db->query($query);
    while($row=$db->fetchByAssoc($results)){
		
        $AudioFile=$Type=$StartTime=$CallDuration=$AgentID=$CallerID=$parent_type=$parent_id=$hour=$min=$id=$retry_count=$uui=$campaign=$skill=$status1="";
		foreach($row as $key=>$value){
			if($key=='id'){
				$id = $value;
			}else if($key=='retry_count'){
				$retry_count = (intval($value));
			}
			else if($key=='Skill')
			{
				$skill=$value;
			}
			else if($key=='AudioFile'){
				$AudioFile = $value;
			}
			else if($key=='Type'){
				if($value=='Manual')
				{
					$Type = 'Outbound';
				}
				else
				{
					$Type = $value;
				}
			}else if($key=='StartTime'){
				// $StartTime=$value;
				$date=new DateTime($value,new DateTimeZone('Asia/Kolkata'));
				$GMT = new DateTimeZone("GMT");
				$date->setTimezone($GMT);
				$StartTime = $date->format('Y-m-d H:i:s');
			}else if($key=='Duration'){
				$CallDuration = $value;
				$str_arr = explode(':',$value);
				if (count($str_arr)>1){
					$hour = $str_arr[0];
					$min = $str_arr[1];
				}
			}else if($key=='AgentName'){
				$aName = explode(" -> ", $value);
				$AgentName = $aName[0];
			}else if($key=='AgentID'){
				$aID = explode(" -> ", $value);
				$AgentID = $aID[0];
			}else if($key=='CallerID'){
				$CallerID = $value;
			}else if($key=='UUI'){
				$uui = $value;
				$str_arr = explode('|',$value);
				if (count($str_arr)>1){
					$parent_type = $str_arr[0];
					$parent_id = $str_arr[1];
				}
			}
			
			
		}
		//echo $id." ".$Type." ".$StartTime." ".$CallDuration." ".$AgentID." ".$CallerID." ".$parent_type." ".$parent_id." ".$hour." ".$min." ".$id." ".$retry_count." ".$uui." ".$campaign." ".$skill." ".$status1;

        $logger->log('debug', "Processing record# $id");
        $retry_count+=1;
        $call_type="";
        $url="";
        $status="Planned";
        $assigned_user_id="B45EECFC-088A-40E7-92EA-925873988A0C";
        $label=$CallerID;
        if($skill=='AOH')
        {
            $call_type="voice_mail";
            $label=$label." - Voice mails calls";
        }
        else{
            $call_type="abundant_calls";
            $label=$label." - Abundant calls";
		}
		$callid=create_guid();
        $query2 = "insert into calls (id, name, date_entered, date_start, description, duration_hours, duration_minutes, status, direction, parent_id, parent_type, assigned_user_id, calls_action,calls_type) values ('$callid', '$label',NOW(),'$StartTime','$url','$hour','$min','$status', '$Type','$parent_id','$parent_type','$assigned_user_id','$uui','$call_type')";
		$logger->log('debug', "Calls Query:".$query2);
		$as_res=$db->query($query2);
        if($as_res){
            $logger->log('debug', "Calls data inserted successfully");
            $query5 = "update call_details set processed=1,retry_count='$retry_count' where id='$id'";
			$res = $db->query($query5);
			$logger->log('debug', "query5:".$query5);
			if($res){
				$logger->log('debug', "Marked record $id as processed");
			}
        }
        else{
            $query5 = "update call_details set retry_count='$retry_count' where id='$id'";
				$res = $db->query($query5);
				$logger->log('debug', "query5:".$query5);
				if($res){
					$logger->log('debug', "Updated retry count as $retry_count for record $id");
                }
                continue;
		}
	}

  
	$logger->log('debug', "--------------process_call_details end----------");
	return true;
}