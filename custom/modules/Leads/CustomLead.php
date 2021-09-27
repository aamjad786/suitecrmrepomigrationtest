<?php
require_once 'modules/Leads/Lead.php';
require_once 'custom/CustomLogger/CustomLogger.php';

class CustomLead extends Lead
{
    function create_export_query($order_by, $where, $relate_link_join = '')
    {
        $logger = new CustomLogger('create_export_query_leads');
        $logger->log('debug', 'create_export_query_leads called at ' . date('Y-M-d H:i:s'));
  

        $custom_join = $this->custom_fields->getJOIN(true, true, $where);

        // For easier code reading, reused plenty of time
        $table = $this->table_name;

        if ($custom_join) {
            $custom_join['join'] .= $relate_link_join;
        }
        $query = "select $table.*,DATE_FORMAT(DATE_ADD($table.date_entered, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date created (d-m-Y)', DATE_FORMAT(DATE_ADD($table.date_modified, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date Modified (d-m-Y)' ,opportunities_cstm.application_id_c as 'App Id',concat(users.first_name,' ',users.last_name) as'Assigned to'";
        if ($custom_join) {
            $query .= $custom_join['select'];
        }

        $query .= " FROM $table ";


        $query .= "LEFT JOIN users
             ON $table.assigned_user_id=users.id left join opportunities_cstm on $table.opportunity_id=opportunities_cstm.id_c";


        //Join email address table too.
        //$query .=  " LEFT JOIN email_addr_bean_rel on $table.id = email_addr_bean_rel.bean_id and email_addr_bean_rel.bean_module = '" . $this->module_dir . "' and email_addr_bean_rel.deleted = 0 and email_addr_bean_rel.primary_address = 1";
        //$query .=  " LEFT JOIN email_addresses on email_addresses.id = email_addr_bean_rel.email_address_id ";

        if ($custom_join) {
            $query .= $custom_join['join'];
        }

        $where_auto = " $table.deleted=0 ";

        if ($where != "") {
            $query .= "WHERE ($where) AND " . $where_auto;
        } else {
            $query .= "WHERE " . $where_auto;
        }

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query .= ' ORDER BY ' . $order_by;
        }
        
        $logger->log('debug', 'create_export_query_leads Query: ' . $query);


        return $query;
    }
}
