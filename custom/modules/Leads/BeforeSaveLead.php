<?php

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once 'custom/CustomLogger/CustomLogger.php';
class BeforeSaveLead {

	public $logger;

	function __construct() {
		$this->logger =new CustomLogger('BeforeSaveLead');
		// $this->logger->log('debug', 'BeforeSaveLead Logger initialized');
	}

	public function checkMobileNumber(&$bean, $event, $args) {

		if (!empty($bean->dwh_sync_c)) {
			return;
		}

		$mobile = $this->validate_mobile(trim($bean->phone_mobile));

		if ($mobile != 1) {

			if ($_REQUEST['module'] == 'Import') {
				$bean->deleted = 1;
			}
		}

		$this->logger->log('debug', 'BeforeSaveLead checkMobileNumber completed');
	}

	public function checkDuplicateLead(&$bean, $event, $args) {

		global $db, $sugar_config;

		if (!empty($bean->dwh_sync_c)) {
			return;
		}
		if ($bean->is_renewal_c == 1) {
			return;
		}

		$mobile = $bean->phone_mobile;
		$scheme_c=$bean->scheme_c;
		
		$query  = "select id,scheme_c from leads l join leads_cstm lcstm where deleted = 0 and phone_mobile = '$mobile' and lcstm.scheme_c='$scheme_c'and date_entered> CURDATE() - INTERVAL 30 DAY order by date_entered desc limit 1";
		
		$result = $db->query($query);

		if (empty($bean->fetched_row) && $result->num_rows > 0) {

			if ($_REQUEST['module'] == 'Import') {
				$this->logger->log('debug', 'Duplicare Leads Found Marking As Deleted For Mobile No: '.$mobile);
				$bean->deleted = 1;
			} 
		}

		$this->logger->log('debug', 'BeforeSaveLead checkDuplicateLead completed');
	}

	public function fieldSanity(&$bean, $event, $args){

		$bean->stored_fetched_row_c = $bean->fetched_row;

		$bean->primary_address_city = strtoupper($bean->primary_address_city);

		$cities = array(
            'BENGALURU' => 'BANGALORE',
            'BHUBANESWAR' => 'BHUBANESHWAR',
            'VADODARA' => 'BARODA',
            'VIJAYAWADA' => 'VIJAYWADA',
            'VISAKHAPATNAM' => 'VIZAG'
        );
        
        if (array_key_exists($bean->primary_address_city, $cities)) {
            $bean->primary_address_city = $cities[$bean->primary_address_city];
        }


		$this->logger->log('debug', 'BeforeSaveLead fieldSanity completed');
	}

	public function utmFieldsUpdate($oLead) {
		//Check Opportunity is linked with current Lead
		if(!empty($oLead->opportunity_id)) {
			// array of Old and new values of UTM fields
			$fieldsOldNewVal = array( $oLead->fetched_row['campaignid_c'] => $oLead->campaignid_c,
								$oLead->fetched_row['campaign_source_c'] => $oLead->campaign_source_c,
								$oLead->fetched_row['campaign_medium_c'] => $oLead->campaign_medium_c,
								$oLead->fetched_row['campaign_name_c'] => $oLead->campaign_name_c,
								$oLead->fetched_row['campaign_term_c'] => $oLead->campaign_term_c,
								$oLead->fetched_row['campaign_content_c'] => $oLead->campaign_content_c);
			foreach($fieldsOldNewVal as $key => $value) {
				//if any of the field is updated 
				if($key !== $value) {
					$update = true; //set update true
					break;
				}
			}
			//when update has to be done
			if($update) {
				//Retrieve Bean of Opportunity
				$oOpportunity = BeanFactory::getBean("Opportunities",$oLead->opportunity_id);
				//array of fields 
				$aFields = array('campaignid_c','campaign_source_c','campaign_name_c','campaign_term_c','campaign_content_c','campaign_medium_c');
				foreach($aFields as $field) {
					//Setting the UTM fields from Leads to Opportnities
					$oOpportunity->$field = $oLead->$field;
				}
				$oOpportunity->save();
			}
		}
		$this->logger->log('debug', 'BeforeSaveLead utmFieldsUpdate completed');

	}

	public function validate_mobile($mobile) {
		return preg_match('/^[0-9]{10}+$/', $mobile);
	}


}
