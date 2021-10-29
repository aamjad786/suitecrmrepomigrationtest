<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');

require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('custom/include/CurlReq.php');

$curl_req = new CurlReq();

	function getUserName($user_name){

        $user = BeanFactory::getBean('Users',$user_name);
        if($user)
            return $user->first_name." ".$user->last_name;
        return "";
    }


    	global $current_user;
    	$roles = ACLRole::getUserRoleNames($current_user->id);

    	
    	echo $html = <<<HTMLFORM
		<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">
		<h1><center><b>Customer Application Profile View</b></center></h1>
		<form action="$_SERVER[REQUEST_URI]" method='post'>
		<table>
		<tr>
			<td>Application ID: &nbsp</td>
			
			<td><input type='text' name='applicationID' id='applicationID' value='$_REQUEST[applicationconID]'/></td>
			<td colspan="1"><input type='submit' value='Get Details' id='details' name='details'/></td>
		</tr>
		<tr>
		<td id="deal_link">
		<a target="_blank" href="index.php?module=Cases&action=covid19&app_id=$_REQUEST[applicationID]"><b>View Deal Details</b></a>
		</td>
		</tr>
		</table>
			
		</form>
HTMLFORM;

		$app_id = $_REQUEST['applicationID'];
    	$as_api_base_url = getenv('SCRM_AS_API_BASE_URL');

		if(!empty($app_id)){
			global $db;	
			$query  = "select * from cases LEFT JOIN cases_cstm cs ON cases.id=cs.id_c where merchant_app_id_c = '$app_id'";
			$result = $db->query($query);
			$row    = $db->fetchByAssoc($result);

			if(!empty($row)){

				$partner_name_c = $row['partner_name_c'];
				$fi_business_c = $row['fi_business_c'];

			}
		}
		

//Personal Details
include_once('customer_application_profile/cap_panel_1.php');

// Loan Details
include_once('customer_application_profile/cap_panel_2.php');

//Other Details
include_once('customer_application_profile/cap_panel_3.php');

//Queries/Requests/Complaints
include_once('customer_application_profile/cap_calls.php');
include_once('customer_application_profile/cap_cases.php');


		echo $HTML = <<<DISP6
			</table>
			<script type="text/javascript">SUGAR.util.doWhen("typeof initPanel == 'function'", function() { initPanel(1, 'expanded'); }); </script>
		</div>
		</div>
		<br>
DISP6;

$full_view=false;
include_once('customer_application_profile/cap_document_requests.php');

//Email Statements
include_once('customer_application_profile/cap_email_statements.php');

//SMS Merchant
include_once('customer_application_profile/cap_sms_merchants.php');

//SMS/EMAIL Payment Link
include_once('customer_application_profile/cap_payment_link.php');

		echo $HTML = <<<DISP9
		<br>
		<br>
		<h2> Color Coding Reference </h2>
			<p><b><span title='Green' style="font-size:15px;color:green">■</span>&nbsp&nbsp DPD: Less than 30 </b></p>
			<p><b><span title='Yellow' style="font-size:15px;color:yellow">■</span>&nbsp&nbsp DPD: 30 to 60</b></p>
			<p><b><span title='Red' style="font-size:15px;color:red">■</span>&nbsp&nbsp DPD: More than 60</b></p>
			<p><b><span title='Black' style="font-size:15px;color:black">■</span>&nbsp&nbsp DPD field is empty/invalid</b></p>
DISP9;

	

