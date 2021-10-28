<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
ini_set('display_errors', 'On');
class BeforeSave {
	
	public function target_date_assigned_to(&$bean, $event, $args) {
		
		echo "<pre>";
		 $assigned_user_id_fetched = $bean->fetched_row['assigned_user_id'];
		 $assigned_user_id = $bean->assigned_user_id;
		 
		 if(strcmp($assigned_user_id , $assigned_user_id_fetched) != 0) {
			$target_date_assigned_c = date("Y-m-d H:i:s");
			$target_date_assigned_c = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($target_date_assigned_c)));
			$bean->target_date_assigned_c = $target_date_assigned_c;
		}
		
		
	}
	
}

		
		
