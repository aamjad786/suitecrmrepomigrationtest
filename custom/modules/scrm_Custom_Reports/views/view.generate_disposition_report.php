<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');

class Column_Order{

	private $column_order;

	public function Column_Order(){
	 $this->column_order = array('LeadSource','New','NotContactable','WrongNumber','CallBack','FollowUp','Dropped','Interested','PickUp_Generated','TotalEnquiries');

	 	//echo sizeof($this->column_order)."<br>";
	}

	public function get_column_order(){
		return $this->column_order;
	}
}

class Columns{
	protected $name;
	protected $value = 0;

	public function getName(){
		return $this->name;
	}

	public function getValue(){
		return $this->value;
	}

	public function setValue($value){
		$this->value = $value;
	}

	public function calculate($row, Row $class_row){
		echo "Base class calculate";
	}

}

class Columns_LeadSource extends Columns{
	public function calculate($row, Row $class_row){
		$this->value = $row['lead_source'];
	}
}

class Columns_New extends Columns{
	public function calculate($row, Row $class_row){
		if(!empty($row['name']) and (empty($row['disposition_c']) or $row['disposition_c'] === ''))
			$this->value += 1;
	}
}

class Columns_NotContactable extends Columns{
	public function calculate($row, Row $class_row){
		if($row['disposition_c'] === 'Not contactable' and !empty($row['name']))
			$this->value += 1;
	}
}

class Columns_WrongNumber extends Columns{
	public function calculate($row, Row $class_row){
		if($row['disposition_c'] === 'Wrong number' and !empty($row['name']))
			$this->value += 1;
	}
}

class Columns_CallBack extends Columns{
	public function calculate($row, Row $class_row){
		if($row['disposition_c'] === 'Call_back' and !empty($row['name']))
			$this->value += 1;
	}
}

class Columns_FollowUp extends Columns{
	public function calculate($row, Row $class_row){
		if($row['disposition_c'] === 'Follow_up' and !empty($row['name']))
			$this->value += 1;
	}
}

class Columns_Dropped extends Columns{
	public function calculate($row, Row $class_row){
		if($row['disposition_c'] === 'Dropped' and !empty($row['name']))
			$this->value += 1;
	}
}

class Columns_Interested extends Columns{
	public function calculate($row, Row $class_row){
		if($row['disposition_c'] === 'Interested' and !empty($row['name']) and $row['sub_disposition_c'] !== 'Lead generated')
			$this->value += 1;

	}
}

class Columns_PickUp_Generated extends Columns{
	public function calculate($row, Row $class_row){
		if(($row['sub_disposition_c'] === 'Lead generated' or $row['disposition_c'] === 'Pickup generation_Appointment') and !empty($row['name']))
			$this->value += 1;
	}
}

class Columns_TotalEnquiries extends Columns{
	public function calculate($row, Row $class_row){
			$this->value = $class_row->get_column('New')->getValue() + $class_row->get_column('NotContactable')->getValue() + $class_row->get_column('FollowUp')->getValue() + $class_row->get_column('CallBack')->getValue() + $class_row->get_column('WrongNumber')->getValue() + $class_row->get_column('PickUp_Generated')->getValue() + $class_row->get_column('Dropped')->getValue() + $class_row->get_column('Interested')->getValue();
	}
}


class Row{
	private $row_array;
	private $column_order_obj;
	public function Row(){
		$this->column_order_obj = new Column_Order;
		$column_order = $this->column_order_obj->get_column_order();

		foreach ($column_order as $column) {
			try{
				$class_name = "Columns_".$column;
				$this->row_array[$column] = new $class_name();
			}
			catch(Exception $e){
				echo "exception caught";
			}
		}
	}

	public function get_column($name){
		return $this->row_array[$name];
	}

}

class Report{
	private $report;
	private $column_order_obj;
	private $ls_array;

