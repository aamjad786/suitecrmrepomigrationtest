<?php 

    require_once 'custom/CustomLogger/CustomLogger.php';
    $job_strings[]='source_email_update';

    function source_email_update() {
        $logger = new CustomLogger('EmailSourceUpdate');
		
        /**Get the email to address */
        global $db;

        $emailSource = 'select parent_id,to_addrs from emails  LEFT JOIN emails_text ON  emails.id = emails_text.email_id where date(date_entered)>="2021-07-25" and status !="sent" and parent_type="cases"  order by date_entered desc';

        $eSource = $db->query($emailSource, true);

        $to_addrs = $db->fetchByAssoc($emailSource);

        //$toAddress=$e['to_addrs'];

        $logger->log('debug', "***Email tables ***".$eSource);
        while($toAddress = $db->fetchByAssoc($eSource)){ 

            if(!empty($toAddress['parent_id'])){
                
                preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $toAddress['to_addrs'], $matches);
                
                $a = $matches[0];
                
                $update_case = "update cases_cstm set email_source_c = '".strip_tags($a[0])."' where id_c = '".$toAddress['parent_id']."'";
    
                $logger->log('debug', "***Email tables ***".$update_case);

                $db->query($update_case);
            }
        }

    }
?>