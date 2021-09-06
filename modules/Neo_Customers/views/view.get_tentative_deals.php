<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
// ini_set('display_errors','On');
ini_set('memory_limit','-1');
// require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');


class Neo_CustomersViewget_tentative_deals extends SugarView {
    

    function __construct(){    
        parent::SugarView();
    }


    function displayForm($bean){
    	$customer_id = $bean->customer_id;
    	$upfront_deduction_app_list = $_REQUEST['upfront_deduction_app_list'];
    	if(empty($upfront_deduction_app_list))
    	$upfront_deduction_app_list = $bean->upfront_deduction_app_list;
    	$app_id_list = $bean->app_id_list;
    	$live_app_id_count = 0;
    	if(!empty($upfront_deduction_app_list)){
	    	// $loan_status_list = explode(",",$bean->loan_status_list);
	    	$app_id_list = explode(",",$upfront_deduction_app_list);
	    	$app_id_count = count($app_id_list);
	        
        }
        $upfront_count_options = "";
        for($i=0;$i<=$app_id_count;$i++){
        	$upfront_count_options .= "<option value='$i'>$i</option>"; 
        }
        $parallel_count_options = "";
        for($i=0;$i<=10;$i++){
        	$parallel_count_options .= "<option value='$i'>$i</option>"; 
        }


        $users1 = BeanFactory::getBean('Users');
	    $query1 = 'users.deleted=0';
	    $items = $users1->get_full_list('',$query1);
	    $credit_users_options = "";
    	$internal_user_options = "";
	    if ($items){
	   		foreach ($items as $item) {
	   			$userBean = new User();
	   			$userBean->retrieve($item->id);
	   			$item = $userBean;
	   			$name = $item->first_name." ".$item->last_name;
	    		$department = $item->department;
	    		$email = $item->email1;
	    		$user_name = $item->user_name;
	    		// var_dump($row);
	    		if(true || $department=='Credit'){
	    			$credit_users_options .= "<option value='$email'> $name, $user_name, $department</option>";
	    		}
	    		if(true || $department=='Renewals'){
	    			$internal_user_options .= "<option value='$email'> $name, $user_name, $department</option>";
	    		}
	   		}
	   		
    	}


    ?>
		<style>
		table td{
			padding:5px;
		}
		</style>
		
<?php
		global $current_user;
		$current_user_email=$current_user->email1;
		$latest_app_id = substr(trim($bean->app_id_list),-7);
        echo $html = <<<HTMLFORM
		<h1><center><b>Tentative Deal Form</b></center></h1>
		<form id='renewal_form' action="#" method='post'>
		<table>
		<tr>
			<td>Customer ID:</td>
			<td><input type='text' name='customer_id' id='customer_id' value='$customer_id' readonly="readonly"/></td>
		</tr>	
		<tr>
			<td>Latest app ID:</td>
			<td><input type='text' name='app_id' id='app_id' value='$latest_app_id' readonly="readonly"/>(*This will be used if upfront app ids are not present)</td>
		</tr>
		<tr>

			<td>Upfront Deduction IDs:</td>
			<td>
			<input type="text" id="upfront_deduction_app_list" name="upfront_deduction_app_list" value="$upfront_deduction_app_list" readonly="readonly"/>
			</td>
		</tr>
		<tr>
			<td>Count of app id to be renewed:</td>
			<td>

			<select name='upfront_count' id='upfront_count' >
				$upfront_count_options
				</select>
			</td>
		</tr>
		<tr>
			<td>Count of parallel Loan to be created:</td>
			<td>
			<select name='parallel_count' id='parallel_count' >
				$parallel_count_options
				</select>
			</td>
		</tr>
		
		<tr>
			<td>Credit Resource:</td>
			<td>
				<input list="credit_resource" name="credit_resource" /></label>
				<datalist id="credit_resource">$credit_users_options
				</datalist>
			</td>
		</tr>
		<tr>
			<td>Internal User:</td>
			<td>
				<input list="internal_user" name="internal_user" /></label>
				<datalist id="internal_user">$internal_user_options
				</datalist>
			</td>
		</tr>
		<tr>
			<td>Current User:</td>
			<td>
				<input type="text" id="AddedBy" name="AddedBy" value="$current_user_email" readonly="readonly"/>
			</td>
		</tr>
		<tr>
			<td>Remarks:</td>
			<td>
				<textarea id="remarks" name="remarks" value="" ></textarea>
			</td>
		</tr>
		<tr>
			<td></td><td colspan="1"><input type='submit' value='Submit' id='submit1' name='submit1'/></td>
		</tr>
		</table>
		</form>
		<br>
		<h5>Status</h5>
		<br>
HTMLFORM;
    }



