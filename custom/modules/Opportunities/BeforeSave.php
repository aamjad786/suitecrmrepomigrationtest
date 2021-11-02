<?php
if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
require_once 'custom/CustomLogger/CustomLogger.php';
class BeforeSaveOpportunity {

    function __construct() {
		$this->logger =new CustomLogger('BeforeSaveOpportunity');
	}

    public function store_assigned(&$bean, $event, $args)
    {
        $bean->stored_fetched_row_c = $bean->fetched_row;
        $cities = array(
            'BENGALURU' => 'BANGALORE',
            'BHUBANESWAR' => 'BHUBANESHWAR',
            'VADODARA' => 'BARODA',
            'VIJAYAWADA' => 'VIJAYWADA',
            'VISAKHAPATNAM' => 'VIZAG'
        );
        
        if (array_key_exists($bean->pickup_appointment_city_c, $cities)) {
            $bean->pickup_appointment_city_c = $cities[$bean->pickup_appointment_city_c];
        }
        $bean->pickup_appointment_city_c = strtoupper($bean->pickup_appointment_city_c);
        
        $this->logger->log('debug', '<============ Store_Assigned Logic hook Executed ===========>');
    }

    // * Purpose : To convert the Insta Opportunity to Marketing opportunity and assign to sales on receiving disposition of 'Aadhar Not Linked to phone number from EOS
    // * Ticket : CSI-1147

    public function convertInstaOpp(&$bean, $event, $args) {
            
        $this->logger->log('debug', '<============ convertInstaOpp Logic hook Started ===========>');

            //When EOS Disposition , Sub EOS Disposition and Opportunity Status belongs to below value	
            if(($bean->eos_disposition == 'aadhar_not_linked_with_phno') && ($bean->eos_sub_disposition == 'aadhar_not_linked_with_phno_eligible') && ($oOpp->opportunity_status_c == 'appointment_done_cam_to_visit_customer')) {
                
                //Removing(Emptying) fields which belongs to Insta Opp
                $bean->control_program = "";
                $bean->app_form_link = "";

                //Set the Lead Source to Marketing 
                $bean->lead_source = 'Marketing';
                
                $this->logger->log('debug', 'Converted To Marketing Opportunity------>!');
        }
        $this->logger->log('debug', '<============ convertInstaOpp Logic hook executed ===========>');
    }

    // If app id is linked to an opportunity, the opp status should read as ''Logged In'
    // CSI-1172 
    
    public function changeOppStatus($bean) {

         //When Application ID is added in Opportunity
        if((empty($bean->fetched_row['application_id_c'])) && (!empty($bean->application_id_c))) {
            
            $bean->opportunity_status_c='logged_in';
        }
        $this->logger->log('debug', '<============ changeOppStatus Logic hook executed ===========>');
    }

    // CSI-1131
    public function opportunitiesStatusUpdate(&$bean, $event, $args){
      
        $opp_status = $bean->alliance_opp_status_c;
        $status = $bean->opportunity_status_c;

        $alliance_opp_status = $this->first_val_if_present($_REQUEST['alliance_opp_status_c'],$opp_status);

        $o_status=$this->first_val_if_present($_REQUEST['opportunity_status'],$status);
        
        if(!empty($alliance_opp_status) && $bean->stored_fetched_row_c["alliance_opp_status_c"]!=$alliance_opp_status && (empty($o_status) || $bean->stored_fetched_row_c['opportunity_status_c'] !=$alliance_opp_status)) {

            $bean->opportunity_status_c = $alliance_opp_status;
        }
        
        $this->logger->log('debug', '<============ opportunitiesStatusUpdate Logic hook executed ===========>');
        
    }

    /**
     * CSI -1153 
     * Opportunity sales stage sould be rejected when this two opportunity status receive from other system.rejected_incorrect_doc and appointment_not_done_customer_ineligible
     */
    public function updateSalesStage(&$bean, $event, $args){
        
        $opp_status = $bean->opportunity_status_c;

        $opp_status = $this->first_val_if_present($_REQUEST['opportunity_status'],$opp_status);
      
        if(!empty($opp_status) && ($opp_status =='appointment_not_done_customer_ineligible' || $opp_status=='rejected_incorrect_doc')){

            $bean->sales_stage ='Rejected';

        }
       
    }

    function first_val_if_present($a, $b) {
		return ((!empty($a)) ? ($a) : ($b));
	}
}
