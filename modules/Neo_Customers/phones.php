<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
// ini_set('display_errors','On');
error_reporting(E_ERROR | E_PARSE);
$app_id = $_REQUEST['app_id'];
$app_id_arr = explode(",", $app_id);
$phone = $_REQUEST['phone'];
$customer_id = $_REQUEST['customerID'];
// die('here');
require_once('include/entryPoint.php');
global $db;

$bean = BeanFactory::getBean('Neo_Customers');
$query = "neo_customers.deleted=0 and neo_customers.customer_id =$customer_id";
$customer = $bean->get_full_list('',$query);
$customer = $customer[0];
//print_r($customer->all_phones);
//die();
if(!empty($customer)){
	require_once('CurlReq.php');

		$body = <<<HTML_FORM
		<style>
		td{
			border: 2px solid;
			padding: 5px;
		}
		</style>
		<br/><hr>
		<h1>Phone Numbers</h1><br/>
		<h2>Main Applicant: <i>$phone</i></h2> 
HTML_FORM;
		
		echo $body;

	foreach ($app_id_arr as $item) {
		$url = getenv('SCRM_AS_API_BASE_URL')."/moneytor/get_applicant_details/$item";
		$auth_token = "authorization:  Basic " . getenv('SCRM_MONEYTOR_API_AUTH_TOKEN');
		$cl = new CurlReq();
		$header = array(
			$auth_token,
			//Loca, Dev
		    // "authorization: Basic bmVvZ3Jvd3RoOnBhc3N3b3Jk",
		    // Prod
		    // "authorization: Basic bmVvZ3Jvd3RoOm0wbjN5K29SQG5lMEdyMFc3aA==",
		  );

		$res = $cl->curl_req($url,'get',null,$header);
		$arr = json_decode($res);
		// var_dump($arr);
		// echo $body;
		if(!empty($arr)){
			$body = "<style>
			td{
				border: 2px solid;
				padding: 5px;
			}
			</style><table>";
			if(!property_exists($arr,'status')){
			echo "<h2>Application ID: $item</h2>";

			$director_len = count($arr->associates->director);
			$director = $arr->associates->director;
			//var_dump($arr);
			for($i=0;$i<$director_len;$i++){
				// $body .= "hi";
				$body .= "<tr><td>Director ".intval($i+1)."</td>";
				if(isset($director[$i]->name) && !empty($director[$i]->name)){
					$body .= "<td><i>" . $director[$i]->name . "</i></td>";
				}
				else{
					$body .= "<td><i> N/A </i></td>";
				}
				if(property_exists($director[$i]->contact_number, 'landline'))
					$body .= "<td>Landline: <i>".$director[$i]->contact_number->landline."</i></td>";
				else
					$body .= "<td>Landline: <i> N/A </i></td>";
				if(property_exists($director[$i]->contact_number, 'mobile'))
					$body .= "<td>Mobile: <i>".$director[$i]->contact_number->mobile."</i></td>";
				else
					$body .= "<td>Mobile: <i> N/A </i></td>";
				$body .= "</tr>";
			}

			$len = count($arr->associates->reference);
			$ref = $arr->associates->reference;
			//var_dump($ref->contact_number);
			for($i=0;$i<$len;$i++){
				// $body .= "hi";
				$body .= "<tr><td>Reference ".intval($i+1)."</td>";
				if(isset($ref[$i]->name) && !empty($ref[$i]->name)){
					$body .= "<td><i>" .  $ref[$i]->name . "</i></td>";
				}
				else{
					$body .= "<td><i> N/A </i></td>";
				}
				if(property_exists($ref[$i]->contact_number, 'landline')){
					$body .= "<td>Landline: <i>".$ref[$i]->contact_number->landline."</i></td>";
				}
				else
					$body .= "<td>Landline: <i> N/A </i></td>";
				if(property_exists($ref[$i]->contact_number, 'mobile')){
					$body .= "<td>Mobile: <i>".$ref[$i]->contact_number->mobile."</i></td>";
				}
				else
					$body .= "<td>Mobile: <i> N/A </i></td>";
				$body .= "</tr>";
			}
			$body .="</table><hr>";
			echo $body;
			}//end if(!property_exists($arr,'status'))
		}//end if (!empty($arr)
	}//end of foreach


	//echo "Fetching data from renewal_cibil_trigger";
	// all_phones is user for duplicate checking
	$all_phones = array();
	// cibil_trigger_phones is used for displaying data, it contains phone no. and time stamp
	$all_phones_view = array();
	$query = "
		SELECT 
		name_1, name_2, name_3, name_4, name_5, 
		latest_phone, second_phone, date_updated 
		FROM renewal_cibil_trigger 
		WHERE as_app_id IN ($app_id) 
		";
	// echo "$query <br>";
	$res = $db->query($query);
	// print_r($res); echo "<br>";
	$body = "";
	if($res){
		$num = ($res->num_rows);
		for($i=0;$i<$num;$i++){
			$row = $db->fetchByAssoc($res);
			// print_r($row);
			// echo "<br>Cibil trigger<br>";

			$latest_phone = "-";
			$second_phone = "-";
			$date_updated = "-";
			$contact_name = "";
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
			if(!empty(trim($row['latest_phone']))){
				$latest_phone = trim($row['latest_phone']);
			}
			if(!empty(trim($row['second_phone']))){
				$second_phone = trim($row['second_phone']);
			}
			if(!empty(trim($row['date_updated']))){
				$date_updated = trim($row['date_updated']);
			}
			if(!empty($latest_phone) && strlen($latest_phone)>5 && $latest_phone!="NA" && $latest_phone!="N/A"
				&& $latest_phone!="-"){
				if(!in_array($latest_phone, $all_phones)){
					//dont change the position of date_updated, used for usort::date_compare
					//put the name in 5th position to get displayed
					array_push($all_phones_view, array($latest_phone, $date_updated, "", $contact_name));
					array_push($all_phones, $latest_phone);
				}
			}
			if(!empty($second_phone) && strlen($second_phone)>5 && $second_phone!="NA" && $second_phone!="N/A"
				&& $second_phone!="-"){
				if(!in_array($second_phone, $all_phones)){
					//dont change the position of date_updated, used for usort::date_compare
					//put the name in 3rd position to get displayed
					array_push($all_phones_view, array($second_phone, $date_updated, "", $contact_name));
					array_push($all_phones, $second_phone);
				}
			}
		}	
	}
	// print_r($all_phones_view);
	//echo "Fetching data from Soft Cibil";
	foreach ($app_id_arr as $item) {
		$edw_api_url = getenv('SCRM_EDW_API_BASE_URL');
		$url = $edw_api_url."/softcibil/get_telephone_details?application_ids=".$item;
		$cl = new CurlReq();
		$res = $cl->curl_req($url,'get',null,$header);
		$res = json_decode($res);
		//print_r($res);
		$soft_cibil_phones = array();
		if(!empty($res)){
			foreach ($res as $res_item) {
				$item1 = "";
				$item2 = "";
				if(isset($res_item[0]) && !empty($res_item[0])){
					//0 - phone number
					//1 - date updated
					//2 - app id
					//3 - name
					//synced the position with api results and view.
					$item1 = $res_item[0];
					$item2 = $res_item[1];
					if(empty(trim($res_item[3]))){
						$res_item[3] = "NA";
					}
					// $res_item[3] = $contact_name;
					// $res_item[3] = "API NOT UPDATED /softcibil/get_telephone_details";
				}
				$item1 = (string)trim($item1);
				$item1 = str_replace(",", "", $item1);
				if(!empty($item1) && strlen($item1)>5 && $item1!="NA" && $item1!="N/A"
				 && $item1!="-" && !in_array($item1, $all_phones)){
					array_push($all_phones, $item1);
					if(!empty($item2)){
						$res_item[1] = date('Y-m-d H:i:s',strtotime($res_item[1]));
					}
					array_push($all_phones_view, $res_item);
				}
			}
		}
	}   
	// print_r($all_phones_view); echo "<br>";
	usort($all_phones_view, 'date_compare');
	// print_r($all_phones_view); echo "<br>";
	// echo"<br>cibil data";
	$all_phones = implode(",", $all_phones);
	$customer->all_phones = $all_phones;
	$customer->save();
	if(empty($customer->all_phones)){
		$body .= "<h3>No data available</h3>";
	}
	$all_phones = $customer->all_phones;
	$all_phones = explode(',', $all_phones);

	echo " <div id='detailpanel_3' class='' style='overflow-x:auto;'>
            <h4>
                <a href='javascript:void(0)' class='collapseLink' onclick='collapsePanel(3);'>
                <img border='0' id='detailpanel_3_img_hide' src='themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
                <a href='javascript:void(0)' class='expandLink' onclick='expandPanel(3);'>
                <img border='0' id='detailpanel_3_img_show' src='themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
                Cibil Phone Data
                <script>
                    document.getElementById('detailpanel_3').className += ' expanded';
                </script>
            </h4>
                <table border='0' cellpadding='0' cellspacing='0' width='100%' class='panelContainer list view table default footable-loaded footable' >
		                <th scope='col'>
		                    <div style='white-space: normal;' align='left'>
		                            CONTACT PERSON
		                            &nbsp;&nbsp;
		                    </div>
		                </th>
		                <th scope='col'>
		                    <div style='white-space: normal;' align='left'>
		                            PHONE NUMBER
		                            &nbsp;&nbsp;
		                    </div>
		                </th>
		                <th scope='col'>
		                    <div style='white-space: normal;' align='left'>
		                            DATE UPDATED
		                            &nbsp;&nbsp;
		                    </div>
		                </th>
        "; 
	$i=0;
	foreach ($all_phones_view as $item) {
		$i++;
		$body .= "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
		$body .= "<td class='footable-visible footable-first-column'><i>$item[3]</i></td>";
		$body .= "<td class='footable-visible footable-first-column'><i>$item[0]</i></td>";
		$body .= "<td class='footable-visible footable-first-column'><i>$item[1]</i></td>";
		$body .= "</tr>";
	}
	$body .= "</table></div><br><hr>";
	//$customer->all_phones = implode(",", $all_phones);
	//$customer->save();	
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

