<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
class scrm_Custom_ReportsViewgenerate_camcitywise_report extends SugarView {
	
	private $chartV;
	private $opportunity_status_c;
	private $lead_sources;
	private $total_count;
	private $header_list;
	private $csv_rows;
    function __construct(){
    	$this->opportunity_status_c =array();
    	$this->lead_sources = array();
    	$this->total_count = 0;
    	$this->csv_rows = array();
    	$this->header_list = array();
        parent::SugarView();
    }
    
	function getMatrixData($post)
	{
		
		global $db;	
		global $current_user;	
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
		//echo "herel";
		
		$ls_array = $GLOBALS['app_list_strings']['lead_source_list'];
             
		// $query = "select count(*) c,lc.opportunity_status_c, l.assigned_user_id, lc.pickup_appointment_city_c from opportunities l join opportunities_cstm lc on l.id=lc.id_c 
		// where l.date_entered BETWEEN '$from_date' and '$to_date' group by assigned_user_id, opportunity_status_c, pickup_appointment_city_c";

		$all_leads = $_REQUEST['all_leads'];

		$query = "";
		if(isset($all_leads)){
			$query = "SELECT o.id as id, u.id as user_id from opportunities o join leads l on l.opportunity_id=o.id join users u on u.id = l.assigned_user_id where l.assigned_user_id!='' and o.deleted=0 and o.date_entered BETWEEN '$from_date' and '$to_date'";
		}
		else {
			$securitygroups = array('Call Center-Tata BSS', 'Call Center-Kenkei', 'Call Center-KServe');
			$sg = join("','",$securitygroups);

	        $query = "SELECT o.id as id, u.id as user_id from opportunities o join leads l on l.opportunity_id=o.id join users u on u.id = l.assigned_user_id join securitygroups_users sgu on sgu.user_id=l.assigned_user_id join securitygroups sg on sg.id=sgu.securitygroup_id where sg.name in ('$sg') and l.assigned_user_id!='' and o.deleted=0 and o.date_entered BETWEEN '$from_date' and '$to_date'";
		}
		$result = $db->query($query);
		$data = array();
		// var_dump($result);
		while($row = $db->fetchByAssoc($result))
		{	
			// var_dump($row);
			$id = $row['id'];

			$opp = BeanFactory::getBean('Opportunities',$id);
			$assigned_user_id = $opp->assigned_user_id;
			$pickup_appointment_city_c = $opp->pickup_appointment_city_c;
			if(empty($assigned_user_id) || $assigned_user_id== " ")continue;
			if(empty($pickup_appointment_city_c))continue;
			
			if ($opp->load_relationship('opportunities_sales_opportunity_status_1'))
			{
			    //Fetch related beans
			    $relatedBeans = $opp->opportunities_sales_opportunity_status_1->getBeans(array('limit' => 1, 'orderby' => 'date_modified DESC'));
			    // var_dump($relatedBeans);
			    /*Leads not updated</label></th>
								<th  $header_style><label>Leads not contactable</label></th>
								<th  $header_style><label>Leads in Callback</label></th>
								<th  $header_style><label>Leads in Followup</label></th>
								<th  $header_style><label>Leads not interested
								*/
			    if(!array_key_exists($assigned_user_id, $data)){
					$data[$assigned_user_id] = array();
				}
			    if(!array_key_exists($pickup_appointment_city_c, $data[$assigned_user_id])){
						$data[$assigned_user_id][$pickup_appointment_city_c] = array();
						$data[$assigned_user_id][$pickup_appointment_city_c]['total'] = 
						$data[$assigned_user_id][$pickup_appointment_city_c]['diff'] = 
						$data[$assigned_user_id][$pickup_appointment_city_c]['logins'] =
						$data[$assigned_user_id][$pickup_appointment_city_c]['sanctions'] =
						$data[$assigned_user_id][$pickup_appointment_city_c]['rejects'] = 
						$data[$assigned_user_id][$pickup_appointment_city_c]['disbursals'] =
						$data[$assigned_user_id][$pickup_appointment_city_c]['credit'] =
						$data[$assigned_user_id][$pickup_appointment_city_c]['credit rejected'] =
						$data[$assigned_user_id][$pickup_appointment_city_c]['open'] =
						$data[$assigned_user_id][$pickup_appointment_city_c]['disbursal_value'] =
						$data[$assigned_user_id][$pickup_appointment_city_c][2] = 
						$data[$assigned_user_id][$pickup_appointment_city_c][3] =
						$data[$assigned_user_id][$pickup_appointment_city_c][4] = 
						$data[$assigned_user_id][$pickup_appointment_city_c][5] =
						$data[$assigned_user_id][$pickup_appointment_city_c][6] = 
						$data[$assigned_user_id][$pickup_appointment_city_c][7]=0;
				}
				$data[$assigned_user_id][$pickup_appointment_city_c]['total'] +=1;
				if($opp->sales_stage == 'Submitted'){
					$data[$assigned_user_id][$pickup_appointment_city_c]['logins'] += 1;
				}else if($opp->sales_stage == 'Rejected'){
					$data[$assigned_user_id][$pickup_appointment_city_c]['rejects'] += 1;
				}else if($opp->sales_stage == 'Sanctioned'){
					$data[$assigned_user_id][$pickup_appointment_city_c]['sanctions'] += 1;
				}else if($opp->sales_stage == 'Disbursed'){
					$data[$assigned_user_id][$pickup_appointment_city_c]['disbursals'] += 1;
					if(!empty($opp->amount)){
						$data[$assigned_user_id][$pickup_appointment_city_c]['disbursal_value'] += $opp->amount;
					}else if(!empty($opp->loan_amount_sanctioned_c)){
						$data[$assigned_user_id][$pickup_appointment_city_c]['disbursal_value'] += $opp->loan_amount_sanctioned_c;
					}else{
						$data[$assigned_user_id][$pickup_appointment_city_c]['disbursal_value'] += $opp->loan_amount_c;
					}
				}else if($opp->sales_stage == 'credit'){
					$data[$assigned_user_id][$pickup_appointment_city_c]['credit'] += 1;
				}else if($opp->sales_stage == 'credit rejected'){
					$data[$assigned_user_id][$pickup_appointment_city_c]['credit rejected'] += 1;
				}else if($opp->sales_stage == 'open'){
					$data[$assigned_user_id][$pickup_appointment_city_c]['open'] += 1;
				}
				
				if($relatedBeans){
				    foreach($relatedBeans as $key=>$value){
				    	/*2-follow up
				    	3-login
				    	4-not contactable
				    	5-not eligible
				    	6-not interested
				    	7-Pick up
				    	*/
				    	$status = $value->status;
				    	if(!array_key_exists($status, $data[$assigned_user_id][$pickup_appointment_city_c])) {
				    		$data[$assigned_user_id][$pickup_appointment_city_c][$status]=0;
				    	}
				    	$data[$assigned_user_id][$pickup_appointment_city_c][$status] +=1;
				    	
				    	// var_dump($value);
					    // echo 'value is'.$value->status;
					    break;
					}
				}else{
					$data[$assigned_user_id][$pickup_appointment_city_c]['diff'] += 1;
					// $data[$assigned_user_id][$pickup_appointment_city_c]['total'] +=1;
				}
			}
			
		}

		return $data;
	}
	
		
    function display()
	{
		
		
		$MData = $this->getMatrixData($_REQUEST);
		

		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
      
		$report_name = "Cam City Wise Report";
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
				 <td><b>All Leads </b>&nbsp;<input type="checkbox" id="all_leads" name="all_leads" /></td>
				 <td> 
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
		$this->header_list = array("City Name","Name of CAM","Total Leads Given","Leads not updated","Leads not contactable",
					"Leads in Callback","Leads in Followup","Leads not interested","Leads not eligible", "Leads in pickup",
					 "Logins", "Sanctions", "Rejects","Credit", "Credit Rejects", "Open",
					"Disbursal Count", "Disbursal Value", "Login-Leads", "Disbursal-Leads", "Disbursal Value/Disbursal Count");
		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";
		$table = "<table><tr>";
		foreach($this->header_list as $value){
			$table .= "<th  $header_style><label>$value</label></th>";
		}
		
			$table .= "</tr>";

			// var_dump($MData);
		foreach($MData as $assigned_user_id=>$cityArr){
			// echo $assigned_user_id;
			$user = BeanFactory::getBean('Users',$assigned_user_id);
			$user_name = $user->first_name.' '.$user->last_name;
			if(empty($user_name) || $user_name==" ")$user_name=$assigned_user_id;
			
			foreach($cityArr as $city=>$oppArr){
				$row = array();
				$total = $oppArr['total'];$left=$oppArr['diff'];
				$logins = $oppArr['logins']; $sanctions=$oppArr['sanctions'];
				$rejects = $oppArr['rejects']; $disbursal_value=$oppArr['disbursal_value'];
				$disbursals = $oppArr['disbursals']; $login_leads = round($logins/$total*1.0,2);
				$disbursal_leads = round($disbursals/$total*1.0,2); $disbursals_avg = round($disbursal_value/$disbursals*1.0,2);
				
				array_push($row,$city);
				array_push($row,$user_name);
				array_push($row,$total);
				array_push($row,$left);
				array_push($row,$oppArr[4]);
				array_push($row,0);
				array_push($row,$oppArr[2]);
				array_push($row,$oppArr[6]);
				array_push($row,$oppArr[5]);
				array_push($row,$oppArr[7]);
				array_push($row,$logins);
				array_push($row,$sanctions);
				array_push($row,$rejects);
				array_push($row,$oppArr['credit']);
				array_push($row,$oppArr['credit rejected']);
				array_push($row,$oppArr['open']);
				array_push($row,$disbursals);
				array_push($row,$disbursal_value);
				array_push($row,$login_leads);
				array_push($row,$disbursal_leads);
				array_push($row,$disbursals_avg);
				array_push($this->csv_rows,$row);
				
			}
			
			// $table .= "</tr>";
			
		}
		foreach ($this->csv_rows as $row){
			$table .="<tr>";
			foreach ($row as $key => $value) {
				# code...
				$table .= "<td $td_style><label>$value</label></td>";
			}
			$table .="</tr>";
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
			header("Content-Disposition: attachment; filename=camCityWise_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			// foreach($this->header_list as $value){
			// 	fputcsv($output, $value);
			// }
			fputcsv($output,$this->header_list);
			foreach ($this->csv_rows as $row_data)
			{
				fputcsv($output,$row_data);
			}
			exit;
			
		}
	} //end of display
} //end of class
