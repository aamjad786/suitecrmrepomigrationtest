<?php

$job_strings[] = 'process_call_details';
date_default_timezone_set('Asia/Kolkata');

function process_call_details() {
	global $db;
	$query = "select * from call_details where processed=0 and retry_count<5";
	$results = $db->query($query);
	$fp = fopen('Logs/Request.log', 'a');
	fwrite($fp, "\n\n--------------process_call_details----------");
	fwrite($fp, "\n".date('Y-m-d H:i:s'));
	while($row=$db->fetchByAssoc($results)){

		$AudioFile=$Type=$StartTime=$CallDuration=$AgentID=$CallerID=$parent_type=$parent_id=$hour=$min=$id=$retry_count=$uui="";

		foreach($row as $key=>$value){
			if($key=='id'){
				$id = $value;
			}else if($key=='retry_count'){
				$retry_count = (intval($value));
			}
			else if($key=='AudioFile'){
				$AudioFile = $value;
			}else if($key=='Type'){
				if($value=='Manual')
					$Type = 'Outbound';
				else
					$Type = $value;
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
		fwrite($fp,"\n\nProcessing record# $id");
		$retry_count+=1;
		$url ="";
		$new_file_name="";
		if(!empty($AudioFile)){

			fwrite($fp, "\nold file link:".$AudioFile);
			$file_base_name = baseName($AudioFile);
			$file_base_name = str_replace('neogrowth_','',$file_base_name);
			$media_output = file_get_contents(trim($AudioFile));
			$url  = "";
			if(!empty($media_output)){
			    $new_file_name = 'file_'.$parent_type.'_'.$parent_id.'_'.$file_base_name;
			    $actual = $new_file_name;
				$file = file_put_contents($actual, $media_output);
		        $path = "ozonetel/".date('Ym');
		        $bucket = "neo-ivrs-recordings-prod";
		        $url = uploadDocToS3ApiAudio($actual, $new_file_name, $path, 'calls', $bucket);
		        unlink($new_file_name);
		    }else{
		    	fwrite($fp,"\nUnable to download file.\n");
		    }
		    
			fwrite($fp, "\nnew file link:".$url);
		}

		if(empty($url)){
			fwrite($fp, "\nCouldn't get the new s3 so not proceeding further");
			$query5 = "update call_details set retry_count='$retry_count' where id='$id'";
			$res = $db->query($query5);
			fwrite($fp, "\nquery5:".$query5);
			if($res){
				fwrite($fp, "\nUpdated retry count as $retry_count for record $id");
			}
			continue;
		}
		$as_res = 0;
		if($parent_type == 'AS'){
			fwrite($fp, "\nIts AS Triggred call ");
		    $as_res = postDataToAS($row,$url);
		}else{
			fwrite($fp, "\nIts CRM Triggred call");
			$user_id_query = "select id from users where user_name = '$AgentID'";
			// echo $user_id_query;
			$res = $db->fetchOne($user_id_query);
			$assigned_user_id = null;
			if($res){
				$assigned_user_id = $res['id'];
			}
			if($parent_type!='Calls'){
			
				$query2 = "insert into calls (id, name, date_entered, date_start, description, duration_hours, duration_minutes, status, direction, parent_id, parent_type, assigned_user_id, calls_action) values (UUID(), 'IVRS call: $CallerID',NOW(),'$StartTime','$url','$hour','$min','Held', '$Type','$parent_id','$parent_type','$assigned_user_id','$uui')";

				fwrite($fp, "\n\nCalls Query:".$query2);
				$as_res = $db->query($query2);
				if($as_res){
					fwrite($fp, "\n\nCalls data inserted successfully");
				}
			}else{
				$call = BeanFactory::getBean('Calls',$parent_id);
				$call->description .= $url;
				$call->status = "Held";
				$call->save();
			}

		}

			
			
		
		if($as_res){
			$query5 = "update call_details set processed=1,retry_count='$retry_count' where id='$id'";
			$res = $db->query($query5);
			fwrite($fp, "\nquery5:".$query5);
			if($res){
				fwrite($fp, "\nMarked record $id as processed");
			}
		}
		
	}
	fwrite($fp, "\n\n--------------process_call_details end----------");
	return true;
}

function uploadDocToS3ApiAudio($tmpfile, $filename, $path, $application, $bucket){
	$fp = fopen('Logs/Request.log', 'a');
    $filePath = curl_file_create($tmpfile, "audio/mpeg", $filename);
    if (!empty($filePath)) {
        $as_api_url = getenv('AWS_API_UTILITY_URL')."/aws_upload";
        $params =array('application' => $application, 'bucket' => $bucket, 'path' => $path, 'files'=>$filePath);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$as_api_url);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        // fwrite($fp,"\n ".print_r($result,true));
        $resultArray = json_decode($result, true);

        $completeUrl = $resultArray['download_url'];
        $array = explode("?", $completeUrl, 2);
        $url = $array[0];
        fwrite($fp,"\nConverted url = $url");
        fclose($fp);
        return $url;
    }
}
function postDataToAS($input_array, $s3_file_url){
	if(empty($s3_file_url))return 0;
	$fp1 = fopen('Logs/Request.log', 'a');
	fwrite($fp1, "\n------- Inside postDataToAS() -------");
	// echo "<br>inside end";
	$s3_file_url_arr = parse_url($s3_file_url);
	fwrite($fp1, "\n" . "s3_file_url_arr: ".print_r($s3_file_url_arr,true));
	$s3_path = $s3_file_url_arr['path'];
	$input_array['AudioFile'] = $s3_path;

	$json_to_post = json_encode($input_array);
	$response = false;
    $ch = curl_init();
    $as_url = getenv('SCRM_AS_API_OZONTEL_URL');
    if(empty($as_url)){
    	fwrite($fp1, "\n AS URL is empty. env required SCRM_AS_API_OZONTEL_URL");
    	return;
    }
    $as_api_url = $as_url."/api/Inductioncall/PostInsertInductioncallDetails";
    fwrite($fp1, "\nas_api_url = $as_api_url" );
    curl_setopt($ch, CURLOPT_URL,$as_api_url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_to_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// die('done, not sent');
    $result = curl_exec($ch);
    fwrite($fp1, "\n" . "as post result: ".print_r($result,true));
    fclose($fp1);
    return 1;
}