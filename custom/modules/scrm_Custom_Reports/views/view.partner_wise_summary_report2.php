<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');



class Report{
	private $report;

	public function Report(){
		$this->report = array();
	}
	
	public function run($from_date, $to_date, $assigned_user_id){
		global $db;
		$query = "Select count(*) total_count, partner_name, DATE_FORMAT(date_entered, '%Y%m') month, disposition from neo_paylater_leads where deleted=0 group by partner_name, DATE_FORMAT(date_entered, '%Y%m'), disposition;";
	    
	    $result = $db->query($query);
	    $data = array();
	    while($row = $db->fetchByAssoc($result)){
	    	$partner_name = $row['partner_name_c'];
	    	$disposition = $row['disposition'];
	    	$month = $row['month'];
	    	$count = $row['total_count'];
	    	// echo $count;
	    	if(empty($partner_name) || empty($disposition) )
	    		continue;
	    	if (!array_key_exists($partner_name,$data)){
	    		$data[$partner_name] = array();
	    	}
	    	$partner_data = &$data[$partner_name];
	    	if (!array_key_exists($month,$partner_data)){
	    		$partner_data[$month] = array();
	    		$partner_data[$month]['total_count'] = 0;
	    		$partner_data[$month]['converted_count'] = 0;
	    	}
	    	$partner_data[$month]['total_count'] += intval($count);
	    	// echo $partner_data[$month]['total_count'];
	    	// echo intval($total_count)."<br/>";
	    	if($disposition == 'sent_to_ng_login'){
	    		$partner_data[$month]['converted_count'] = intval($count);
	    	}
	    	
	    }
	    // var_dump($data);die();
	    $this->report = $data;
	}

	public function get_report(){
		return $this->report;
	}

}

class scrm_Custom_ReportsViewpartner_wise_summary_report2 extends SugarView {
	
	private $chartV;
    
    public static $report_name = "Campaign wise Conversions & TAT Summary";
				
    function __construct(){    
        parent::SugarView();
    }
	function getData($post)
	{
		
		global $db;	
		
		global $sugar_config;
			//From Date & To Date filter Condition
		
		$report = new Report();
		$report->run($from_date, $to_date, $assigned_user_id);

		return $report->get_report();
	}
	
		
    function display()
	{
		global $db;
		$report = $this->getData($_REQUEST);
		$export_data_final = array();
		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$caption_style="style=\"background-color:black; text-align:center;padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";
		$data = "";
		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
		$report_name = $this->view_object_map['report_name'];
		
		echo $html =<<<HTML_Search
		<div id="mainBody">
			<div  id="imageLoading"></div
			<div id="TitleReport"> <h2>$report_name</h2></div>						
			<div id="SearchPanel">
			<form id="ReportRequest" name="ReportRequest" method="POST">
			
			</br>
			<table cellspacing="10">
			<tr>
			<td><input type="submit" id="Run_Report" name="Run_Report" value="Run Report" />&nbsp;&nbsp;&nbsp;<input type="submit" id="Export" name="Export" value="Export" /> <!-- &nbsp;&nbsp;<input type="submit" id="Export_Chart" name="Export_Chart" value="Export Chart" /> -->&nbsp;&nbsp;&nbsp;
			</tr>
			</table>
			</form>
			</div>
		</div>
HTML_Search;
		
		$disp_array = $GLOBALS['app_list_strings']['paylater_disposition_list'];
		$cities_array = $GLOBALS['app_list_strings']['cstm_cities_list'];
		$partner_array = $GLOBALS['app_list_strings']['partner_name_list'];
		$data = "<div id='mainData'>
			<div id='DataHeader'>
			<table cellpadding='0' cellspacing='0'  border='1'>
			<tr  height='25'><th $header_style>#</th>";
		$export_data_final = array();
		$header_row = array("#","Count of leads in a month","Count of Conversions of those leads");;
		// var_dump($header_row);die();
		// foreach ($cities_array as $key=>$value){
			// if(empty($value))continue;
			$data .= "<th  $header_style><label>Count of leads in a month</lable></th>";
			$data .= "<th  $header_style><label>Count of Conversions of those leads</lable></th>";
		// }
		array_push($export_data_final, $header_row);
		$data .= "</tr>";
		// var_dump($report);die();
		// $export_data_final = array();
		$csv_final_data = array();
		foreach ($partner_array as $partner_key => $partner_value) {
			$data .= "<tr><td $caption_style colspan='100'>$partner_value</td></tr>";
			$partner_data = array();
			array_push($export_data_final,array($partner_value));
			if(array_key_exists($partner_key, $report)){
				$partner_data = $report[$partner_key];
			}
			$partner_csv_data = array();
			$year_month = 201801;
			// $month = 1;
			$current_year_month = intval(date('Ym'));
			for($ym = $year_month;$ym<=$current_year_month;$ym++){
				$mon = intval($ym%100)-1;
				$months = array('Jan','Feb','Mar','Apr','May','June','July','Aug','Sep','Oct','Nov','Dec');
				$data .= "<tr><td $header_style><label>".$months[$mon].' ' .intval($ym/100).'</label></td>';
				$csv_row_data = array();
				$csv_row_data[] = $ym;
				if(!array_key_exists($ym,$partner_data)){
					$data .= "<td $td_style><label>0</label></td>";	
					$csv_row_data[]= 0;
					$data .= "<td $td_style><label>0</label></td>";	
					$csv_row_data[]= 0;
				}else{
					$data .= "<td $td_style><label>".$partner_data[$ym]['total_count']."</label></td>";	
					$csv_row_data[]= $partner_data[$ym]['total_count'];
					$data .= "<td $td_style><label>".$partner_data[$ym]['converted_count']."</label></td>";	
					$csv_row_data[]= $partner_data[$ym]['converted_count'];
				}
				if($ym%100==12){
					$ym += 89;
				}
				$data .= "</tr>";
				array_push($export_data_final, $csv_row_data);
			}
			
			
		}
		// var_dump($export_data_final);die();
		$data .= '</table>
			</div>
		</div>';
		echo $data;

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
			header("Content-Disposition: attachment; filename=$report_name.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			// fputcsv($output, array_values($columns));

			foreach ($export_data_final as $row_data)
			{
				fputcsv($output,$row_data);
			}
			exit;
			
		}
	} //end of display
} //end of class
