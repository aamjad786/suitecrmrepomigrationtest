<?php

if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

ini_set("display_errors","Off");

class ProcessRecord {
	
	public function add_new_label($bean, $event, $args) {
		global $app_list_strings;
		$date_assigned = date("Y-m-d" , strtotime($bean->date_assigned));
		$today = date("Y-m-d");
		if(!empty($bean->date_assigned) && strtotime($today) == strtotime($date_assigned)){
		
		 $bean->name .= '<span class="label label-warning">New</span>';
	 }
	}
}
 
