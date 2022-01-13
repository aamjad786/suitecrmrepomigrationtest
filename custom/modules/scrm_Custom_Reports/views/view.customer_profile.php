<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');

require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once 'custom/include/SendEmail.php';
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('include/MVC/View/views/view.detail.php');
class scrm_Custom_ReportsViewcustomer_profile extends ViewDetail {
	
	private $chartV;

    function __construct(){    
        parent::__construct();
    }

    // function curl_req($url){
	// 	$ch = curl_init();
	// 	curl_setopt($ch, CURLOPT_URL, $url);
	// 	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// 	$output = curl_exec($ch);
	// 	curl_close($ch);
	// 	return $output;
	// }

	function getUserName($user_name){
        $user = BeanFactory::getBean('Users',$user_name);
        if($user)
            return $user->first_name." ".$user->last_name;
        return "";
    }

    function display(){	
    	global $current_user, $sugar_config;
    	$roles = ACLRole::getUserRoleNames($current_user->id);
    	$permitted_users = $sugar_config['CR_cust_profile_permitted_user'];
    	if (!$current_user->is_admin  && !(in_array(strtoupper($current_user->user_name), $permitted_users)) && !(in_array('Customer support executive', $roles))) {
    		die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
    	}
    	echo $html = <<<HTMLFORM
		<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">
		<h1><center><b>Customer Profile View</b></center></h1>
		<form action="index.php?module=scrm_Custom_Reports&action=CustomerApplicationProfile" method='get'>
		<table>
		<tr>
			<td>Application ID: &nbsp</td>
			<input type='hidden' name='module' id='module' value='scrm_Custom_Reports'/>
			<input type='hidden' name='action' id='action' value='CustomerProfile'/>
			<td><input type='text' name='applicationID' id='applicationID' value='$_REQUEST[applicationID]'/></td>
			<td colspan="1"><input type='submit' value='Get Details' id='details' name='details'/></td>
		</tr>
		</table>
		</form>
HTMLFORM;

    	$app_id = $_GET['applicationID'];
    	$as_api_base_url = getenv('SCRM_AS_API_BASE_URL');
		if(!empty($app_id)){
			$url = $as_api_base_url."/get_application_basic_details?ApplicationID=".$app_id;
			
			require_once('custom/include/CurlReq.php');
			$curl_req = new CurlReq();

			$response = $curl_req->curl_req($url);
			if($response){
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					$company_name = $json_response[0]['Company Name'];
					$industry = $json_response[0]['Industry'];
					$address = $json_response[0]['BusinessAddress'];
					$contact_name = $json_response[0]['Contact Person Name'];
					$contact_number = $json_response[0]['Contact Number'];
					$internal_sales_user = $json_response[0]['Internal Sales user'];
					$external_sales_user = $json_response[0]['External Sales user'];
					$customer_id = $json_response[0]['Customer ID'];
					$lead_source = $json_response[0]['Lead Source'];
					$constitution_name = $json_response[0]['Constitution Name'];
				}
			}
			$url = $as_api_base_url."/get_merchant_details?ApplicationID=".$app_id;
			
			require_once('custom/include/CurlReq.php');
			$curl_req = new CurlReq();

			$response = $curl_req->curl_req($url);
			if($response){
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					$applicant_person = $json_response[0]['Applicant Person'];
					$applicant_number = $json_response[0]['Applicant Number'];
					$applicant_email_id = $json_response[0]['Applicant Email Id'];
					$applicant_scheme = $json_response[0]['Scheme'];
					$dealer_code=$json_response[0]['Dealer Code'];
					$omc=$json_response[0]['OMC'];
					$nach_detail = $json_response[0]['Nach Detail'];
					$nach_status = $json_response[0]['Nach Status'];
					$source_branch = $json_response[0]['Branch Name'];
				}
			}
		}
		echo $HTML = <<<TITLE
			<div>
				<h2><b><span id="classification" style="font-size:40px">■</span>&nbsp&nbsp $app_id - $company_name</b></h2>
			</div>
