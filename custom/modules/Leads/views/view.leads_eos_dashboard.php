<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/entryPoint.php');
require_once('include/MVC/View/SugarView.php');

// if($is_admin || ($current_user->department == 'SALES')) {
// 	echo $html = <<<SCRIPT_ACCESS
// 		<a target="_blank" style="text-decoration: none" href="?module=Leads&action=Month_sales_report"><span style ="font-family: Arial;font-size:14px;" ><b>1. Performance Report - Monthly</b></span></a>
// SCRIPT_ACCESS;
// 	}
class LeadsViewleads_eos_dashboard extends SugarView {

	function __construct()	{
		parent::SugarView();
	}

    function display() {
    	
		global $app_list_strings, $db;

		$cities = json_encode($app_list_strings['cluster_cities']);
		$zones = json_encode($app_list_strings['zone_list']);
		$parnerList = json_encode($app_list_strings['dsa_code_list']);
		
		$zone_city_mapping = $app_list_strings['zone_city_mapping'];

		// $fromDate  = date("Y-m-d 00:00:00");
		// $to_date    = date("Y-m-d H:i:s", strtotime("+1 month", strtotime($from_date)));
		// $from_date  = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($from_date)));
		// $to_date    = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($to_date)));
		
		$fromDate='';
		$toDate = "";
		$filter='';
		$subFilter='';

		(!empty($_REQUEST['from_date']) ? $fromDate = date("Y-m-d H:i:s", strtotime($_REQUEST['from_date']))  : '');
		(!empty($_REQUEST['to_date']) ? $toDate = date("Y-m-d H:i:s", strtotime($_REQUEST['to_date']))  : '');
		(!empty($_REQUEST['filter_dropdown']) ? $filter = $_REQUEST['filter_dropdown'] : '');
		(!empty($_REQUEST['sub_filter_dropdown']) ? $subFilter = $_REQUEST['sub_filter_dropdown']  : '');

		// echo $fromDate .'<br/>';
		// echo $toDate . '<br/>';
		// echo $filter . '<br/>';
		// echo $subFilter . '<br/>';

echo $html = <<<SEARCHFORM
        
	<script>
	
		$(document).ready( function () {

			$("#filter_dropdown").change(function(){
				
				var selectedFilter=$(this).val();
				console.log("filter selected value: ",selectedFilter);

				if(selectedFilter=="city_wise"){
					
					target = $('#sub_filter_dropdown').empty();

					$.each( $cities , function(value, index) {
						$('<option value="' + value + '">' + index + '</option>').appendTo(target);
					});
				}

				if(selectedFilter=="zone_wise"){
				
					target = $('#sub_filter_dropdown').empty();

					$.each( $zones , function(value, index) {
						$('<option value="' + value + '">' + index + '</option>').appendTo(target);
					});
				}

				if(selectedFilter=="partner_wise"){
					console.log("filter selected value: partner wise ",selectedFilter);
					target = $('#sub_filter_dropdown').empty();

					$.each( $parnerList , function(value, index) {
						$('<option value="' + value + '">' + index + '</option>').appendTo(target);
					});
				}

			});
			
			$("#filter_dropdown").val("$filter").change();
			$("#sub_filter_dropdown").val("$subFilter").change();

		});

	</script>	
	
	<style>
		
		.tab-content {
				background-color: #fff;
				padding: 24px;
				margin-bottom: 5px;
				border: 1px solid transparent;
				border-top-right-radius: 4px;
				border-bottom-left-radius: 4px;
				border-bottom-right-radius: 4px;
				margin-top:4%;
		}

		.cstm-label{
			display: inline;
			font-size: 15px;
			font-weight: 700;
			color: #534d64;
			padding: 0 0 10px 8px;
			text-align: left;
			vertical-align: baseline;
			white-space: pre-line;
		}
		
	</style>
	
	<body>

		<div class="row ">
			<div class="col-12">
				<h2 style="padding-bottom: 15px;">
					EOS-CRM Dashboard
				</h2>
			</div>
		</div>
		
		<form action="" id="uploadForm1" method="post" enctype="multipart/form-data">


			<div class="col-xs-12 col-sm-6 edit-view-row-item">
		
				<div class="col-xs-12 col-sm-2 label">
					Filter:
				</div>
				<div class="col-xs-12 col-sm-5 label">
					<select name="filter_dropdown" id="filter_dropdown">
						<option label="" value=""></option>
						<option label="Partner Wise" value="partner_wise">Partner Wise</option>
						<option label="City Wise" value="city_wise">City Wise</option>
						<option label="Zone Wise" value="zone_wise">Zone Wise</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-5 label">
					<select name="sub_filter_dropdown" id="sub_filter_dropdown" title="">
						<option label="" value=""></option>
					
					</select>
				</div>
		
			</div>
		
			<div class="col-xs-12 col-sm-6 edit-view-row-item">
		
				<div class="col-xs-12 col-sm-4 label">
					From Date:
				</div>
		
				<div class="col-xs-12 col-sm-8 edit-view-field " type="date" field="from_date">
					<span class="dateTime">
				<input class="date_input" autocomplete="off" type="text" name="from_date" id="from_date" value="{$_REQUEST['from_date']}" title="" tabindex="0" size="11" maxlength="10">
				<button type="button" id="from_date_trigger" class="btn btn-danger" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
		
					<script type="text/javascript">
						Calendar.setup({
							inputField: "from_date",
							form: "uploadForm1",
							ifFormat: "%m/%d/%Y %H:%M",
							daFormat: "%m/%d/%Y %H:%M",
							button: "from_date_trigger",
							singleClick: true,
							dateStr: "",
							startWeekday: 0,
							step: 1,
							weekNumbers: false
						});
					</script>
				</div>
			</div>
		
			<div class="clear"></div>
			<div class="clear"></div>
		
			<div class="col-xs-12 col-sm-6 edit-view-row-item">
		
				<div class="col-xs-12 col-sm-2 label">
					<input type="submit" value="Generate Report" name="Submit">
				</div>
		
			</div>
		
			<div class="col-xs-12 col-sm-6 edit-view-row-item">
		
				<div class="col-xs-12 col-sm-4 label">
					To Date:</div>
				<div class="col-xs-12 col-sm-8 edit-view-field " type="date" field="to_date">
					<span class="dateTime">
						<input class="date_input" autocomplete="off" type="text" name="to_date" id="to_date" value="{$_REQUEST['to_date']}" title="" tabindex="0" size="11" maxlength="10">
						<button type="button" id="to_date_trigger" class="btn btn-danger" onclick="return false;"><span class="suitepicon suitepicon-module-calendar" alt="Enter Date"></span></button>
		
					<script type="text/javascript">
						Calendar.setup({
							inputField: "to_date",
							form: "uploadForm1",
							ifFormat: "%m/%d/%Y %H:%M",
							daFormat: "%m/%d/%Y %H:%M",
							button: "to_date_trigger",
							singleClick: true,
							dateStr: "",
							startWeekday: 0,
							step: 1,
							weekNumbers: false
						});
					</script>
				</div>
		
			</div>
		</form>
	</body>
SEARCHFORM;
    	

// <=============== Generate Report If Dates Are Selected =============>

	if(!empty($fromDate) && !empty($toDate)){

	$leads=0;
	$leadsPushedToEos=0;
	$leadsNotPushedToEos=0;
	$OppFromEos=0;
	$leadsPendingAtEos=0;
	$appointmentGeneratedByEos=0;

	$groupByClause='';

	$query = "SELECT 
				COUNT(*) AS leads_count,
				SUM(CASE WHEN pushed_lead_c IS NOT NULL THEN 1 ELSE 0 END) AS leads_pushed,
				SUM(CASE WHEN pushed_lead_c IS NULL THEN 1 ELSE 0 END) AS leads_not_pushed,
				SUM(CASE WHEN pushed_lead_c IS NOT NULL AND date_updated_by_eos_c IS NULL THEN 1 ELSE 0 END) AS leads_pending_at_eos,
				SUM(CASE WHEN date_updated_by_eos_c IS NOT NULL
					AND eos_opportunity_status_c IN ('Appointment fixed' , 'appointment_done_cam_visit_customer',
					'appointment_done_picked_up_documents',
					'appointment_done_followup',
					'appointment_done_will_get_documents_later') THEN 1 ELSE 0 END) AS opp_from_eos,
				SUM(CASE WHEN date_updated_by_eos_c IS NOT NULL
					AND eos_opportunity_status_c IN  ('Appointment fixed' , 'appointment_done_picked_up_documents',
					'appointment_done_will_get_documents_later',
					'appointment_done_not_interested',
					'appointment_done_not_eligible',
					'appointment_done_followup',
					'appointment_done_negative_area',
					'appointment_done_existing_customer',
					'appointment_done_cam_visit_customer') THEN 1 ELSE 0 END) AS appointment_from_eos
			FROM
				leads
					JOIN
				leads_cstm ON id = id_c
			WHERE
				date_entered BETWEEN '$fromDate' AND '$toDate'
					AND deleted = 0";
	// echo $filter;
	if(!empty($filter) && !empty($subFilter)){

		$groupColumn='';
		$groupHaving='';

		if($filter=='city_wise'){
			$groupColumn='primary_address_city';
			$groupHaving="$groupColumn = '$subFilter'";
		}
	
		if($filter=='zone_wise'){
			
			$groupColumn='primary_address_city';
			
			$zoneCitites=$zone_city_mapping[$subFilter];
			$groupHaving=$groupColumn. " IN ('" .implode("','",$zoneCitites) ."')";

		}
		
		if($filter=='partner_wise'){

			$groupColumn='dsa_code_c';
			$groupHaving="$groupColumn = '$subFilter'";
		}

		$groupByClause=" GROUP BY $groupColumn HAVING $groupHaving";
		
	}
	
	$finalQuery=$query .' '. $groupByClause;

	// print_r($finalQuery);

	$result = $db->query($finalQuery);
	
	while ($row = $db->fetchByAssoc($result)) {

		$leads = $row['leads_count'];
		$leadsPushedToEos = $row['leads_pushed'];
		$leadsNotPushedToEos = $row['leads_not_pushed'];
		$OppFromEos = $row['opp_from_eos'];
		$leadsPendingAtEos = $row['leads_pending_at_eos'];
		$appointmentGeneratedByEos= $row['appointment_from_eos'];
	
	}

echo $report=<<<REPORT
<div class="tab-content">
			<div class="fade in" id="tab-content-0">

				<div class="row detail-view-row" >
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
					<div class="col-xs-12 col-sm-6 detail-view-row-item">
						<div class="col-xs-12 col-sm-8 cstm-label">
							Number Of Leads Received:
						</div>
						<div class="col-xs-12 col-sm-2 detail-view-field inlineEdit"  style="margin-top: 2%;">						
							<span class="sugar_field" id="name">{$leads}</span>
						</div>
					</div>
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
				</div>
		

				<div class="row detail-view-row">
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
					<div class="col-xs-12 col-sm-6 detail-view-row-item">
						<div class="col-xs-12 col-sm-8 cstm-label">
							Number Of Leads Pushed To EOS:
						</div>
						<div class="col-xs-12 col-sm-2 detail-view-field inlineEdit" style="margin-top: 2%;">
							<span class="sugar_field" id="name">{$leadsPushedToEos}</span>	
						</div>
					</div>
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
				</div>

				<div class="row detail-view-row">
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
					<div class="col-xs-12 col-sm-6 detail-view-row-item">
						<div class="col-xs-12 col-sm-8 cstm-label">
							Number Of Leads Not Pushed To EOS:
						</div>
						<div class="col-xs-12 col-sm-2 detail-view-field inlineEdit" style="margin-top: 2%;">
							<span class="sugar_field" id="name">{$leadsNotPushedToEos}</span>	
						</div>
					</div>
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
				</div>

				<div class="row detail-view-row">
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
					<div class="col-xs-12 col-sm-6 detail-view-row-item">
						<div class="col-xs-12 col-sm-8 cstm-label">
							Number Of Opportunities Received From EOS:
						</div>
						<div class="col-xs-12 col-sm-2 detail-view-field inlineEdit" style="margin-top: 2%;">
							<span class="sugar_field" id="name">{$OppFromEos}</span>	
						</div>
					</div>
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
				</div>

				<div class="row detail-view-row">
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
					<div class="col-xs-12 col-sm-6 detail-view-row-item">
						<div class="col-xs-12 col-sm-8 cstm-label">
							Number Of Leads Pending At EOS:
						</div>
						<div class="col-xs-12 col-sm-2 detail-view-field inlineEdit" style="margin-top: 2%;">
							<span class="sugar_field" id="name">{$leadsPendingAtEos}</span>	
						</div>
					</div>
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
				</div>

				<div class="row detail-view-row">
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
					<div class="col-xs-12 col-sm-6 detail-view-row-item">
						<div class="col-xs-12 col-sm-8 cstm-label">
							Number Of Appointments Generated By EOS:
						</div>
						<div class="col-xs-12 col-sm-2 detail-view-field inlineEdit" style="margin-top: 2%;">
							<span class="sugar_field" id="name">{$appointmentGeneratedByEos}</span>	
						</div>
					</div>
					<div class="col-xs-12 col-sm-3 detail-view-row-item">
					</div>
				</div>

			</div>
		</div>
REPORT;

	}




	}
    
}
?>