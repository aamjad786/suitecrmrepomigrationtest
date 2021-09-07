<?php
$job_strings[] = 'TdsEscalationLevel';
date_default_timezone_set('Asia/Kolkata');
require_once('include/entryPoint.php');

function TdsEscalationLevel(){

    $myfile = fopen("Logs/CasesEscalationMail.log", "a");
    fwrite($myfile, "\n"."------------------function::CasesEscalationMail() Starts--------------");
    fwrite($myfile, "\n"."time - ".date('Y-m-d H:i:s'));
    $bean = BeanFactory::getBean('Cases');
    $query = "cases.deleted=0 and cases.state in ('Open','In_progress') and cases_cstm.case_subcategory_c='financial_live_tds_refund'";
    fwrite($myfile, "\n"."Query :: " . $query);
    $items = $bean->get_full_list('',$query);
    if ($items){
        foreach($items as $item){
            $escalation_type=0;
            $creationday=date('d',strtotime($item->date_entered));
            $today=date('d');
            if($item->escalation_level_c==3)
            {
                continue;
            }
            if($creationday>=8 && $creationday<=23)
            {
                $escalation_type=1;
            }
            else
            {
                $escalation_type=2;
            }
            if($escalation_type==1)
            {
                if ($today==28 || $today==29)
                {
                    $item->escalation_level_c=1;
                }else if ($today==30 || $today==31){
                    $item->escalation_level_c=2;
                }
                else if($today==1){
                    $item->escalation_level_c=3;
                }
            }
            else{
                if ($today==13 || $today==14)
                {
                    $item->escalation_level_c=1;
                }else if ($today==15 || $today==16){
                    $item->escalation_level_c=2;
                }
                else if($today==17){
                    $item->escalation_level_c=3;
                }
            }
            $item->save();
        }
    }
    
}