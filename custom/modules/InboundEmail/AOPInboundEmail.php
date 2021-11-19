<?php
 /**
 * 
 * 
 * @package 
 * @copyright SalesAgility Ltd http://www.salesagility.com
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author Salesagility Ltd <support@salesagility.com>
 */

use Api\Core\Loader\CustomLoader;

require_once 'custom/modules/InboundEmail/InboundEmail.php';
require_once 'include/clean.php';
require_once 'custom/CustomLogger/CustomLogger.php';
class AOPInboundEmail extends InboundEmail
{
    public $job_name = 'function::custompollMonitoredInboxesAOP';

    public function __construct() {
        parent::__construct();
		$this->logger =new CustomLogger('custompollMonitoredInboxesAOP-'.date('Ymd'));
	}

    /**
     * Replaces embedded image links with links to the appropriate note in the CRM.
     * @param $string
     * @param $noteIds A whitelist of note ids to replace
     * @return mixed
     */
    function processImageLinks($string, $noteIds){
        global $sugar_config;
        if(!$noteIds){
            return $string;
        }
        $matches = array();
        preg_match('/cid:([[:alnum:]-]*)/',$string,$matches);
        if(!$matches){
            return $string;
        }
        array_shift($matches);
        $matches = array_unique($matches);
        foreach ($matches as $match) {
            if (in_array($match, $noteIds)) {
                $string = str_replace('cid:'.$match, $sugar_config['site_url']."/index.php?entryPoint=download&id={$match}&type=Notes&", $string);
            }
        }
        return $string;
    }
    
    function getAPPID($str){
        preg_match_all('!\d+!', $str, $matches);
        foreach($matches[0] as $num){
                $num_length = strlen((string)$num);
                if($num_length=='7'){
                        // echo "APP ID:$num";
                    return $num;
                    break;
                }
        }
        return null;
    }
    
    
    function isMineField($str) {
        global $sugar_config;
        $arrayOfMineFieldElements = $sugar_config['arrayOfMineFieldElements'];
        $listOfEmailsListedUnderMineField = $sugar_config['listOfEmailsListedUnderMineField'];
        if(is_array($str)) {
            foreach($str as $instanceElement){
                if(in_array($instanceElement, $listOfEmailsListedUnderMineField)){
                    return true;
                }
            }
        } else {
            foreach ($arrayOfMineFieldElements as $instanceElement) {
                if (preg_match("/\b$instanceElement\b/i", $str)) {
                    return true;    
                }
            }
            foreach ($listOfEmailsListedUnderMineField as $instanceEmail){
                if (preg_match("/\b$instanceEmail\b/i", $str)) {
                    return true;
                }
            }
        }
        return false;
    }


