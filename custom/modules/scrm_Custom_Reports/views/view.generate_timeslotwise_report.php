<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');

class UserNameTable{
	static $name_table;

	public static function init(){
		global $db;
		$query = "select group_concat(sg.name) as sg_name,u.user_name as user_name, u.id as user_id, LTRIM( RTRIM(		 CONCAT( IFNULL( u.first_name, '' ) , ' ', IFNULL( u.last_name, '' ) ) ) ) AS name from 				users u
						LEFT JOIN securitygroups_users sgu on sgu.user_id = u.id
						LEFT JOIN securitygroups sg on sg.id=sgu.securitygroup_id
						group by user_name";

		$result = $db->query($query);

		while($row = $db->fetchByAssoc($result)){
			if((strpos($row['sg_name'], 'Kenkei') !== false) or  (strpos($row['sg_name'], 'KServe') !== false)){
				self::$name_table[$row['user_id']]['name'] = $row['name'];
				self::$name_table[$row['user_id']]['sg_name'] = $row['name'];
				self::$name_table[$row['user_id']]['user_name'] = $row['user_name'];
			}
		}
	}

	public static function get_user_names(){
		return self::$name_table;
	}
}

UserNameTable::init();

class scrm_Custom_ReportsViewgenerate_timeslotwise_report extends SugarView {
	
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

		$assigned_user_id = $_REQUEST['assigned_user_id'];

		$ts0 = array('ts'=>"9 a.m. - 10 a.m.");
		$ts1 = array('ts'=>"10 a.m. - 11 a.m.");
		$ts2 = array('ts'=>"11 a.m. - 12 p.m.");
		$ts3 = array('ts'=>"12 p.m. - 1 p.m.");
		$ts4 = array('ts'=>"1 p.m. - 2 p.m.");
		$ts5 = array('ts'=>"2 p.m. - 3 p.m.");
		$ts6 = array('ts'=>"3 p.m. - 4 p.m.");
		$ts7 = array('ts'=>"4 p.m. - 5 p.m.");
		$ts8 = array('ts'=>"5 p.m. - 6 p.m.");
		$total = array('ts'=>'Total');
		/*
		time slots index mapping:
			0.	9 - 10
			1.	10 - 11
			2.	11 - 12
			3.	12 - 1
			4.	1 - 2
			5.	2 - 3
			6.	3 - 4
			7.	4 - 5
			8.	5 - 6

		Disposition Codes:
			1.	Not contactable
			2.	Wrong number
			3.	Call_back
			4.	Follow_up
			5.	Dropped
			6.	Interested
			7.	Pickup generation_Appointment
		*/
		$ls_array = $GLOBALS['app_list_strings']['lead_source_list'];
		$disposition_array = $GLOBALS['app_list_strings']['cstm_disposition_list'];
        
        $query = "";
        if ($assigned_user_id) {
        	$query = "SELECT l.lead_source, lc.disposition_c, sum(lc.attempts_done_c) as
					att_count, cast(l.date_entered as time) time
					from leads l
					LEFT JOIN leads_cstm lc ON l.id=lc.id_c
					WHERE l.deleted = 0
					AND l.assigned_user_id = '$assigned_user_id'
					AND l.date_entered BETWEEN '$from_date' AND '$to_date'
					GROUP BY l.lead_source, lc.disposition_c, l.id";
        }
        else{
        	$query = "SELECT l.lead_source, lc.disposition_c, sum(lc.attempts_done_c) as
					att_count, cast(l.date_entered as time) time
					from leads l
					LEFT JOIN leads_cstm lc ON l.id=lc.id_c
					WHERE l.deleted = 0
					AND l.date_entered BETWEEN '$from_date' AND '$to_date'
					GROUP BY l.lead_source, lc.disposition_c, l.id";
        }
		
        
        $result = $db->query($query);
		$data = array(0=>$ts0, 1=>$ts1, 2=>$ts2, 3=>$ts3, 4=>$ts4, 5=>$ts5, 6=>$ts6, 7=>$ts7, 8=>$ts8, 9=>$total);
		for ($i = 0; $i < sizeof($data); $i++){
			$data[$i]['total_enquiries'] = 0;
			$data[$i]['new_unattempted'] = 0;
			foreach($disposition_array as $key=>$disp){
				$data[$i][$key] = 0;
			}
		}

