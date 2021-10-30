<?php 

    $job_strings[]='InboundEmailCreateCase';

    function InboundEmailCreateCase() {
        require_once('custom/modules/InboundEmail/AOPInboundEmail.php');
        
        $emailBean = BeanFactory::getBean('Emails'); 
		$userId = NULL;
        $myfile = fopen("Logs/case_creation_schedular.log", 'a');
		
		$previousDayDate = date('Y-m-d', strtotime('-1 day'));

        $emailsList = $emailBean->get_full_list("", "emails.name not like '%Undeliver%' and emails.date_entered > '$previousDayDate' and intent = 'createcase' and (parent_type = '' or parent_type IS NULL) and (parent_id = '' or parent_id IS NULL) and type = 'inbound'");

        fwrite($myfile, "\n" .COUNT($emailsList). "\n");
        fwrite($myfile, "\n"."$emailsList". "\n");
		if (empty($emailsList)) 
			return true;
        foreach($emailsList as $instanceEmail){     

            $emailBean = BeanFactory::getBean('Emails');
            $instanceEmail->retrieveEmailAddresses();
            $aopInboundEmailObject = new AOPInboundEmail();
            $aopInboundEmailObject->groupfolder_id = "9577534a-4f92-d2a3-aa45-59759d13c2ff";
            $aopInboundEmailObject->group_id = "95734701-afce-190d-d1be-59759d14798d";
            $aopInboundEmailObject->mailbox_type = "createcase";
            fwrite($myfile, "\n"."$instanceEmail->id". "\n");
            fwrite($myfile, "\n"."$instanceEmail->name". "\n");
            echo '<br/>email [ ' . $instanceEmail->id . ' ' . $instanceEmail->name . ' ]userId [ ' . $userId . ' ]';
            $GLOBALS['log']->debug('email [ ' . $instanceEmail->id . ' ' . $instanceEmail->name . ' ]userId [ ' . $userId . ' ]');
            
            $createCase = $aopInboundEmailObject->handleCreateCase($instanceEmail, $userId);
            fwrite($myfile, "\n"."Cases is created");
            $emailBean->id =  $instanceEmail->id;
            $emailBean->flagged = 1;
            $emailBean->save();

        }
    
		

        return true;

    }
?>