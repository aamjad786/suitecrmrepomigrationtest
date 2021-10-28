<?php
array_push($job_strings, 'salesTargetCalculationForCamJob');

require_once 'custom/include/JTree.php';
require_once 'custom/CustomLogger/CustomLogger.php';

function salesTargetCalculationForCamJob() { 
    
    $logger = new CustomLogger('SalesTargetCalculationForCamJob');
    $logger->log('debug', "***********SalesTargetCalculationForCamJob Started******************");
 
    global $db;
    $level	= 1;
    $visits = 1;
    $data_set   = array();

    $from_date  = date("Y-m-01 00:00:00");
    $month      = date("F",strtotime($from_date));
    $month_year = date("FY",strtotime($from_date));
    $to_date    = date("Y-m-d H:i:s", strtotime("+1 month", strtotime($from_date)));
    

    //Fetch CAM Users List
    
    if($visits){
        $cam_query = "
            SELECT 
                u.id,
                LTRIM(RTRIM(CONCAT(IFNULL(u.first_name, ''),' ',IFNULL(u.last_name, '')))) AS name,
                uc.targets_achieved_c AS target_number,
                u.user_name,
                uc.target_amount_c AS sales_target
            FROM
                users u
                    LEFT JOIN
                users_cstm uc ON uc.id_c = u.id
            WHERE
                u.id IN (SELECT DISTINCT
                        (assigned_user_id)
                    FROM
                        opportunities o
                            LEFT JOIN
                        opportunities_cstm ocstm ON o.id = ocstm.id_c
                    WHERE
                        ocstm.date_funded_c BETWEEN '$from_date' AND '$to_date'
                            AND assigned_user_id != 'null' 
                            UNION (SELECT DISTINCT
                                    (assigned_user_id)
                                FROM
                                    meetings m
                                        LEFT JOIN
                                    meetings_cstm mcstm ON m.id = mcstm.id_c
                                WHERE
                                    !( mcstm.nature_of_visit_c IS NULL OR mcstm.nature_of_visit_c = '')
                                )
                        )
                    ";
	}else{
		 $cam_query = "
            SELECT 
                u.id,
                LTRIM(RTRIM(CONCAT(IFNULL(u.first_name, ''),' ',IFNULL(u.last_name, '')))) AS name,
                uc.targets_achieved_c AS target_number,
                u.user_name,
                uc.target_amount_c AS sales_target
            FROM
                users u
                    LEFT JOIN
                users_cstm uc ON uc.id_c = u.id
            WHERE
                u.id IN (SELECT DISTINCT
                        (assigned_user_id)
                    FROM
                        opportunities o
                            LEFT JOIN
                        opportunities_cstm ocstm ON o.id = ocstm.id_c
                    WHERE
                        ocstm.date_funded_c BETWEEN '$from_date' AND '$to_date'
                            AND assigned_user_id != 'null' )
            ";
	}
	
    $logger->log('debug', "Select CAM Query: $cam_query");
	
    $cam_result = $db->query($cam_query);
	
	while($cam_row = $db->fetchByAssoc($cam_result)){
        
        $userid = $cam_row['id'];

        $data_set[$userid]['month']            = $month;
        $data_set[$userid]['user_profile_id']  = $userid;
        $data_set[$userid]['assigned_user_id'] = $cam_row['user_name'];
        $data_set[$userid]['name']             = $cam_row['name'];
        $data_set[$userid]['target']           = $cam_row['target_number'];
        $data_set[$userid]['sales_target']     = $cam_row['sales_target'];
        $data_set[$userid]['user_type']        = "CAM";
        $data_set[$userid]['done']             = 0;
        $data_set[$userid]['cases_logged_in']  = 0;
        
  
	}

	$target_query = "
        SELECT 
            sales_target.*, users.id AS user_id
        FROM
            sales_target
                JOIN
            users ON sales_target.emp_id = users.user_name
        WHERE
            month_code = '$month_year'
    ";

	$logger->log('debug', "Select Target Query: $target_query");

	$target_res = $db->query($target_query);

	while ($target_row = $db->fetchByAssoc($target_res)) {

		$userid=$target_row['user_id'];

		if(array_key_exists($userid, $data_set)){

			$target = $target_row['disbursal_target'];
			$data_set[$userid]['disbursal_target']=$target_row['disbursal_target'];
			$data_set[$userid]['login_target']=$target_row['login_target'];
			
            $logger->log('debug',"Updated data for $userid with $target");
		}
		// var_dump($target_row);
	}

   $opp_query  = "
        SELECT 
            COUNT(o.id) AS count,
            o.sales_stage,
            oc.opportunity_status_c,
            oc.dsa_code_c,
            SUM(oc.loan_amount_c) AS loan,
            SUM(o.amount) AS amount,
            o.assigned_user_id,
            u.id AS ci_assigned_user_id,
            oc.loan_amount_sanctioned_c AS cases_sanctioned_amount,
            LTRIM(RTRIM(CONCAT(IFNULL(u.first_name, ''),' ',IFNULL(u.last_name, '')))) AS name,
            uc.targets_achieved_c AS target_number,
            uc.target_amount_c AS sales_target,
            SUM(oc.insurance_c) AS insurance,
            SUM(oc.APR_c) AS APR,
            SUM(oc.processing_fees_c) AS processing_fees
        FROM
            opportunities o
                JOIN
            opportunities_cstm oc ON o.id = oc.id_c
                LEFT JOIN
            users u ON u.id = o.assigned_user_id
                AND u.deleted = 0
                LEFT JOIN
            users_cstm uc ON uc.id_c = u.id
        WHERE
            o.deleted = 0
                AND oc.date_funded_c BETWEEN '$from_date' AND '$to_date'
                AND application_id_c < 50000000
        GROUP BY o.assigned_user_id , oc.dsa_code_c , o.sales_stage

   ";
                    
    $logger->log('debug', "Opportunity Query: $opp_query");
		
    $opp_result = $db->query($opp_query);

    
    $userid=1;
    $data_set[$userid]=array();
    $data_set[$userid]['month']            = $month;
    $data_set[$userid]['user_profile_id']  = 1;
    $data_set[$userid]['assigned_user_id'] = 1;
    $data_set[$userid]['name']             = 'Admin';
    $data_set[$userid]['user_type']        = "CAM";
    
    $step_i=0;
    $total_amount =0;

    while ($opp_row = $db->fetchByAssoc($opp_result)) {
    
        $user_id = $opp_row['ci_assigned_user_id'];
        $dsa_code_c = $opp_row['dsa_code_c'];

        if(empty($user_id)){$user_id=1;$userid=1;}

        $APR=$opp_row['APR'];
        $insurance=$opp_row['insurance'];
        $processing_fees=$opp_row['processing_fees'];

        if(!empty($APR)){
        	$data_set[$user_id]['APR']=$APR;
        }

        if(!empty($insurance)){
        	$data_set[$user_id]['insurance']=$insurance;
        }

        if(!empty($processing_fees)){
        	$data_set[$user_id]['processing_fees']=$processing_fees;
        }

        if(!empty($dsa_code_c) && !in_array($dsa_code_c, $data_set[$user_id]["dsa_code"])) {
			$data_set[$user_id]["dsa_code"][] = $dsa_code_c;
			$data_set[$user_id]["active_dsa"] += 1;
		}
        
		$count = $opp_row['count'];
		
		$amount = $opp_row['amount'];
		$total_amount += $amount;
		$step_i++;

		$logger->log('debug', "$step_i.user id = $user_id,amount=$amount,total_amount=$total_amount,opp_count=$count");

		if(!empty($opp_row['status'])){
			$data_set[$user_id]['cases_attended'] += $opp_row['count'];
		}
		if ($amount > 0) {
            $data_set[$user_id]['achieved'] += $opp_row['count'];
            $data_set[$user_id]['target_amount_achieved'] += $opp_row['amount'];

            if(!empty($dsa_code_c)){
				$data_set[$user_id]['dsa_cases_disbursed'] += $opp_row['count'];
				$data_set[$user_id]['dsa_cases_disbursed_amount'] += $opp_row['amount'];
			}
        }
        if ($opp_row['cases_sanctioned_amount']>0) {
            $data_set[$user_id]['cases_sanctioned'] += $opp_row['count'];
            $data_set[$user_id]['cases_sanctioned_amount'] += $opp_row['cases_sanctioned_amount'];
            
            if(!empty($dsa_code_c)){
				$data_set[$user_id]['dsa_cases_sanctioned'] += $opp_row['count'];
				$data_set[$user_id]['dsa_cases_sanctioned_amount'] += $opp_row['cases_sanctioned_amount'];
			}
            
        }
        if ($opp_row['insurance']>0) {
        	$data_set[$user_id]['no_of_dsa_assigned'] += 1; //treating as insurance count
        	 $data_set[$user_id]['insurance'] += $opp_row['insurance'];
        }
        if ($opp_row['APR']>0) {
        	 $data_set[$user_id]['APR'] += $opp_row['APR'];
        }
        if ($opp_row['processing_fees']>0) {
        	 $data_set[$user_id]['processing_fees'] += $opp_row['processing_fees'];
        }
	}
	$opp_query  = "
        SELECT 
            COUNT(o.id) AS count,
            o.sales_stage,
            oc.opportunity_status_c,
            oc.dsa_code_c,
            SUM(oc.loan_amount_c) AS loan,
            SUM(o.amount) AS amount,
            o.assigned_user_id,
            u.id AS ci_assigned_user_id,
            oc.loan_amount_sanctioned_c AS cases_sanctioned_amount,
            LTRIM(RTRIM(CONCAT(IFNULL(u.first_name, ''),' ',IFNULL(u.last_name, '')))) AS name,
            uc.targets_achieved_c AS target_number,
            uc.target_amount_c AS sales_target
        FROM
            opportunities o
                JOIN
            opportunities_cstm oc ON o.id = oc.id_c
                LEFT JOIN
            users u ON u.id = o.assigned_user_id
                AND u.deleted = 0
                LEFT JOIN
            users_cstm uc ON uc.id_c = u.id
        WHERE
            o.deleted = 0
                AND o.date_entered BETWEEN '$from_date' AND '$to_date'
                AND application_id_c < 50000000
        GROUP BY o.assigned_user_id , oc.dsa_code_c , o.sales_stage;
    ";

    $logger->log('debug', "Second Opp query: $opp_query");
    $opp_result = $db->query($opp_query);
    $logger->log('debug', var_export($opp_result,true));
    
    while ($opp_row = $db->fetchByAssoc($opp_result)) {

        $user_id = $opp_row['ci_assigned_user_id'];
        $dsa_code_c = $opp_row['dsa_code_c'];
        if(empty($user_id)){$user_id=1;$userid=1;}

        try {
            $data_set[$user_id]['cases_logged_in'] += $opp_row['count'];
            $data_set[$user_id]['cases_wip'] += $opp_row['count'];
            $data_set[$user_id]['cases_wip_amount'] += $opp_row['loan'];
            $data_set[$user_id]['cases_login_amount'] += $opp_row['loan'];
            if ($opp_row['sales_stage'] == 'Credit') {
                $data_set[$user_id]['credit_count'] += $opp_row['count'];
                $data_set[$user_id]['credit_amount'] += $opp_row['loan'];

                if (!empty($dsa_code_c)) {
                    $data_set[$user_id]['dsa_cases_wip'] += $opp_row['count'];
                    $data_set[$user_id]['dsa_cases_wip_amount'] += $opp_row['loan'];
                }
            } else if ($opp_row['sales_stage'] == 'Sent To Finance') {
                $data_set[$user_id]['sent_to_finance_amount'] += $opp_row['loan'];
                $data_set[$user_id]['sent_to_finance_count'] += $opp_row['count'];
            } else if ($opp_row['sales_stage'] == 'Credit_Rejected' || $opp_row['sales_stage'] == 'Rejected') {
                $data_set[$user_id]['cases_dropped'] += $opp_row['count'];
            } else if ($opp_row['sales_stage'] == 'Ops Rejected') {
                $data_set[$user_id]['ops_rejected_amount'] += $opp_row['loan'];
                $data_set[$user_id]['ops_rejected_count'] += $opp_row['count'];
            }
        } catch (Exception $e) {
            $logger->log('error', "Exception occured " . $e->getMessage());
        }
		// fwrite($myfile,print_r($opp_row['dsa_code_c'],true));
    }
    // fwrite($myfile,"\nSecond query caluculation done".print_r($data_set,true));
    if($visits){

	    $visits_query  = "
            SELECT 
                COUNT(m.id) AS count,
                mcstm.nature_of_visit_c,
                u.id
            FROM
                meetings m
                    LEFT JOIN
                meetings_cstm mcstm ON m.id = mcstm.id_c
                    LEFT JOIN
                users u ON u.id = m.assigned_user_id
                    AND u.deleted = 0
                    LEFT JOIN
                users_cstm uc ON uc.id_c = u.id
            WHERE
                m.deleted = 0
                    AND !( mcstm.nature_of_visit_c IS NULL
                    OR mcstm.nature_of_visit_c = '')
                    AND m.date_entered BETWEEN '$from_date' AND '$to_date'
            GROUP BY mcstm.nature_of_visit_c , m.assigned_user_id

        ";
	                    
	    $opp_result = $db->query($visits_query);

	    while ($opp_row = $db->fetchByAssoc($opp_result)) {
	    	$user_id = $opp_row['id'];
	    	if($opp_row['nature_of_visit']=='Customer'){
	    		$data_set[$user_id]['customer_visit'] += $opp_row['count'];
	    	}else if($opp_row['nature_of_visit']=='RA' || $opp_row['nature_of_visit']=='Alliance'){
	    		$data_set[$user_id]['channel_visit'] += $opp_row['count'];
	    	}else{

	    	}
	    }
	    
	}

    $jTree = new JTree();
    $user_id = 'A6B9E7C9-A8C1-4297-836F-6FE7BD1187BD';	//PK
    // $user_id = '3731FB72-744E-47DE-9578-5793B8B60A58'; //Gowthami
    $nodeCal = new NodeCal($data_set);
    $node_arr = $nodeCal->createNodeArray($user_id,$month,0);
    $parent = $jTree->createNode($node_arr);
    $nodeCal->createChildren($jTree,$parent,$user_id,$month,1);
    // $nodeCal->printTree($jTree,$parent);
    $nodeCal->sumTree($jTree,$parent);
    // $nodeCal->printTree($jTree,$parent);
    $data_set = ($nodeCal->getData());
    // fwrite($myfile,print_r($data_set,true));

    foreach ($data_set as $data) {
        $user_id = $data['user_profile_id'];
         if(empty($user_id))continue;
        // if(empty($data['month']))continue;
        $th      = new scrm_Targets_History();
        $query   = "select id from scrm_targets_history t where t.user_profile_id = '$user_id' and t.deleted = 0 and t.month = '$month' AND t.lead_source is NULL";
        $result  = $db->query($query);
        if ($row = $db->fetchByAssoc($result)) {
            $th_id = $row['id'];
            $th->retrieve($th_id);
        }
        // fwrite($myfile,"\nsaving data for user".$data['name']);
        $th->name                   = $data['name'];
        $th->assigned_user_id       = $data['assigned_user_id'];
        $th->month                  = $data['month'];
        $th->user_profile_id        = $data['user_profile_id'];
        $th->target                 = (empty($data['target']) ? 0 : $data['target']);
        $th->sales_target           = (empty($data['sales_target']) ? 0 : $data['sales_target']);
        $th->achieved               = (!isset($data['achieved']) ? 0 : $data['achieved']);
        $th->target_amount_achieved = (!isset($data['target_amount_achieved']) ? 0 : $data['target_amount_achieved']);
        
        $th->cases_sanctioned       = (!isset($data['cases_sanctioned']) ? 0 : $data['cases_sanctioned']);
        $th->cases_sanctioned_amount= (!isset($data['cases_sanctioned_amount']) ? 0 : $data['cases_sanctioned_amount']);
        $th->cases_wip              = (!isset($data['cases_wip']) ? 0 : $data['cases_wip']);
        $th->cases_wip_amount       = (!isset($data['cases_wip_amount']) ? 0 : $data['cases_wip_amount']);
        
        $th->cases_picked_up        = (!isset($data['cases_picked_up']) ? 0 : $data['cases_picked_up']);
        $th->cases_logged_in        = (!isset($data['cases_logged_in']) ? 0 : $data['cases_logged_in']);
        //$th->cases_logged_in += $th->achieved;

        $th->cases_dropped          = (!isset($data['cases_dropped']) ? 0 : $data['cases_dropped']);
        $th->cases_attended          = (!isset($data['cases_attended']) ? 0 : $data['cases_attended']);
        $th->cases_login_amount     = (!isset($data['cases_login_amount']) ? 0 : $data['cases_login_amount']);
        $th->cases_login_amount += $th->target_amount_achieved;
        
        //DSA fields
        $th->no_of_dsa_assigned     = (!isset($data['no_of_dsa_assigned']) ? 0 : $data['no_of_dsa_assigned']);
        $th->active_dsa     = (!isset($data['active_dsa']) ? 0 : $data['active_dsa']);
        $th->dsa_cases_logged_in     = (!isset($data['dsa_cases_logged_in']) ? 0 : $data['dsa_cases_logged_in']);
        $th->dsa_cases_login_amount     = (!isset($data['dsa_cases_login_amount']) ? 0 : $data['dsa_cases_login_amount']);
        
        $th->dsa_cases_sanctioned     = (!isset($data['dsa_cases_sanctioned']) ? 0 : $data['dsa_cases_sanctioned']);
        $th->dsa_cases_sanctioned_amount     = (!isset($data['dsa_cases_sanctioned_amount']) ? 0 : $data['dsa_cases_sanctioned_amount']);
        $th->dsa_cases_disbursed     = (!isset($data['dsa_cases_disbursed']) ? 0 : $data['dsa_cases_disbursed']);
        $th->dsa_cases_disbursed_amount     = (!isset($data['dsa_cases_disbursed_amount']) ? 0 : $data['dsa_cases_disbursed_amount']);
        $th->dsa_cases_wip     = (!isset($data['dsa_cases_wip']) ? 0 : $data['dsa_cases_wip']);
        $th->dsa_cases_wip_amount     = (!isset($data['dsa_cases_wip_amount']) ? 0 : $data['dsa_cases_wip_amount']);
        $th->target_amount_pending = (!isset($data['customer_visit']) ? 0 : $data['customer_visit']);
        $th->value_disbursed = (!isset($data['channel_visit']) ? 0 : $data['channel_visit']);
        $th->insurance_amount = (!isset($data['insurance']) ? 0 : $data['insurance']);
        $th->APR = (!isset($data['APR']) ? 0 : $data['APR']);
        $th->processing_fees = (!isset($data['processing_fees']) ? 0 : $data['processing_fees']);

        $th->sent_to_finance_amount = (!isset($data['sent_to_finance_amount']) ? 0 : $data['sent_to_finance_amount']);
        $th->sent_to_finance_count = (!isset($data['sent_to_finance_count']) ? 0 : $data['sent_to_finance_count']);
        $th->credit_count = (!isset($data['credit_count']) ? 0 : $data['credit_count']);
        $th->credit_amount = (!isset($data['credit_amount']) ? 0 : $data['credit_amount']);
        $th->ops_rejected_amount = (!isset($data['ops_rejected_amount']) ? 0 : $data['ops_rejected_amount']);
        $th->ops_rejected_count = (!isset($data['ops_rejected_count']) ? 0 : $data['ops_rejected_count']);

        $th->disbursal_target = (!isset($data['disbursal_target']) ? 0 : $data['disbursal_target']);
         $th->login_target = (!isset($data['login_target']) ? 0 : $data['login_target']);
        // if($th->disbursal_target>0){
        // 	fwrite($myfile,)
        // }
        
        $th->user_type              = $data['user_type'];
        $th->description = $data['description'];
        // $th->level = $data['level'];

        $th->save();
    }
    
    $logger->log('debug', "***********finishing here******************");
    

    return true;
}