		while($row = $db->fetchByAssoc($result))
		{
			$time_entered = date("H:i:s", strtotime("+5 hours, +30 minutes", strtotime($row['time'])));
			if($time_entered >= 9 && $time_entered < 10){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[0]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[0][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[0]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
				}
			}
			else if($time_entered >= 10 && $time_entered < 11){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[1]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[1][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[1]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
				}
			}
			else if($time_entered >= 11 && $time_entered < 12){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[2]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[2][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[2]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
				}
			}
			else if($time_entered >= 12 && $time_entered < 13){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[3]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[3][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[3]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
				}
			}
			else if($time_entered >= 13 && $time_entered < 14){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[4]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[4][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[4]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
				}
			}
			else if($time_entered >= 14 && $time_entered < 15){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[5]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[5][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[5]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
				}
			}
			else if($time_entered >= 15 && $time_entered < 16){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[6]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[6][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[6]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
				}
			}
			else if($time_entered >= 16 && $time_entered < 17){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[7]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[7][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[7]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
				}
			}
			else if($time_entered >= 17 && $time_entered < 18){
				foreach($disposition_array as $key=>$disp){
					if ($key == $row['disposition_c']){
						if(empty($disp) || $disp == ' '){
							$data[8]['new_unattempted'] += 1;
							$data[9]['new_unattempted'] += 1;
						}
						else{
							$data[8][$key] += 1;
							$data[9][$key] += 1;
						}
						$data[8]['total_enquiries'] += 1;
						$data[9]['total_enquiries'] += 1;
					}
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
		global $db,$current_user;
		//print_r($_REQUEST);

		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";

		$data='';
		$MData = $this->getMatrixData($_REQUEST);
		$export_data_final = array();
		$row = 0;
		 if(!empty($MData)){
			foreach($MData as $d){
				$data .= "<td $td_style><label>".$d['ts'].'</label></td>';
				$data .= "<td $td_style><label>".$d['total_enquiries'].'</label></td>';
				$data .= "<td $td_style><label>".$d['new_unattempted'].'</label></td>';
				$data .= "<td $td_style><label>".$d['Not contactable'].'</label></td>';
				$data .= "<td $td_style><label>".$d['Call_back'].'</label></td>'; 
				$data .= "<td $td_style><label>".$d['Follow_up'].'</label></td>'; 
				$data .= "<td $td_style><label>".$d['Dropped'].'</label></td>'; 
				$data .= "<td $td_style><label>".$d['Interested'].'</label></td>'; 
				$data .= "<td $td_style><label>".$d['Wrong number'].'</label></td>'; 
				$data .= "<td $td_style><label>".$d['Pickup generation_Appointment'].'</label></td>';
				$data .= '</tr>';
			}
		}

		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
      
		$report_name = "Time-Slot wise MIS Report";
		$user_names = UserNameTable::get_user_names();
		$selected=$_REQUEST['assigned_user_id'];
		$options .= "<option value=''></option>";
		foreach ($user_names as $user_id=>$name_arr){
			if ($selected == $user_id)
				$options .= "<option selected value='$user_id'>$name_arr[name]</option>";
			else
				$options .= "<option value='$user_id'>$name_arr[name]</option>";
		}

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
				 <td></td>
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
			       <td></td>

			       <td>&nbsp;&nbsp;&nbsp;&nbsp;Assigned User Name</td>
			       <td>
			       <select id="assigned_user_id" name="assigned_user_id">
			       $options
			       </select>
			       </td>
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
			<th  $header_style><label>Time-Slot</lable></th>
			<th  $header_style><label>Total Enquiries</lable></th>
			<th  $header_style><label>New (Unattempted)</lable></th>
			<th  $header_style><label>Not Contactable</lable></th>
			<th  $header_style><label>Call Back</lable></th>
			<th  $header_style><label>Follow Ups</lable></th>
			<th  $header_style><label>Not Eligible</lable></th>
			<th  $header_style><label>Interested</lable></th>
			<th  $header_style><label>Wrong Number</lable></th>
			<th  $header_style><label>Pick Up Generation</lable></th>
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
			header("Content-Disposition: attachment; filename=TimeslotwiseMIS_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			fputcsv($output, array('Time-Slot', 'Total Enquiries', 'New (Unattempted)', 'Not Contactable', 'Call Back', 'Follow Ups', 'Not Eligible', 'Interested', 'Wrong Number', 'Pick Up Generation'));

			foreach ($MData as $d)
			{
				fputcsv($output,$d);
			}
			exit;
			
		}
	} //end of display
} //end of class
