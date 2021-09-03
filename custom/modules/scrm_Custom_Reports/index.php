<?php
	

if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('include/entryPoint.php');

require_once('include/database/DBManager.php');
require_once('config.php');
global $sugar_config;
$url = $sugar_config['site_url'];
global $current_user;

switch($_GET['report']) {

	default:
	include_once("custom_report.php");
	break;	
	
	/*case "customerDueReport":
	include_once("customerDueReport.php");
	break;	
	
	case "customerEmiDefaultReport":
	include_once("customerEmiDefaultReport.php");
	break;

	case "":
	include_once("opportunityReport.php");
	break;
	*/

}
		
?>

