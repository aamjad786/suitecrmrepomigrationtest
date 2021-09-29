<?php

if (!defined('sugarEntry')) define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once 'custom/CustomLogger/CustomLogger.php';
class AfterSaveLead
{
	function __construct() {
		$this->logger =new CustomLogger('AfterSaveLead');
	}

	public function autoConvertionOfLead(&$bean, $event, $args) {

		$positiveEosOppStatusArray=array('Appointment fixed','appointment_done_cam_visit_customer','appointment_done_picked_up_documents','appointment_done_followup','appointment_done_will_get_documents_later');
		
		$notDeletedAndNew = ($bean->deleted == 0 and empty($bean->opportunity_id));

		$sourceOrDsaMatched = (trim($bean->lead_source) == 'Self Generated' || trim($bean->dsa_code_c) == 'Nine Group');
		$positiveStatusFromEOS = in_array(trim($bean->eos_opportunity_status_c), $positiveEosOppStatusArray);

		$this->logger->log('debug', 'Auto Convertion Conditions=======>');
		$this->logger->log('debug', 'notDeletedAndNew: '.$notDeletedAndNew);
		$this->logger->log('debug', 'sourceOrDsaMatched: '.$sourceOrDsaMatched);
		$this->logger->log('debug', 'positiveStatusFromEOS: '.$positiveStatusFromEOS);

		if (($notDeletedAndNew and $sourceOrDsaMatched) or ($notDeletedAndNew and $positiveStatusFromEOS)) {

			$this->logger->log('debug', 'Auto Converting Lead For Mobile No: '.$bean->phone_mobile);
			$this->logger->log('debug', 'Lead ID: '.$bean->id);

			$opportunityBean = $this->convertToOpportunity($bean);
			
			$accountBean=$this->createAccountForOpp($bean);

			$contactBean=$this->createContactForOpp($bean);

			// Creating Relationship

			global $db;
			$accountID = $accountBean->id;
			$contactID = $contactBean->id;

			$query = "update leads set status = 'Verified' , account_id = '$accountID' , contact_id = '$contactID' , opportunity_id = '$opportunityBean->id' where id = '$bean->id' and deleted = 0";
			$db->query($query);

			$acGUID = create_guid();
			$aoGUID = create_guid();

			$accountContact = "insert into accounts_contacts(id,contact_id,account_id,date_modified,deleted) values ('$acGUID','$contactID','$accountID',NOW(),'0')";
			$accountOppo = "insert into accounts_opportunities(id,opportunity_id,account_id,date_modified,deleted) values ('$aoGUID','$opportunityBean->id','$accountID',NOW(),'0')";

			$db->query($accountContact);
			$db->query($accountOppo);

		}else{
			$this->logger->log('debug', 'Skipping Lead AutoConvertion: '.$bean->phone_mobile);
		}

	}

