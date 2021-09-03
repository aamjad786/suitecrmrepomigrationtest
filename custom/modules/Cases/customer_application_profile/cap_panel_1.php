<?php
$company_name ="";
if(!empty($app_id)){

			$url = $as_api_base_url."/get_application_basic_details?ApplicationID=".$app_id;
			
			$response = $curl_req->curl_req($url);
			
			$json_response = json_decode($response, true);
	
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
					$application_status=$json_response[0]['Status'];
					$is_old_app_id = $old_app_id;
				}
			}
			$url = $as_api_base_url."/get_merchant_details?ApplicationID=".$app_id;
			$response = $curl_req->curl_req($url);
			if($response){
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					$applicant_person = $json_response[0]['Applicant Person'];
					$applicant_number = $json_response[0]['Applicant Number'];
					$applicant_email_id = $json_response[0]['Applicant Email Id'];
					$applicant_scheme = $json_response[0]['Scheme'];
					$new_app_id = $json_response[0]['New Application Id'];
					$omc=$json_response[0]['OMC'];
					$dealer_code=$json_response[0]['Dealer Code'];
					$source_branch = $json_response[0]['Branch Name'];
					$customer_id = $json_response[0]['Customer ID'];
					$nach_detail = $json_response[0]['Nach Detail'];
					$nach_status = $json_response[0]['Nach Status'];
					$bank_name = $json_response[0]['Bank Name'];
					$bank_account_no = $json_response[0]['Bank account no'];
					$pan = $json_response[0]['PAN'];
					if(empty($applicant_scheme) && !empty($new_app_id))
					{
						$applicant_scheme="Opted for COVID 19 Scheme";
					}
				}
			}
		}
		echo $HTML = <<<TITLE
			<div>
				<h2><b><span id="classification" style="font-size:40px">■</span>&nbsp&nbsp $app_id - $company_name</b></h2>
			</div>
		
TITLE;
		if(!empty($is_old_app_id) &&  $is_old_app_id==1){
		echo $HTML = <<<TITLE
			<div>
				<h2><b><span id="classification" style="font-size:40px">■</span>&nbsp&nbsp $app_id - $company_name</b></h2>
			</div>
TITLE;
		}

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
						Customer ID:
					</td>
					<td class="" type="enum" field="cust_id" width='37.5%'  >
						<span class="sugar_field" id="cust_id"><a target='_blank' href="?module=scrm_Custom_Reports&action=RenewalCustomerProfile&customerID=$customer_id&details=Get+Details">$customer_id</a></span>
					</td>
					<td width='12.5%' scope="col">
						Nach Detail (UMRN):
					</td>
					<td class="" type="dynamicenum" field="external_sales_user" width='37.5%'  >
						<span class="sugar_field" id="external_sales_user">$nach_detail</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Nach Registration Status:
					</td>
					<td class="" type="enum" field="industry" width='37.5%'  >
						<span class="sugar_field" id="industry">$nach_status</span>
					</td>
					<td width='12.5%' scope="col">
						Nach Bank Name:
					</td>
					<td class="" type="dynamicenum" field="external_sales_user" width='37.5%'  >
						<span class="sugar_field" id="external_sales_user">$bank_name</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						OMC Name:
					</td>
					<td class="" type="enum" field="omc" width='37.5%'  >
						<span class="sugar_field" id="omc">$omc</span>
					</td>
					<td width='12.5%' scope="col">
						Dealer Code:
					</td>
					<td class="" type="dynamicenum" field="dealercode" width='37.5%'  >
						<span class="sugar_field" id="dealercode">$dealer_code</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Nach Bank Account Number:
					</td>
					<td class="" type="enum" field="industry" width='37.5%'  >
						<span class="sugar_field" id="industry">$bank_account_no</span>
					</td>
					<td width='12.5%' scope="col">
						PAN:
					</td>
					<td class="" type="dynamicenum" field="external_sales_user" width='37.5%'  >
						<span class="sugar_field" id="external_sales_user">$pan</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Address:
					</td>
					<td class="" type="enum" field="address" width='37.5%' colspan='1' >
						<span class="sugar_field" id="address">$address</span>
					</td>
					<td width='12.5%' scope="col">
						Application Status:
					</td>
					<td class="" type="dynamicenum" field="application status" width='37.5%'  >
						<span class="sugar_field" id="application status">$application_status</span>
					</td>
						
				</tr>
			</table>
			<script type="text/javascript">SUGAR.util.doWhen("typeof initPanel == 'function'", function() { initPanel(1, 'expanded'); }); </script>
		</div>
		</div>
DISP1;
?>