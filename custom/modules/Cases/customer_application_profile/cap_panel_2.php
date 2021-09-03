<?php
if(!empty($app_id)){

			
			$url = $as_api_base_url."/get_application_deal_details?ApplicationID=".$app_id;
			
			$response = $curl_req->curl_req($url);
			
			$json_response = json_decode($response, true);
			
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
			$env=getenv('SCRM_ENVIRONMENT');
			if($env=="prod")
			{
				$url="https://app.advancesuite.in:3033";
			}
			else{
				$url="http://localhost:3033";
			}
			$url = "$url/crm/day_wise_due?app_id=$app_id";
			$response = $curl_req->curl_req($url);
			if($response)
			{
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					$dpd=$json_response['day_wise_due']['DPD'];
					$variance = $json_response['day_wise_due']['EMI_Variance'];
					$expected_recoveries = $json_response['day_wise_due']['EMI_EXPR'];
					$actual_recoveries = $json_response['day_wise_due']['EMI_AREP'];
				}
			}

			$url = $as_api_base_url."/get_application_funding_details?ApplicationID=".$app_id;
			$response = $curl_req->curl_req($url);
			if($response){
				$json_response = json_decode($response, true);
				if(!empty($json_response) && count($json_response)>0){
					//$dpd = $json_response[0]['Days Ahead / Behind'];
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
					//$variance = $json_response[0]['Variance /%'];
					$average_daily_pay = $json_response[0]['Average Daily Pay'];
					$expected_payoff_date = $json_response[0]['Expected Payoff Date'];
					$expected_payoff_date = date_create($expected_payoff_date);
					$expected_payoff_date = date_format($expected_payoff_date,"Y-m-d");
					$paid_remaining = $json_response[0]['% Paid/% Remaining'];
					//$expected_recoveries = $json_response[0]['Expected Recoveries'];
					//$actual_recoveries = $json_response[0]['Actual Recoveries'];
					$funded_date = $json_response[0]['Funded Date'];
					$funded_date = date_create($funded_date);
					$funded_date = date_format($funded_date,"Y-m-d");
					$funded_date_month = date_format($funded_date,"Y-m");
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
?>