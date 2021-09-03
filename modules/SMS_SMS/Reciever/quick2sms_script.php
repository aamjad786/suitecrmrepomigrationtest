<?php
#set_ time_limit(0);
include_once('sugar_version.php');
require_once('include/utils/db_utils.php');
require_once('modules/SMS_SMS/firesms.php'); 
require_once('modules/SMS_SMS/license/OutfittersLicense.php');
global $db, $sugar_config, $current_user;

if($_SERVER['HTTP_USER_AGENT']!="FireText"){
	exit;
}

$current_user = new User();
$current_user->retrieve('1');
$GLOBALS['log']->debug('Inbound SMS FireText');

## START Module License Checking
$validate_license = OutfittersLicense::isValid('SMS_SMS',$user_id,false);
if($validate_license != '1'){
	$error_msg = "<div style='background-color:#fff;padding:10px;text-align:center;'>Your license key is Invalid or has expired.
<br /><br />
Please contact FibreCRM (licensing@fibrecrm.com) to obtain a new license key or buy one now at <a href=\"http://www.fibrecrm.com/crm-plugins/sugarsms\">http://www.fibrecrm.com/crm-plugins/sugarsms</a>. If you already have a valid key, <a href=\"index.php?module=SMS_SMS&action=license\">click here to enter</a> it now.</div>";
	echo $error_msg;
	exit;
}
## END Module License Checking

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

