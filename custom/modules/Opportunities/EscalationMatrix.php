<?php
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
class EscalationMatrix {
    public function calculate_escalation_date_time($bean, $events, $arguments) {
        global $db;
        $sales_stage_old      = $bean->fetched_row['sales_stage'];
        $sales_stage_new      = $bean->sales_stage;
        $assigned_user_id_old = $bean->fetched_row['assigned_user_id'];
        $assigned_user_id_new = $bean->assigned_user_id;
        if ((empty($bean->fetched_row) && $bean->sales_stage == 'Open' && !empty($assigned_user_id_new)) || (strcmp($sales_stage_old, $sales_stage_new) == 0 && strcmp($assigned_user_id_new, $assigned_user_id_old) != 0 && $bean->sales_stage == 'Open')  && !empty($assigned_user_id_new)) {
           $query1  = "select em.escalation_hours from scrm_escalation_matrix em where em.name = 'Escalation To Cluster Open' and em.deleted = 0";
            $result1 = $db->query($query1);
            if ($row1 = $db->fetchByAssoc($result1)) {
                $hours_level1 = $row1['escalation_hours'];
            }
            $current_date            = date("Y-m-d H:i:s");
            $escalation_date         = $this->expected_resolution_date($hours_level1, 'hours', $current_date);
            $escalation_date         = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($escalation_date)));
            $user_id                 = $bean->assigned_user_id;
            $escalation_data         = $this->getEscalationUserDetails($user_id);
            $bean->escalation_name_c = $escalation_data[0];
            $bean->escalation_time_c = $escalation_date;
            $bean->escalation_to_c   = implode(",", $escalation_data);
        } else 
        if (strcmp($sales_stage_new, $sales_stage_old) != 0 && $sales_stage_new == 'Submitted') {
            $query1  = "select em.escalation_hours from scrm_escalation_matrix em where em.name = 'Escalation To Cluster Submitted' and em.deleted = 0";
            $result1 = $db->query($query1);
            if ($row1 = $db->fetchByAssoc($result1)) {
                $hours_level1 = $row1['escalation_hours'];
            }
            $current_date            = date("Y-m-d H:i:s");
            $escalation_date         = $this->expected_resolution_date($hours_level1, 'hours', $current_date);
            $escalation_date         = date("Y-m-d H:i:s", strtotime("-5 hours, -30 minutes", strtotime($escalation_date)));
            $bean->escalation_time_c = $escalation_date;
        } else if (strcmp($sales_stage_new, $sales_stage_old) != 0 && $sales_stage_new == 'Disbursed') {
            $bean->escalation_name_c = '';
            $bean->escalation_time_c = '';
            $bean->escalation_to_c   = '';
        }
    }
    public function expected_resolution_date($tat, $unit, $current_date) {
        $current_date = $this->checkOfficeTime($current_date);
        $res_date     = $this->checkHolidayList($current_date);
        $i            = 0;
        while ($tat >= 24) {
            $tat      = $tat - 24;
            $res_date = date("Y-m-d H:i:s", strtotime("+24 hours", strtotime($res_date)));
            echo "<br>";
            $res_date = $this->checkHolidayList($res_date);
        }
        $expected_res_date = date("Y-m-d H:i:s", strtotime("+" . $tat . " " . $unit, strtotime($res_date)));
        $evening_time      = date("Y-m-d 18:59:59", strtotime($res_date));
        while (strtotime($expected_res_date) > strtotime($evening_time)) {
            $date1             = new DateTime($evening_time);
            $date2             = new DateTime($expected_res_date);
            $diff              = $date2->diff($date1);
            $hours             = $diff->h;
            $minutes           = $diff->i;
            $expected_res_date = $this->checkOfficeTime($expected_res_date);
            $expected_res_date = $this->checkHolidayList($expected_res_date);
            $expected_res_date = date("Y-m-d H:i:s", strtotime("+" . $hours . " hours, +" . $minutes . " minutes", strtotime($expected_res_date)));
            $evening_time      = date("Y-m-d 18:59:59", strtotime($expected_res_date));
        }
        return $expected_res_date;
    }
    protected function checkOfficeTime($office_time) {
        $date         = date("Y-m-d", strtotime($office_time));
        $start_time   = date("Y-m-d 00:00:00", strtotime($date));
        $morning_time = date("Y-m-d 09:29:59", strtotime($date));
        $evening_time = date("Y-m-d 18:59:59", strtotime($date));
        $night_time   = date("Y-m-d 23:59:59", strtotime($date));
        if (strtotime($office_time) >= strtotime($start_time) && strtotime($office_time) <= strtotime($morning_time)) {
            $office_time = date("Y-m-d", strtotime($office_time));
            $office_time = date("Y-m-d 09:30:00", strtotime($office_time));
        } else if (strtotime($office_time) >= strtotime($evening_time) && strtotime($office_time) <= strtotime($night_time)) {
            $office_time = date("Y-m-d", strtotime($office_time));
            $office_time = date("Y-m-d 09:30:00", strtotime("+1 day", strtotime($office_time)));
        }
        return $office_time;
    }
    protected function checkHolidayList($d) {
        global $db;
        $date1            = date("Y-m-d H:i:s", strtotime($d));
        $day              = date("D", strtotime($date1));
        $holiday_query    = "select holiday_date from scrm_holidays_list where deleted = 0 order by holiday_date";
        $holiday_result   = $db->query($holiday_query);
        $i                = 0;
        $holiday_calender = array();
        while ($holiday_row = $db->fetchByAssoc($holiday_result)) {
            $holiday_date       = $holiday_row['holiday_date'];
            $holiday_calender[] = date("Y-m-d", strtotime($holiday_date));
        }
        while (1) {
            if ($day == 'Sat' || $day == 'Sun' || in_array($date1, $holiday_calender)) {
                $date1 = date("Y-m-d 09:30:00", strtotime('+1 day', strtotime($date1)));
                $day   = date("D", strtotime($date1));
            } else {
                break;
            }
        }
        return $date1;
    }
    public function getEscalationUserDetails($user_id) {
        global $db;
        $email_query = "SELECT LTRIM(RTRIM(CONCAT(IFNULL(u.first_name,''),' ',IFNULL(u.last_name,'')))) as name,u.id, au.role_id, ea.email_address
                        FROM users u
                        LEFT JOIN acl_roles_users au ON u.id = au.user_id
                        AND au.deleted =0
                        LEFT JOIN email_addr_bean_rel eabr ON eabr.bean_id = u.id
                        AND eabr.deleted =0
                        AND eabr.bean_module =  'Users'
                        LEFT JOIN email_addresses ea ON ea.id = eabr.email_address_id
                        AND ea.deleted =0
                        WHERE u.deleted =0
                        AND u.id = ( 
                        SELECT reports_to_id
                        FROM users
                        WHERE id =  '$user_id'
                        AND deleted =0 )";
        $result      = $db->query($email_query);
        $data        = array();
        if ($row = $db->fetchByAssoc($result)) {
            $data[] = $row['name'];
            $data[] = $row['email_address'];
            $data[] = $row['role_id'];
            $data[] = $row['id'];
        }
        return $data;
    }
}