TITLE;

		echo $HTML = <<<DISP1
		<div>
			<div id='detailpanel_1' class='detail view  detail508 expanded'>
			<h4>
				<a href="javascript:void(0)" class="collapseLink" onclick="collapsePanel(1);">
				<img border="0" id="detailpanel_1_img_hide" src="themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA"></a>
				<a href="javascript:void(0)" class="expandLink" onclick="expandPanel(1);">
				<img border="0" id="detailpanel_1_img_show" src="themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA"></a>
				Personal Details
				<script>
					document.getElementById('detailpanel_1').className += ' expanded';
				</script>
			</h4>
			<table id='LBL_EDITVIEW_PANEL1' class="panelContainer" cellspacing='0'>
				<tr>
					<td width='12.5%' scope="col">
						Company Name:
					</td>
					<td class="" type="varchar" field="company_name" width='37.5%'  >
						<span class="sugar_field" id="company_name">$company_name</span>
					</td>
					<td width='12.5%' scope="col">
						Contact Name:
					</td>
					<td class="" type="varchar" field="contact_name" width='37.5%'  >
						<span class="sugar_field" id="contact_name">$contact_name</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Name of the Applicant:
					</td>
					<td class="" type="varchar" field="applicant_person" width='37.5%'  >
						<span class="sugar_field" id="applicant_person">$applicant_person</span>
					</td>
					<td width='12.5%' scope="col">
						Contact Number:
					</td>
					<td class="" type="varchar" field="contact_number" width='37.5%'  >
						<span class="sugar_field" id="contact_number">$contact_number</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Source Branch:
					</td>
					<td class="" type="varchar" field="source_branch" width='37.5%'  >
						<span class="sugar_field" id="source_branch">$source_branch</span>
					</td>
					<td width='12.5%' scope="col">
						Internal sales user:
					</td>
					<td class="" type="varchar" field="internal_sales_user" width='37.5%'  >
						<span class="sugar_field" id="internal_sales_user">$internal_sales_user</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Industry:
					</td>
					<td class="" type="enum" field="industry" width='37.5%'  >
						<span class="sugar_field" id="industry">$industry</span>
					</td>
					<td width='12.5%' scope="col">
						External sales user:
					</td>
					<td class="" type="dynamicenum" field="external_sales_user" width='37.5%'  >
						<span class="sugar_field" id="external_sales_user">$external_sales_user</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Address:
					</td>
					<td class="" type="enum" field="address" width='37.5%' colspan='3' >
						<span class="sugar_field" id="address">$address</span>
					</td>
				</tr>
			</table>
			<script type="text/javascript">SUGAR.util.doWhen("typeof initPanel == 'function'", function() { initPanel(1, 'expanded'); }); </script>
		</div>
		</div>
DISP1;
		if(!empty($app_id)){
			$url = $as_api_base_url."/get_application_deal_details?ApplicationID=".$app_id;
			
			require_once('custom/include/CurlReq.php');
			$curl_req = new CurlReq();

			$response = $curl_req->curl_req($url);
			if($response){
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					$product_offered = $json_response[0]['Product Offered'];
					$advance_amount = $json_response[0]['Advance Amount'];
					$repayment_amount = $json_response[0]['Repayment Amount'];
					$term_days = $json_response[0]['Term (Days)'];
					$monthly_average_card_sales = $json_response[0]['Monthly Avg. Card Sales'];
				}
			}
			$url = $as_api_base_url."/get_application_funding_details?ApplicationID=".$app_id;
			
			require_once('custom/include/CurlReq.php');
			$curl_req = new CurlReq();

			$response = $curl_req->curl_req($url);
			if($response){
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					$dpd = $json_response[0]['Days Ahead / Behind'];
					if(!empty($dpd)){
						if ($dpd<=30) {
							$classification = "green";
						}else if($dpd>30 && $dpd<=60){
							$classification = "yellow";
						}else{
							$classification = "red";
						}
					}else{
						$classification = "black";
					}
					$variance = $json_response[0]['Variance /%'];
					$average_daily_pay = $json_response[0]['Average Daily Pay'];
					$expected_payoff_date = $json_response[0]['Expected Payoff Date'];
					$expected_payoff_date = date_create($expected_payoff_date);
					$expected_payoff_date = date_format($expected_payoff_date,"Y-m-d");
					$paid_remaining = $json_response[0]['% Paid/% Remaining'];
					$expected_recoveries = $json_response[0]['Expected Recoveries'];
					$actual_recoveries = $json_response[0]['Actual Recoveries'];
					$funded_date = $json_response[0]['Funded Date'];
					$funded_date = date_create($funded_date);
					$funded_date = date_format($funded_date,"Y-m-d");
					$funded_month_year = date_format(date_create($json_response[0]['Funded Date']),"M-Y");
					$current_balance = $json_response[0]['Current Balance'];
					$age_days = $json_response[0]['Age (Days)'];
					$last_payment_date = $json_response[0]['Last Payment Date'];
					$last_payment_date = date_create($last_payment_date);
					$last_payment_date = date_format($last_payment_date,"Y-m-d");
					$last_payment_amount = $json_response[0]['Last Payment Amount'];					
					$dnp = $json_response[0]['Days No Pay'];
					$funded_amount = $json_response[0]['Funded Amount'];
					$settled = $json_response[0]['Settled'];
					$repaid = $json_response[0]['Repaid'];
					$isRenewal = $json_response[0]['isRenewal'];
					if (!empty($isRenewal)) {
						if ($isRenewal == "N") {
							$fresh_renewal = "No";
						}else if ($isRenewal == "Y") {
							$fresh_renewal = "Yes";
						}
					}
					$percent_exp = $json_response[0]['Percent Of Expectation'];
					$expd = $json_response[0]['EXPD'];
				}
			}
			$url = $as_api_base_url."/get_application_repaymec_details?ApplicationID=".$app_id;
			
			require_once('custom/include/CurlReq.php');
			$curl_req = new CurlReq();

			$response = $curl_req->curl_req($url);
			if($response){
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					$repayment_frequency = $json_response[0]['Repayment Frequency'];
					$product_nt = $json_response[0]['Product NT'];
					$terminal_exclusivity_status = $json_response[0]['Terminal Exclusivity Status'];
					$repayment_mode = $json_response[0]['Repayment Mode'];
					$deferral = $json_response[0]['Deferral'];
					$syndication = $json_response[0]['Syndication'];
					$loan_type = $json_response[0]['Loan Type'];
					$is_regularised = $json_response[0]['IsRegularised'];
					$ach_activation_status = $json_response[0]['ACHActivationStatus'];
					$controlled_program = $json_response[0]['ControlledProgram'];
				}
			}
			$url = $as_api_base_url."/get_application_pprmis_details?ApplicationID=".$app_id;
			
			require_once('custom/include/CurlReq.php');
			$curl_req = new CurlReq();

			$response = $curl_req->curl_req($url);
			if($response){
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					$expm = $json_response[0]['EXPM'];
					$actual_mtd = $json_response[0]['ActualMTDCollections'];
					$due_date = $json_response[0]['DueDate'];
					$due_date = date_create($due_date);
					$due_date = date_format($due_date,"Y-m-d");
				}
			}
		}

		echo $classification_disp = <<<classification
			<script type="text/javascript">
				document.getElementById('classification').style.color = '$classification';
			</script>
