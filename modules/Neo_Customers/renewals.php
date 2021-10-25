<?php

if(!defined('sugarEntry'))define('sugarEntry', true);

require_once('include/entryPoint.php');
ini_set('display_errors','On');
// mb_internal_encoding("8bit");

ini_set('max_execution_time', 300000);

global $db;
require_once 'excel_reader2.php';

    function isRenewalsUser($roles){
        $results = false;
        if(empty($roles)) {
            return $results;
        }
        foreach ($roles as $role) {
            if(stripos($role,"Renewal") !== false){
                $results = true;
                break;
            }
        }   
        return $results;
    }
global $current_user, $sugar_config;
$roles = ACLRole::getUserRoleNames($current_user->id);
$permitted_users = $sugar_config['renewals_permitted_user'];
if (!$current_user->is_admin  && !(in_array(strtoupper($current_user->user_name), $permitted_users)) && !isRenewalsUser($roles)) {
    die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
}

function fetchCustomerId($app_id){
	global $db;
	$query       = "SELECT customer_id FROM neo_customers WHERE app_id_list like '%$app_id%' and deleted =0";
	//print_r($query);
	//echo"<br>";
	$result      = $db->query($query);
	$row         = $db->fetchByAssoc($result);
	$customer_id          = $row['customer_id'];
	return $customer_id;
}

function fetchLoanStatusList($app_id_list,$loan_status_list){
	$result = array();
	if(!empty($app_id_list) && isset($loan_status_list)){
		$app_id_list = explode(",", $app_id_list);
		$loan_status_list = explode(",", $loan_status_list);
		if(sizeof($app_id_list) == sizeof($loan_status_list)){
			$result = array_combine($app_id_list,$loan_status_list);
		}
		else{
			$GLOBALS['log']->error("app_id_list size = ".sizeof($app_id_list).
				" & loan_status_list = ". sizeof($loan_status_list) . ".Should be same size ");
		}
	}
	return $result;
}

$body = "";
$body = <<<HTML2
<br/>

<h1>Disposition File & Upfront Deduction File Upload</h1><br/>
<a href="csvUpload/DispositionUpdate.xls" download>
Download sample file </a><br/><br/>
<form action="" id="uploadForm" method="post" enctype="multipart/form-data">
    <div class="form-group">
    	<label for="sheet">Select file name to upload :</label>
    	<p><b>Note:</b> Only spreadsheets(.xls) are accepted. Use the sample file to upload data</p><br>
    	<input type="file" id="sheet" name="sheet" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/> 
    </div>
    <input type="submit" value="Submit" name="submit"><br/><br/>
</form>
HTML2;

echo $body;
$count = 0;

