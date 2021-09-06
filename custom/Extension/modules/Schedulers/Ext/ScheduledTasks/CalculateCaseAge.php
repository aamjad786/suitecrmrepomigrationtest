<?php
$job_strings[] = 'CalculateCaseAge';
date_default_timezone_set('Asia/Kolkata');


function CalculateCaseAge(){
  $myfile = fopen("Logs/CasesEscalationMail.log", "a");
  $bean = BeanFactory::getBean('Cases');
  $query = "cases.deleted=0 and cases.state in ('Open','In_progress')";
  $items = $bean->get_full_list('',$query);
  $count=0;
  if ($items){
      foreach($items as $item){
          // Calculating Case Age
          if($item->date_entered < '2019-02-26'){
            $time = getWeekdays2($item->date_entered);
          }else{
            $time = getWeekdays($item->date_entered);
          }
         
          
          $item->age_c = $time[0];
          // Calculating Case Escalation Level
          $time_spent  = $time[0]*24+$time[1];

          $cat = $item->case_subcategory_c;
          //$defined_hours = 48;


        $level = escalationLevel($time[0],$cat);
          
          
          //Sending interim response
          if ($item->escalation_level_c != $level) {
              $item->escalation_level_c = $level;
              sendIterimResponse($item);
          }
          
          fwrite($myfile, "\n"."Escalation Level - " . $level);
          $item->save();
          
      }
  }

  return true;
}

function getWeekdays($dt1, $dt2=""){
    $dt1 = date('Y-m-d H:i:s', strtotime($dt1 . ' +1 day'));
    $start = new Datetime($dt1);
    $end = new Datetime();
    $end->modify('+1 day');
    $interval = $end->diff($start);
    $s_h = 24-(int)$start->format('H');
    $e_h =  (int)$end->format('H');
    $days = $interval->days;
    $hours = 0;//$s_h+$e_h;
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);

    // best stored as array, so you can add more than one
    $holidays = array('2021-01-01','2021-01-14','2021-01-26','2021-03-11','2021-03-29','2021-04-13','2021-04-21','2021-05-13','2021-09-10','2021-10-14','2021-10-15','2021-11-01','2021-11-04','2021-11-05','2021-12-25');

    // echo $days." days<br>";
    foreach($period as $dt) {
        $curr = $dt->format('D');
        // substract if Saturday or Sunday
        if ($curr == 'Sun') {
            $days--;
        }
        // (optional) for the updated question
        elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days--;
        }
    }
   
    if($days>0){
       // $days -=1;
      } else if($days <0){
          $days = 0;
    }
    
    return array($days,$hours);
}
function getWeekdays2($dt1, $dt2=""){
    $dt1 = date('Y-m-d H:i:s', strtotime($dt1 . ' +1 day'));
    $start = new Datetime($dt1);
    $end = new Datetime();
    $end->modify('+1 day');
    $interval = $end->diff($start);
    $s_h = 24-(int)$start->format('H');
    $e_h =  (int)$end->format('H');
    $days = $interval->days;
    $hours = 0;//$s_h+$e_h;
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);
    // best stored as array, so you can add more than one
    //$holidays = array();//array('2012-09-07');
    $holidays = array('2021-01-01','2021-01-14','2021-01-26','2021-03-11','2021-03-29','2021-04-13','2021-04-21','2021-05-13','2021-09-10','2021-10-14','2021-10-15','2021-11-01','2021-11-04','2021-11-05','2021-12-25');
    // echo $days." days<br>";
    foreach($period as $dt) {
        $curr = $dt->format('D');
        // substract if Saturday or Sunday
        if ($curr == 'Sun'||$curr=='Sat') {
            $days--;
        }
        // (optional) for the updated question
        elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days--;
        }
    }
    if($days>0){
      $days -=1;
    } else if($days <0){
        $days = 0;
    }
    
    // echo $days."<Br>";
    return array($days,$hours);
}
function escalationLevel($ah,$cat)
{
    global $timedate;
    $myfile = fopen("Logs/CasesEscalationMail.log", "a");
    fwrite($myfile, "\n"."-----------------CalculateCaseAge::escalationLevel starts------------ ");
    fwrite($myfile, "\n"."time - ".$timedate->now());
    fwrite($myfile, "\n"."sub_issue_type - ".$cat);
    $bean  = BeanFactory::getBean("scrm_Cases");
    $query = "scrm_cases.deleted=0 and scrm_cases.sub_issue_type = '$cat'";
    fwrite($myfile, "\n"."query - ".$query);
    $items = $bean->get_full_list('',$query);
    $l1 = 0;
    $l2 = 0;
    $l3 = 0;
    if(!empty($items)){
      $item = $items[0];
      $l1 = $item->tat_1;
      $l2 = $item->tat_2;
      $l3 = $item->tat_3;
    }
    else{
      fwrite($myfile, "\n"."No Escalation Details found for this sub category :: " . $cat);
      fwrite($myfile, "\n"."Using Default values");
    }
    $l1 = (empty($l1)?2:$l1);
    $l2 = (empty($l2)?3:($l2));
    $l3 = (empty($l3)?4:($l3));
    fwrite($myfile, "\n"."Days :: l1 - $l1, l2 - $l2, l3 - $l3");
    fwrite($myfile, "\n"."Hours :: l1 - $l1, l2 - $l2, l3 - $l3");
    fwrite($myfile, "\n"."hours taken by the case till now : $ah");
    fwrite($myfile, "\n"."-----------------CalculateCaseAge::escalationLevel ends------------ ");
    $l4 = 30;    // 30 days
    if ($ah>=$l4)    //4th level escalation MD
        return 4;
    else if ($ah>=$l3)   //3rd level escalation
        return 3;
    else if ($ah>=$l2)   //2nd level escalation
        return 2;
    else if ($ah>=$l1)   //1st level escalation
        return 1;
    else    //Do nothing
        return 0;
}

