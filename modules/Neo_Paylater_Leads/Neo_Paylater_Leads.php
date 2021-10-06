<?php

/* * *******************************************************************************
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
 * ****************************************************************************** */

/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/Neo_Paylater_Leads/Neo_Paylater_Leads_sugar.php');

class Neo_Paylater_Leads extends Neo_Paylater_Leads_sugar {

    function __construct() {
        parent::__construct();
    }
//function for export leads
    /*function create_export_query($order_by, $where, $relate_link_join = '') {
        $query = "SELECT neo_paylater_leads.id as 'id', neo_paylater_leads.date_entered as 'Date Created', neo_paylater_leads.customer_id as 'Customer ID',
                neo_paylater_leads.description as 'CRM Remarks', neo_paylater_leads_cstm.as_application_id_c as 'AS Application ID',neo_paylater_leads.lead_source as 'Lead Source',
                CONCAT(neo_paylater_leads.first_name, ' ', neo_paylater_leads.last_name) as 'Customer Name', neo_paylater_leads.business_name as 'Business Name', 
                neo_paylater_leads.phone_mobile as 'Mobile Number', neo_paylater_leads.primary_address_city as 'City', 
                neo_paylater_leads_cstm.address_c as 'Business Address', neo_paylater_leads.primary_address_postalcode as 'Business Address Postal Code', 
                neo_paylater_leads.partner_name as 'Partner Name',  users.user_name as 'Assigned To', neo_paylater_leads.disposition as 'Disposition', 
                neo_paylater_leads.subdisposition as 'Sub Disposition', neo_paylater_leads_cstm.as_remarks_c as 'AS Remarks',
                neo_paylater_leads_cstm.as_lead_status_c  as 'AS Status', neo_paylater_leads_cstm.store_id_c as 'Store ID', 
                neo_paylater_leads.date_modified as 'Date Modified'   from neo_paylater_leads  
                JOIN neo_paylater_leads_cstm ON neo_paylater_leads.id = neo_paylater_leads_cstm.id_c 
                JOIN users ON neo_paylater_leads.assigned_user_id = users.id";
        $where_auto = "  neo_paylater_leads.deleted=0";
        if ($where != "") {
            $query .= " where $where AND " . $where_auto;
        } else {
            $query .= " where " . $where_auto;
        }
        if ($order_by != "") {
            $query .= " ORDER BY $order_by";
        } else {
            $query .= " ORDER BY neo_paylater_leads.customer_id";
        }
        $GLOBALS['log']->debug("Export query for Paylater export leads -> " . $query);
        return $query;
    }*/

}

?>