classification;
		echo $HTML = <<<DISP2
		<div>
			<div id='detailpanel_2' class='detail view  detail508 expanded'>
			<h4>
				<a href="javascript:void(0)" class="collapseLink" onclick="collapsePanel(2);">
				<img border="0" id="detailpanel_2_img_hide" src="themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA"></a>
				<a href="javascript:void(0)" class="expandLink" onclick="expandPanel(2);">
				<img border="0" id="detailpanel_2_img_show" src="themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA"></a>
				Loan Details
				<script>
					document.getElementById('detailpanel_2').className += ' expanded';
				</script>
			</h4>
			<table id='LBL_EDITVIEW_PANEL2' class="panelContainer" cellspacing='0'>
				<tr>
					<td width='12.5%' scope="col">
						Product offered:
					</td>
					<td class="" type="varchar" field="product_offered" width='37.5%'  >
						<span class="sugar_field" id="product_offered">$product_offered</span>
					</td>
					<td width='12.5%' scope="col">
						Variance:
					</td>
					<td class="" type="varchar" field="variance" width='37.5%'  >
						<span class="sugar_field" id="variance">$variance</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Advance amount:
					</td>
					<td class="" type="varchar" field="advance_amount" width='37.5%'  >
						<span class="sugar_field" id="advance_amount">$advance_amount</span>
					</td>
					<td width='12.5%' scope="col">
						Average daily pay:
					</td>
					<td class="" type="varchar" field="average_daily_pay" width='37.5%'  >
						<span class="sugar_field" id="average_daily_pay">$average_daily_pay</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Repayment amount:
					</td>
					<td class="" type="varchar" field="repayment_amount" width='37.5%'  >
						<span class="sugar_field" id="repayment_amount">$repayment_amount</span>
					</td>
					<td width='12.5%' scope="col">
						Expected payoff date:
					</td>
					<td class="" type="enum" field="expected_payoff_date" width='37.5%'  >
						<span class="sugar_field" id="expected_payoff_date">$expected_payoff_date</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Term(days):
					</td>
					<td class="" type="enum" field="term_days" width='37.5%'  >
						<span class="sugar_field" id="term_days">$term_days</span>
					</td>
					<td width='12.5%' scope="col">
						%Paid / %Remaining:
					</td>
					<td class="" type="dynamicenum" field="paid_remaining" width='37.5%'  >
						<span class="sugar_field" id="paid_remaining">$paid_remaining</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Monthly average card sales:
					</td>
					<td class="" type="enum" field="case_location_c" width='37.5%' >
						<span class="sugar_field" id="monthly_average_card_sales">$monthly_average_card_sales</span>
					</td>
					<td width='12.5%' scope="col">
						Expected recoveries:
					</td>
					<td class="" type="enum" field="case_location_c" width='37.5%' >
						<span class="sugar_field" id="expected_recoveries">$expected_recoveries</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						DPD:
					</td>
					<td class="" type="enum" field="case_location_c" width='37.5%' >
						<span class="sugar_field" id="dpd">$dpd</span>
					</td>
					<td width='12.5%' scope="col">
						Actual recoveries:
					</td>
					<td class="" type="enum" field="case_location_c" width='37.5%' >
						<span class="sugar_field" id="actual_recoveries">$actual_recoveries</span>
					</td>
				</tr>
			</table>
			<script type="text/javascript">SUGAR.util.doWhen("typeof initPanel == 'function'", function() { initPanel(1, 'expanded'); }); </script>
		</div>
		</div>
