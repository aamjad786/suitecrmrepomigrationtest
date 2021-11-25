<?php
require_once 'custom/CustomLogger/CustomLogger.php';
$job_strings[] = 'CaseClosedTatStatusUpdate';
date_default_timezone_set('Asia/Kolkata');

function CaseClosedTatStatusUpdate(){

    global $db;
    $logger = new CustomLogger('CaseClosedTatStatusUpdate');
    $logger->log('debug', "--- START In CaseClosedTatStatusUpdate in ScheduledTasks---");
    $bean = BeanFactory::getBean('Cases');

    $two_month_before_date = date('Y-m-d', strtotime('-2 months'));
    $logger->log('debug', "----TWO-----" .$two_month_before_date);
    $query = "cases.deleted=0 and cases.date_entered>='$two_month_before_date'";

    $items = $bean->get_full_list('',$query);
    $logger->log('debug', "---tat in days items---" .print_r($items ));
    if ($items){

        foreach($items as $item){
           

            $subcat = $item->case_subcategory_c;
            $logger->log('debug', "---cat---". $cat);
            $tatInDays = CaseTatInDays($item->case_category_c, $subcat);
            $logger->log('debug', "---tat in days---" .$tatInDays);

            if(!empty($tatInDays)){
                $item->tat_in_days_c = $tatInDays;

                if($item->age_c <= $tatInDays){
                    $status = "within_tat";
                } 
                else {
                    $status = "beyond_tat";
                }

                $query = "update cases_cstm set tat_status_c = '$status',tat_in_days_c='$tatInDays' where id_c='$item->id'";
                $results = $db->query($query);
            }
        }
    }
  
    return true;
}

function CaseTatInDays($category, $subCategoty){

    $tatInDays = 0;
    $logger = new CustomLogger('CaseClosedTatStatusUpdate');
    if(!empty($category) && !empty($subCategoty)){

        global $db;

        $getTatInDays = $db->query("SELECT defined_tat_in_days from scrm_cases where issue_type = '$category' and sub_issue_type = '$subCategoty'");
        $logger->log('debug', "---defined tat in days---" .print_r($tatInDays));
        while($data = $db->fetchByAssoc($getTatInDays)){

            $tatInDays = $data['defined_tat_in_days'];
            $logger->log('debug', "---defined tat in days---" .$tatInDays);
        }
    }
    return $tatInDays;    
}