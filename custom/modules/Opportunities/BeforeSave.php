<?php
if (!defined('sugarEntry'))
    define('sugarEntry', true);
require_once('data/BeanFactory.php');
require_once('include/entryPoint.php');
class BeforeSaveOpportunity {

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
    }
}