DISP2;

		echo $HTML = <<<DISP3
		<div >
			<div id='detailpanel_3' class='detail view  detail508 expanded'>
			<h4>
				<a href="javascript:void(0)" class="collapseLink" onclick="collapsePanel(3);">
				<img border="0" id="detailpanel_3_img_hide" src="themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA"></a>
				<a href="javascript:void(0)" class="expandLink" onclick="expandPanel(3);">
				<img border="0" id="detailpanel_3_img_show" src="themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA"></a>
				Other Details
				<script>
					document.getElementById('detailpanel_3').className += ' expanded';
				</script>
			</h4>
			<table id='LBL_EDITVIEW_PANEL3' class="panelContainer" cellspacing='0'>
				<tr>
					<td width='12.5%' scope="col">
						Application ID:
					</td>
					<td class="" type="varchar" field="app_id" width='37.5%'  >
						<span class="sugar_field" id="app_id">$app_id</span>
					</td>
					<td width='12.5%' scope="col">
						Repayment Frequency:
					</td>
					<td class="" type="varchar" field="repayment_frequency" width='37.5%'  >
						<span class="sugar_field" id="repayment_frequency">$repayment_frequency</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Merchant Name:
					</td>
					<td class="" type="varchar" field="merchant_name" width='37.5%'  >
						<span class="sugar_field" id="merchant_name">$company_name</span>
					</td>
					<td width='12.5%' scope="col">
						Product NT:
					</td>
					<td class="" type="varchar" field="product_nt" width='37.5%'  >
						<span class="sugar_field" id="product_nt">$product_nt</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Branch:
					</td>
					<td class="" type="varchar" field="branch" width='37.5%'  >
						<span class="sugar_field" id="branch">$source_branch</span>
					</td>
					<td width='12.5%' scope="col">
						Exclusive Non Exclusive:
					</td>
					<td class="" type="enum" field="terminal_exclusivity_status" width='37.5%'  >
						<span class="sugar_field" id="terminal_exclusivity_status">$terminal_exclusivity_status</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Funded Date:
					</td>
					<td class="" type="enum" field="funded_date" width='37.5%'  >
						<span class="sugar_field" id="funded_date">$funded_date</span>
					</td>
					<td width='12.5%' scope="col">
						Funded amt:
					</td>
					<td class="" type="dynamicenum" field="funded_amount" width='37.5%'  >
						<span class="sugar_field" id="funded_amount">$funded_amount</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Funded Month Year:
					</td>
					<td class="" type="enum" field="funded_month_year" width='37.5%' >
						<span class="sugar_field" id="funded_month_year">$funded_month_year</span>
					</td>
					<td width='12.5%' scope="col">
						Repay:
					</td>
					<td class="" type="enum" field="repayment_amount" width='37.5%' >
						<span class="sugar_field" id="repayment_amount">$repayment_amount</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Customer ID:
					</td>
					<td class="" type="enum" field="cust_id" width='37.5%' >
						<span class="sugar_field" id="cust_id"><a target='_blank' href="?module=scrm_Custom_Reports&action=RenewalCustomerProfile&customerID=$customer_id&details=Get+Details">$customer_id</a></span>
					</td>
					<td width='12.5%' scope="col">
						Tday:
					</td>
					<td class="" type="enum" field="term_days" width='37.5%' >
						<span class="sugar_field" id="term_days">$term_days</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Constitution:
					</td>
					<td class="" type="enum" field="constitution" width='37.5%' >
						<span class="sugar_field" id="constitution">$constitution</span>
					</td>
					<td width='12.5%' scope="col">
						LBAL:
					</td>
					<td class="" type="enum" field="current_balance" width='37.5%' >
						<span class="sugar_field" id="current_balance">$current_balance</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Fresh/Renewal:
					</td>
					<td class="" type="enum" field="fresh_renewal" width='37.5%' >
						<span class="sugar_field" id="fresh_renewal">$fresh_renewal</span>
					</td>
					<td width='12.5%' scope="col">
						Age:
					</td>
					<td class="" type="enum" field="age_days" width='37.5%' >
						<span class="sugar_field" id="age_days">$age_days</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Settled:
					</td>
					<td class="" type="enum" field="settled" width='37.5%' >
						<span class="sugar_field" id="settled">$settled</span>
					</td>
					<td width='12.5%' scope="col">
						EXPM:
					</td>
					<td class="" type="enum" field="expm" width='37.5%' >
						<span class="sugar_field" id="expm">$expm</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Repaid:
					</td>
					<td class="" type="enum" field="repaid" width='37.5%' >
						<span class="sugar_field" id="repaid">$repaid</span>
					</td>
					<td width='12.5%' scope="col">
						Scheme:
					</td>
					<td class="" type="enum" field="scheme" width='37.5%' >
						<span class="sugar_field" id="scheme">$applicant_scheme</span>
					</td>
				</tr>
			</table>
			<br>
			<table id='LBL_EDITVIEW_PANEL4' class="panelContainer" cellspacing='0'>
				<tr>
					<td width='12.5%' scope="col">
						Percent Expectation:
					</td>
					<td class="" type="varchar" field="percent_exp" width='37.5%'  >
						<span class="sugar_field" id="percent_exp">$percent_exp</span>
					</td>
					<td width='12.5%' scope="col">
						Actual MTD Collection:
					</td>
					<td class="" type="varchar" field="actual_mtd" width='37.5%'  >
						<span class="sugar_field" id="actual_mtd">$actual_mtd</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						EXPD:
					</td>
					<td class="" type="varchar" field="expd" width='37.5%'  >
						<span class="sugar_field" id="expd">$expd</span>
					</td>
					<td width='12.5%' scope="col">
						Applicant name:
					</td>
					<td class="" type="varchar" field="applicant_person" width='37.5%'  >
						<span class="sugar_field" id="applicant_person">$applicant_person</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						ACTD:
					</td>
					<td class="" type="varchar" field="average_daily_pay" width='37.5%'  >
						<span class="sugar_field" id="average_daily_pay">$average_daily_pay</span>
					</td>
					<td width='12.5%' scope="col">
						Mobile:
					</td>
					<td class="" type="enum" field="applicant_number" width='37.5%'  >
						<span class="sugar_field" id="applicant_number">$applicant_number</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Last:
					</td>
					<td class="" type="enum" field="last_payment_date" width='37.5%'  >
						<span class="sugar_field" id="last_payment_date">$last_payment_date</span>
					</td>
					<td width='12.5%' scope="col">
						email:
					</td>
					<td class="" type="dynamicenum" field="applicant_email_id" width='37.5%'  >
						<span class="sugar_field" id="applicant_email_id">$applicant_email_id</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Amt:
					</td>
					<td class="" type="enum" field="last_payment_amount" width='37.5%' >
						<span class="sugar_field" id="last_payment_amount">$last_payment_amount</span>
					</td>
					<td width='12.5%' scope="col">
						CAM name:
					</td>
					<td class="" type="enum" field="internal_sales_user" width='37.5%' >
						<span class="sugar_field" id="internal_sales_user">$internal_sales_user</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						DNP:
					</td>
					<td class="" type="enum" field="dnp" width='37.5%' >
						<span class="sugar_field" id="dnp">$dnp</span>
					</td>
					<td width='12.5%' scope="col">
						Agent Source:
					</td>
					<td class="" type="enum" field="lead_source" width='37.5%' >
						<span class="sugar_field" id="lead_source">$lead_source</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						DueDate:
					</td>
					<td class="" type="enum" field="due_date" width='37.5%' >
						<span class="sugar_field" id="due_date">$due_date</span>
					</td>
					<td width='12.5%' scope="col">
						deferral:
					</td>
					<td class="" type="enum" field="deferral" width='37.5%' >
						<span class="sugar_field" id="deferral">$deferral</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Address:
					</td>
					<td class="" type="enum" field="address" width='37.5%' >
						<span class="sugar_field" id="address">$address</span>
					</td>
					<td width='12.5%' scope="col">
						regularised:
					</td>
					<td class="" type="enum" field="is_regularised" width='37.5%' >
						<span class="sugar_field" id="is_regularised">$is_regularised</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						
					</td>
					<td class="" type="enum" field="" width='37.5%' >
						<span class="sugar_field" id=""></span>
					</td>
					<td width='12.5%' scope="col">
						ACH activation Status:
					</td>
					<td class="" type="enum" field="ach_activation_status" width='37.5%' >
						<span class="sugar_field" id="ach_activation_status">$ach_activation_status</span>
					</td>
				</tr>
			</table>
			<br>
			<table id='LBL_EDITVIEW_PANEL5' class="panelContainer" cellspacing='0'>
				<tr>
					<td width='12.5%' scope="col">
						Syndication:
					</td>
					<td class="" type="varchar" field="syndication" width='37.5%'  >
						<span class="sugar_field" id="syndication">$syndication</span>
					</td>
					<td width='12.5%' scope="col">
						AREP:
					</td>
					<td class="" type="varchar" field="average_daily_pay" width='37.5%'  >
						<span class="sugar_field" id="average_daily_pay">$average_daily_pay</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Controlled Program:
					</td>
					<td class="" type="varchar" field="controlled_program" width='37.5%'  >
						<span class="sugar_field" id="controlled_program">$controlled_program</span>
					</td>
					<td width='12.5%' scope="col">
						Var:
					</td>
					<td class="" type="varchar" field="variance" width='37.5%'  >
						<span class="sugar_field" id="variance">$variance</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						repayment Mode:
					</td>
					<td class="" type="varchar" field="repayment_mode" width='37.5%'  >
						<span class="sugar_field" id="repayment_modes">$repayment_mode</span>
					</td>
					<td width='12.5%' scope="col">
						DPD:
					</td>
					<td class="" type="varchar" field="dpd" width='37.5%'  >
						<span class="sugar_field" id="dpd">$dpd</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						EXPR:
					</td>
					<td class="" type="enum" field="expected_recoveries" width='37.5%'  >
						<span class="sugar_field" id="expected_recoveries">$expected_recoveries</span>
					</td>
					<td width='12.5%' scope="col">
						LSD:
					</td>
					<td class="" type="dynamicenum" field="" width='37.5%'  >
						<span class="sugar_field" id="">$last_payment_date</span>
					</td>
				</tr>
			</table>
			<script type="text/javascript">SUGAR.util.doWhen("typeof initPanel == 'function'", function() { initPanel(1, 'expanded'); }); </script>
		</div>
		</div>
