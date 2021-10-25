<?php
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGenericChart.php');

class MTDSummaryDashlet extends DashletGenericChart {
    
    public $pbss_sales_stages = array();
    
    protected $_seedName = 'Leads';
    
    public function __construct($id, array $options = null) {
        global $timedate;
        
        if (empty($options['title']))
            $options['title'] = translate('LBL_MTD_SUMMARY_TITLE', 'Home');
        
        parent::__construct($id, $options);
    }
    
    public function displayOptions() {
        global $app_list_strings;
        
        return parent::displayOptions();
    }
    
    public function display() {
        global $current_user, $sugar_config;
        
        
        $MDataUSER = $this->getMatrixData();
        //~ $data = $this->getMatrixData();
        
        
        $data  = '<tbody>';
        $index = 1;
        
        if (!empty($MDataUSER)) {
            foreach ($MDataUSER as $d) {
                
                $data .= '<tr height="20" class="oddListRowS1">';
                $data .= '<td valign="top" align="left" scope="row"><b>' . (empty($d['Held']) ? 0 : $d['Held']) . '</b></td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['total_contacts']) ? 0 : $d['total_contacts']) . '</td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['unique_contacts']) ? 0 : $d['unique_contacts']) . '</td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['pitched']) ? 0 : $d['pitched']) . '</td>';
                $data .= '</tr>';
                
                
                if ($index >= 10)
                    break;
                $index++;
                
            }
            $c = count($MDataUSER);
            
            $pageNumbers = " 1 - <span id='curr_index'>$c</span> of " . ($c) . "+";
        } else {
            $data        = <<<D
       <tr height="25">
            <td><label></lable></td>        
            <td><label></lable></td>
            <td><label></lable></td>
            <td><label></lable></td>
            <td><label></lable></td>        
            <td><label></lable></td>
            </tr>
            
D;
            $pageNumbers = '0 - 0';
            $total_row   = 0;
            $jsMData     = 'empty';
            $c           = 0;
            $total_row   = 0;
            
        }
        
        
        
        
        $module        = 'Leads';
        $action        = 'index';
        $query         = 'true';
        $searchFormTab = 'advanced_search';
        
        $autoRefresh = $this->processAutoRefresh();
        
        
        
        
        
        $chart = <<<EOD
       <input type='hidden' class='module' value='$module' />
        <input type='hidden' class='action' value='$action' />
        <input type='hidden' class='query' value='$query' />
        <input type='hidden' class='searchFormTab' value='$searchFormTab' />
        <input type='hidden' class='userId' value='$login_user_id' /> 
      
       <table width="100%" cellspacing="0" cellpadding="0" border="0" class="list view default" id="dashletPanel">
    <thead>
    
                <tr height="20">   
                  <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">Calls Done</div></th>        
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;"> Total Contacts </div> </th>        
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">Unique Contacts </div></th>
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;"> No of Tele Pitches </div></th>
           
            </tr>
    </thead>
            </tr>
            $data
            </tbody>
            </table>
        </body>      
      
