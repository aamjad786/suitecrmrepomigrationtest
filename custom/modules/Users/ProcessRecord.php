<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
ini_set('display_errors', 'On');
class ProcessRecord {
	
	public function add_edit_button(&$bean, $event, $args) {
		//~ echo '<pre>'; echo "Hi";
			//~ print_r($bean->first_name);
		$myfile = fopen("api_process.txt","a");	
		fwrite($myfile, "Hi Shakeer");
		fclose($myfile);	
	}
	
}

		
		
