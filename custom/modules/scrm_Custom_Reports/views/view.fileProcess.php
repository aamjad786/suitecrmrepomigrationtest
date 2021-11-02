<?php

if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
class scrm_Custom_ReportsViewfileProcess extends SugarView {
	
	private $chartV;

    function __construct(){    
        parent::SugarView();
    }
    function display()
	{
		//echo "hello";
		//echo "var is:" .$this->request['result'];

		global $sugar_config;$url=$sugar_config['site_url'];
		echo $HTML_Data_header = <<<HTML_Data_header
		<h1>CSV DATA FILE upload utility</h1>
		<br/>
		<style>
		label {
    /* Other styling.. */
    text-align: right;
    clear: both;
    float:left;
    margin-right:15px;
}</style>
		<a href="csvUpload/query2.csv" download>
		Download sample file </a><br/><br/>
		<form action="$url/index.php?module=scrm_Custom_Reports&action=fileProcess" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <label for="fileToUpload">Select CSV File to upload(Max 50 MB):</label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    </div>
    <br>
    <input type="submit" value="Upload CSV File" name="submit"><br/><br/>
</form>
HTML_Data_header;
?>
<style type=”text/css”>
	td,th{
	border: 1px solid #000 !important;
	padding: 10px !important;
	}</style>
<?php
	if($this->view_object_map['myDataKey']){
		echo $this->view_object_map['myDataKey'];
	}
	if($this->view_object_map['tableOutput']){
		echo $this->view_object_map['tableOutput'];
	}
	
	}
} //end of class
