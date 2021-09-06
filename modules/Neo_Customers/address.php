<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
ini_set('display_errors','On');
$app_id = $_REQUEST['app_id'];
$app_id_arr = explode(",", $app_id);
$num = 0;
$as_api_base_url = getenv('SCRM_AS_API_BASE_URL');
// die('here');
require_once('include/entryPoint.php');
require_once('CurlReq.php');
global $db;
$body = "";

if(!empty($app_id_arr)){

    foreach ($app_id_arr as $app_id) {
    	$c = 0; 
		$total_address_list = array();
		$total_address_list_view = array();

	    //CIBIL TRIGGER ADDRESS FETCH
		$body .= "<h2>Application ID - ".$app_id."</h2>";
		$query = "
			SELECT 
			name_1, name_2, name_3, name_4, name_5, 
			latest_address_category, latest_address_1, latest_address_2,
			latest_address_3,latest_address_4,latest_address_5,
			latest_state_code, latest_pin_code, latest_address_residence_code, 
			second_address_category, second_address_1, second_address_2, 
			second_address_3,second_address_4,second_address_5,
			second_state_code, second_pin_code, second_address_residence_code, 
			date_updated 
			FROM renewal_cibil_trigger 
			WHERE as_app_id = '$app_id' ";
		$res = $db->query($query);
		// print_r($query); echo "<br>";
		// print_r($res); echo "<br>";
	    if($res){
			$num = ($res->num_rows);
			$c = $c + $num;
			if($num>0){
				$body .= "<h2>Cibil Trigger Address Details</h2>";
				$body .= "
						<table name = 'Cibil_Trigger_Address_Data' border='0' cellpadding='0' cellspacing='0' width='100%' class='panelContainer list view table default footable-loaded footable' >
								<div style='border-bottom:1px solid #dddddd; align:left;'>
					                <th scope='col'>
					                    <div style='white-space: normal;' align='left'>
					                            CATEGORY
					                            &nbsp;&nbsp;
					                    </div>
					                </th>
					                <th scope='col'>
					                    <div style='white-space: normal;' align='left'>
					                            CONTACT PERSON
					                            &nbsp;&nbsp;
					                    </div>
					                </th>
					                <th scope='col'>
					                    <div style='white-space: normal;' align='left'>
					                            ADDRESS
					                            &nbsp;&nbsp;
					                    </div>
					                </th>
					                <th scope='col'>
					                    <div style='white-space: normal;' align='left'>
					                            DATE UPDATED
					                            &nbsp;&nbsp;
					                    </div>
					                </th>
					            </div>";
			}

			for($i=0;$i<$num;$i++){
				$row = $db->fetchByAssoc($res);
				$latest_address_category = "NA";
				$latest_address_residence_code = "NA";
				$latest_address = "";
				$contact_name = "";
				$date_updated = trim($row['date_updated']);
				if(!empty(trim($row['name_3']))){
					$contact_name = trim($row['name_3']);
				}
				if(!empty(trim($row['name_1']))){
					$contact_name .= " " . trim($row['name_1']);
				}
				if(!empty(trim($row['name_2']))){
					$contact_name .= " " . trim($row['name_2']);	
				}
				if(!empty(trim($row['name_4']))){
					$contact_name .= " " . trim($row['name_4']);
				}
				if(!empty(trim($row['name_5']))){
					$contact_name .= " " . trim($row['name_5']);
				}
				if(empty($contact_name)){
					$contact_name = "NA";
				}
				if(!empty($row['latest_address_1']) && $row['latest_address_1'] != "NA")
					$latest_address .= $row['latest_address_1'].", ";
				if(!empty($row['latest_address_2']) && $row['latest_address_2'] != "NA")
					$latest_address .= $row['latest_address_2'].", ";
				if(!empty($row['latest_address_3']) && $row['latest_address_3'] != "NA")
					$latest_address .= $row['latest_address_3'].", ";
				if(!empty($row['latest_address_4']) && $row['latest_address_4'] != "NA")
					$latest_address .= $row['latest_address_4'].", ";
				if(!empty($row['latest_address_5']) && $row['latest_address_5'] != "NA")
					$latest_address .= $row['latest_address_5'].", ";
				if(!empty($row['latest_state_code']) && $row['latest_state_code'] != "NA")
					$latest_address .= $row['latest_state_code'].", ";
				if(!empty($row['latest_pin_code']) && $row['latest_pin_code'] != "NA")
					$latest_address .= $row['latest_pin_code'].".";
				$latest_address = trim($latest_address);
				if(!empty($row['latest_address_category']))
					$latest_address_category = $row['latest_address_category'];

				if(!empty($latest_address) && !in_array($latest_address, $total_address_list)){
					array_push($total_address_list, $latest_address);
					$body .= "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
					$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$latest_address_category."</i></td>";
					$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$contact_name."</i></td>";
					$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'>".$latest_address."</td>";
					$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$date_updated."</i></td></tr>";
				}

				$second_address_category = "NA";
				$second_address_residence_code = "NA";
				$second_address = "";
				if(!empty($row['second_address_1']) && $row['second_address_1'] != "NA")
					$second_address .= $row['second_address_1'].", ";
				if(!empty($row['second_address_2']) && $row['second_address_2'] != "NA")
					$second_address .= $row['second_address_2'].", ";
				if(!empty($row['second_address_3']) && $row['second_address_3'] != "NA")
					$second_address .= $row['second_address_3'].", ";
				if(!empty($row['second_address_4']) && $row['second_address_4'] != "NA")
					$second_address .= $row['second_address_4'].", ";
				if(!empty($row['second_address_5']) && $row['second_address_5'] != "NA")
					$second_address .= $row['second_address_5'].", ";
				if(!empty($row['latest_state_code']) && $row['latest_state_code'] != "NA")
					$second_address .= $row['latest_state_code'].", ";
				if(!empty($row['latest_pin_code']) && $row['latest_pin_code'] != "NA")
					$second_address .= $row['latest_pin_code'].".";
				$second_address = trim($second_address);
				if(!empty($row['second_address_category']))
					$second_address_category = $row['second_address_category'];

				if(!empty($second_address) && !in_array($second_address, $total_address_list)){
					array_push($total_address_list, $second_address);
					$body .= "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
					$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$second_address_category."</i></td>";
					$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$contact_name."</i></td>";
					$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'>".$second_address."</td>";
					$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$date_updated."</i></td></tr>";
				}
			}//for
			if($num>0){
				$body .="</table><br><br>";
			}	
		}//if($res)

		//AS ADDRESS FETCH
		$business_address = "NA";
		$contact_person_name = "NA";
		$date_updated = "NA";
		$date_updated = "API NOT UPDATED - /get_application_basic_details";
		$url = $as_api_base_url."/get_application_basic_details?ApplicationID=".$app_id;
		$ch = new CurlReq();
		$response = "";
		$response = $ch->curl_req($url);
		// print_r($url);
		// print_r($response);
		if($response){
			$json_response = json_decode($response, true);
			if(!empty($json_response) && count($json_response)>0){
				if(isset($json_response[0]['BusinessAddress']) && !empty($json_response[0]['BusinessAddress'])){
					$business_address = $json_response[0]['BusinessAddress'];
				}
				if(isset($json_response[0]['Contact Person Name']) && !empty($json_response[0]['Contact Person Name'])){
					$contact_person_name = $json_response[0]['Contact Person Name'];
				}
				if(isset($json_response[0]['Date Updated']) && !empty($json_response[0]['Date Updated'])){
					$date_updated = $json_response[0]['Date Updated'];
				}
			}
		}
		if($business_address != "NA" && !in_array($business_address, $total_address_list)){
			$c++;
			array_push($total_address_list, $business_address);
			$body .= "<h2>AS Address Details</h2>";
			$body .= "
					<table name = 'AS_Address_Data' border='0' cellpadding='0' cellspacing='0' width='100%' class='panelContainer list view table default footable-loaded footable' >
							<div style='border-bottom:1px solid #dddddd; align:left;'>
				                <th scope='col'>
				                    <div style='white-space: normal;' align='left'>
				                            CATEGORY
				                            &nbsp;&nbsp;
				                    </div>
				                </th>
				                <th scope='col'>
				                    <div style='white-space: normal;' align='left'>
				                            CONTACT PERSON
				                            &nbsp;&nbsp;
				                    </div>
				                </th>
				                <th scope='col'>
				                    <div style='white-space: normal;' align='left'>
				                            ADDRESS
				                            &nbsp;&nbsp;
				                    </div>
				                </th>
				                <!--<th scope='col'>
				                    <div style='white-space: normal;' align='left'>
				                            DATE UPDATED
				                            &nbsp;&nbsp;
				                    </div>
				                </th>-->
				            </div>";
			$body .= "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
			$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>OFFICE ADDRESS</i></td>";	
			$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$contact_person_name."</i></td>";
			$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$business_address."</i></td>";
			// $body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$date_updated."</i></td>";
			$body .= "</tr>";
			$body .="</table><br><br>";
		}
		
		//CIBIL SCRUB ADDRESS FETCH
		$scrub_address = array();
		$view_scrub_address = array();
		$edw_api_url = getenv('SCRM_EDW_API_BASE_URL');
		$url = $edw_api_url."/softcibil/get_address_details?application_ids=".$app_id;
		$ch = new CurlReq();
		$response = "";
		$response = $ch->curl_req($url);
		if($response){
			$json_response = json_decode($response, true);
			if(!empty($json_response) && count($json_response)>0){
				foreach ($json_response as $res) {
					$c++;
					$address = array();
					for($i=0;$i<3;$i++) {
						if(isset($res[$i]) && !empty($res[$i])){
							array_push($address, trim($res[$i]));
						}
					}
					$time_stamp = "NA";
					$contact_name = "";
					// $contact_name = "API NOT UPDATED. /softcibil/get_address_details";
					if(isset($res[5]) && !empty($res[5])){
						$time_stamp = date('Y-m-d H:i:s',strtotime($res[5]));
					}
					if(isset($res[6]) && !empty(trim($res[6]))){
						$contact_name =  $res[6];
					}
					if(isset($res[7]) && !empty(trim($res[7]))){
						$contact_name .= " " . $res[7];
					}
					if(isset($res[8]) && !empty(trim($res[8]))){
						$contact_name .= " " .  $res[8];
					}
					if(empty(trim($contact_name))){
						$contact_name = "NA";
					}
					if(!empty($address)){
						$address = implode(", ", $address);
						$address = trim($address);
						if(!in_array($address, $total_address_list)){
							array_push($scrub_address, $address);
							array_push($view_scrub_address, array($address, $time_stamp, $contact_name));
							array_push($total_address_list, $address);
						}
					}
				}
			}
		}
		usort($view_scrub_address, 'date_compare');
		if(!empty($view_scrub_address)){
		// if(empty($scrub_address)){
			$body .= "<h2>Cibil Scrub Address Details</h2>";
			$body .= "
					<table name = 'CIBIL_SCRUB_Address_Data' border='0' cellpadding='0' cellspacing='0' width='100%' class='panelContainer list view table default footable-loaded footable' >
							<div style='border-bottom:1px solid #dddddd; align:left;'>
				                <th scope='col'>
				                    <div style='white-space: normal;' align='left'>
				                            CATEGORY
				                            &nbsp;&nbsp;
				                    </div>
				                </th>
				                <th scope='col'>
				                    <div style='white-space: normal;' align='left'>
				                            CONTACT PERSON
				                            &nbsp;&nbsp;
				                    </div>
				                </th>
				                <th scope='col'>
				                    <div style='white-space: normal;' align='left'>
				                            ADDRESS
				                            &nbsp;&nbsp;
				                    </div>
				                </th>
				                <th scope='col'>
				                    <div style='white-space: normal;' align='left'>
				                            DATE UPDATED
				                            &nbsp;&nbsp;
				                    </div>
				                </th>
				            </div>";
			foreach ($view_scrub_address as $address) {
			// foreach ($scrub_address as $address) {
				$body .= "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
				$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>ADDRESS</i></td>";	
				$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$address[2]."</i></td>";
				$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$address[0]."</i></td>";
				$body .= "<td style='background-color:#f6f6f6;' valign='top' class='footable-visible footable-first-column'><i>".$address[1]."</i></td>";
				$body .= "</tr>";
			}
			$body .= "</table><br><hr>";
		}		
		//echo "<br>new length :: " . sizeof($total_address_list);
		//echo "<br>original length :: " . $c;
	}//for app_id_arr
	echo $body;
}//if(!empty($customer))
else{
	echo "<p style='color:red'>Unable to display information. Try again after some time. If its still the same contact IT admin / IT support for further clarifications.</p>";
}

function date_compare($a, $b)
{
	// compare based on position of date stamp
    $t1 = strtotime($a[1]);
    $t2 = strtotime($b[1]);
    return $t2 - $t1;
} 

