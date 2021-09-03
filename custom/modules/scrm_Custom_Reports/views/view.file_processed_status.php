<?php
/*
Created By : Nikhil Kumar

*/
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
class scrm_Custom_ReportsViewfile_processed_status extends SugarView {
	
	private $chartV;

    function __construct(){    
        parent::SugarView();
    }

    function dbQuery($query){
    	global $sugar_config;
		$databasehost = $sugar_config['dbconfig']['db_host_name'];
		$databasename = 'vaaradhi';//$sugar_config['dbvaradhiconfig']['db_name'];
		$databasetable = "file_process_step_tracker";
		$databaseusername = $sugar_config['dbconfig']['db_user_name'];
		$databasepassword = $sugar_config['dbconfig']['db_password'];
		$qry_result = null;
		mysql_connect($databasehost, $databaseusername, $databasepassword);
		mysql_select_db($databasename) or die(mysql_error());

		try{
	    	$qry_result = mysql_query($query) or die(mysql_error());
		}catch(Exception $e){
			die($e->message);
		}
		return $qry_result;

    }
    function logic_something($REQUEST)
    {
    	
    	$tableOutput = "";
    	if(isset($REQUEST["submit"])) {
    		// echo "hello";
    		$file_id = $REQUEST['filen'];
			if ($file_id == ""){
				die('You must choose a file name');
			//continue;
			}
	    	
			$show_result = "select f.id_file,f.file_name,f.file_location,f.file_no_rows,f.upload_date,f.uploaded_by,s.step_name,fs.step_status,fs.no_record_processed,fs.step_start_time, fs.step_end_time from file_process_tracker f  left JOIN  file_process_step_tracker fs on f.id_file=fs.fk_file   inner join  step_detail_master s  on s.id_step=fs.fk_step where f.id_file=".$file_id." order by fs.fk_step*1";
			$qry_result = $this->dbQuery($show_result);

			$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
			$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";
			$i=0;



			//Build Result String
			   $tableOutput .= "<table id=\"table\" border=\"1\" cellspacing=0 cellpadding=0>";
			   $tableOutput .= "<tr>";
			   $tableOutput .= "<td ".$header_style.">file name</td>";
			   $tableOutput .= "<td ".$header_style.">file id</td>";
			   $tableOutput .= "<td ".$header_style.">total no. of rows</td>";
			   $tableOutput .= "<td ".$header_style.">upload date</td>";
			   $tableOutput .= "<td ".$header_style.">uploaded by</td>";
			   $tableOutput .= "<td ".$header_style.">step name</td>";
			   $tableOutput .= "<td ".$header_style.">step status</td>";
			   $tableOutput .= "<td ".$header_style.">records processed</td>";
			   $tableOutput .= "<td ".$header_style.">step start time</td>";
			   $tableOutput .= "<td ".$header_style.">step end time</td>";
			   $tableOutput .= "</tr>";
			   
			   // Insert a new row in the table for each person returned
			   while($row = mysql_fetch_array($qry_result)) {
			      $tableOutput .= "<tr>";
			      $tableOutput .= "<td ".$td_style.">$row[file_name]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[id_file]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[file_no_rows]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[upload_date]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[uploaded_by]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[step_name]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[step_status]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[no_record_processed]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[step_start_time]</td>";
			      $tableOutput .= "<td ".$td_style.">$row[step_end_time]</td>";
			      $tableOutput .= "</tr>";
			   }
		   	   $tableOutput .= "</table>";
			 }
		 
	    	
		 echo $tableOutput;
		//echo $this->view_object_map['tableOutput'];die();	 	
		// $this->view_object_map['other']=$tableOutput;	
    }
    function display()
	{
		//echo "hello";
		//echo "var is:" .$this->request['result'];

		global $sugar_config;$url=$sugar_config['site_url'];
		$statement = ("select id_file,file_name from file_process_tracker order by upload_date desc");
		$qry_result = $this->dbQuery($statement) or die(mysql_error());
		$select_st = "<select id='filen' name='filen'><option value=''>-Choose a file from below:-</option>";
		 while($row = mysql_fetch_assoc($qry_result)) {
		 	$select_st .= "<option value='".$row['id_file']."' >".$row['file_name']."</option>";
		 }
		 $select_st .= "</select>";
		 
		//echo $HTML_Data_header = <<<HTML_Data_header
		?>
		<h1>File Processed utility</h1>
		<br/>
		
		<form action="?module=scrm_Custom_Reports&action=file_proccessed_status" method="post" enctype="multipart/form-data">
    		<div class="form-group">
    <label for="fileToUpload">Select file name:</label>
    <?= $select_st; ?>
    </div>
    <br>
    <input type="submit" value="Get file details" name="submit"><br/><br/>
</form>
<?php
	$this->logic_something($_REQUEST);
//HTML_Data_header;
//if($this->view_object_map['tableOutput']){
		// echo $this->view_object_map['other'];
	//}
	
	}
} //end of class