function escalationLevelForTDS($bean) {
    
    $myfile = fopen("Logs/TDSEscalation.log", "a");
    fwrite($myfile, "\n\n--------escalationLevelForTDS -----------");
    fwrite($myfile, "\n *********** ".date('Y-m-d H:i:s')." ***********\n");
    fwrite($myfile, "\n\n Case ID ---> ". $bean->id);
    fwrite($myfile, "\n\n Case application ID ---> ". $bean->merchant_app_id_c);    
    fwrite($myfile, "\n\n Initial Escalation level for this case ---> ". $bean->escalation_level_c);
    if ($bean->escalation_level_c == 3) {
        return 3;
    }
    $escalationType = 0;
    $escalationLevel = 0;
    $caseCreated = $bean->date_entered;
    
    $caseCreatedDate = date('d',strtotime($bean->date_entered));

    $day = substr($caseCreated, 8, 2);

    $todaysDate = date("d");

   // $currentDate = date("d");
   $currentDate = date("d",strtotime($bean->test_current_date_c));

    if ((23 <= $caseCreatedDate) || ($caseCreatedDate <= 7)) {

        $escalationType = 1;

    } else if(($caseCreatedDate <= 22) && ($caseCreatedDate >= 8)){

        $escalationType = 2;

    }
    else if(($day <= 22) && ($day >= 8)){
        $escalationType = 2;
    }
    fwrite($myfile, "\n\n created $day  ---> ". $day);
    fwrite($myfile, "\n\n Escalation Type ---> ". $escalationType);
    $todaysDate = date("d");

    
    if($escalationType==1){

        if($currentDate== 13){

            $escalationLevel = 1;

        } else if($currentDate == 15){

            $escalationLevel = 2;

        } else if($currentDate == 17){

            $escalationLevel = 3;

        }
    } else if($escalationType==2){

        if($currentDate == 28){

            $escalationLevel = 1;

        } else if($currentDate == 30){

            $escalationLevel = 2;

        } else if($currentDate == 1){

            $escalationLevel = 3;

        }
    }
    
    return (!empty($escalationLevel)?$escalationLevel:$bean->escalation_level_c);
}

function sendIterimResponse($item) {
    if (!empty($item)) {
        require_once('include/entryPoint.php');
        require_once('custom/include/SendEmail.php');
        require_once 'custom/include/SendSMS.php';
        $env = getenv('SCRM_ENVIRONMENT');
        $merchantEmailId = $item->merchant_email_id_c;
        if (!empty($merchantEmailId)) {
            $ticket = $item->case_number;
            $body = getInterimResponseEmailContent($ticket);
            $emailId = $merchantEmailId;
            $subject = "Update on your query [SR-#$ticket]";
            $to = array($emailId);
            $cc = array();
            $email = new SendEmail();
            $email->send_email_to_user($subject, $body, $to, $cc, $item);
        }
        //Send SMS to User
        if(!empty($item->merchant_contact_number_c)){
            $ticket = $item->case_number;
            $send = new SendSMS();
            $content="Dear NeoGrowth Customer, 
            Our records indicate that a decision on your query [SR-#$ticket] is still pending. We are following-up on this with the respective department and will certainly contact you once a resolution is received. For any further assistance, please do not hesitate to email us on helpdesk@neogrowth.in or call us on 1800-419-5565 between 10 A.M - 6 P.M from Monday to Saturday.";
            $send->send_sms_to_user($tag_name="Cust_CRM_17", $item->merchant_contact_number_c,$content,$item);
        }
    
    }
}
function getInterimResponseEmailContent($ticket) {

    $body = "<pre>
    Dear NeoGrowth Customer, </br></br>"
    ."Greetings from NeoGrowth! Our records indicate that a decision on your query [SR-#$ticket] is still pending."
    ."Please be rest assured that we are following-up on this with the respective department and will certainly contact you once a resolution is received. </br></br>"
    ."We appreciate your patience and apologise for any inconvenience.</br></br>"
    ."For any further assistance, please do not hesitate to email us on helpdesk@neogrowth.in or call us on 1800-419-5565 between 10 A.M - 6 P.M from Monday to Saturday.</br></br>"
    ."We are always here to assist you.</br></br>"
    ."Thank you for choosing NeoGrowth.</br></br>
    </pre>";
//     $body = "<pre>
// Dear NeoGrowth Customer, </br></br> 
//   Greetings from NeoGrowth!</br></br>"
// . "Our records indicate that a decision on your query [SR-#$ticket] is still pending. "
// . "Please be rest assured that we are following-up on this with the respective department and will certainly contact you once a resolution is received.</br></br>"
// . "We appreciate your patience and apologise for any inconvenience.</br></br>"
// . "For any further assistance, please do not hesitate to email us on helpdesk@neogrowth.in or call us on 1800-419-5565 between 10 A.M - 6 P.M from Monday to Friday.</br></br>"
// . "We are always here to assist you.</br></br>"
// . "Thank you for choosing NeoGrowth.</br></br>
//     </pre>";
    return $body;
}
