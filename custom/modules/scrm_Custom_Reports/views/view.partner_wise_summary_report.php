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
		$query = "select count(*) count, partner_name,disposition,primary_address_city from neo_paylater_leads where deleted=0 group by partner_name,disposition,primary_address_city;";
	    
	    $result = $db->query($query);
	    $data = array();
	    while($row = $db->fetchByAssoc($result)){
	    	$partner_name = $row['partner_name'];
	    	$disposition = $row['disposition'];
	    	$primary_address_city = $row['primary_address_city'];
	    	if(empty($partner_name) || empty($disposition) || empty($primary_address_city))
	    		continue;
	    	if (!array_key_exists($partner_name,$data)){
	    		$data[$partner_name] = array();
	    	}
	    	$partner_data = &$data[$partner_name];
	    	if (!array_key_exists($disposition,$partner_data)){
	    		$partner_data[$disposition] = array();
	    	}
	    	$partner_dispostion_data = &$partner_data[$disposition];
	    	if (!array_key_exists($primary_address_city,$partner_dispostion_data)){
	    		$partner_dispostion_data[$primary_address_city] = intval($row['count']);
	    	}else{
	    		$partner_dispostion_data[$primary_address_city] += intval($row['count']);
	    	}
	    }
	    // var_dump($data);die();
	    $this->report = $data;
	}

	public function get_report(){
		return $this->report;
	}

}

class scrm_Custom_ReportsViewpartner_wise_summary_report extends SugarView {
	
	private $chartV;
    
				
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
		$header_row[] = "#";
		// var_dump($header_row);die();
		foreach ($cities_array as $key=>$value){
			// if(empty($value))continue;
			$data .= "<th  $header_style><label>$value</lable></th>";
			$header_row[] = $value;
		}
		array_push($export_data_final, $header_row);
		$data .= "</tr>";

		// $export_data_final = array();
		$csv_final_data = array();
		foreach ($partner_array as $partner_key => $partner_value) {
			$data .= "<tr><td $caption_style colspan='100'>$partner_value</td></tr>";
			$partner_data = array();
			array_push($export_data_final,array($partner_value));
			if(array_key_exists($partner_key,$report)){
				$partner_data = $report[$partner_key];
			}
			$partner_csv_data = array();
			
			foreach ($disp_array as $disp_key => $disp_value) {
				if(empty($disp_key))continue;
				$csv_row_data = array();
				$csv_row_data[] = $disp_value;
				$data .= "<tr><td $header_style><label>".$disp_value.'</label></td>';
				$partner_csv_row_data = array();
				foreach ($cities_array as $city_key=>$city_value) {
					// if(empty($city_value))continue;
					if(!array_key_exists($disp_key,$partner_data)){
							$data .= "<td $td_style><label>0</label></td>";	
							// array_push($partner_csv_row_data, 0);
							$partner_csv_row_data[$city_key] = 0;
							$csv_row_data[]= 0;
					}else{
						$partner_disposition_data  = $partner_data[$disp_key];
						if(!array_key_exists($city_key,$partner_disposition_data)){
							$data .= "<td $td_style><label>0</label></td>";	
							// array_push($partner_csv_row_data, 0);
							$partner_csv_row_data[$city_key] = 0;
							$csv_row_data[]= 0;
						}else{
							$data .= "<td $td_style><label>".$partner_disposition_data[$city_key].'</label></td>';
							// array_push($partner_csv_row_data, $partner_disposition_data[$city_key]);
							$partner_csv_row_data[$city_value] = $partner_disposition_data[$city_key];
							$csv_row_data[]= $partner_disposition_data[$city_key];
						}
					}
				}
				array_push($export_data_final, $csv_row_data);
				$data .= '</tr>';
				if (!empty($partner_csv_row_data)){
						// array_push($partner_csv_data, $partner_csv_row_data);
					$partner_csv_data[$disp_value] = $partner_csv_row_data;
				}
			}
			if (!empty($partner_csv_data)){
					// array_push($csv_final_data, $partner_csv_data);
				$csv_final_data[$partner_value] = $partner_csv_data;
			}

			array_push($export_data_final, $csv_row_data);
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
