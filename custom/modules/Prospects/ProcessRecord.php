<?php

if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');

ini_set("display_errors","Off");

class ProcessRecord {
	
	public function primary_address_city($bean, $event, $args) {
		global $app_list_strings;
		
		 if(!empty($bean->primary_address_city)){
			$bean->primary_address_city = ($app_list_strings['city_pincodes_list'][$bean->primary_address_city]?$app_list_strings['city_pincodes_list'][$bean->primary_address_city]:$bean->primary_address_city);
		}
        if(!empty($bean->alt_address_city)){
			$bean->alt_address_city = ($app_list_strings['city_pincodes_list'][$bean->alt_address_city]?$app_list_strings['city_pincodes_list'][$bean->alt_address_city]:$bean->alt_address_city);
		}
	}
}
