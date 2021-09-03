<?php
require_once('modules/SMS_SMS/firesms.php'); 
$job_strings[] = 'quick2sms_received_cron';

function SMS_validate_phone_number_cron ( $phone ) {
	/*********************************************************************/
	/*   Purpose:   To determine if the passed string is a valid phone   */
	/*              number following one of the establish formatting     */
	/*              styles for phone numbers.  This function also breaks */
	/*              a valid number into it's respective components of:   */
	/*              3-digit area code,                                   */
	/*              3-digit exchange code,                               */
	/*              4-digit subscriber number                            */
	/*              and validates the number against 10 digit US NANPA   */
	/*              guidelines.                                          */
	/*********************************************************************/         
	$format_pattern =   '/^(?:(?:\((?=\d{3}\)))?(\d{3})(?:(?<=\(\d{3})\))'.
								'?[\s.\/-]?)?(\d{3})[\s\.\/-]?(\d{4})\s?(?:(?:(?:'.
								'(?:e|x|ex|ext)\.?\:?|extension\:?)\s?)(?=\d+)'.
								'(\d+))?$/';
	$nanpa_pattern      =   '/^(?:1)?(?(?!(37|96))[2-9][0-8][0-9](?<!(11)))?'.
								'[2-9][0-9]{2}(?<!(11))[0-9]{4}(?<!(555(01([0-9]'.
								'[0-9])|1212)))$/';

	// Init array of variables to false
	$valid = array('format' =>  false,
						'nanpa' => false,
						'ext'       => false,
						'all'       => false);

	//Check data against the format analyzer
	if ( preg_match ( $format_pattern, $phone, $matchset ) ) {
		$valid['format'] = true;    
	}

	//If formatted properly, continue
	//if($valid['format']) {}
	if ( !$valid['format'] ) {
		return false;
	} else {
		//Set array of new components
		$components =   array ( 'ac' => $matchset[1], //area code
								'xc' => $matchset[2], //exchange code
								'sn' => $matchset[3] //subscriber number
								);
//     $components =   array ( 'ac' => $matchset[1], //area code
//                              'xc' => $matchset[2], //exchange code
//                              'sn' => $matchset[3], //subscriber number
//                              'xn' => $matchset[4] //extension number
//                           );

		//Set array of number variants
		$numbers    =   array ( 'original' => $matchset[0],
								'stripped' => substr(preg_replace('[\D]', '', $matchset[0]), 0, 10)
							);

		//Now let's check the first ten digits against NANPA standards
		if(preg_match($nanpa_pattern, $numbers['stripped'])) {
			$valid['nanpa'] = true;
		}

		//If the NANPA guidelines have been met, continue
		if ( $valid['nanpa'] ) {
			if ( !empty ( $components['xn'] ) ) {
				if ( preg_match ( '/^[\d]{1,6}$/', $components['xn'] ) ) {
					$valid['ext'] = true;
				}   // end if if preg_match 
			} else {
				$valid['ext'] = true;
			}   // end if if  !empty
		}   // end if $valid nanpa

		//If the extension number is valid or non-existent, continue
		if ( $valid['ext'] ) {
			$valid['all'] = true;
		}   // end if $valid ext
	}   // end if $valid
	return $valid['all'];
}   // end functon validate_phone_number
function SMS_format_phone_number_cron ( $mynum, $mask ) {
	/*********************************************************************/
	/*   Purpose: Return either masked phone number or false             */
	/*     Masks: Val=1 or 0xxxxxxxxxx                                   */
	/*            Val=2 or xxx xxx.xxxx                                  */
	/*            Val=3 or xxx.xxx.xxxx                                  */
	/*            Val=4 or (xxx) xxx xxxx                                */
	/*            Val=5 or (xxx) xxx.xxxx                                */
	/*            Val=6 or (xxx).xxx.xxxx                                */
	/*            Val=7 or (xxx) xxx-xxxx                                */
	/*            Val=8 or (xxx)-xxx-xxxx                                */
	/*********************************************************************/         
	$val_num        = SMS_validate_phone_number_cron ( $mynum );
	if ( !$val_num && !is_string ( $mynum ) ) { 
		echo "Number $mynum is not a valid phone number! \n";
		return false;
	}   // end if !$val_num
	if ( ( $mask == 1 ) || ( $mask == '0xxxxxxxxxx' ) ) { 
		$phone = '0'.preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'$1$2$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 1
	else if ( ( $mask == 2 ) || ( $mask == '0xxxxx xxxxx' ) ) { 
		$phone = '0'.preg_replace('~.*(\d{5})[^\d]*(\d{5})[^\d]*(\d{0}).*~', 
				'$1 $2$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 2
	else if ( ( $mask == 3 ) || ( $mask == '0044xxxxxxxxxx' ) ) { 
		$phone = '0044'.preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'$1$2$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 3
	else if ( ( $mask == 4 ) || ( $mask == '0044xxxxx xxxxx' ) ) { 
		$phone = '0044'.preg_replace('~.*(\d{5})[^\d]*(\d{5})[^\d]*(\d{0}).*~', 
				'$1 $2$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 4
	else if ( ( $mask == 5 ) || ( $mask == '0044 xxxxxxxxxx' ) ) { 
		$phone = '0044 '.preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'$1$2$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 5
	else if ( ( $mask == 6 ) || ( $mask == '0044 xxxxx xxxxx' ) ) { 
		$phone = '0044 '.preg_replace('~.*(\d{5})[^\d]*(\d{5})[^\d]*(\d{0}).*~', 
				'$1 $2$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 6
	else if ( ( $mask == 7 ) || ( $mask == '+44 xxxxxxxxxx' ) ) { 
		$phone = '+44 '.preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'$1$2$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 7
	else if ( ( $mask == 8 ) || ( $mask == '+44 xxxxx xxxxx' ) ) { 
		$phone = '+44 '.preg_replace('~.*(\d{5})[^\d]*(\d{5})[^\d]*(\d{0}).*~', 
				'$1 $2$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 8
	else if ( ( $mask == 3 ) || ( $mask == 'xxx.xxx.xxxx' ) ) { 
		$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'$1.$2.$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 3
	else if ( ( $mask == 4 ) || ( $mask == '(xxx) xxx xxxx' ) ) { 
		$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'($1) $2 $3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 4
	else if ( ( $mask == 5 ) || ( $mask == '(xxx) xxx.xxxx' ) ) { 
		$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'($1) $2.$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 5
	else if ( ( $mask == 6 ) || ( $mask == '(xxx).xxx.xxxx' ) ) { 
		$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'($1).$2.$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 6
	else if ( ( $mask == 7 ) || ( $mask == '(xxx) xxx-xxxx' ) ) { 
		$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'($1) $2-$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 7
	else if ( ( $mask == 8 ) || ( $mask == '(xxx)-xxx-xxxx' ) ) { 
		$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', 
				'($1)-$2-$3'." \n", $mynum);
		return trim($phone);
	}   // end if $mask == 8
	return false;       // Returns false if no conditions meet or input
} 
## START sms_received_cron for Cron Run
function quick2sms_received_cron(){
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	$GLOBALS['log']->debug("Scheduler:  Function sms_received_cron Start");
	global $db, $sugar_config, $current_user;
	
	## START Module License For Access Rights
	require_once('modules/SMS_SMS/license/OutfittersLicense.php');
	$validate_license = OutfittersLicense::isValid('SMS_SMS',$user_id,false);
	if(!$validate_license){
			return true;
	}
	## END Module License For Access Rights
	
	if (empty($sugar_config['firesms_username']) || empty($sugar_config['firesms_password'])) {
		return true;
	}
	
	try{
	FireText::SetAuth($sugar_config['firesms_username'], $sugar_config['firesms_password']);
	
	// Prepare data for POST request
	$smsData = "from=last&pp=50";"";
	// Received SMS
	$smsResponse = FireText::receivedSms($smsData);
	$sms_count = $smsResponse->data;
	$receivedConent = trim(str_replace("Successful request","",$smsResponse->status));
	$receivedArr = preg_split('/\r\n|\r|\n/', $receivedConent); //explode(PHP_EOL, $_POST['thetextarea']);
	//echo '<pre>';
	//print_r($receivedArr);
	//exit;
	if(count($receivedArr)>0)
	{
		foreach($receivedArr as $smsReceived)
		{
			$EachSMS = urldecode($smsReceived);
			$splitSMS = explode("&",$EachSMS);
			$messageID = trim(str_replace("messageID=","",$splitSMS[0]));
			$sentTo = trim(str_replace("sentTo=","",$splitSMS[1]));
			$keyword = trim(str_replace("keyword=","",$splitSMS[2]));
			$receivedFrom = trim(str_replace("receivedFrom=","",$splitSMS[3]));
			$receivedOn = trim(str_replace("receivedOn=","",$splitSMS[4]));
			$message = trim(str_replace("message=","",$splitSMS[5]));
			#$message = 'JOIN '.trim(str_replace("message=","",$splitSMS[5]));
			//$receivedFrom = '01326702098';
			
			$phone_mobile1 = SMS_format_phone_number_cron($receivedFrom, '0xxxxxxxxxx');
			$phone_mobile2 = SMS_format_phone_number_cron($receivedFrom, '0xxxxx xxxxx');
			$phone_mobile3 = SMS_format_phone_number_cron($receivedFrom, '0044xxxxxxxxxx');
			$phone_mobile4 = SMS_format_phone_number_cron($receivedFrom, '0044xxxxx xxxxx');
			$phone_mobile5 = SMS_format_phone_number_cron($receivedFrom, '0044 xxxxxxxxxx');
			$phone_mobile6 = SMS_format_phone_number_cron($receivedFrom, '0044 xxxxx xxxxx');
			$phone_mobile7 = SMS_format_phone_number_cron($receivedFrom, '+44 xxxxxxxxxx');
			$phone_mobile8 = SMS_format_phone_number_cron($receivedFrom, '+44 xxxxx xxxxx');
			
			$Contactbean = BeanFactory::getBean('Contacts');
			$contact_lists = $Contactbean->get_full_list("", "(contacts.phone_mobile = '".$phone_mobile1."' OR contacts.phone_mobile = '".$phone_mobile2."' OR contacts.phone_mobile = '".$phone_mobile3."' OR contacts.phone_mobile = '".$phone_mobile4."' OR contacts.phone_mobile = '".$phone_mobile5."' OR contacts.phone_mobile = '".$phone_mobile6."' OR contacts.phone_mobile = '".$phone_mobile7."' OR contacts.phone_mobile = '".$phone_mobile8."')");
			//echo count($contact_lists);exit;
			foreach($contact_lists as $contact_list){
				## START Send Message Entry Log in SMS Module
				$SMS_SMS = new SMS_SMS();
				$SMS_SMS->parent_type = 'Contacts';
				$SMS_SMS->parent_id = $contact_list->id; 
				$SMS_SMS->name = $receivedFrom; 
				$SMS_SMS->messageid = $messageID;
				$SMS_SMS->smsreceivedon = $receivedOn;
				$SMS_SMS->description = $message;
				$SMS_SMS->delivery_status = "";
				$SMS_SMS->msg_type = 'Received';
				$SMS_SMS->msg_response = ''; 
				$SMS_SMS->assigned_user_id = $current_user->id;
				require('sugar_version.php');
				if($sugar_flavor != 'CE')
				$SMS_SMS->team_id = $current_user->default_team;
				$SMS_SMS->save();
				## END Send Message Entry Log in SMS Module
				
				## START SMS Opted Out
				if(strtoupper(substr($message,0,4))=='STOP'){
					$qry_upd = "UPDATE contacts_cstm SET sms_opt_out_c = '1' 
								WHERE id_c = '".$contact_list->id."'";
					$db->query($qry_upd);
				}
				if(strtoupper(substr($message,0,4))=='JOIN'){
					$qry_upd = "UPDATE contacts_cstm SET sms_opt_out_c = '0' 
								WHERE id_c = '".$contact_list->id."'";
					$db->query($qry_upd);
				}
				## END SMS Opted Out
			}// END Foreach $contact_lists
			$Leadbean = BeanFactory::getBean('Leads');
			$lead_lists = $Leadbean->get_full_list("", "(leads.phone_mobile = '".$phone_mobile1."' OR leads.phone_mobile = '".$phone_mobile2."' OR leads.phone_mobile = '".$phone_mobile3."' OR leads.phone_mobile = '".$phone_mobile4."' OR leads.phone_mobile = '".$phone_mobile5."' OR leads.phone_mobile = '".$phone_mobile6."' OR leads.phone_mobile = '".$phone_mobile7."' OR leads.phone_mobile = '".$phone_mobile8."')");
			foreach($lead_lists as $lead_list){
				## START Send Message Entry Log in SMS Module
				$SMS_SMS = new SMS_SMS();
				$SMS_SMS->parent_type = 'Leads';
				$SMS_SMS->parent_id = $lead_list->id; 
				$SMS_SMS->name = $receivedFrom; 
				$SMS_SMS->messageid = $messageID;
				$SMS_SMS->smsreceivedon = $receivedOn;
				$SMS_SMS->description = $message;
				$SMS_SMS->delivery_status = "";
				$SMS_SMS->msg_type = 'Received';
				$SMS_SMS->msg_response = ''; 
				$SMS_SMS->assigned_user_id = $current_user->id;
				require('sugar_version.php');
				if($sugar_flavor != 'CE')
				$SMS_SMS->team_id = $current_user->default_team;
				$SMS_SMS->save();
				## END Send Message Entry Log in SMS Module
	
				## START SMS Opted Out				
				if(strtoupper(substr($message,0,4))=='STOP'){
					$qry_upd = "UPDATE leads_cstm SET sms_opt_out_c = '1' 
								WHERE id_c = '".$lead_list->id."'";
					$db->query($qry_upd);
				}
				if(strtoupper(substr($message,0,4))=='JOIN'){
					$qry_upd = "UPDATE leads_cstm SET sms_opt_out_c = '0' 
								WHERE id_c = '".$lead_list->id."'";
					$db->query($qry_upd);
				}
				## END SMS Opted Out
			}// END Foreach $lead_lists
		}// END Foreach $receivedArr
	}// END IF count($receivedArr)
	}catch(Exception $e) {
		$GLOBALS['log']->fatal('Exception Error: '.print_r($e->getMessage(),true));
	}
	
	return true;
}
## END sms_received_cron for Cron Run

?>