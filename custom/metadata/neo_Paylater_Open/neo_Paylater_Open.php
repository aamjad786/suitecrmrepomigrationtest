<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/

/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/neo_Paylater_Open/neo_Paylater_Open_sugar.php');
class neo_Paylater_Open extends neo_Paylater_Open_sugar {
	
	function __construct(){
		parent::__construct();
	}
	function create_export_query($order_by, $where, $relate_link_join='')
		{   $myfile = fopen("Logs/paylater_extract.log", "a");
			$custom_join = $this->custom_fields->getJOIN(true, true, $where);

        // For easier code reading, reused plenty of time
        $table = $this->table_name;

        if($custom_join)
        {
            $custom_join['join'] .= $relate_link_join;
        }
		$query = "select
		neo_paylater_open.id,
		neo_paylater_open.entity_name as 'Establishment Name',
		neo_paylater_open.application_id as 'Application Id',
		CONCAT(users.first_name, ' ', users.last_name) as 'Assigned To',
		neo_paylater_open.closed_by as 'Closed By',
		neo_paylater_open.status as 'Welcome Call Status',
		neo_paylater_open.date_closed as 'Date Closed',
		neo_paylater_open.description as 'Welcome Call Remarks',
		DATEDIFF(neo_paylater_open.date_closed,neo_paylater_open.date_entered) as 'Call Ageing',
		neo_paylater_open.call_owner as 'Call Owner',
		neo_paylater_open.product as 'Product Name',
		neo_paylater_open.date_entered as 'Date Created',
		neo_paylater_open.phone_number as 'Phone Number',
		neo_paylater_open.alternate_phone_number as 'Alternate Phone Number',
		neo_paylater_open.city as 'City',
		neo_paylater_open.email_id as 'Email Id',
		neo_paylater_open.alternate_email_id as 'Alternate Email Id',
		neo_paylater_open.is_primary_email_verified as 'is primary email verified',
		neo_paylater_open.is_secondary_email_verified as 'is secondary email verified',
		neo_paylater_open.email_verification_status as 'email verification status',
		neo_paylater_open.escalation_level as 'Escalation Level',
		neo_paylater_open.date_attended as 'Date attended',
		neo_paylater_open.transaction_status as 'Transaction Status',
		neo_paylater_open.transaction_status_remarks as 'Transaction Status Remarks'";
        if($custom_join)
        {
            $query .= $custom_join['select'];
        }

        $query .= " FROM $table ";


        $query .= "LEFT JOIN users
					ON $table.assigned_user_id=users.id ";


        //Join email address table too.
        //$query .=  " LEFT JOIN email_addr_bean_rel on $table.id = email_addr_bean_rel.bean_id and email_addr_bean_rel.bean_module = '" . $this->module_dir . "' and email_addr_bean_rel.deleted = 0 and email_addr_bean_rel.primary_address = 1";
        //$query .=  " LEFT JOIN email_addresses on email_addresses.id = email_addr_bean_rel.email_address_id ";

        if($custom_join)
        {
            $query .= $custom_join['join'];
        }

        $where_auto = " $table.deleted=0 ";

        if($where != "")
        {
            $query .= "WHERE ($where) AND " . $where_auto;
        }
        else
        {
            $query .= "WHERE " . $where_auto;
        }

        $order_by = $this->process_order_by($order_by);
        if (!empty($order_by)) {
            $query .= ' ORDER BY ' . $order_by;
		}
		fwrite($myfile,"\n.$query.\n");


        return $query;
        } 
	
}
?>