    // function curl_req($url){
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_HTTPGET, 1);
    //     curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     $output = curl_exec($ch);
    //     curl_close($ch);
    //     return $output;
    //  }

    function handleCreateCase($email, $userId) {
        if(empty($email))
            return;

        global $sugar_config, $mod_strings, $current_language;

        $this->logger->log('debug', "In handleCreateCase in AOPInboundEmail for $email->name");
        
        if(substr(trim($email->name), 0, 14) === 'Undeliverable:' || substr(trim($email->name), 0, 11) === 'Undelivered'){
            $this->logger->log('info', "Skipping the case creation for [$email->name] as its undeliverable mail");
            return;
        }
        if (strpos($email->from_addr, $sugar_config['skip_handleCreateCase_from_domain']) !== false) {
            $this->logger->log('info', "Skipping the case creation for [$email->name] as its from '@neogrowth.onmicrosoft.com domain");
            return;
        }

        $from_addrs = $sugar_config['skip_handleCreateCase_from_addrs'];
        foreach($from_addrs as $email1){
            if(strcasecmp($email->from_addr, $email1)==0){
                $this->logger->log('info', "Skipping the case creation for [$email->name] as its mail from $email1");
                return;
            }
        }
        // Adding condition for skipping the cases coming from namaste@fullertonindia.com and more conditions
        //Added for Ticket CSI-1167
        $this->loggerTesting = new CustomLogger('TestingCases1');

        $GLOBALS['log']->fatal(print_r($email->name,true));
        if((strcasecmp($email->from_addr, $sugar_config['ng_khatal_jay']) == 0) && (strpos($email->name, $sugar_config['skip_handleCreateCase_email_name']) !== false)) {
            $this->loggerTesting->log('info', "Skipping the case creation for [$email->name] as its mail from $email->from_addr AND subject starts with Re : Auto Acknowledgement for Service");
            return;
        }
       
        $skip_email =$this->extract_emails($email->from_addr);
        
        if($skip_email[0]==$sugar_config['helpdesk_email'] || strpos($email->from_addr,$sugar_config['helpdesk_email']) !== false){

            $this->loggerTesting->log('info', "Inside skiping  automatic email case creation for [$email->from_addr] as its mail");
            return;
        }
        
        if((strpos($email->from_addr,"consumer.support@in.experian.com") !== false || $skip_email[0]=='consumer.support@in.experian.com') && strpos($email->name ,"Automatic reply") !== false){

            $this->loggerTesting->log('info', "Skipping the automatic email case creation for [$email->name] as its mail from");
            return;
        }
        if ($email->name == "Detractor Response") {
            require_once('ApplicationApi.php');
            require_once('custom/modules/Calls/CreateCall.php');

            $applicationApis = new ApplicationApi();
            $createCall = new CreateCall();
            $mod_strings = return_module_language($current_language, "Emails");
            $phoneNumber = $this->extractPhoneNumberFromEmail($email->description_html);

            $this->logger->log('info', "Its detractor response, creating call for $applicationId");
            if (!empty($phoneNumber)) {
                $applicationId = $applicationApis->getApplicationByPhoneNumber($phoneNumber);
                if(!empty($applicationId)){
                    $createCall->customCreateCall($applicationId, "nps_detractor");
                    return; // Just create call and exit
                } 
               return;
            }
        }
       
        if (!$this->handleCaseAssignment($email) && $this->isMailBoxTypeCreateCase()) {
            // create a new case
            $this->logger->log('info', "Creating case for Email:$email->id from $email->from_addr, Subject:[$email->name]");

            $app_id = null;
            $noteIds = array();
            $base_url = getenv("SCRM_AS_API_BASE_URL");
            $email->retrieve($email->id);
            $c = new aCase();
            $c->priority = 'P3';
            $c->case_sub_source_c = 'email';
            $c->merchant_email_id_c = $email->from_addr;
            $c->case_source_c = 'internal';
            $c->assigned_user_id = $userId;
            $c->attended_by_c = $this->getUserName($userId);
            $c->name = $email->name;
            $c->description = $email->description;

            $notes = $email->get_linked_beans('notes','Notes');
            foreach($notes as $note){
                $noteIds[] = $note->id;
            }

            if($email->description_html) {
                $c->description = $this->processImageLinks(SugarCleaner::cleanHtml($email->description_html),$noteIds);
            }

            if($this->isMineField($email->name) || $this->isMineField($c->description) || $this->isMineField($email->to_addrs) || $this->isMineField($email->cc_addrs)) {
                $this->logger->log('info', "is Minefield");
                $c->priority = 'P4';
            }

            if (strpos($email->name, 'Suspicious Transactions') !== false) {
                $c->case_category_c = 'information';
                $c->case_subcategory_c="information_suspicious_transaction";
                $c->type = "request";
                $c->priority="P1";
            }

            if (strpos($email->from_addr, 'neogrowth.in') == false) {
                $c->case_source_c = 'merchant';
                $c->status="merchant_email";
            }
            
            if(empty($c->complaintaint_c)) {
                $c->complaintaint_c = !empty($email->from_addr_name) ? $email->from_addr_name : $email->from_addr;
            }

            // Custom code internal to neogrowth
            $app_id = $this->getAppidFromEmailCase($email->name, $c->description, $email->from_addr);
            
            if(!empty($app_id)){
                $url = "$base_url/get_merchant_details?ApplicationID=$app_id";
                $c->merchant_app_id_c = $app_id;
                
                require_once('custom/include/CurlReq.php');
                $curl_req = new CurlReq();

                $response = $curl_req->curl_req($url);
                $json_response = json_decode($response);
                $json_response = $json_response[0];
                if(!empty($json_response)){
                    $c->merchant_contact_number_c = $json_response->{'Applicant Number'};
                    $c->merchant_name_c = $json_response->{'Applicant Person'};
                    $c->merchant_establisment_c = $json_response->{'Company Name'};
                    $c->merchant_email_id_c = $json_response->{'Applicant Email Id'};
                    $c->case_location_c = strtolower($json_response->{'Branch Name'});
                    $c->complaintaint_c = $json_response->{'Applicant Person'};
                }
            } 
            else {
                $c->merchant_app_id_c = 'N/A';
                $c->merchant_contact_number_c = 'N/A';
                $c->merchant_name_c = '';
                $c->merchant_establisment_c = 'N/A';
            }

            $c->save(true);
            $caseId = $c->id;
            $c = new aCase();
            $c->retrieve($caseId);
            if($c->load_relationship('emails')) {
                $c->emails->add($email->id);
            } 
           
            //Attachments
            // foreach($notes as $note){
            //     //Link notes to case also
            //     $newNote = BeanFactory::newBean('Notes');
            //     $newNote->name = $note->name;
            //     $newNote->file_mime_type = $note->file_mime_type;
            //     $newNote->filename = $note->filename;
            //     $newNote->parent_type = 'Cases';
            //     $newNote->parent_id = $c->id;
            //     $newNote->save();
            //     $srcFile = "upload://{$note->id}";
            //     $destFile = "upload://{$newNote->id}";
            //     copy($srcFile,$destFile);
            // }

            // assign name with CaseID, parent type, parent_id and the email to the case owner
            $email->parent_type = "Cases";
            $email->parent_id = $c->id;
            $email->assigned_user_id = $c->assigned_user_id;
            $email->name = str_replace('%1', $c->case_number, $c->getEmailSubjectMacro()) . " ". $email->name;
            $email->save();

            $this->logger->log('info', 'AOPInboundEmail created one case with number: '.$c->case_number);

            if ($c->case_source_c == 'merchant') {
                $this->sendReplyToMerchant($email,$c);
                $this->logger->log('debug', 'Saved and sent auto-reply email');
            }
        } 
        else {
            echo "First if not matching\n";
            $contactAddr = $email->from_addr;
            if(!empty($email->reply_to_email)) {
                $contactAddr = $email->reply_to_email;
            } 
            $this->handleAutoresponse($email, $contactAddr);
        }   
        echo "End of handle create case\n";
    } // fn

    function extract_emails($str){

        $regexp = '/([a-z0-9_\.\-])\@(([a-z0-9\-])\.)([a-z0-9]{2,4})/i';
        preg_match_all($regexp, $str, $m);

        return isset($m[0]) ? $m[0] : array();
    }

    function getUserName($user_id) {   
        $user = BeanFactory::getBean('Users',$user_id);
      
        if($user){

            return $user->first_name." ".$user->last_name;
        } 
        return "";
    }

    function getAppidFromEmailCase($email_name, $case_description, $email_from_addr) {
        $app_id = $this->getAPPID($email_name);
            
        if(empty($app_id)){
            $app_id=$this->getAPPID($case_description);
        }

        if(empty($app_id)){
            $base_url = getenv("SCRM_AS_API_BASE_URL");
            $url = "$base_url/get_applications_by_email?email=$email_from_addr";
            
			require_once('custom/include/CurlReq.php');
			$curl_req = new CurlReq();

			$response = $curl_req->curl_req($url);
            $json_response = json_decode($response);
            rsort($json_response);
            $app_id = $json_response[0];
        }
        return $app_id;
    }

    function sendReplyToMerchant($email,$c) {
        global $current_user;
        
        if(!empty($this->stored_options)) {
            $storedOptions = unserialize(base64_decode($this->stored_options));
        }
        $fromName = "";
        $fromAddress = "";
        try{
            if (!empty($this->stored_options)) {
                $fromAddress = $storedOptions['from_addr'];
                // isValidEmailAddress($fromAddress);
                $fromName = from_html($storedOptions['from_name']);
                $replyToName = (!empty($storedOptions['reply_to_name']))? from_html($storedOptions['reply_to_name']) :$fromName ;
                $replyToAddr = (!empty($storedOptions['reply_to_addr'])) ? $storedOptions['reply_to_addr'] : $fromAddress;
            } // if
            $defaults = $current_user->getPreferredEmail();
            $fromAddress = (!empty($fromAddress)) ? $fromAddress : $defaults['email'];
            // isValidEmailAddress($fromAddress);
            $fromName = (!empty($fromName)) ? $fromName : $defaults['name'];
            $to[0]['email'] = $email->from_addr;
            if(!empty($email->reply_to_email)) {
                $to[0]['email'] = $email->reply_to_email;
            } 
        }catch(Exception $e){
            $this->logger->log('info', "Exception occured in sendReplyToMerchant ".$e->getMessage());
        }
        // handle to name: address, prefer reply-to
        if (!empty($email->reply_to_name)) {
            $to[0]['display'] = $email->reply_to_name;
        } elseif (!empty($email->from_name)) {
            $to[0]['display'] = $email->from_name;
        }

        $createCaseTemplateId = $this->get_stored_options('create_case_email_template', "");
        $et = new EmailTemplate();
        if (!empty($createCaseTemplateId)) 
            $et->retrieve($createCaseTemplateId);
        else 
            $et->retrieve_by_string_fields(array('name' => 'Case Creation Template')); // Template name hardcoded here

        if(empty($app_id))
            $et->retrieve_by_string_fields(array('name' => 'Unregistered Mail Id Case'));
        if (empty($et->subject)) 
            $et->subject = '';
        if (empty($et->body)) 
            $et->body = '';
        if (empty($et->body_html)) 
            $et->body_html = '';

        $et->subject = "Re:" . " " . str_replace('%1', $c->case_number, $c->getEmailSubjectMacro() . " ". $c->name);

        $html_description = trim($email->description_html);
        $plain_description = trim($email->description);
        $merchant_name = $c->merchant_name_c;
        if(empty($merchant_name))
            $merchant_name = $to[0]['email'];
        $body_html = $et->body_html;
        $body_html = str_replace('$merchant_name', $merchant_name, $body_html);
        $body_html = str_replace('$case_number', $c->case_number,  $body_html);
        $email->email2init();
        $email->from_addr = $email->from_addr_name;
        // isValidEmailAddress($email->from_addr);
        $email->to_addrs = $email->to_addrs_names;
        $email->cc_addrs = $email->cc_addrs_names;
        $email->bcc_addrs = $email->bcc_addrs_names;
        $email->from_name = $email->from_addr;

        $email = $email->et->handleReplyType($email, "reply");
        $ret = $email->et->displayComposeEmail($email);
        $ret['description'] = empty($html_description) ?  str_replace("\n", "\n<BR/>", $plain_description) : $html_description;

        if(!empty($c->status)){
            $reply = new Email();
            $reply->type                = 'out';
            $reply->to_addrs            = $to[0]['email'];
            $reply->to_addrs_arr        = $to;
            $reply->cc_addrs_arr        = array();
            $reply->bcc_addrs_arr       = array();
            $reply->from_name           = $fromName;
            $reply->from_addr           = $fromAddress;
            $reply->reply_to_name       = $replyToName;
            $reply->reply_to_addr       = $replyToAddr;
            $reply->name                = $et->subject;
            $reply->description         = $et->body . "<div><hr /></div>" .  $plain_description;
            if (!$et->text_only) {
                $reply->description_html    = $body_html .  "<div><hr /></div>" . $plain_description;
            }
            
            $reply->send();
            $reply->parent_type = 'Cases';
            $reply->parent_id = $c->id;
            $reply->date_sent = TimeDate::getInstance()->nowDb();
            $reply->modified_user_id = '1';
            $reply->created_by = '1';
            $reply->status = 'sent';
            $reply->save(); // don't save the actual email.
            
        }
    }
    
    function extractPhoneNumberFromEmail($emailDescription) {
        //This may not be the best practice, Needs rework on this
       $slicedDataPartOne = strstr($emailDescription, 'Custom Variable 1');
       $slicedText = substr($slicedDataPartOne, 0, strpos($slicedDataPartOne, "Geo Coding"));
       $phoneNumber = $this->getPhoneNumber($slicedText);
       return $phoneNumber;
   }

   function getPhoneNumber($str) {
       preg_match_all('/\d+/', $str, $matches);
       $phoneNumber = "";
       foreach ($matches[0] as $key => $val) {
           if (strlen($val) == 10) {
               $phoneNumber = $val;
               return $phoneNumber;
           };
       }
       return;
   }

   
   function getApplication($phoneNumber){
       global $db;
       $queryTOGetApplicationId = "select app_id_list from neo_customers where mobile = '$phoneNumber'";
       $applicationIdsResponse = $db->query($queryTOGetApplicationId);
       while ($row = $db->fetchByAssoc($applicationIdsResponse)) {
           $applicationIds = $row['app_id_list'];
           if(!empty($applicationIds)){
               $str_arr = explode (",", $applicationIds); 
               $arraySize = sizeof($str_arr);
               return ($str_arr[$arraySize-1]);
           }

       }
               
       //Check if application Ids are multiple
   }



}
