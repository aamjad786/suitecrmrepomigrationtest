<?php
require_once 'modules/Opportunities/Opportunity.php';
require_once 'custom/CustomLogger/CustomLogger.php';

class CustomOpportunity extends Opportunity
{

    public function create_export_query($order_by, $where, $relate_link_join='')
    {

        $logger = new CustomLogger('create_export_query_opportunities');
        $logger->log('debug', 'create_export_query_opportunities called at ' . date('Y-M-d H:i:s'));
        
        $custom_join = $this->getCustomJoin(true, true, $where);
        $custom_join['join'] .= $relate_link_join;
        $query = "SELECT
                  opportunities.*,
                  DATE_FORMAT(DATE_ADD(opportunities.date_entered, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date created (d-m-Y)',
                  DATE_FORMAT(DATE_ADD(opportunities.date_modified, INTERVAL 330 minute),'%d-%m-%Y %H:%i:%s') as 'Date modified (d-m-Y)',
                  product_type_c as 'Product Type',eos_disposition_c as 'Disposition',eos_sub_disposition_c as 'Sub-Disposition',
                  accounts.name as account_name,
                  users.user_name as assigned_user_name ,
                  concat(users.first_name,' ',users.last_name) as 'Assigned to' ";
        $query .= $custom_join['select'];
        $query .= " FROM opportunities ";
        $query .= 				"LEFT JOIN users
                                ON opportunities.assigned_user_id=users.id";
        $query .= " LEFT JOIN $this->rel_account_table
                                ON opportunities.id=$this->rel_account_table.opportunity_id
                                LEFT JOIN accounts
                                ON $this->rel_account_table.account_id=accounts.id ";
        $query .= $custom_join['join'];
        $where_auto = "
			($this->rel_account_table.deleted is null OR $this->rel_account_table.deleted=0)
			AND (accounts.deleted is null OR accounts.deleted=0)
			AND opportunities.deleted=0";

        if ($where != "") {
            $query .= "where $where AND ".$where_auto;
        } else {
            $query .= "where ".$where_auto;
        }

        if ($order_by != "") {
            $query .= " ORDER BY opportunities.$order_by";
        } else {
            $query .= " ORDER BY opportunities.name";
        }

        $logger->log('debug', 'create_export_query_opportunities Query: ' .  $query);
        return $query;
    }
    
}
