<?php

$environment = getenv('SCRM_SITE_URL');
global $db;
if (!defined('sugarEntry'))
    define('sugarEntry', true);

require_once('include/entryPoint.php');
require_once('custom/modules/InboundEmail/AOPInboundEmail.php');
    $emailBean = BeanFactory::getBean('Emails');
    
    $startingDate = "2003-05-03 00:00:00";
    $endDate = "2019-05-10 00:00:00";
    $emailsList = $emailBean->get_full_list("", "emails.date_entered > '$startingDate' and emails.date_entered < '$endDate' and intent = 'createcase' and (parent_type = '' or parent_type IS NULL) and (parent_id = '' or parent_id IS NULL) and type = 'inbound'");
    foreach($emailsList as $instanceEmail){            
            $userId = NULL;
            $emailBean = BeanFactory::getBean('Emails');
            $instanceEmail->retrieveEmailAddresses();
            $aopInboundEmailObject = new AOPInboundEmail();
            $aopInboundEmailObject->groupfolder_id = "9577534a-4f92-d2a3-aa45-59759d13c2ff";
            $aopInboundEmailObject->group_id = "95734701-afce-190d-d1be-59759d14798d";
            $aopInboundEmailObject->mailbox_type = "createcase";
            $createCase = $aopInboundEmailObject->handleCreateCase($instanceEmail, $userId);
            echo "1";
            exit;
            $emailBean->id =  $instanceEmail->id;
            $emailBean->flagged = 1;
            $emailBean->save();
        }
?>