DISP3;

		echo "
			<div>
				<div id='detailpanel_4' class='detail view  detail508 expanded'>
				<h4>
					<a href='javascript:void(0)' class='collapseLink' onclick='collapsePanel(4);'>
					<img border='0' id='detailpanel_4_img_hide' src='themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
					<a href='javascript:void(0)' class='expandLink' onclick='expandPanel(4);'>
					<img border='0' id='detailpanel_4_img_show' src='themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
					Queries/Requests/Complaints
					<script>
						document.getElementById('detailpanel_4').className += ' expanded';
					</script>
				</h4>
				<table border='0' cellpadding='0' cellspacing='0' width='100%' class='panelContainer list view table default footable-loaded footable'>
			";		
		if(!empty($app_id)) {
			$bean = BeanFactory::getBean('Cases');
			$query = "cases.deleted=0 and cases_cstm.merchant_app_id_c='$app_id'";	
			$items = $bean->get_full_list('case_number desc',$query);
			if ($items){
				echo $HTML = <<<DISP5
					<th scope='col' data-hide="phone">
						<div style='white-space: normal;'width='100%' align='left'>
		                		Number
			                	&nbsp;&nbsp;
						</div>
					</th>
					<th scope='col' data-toggle="true">
						<div style='white-space: normal;' align='left'>
		                        Subject
								&nbsp;&nbsp;
						</div>
					</th>
					<th scope='col' data-hide="phone">
						<div style='white-space: normal;'width='100%' align='left'>
			                	Establishment
								&nbsp;&nbsp;
						</div>
					</th>
					
				    <th scope='col' data-hide="phone,phonelandscape">					
				    	<div style='white-space: normal;'width='100%' align='left'>
							Name
							&nbsp;&nbsp;
						</div>
					</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet">					
						<div style='white-space: normal;'width='100%' align='left'>
								Priority
								&nbsp;&nbsp;
						</div>
					</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet">					
						<div style='white-space: normal;'width='100%' align='left'>
								Status
								&nbsp;&nbsp;
						</div>
					</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet">	
						<div style='white-space: normal;'width='100%' align='left'>
								Assigned to
								&nbsp;&nbsp;
						</div>
					</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet">					
						<div style='white-space: normal;'width='100%' align='left'>
								Date Created
								&nbsp;&nbsp;
							</th>

					<th scope='col' data-hide="phone,phonelandscape,tablet">
						<div style='white-space: normal;'width='100%' align='left'>
								Complainant
								&nbsp;&nbsp;
						</div>
					</th>
					<th scope='col' data-hide="phone,phonelandscape,tablet">					
						<div style='white-space: normal;'width='100%' align='left'>
								Created By
								&nbsp;&nbsp;
						</div>
					</th>
					<th scope='col' data-hide="phone,phonelandscape,tablet">					
						<div style='white-space: normal;'width='100%' align='left'>
								Modified By Name
								&nbsp;&nbsp;
						</div>
					</th>
DISP5;
			    foreach($items as $key=>$item){
			    	$key +=1;
			    	echo "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='int' field='case_number' class='footable-visible footable-first-column'>$item->case_number</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='name' field='name' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Cases&return_module=Cases&action=DetailView&record=$item->id'><b>$item->name</b></a></td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='merchant_establisment_c' class='footable-visible footable-first-column'>$item->merchant_establisment_c</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='merchant_name_c' class='footable-visible footable-first-column'>$item->merchant_name_c</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='enum' field='priority' class='footable-visible footable-first-column'>$item->priority</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='enum' field='state' class='footable-visible footable-first-column'>$item->state</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='relate' field='assigned_user_id' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Employees&return_module=Employees&action=DetailView&record=$item->assigned_user_id'>".$this->getUserName($item->assigned_user_id)."</a></td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='datetime' field='date_entered' class='footable-visible footable-first-column'>$item->date_entered</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='complaintaint_c' class='footable-visible footable-first-column'>$item->complaintaint_c</td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='relate' field='created_by' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Employees&return_module=Employees&action=DetailView&record=$item->created_by'>".$this->getUserName($item->created_by)."</a></td>";

			    	echo "<td style='background-color:#f6f6f6;' valign='top' type='relate' field='modified_user_id' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Employees&return_module=Employees&action=DetailView&record=$item->modified_user_id'>".$this->getUserName($item->modified_user_id)."</a></td>";

			    	echo "</tr>";
			    }
			}else{
				echo "<tr><td>No Cases with the APP ID $app_id</h2></td>";
			}
		}
		echo $HTML = <<<DISP6
			</table>
			<script type="text/javascript">SUGAR.util.doWhen("typeof initPanel == 'function'", function() { initPanel(1, 'expanded'); }); </script>
		</div>
		</div>
		<br>
