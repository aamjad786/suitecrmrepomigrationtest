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
	 $this->column_order = array('LeadSource','Open','Logged_In','Sanctioned','Disbursed');

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
	public function Columns_TargetCount(){
		$this->name = 'lead_source';
	}

	public function calculate($row, Row $class_row){
		$ls = $row['lead_source'];
		$dsa_code_c = $row['dsa_code_c'];
		if($ls == 'Facebook' || $ls == 'Web Site' || $ls == 'Missed Calls' || $ls == 'missed_calls_sms'){
			$this->value = 'Marketing';
		}
		if($ls == 'Self Generated'){
			$this->value = 'Direct';
		}
		if($ls == 'Tele_Calling'){
			$this->value = 'Tele-Calling';
		}
		if(!empty($dsa_code_c)){
			$this->value = 'DSA';
		}
	}
}

class Columns_Open extends Columns{
	public function Columns_New(){
		$this->name = 'Open';
	}

	public function calculate($row, Row $class_row){
		if($row['sales_stage'] === 'Open')
			$this->value += $row['count'];
	}
}

class Columns_Logged_In extends Columns{
	public function Columns_New(){
		$this->name = 'Submitted';
	}

	public function calculate($row, Row $class_row){
		if($row['sales_stage'] === 'Submitted')
			$this->value += $row['count'];
	}
}

class Columns_Sanctioned extends Columns{
	public function Columns_New(){
		$this->name = 'Sanctioned';
	}

	public function calculate($row, Row $class_row){
		if($row['sales_stage'] === 'Sanctioned' or $row['sales_stage'] === 'Disbursed')
			$this->value += $row['count'];
	}
}

class Columns_Disbursed extends Columns{
	public function Columns_New(){
		$this->name = 'Disbursed';
	}

	public function calculate($row, Row $class_row){
		if($row['sales_stage'] === 'Disbursed')
			$this->value += $row['count'];
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
	public function run($from_date, $to_date){
		global $db;
		$this->column_order_obj = new Column_Order;
		$column_order = $this->column_order_obj->get_column_order();
		$this->ls_array = array('Marketing' => 'Marketing', 'Direct'=>'Direct', 'Tele-Calling'=>'Tele-Calling', 'DSA'=>'DSA');

		foreach($this->ls_array as $key=>$val){
			if ($key != '' and !empty($key)){
				$this->report[$key] = new Row();
				$this->report[$key]->get_column('LeadSource')->setValue($key);
			}
		}

		$query = "SELECT count( o.id ) AS count, o.lead_source, o.assigned_user_id, o.sales_stage, oc.dsa_code_c, LTRIM( RTRIM( CONCAT( IFNULL( u.first_name, '' ) , ' ', IFNULL( u.last_name, '' ) ) ) ) AS name
				FROM opportunities o
				JOIN opportunities_cstm oc ON o.id = oc.id_c
				LEFT JOIN users u on u.id = o.assigned_user_id
				AND u.deleted = 0
				WHERE o.deleted =0
				AND o.date_entered
				BETWEEN '$from_date'
				AND '$to_date'
				GROUP BY o.assigned_user_id, o.lead_source, o.sales_stage, oc.dsa_code_c";
	        
	    $result = $db->query($query);
	    while($row = $db->fetchByAssoc($result)){
	    	$lead_source = $row['lead_source'];
			$dsa_code_c = $row['dsa_code_c'];
			if(!empty($dsa_code_c)){
				$lead_source = 'DSA';
				foreach ($column_order as $column) {
		    		if($lead_source)
		    			$this->report[$lead_source]->get_column($column)->calculate($row, $this->report[$lead_source]);
		    	}
			}
			if($lead_source == 'Facebook' || $lead_source == 'Web Site' || $lead_source == 'Missed Calls' || $lead_source == 'missed_calls_sms'){
				$lead_source = 'Marketing';
				foreach ($column_order as $column) {
		    		if($lead_source)
		    			$this->report[$lead_source]->get_column($column)->calculate($row, $this->report[$lead_source]);
		    	}
			}
			else if($lead_source == 'Self Generated'){
				$lead_source = 'Direct';
				foreach ($column_order as $column) {
		    		if($lead_source)
		    			$this->report[$lead_source]->get_column($column)->calculate($row, $this->report[$lead_source]);
		    	}
			}
			else if($lead_source == 'Tele_Calling'){
				$lead_source = 'Tele-Calling';
				foreach ($column_order as $column) {
		    		if($lead_source)
		    			$this->report[$lead_source]->get_column($column)->calculate($row, $this->report[$lead_source]);
		    	}
			}
	    }

	    ksort($this->report);

	}

	public function get_report(){
		return $this->report;
	}

}

class scrm_Custom_ReportsViewgenerate_lead_source_report extends SugarView {
	
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

		$report = new Report();
		$report->run($from_date, $to_date);

		return $report->get_report();
	}
	
	
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
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
				if($column !== 'Unit_Name'){
					$data .= "<td $td_style>".$row->get_column($column)->getValue().'</label></td>';
					array_push($export_data, $row->get_column($column)->getValue());
				}
			}
			$data .= '</tr>';
			if (!empty($export_data)){
					array_push($export_data_final, $export_data);
			}
		}

		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
		$report_name = "Lead Source Report";
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
			<th  $header_style><label>Open</lable></th>
			<th  $header_style><label>Logged In</lable></th>
			<th  $header_style><label>Sanctioned</lable></th>
			<th  $header_style><label>Disbursed</lable></th>
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
			header("Content-Disposition: attachment; filename=LeadSource_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			fputcsv($output, array('LeadSource','Open','Logged_In','Sanctioned','Disbursed'));

			foreach ($export_data_final as $row_data)
			{
				fputcsv($output,$row_data);
			}
			exit;
			
		}
	} //end of display
} //end of class
