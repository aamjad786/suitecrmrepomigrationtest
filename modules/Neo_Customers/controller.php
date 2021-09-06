<?php
if(!defined('sugarEntry')) die('Not a Valid Entry Point');

//require_once('modules/bhea_Reports/report_utils.php');

class Neo_CustomersController extends SugarController {

	function action_test(){	

	}

	function action_get_tentative_deals(){
		$this->view = "get_tentative_deals";
	}

	function action_RenewalsUserAnalytics(){
		$this->view = "renewals_user_analytics";
	}

	function action_tentativeofferdetails(){
        $this->view = 'upfront_deduction_update';
    }
	
    function action_assign() {
    	global $current_user;
		require_once 'modules/ACLRoles/ACLRole.php';
		// var_dump($current_user);
		$objACLRole = new ACLRole();
		$roles = $objACLRole->getUserRoles($current_user->id);
		if($current_user->is_admin || in_array('Renewal admin',$roles)) {
			$this->view = "assign";
		}else{
			echo "You can not access this. Contact Admin.";
		}	
    }

    function action_monthly_file_upload(){
        global $current_user;
		require_once 'modules/ACLRoles/ACLRole.php';
		// var_dump($current_user);
		$objACLRole = new ACLRole();
		$roles = $objACLRole->getUserRoles($current_user->id);
		if($current_user->is_admin || in_array('Renewal admin',$roles)) {
			$this->view = "monthly_file_upload";
		}else{
			echo "You can not access this. Contact Admin.";
		}	
    }

    function action_downloadSample(){
    	// die('erer');
        function cleanData(&$str)
          {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
          }

           function display_colnames($arr)
          {
            // $bean = BeanFactory::getBean('Neo_Customers');
            // foreach($arr as $i=>$k){
            //     $v = translate($bean->field_defs[$k]['vname'],  'Neo_Customers');
            //     $arr[$i] = html_entity_decode ($v);

            // }
            foreach($arr as $k=>$v){
            	if($v=='customer_id'){
            		$v='Customer_ID';
            	}else if($v=='blacklisted'){
            		$v='Blacklist';
            	}else if($v=='ever_30_dpd'){
            		$v="30 DPD'";
            	}else if($v=='half_paid_up'){
            		$v="50% paid-up";
            	}
            	$arr[$k]=$v;
            }
            // $arr = ['Customer_ID','Blacklist',"30 DPD'","50% paid-up"];
            return $arr;
        }
        function display_value($arr)
        {

        	foreach($arr as $i=>$k){
        		if($i=='customer_id')continue;
        		if($k==0){
        			$arr[$i] = 'N';
        		}else if($k==1){
        			$arr[$i] = 'Y';
        		}

        	}
        	return $arr;
        }
        $filename = "master_data_" . date('Ymd') . ".csv";

          header("Content-Disposition: attachment; filename=\"$filename\"");
          header('Content-Type: text/csv; charset=utf-8');
          global $db;
          $flag = false;
          $result = $db->query("SELECT customer_id,blacklisted,ever_30_dpd,half_paid_up FROM neo_customers ORDER BY customer_id limit 5") or die('Query failed!');
          while(false !== ($row = $db->fetchByAssoc($result))) {
            if(!$flag) {
              // display field/column names as first row
              echo implode(",", display_colnames(array_keys($row))) . "\r\n";
              $flag = true;
            }
            array_walk($row, __NAMESPACE__ . '\cleanData');
            echo implode(",", display_value(($row))) . "\r\n";
          }
          // ob_end_clean();
          exit;
    }

    function action_eligibility_upload(){
    	// die('here');
        global $current_user;
		require_once 'modules/ACLRoles/ACLRole.php';
		// var_dump($current_user);
		$objACLRole = new ACLRole();
		$roles = $objACLRole->getUserRoles($current_user->id);
		if($current_user->is_admin || in_array('Renewal admin',$roles)) {
			$this->view = "eligibility_upload";
		}else{
			echo "You can not access this. Contact Admin.";
		}	
    }

