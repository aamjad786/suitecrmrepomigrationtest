<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
class scrm_Custom_ReportsViewgenerate_attemptwisemis_report extends SugarView {
	
	private $chartV;
    function __construct(){    
        parent::SugarView();
    }
    
	function getMatrixData($post)
	{
		
		global $db;	
		
		global $sugar_config;
	
		
			//From Date & To Date filter Condition
		$from_date = $_REQUEST['from_date'];
		if(!empty($from_date))
		{
			$tmp = explode("/",$from_date);
			if( count($tmp) == 3)
			{
				$from_date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0];
			} else 
			$from_date = '';
		}
		$to_date = $_REQUEST['to_date'];
		if(!empty($to_date))
		{
			$tmp = explode("/",$to_date);
			if( count($tmp) == 3)
			{
				$to_date = $tmp[2].'-'.$tmp[1].'-'.($tmp[0]);
				$to_date = date('Y-m-d', strtotime($to_date. ' + 1 days'));
			} else 
			$to_date = '';
		}

		/*
		Disposition Codes:
			1.	new_unattempted
			2.	Not contactable
			3.	Call_back
			4.	Follow_up
			5.	Dropped (Not eligible)
			6.	Interested
			7.	Wrong number
			8.	Pickup generation_Appointment


			Lead source
			'Cold Call' => 'Cold Call',
		  'Self Generated' => 'Self Generated',
		  'Email' => 'Email',
		  'OBD Campaign' => 'OBD Campaign',
		  'BTL' => 'BTL',
		  'SMS' => 'SMS',
		  'Digital' => 'Digital',
		  'Missed Calls' => 'Missed Calls Website',
		  'IMS' => 'IMS',
		  'Alliances' => 'Alliances',
		  'Tele_Calling' => 'Tele Calling',
		  'missed_calls_sms' => 'Missed Calls SMS',
		  'Facebook' => 'Facebook',
		  'Referral' => 'Referral',
		*/
		$ls_array = $GLOBALS['app_list_strings']['lead_source_list'];

		$all_leads = $_REQUEST['all_leads'];

		$query = "";
		if(isset($all_leads)){
			$query = "SELECT count(l.id) as count, l.lead_source, lc.disposition_c, sum(lc.attempts_done_c) as att_count
					from leads l
					LEFT JOIN leads_cstm lc ON l.id=lc.id_c
					WHERE l.deleted = 0
					AND l.date_entered BETWEEN '$from_date' AND '$to_date'
					GROUP BY l.lead_source, lc.disposition_c";
        }
        else{
        	$securitygroups = array('Call Center-Tata BSS', 'Call Center-Kenkei', 'Call Center-KServe');
			$sg = join("','",$securitygroups);

	        $query = "SELECT count(l.id) as count, l.lead_source, lc.disposition_c, sum(lc.attempts_done_c) as att_count
					from leads l
					LEFT JOIN leads_cstm lc ON l.id=lc.id_c
					LEFT JOIN securitygroups_users sgu on sgu.user_id = l.assigned_user_id
					JOIN securitygroups sg on sg.id=sgu.securitygroup_id
					WHERE l.deleted = 0
					AND sg.name in ('$sg')
					AND l.date_entered BETWEEN '$from_date' AND '$to_date'
					GROUP BY l.lead_source, lc.disposition_c";
        }
        
