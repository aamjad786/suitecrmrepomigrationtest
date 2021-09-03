<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
class scrm_Custom_ReportsViewgenerate_subdisposition_report extends SugarView {
	
	private $chartV;
	private $sub_dispositions;
	private $lead_sources;
	private $total_count;
	private $csv_rows;
    function __construct(){
    	$this->sub_dispositions =array();
    	$this->lead_sources = array();
    	$this->total_count = 0;
    	$this->csv_rows = array();
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

		$all_leads = $_REQUEST['all_leads'];

		$ls_array = $GLOBALS['app_list_strings']['lead_source_list'];

		$query = "";
		if(isset($all_leads)){
			$query = "SELECT count(*) c, l.lead_source, lc.sub_disposition_c from leads l join leads_cstm lc on l.id = lc.id_c  join users u on u.id = l.assigned_user_id where l.date_entered BETWEEN '$from_date' and '$to_date' group by lc.sub_disposition_c,l.lead_source";
		}
		else {
			$securitygroups = array('Call Center-Tata BSS', 'Call Center-Kenkei', 'Call Center-KServe');
			$sg = join("','",$securitygroups);

			$query = "SELECT count(*) c, l.lead_source, lc.sub_disposition_c from leads l join leads_cstm lc on l.id = lc.id_c  join users u on u.id = l.assigned_user_id join securitygroups_users sgu on sgu.user_id=l.assigned_user_id join securitygroups sg on sg.id=sgu.securitygroup_id where sg.name in ('$sg') and l.date_entered BETWEEN '$from_date' and '$to_date' group by lc.sub_disposition_c,l.lead_source";
		}
        
        $result = $db->query($query);
		$data = array();

		while($row = $db->fetchByAssoc($result))
		{	
			$sub_disposition = $row['sub_disposition_c'];
			if(empty($sub_disposition))continue;
			$lead_source = $row['lead_source'];
			if(empty($lead_source))continue;
			$count = $row['c'];
			$this->total_count += $count;
			// echo $sub_disposition,$lead_source,$count;die();
			$data[$sub_disposition][$lead_source] = $count;
			if(!in_array($lead_source, $this->lead_sources, true)){
		        array_push($this->lead_sources, $lead_source);
		    }
		    if(!in_array($sub_disposition, $this->sub_dispositions, true)){
		        array_push($this->sub_dispositions, $sub_disposition);
		    }
		}

		return $data;
	}
	
		
    function display()
	{
		
		// $ls_array = $GLOBALS['app_list_strings']['lead_source_list'];	
		// global $db,$current_user;
		//print_r($_REQUEST);	
		// $data='';
		$MData = $this->getMatrixData($_REQUEST);
		// $export_data_final = array();
		// var_dump($this->lead_sources);die();
		
			

		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
      
		$report_name = "Sub Disposition Report";
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
		// echo "<br><h1>$report_name</h1><br/>";
		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";
		$table = "<table>
							<tr>
								<th  $header_style><label>Sub Dispostion</label></th>";
		foreach($this->lead_sources as $lead_source){
			$table .= "<th $header_style><label>$lead_source</label></th>";
		}
		$table .= "<th $header_style><label>Total Leads</label></th>";
		$table .= "<th $header_style><label>Percentage</label></th>
					</tr>";

		foreach($this->sub_dispositions as $sub_disposition){
			$table .= "<tr><td $td_style><label>$sub_disposition</label></td>";
			$sub_disposition_lead_count = 0;
			$row = array();
			array_push($row,$sub_disposition);
			foreach($this->lead_sources as $lead_source){
				$count = $MData[$sub_disposition][$lead_source];
				if(!empty($count)){
					$table .= "<td $td_style><label>$count</label></td>";
					$sub_disposition_lead_count += $count;
					array_push($row,$count);
 				}else{
					$table .= "<td $td_style><label>0</label></td>";
					array_push($row,0);
				}
			}
			$table .= "<td $td_style><label>$sub_disposition_lead_count</label></td>";
			$percentage = round($sub_disposition_lead_count/$this->total_count*100,2);
			$table .= "<td $td_style><label>$percentage</label></td>";
			$table .= "</tr>";
			array_push($row,$sub_disposition_lead_count);
			array_push($row, $percentage);
			array_push($this->csv_rows,$row);
		}
		$table .= "</table>";
		echo $table;



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
			header("Content-Disposition: attachment; filename=subDisposition_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			$sub_disposition = $this->sub_dispositions;
			$lead_sources = $this->lead_sources;
			array_unshift($lead_sources, 'Sub Disposition');
			array_push($lead_sources,'Total Leads','Percentage');
			fputcsv($output, $lead_sources);

			foreach ($this->csv_rows as $row_data)
			{
				fputcsv($output,$row_data);
			}
			exit;
			
		}
	} //end of display
} //end of class