    function getUrl(){
    	global $sugar_config;
    	$url = "";
        $parsedSiteUrl = parse_url($sugar_config['site_url']);
        $host = $parsedSiteUrl['host'];
        if (!isset($parsedSiteUrl['port'])) {
            $parsedSiteUrl['port'] = 80;
        }
        $port = ($parsedSiteUrl['port'] != 80) ? ":" . $parsedSiteUrl['port'] : '';
        $path = !empty($parsedSiteUrl['path']) ? $parsedSiteUrl['path'] : "";
        $cleanUrl = "{$parsedSiteUrl['scheme']}://{$host}{$port}{$path}";
        //$url = $cleanUrl . "/index.php?module={$this->module_dir}&action=DetailView";
        $url = $cleanUrl . "/index.php?module=Neo_Customers&action=index&return_module=Neo_Customers&return_action=DetailView";
        return $url;
    }

    function getUserId($id){
    	$bean 				= BeanFactory::getBean($this->module,$id);
    	$assigned_user_id 	= "";
    	$assigned_user_id 	= $bean->assigned_user_id;
    	return $assigned_user_id;
    }

    function getLabelName($str){
		global $mod_strings;
		global $app_strings;
		$str = strtoupper($str);
		$str1 = $mod_strings['LBL_'.$str];
		if(empty($str1)){
			$str1 = $app_strings['LBL_'.$str];
			if(empty($str1)){
				$str1 = $str;	
			}	
		} 
		$GLOBALS['log']->debug("Mass Update get Label final string after checking mod & app strings: " . $str1);
		return $str1;
    }

	public function massUpdateEmailNotification($post,$seed){
		global $current_user;
		require_once('SendEmail.php');

		$updatedRecords 			= array();
		$assignedToUserRecords 		= false;
		$emailTo 					= array();
		$no_of_records				= 0;
		$no_of_records_assigned		= 0;
		$individual_rec_count		= array();
		$body 						= "";
		$user_ids					= array();
		$fields 					= "0";
		$current_user_name			= $current_user->name;
		$url 						= $this->getUrl();
		$sendEmail = new SendEmail();
		if(isset($post['selectCount'][0]))
			$no_of_records = $post['selectCount'][0];

		foreach($post as $key=>$value){
			if(is_string($value) && isset($seed->field_defs[$key]) && !empty($value)){
				if($key == "assigned_user_id"){
					array_push($user_ids, $value);
					$user_id = $value;
					$assignedToUserRecords = true;
					$no_of_records_assigned = $no_of_records;
				}
				else{
					array_push($updatedRecords, $key);
				}
			}
		}
		if(!empty($updatedRecords)){
			$fields = implode(",", $updatedRecords);	
		}
		if(!$assignedToUserRecords){
			if(isset($post['mass'])){
				foreach ($post['mass'] as $key => $value) {
					if(!empty($value)){
						$id = $this->getUserId($value);
						if(!in_array($id, $user_ids)){
							array_push($user_ids, $id);	
						}
						if(isset($individual_rec_count[$id]))
							$individual_rec_count[$id]++;
						else{
							$individual_rec_count[$id] = 1;
						}
					}
				}
			}
		}
		foreach ($user_ids as $key => $value) {
			$user = BeanFactory::getBean('Users',$value);
			array_push($emailTo, $user->email1);
			$body = "Hi $user->name,<br><br>";
			if($no_of_records_assigned>0){
				$body .= "You have been assigned to <b>$no_of_records_assigned</b> customer(s) by $current_user_name <br><br>";
			}
			$body .= "Following fields have been updated for <b>" . $individual_rec_count[$value] ." </b>record(s):<br>";
			$count = 1;
			foreach ($updatedRecords as $key => $value) {
				$str1 = $this->getLabelName($value);
				$str2 = $this->getLabelName($post[$value]);
				$body .= "  $count). " . $str1 . " is been updated to ". $str2 ."<br>";	
				$count++;
			}
			$body .= "<br><br>";
			$body .= "You can review this at " . $url;
			$body .= "<br><br><hr>";
			$result = $sendEmail->send_email_to_user("Master Data Mass Update", $body, $emailTo);
			if($result){
				echo "Mass update Email Send Successfully to " . $emailTo;
				$GLOBALS['log']->debug("Notifications: Mass update Email Send Successfully to " . $emailTo);
			}
			else{
				echo "Mass update Email Sending failed to " . $emailTo;
				$GLOBALS['log']->error("Notifications: Mass update Email Send Failed to " . $emailTo);	
			}
			$emailTo = array();
		}		
	}


    protected function action_massupdate(){
    	$seed = loadBean($_REQUEST['module']);
    	$post = $_POST;
    	parent::action_massupdate();
    	$this->massUpdateEmailNotification($post,$seed);
    }

}