    function display(){
    	if(empty($_POST['submit1'])){
	    	$GLOBALS['log']->debug("check upfront_deduction:: " . $_REQUEST['upfront_deduction_list']);
			$upfront_deduction_list = $_REQUEST['upfront_deduction_list'];
			$customer_id = $_REQUEST['customer_id']; 
			$id = "";
			if(!empty($customer_id)){
				echo "<div>";
				$bean = BeanFactory::getBean('Neo_Customers');
				$query = "neo_customers.deleted=0 and neo_customers.customer_id=$customer_id";
				$items = $bean->get_full_list('',$query);
				if(empty($items)){
					$GLOBALS['log']->error("Customer ID is not found " . $customer_id);
					echo "Unable to update data for this Customer ID - $customer_id. Please contact IT team";
				}
				foreach ($items as $item) {
					$item->upfront_deduction_app_list = $upfront_deduction_list;
					$item->save();
					echo "Data updated successfully for this Customer ID - $customer_id";
					echo "<br>";
					echo "Upfront Deduction App ID for this Customer - $upfront_deduction_list";
					// echo "hello";
					echo "</br/><br/>";
					$id = $item->id;

	            	break;

				}

			}
			else{
				$GLOBALS['log']->error("Empty customer_id");
				echo "Unable to update data. Please contact IT team";
			}
			echo "</div>";
		

	    	if(!empty($id)){
	    		$bean = BeanFactory::getBean('Neo_Customers',$id);
	    		if($bean){
	    			require_once('modules/Neo_Customers/Renewals_functions.php');
                    $renewals = new Renewals_functions();
		      		$is_eligible = $renewals->isEligibleForTentativeOffer($bean);
		    		if($is_eligible){
		    			$this->displayForm($bean);
		    		}
		    		else{
		    			echo "<p>Customer not eligible for tentative offer.<p>";
		    		}	
	    		}
	    		else{
	    			echo "<p>Customer Not Available.<p>";
	    		}
	    	}
	    	else{
	    		echo "<p>Unable to load. Kindly Contact Admin.<p>";
	    	}
	    }else{
		   $upfront_count = $_POST['upfront_count'];
		   $app_id = $_POST['app_id'];
			$parallel_count = $_POST['parallel_count'];
			$upfront_deduction_app_list = $_POST['upfront_deduction_app_list'];
			$credit_resource = $_POST['credit_resource'];
			$internal_user = $_POST['internal_user'];
			$customer_id = $_POST['customer_id'];
			// global $current_user;
			$current_user_email=$_POST['AddedBy'];//$current_user->email1;
			if(empty($upfront_deduction_app_list)){
				$upfront_deduction_app_list = $app_id;
			}
			$VerificationKey = getenv('SCRM_AS_VERIFICATION_KEY');
			$remarks = "";
			$json_data = [
				"ApplicationId"=>$upfront_deduction_app_list,
				"UpfrontCount"=>$upfront_count,
				"ParallelCount"=>$parallel_count,
				"CamId"=>$internal_user,
				"CreditResource"=>$credit_resource,
				"AddedBy"=>$current_user_email,
				"Customerid"=>$customer_id,
				"Remark"=>$remarks,
				"VerificationKey"=>$VerificationKey
			];
			require_once('modules/Neo_Customers/Renewals_functions.php');
            $renewals = new Renewals_functions();
			$encoded_data = json_encode($json_data);
			
			echo "<br/><hr>	<h2>Data sent to AS:</h2><br/>";
			$renewals->printJson(($encoded_data));
			require_once('CurlReq.php');
			$CurlReq = new CurlReq();
			$output = $CurlReq->curl_req(getenv('SCRM_AS_URL').'/api/Renewal/PostSaveRenewalQueueForCRMAPI','post',$encoded_data);

			// var_dump($output);
			echo "<br/><hr>	<h2>Response received from AS:</h2><br/>";
        
			$arr = json_decode($output);
			if(!is_array($arr)){
				echo "Some error occured";
			}else{

				$customers = BeanFactory::getBean('Neo_Customers')->get_full_list('', "deleted=0 and customer_id='$customer_id'");
				if(!empty($customers)){
		        	$customer = $customers[0];
		        }
		        $new_app_ids= "";
		        $tentative_offer="";

		        foreach($arr as $arr1){
		            echo "<br/>";
		            $arr = array($arr1);
		            foreach($arr1 as $k=>$v){
		                echo "<b>$k:</b> <i>$v</i><br/>";

		            }
		        }
		        $customers = BeanFactory::getBean('Neo_Customers')->get_full_list('', "deleted=0 and customer_id='$customer_id'");
		        if(!empty($customers)){
		        	$customer = $customers[0];

		                if($k=='NewApplicationID'){
		                	$new_app_ids .= $v.',';
		                }else if($k=='TentativeOffer'){
		                	$tentative_offer=$v;
		                }
		            }
		        }
		        if(!empty($customer)){
		        	if(!empty($new_app_ids)){
		        		$customer->renewed_app_id = substr($new_app_ids,  0, -1);
		        	}
		        	if(!empty($tentative_offer)){
		        		$customer->renewal_eligiblity_amount=$tentative_offer;
		        	}
		        	global $timedate;
		        	$customer->tentative_offer_requested=1;
		        	$customer->tentative_offer_requested_time = $timedate->getNow(true)->asDb(false);
		        	$customer->save();

		        }
		    }
            // $renewals->printJson1(($output));
	    }
	}


 ?>