	public function convertToOpportunity($bean) {

		$phone_mobile = $bean->phone_mobile;
		$loan_amount_c = isset($bean->loan_amount_c) ? $bean->loan_amount_c : "";
		$postalcode = $bean->primary_address_postalcode;
		$city = $bean->primary_address_city;
		$street = $bean->primary_address_street;
		$digital_c = strtolower($bean->digital_c);
		$pickup_appointment_date_time_c = isset($_REQUEST['pickup_appointment_date_time_c'])?$_REQUEST['pickup_appointment_date_time_c']:"" ;

		// Creating Bean and Setting Values
		$opportunity_bean = BeanFactory::newBean('Opportunities');

		$opportunity_bean->sales_stage = "Open";
		$opportunity_bean->name = $bean->salutation . " " . $bean->first_name . " " . $bean->last_name;
		$opportunity_bean->email1 = $bean->email1;
		$opportunity_bean->description = $bean->description;
		$opportunity_bean->merchant_name_c = $bean->merchant_name_c;
		$opportunity_bean->acspm_c = $bean->acspm_c;
		$opportunity_bean->dsa_code_c = $bean->dsa_code_c;
		$opportunity_bean->sub_source_c = $bean->sub_source_c;
		$opportunity_bean->lead_source = $bean->lead_source;
		$opportunity_bean->scheme_c = $bean->scheme_c;
		$opportunity_bean->Alliance_Lead_Docs_shared_c = $bean->Alliance_Lead_Docs_shared_c;
		$opportunity_bean->referral_agent_id_c = $bean->refered_by;
		$opportunity_bean->original_app_id_c = $bean->original_app_id_c;
		$opportunity_bean->is_renewal_c = $bean->is_renewal_c;
		$opportunity_bean->product_type_c = $bean->product_type_c;
		$opportunity_bean->seller_id_online_platform_c = $bean->seller_id_online_platform_c;
		$opportunity_bean->seller_customer_rating_online_platform_c = $bean->seller_customer_rating_online_platform_c;
		$opportunity_bean->seller_partner_rating_online_platform_c = $bean->seller_partner_rating_online_platform_c;
		$opportunity_bean->business_age_in_months_c = $bean->business_age_in_months_c;
		$opportunity_bean->settlement_cycle_in_days_c = $bean->settlement_cycle_in_days_c;
		$opportunity_bean->partner_id_c = $bean->partner_id_c;
		$opportunity_bean->sales_3_month_c = $bean->sales_3_month_c;
		$opportunity_bean->industry_type_c = $bean->industry_type_c;
		$opportunity_bean->source_type_c = $bean->source_type_c;
		$opportunity_bean->control_program_c = $bean->control_program_c;
		$opportunity_bean->app_form_link_c = $bean->app_form_link_c;
		$opportunity_bean->stage_drop_off_c = $bean->stage_drop_off_c;
		$opportunity_bean->dsa_id_c = $bean->dsa_id_c;

		$opportunity_bean->digital_c = $this->first_val_if_present(strtolower($_REQUEST['digital_c']), $digital_c);
		$opportunity_bean->loan_amount_c = $this->first_val_if_present(isset($_REQUEST['loan_amount_required_c']) ? $_REQUEST['loan_amount_required_c'] : "", $loan_amount_c);
		$opportunity_bean->pickup_appointment_contact_c = $this->first_val_if_present(isset($_REQUEST['pickup_contact_number_c']) ? $_REQUEST['pickup_contact_number_c'] : "", $phone_mobile);
		$opportunity_bean->pickup_appointment_address_c = $this->first_val_if_present(isset($_REQUEST['pickup_appointment_address_c']) ? $_REQUEST['pickup_appointment_address_c'] : "", $street);
		$opportunity_bean->pickup_appointment_pincode_c = $this->first_val_if_present(isset($_REQUEST['pickup_appointment_pincode_c']) ? $_REQUEST['pickup_appointment_pincode_c'] : "", $postalcode);
		$opportunity_bean->pickup_appointment_city_c = $this->first_val_if_present(isset($_REQUEST['pickup_appointment_city_c']) ? $_REQUEST['pickup_appointment_city_c'] : "", $city);

		
		// Formating date before insertion
		if (!empty($pickup_appointment_date_time_c)) {
			
			global $current_user;
			$datef = $current_user->getPreference('datef');
			$timef =  $current_user->getPreference('timef');
			$dateFormat = $datef . " " . $timef;
			$datetime = DateTime::createFromFormat("$dateFormat", $pickup_appointment_date_time_c)->format('Y-m-d H:i');
			$datetime = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($datetime)));
			$opportunity_bean->pickup_appointment_date_c = $datetime;
		}

		// User Assignment
		$opportunity_bean->assigned_user_id = $this->first_val_if_present($bean->user_id_c, $bean->assigned_user_id);
		$opportunity_bean->user_id_c = $bean->assigned_user_id;
		
		// EOS Fields
		$opportunity_bean->date_updated_by_eos_c=$bean->date_updated_by_eos_c;
		$opportunity_bean->eos_disposition_c=$bean->eos_disposition_c;
		$opportunity_bean->eos_sub_disposition_c=$bean->eos_sub_disposition_c;
		$opportunity_bean->eos_opportunity_status_c=$bean->eos_opportunity_status_c;
		$opportunity_bean->eos_sub_status_c=$bean->eos_sub_status_c;
		$opportunity_bean->eos_remark_c=$bean->eos_remark_c;
	
		$opportunity_bean->save();

		return $opportunity_bean;
	}

	public function createContactForOpp($bean){
		
		$contact_bean = BeanFactory::newBean('Contacts');

		$contact_bean->first_name = $bean->first_name;
		$contact_bean->last_name = $bean->last_name;
		$contact_bean->email1 = $bean->email1;
		$contact_bean->phone_mobile = $bean->phone_mobile;
		$contact_bean->phone_work = $bean->phone_work;
		$contact_bean->description = $bean->description;
		$contact_bean->primary_address_street = $bean->primary_address_street;
		$contact_bean->primary_address_city = $bean->primary_address_city;
		$contact_bean->primary_address_postalcode = $bean->primary_address_postalcode;

		$contact_bean->save();
		
		return $contact_bean;
	}

	public function createAccountForOpp($bean){
		
		$account_bean = BeanFactory::newBean('Accounts');

		$account_bean->name = $bean->merchant_name_c;
		$account_bean->email1 = $bean->email1;
		$account_bean->phone_mobile = $bean->phone_mobile;
		$account_bean->phone_work = $bean->phone_work;
		$account_bean->description = $bean->description;
		$account_bean->billing_address_street = $bean->primary_address_street;
		$account_bean->billing_address_city = $bean->primary_address_city;
		$account_bean->billing_address_postalcode = $bean->primary_address_postalcode;
		
		$account_bean->save();

		return $account_bean;
	}
	

	function first_val_if_present($a, $b) {
		return ((!empty($a)) ? ($a) : ($b));
	}
}
