<?php
if (!defined('sugarEntry') || !sugarEntry) {
	define('sugarEntry', true);
}
require_once("include/SugarPHPMailer.php");
require_once('modules/Emails/Email.php');
require_once('include/entryPoint.php');
require_once 'custom/CustomLogger/CustomLogger.php';

class SendEmail
{
	public function send_email_to_user($sub, $body, $emailTo = array(), $emailCc = array(), $relatedBean = null, $replyTo = array(), $disable_print = 0) {
		
		$logger = new CustomLogger('OutgoingMails');
		$logger->log('debug', '<=============== OutgoingMails Details =================>');

		$logger->log('debug', 'Subject: '.$sub);
		$logger->log('debug', 'Body: '.$body);
		$logger->log('debug', 'To: '.print_r($emailTo,true));
		$logger->log('debug', 'CC: '.print_r($emailCc,true));
		
		try {
			//Message Details
			$env = getenv('SCRM_ENVIRONMENT');
			if ($env != "prod") {
				$emailTo = array(getenv('SCRM_TEST_EMAIL'));
				$emailCc = array(getenv('SCRM_TEST_EMAIL_CC'));
			
				$logger->log('debug', 'Added Recipient From Test Environment ');
			}
			$logger->log('debug', 'To: '.print_r($emailTo,true));
			$logger->log('debug', 'CC: '.print_r($emailCc,true));

			$emailObj = new Email();
			$mail = new SugarPHPMailer();

			$defaults = $emailObj->getSystemDefaultEmail();

			$mail->setMailerForSystem();
			$mail->IsHTML(true);
			$mail->From = $defaults['email'];
			$mail->FromName = $defaults['name'];
			$mail->Subject = $sub;
			$mail->Body = $body;
			$mail->prepForOutbound();

			$success = true;
			$mail->ClearAddresses();

			if (empty($emailTo)) return false;

			foreach ($emailTo as $to) {
				if (!empty($to))
					$mail->AddAddress($to);
			}

			if (!empty($emailCc)) {
				foreach ($emailCc as $email) {
					if (!empty($email))
						$mail->AddCC($email);
				}
			}

			if (!empty($replyTo)) {
				foreach ($replyTo as $replyToemail) {
					if (!empty($replyToemail))
						$mail->addReplyTo($replyToemail, 'Neogrowth');
				}
			}


			
			$success = $mail->Send() && $success;
			
			if ($success) {

				if (empty($disable_print)) {

					$logger->log('debug', "<=============== Successfully sent email =================>");
				}
				
				// Saving Send email in email module
				$emailObj->to_addrs = implode(',', $emailTo);
				$emailObj->cc_addrs = implode(',', $emailCc);
				$emailObj->type = 'out';
				$emailObj->deleted = '0';
				$emailObj->name = $mail->Subject;
				$emailObj->description = $mail->AltBody;
				$emailObj->description_html = $mail->Body;
				$emailObj->from_addr = $mail->From;
				if ($relatedBean instanceof SugarBean && !empty($relatedBean->id)) {
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
				
				$logger->log('fatal', "Notifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
				$GLOBALS['log']->FATAL("Notifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
				return false;
			}
		} catch (Exception $e) {
			$logger->log('fatal', "<=============== Error While Sending Mail! =================>");
			return false;
		}
	}
}
