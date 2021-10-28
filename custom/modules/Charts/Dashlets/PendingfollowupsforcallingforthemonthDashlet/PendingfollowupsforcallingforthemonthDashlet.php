<?php
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

require_once('include/Dashlets/DashletGenericChart.php');

class PendingfollowupsforcallingforthemonthDashlet extends DashletGenericChart {
    
    public $pbss_sales_stages = array();
    
    protected $_seedName = 'Leads';
    
    public function __construct($id, array $options = null) {
        global $timedate;
        
        if (empty($options['title']))
            $options['title'] = translate('LBL_PENDING_FOLLOWUPS_CALLING_FOR_THE_MONTH_TITLE', 'Home');
        
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
        //~ 
        if (!empty($MDataUSER)) {
            foreach ($MDataUSER as $d) {
                
                $data .= '<tr height="20" class="oddListRowS1">';
                $data .= '<td valign="top" align="left" scope="row"><b>' . $d['Name of caller'] . '</b></td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['New leads allocated/ unattempted']) ? 0 : $d['New leads allocated/ unattempted']) . '</td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['Pickup follow-ups']) ? 0 : $d['Pickup follow-ups']) . '</td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['Leads given to CAM follow-ups']) ? 0 : $d['Leads given to CAM follow-ups']) . '</td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['Not contactable']) ? 0 : $d['Not contactable']) . '</td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['Callbacks']) ? 0 : $d['Callbacks']) . '</td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['Follow-ups']) ? 0 : $d['Follow-ups']) . '</td>';
                $data .= '<td valign="top" align="left" scope="row">' . (empty($d['Interested']) ? 0 : $d['Interested']) . '</td>';
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
                            <div align="left" width="100%" style="white-space: normal;">Name of Caller</div></th>        
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">New leads allocated/ unattempted</div></th>        
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">Pickup follow-ups </div> </th>        
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">Leads given to CAM follow-ups </div></th>
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">Not contactable    </div></th>
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">Callbacks</div></th>
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">Follow-ups</div></th>
            <th data-toggle="true" scope="col">
                            <div align="left" width="100%" style="white-space: normal;">Interested</div></th>
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
        } else {
			$data = $this->getDataForManagers($from_date, $to_date, $assigned_user_id);
		}
        
        return $data;
    }
    
    public function getDataForAgents($from_date, $to_date, $assigned_user_id) {
        
        global $db, $current_user;
        
        $query = "SELECT count(l.id) as count, lc.disposition_c, lc.sub_disposition_c 
                FROM leads l JOIN leads_cstm lc ON lc.id_c = l.id 
                WHERE l.deleted = 0
                AND l.assigned_user_id = '$assigned_user_id' 
                AND ((l.date_entered BETWEEN '$from_date' AND '$to_date' AND lc.disposition_c is NULL AND lc.sub_disposition_c is NULL) 
                OR (lc.disposition_c = 'Pickup generation_Appointment' AND lc.sub_disposition_c = 'Pickup assigned to FOS/ Appointment sent to CAM' AND pickup_appointment_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Interested' AND lc.sub_disposition_c = 'Lead generated' AND call_back_date_time_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Not contactable' AND call_back_date_time_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Call_back' AND call_back_date_time_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Follow_up' AND call_back_date_time_c BETWEEN '$from_date' AND '$to_date') 
                OR (lc.disposition_c = 'Interested' AND lc.sub_disposition_c <> 'Lead generated')) group by lc.disposition_c, lc.sub_disposition_c";
        
        $data   = array();
        $result = $db->query($query);
        while ($row = $db->fetchByAssoc($result)) {
            $count                      = $row['count'];
            $disposition_c              = $row['disposition_c'];
            $sub_disposition_c          = $row['sub_disposition_c'];
            $i                          = 0;
            $data[$i]['Name of caller'] = $current_user->full_name;
            
            if ($disposition_c == '' && $sub_disposition_c == '') {
                $data[$i]['New leads allocated/ unattempted'] += $count;
            } else if ($disposition_c == 'Pickup generation_Appointment' && $sub_disposition_c == 'Pickup assigned to FOS/ Appointment sent to CAM') {
                $data[$i]['Pickup follow-ups'] += $count;
            } else if ($disposition_c == 'Interested' && $sub_disposition_c == 'Lead generated') {
                $data[$i]['Leads given to CAM follow-ups'] += $count;
            } else if ($disposition_c == 'Not contactable') {
                $data[$i]['Not contactable'] += $count;
            } else if ($disposition_c == 'Call_back') {
                $data[$i]['Callbacks'] += $count;
            } else if ($disposition_c == 'Follow_up') {
                $data[$i]['Follow-ups'] += $count;
            } else if ($disposition_c == 'Interested' && $sub_disposition_c != 'Lead generated') {
                $data[$i]['Interested'] += $count;
            }
        }
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
        return $data_set;
    }
    
    public function getDataForManagers($from_date, $to_date, $assigned_user_id) {
        
        global $db, $current_user;
        
       $query = "SELECT count(l.id) as count, l.assigned_user_id, lc.disposition_c, lc.sub_disposition_c 
                FROM leads l JOIN leads_cstm lc ON lc.id_c = l.id
                LEFT JOIN users u ON u.id = l.assigned_user_id
                AND u.status = 'Active'
                AND u.deleted =0
                JOIN acl_roles_users aru ON aru.user_id = u.id
                AND aru.deleted =0
                JOIN acl_roles ar ON ar.id = aru.role_id
                AND ar.deleted =0
                AND ar.name = 'Call Center Agent'
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
        return $data_set;
    }
}