	public function Report(){
		$this->report = array();
	}
	public function run($from_date, $to_date, $assigned_user_id){
		global $db;
		$this->column_order_obj = new Column_Order;
		$column_order = $this->column_order_obj->get_column_order();
		$this->ls_array = $GLOBALS['app_list_strings']['lead_source_list'];

		foreach($this->ls_array as $key=>$val){
			if ($key != '' and !empty($key)){
				$this->report[$key] = new Row();
				$this->report[$key]->get_column('LeadSource')->setValue($key);
			}
		}

		$this->report['Total'] = new Row();
		$this->report['Total']->get_column('LeadSource')->setValue('Total');

		if ($assigned_user_id){

			$query = "select * from (SELECT o.id, o.lead_source, ADDTIME(o.date_entered, '05:30:00') as 					date_entered, CAST(ADDTIME(o.date_entered, '05:30:00') as DATE) as date_entered_onlydate, o.		date_modified, o.assigned_user_id, oc.disposition_c, oc.sub_disposition_c, LTRIM( RTRIM( CONCAT( IFNULL( u.				first_name, '' ) , ' ', IFNULL( u.last_name, '' ) ) ) ) AS name
	        			FROM leads o
	        			JOIN leads_cstm oc ON o.id = oc.id_c
	        			LEFT JOIN users u on u.id = o.assigned_user_id
	        			AND u.deleted = 0
	        			WHERE o.deleted =0) t1
	        			where t1.date_entered BETWEEN '$from_date' AND '$to_date'
	        			AND t1.assigned_user_id='$assigned_user_id'
	        			GROUP BY t1.id";
        }

        else{
        	$query = "select * from (SELECT o.id, o.lead_source, ADDTIME(o.date_entered, '05:30:00') as 					date_entered, CAST(ADDTIME(o.date_entered, '05:30:00') as DATE) as date_entered_onlydate, o.		date_modified, o.assigned_user_id, oc.disposition_c, oc.sub_disposition_c, LTRIM( RTRIM( CONCAT( IFNULL( u.				first_name, '' ) , ' ', IFNULL( u.last_name, '' ) ) ) ) AS name
	        			FROM leads o
	        			JOIN leads_cstm oc ON o.id = oc.id_c
	        			LEFT JOIN users u on u.id = o.assigned_user_id
	        			AND u.deleted = 0
	        			WHERE o.deleted =0) t1
	        			where t1.date_entered BETWEEN '$from_date' AND '$to_date'
	        			GROUP BY t1.id";
        }
	        
	    $result = $db->query($query);
	    while($row = $db->fetchByAssoc($result)){
	    	$lead_source = $row['lead_source'];
	    	if (!array_key_exists($lead_source, $this->report)){
	    		$this->report[$lead_source] = new Row();
	    	}

	    	foreach ($column_order as $column) {
	    		$this->report[$lead_source]->get_column($column)->calculate($row, $this->report[$lead_source]);
	    		if($column !== 'LeadSource')
	    			$this->report['Total']->get_column($column)->calculate($row, $this->report['Total']);
	    	}
	    }

	    $row_temp = $this->report['Total'];
	    unset($this->report['Total']);
	    $this->report['Total'] = $row_temp;

	}

	public function get_report(){
		return $this->report;
	}

}

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
			if((strpos($row['sg_name'], 'Tata BSS') !== false) or (strpos($row['sg_name'], 'Kenkei') !== false) or (strpos($row['sg_name'], 'KServe') !== false)){
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








class scrm_Custom_ReportsViewgenerate_disposition_report extends SugarView {
	
	private $chartV;
    function __construct(){    
        parent::SugarView();
    }
    
    private $TATA_caller_array = array();
	private $Kenkei_caller_array = array();
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


		$report = new Report();
		$report->run($from_date, $to_date, $assigned_user_id);

		return $report->get_report();
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
		global $db;
		$report = $this->getMatrixData($_REQUEST);
		$export_data_final = array();

		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";


		$column_order_obj = new Column_Order;
		$column_order = $column_order_obj->get_column_order();

		$data = "";

		foreach($report as $row){
			$export_data = array();

			$data .= '<tr height="25">';
			foreach ($column_order as $column){
				$data .= "<td $td_style><label>".$row->get_column($column)->getValue().'</label></td>';
				array_push($export_data, $row->get_column($column)->getValue());
			}
			$data .= '</tr>';
			if (!empty($export_data)){
					array_push($export_data_final, $export_data);
			}
		}

		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
		$report_name = "Disposition Report";
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

			<td>
			
				 </td>
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
			<th  $header_style><label>Wrong Number</lable></th>
			<th  $header_style><label>Call Back</lable></th>
			<th  $header_style><label>Follow Up</lable></th>
			<th  $header_style><label>Not Eligible</lable></th>
			<th  $header_style><label>Interested</lable></th>
			<th  $header_style><label>Pick-up generated</lable></th>
			<th  $header_style><label>Total Enquiries</lable></th>
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
			header("Content-Disposition: attachment; filename=Disposition_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			fputcsv($output, array('Lead Source','New (Unattempted)','Not Contactable','Wrong Number','Call Back','Follow Up','Not Eligible','Interested','Pick-up generated','Total Enquiries'));

			foreach ($export_data_final as $row_data)
			{
				fputcsv($output,$row_data);
			}
			exit;
			
		}
	} //end of display
} //end of class