if(isset($_POST["submit"])){

	require_once 'excel_reader2.php';
	$target_dir = "upload/";
	$errors = array();
	$disp_arr = array(
		'Not_Yet_Contacted'	=> 'not_yet_contacted',
		'Not_Contactable'	=> 'not_contactable',
		'Interested_WIP'	=> 'interested',
		'Not_Interested'	=> 'not_interested',
		);
	
	$sub_disp_arr = array(
	  'Not_Yet_Contacted' 	=> array(),
	  'Not_Contactable' 	=> array(
	  	'Ringing' 		=> 'not_contactable_ringing',
		'Call Back' 	=> 'not_contactable_call_back',
		'Busy' 			=> 'not_contactable_busy',
		'Switched Off' 	=> 'not_contactable_off',
		'Wrong Number' 	=> 'not_contactable_wrong',
		'Invalid' 		=> 'not_contactable_invalid',
	  ),
	  'Not_Interested' 		=> array(
	    'No Fund Required'		=> "not_interested_no_fund",
	    'Low Flat ROI' 			=> "not_interested_low",
	    'High Tenure' 			=> "not_interested_tenure",
	    'Service Issues' 		=> "not_interested_service",
	    'Reducing Rate' 		=> "not_interested_rate",
	    'Higher Loan Amount' 	=> "not_interested_higher_amount",
	    'Business Seasonality' 	=> "not_interested_business"
	  ),
	  'Interested_WIP' 		=> array()
	);

	if(!empty($_FILES["sheet"])){
		$file = $_FILES["sheet"]["tmp_name"];
		$file_name = $current_user->user_name . "_" .time(). "_" .$_FILES['sheet']['name'];
		$file_size = $_FILES['sheet']['size'];
		$file_tmp = $_FILES['sheet']['tmp_name'];
		$file_type = $_FILES['sheet']['type'];
		$file_ext = strtolower(end(explode('.',$_FILES['sheet']['name'])));
		//echo "file name " . basename($file_name) . "<br>";
		
		if (!empty($file_name)) {
			$extensions= array("xls");
			if(in_array($file_ext,$extensions) === false){
				$errors[]="Extension not allowed, please choose an xls file. ($file_ext is not supported)";
			}

			if($file_size > 2097152){
				$errors[]='File size must be less 2 MB';
			}

			if(empty($errors)){
		        move_uploaded_file($file_tmp,$target_dir.$file_name);
		    }else{
		    	echo "<p style='color:red'>File upload failed please try again. If problem persists contact IT team.</p>";	
		       	for ($i=0; $i < sizeof($errors); $i++) { 
		       		echo "<p>$errors[0]</p>";
		       	}
		    }
			$target_file = $target_dir . basename($file_name);
			try{
				$data = new Spreadsheet_Excel_Reader($target_file);	
			}
			catch(Exception $e){
				$GLOBALS['log']->debug("Renewals file upload exception :: " . $e->getMessage());
			}
			$column_list = $data->sheets[0]['cells'][1];
			$length = $data->rowcount();
			$customer_id_row = array_search('Customer ID', $column_list);
			$app_id_row = array_search('App ID', $column_list);
			$disposition_row = array_search('Disposition', $column_list);
			$sub_disposition_row = array_search('Sub Disposition', $column_list);
			$up_app_id_1_row = array_search('App Id 1 - Upfront deduction', $column_list);
			$up_app_id_2_row = array_search('App Id 2 - Upfront deduction', $column_list);
			//echo ("Uploaded records = " . ($length) . "<br>");
			$total_count = 0;
			//print_r($data->dump($row_numbers=true,$col_letters=false,$sheet=0,$table_class='excel'));

			for($i=2;$i<=$length;$i++){
				//echo "row : $i<br>";
				$total_count++;
				$customer_id 		= "";
				$app_id 			= "";
				$disposition 		= "";
				$up_app_id_1 		= "";
				$up_app_id_2 		= "";
				$sub_disposition	= "";
 
				if(isset($data->sheets[0]['cells'][$i][$customer_id_row]))
					$customer_id = trim((string)$data->sheets[0]['cells'][$i][$customer_id_row]);
				if(isset($data->sheets[0]['cells'][$i][$app_id_row]))
					$app_id 	 = trim((string)$data->sheets[0]['cells'][$i][$app_id_row]);
				if(isset($data->sheets[0]['cells'][$i][$disposition_row]))
					$disposition = trim((string)$data->sheets[0]['cells'][$i][$disposition_row]);
				if(isset($data->sheets[0]['cells'][$i][$sub_disposition_row]))
					$sub_disposition = trim((string)$data->sheets[0]['cells'][$i][$sub_disposition_row]);
				if(isset($data->sheets[0]['cells'][$i][$up_app_id_1_row]))
					$up_app_id_1 = trim((string)$data->sheets[0]['cells'][$i][$up_app_id_1_row]);
				if(isset($data->sheets[0]['cells'][$i][$up_app_id_2_row]))
					$up_app_id_2 = trim((string)$data->sheets[0]['cells'][$i][$up_app_id_2_row]);
				//echo "$i=>$customer_id,$app_id,$disposition,$up_app_id_1,$up_app_id_2<br>";
				if(empty($customer_id) && empty($app_id) && empty($disposition) && empty($up_app_id_1) && empty($up_app_id_2)){
					//to avoid logging. Loop will run for min 7 times because of the disposition list placed in the sheet as list source 
					continue;
				}
				if(empty($customer_id) && empty($app_id)){
					echo "<p style='color:red'>Rownum#$i. No Customer ID nor App ID found</p>";
					continue;
				}
				else if(empty($customer_id)){
					if(strlen($app_id) != 7){
						echo "<p style='color:red'>Rownum#$i. App ID entered is invalid</p>";
						continue;
					}
					$customer_id = fetchCustomerId($app_id);
					if(empty($customer_id)){
						echo "<p style='color:red'>Rownum#$i. No Customer ID found for this App ID $app_id</p>";
						continue;
					}
				}
				$bean = BeanFactory::getBean('Neo_Customers');
				$query = 'neo_customers.deleted=0 and neo_customers.';//customer_id = "1"';
				$query = $query."customer_id='$customer_id'";
				$items = $bean->get_full_list('',$query);

				if(!empty($items)){
					$item = $items[0];
					$app_id_list = "";
					if(!empty($item)){
				    	// print_r($item->customer_id);
				    	// die();
				    	$app_id_list = $item->app_id_list;
				    	$upfront_deduction_app_list = array();
				    	$loan_status_list = fetchLoanStatusList($app_id_list,$item->loan_status_list);
				    }
				    else{
				    	echo "<p style='color:red'>Rownum#$i. No Customer ID found</p>";
				    }
				    $disposition_logic_failed = false;                 
				    if(!empty($disposition)){
					    if(array_key_exists($disposition, $disp_arr)){
						    $item->disposition = $disp_arr[$disposition];
						    if(!empty($sub_disposition)){
						    	if(array_key_exists($sub_disposition, $sub_disp_arr[$disposition])){
						    		$item->subdisposition = $sub_disp_arr[$disposition][$sub_disposition];
						    	}
						    	else{
						    		$item->subdisposition = '';
						    		$disposition_logic_failed = true;
						    		echo "<p style='color:red'>Rownum#$i Sub Disposition entered doesn't match the given list.</p>";
						    	}
						    }
						    else{
						    	if(!empty($sub_disp_arr[$disposition])){
						    		$disposition_logic_failed = true;
						    		echo "<p style='color:red'>Rownum#$i Sub Disposition is empty</p>";
						    	}
						    }
						}
						else{
							$disposition_logic_failed = true;
							echo "<p style='color:red'>Rownum#$i Disposition entered doesn't match the given list.</p>";
						}
					    if($disposition_logic_failed){
					    	//if disposition data is incorrect, no need to look for upfront deduction app ids
					    	continue;
					    }
					    else{
					    	//disposition & sub disposition data are good. Display success messages and move on
					    	echo "Rownum#$i Updated Disposition<br/>";
					    	if(!empty($sub_disposition)){
					    		echo "Rownum#$i Updated Sub Disposition<br/>";
					    	}
					    }	
				    }
				    if(!empty($up_app_id_1) || !empty($up_app_id_2)){
						if(!empty($app_id_list)){
						    if(!empty($loan_status_list)){
								if(!empty($up_app_id_1)){
									if(stripos($app_id_list,$up_app_id_1)===false){
										echo "Rownum#$i App id 1 entered doesn't correspond to customer<br/>";
									}else if($loan_status_list[$up_app_id_1] == 'Y'){ //loan status Y => closed, 0=> live
										echo "Rownum#$i App id 1 entered is closed. Upfront deduction cant be updated.<br/>";
									}
									else{
										array_push($upfront_deduction_app_list, $up_app_id_1);
										echo "Rownum#$i Updated upfront deduction for app id $up_app_id_1<br/>";
									}
								}
								if(!empty($up_app_id_2) && $up_app_id_2!=$up_app_id_1){
									if(stripos($app_id_list,$up_app_id_2)===false){
										echo "Rownum#$i App id 2 entered doesn't correspond to customer<br/>";
									}else if($loan_status_list[$up_app_id_2] == 'Y'){ //loan status Y => closed, 0=> live
										echo "Rownum#$i App id 2 entered is closed. Upfront deduction cant be updated.<br/>";
									}else{
										array_push($upfront_deduction_app_list, $up_app_id_2);
										echo "Rownum#$i Updated upfront deduction for app id $up_app_id_2<br/>";
									}
								}
							}else{
						    	echo "Rownum#$i Failed to fetch loan status for customer $customer_id. Upfront deduction cant be updated, Contact IT team.<br/>";
						    }
						}
						else{
							//app_id_list may be empty check why
							echo "<p style='color:red'>Rownum#$i. No App ID found for this Customer ID $customer_id</p>";
						}
					}//if(!empty($up_app_id_1) && !empty($up_app_id_2))
				    if(!empty($upfront_deduction_app_list)){
				    	$item->upfront_deduction_app_list = implode(",",$upfront_deduction_app_list);	
				    }

					$item->save();
				}//if(!empty($items))
				else{
			    	echo "<p style='color:red'>Rownum#$i. No Customer ID found</p>";
			    }
			}//for
			echo "Updation process completed";
			//echo "target file" . $target_file . "<br>";
			if (file_exists($target_file)) {
				if (!unlink($target_file)){
					$GLOBALS['log']->error("Error deleting $target_file");
				}
				else{
					$GLOBALS['log']->debug("Deleted $target_file");
				}
			}
			else{
				$GLOBALS['log']->error("Error deleting $target_file, File not found error");
			}
		}//if (!empty($file_name))
		else{
			echo "<p style='color:red'>Please choose a file</p>";
		}
	}//if(!empty($_FILES["sheet"]['name']))
}//end if(isset($_POST["submit"]))





		 