EOD;
        return $chart;
    }
    
    function getMatrixData() {
        
        global $db, $current_user;
        
        $from_date        = date("Y-m-1");
        $to_date          = date("Y-m-d H:i:s", strtotime("+1 month", strtotime($from_date)));
        $from_date        = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($from_date)));
        $to_date          = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($to_date)));
        $assigned_user_id = $current_user->id;
        
        $role = ACLRole::getUserRoleNames($current_user->id);
        if ($role[0] == "Call Center Agent") {
            $data = $this->getDataForAgents($from_date, $to_date, $assigned_user_id);
        } else if ($role[0] == "Call Center Team Leader") {
            $data = $this->getDataForTeamLead($from_date, $to_date, $assigned_user_id);
        }
        
        return $data;
    }
    
    public function getDataForAgents($from_date, $to_date, $assigned_user_id) {
        
        global $db, $current_user;
        
       $query = "SELECT count( c.id ) AS count, c.status, c.parent_id, c.parent_type, c.assigned_user_id, lc.product_pitched_c AS leads_pitched, pc.product_pitched_c AS prospects_pitched
				FROM calls c
				LEFT JOIN leads l ON l.id = c.parent_id
				AND l.deleted =0
				LEFT JOIN leads_cstm lc ON lc.id_c = l.id
				LEFT JOIN prospects p ON p.id = c.parent_id
				AND p.deleted =0
				LEFT JOIN prospects_cstm pc ON pc.id_c = p.id
				WHERE c.deleted =0
				AND c.date_entered
				BETWEEN '$from_date'
				AND '$to_date'
				AND c.assigned_user_id = '$assigned_user_id'
				GROUP BY c.status, c.parent_id, c.parent_type";
        
        $data   = array();
        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)) {
            $count                      = $row['count'];
            $status              = $row['status'];
            $parent_id          = $row['parent_id'];
            $parent_type = $row['parent_type'];
            $leads_pitched = $row['leads_pitched'];
            $prospects_pitched = $row['prospects_pitched'];
            
            $data[$assigned_user_id]['total_contacts'] += $count;
            if($status == 'Held'){
				$data[$assigned_user_id]['Held'] += $count;
			}
			
			if($status == 'Held' && $count == 1 && !in_array($parent_id, $parent_id_list)){
				$unique_contacts++;
				
			} else if(in_array($parent_id, $parent_id_list)){
				$unique_contacts--;
			}
			
			if($parent_type == 'Leads' && $leads_pitched == 'Yes' && !in_array($parent_id, $pitched_id_list)){
				$data[$assigned_user_id]['pitched']++;
			} else if($parent_type == 'Prospects' && $prospects_pitched == 'Yes' && !in_array($parent_id, $pitched_id_list)){
				$data[$assigned_user_id]['pitched']++;
				
			}
			$parent_id_list[] = $parent_id;
			$pitched_id_list[] = $parent_id;
        }
        
        $data[$assigned_user_id]['unique_contacts'] = $unique_contacts;
        
        //~ print_r($data);
        
        return $data;
    }
    
    
    public function getDataForTeamLead($from_date, $to_date, $assigned_user_id) {
        
        global $db, $current_user;
        
        $query = "SELECT count(l.id) as count, l.assigned_user_id, lc.disposition_c, lc.sub_disposition_c 
                FROM leads l JOIN leads_cstm lc ON lc.id_c = l.id
                LEFT JOIN users urs ON urs.id = l.assigned_user_id
                AND urs.status = 'Active'
                AND urs.deleted =0
                JOIN users u ON urs.reports_to_id = u.id
                AND u.deleted =0
                AND u.status = 'Active'
                AND u.id = '$assigned_user_id'
                JOIN acl_roles_users aru ON aru.user_id = u.id
                AND aru.deleted =0
                JOIN acl_roles ar ON ar.id = aru.role_id
                AND ar.deleted =0
                AND ar.name = 'Call Center Team Leader'
                WHERE l.deleted = 0
                AND ((l.date_entered BETWEEN '$from_date' AND '$to_date' AND lc.disposition_c is NULL AND lc.sub_disposition_c is NULL)    
                OR (lc.disposition_c = 'Pickup generation_Appointment' AND lc.sub_disposition_c = 'Pickup assigned to FOS/ Appointment sent to CAM' AND pickup_appointment_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Interested' AND lc.sub_disposition_c = 'Lead generated' AND call_back_date_time_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Not contactable' AND call_back_date_time_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Call_back' AND call_back_date_time_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Follow_up' AND call_back_date_time_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Interested' AND lc.sub_disposition_c <> 'Lead generated')) group by l.assigned_user_id, lc.disposition_c, lc.sub_disposition_c";
        
        $data_set   = array();
        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)) {
            $count             = $row['count'];
            $disposition_c     = $row['disposition_c'];
            $sub_disposition_c = $row['sub_disposition_c'];
            
            if ($user_id != $row['assigned_user_id']) {
                $user_id = $row['assigned_user_id'];
                $user    = new User();
                $user->retrieve($user_id);
                $data_set[$user_id]['assigned_user_id'] = $user_id;
                $data_set[$user_id]['Name of caller'] = $user->full_name;
            }
            
            if ($disposition_c == '' && $sub_disposition_c == '') {
                $data_set[$user_id]['New leads allocated/ unattempted'] += $count;
            } else if ($disposition_c == 'Pickup generation_Appointment' && $sub_disposition_c == 'Pickup assigned to FOS/ Appointment sent to CAM') {
                $data_set[$user_id]['Pickup follow-ups'] += $count;
            } else if ($disposition_c == 'Interested' && $sub_disposition_c == 'Lead generated') {
                $data_set[$user_id]['Leads given to CAM follow-ups'] += $count;
            } else if ($disposition_c == 'Not contactable') {
                $data_set[$user_id]['Not contactable'] += $count;
            } else if ($disposition_c == 'Call_back') {
                $data_set[$user_id]['Callbacks'] += $count;
            } else if ($disposition_c == 'Follow_up') {
                $data_set[$user_id]['Follow-ups'] += $count;
            } else if ($disposition_c == 'Interested' && $sub_disposition_c != 'Lead generated') {
                $data_set[$user_id]['Interested'] += $count;
            }
        }
        $data  = '<tbody>';
        
        foreach($data_set as $d){
        
         $data .= '<tr height="20" class="oddListRowS1">';
                $data .= '<td valign="top" align="left" scope="row"><b>' . $d['Name of caller'] . '</b></td>';
                $data .= '<td valign="top" align="left" scope="row"><a href="index.php?module=Leads&status_advanced=New&assigned_user_id_advanced='.$d['assigned_user_id'].'&start_range_date_entered_advanced='.$from_date.'date_entered_advanced_range_choice=between&end_range_date_entered_advanced='.$to_date.'&query=true&offset=1&searchFormTab=advanced_search">' . (empty($d['New leads allocated/ unattempted']) ? 0 : $d['New leads allocated/ unattempted']) . '</a></td>';
                $data .= '<td valign="top" align="left" scope="row"><a href="index.php?module=Leads&disposition_c_advanced[]=Pickup generation_Appointment&sub_disposition_c_advanced[]=Pickup assigned to FOS/ Appointment sent to CAM&assigned_user_id_advanced='.$d['assigned_user_id'].'&start_pickup_appointment_c_advanced='.$from_date.'pickup_appointment_c_advanced_range_choice=between&end_range_pickup_appointment_c_advanced='.$to_date.'&query=true&offset=1&searchFormTab=advanced_search">' . (empty($d['Pickup follow-ups']) ? 0 : $d['Pickup follow-ups']) . '</a></td>';
                $data .= '<td valign="top" align="left" scope="row"><a href="index.php?module=Leads&disposition_c_advanced[]=Interested&sub_dispsition_c_advanced[]=Lead generated&assigned_user_id_advanced='.$d['assigned_user_id'].'&start_range_call_back_date_time_c_advanced='.$from_date.'call_back_date_time_c_advanced_range_choice=between&end_range_call_back_date_time_c_advanced='.$to_date.'&query=true&offset=1&searchFormTab=advanced_search">' . (empty($d['Leads given to CAM follow-ups']) ? 0 : $d['Leads given to CAM follow-ups']) . '</a></td>';
                $data .= '<td valign="top" align="left" scope="row"><a href="index.php?module=Leads&disposition_c_advanced[]=Not contactable&assigned_user_id_advanced='.$d['assigned_user_id'].'&start_range_call_back_date_time_c_advanced='.$from_date.'call_back_date_time_c_advanced_range_choice=between&end_range_call_back_date_time_c_advanced='.$to_date.'&query=true&offset=1&searchFormTab=advanced_search">' . (empty($d['Not contactable']) ? 0 : $d['Not contactable']) . '</a></td>';
                $data .= '<td valign="top" align="left" scope="row"><a href="index.php?module=Leads&disposition_c_advanced[]=Call_back&assigned_user_id_advanced='.$d['assigned_user_id'].'&start_range_call_back_c_advanced='.$from_date.'call_back_c_advanced_range_choice=between&end_range_call_back_c_advanced='.$to_date.'&query=true&offset=1&searchFormTab=advanced_search">' . (empty($d['Callbacks']) ? 0 : $d['Callbacks']) . '</a></td>';
                $data .= '<td valign="top" align="left" scope="row"><a href="index.php?module=Leads&disposition_c_advanced=Follow_up&assigned_user_id_advanced='.$d['assigned_user_id'].'&start_range_call_back_c_advanced='.$from_date.'call_back_c_advanced_range_choice=between&end_range_call_back_c_advanced='.$to_date.'&query=true&offset=1&searchFormTab=advanced_search">' . (empty($d['Follow-ups']) ? 0 : $d['Follow-ups']) . '</a></td>';
                $data .= '<td valign="top" align="left" scope="row"><a href="index.php?module=Leads&disposition_c_advanced=Interested&sub_dispsition_c_advanced[]=Lead generated&assigned_user_id_advanced='.$d['assigned_user_id'].'&start_range_call_back_c_advanced='.$from_date.'call_back_date_time_c_advanced_range_choice=between&end_range_call_back_date_time_c_advanced='.$to_date.'&query=true&offset=1&searchFormTab=advanced_search">' . (empty($d['Interested']) ? 0 : $d['Interested']) . '</a></td>';
                $data .= '</tr>';   
	}  
        return $data;
    }
    
}