if(strlen($_REQUEST['source'])>0 && strlen($_REQUEST['message'])>0)
{
    $no_record_found = true;
	$sentTo = trim(urldecode($_REQUEST['destination']));
	$keyword = trim(urldecode($_REQUEST['keyword']));
	$receivedFrom = trim(urldecode($_REQUEST['source']));
	$receivedOn = trim(urldecode($_REQUEST['time']));
	$message = trim(urldecode($_REQUEST['message']));

	$GLOBALS['log']->debug('--->Inbound SMS FireText Get SMS Message: '.$receivedFrom);
	
	if($message=='No Message Content'){
		$GLOBALS['log']->debug('--->Inbound SMS FireText No Message Content');
		exit;
	}
	
	$phone_mobile1 = SMS_format_phone_number_cron($receivedFrom, '0xxxxxxxxxx');
	$phone_mobile2 = SMS_format_phone_number_cron($receivedFrom, '0xxxxx xxxxx');
	$phone_mobile3 = SMS_format_phone_number_cron($receivedFrom, '0044xxxxxxxxxx');
	$phone_mobile4 = SMS_format_phone_number_cron($receivedFrom, '0044xxxxx xxxxx');
	$phone_mobile5 = SMS_format_phone_number_cron($receivedFrom, '0044 xxxxxxxxxx');
	$phone_mobile6 = SMS_format_phone_number_cron($receivedFrom, '0044 xxxxx xxxxx');
	$phone_mobile7 = SMS_format_phone_number_cron($receivedFrom, '+44 xxxxxxxxxx');
	$phone_mobile8 = SMS_format_phone_number_cron($receivedFrom, '+44 xxxxx xxxxx');
	
	$Contactbean = BeanFactory::getBean('Contacts');
	
	$contact_lists = $Contactbean->get_full_list("", "((contacts.phone_mobile = '".$phone_mobile1."' OR contacts.phone_mobile = '".$phone_mobile2."' OR contacts.phone_mobile = '".$phone_mobile3."' OR contacts.phone_mobile = '".$phone_mobile4."' OR contacts.phone_mobile = '".$phone_mobile5."' OR contacts.phone_mobile = '".$phone_mobile6."' OR contacts.phone_mobile = '".$phone_mobile7."' OR contacts.phone_mobile = '".$phone_mobile8."') OR (contacts.phone_work = '".$phone_mobile1."' OR contacts.phone_work = '".$phone_mobile2."' OR contacts.phone_work = '".$phone_mobile3."' OR contacts.phone_work = '".$phone_mobile4."' OR contacts.phone_work = '".$phone_mobile5."' OR contacts.phone_work = '".$phone_mobile6."' OR contacts.phone_work = '".$phone_mobile7."' OR contacts.phone_work = '".$phone_mobile8."') OR (contacts.phone_home = '".$phone_mobile1."' OR contacts.phone_home = '".$phone_mobile2."' OR contacts.phone_home = '".$phone_mobile3."' OR contacts.phone_home = '".$phone_mobile4."' OR contacts.phone_home = '".$phone_mobile5."' OR contacts.phone_home = '".$phone_mobile6."' OR contacts.phone_home = '".$phone_mobile7."' OR contacts.phone_home = '".$phone_mobile8."') OR (contacts.phone_other = '".$phone_mobile1."' OR contacts.phone_other = '".$phone_mobile2."' OR contacts.phone_other = '".$phone_mobile3."' OR contacts.phone_other = '".$phone_mobile4."' OR contacts.phone_other = '".$phone_mobile5."' OR contacts.phone_other = '".$phone_mobile6."' OR contacts.phone_other = '".$phone_mobile7."' OR contacts.phone_other = '".$phone_mobile8."'))");
	
    if(count($contact_lists) > 0)
    {
    	foreach($contact_lists as $contact_list){
    		$GLOBALS['log']->debug('--->Inbound SMS FireText Contact ID: '.$contact_list->id);
            $no_record_found = false;
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
    		//if(strtoupper(substr($message,0,4))=='STOP')
    		if(strtoupper($keyword)=='STOP'){
    			$GLOBALS['log']->debug('--->Inbound SMS FireText STOP SMS Message Contact ID: '.$contact_list->id);
    			$qry_upd = "UPDATE contacts_cstm SET sms_opt_out_c = '1' 
    						WHERE id_c = '".$contact_list->id."'";
    			$db->query($qry_upd);
    		}
    		if(strtoupper(substr($message,0,4))=='JOIN'){
    			$GLOBALS['log']->debug('--->Inbound SMS FireText JOIN SMS Message Contact ID: '.$contact_list->id);
    			$qry_upd = "UPDATE contacts_cstm SET sms_opt_out_c = '0' 
    						WHERE id_c = '".$contact_list->id."'";
    			$db->query($qry_upd);
    		}
    		## END SMS Opted Out
    	}// END Foreach $contact_lists
    }

	$Leadbean = BeanFactory::getBean('Leads');
	$lead_lists = $Leadbean->get_full_list("", "((leads.phone_mobile = '".$phone_mobile1."' OR leads.phone_mobile = '".$phone_mobile2."' OR leads.phone_mobile = '".$phone_mobile3."' OR leads.phone_mobile = '".$phone_mobile4."' OR leads.phone_mobile = '".$phone_mobile5."' OR leads.phone_mobile = '".$phone_mobile6."' OR leads.phone_mobile = '".$phone_mobile7."' OR leads.phone_mobile = '".$phone_mobile8."') OR (leads.phone_work = '".$phone_mobile1."' OR leads.phone_work = '".$phone_mobile2."' OR leads.phone_work = '".$phone_mobile3."' OR leads.phone_work = '".$phone_mobile4."' OR leads.phone_work = '".$phone_mobile5."' OR leads.phone_work = '".$phone_mobile6."' OR leads.phone_work = '".$phone_mobile7."' OR leads.phone_work = '".$phone_mobile8."') OR (leads.phone_home = '".$phone_mobile1."' OR leads.phone_home = '".$phone_mobile2."' OR leads.phone_home = '".$phone_mobile3."' OR leads.phone_home = '".$phone_mobile4."' OR leads.phone_home = '".$phone_mobile5."' OR leads.phone_home = '".$phone_mobile6."' OR leads.phone_home = '".$phone_mobile7."' OR leads.phone_mobile = '".$phone_mobile8."') OR (leads.phone_other = '".$phone_mobile1."' OR leads.phone_other = '".$phone_mobile2."' OR leads.phone_other = '".$phone_mobile3."' OR leads.phone_other = '".$phone_mobile4."' OR leads.phone_other = '".$phone_mobile5."' OR leads.phone_other = '".$phone_mobile6."' OR leads.phone_other = '".$phone_mobile7."' OR leads.phone_other = '".$phone_mobile8."'))");
    
    if(count($lead_lists) > 0)
    {
    	foreach($lead_lists as $lead_list){
    		$GLOBALS['log']->debug('--->Inbound SMS FireText Lead ID: '.$lead_list->id);
            $no_record_found = false;
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
    		//if(strtoupper(substr($message,0,4))=='STOP'){
    		if(strtoupper($keyword)=='STOP'){
    			$GLOBALS['log']->debug('--->Inbound SMS FireText STOP SMS Message Lead ID: '.$lead_list->id);
    			$qry_upd = "UPDATE leads_cstm SET sms_opt_out_c = '1' 
    						WHERE id_c = '".$lead_list->id."'";
    			$db->query($qry_upd);
    		}
    		if(strtoupper(substr($message,0,4))=='JOIN'){
    			$GLOBALS['log']->debug('--->Inbound SMS FireText JOIN SMS Message Lead ID: '.$lead_list->id);
    			$qry_upd = "UPDATE leads_cstm SET sms_opt_out_c = '0' 
    						WHERE id_c = '".$lead_list->id."'";
    			$db->query($qry_upd);
    		}
    		## END SMS Opted Out
    	}// END Foreach $lead_lists
    }

	## START IF No Record Found Then Create New Record without Relation Contacts & Leads
    if($no_record_found === true)
    {
        ## START Send Message Entry Log in SMS Module
        $SMS_SMS = new SMS_SMS();
        $SMS_SMS->parent_type = 'Leads';
        $SMS_SMS->parent_id = ''; 
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
    }
    ## END IF No Record Found Then Create New Record without Relation Contacts & Leads
}

##echo "<br /><br />Successfully Done";

?>