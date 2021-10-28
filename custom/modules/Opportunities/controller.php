<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class OpportunitiesController extends SugarController {
    
   
    function action_Spoc_mapping_marketing() {
       $this->view = 'spoc_mapping_marketing';
    }
    function action_Spoc_mapping_alliance() {
       $this->view = 'spoc_mapping_alliance';
    }

}