class NodeCal{

	public function __construct($data_set) {
        $this->_data_set = $data_set;
        $this->logger = new CustomLogger('SalesTargetCalculationForCamJob');
    }
    function getData(){
        return $this->_data_set;
    }
	function createChildren($tree,$parent,$user_id,$month,$level){
    	global $db;
    	$query = "select id from users where reports_to_id='$user_id' and deleted=0";
    	$results = $db->query($query);
    	// fwrite($this->myfile,print_r($results,true));
    	while($row = $db->fetchByAssoc($results)) {
    		$nodeArr = $this->createNodeArray($row['id'],$month,$level);
    		$child = $tree->createNode($nodeArr);
    		echo "<br/>".$row['id'];
    		$tree->addChild($parent,$child);
    		$this->createChildren($tree,$child,$row['id'],$month,$level+1);

    	}
    }


    function sumTree($tree,$head){
    	    	
    	if(empty($head))return;
        if($tree->getNode($head)->childrenCount()==0)return;
        $children = $tree->getChildren($head);
        // fwrite($myfile,print_r($children,true));
        $target = 0;
        $parentNode = $tree->getNode($head);
        $parentNodeValue = $tree->getValue($head);
        $parent_id = $parentNodeValue['user_profile_id'];
        foreach($children as $child){
                $this->sumTree($tree,$child);
                $childNode = $tree->getNode($child);
                $childNodeValue = $tree->getValue($child);
                $parentNodeValue['target'] += $childNodeValue['target'];
                $parentNodeValue['sales_target'] += $childNodeValue['sales_target'];
                $parentNodeValue['achieved'] += $childNodeValue['achieved'];
                $parentNodeValue['target_amount_achieved'] += $childNodeValue['target_amount_achieved'];
                $parentNodeValue['cases_sanctioned'] += $childNodeValue['cases_sanctioned'];
                $parentNodeValue['cases_sanctioned_amount'] += $childNodeValue['cases_sanctioned_amount'];
                $parentNodeValue['cases_wip'] += $childNodeValue['cases_wip'];
                $parentNodeValue['cases_wip_amount'] += $childNodeValue['cases_wip_amount'];
                $parentNodeValue['cases_picked_up'] += $childNodeValue['cases_picked_up'];
                $parentNodeValue['cases_logged_in'] += $childNodeValue['cases_logged_in'];
                $parentNodeValue['cases_login_amount'] += $childNodeValue['cases_login_amount'];
                $parentNodeValue['cases_dropped'] += $childNodeValue['cases_dropped'];
                $parentNodeValue['active_dsa'] += $childNodeValue['active_dsa'];
                $parentNodeValue['no_of_dsa_assigned'] += $childNodeValue['no_of_dsa_assigned'];
                $parentNodeValue['dsa_cases_logged_in'] += $childNodeValue['dsa_cases_logged_in'];
                $parentNodeValue['dsa_cases_login_amount'] += $childNodeValue['dsa_cases_login_amount'];
                $parentNodeValue['dsa_cases_sanctioned'] += $childNodeValue['dsa_cases_sanctioned'];
                $parentNodeValue['dsa_cases_sanctioned_amount'] += $childNodeValue['dsa_cases_sanctioned_amount'];
                $parentNodeValue['dsa_cases_disbursed'] += $childNodeValue['dsa_cases_disbursed'];
                $parentNodeValue['dsa_cases_disbursed_amount'] += $childNodeValue['dsa_cases_disbursed_amount'];
                $parentNodeValue['dsa_cases_wip'] += $childNodeValue['dsa_cases_wip'];
                $parentNodeValue['dsa_cases_wip_amount'] += $childNodeValue['dsa_cases_wip_amount'];
                $parentNodeValue['channel_visit'] += $childNodeValue['channel_visit'];
                $parentNodeValue['customer_visit'] += $childNodeValue['customer_visit'];
                $parentNodeValue['sent_to_finance_count'] += $childNodeValue['sent_to_finance_count'];
                $parentNodeValue['sent_to_finance_amount'] += $childNodeValue['sent_to_finance_amount'];
                $parentNodeValue['credit_amount'] += $childNodeValue['credit_amount'];
                $parentNodeValue['credit_count'] += $childNodeValue['credit_count'];
                $parentNodeValue['ops_rejected_amount'] += $childNodeValue['ops_rejected_amount'];
                $parentNodeValue['ops_rejected_count'] += $childNodeValue['ops_rejected_count'];
                $parentNodeValue['insurance'] += $childNodeValue['insurance'];
                $parentNodeValue['processing_fees'] += $childNodeValue['processing_fees'];
                $parentNodeValue['APR'] += $childNodeValue['APR'];
                $parentNodeValue['disbursal_target'] += $childNodeValue['disbursal_target'];
                $parentNodeValue['login_target'] += $childNodeValue['login_target'];

                $child_id =  $childNodeValue['user_profile_id'];
                $childNodeValue['description'] = "Calculated for parent id $parent_id";
                $childNode->setValue($childNodeValue);
        		$this->_data_set[$child_id] = $childNodeValue;

               
        }
        if($parentNodeValue['cases_logged_in']>0)
        $this->logger->log('debug', "After saving logged in =".$parentNodeValue['cases_logged_in']." for user ".$parentNodeValue['name']." id= ".$parentNodeValue['user_profile_id']);
        if($parentNodeValue['name']=="")
        $this->logger->log('debug', print_r($parentNodeValue,true));
        $parentNode->setValue($parentNodeValue);
        $this->_data_set[$parent_id] = $parentNodeValue;
    }