DISP6;

		echo "<div class='detail'> <h4>Email Interest Certificate / Statement</h4></div>";	
		echo $HTML = <<<DISP7
		<br>
		<form>
		<table class='table'>
		<tr>
			<td>Document Type: </td>
			<td>
				<input type='radio' name='document_type' value='Interest Certificate' /> &nbspInterest Certificate&nbsp&nbsp
				<input type='radio' name='document_type' value='Loan Statement' /> &nbspLoan Statement
			</td>
		</tr>
		<tr id='tr_from' style='visibility:collapse'>
			<td>From Date:</td>
			<td>
				<select name='from_year' id='from_year' value='$_REQUEST[from_year]'> 
				</select>
				<select name='from_month' id='from_month' value='$_REQUEST[from_month]'> 
				</select>
			</td>
		</tr>
		<tr id='tr_to' style='visibility:collapse'>			
			<td>To Date:</td>
			<td>
				<select name='to_year' id='to_year' value='$_REQUEST[to_year]'> 
				</select>
				<select name='to_month' id='to_month' value='$_REQUEST[to_month]'> 
				</select>
			</td>
		</tr>
		<tr id='tr_financial_year' style='visibility:collapse'>
			<td>Financial Year:</td>
			<td>
				<select name='financial_year' id='financial_year' value='$_REQUEST[financial_year]'> 
				</select>
			</td>

		</tr>
		<tr>
			<td></td><td colspan="1"><input type='button' value='Email Merchant' id='email_merchant' name='email_merchant'/></td>
		</tr>
		</table>
		<div  id="imageLoading"></div>
		<p id='mail_status'></p>
		</form>
		<script>
			$('#email_merchant').click(function() {
			    $('#imageLoading').css('display','block');
				$("#mail_status").html('');
    			$.ajax({
	        		url: 'custom/send_merchant_email.php',
			        type: 'POST',
			        data: {
			        	application_id: $('#app_id').text(),
			        	contact_number: $('#contact_number').text(),
			            document_type: $('input:radio[name="document_type"]:checked').val(),
			            from_month: $('#from_month').val(),
			            from_year: $('#from_year').val(),
			            to_month: $('#to_month').val(),
			            to_year: $('#to_year').val(),
			            financial_year: $('#financial_year').val(),
			            submit: 1
			        },
			        success: function(msg) {
			        	$('#imageLoading').css('display','none');
			        	console.log(msg);
			        	var obj = JSON.parse(msg);
			        	if(obj['error'] == ''){
			        		$('#mail_status').html(obj['message']);
			        		$('#mail_status').attr('style', 'color:black');
			        	}else{
			        		$('#mail_status').html(obj['error']);
			        		$('#mail_status').attr('style', 'color:red');
			        	}
	       			}
				});
			});
			$('input:radio[name="document_type"]').change(function(){
				$("#from_year").html('');
				$("#from_month").html('');
				$("#to_year").html('');
				$("#to_month").html('');
				$("#financial_year").html('');
				$("#mail_status").html('');
				$('#imageLoading').css('display','block');
				if($(this).val() == 'Loan Statement'){
					$('#tr_from').css('visibility','visible');
					$('#tr_to').css('visibility','visible');
					$('#tr_financial_year').css('visibility','collapse');
				}else if($(this).val() == 'Interest Certificate'){
					$('#tr_from').css('visibility','collapse');
					$('#tr_to').css('visibility','collapse');
					$('#tr_financial_year').css('visibility','visible');
				}
				$.ajax({
	        		url: 'custom/send_merchant_email.php',
			        type: 'POST',
			        data: {
			        	document_type: $('input:radio[name="document_type"]:checked').val(),
			        	application_id: $('#app_id').text()
			        },
			        success: function(msg) {
			        	$('#imageLoading').css('display','none');
			        	console.log(msg);
			        	var obj = JSON.parse(msg);
			        	if(obj['message'] == 'success'){
			        		if(obj['document_type'] == 'Loan Statement'){
				        		$('#from_year').append($('<option>', {
								    value: 'Select Year',
								    text: 'Select Year'
								}));
								$('#to_year').append($('<option>', {
								    value: 'Select Year',
								    text: 'Select Year'
								}));
					        	$.each(obj['response'], function(ob_key, ob_value){
									$('#from_year').append($('<option>', {
									    value: ob_key,
									    text: ob_key
									}));
								});
								$("#from_year").change(function () {
							        var val = $(this).val();
							        $('#from_month').html('');
									$.each(obj['response'][val], function(key, value){
										$('#from_month').append($('<option>', {
										    value: value,
										    text: value
										}));
									});
							    });

							    $.each(obj['response'], function(ob_key, ob_value){
									$('#to_year').append($('<option>', {
									    value: ob_key,
									    text: ob_key
									}));
								});
								$("#to_year").change(function () {
							        var val = $(this).val();
							        $('#to_month').html('');
									$.each(obj['response'][val], function(key, value){
										$('#to_month').append($('<option>', {
										    value: value,
										    text: value
										}));
									});
							    });
				        	}
				        	else if(obj['document_type'] == 'Interest Certificate'){
								$.each(obj['response'], function(ob_key, ob_value){
									$('#financial_year').append($('<option>', {
									    value: ob_value,
									    text: ob_value
									}));
								});
				        	}
			        	}else{
			        		console.log(msg);
				        	var obj = JSON.parse(msg);
				        	if(obj['error'] == ''){
				        		$('#mail_status').html(obj['message']);
				        		$('#mail_status').attr('style', 'color:black');
				        	}else{
				        		$('#mail_status').html(obj['error']);
				        		$('#mail_status').attr('style', 'color:red');
				        	}
			        	}    
	       			}
				});
			});
		</script>