        $result = $db->query($query);
		$data = array();
		$data['Total']['lead_source'] = 'Total';
		while($row = $db->fetchByAssoc($result))
		{
			$disposition = $row['disposition_c'];
			$lead_source = $row['lead_source'];
			$count = $row['count'];
			$att_count = $row['att_count'];
			if (!empty($lead_source)){
				if (array_key_exists($lead_source, $ls_array)){
					$data[$lead_source]['lead_source'] = $lead_source;
					if(empty($disposition) || $disposition == ' '){
						$data[$lead_source]['new_unattempted'] += $count;
						$data['Total']['new_unattempted'] += $count;

					}
					else{
						$data[$lead_source][$disposition] += $count;
						$data[$lead_source]['att_'.$disposition] += $att_count;
						$data['Total'][$disposition] += $count;
						$data['Total']['att_'.$disposition] += $att_count;
					}
					$data[$lead_source]['total_enquiries'] += $count;
					$data[$lead_source]['total_attempts'] += $att_count;
					$data['Total']['total_enquiries'] += $count;
					$data['Total']['total_attempts'] += $att_count;
				}
			}
		}
		return $data;
	}
	
	
	function array_sort_by_column(&$arr
	, $col, $dir = SORT_ASC) {
            $sort_col = array();
            foreach ($arr as $key=> $row) {
                $sort_col[$key] = $row[$col];
            }

            array_multisort($sort_col, $dir, $arr);
     }
		
    function display()
	{
		$ls_array = $GLOBALS['app_list_strings']['lead_source_list'];
		$ls_array['Total'] = 'Total';
		global $db,$current_user;
		//print_r($_REQUEST);

		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";


		$data='';
		$MData = $this->getMatrixData($_REQUEST);
		$export_data_final = array();

		foreach($ls_array as $ls=>$val){
			$export_data = array();
			if ($ls){
				$data .= '<tr height="25">';
				array_push($export_data, $ls);
				$data .= "<td $td_style><label>".$ls.'</label></td>';

				$enq_new_unattempted = (array_key_exists('new_unattempted', $MData[$ls])?$MData[$ls]['new_unattempted']:0);
				array_push($export_data, $enq_new_unattempted);
				$data .= "<td $td_style><label>".$enq_new_unattempted.'</label></td>';

				$enq_Not_contactable = ((array_key_exists('Not contactable', $MData[$ls]))?$MData[$ls]['Not contactable']:0);
				array_push($export_data, $enq_Not_contactable);
				$data .= "<td $td_style><label>".$enq_Not_contactable.'</label></td>';


				$enq_Call_back = ((array_key_exists('Call_back', $MData[$ls]))?$MData[$ls]['Call_back']:0);
				array_push($export_data, $enq_Call_back);
				$data .= "<td $td_style><label>".$enq_Call_back.'</label></td>';

				$enq_Follow_up = ((array_key_exists('Follow_up', $MData[$ls]))?$MData[$ls]['Follow_up']:0);
				array_push($export_data, $enq_Follow_up);
				$data .= "<td $td_style><label>".$enq_Follow_up.'</label></td>';

				$enq_Dropped = ((array_key_exists('Dropped', $MData[$ls]))?$MData[$ls]['Dropped']:0);
				array_push($export_data, $enq_Dropped);
				$data .= "<td $td_style><label>".$enq_Dropped.'</label></td>';

				$enq_Interested = ((array_key_exists('Interested', $MData[$ls]))?$MData[$ls]['Interested']:0);
				array_push($export_data, $enq_Interested);
				$data .= "<td $td_style><label>".$enq_Interested.'</label></td>';

				$enq_Wrong_number = ((array_key_exists('Wrong number', $MData[$ls]))?$MData[$ls]['Wrong number']:0);
				array_push($export_data, $enq_Wrong_number);
				$data .= "<td $td_style><label>".$enq_Wrong_number.'</label></td>';

				$enq_Pickup_generation_Appointment = ((array_key_exists('Pickup generation_Appointment', $MData[$ls]))?$MData[$ls]['Pickup generation_Appointment']:0);
				array_push($export_data, $enq_Pickup_generation_Appointment);
				$data .= "<td $td_style><label>".$enq_Pickup_generation_Appointment.'</label></td>';

				$enq_total = ((array_key_exists('total_enquiries', $MData[$ls]))?$MData[$ls]['total_enquiries']:0);
				array_push($export_data, $enq_total);
				$data .= "<td $td_style><label>".$enq_total.'</label></td>';

				$total_leads_attempted = ($MData[$ls]['total_enquiries']-$MData[$ls]['new_unattempted']);
				array_push($export_data, $total_leads_attempted);
				$data .= "<td $td_style><label>".$total_leads_attempted.'</label></td>';

				$att_not_contactable = ((array_key_exists('att_Not contactable', $MData[$ls]))?$MData[$ls]['att_Not contactable']:0);
				array_push($export_data, $att_not_contactable);
				$data .= "<td $td_style><label>".$att_not_contactable.'</label></td>';


				$att_Call_back = ((array_key_exists('att_Call_back', $MData[$ls]))?$MData[$ls]['att_Call_back']:0);
				array_push($export_data, $att_Call_back);
				$data .= "<td $td_style><label>".$att_Call_back.'</label></td>';

				$att_Follow_up = ((array_key_exists('att_Follow_up', $MData[$ls]))?$MData[$ls]['att_Follow_up']:0);
				array_push($export_data, $att_Follow_up);
				$data .= "<td $td_style><label>".$att_Follow_up.'</label></td>';

				$att_Dropped = ((array_key_exists('att_Dropped', $MData[$ls]))?$MData[$ls]['att_Dropped']:0);
				array_push($export_data, $att_Dropped);
				$data .= "<td $td_style><label>".$att_Dropped.'</label></td>';

				$att_Interested = ((array_key_exists('att_Interested', $MData[$ls]))?$MData[$ls]['att_Interested']:0);
				array_push($export_data, $att_Interested);
				$data .= "<td $td_style><label>".$att_Interested.'</label></td>';

				$att_Wrong_number = ((array_key_exists('att_Wrong number', $MData[$ls]))?$MData[$ls]['att_Wrong number']:0);
				array_push($export_data, $att_Wrong_number);
				$data .= "<td $td_style><label>".$att_Wrong_number.'</label></td>';

				$att_Pickup_generation_Appointment = ((array_key_exists('att_Pickup generation_Appointment', $MData[$ls]))?$MData[$ls]['att_Pickup generation_Appointment']:0);
				array_push($export_data, $att_Pickup_generation_Appointment);
				$data .= "<td $td_style><label>".$att_Pickup_generation_Appointment.'</label></td>';

				$total_attempts = ((array_key_exists('total_attempts', $MData[$ls]))?$MData[$ls]['total_attempts']:0);
				array_push($export_data, $total_attempts);
				$data .= "<td $td_style><label>".$total_attempts.'</label></td>';

				$avg_calls_not_contactable = ($enq_Not_contactable)?$att_not_contactable/$enq_Not_contactable:$enq_Not_contactable;
				array_push($export_data, (round($avg_calls_not_contactable, 2)));
				$data .= "<td $td_style><label>".(round($avg_calls_not_contactable, 2)).'</label></td>';

				$avg_calls_call_back = 	($enq_Call_back)?$att_Call_back/$enq_Call_back:$enq_Call_back;
				array_push($export_data, round($avg_calls_call_back, 2));
				$data .= "<td $td_style><label>".round($avg_calls_call_back, 2).'</label></td>';

				$avg_calls_follow_up = 	($enq_Follow_up)?$att_Follow_up/$enq_Follow_up:$enq_Follow_up;
				array_push($export_data, round($avg_calls_follow_up, 2));
				$data .= "<td $td_style><label>".round($avg_calls_follow_up, 2).'</label></td>';

				$avg_calls_dropped = 	($enq_Dropped)?$att_Dropped/$enq_Dropped:$enq_Dropped;
				array_push($export_data, round($avg_calls_dropped, 2));
				$data .= "<td $td_style><label>".round($avg_calls_dropped, 2).'</label></td>';

				$avg_calls_interested = ($enq_Interested)?$att_Interested/$enq_Interested:$enq_Interested;
				array_push($export_data, round($avg_calls_interested, 2));
				$data .= "<td $td_style><label>".round($avg_calls_interested, 2).'</label></td>';

				$avg_calls_wrong_number = ($enq_Wrong_number)?$att_Wrong_number/$enq_Wrong_number:$enq_Wrong_number;
				array_push($export_data, round($avg_calls_wrong_number, 2));
				$data .= "<td $td_style><label>".round($avg_calls_wrong_number, 2).'</label></td>';

				$avg_calls_Pickup_generation_Appointment = ($enq_Pickup_generation_Appointment)?$att_Pickup_generation_Appointment/$enq_Pickup_generation_Appointment:$enq_Pickup_generation_Appointment;
				array_push($export_data, round($avg_calls_Pickup_generation_Appointment, 2));
				$data .= "<td $td_style><label>".round($avg_calls_Pickup_generation_Appointment, 2).'</label></td>';

				$avg_call_attempts = ($total_leads_attempted)?$total_attempts/$total_leads_attempted:$total_leads_attempted;
				array_push($export_data, round($avg_call_attempts, 2));
				$data .= "<td $td_style><label>".round($avg_call_attempts, 2).'</label></td>';							
				$data .= '</tr>';

				if (!empty($export_data)){
					array_push($export_data_final, $export_data);
				}
			}
			
		}
		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
      
		$report_name = "Attemptwise MIS Report";
		echo $html =<<<HTML_Search
		<div id="mainBody">
			<div  id="imageLoading"></div
			<div id="TitleReport"> <h2>$report_name</h2></div>						
			<div id="SearchPanel">
			<form id="ReportRequest" name="ReportRequest" method="POST">
			<table cellpadding="0" cellspacing="10">
			<tr>
				<td><label>From Date: </label></td>
				<td> <input type="text" id="from_date" name="from_date" size="10" value='$_REQUEST[from_date]' /> 
					<img border="0" src="themes/SuiteR/images/jscalendar.gif" id="fromb" align="absmiddle" />
					<script type="text/javascript">
						Calendar.setup({inputField   : "from_date",
						ifFormat      :    "%d/%m/%Y", 
						button       : "fromb",
						align        : "right"});
					</script>
				 </td>
				 <td>&nbsp;&nbsp;</td>
			     <td><label>To Date: </label></td>
			     <td> <input type="text" id="to_date" name="to_date" size="10" value='$_REQUEST[to_date]' /> 
					<img border="0" src="themes/SuiteR/images/jscalendar.gif" id="tob" align="absmiddle" />
					<script type="text/javascript">
						Calendar.setup({inputField   : "to_date",
						ifFormat      :    "%d/%m/%Y", 
						button       : "tob",
						align        : "right"});
					</script>
				   </td>
			       <td>&nbsp;&nbsp;</td>
				 <td><label>All Leads</label>&nbsp;</td>
				 <td> <input type="checkbox" id="all_leads" name="all_leads" size="10" />
				 </td>
			       <td></td>
			<td>
			 </tr>
			</table>
			</br>
			<table cellspacing="10">
			<tr>
			<td><input type="submit" id="Run_Report" name="Run_Report" value="Run Report" />&nbsp;&nbsp;&nbsp;<input type="submit" id="clear" name="Clear" value="Clear">&nbsp;&nbsp;<input type="submit" id="Export" name="Export" value="Export" /> <!-- &nbsp;&nbsp;<input type="submit" id="Export_Chart" name="Export_Chart" value="Export Chart" /> -->&nbsp;&nbsp;&nbsp;
			</tr>
			</table>
			</form>
			</div>
		</div>
		
HTML_Search;
		echo $HTML_Data_header = <<<HTML_Data_header
		<div id="mainData">
			<div id="DataHeader">
			<table cellpadding="0" cellspacing="0"  border="1">
			<tr  height="25">
			<th  $header_style><label>Lead Source</lable></th>
			<th  $header_style><label>New (Unattempted)</lable></th>
			<th  $header_style><label>Not Contactable</lable></th>
			<th  $header_style><label>Call Back</lable></th>
			<th  $header_style><label>Follow Ups</lable></th>
			<th  $header_style><label>Not Eligible</lable></th>
			<th  $header_style><label>Interested</lable></th>
			<th  $header_style><label>Wrong Number</lable></th>
			<th  $header_style><label>Pick Up Generation</lable></th>
			<th  $header_style><label>Total Enquiries</lable></th>
			<th  $header_style><label>Total Leads Attempted</lable></th>
			<th  $header_style><label>Attempts Done Not Contactable</lable></th>
			<th  $header_style><label>Attempts Done Call Back</lable></th>
			<th  $header_style><label>Attempts Done Follow Ups</lable></th>
			<th  $header_style><label>Attempts Done Not Eligible</lable></th>
			<th  $header_style><label>Attempts Done Interested</lable></th>
			<th  $header_style><label>Attempts Done Wrong Number</lable></th>
			<th  $header_style><label>Attempts Done Pick Up Generation</lable></th>
			<th  $header_style><label>Total Call Attempts</lable></th>
			<th  $header_style><label>Average Calls Done on Not Contactable</lable></th>
			<th  $header_style><label>Average Calls Done on Call Back</lable></th>
			<th  $header_style><label>Average Calls Done on Follow Ups</lable></th>
			<th  $header_style><label>Average Calls Done on Not Eligible</lable></th>
			<th  $header_style><label>Average Calls Done on Interested</lable></th>
			<th  $header_style><label>Average Calls Done on Wrong Number</lable></th>
			<th  $header_style><label>Average Calls Done on Pick Up Generation</lable></th>
			<th  $header_style><label>Average Call Attempts</lable></th>
			</tr>
			$data
			</table>
			</div>
		</div>
HTML_Data_header;

		echo $js=<<<JS
		<script>
		    
        $('#Export_Chart').click(function(){
              a = document.getElementById("group_by[]");
              if(a.options[a.selectedIndex].value!='')
                return true;
              else {
                alert("Please select Across option ");
                return false;
              }
        });
        
        
        
       function showLoadingMesg()
        {
          $('#imageLoading').css('display','block');
          return true;
        }
        $('#Run_Report').attr('onclick','showLoadingMesg()');
		$('#send_email').click(function(){
		 email = document.getElementById("email").value;
		 if(email =='')
		 {
			alert("Please provide Email Address");
			 return false;
		 }
		
		});
		
		$('#clear').click(function(){
			$('#from_date').val('');
			$('#to_date').val('');
			$('select option').removeAttr("selected");
			return false;
		});
</script>		
JS;

		if(!empty($_REQUEST['Export']))
		{
			$timestamp = date('Y_m_d_His'); 
			ob_end_clean();
			ob_start();	
			// output headers so that the file is downloaded rather than displayed
			header('Content-Type: text/csv; charset=utf-8');
			header("Content-Disposition: attachment; filename=AttemptwiseMIS_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			fputcsv($output, array('Lead Source','New (Unattempted)','Not Contactable','Call Back','Follow Ups','Not Eligible','Interested','Wrong Number','Pick Up Generation','Total Enquiries','Total Leads Attempted','Attempts Done Not Contactable','Attempts Done Call Back','Attempts Done Follow Ups','Attempts Done Not Eligible','Attempts Done Interested','Attempts Done Wrong Number', 'Attempts Done Pick Up Generation', 'Total Call Attempts', 'Average Calls Done on Not Contactable', 'Average Calls Done on Call Back', 'Average Calls Done on Follow Ups', 'Average Calls Done on Not Eligible', 'Average Calls Done on Interested', 'Average Calls Done on Wrong Number', 'Average Calls Done on Pick Up Generation', 'Average Call Attempts'));

			foreach ($export_data_final as $row_data)
			{
				fputcsv($output,$row_data);
			}
			exit;
			
		}
	} //end of display
} //end of class