    function printNodeValue($nodeValue){
    	return $nodeValue['name']. " ".$nodeValue['disbursal_target'] ;
    }

    function printTree($tree,$head){
    	

        $this->logger->log('debug', "<ul><li>".$this->printNodeValue($tree->getValue($head)));
	        $children = $tree->getChildren($head);
	        foreach($children as $child){
	                $this->printTree($tree,$child);
	        }
	        $this->logger->log('debug', "</li></ul>");
	}

	function createNodeArray($user_id,$month,$level){
        $data_set = $this->_data_set;
        
        if(empty($data_set[$user_id])){
            $data_set[$user_id]=array();
            $user          = new User();
            $query = "users.id='$user_id'";
            $items = $user->get_full_list('',$query);
            $user = $items[0];
            // $user->retrieve($user_id);
            // global $month;
            // $month      = //date("F");
            $data_set[$user_id]['name']             = $user->full_name;
            $data_set[$user_id]['month']            = $month;
            $data_set[$user_id]['user_profile_id']  = $user_id;
            $data_set[$user_id]['assigned_user_id'] = $user->user_name;
            $data_set[$user_id]['user_type']        = "CM-$level";
            $data_set[$user_id]['done'] = 0;
            $data_set[$user_id]['cases_wip'] = 0;
            $data_set[$user_id]['target'] = 0;
            $data_set[$user_id]['channel_visit']=0;
            $data_set[$user_id]['customer_visit']=0;
            $data_set[$user_id]['cases_logged_in']=0;
        }
        // fwrite($this->myfile,"\nuser id = $user_id , created data = ".print_r($data_set[$user_id],true));
        // $this->_data_set[$user_id]['description'] = 'calculated';
         return $data_set[$user_id];
        
    }
}

// salesTargetCalculationForCamJob();

// echo "at the end";