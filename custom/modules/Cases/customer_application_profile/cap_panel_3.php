<?php
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
					$(document).ready(function() {
						if($('#scheme_c').text()!='COVID 19')
						{
							$('#deal_link').hide();
						}
					});
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
					<td class="" type="enum" field="scheme_c" width='37.5%' >
						<span class="sugar_field" id="scheme_c">$applicant_scheme</span>
					</td>
				</tr>
				<tr>
					<td width='12.5%' scope="col">
						Partner Name:
					</td>
					<td class="" type="enum" field="partner_name_c" width='37.5%' >
						<span class="sugar_field" id="partner_name_c">$partner_name_c</span>
					</td>
					<td width='12.5%' scope="col">
						FI Business:
					</td>
					<td class="" type="enum" field="fi_business_c" width='37.5%' >
						<span class="sugar_field" id="fi_business_c">$fi_business_c</span>
					</td>
				</tr>
				<tr>
				<td width='12.5%' scope="col">
						<b>New Application Id :</b>
					</td>
					<td class="" type="enum" field="new_app_id" width='37.5%'  >
						<span class="sugar_field" id="new_app_id"><a target='_blank' href="index.php?module=Cases&action=customer_application_profile&applicationID=$new_app_id"><b>$new_app_id</b></a></span>
					</td>
					<td width='12.5%' scope="col">
						
					</td>
					<td class="" type="enum" field="" width='37.5%' >
						<span class="sugar_field" id=""></span>
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
						<span class="sugar_field" id="repayment_mode">$repayment_mode</span>
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
?>