DISP7;

		echo "<div class='detail'> <h4>SMS Merchant</h4></div>";
		$sms_template_list = $GLOBALS['app_list_strings']['sms_template_list'];
		$sms_template_options = "";
		$sms_templates = array();
		foreach ($sms_template_list as $key => $value) {
			$sms_template_options .="<option name='$key' value='$key' data-description='$value'>$key"; 
			$sms_template_options .="</option>"; 
			$sms_templates[$key] = $value;
		}
		echo $HTML = <<<DISP8
		<form id='smsForm'>
			<input type='hidden' id='module' name='module' value='Administration'>
		    <input type='hidden' id='action' name='action' value='FireTextSMS'>
		    <input type='hidden' id='sugar_body_only' name='sugar_body_only' value='1'>
		    <input type='hidden' id='option' name='option' value='send'>
		    <input type='hidden' id='personalizeh' name='personalizeh' value='0'>
		    <input type='hidden' id='masssms' name='masssms' value='0'>
		    <input type='hidden' id='to' name='to'>
			<table class='table'>
				<tr>
				<td>Template: </td>
					<td>
						<select name='template' id='template' value='$_REQUEST[template]'>
						<option value='Select a template' selected>Select a template</option>
						$sms_template_options;
						</select>
					</td>
				</tr>
				<tr>
					<td>Context:</td>
					<td>
						<textarea rows='5' cols='70' name='sms' type='textarea' id='sms' value='$_REQUEST[sms]'>
						</textarea>
					</td>
				</tr>
				<tr>
					<td></td>
					<td colspan='1'><input type='button' value='Send SMS' id='send_sms' name='send_sms'/></td>
				</tr>
			</table>
			<p id='mail_status'></p>
		</form>
			<script>
				function validateTo(){
					var mobile_regex = /^\d{10}$/;
					var contact_no = ($('#contact_number').text()).trim();
					if(!contact_no.match(mobile_regex)){
				        alert("Mobile format should be 10 digit number");
				        return false;
					}
					$('#to').val(trim($('#contact_number').text()));
					// $('#to').val('9787114353');
					return true;
				}
				$('#template').change(function(){
					$('textarea#sms').val($('#template option:selected').attr('data-description'));
				});
				$('#send_sms').click(function(){
					if(validateTo()){
						var input = confirm('Are you sure you want to send the message to the customer? Please verify the data in message is accurate');
						console.log($('#smsForm').serialize());
						if(input){
							var data123 = $('#smsForm').serialize();
							var url = "./index.php";
							console.log(data123);
							$.ajax({
								type: "POST",
								url: url,
								data: data123,
								async: true,
								success: function(data){
									console.log(data);
									alert('Done');
								},
								error: function (XMLHttpRequest, textStatus, errorThrown) {
									alert('Some Error. Cannot send SMS.');
									console.log(textStatus+' '+errorThrown);
								}
							});	
						}
					}
					return false;
				});
			</script>

DISP8;


		echo $HTML = <<<DISP9
		<br>
		<br>
		<h2> Color Coding Reference </h2>
			<p><b><span title='Green' style="font-size:15px;color:green">■</span>&nbsp&nbsp DPD: Less than 30 </b></p>
			<p><b><span title='Yellow' style="font-size:15px;color:yellow">■</span>&nbsp&nbsp DPD: 30 to 60</b></p>
			<p><b><span title='Red' style="font-size:15px;color:red">■</span>&nbsp&nbsp DPD: More than 60</b></p>
			<p><b><span title='Black' style="font-size:15px;color:black">■</span>&nbsp&nbsp DPD field is empty/invalid</b></p>
DISP9;

		
	}
} //end of class
