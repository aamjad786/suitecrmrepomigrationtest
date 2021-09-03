<?php
if(!defined('sugarEntry') || !sugarEntry) {
	define('sugarEntry', true);
}
require_once('include/entryPoint.php');

class SendEmail {
	public function send_email_to_user($sub, $body, $emailTo=array(),$emailCc=array(),$relatedBean=null,$replyTo=array(),$disable_print=0){
		$myfile = fopen("Logs/SendEmail.log","a");
		fwrite($myfile,"Sub=$sub\n");
		fwrite($myfile,"Body=$body\n");
		
		try{
			//Message Details
			$env = getenv('SCRM_ENVIRONMENT');
			if($env != "prod"){
	        	$emailTo=array(getenv('SCRM_TEST_EMAIL'));
	        	$emailCc = array(getenv('SCRM_TEST_EMAIL2'));
	        	if(empty($emailTo))$emailTo = array('mayank.verma@neogrowth.in');
		    }
		    fwrite($myfile,"To=".print_r($emailTo,true)."\n");
			fwrite($myfile,"Cc=".print_r($emailCc,true)."\n");
			require_once("include/SugarPHPMailer.php");
			require_once('modules/Emails/Email.php');
			$emailObj = new Email();
		    $defaults = $emailObj->getSystemDefaultEmail();
		    $mail = new SugarPHPMailer();

		    $mail->setMailerForSystem();
		    $mail->IsHTML(true);
		    $mail->From = $defaults['email'];
		    $mail->FromName = $defaults['name'];
		    $mail->Subject = $sub;
		    $mail->Body = $body;
		    $mail->prepForOutbound();
		    $success = true;
		    $mail->ClearAddresses();

		    if(empty($emailTo)) return false;
	        foreach($emailTo as $to){
	        	if(!empty($to))
	            	$mail->AddAddress($to);
	        }
	        if(!empty($emailCc)){
	            foreach($emailCc as $email){
	            	if(!empty($email))
	                	$mail->AddCC($email);
	            }
	        }
	        if(!empty($replyTo)){
	            foreach($replyTo as $email){
	                $mail->addReplyTo($email,'Neogrowth');
	            }
	        }

        
		    // $mail->AddAddr	ess($to);
		    $success = $mail->Send() && $success;
		    if($success) {
		    	if(empty($disable_print)){
		    		// echo "\nSuccessfully sent email\n";
		    		fwrite($myfile,"\nSuccessfully sent email\n");
		    	}
		    	$emailObj->to_addrs= implode(',',$emailTo);
	            $emailObj->cc_addrs= implode(',',$emailCc);
	            // $emailObj->bcc_addrs= implode(',',$emailBcc);
	            $emailObj->type= 'out';
	            $emailObj->deleted = '0';
	            $emailObj->name = $mail->Subject;
	            $emailObj->description = $mail->AltBody;
	            $emailObj->description_html = $mail->Body;
	            $emailObj->from_addr = $mail->From;
	            if ( $relatedBean instanceOf SugarBean && !empty($relatedBean->id) ) {
	                $emailObj->parent_type = $relatedBean->module_dir;
	                $emailObj->parent_id = $relatedBean->id;
	            }
	            $emailObj->date_sent = TimeDate::getInstance()->nowDb();
	            $emailObj->modified_user_id = '1';
	            $emailObj->created_by = '1';
	            $emailObj->status = 'sent';
	            $emailObj->save();

	            return true;
		    } else {
		    	if(empty($disable_print)){
		    		// echo "\nSome problem occurred in sending mail\n";
		    		// echo "Notifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})";
		    	}
		    	fwrite($myfile,"\nNotifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})\n");
		    	$GLOBALS['log']->FATAL("Notifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
		    	return false;
		    }
		}
		catch(Exception $e){
			if(empty($disable_print)){
				// echo '\nCaught exception while sending sms : ',  $e->getMessage(), "\n";
			}
			return false;
		}
	}

}
// $sub = "test";
// $body = "test body"
// $to = "nikhil.kumar@neogrowth.in"
// $email = new SendEmail();
// $email->send_email_to_user($sub, $body,array($to));
