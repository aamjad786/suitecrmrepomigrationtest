<?php
require_once 'custom/CustomLogger/CustomLogger.php';

$job_strings[] = 'AssignCaseType';
date_default_timezone_set('Asia/Kolkata');

function AssignCaseType(){
    $logger = new CustomLogger('AssignCaseType');
    $logger->log('debug', "--- START In AssignCaseType in ScheduledTasks---");

    global $db, $sugar_config;

    $details = $sugar_config['case_types'];
   
    $bean = BeanFactory::getBean('Cases');
    $one_month_before_date = date('Y-m-d', strtotime('-1 months'));
    $query = "cases.deleted=0 and cases.state in ('Open','In_progress') and cases.date_entered>='$one_month_before_date'";

    $items = $bean->get_full_list('',$query);

    if ($items){

        foreach($items as $item){
           
            $subcat = $item->case_subcategory_c;
            $index = getdetail($details, $subcat);
            $detail = $details[$index];
            $type = $detail['qrc'];
            $action_code = $detail['ftr'];

            if(isset($item->type) && $item->type != $type) {
                $query = "update cases set type = '$type' where id='$item->id'";
                $results = $db->query($query);
                $logger->log('debug', "[$item->type] changed to [$type] for [$item->id]");
            }
            if(isset($item->case_action_code_c) && $item->case_action_code_c != $action_code) {
                $query="update cases_cstm set case_action_code_c = '$action_code' where id_c='$item->id'";
                $results = $db->query($query);
                $logger->log('debug', "[$item->case_action_code_c] changed to [$action_code] for [$item->id]");
            }
        }
    }
    $logger->log('debug', "--- END AssignCaseType in ScheduledTasks---");
    return true;
}


function getdetail($a, $subcat){
    foreach($a as $key => $i){
        if(array_search($subcat, $i)){
            return $key;
        }
    }
}