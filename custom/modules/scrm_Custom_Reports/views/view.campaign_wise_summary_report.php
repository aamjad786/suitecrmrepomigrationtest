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
		$query = "Select count(*) total_count,campaign from neo_paylater_leads where deleted=0 group by campaign";
	    
	    $result = $db->query($query);
	    while($row = $db->fetchByAssoc($result)){
	    	$campaign = $row['campaign'];
	    	if(!empty($campaign)){
		    	$lead_bean = new Neo_Paylater_Leads();
		    	$total_count = intval($row['total_count']);
		    	$leads_list = $lead_bean->get_full_list(null,"(disposition = 'sent_to_ng_login' and campaign = '$campaign' )");
		    	$converted_count = count($leads_list);
		    	$row['avg_tat'] = 'NA';
		    	$row['converted_percentage'] = 'NA';
		    	$row['Tat(0-7)'] = 0;
		    	$row['Tat(8-10)'] = 0;
		    	$row['Tat(11-15)'] = 0;
		    	$row['Tat(>15)'] = 0;
		    	$tat = 0;
		    	foreach($leads_list as $lead){
		    		$login_date = date_create($lead->converted_time);
		    		$created_date = date_create($lead->date_entered);
		    		$diff = date_diff($created_date,$login_date);
		    		// echo $diff->format("%R%a days");
		    		$diff = intval($diff->format("%a"));
		    		if($diff<8){
		    			$row['Tat(0-7)'] += 1;
		    		}else if($diff<11){
		    			$row['Tat(8-10)'] += 1;
		    		}else if($diff<16){
		    			$row['Tat(11-15)'] += 1;
		    		}else{
		    			$row['Tat(>15)'] += 1;
		    		}
		    		$tat += $diff;
		    	}
		    	if($converted_count>0){
		    		$row['avg_tat'] = $tat/$converted_count;
		    		$row['converted_percentage'] = floatval($converted_count/$total_count)*100;
		    		$row['converted_percentage'] .= '%';
		    	}
		    	$row['total_count'] = intval($total_count);
		    	$row['converted_count'] = $converted_count;
		    	$this->report[] = $row;
		    }
	    }
	    // var_dump($this->report);die();
	}

	public function get_report(){
		return $this->report;
	}

}

class scrm_Custom_ReportsViewcampaign_wise_summary_report extends SugarView {
	
	private $chartV;
    
    public static $report_name = "Campaign wise Conversions & TAT Summary";
    public static $columns = array('campaign'=>'Campaign Code',
				'total_count'=>'Count of Leads in each category',
				'converted_count'=>'Count of Conversions (Lead to Login) in each category',
				'avg_tat'=>'Avg Lead to Login TAT',
				'converted_percentage'=>'Conversion %');
    public static $columns2 = array('campaign'=>'Campaign Code',
				'converted_count'=>'Count of Conversions (Lead to Login) in each category',
				'Tat(0-7)'=>'Lead Conversion TAT (0-7 days)',
				'Tat(8-10)'=>'Lead Conversion TAT (8-10 days)',
				'Tat(11-15)'=>'Lead Conversion TAT (11-15 days)',
				'Tat(>15)'=>'Lead Conversion TAT (>15 days)');
				
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
		if($this->view_object_map['report_type']=='PL2')
			$columns = self::$columns;
		else
			$columns = self::$columns2;
		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
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
		$data = '<div id="mainData">
			<div id="DataHeader">
			<table cellpadding="0" cellspacing="0"  border="1">
			<tr  height="25">';
		foreach ($columns as $key=>$value){
			$data .= "<th  $header_style><label>$value</lable></th>";
		}
		$data .= "</tr>";
		foreach($report as $row){
			$export_data = array();

			$data .= '<tr height="25">';
			foreach ($columns as $column=>$value){
				$data .= "<td $td_style><label>".$row[$column].'</label></td>';
				array_push($export_data, $row[$column]);
			}
			$data .= '</tr>';
			if (!empty($export_data)){
					array_push($export_data_final, $export_data);
			}
		}
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
			fputcsv($output, array_values($columns));

			foreach ($export_data_final as $row_data)
			{
				fputcsv($output,$row_data);
			}
			exit;
			
		}
	} //end of display
} //end of class
