<?php
// die('123');

if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
class scrm_Custom_ReportsViewdocument_requests_report extends SugarView {
		
    function display(){
    	$full_view=true;
    	include_once('custom/modules/Cases/customer_application_profile/cap_document_requests.php');
    	
    } //end of function

} //end of class
?>

