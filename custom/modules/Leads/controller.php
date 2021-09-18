<?php
require_once('modules/Leads/controller.php');
class CustomLeadsController extends LeadsController {     
    
    function action_Month_sales_report(){
      $this->view = 'month_sales_report'; 
    }
    function action_visits_dashboard(){
    	$this->view = 'visits_dashboard'; 
    }
    function action_sales_dashboard(){
    	$this->view = 'sales_dashboard'; 
    }
   
}
