<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
require_once('include/entryPoint.php');

class Disposition
{

    static $run = false;

    function saveDisposition($bean, $event, $arguments)
    {
        global $db;
        global $current_user;

        $ID                = $bean->id;
        $disposition_c     = $bean->disposition_c;
        $sub_disposition_c = $bean->sub_disposition_c;
        // $remarks_c = $bean->remarks_c;

        if (($disposition_c == "Interested" && $sub_disposition_c == "Lead generated") || ($disposition_c == 'Pickup generation_Appointment')) {
            $db->query("UPDATE `leads_cstm` SET lead_type_l_c='Hot' WHERE`id_c`='$ID'");
        } elseif ($disposition_c == "Interested" && ($sub_disposition_c == "Changed mind" || $sub_disposition_c == "Bought from competition" || $sub_disposition_c == "Postponing pickup continously")) {
            $db->query("UPDATE `leads_cstm` SET lead_type_l_c='Cold' WHERE`id_c`='$ID'");
        }

        $old_sub_disposition_c     = $bean->fetched_row['sub_disposition_c'];
        $disposition_c         = $bean->disposition_c;
        $sub_disposition_c     = $bean->sub_disposition_c;
        $check_disposition_c   = $bean->check_disposition_c;
        $agent_id              = $bean->assigned_user_id;

        if ($check_disposition_c == '1') {

            $lead = new scrm_Disposition_History();

            if (($disposition_c == 'Call_back') || ($disposition_c == 'Not contactable') || ($disposition_c == 'Follow_up') || ($disposition_c == 'Interested')) {

                if ($sub_disposition_c == 'Lead generated') {
                    $call_back_date_time_c = $bean->pickup_appointment_date_time_c;
                    $db->query("UPDATE `leads_cstm` SET call_back_date_time_c = '$call_back_date_time_c' WHERE`id_c`='$ID'");
                }

                $lead->call_pickup_datetime_c = $call_back_date_time_c;
            }

            if ((($disposition_c != 'Interested') && ($sub_disposition_c != 'Lead generated')) || ($disposition_c != 'Pickup generation_Appointment')) {
                $db->query("UPDATE `leads_cstm` SET check_disposition_c='0' WHERE`id_c`='$ID'");
            }

            $lead->disposition_c     = $disposition_c;
            $lead->sub_disposition_c = $sub_disposition_c;
            $lead->assigned_user_id  = $agent_id;
            // $lead->remarks_c  = $remarks_c;
            $lead->save();
            $bean->load_relationship('leads_scrm_disposition_history_1');
            $bean->leads_scrm_disposition_history_1->add($lead->id);
        }
    }
}
