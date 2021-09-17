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

$mod_strings = array (
  'LBL_ASSIGNED_TO_ID' => 'Assigned User Id',
  'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
  'LBL_SECURITYGROUPS' => 'Security Groups',
  'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => 'Security Groups',
  'LBL_ID' => 'ID',
  'LBL_DATE_ENTERED' => 'Date Created',
  'LBL_DATE_MODIFIED' => 'Date Modified',
  'LBL_MODIFIED' => 'Modified By',
  'LBL_MODIFIED_ID' => 'Modified By Id',
  'LBL_MODIFIED_NAME' => 'Modified By Name',
  'LBL_CREATED' => 'Created By',
  'LBL_CREATED_ID' => 'Created By Id',
  'LBL_DESCRIPTION' => 'Description',
  'LBL_DELETED' => 'Deleted',
  'LBL_NAME' => 'Name',
  'LBL_CREATED_USER' => 'Created by User',
  'LBL_MODIFIED_USER' => 'Modified by User',
  'LBL_LIST_NAME' => 'Name',
  'LBL_EDIT_BUTTON' => 'Edit',
  'LBL_REMOVE' => 'Remove',
  'LBL_LIST_FORM_TITLE' => 'Regularizations List',
  'LBL_MODULE_NAME' => 'Regularizations',
  'LBL_MODULE_TITLE' => 'Regularizations',
  'LBL_HOMEPAGE_TITLE' => 'My Regularizations',
  'LNK_NEW_RECORD' => 'Create Regularizations',
  'LNK_LIST' => 'View Regularizations',
  'LNK_IMPORT_REG_REGULARIZATION' => 'Import Regularizations',
  'LBL_SEARCH_FORM_TITLE' => 'Search Regularizations',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'View History',
  'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
  'LBL_REG_REGULARIZATION_SUBPANEL_TITLE' => 'Regularizations',
  'LBL_NEW_FORM_TITLE' => 'New Regularizations',
  'LBL_CALL_ATTEMPT_STATUS' => 'Call Attempt Status',
  'LBL_CALL_UPDATION' => 'Call update',
);
$GLOBALS['app_list_strings']['neo_cash_status'] = array(
  '' => '',
  'IN_PROGRESS' => 'In Progress',
  'NON_CONTACTABLE' => 'Non Contactable',
  'CLOSED' => 'Closed'
);

$GLOBALS['app_list_strings']['call_attempt_status'] = array(
  '' => '',
  'LANGUAGE_BARRIER'=>'Language Barrier',
  'SWITCHED_OFF'=>'Switched Off',
  'NO_ANSWER' => 'No Answer',
  'CALL_BACK' => 'Call Back',
  'NOT_REACHABLE' => 'Not Reachable',
  'BUSY' => 'Busy',
  'WRONG_NUMBER' => 'Wrong Number'
);

$GLOBALS['app_list_strings']['call_updation_list'] = array(
  '' => '',
  'attempt_one' => 'Attempt 1',
  'attempt_two' => 'Attempt 2',
  'attempt_three' => 'Attempt 3',
  'attempt_four' => 'Attempt 4',
  'attempt_five' => 'Attempt 5'
);

$GLOBALS['app_list_strings']['regularization_category_list'] = array(
  '' => '',
  'Cheque_is_already_given_to_clear_the_variance' => 'Cheque is already given to clear the variance',
  'Foreclose_Ready_for_a_Foreclosure_loan_amount' => 'Foreclose: Ready for a Foreclosure loan amount',
  'Loan_amount_paid_and_closed_the_loan_from_merchant_end' => 'Loan amount paid and closed the loan from merchant end',
  'Merchant_requested_to_NG_Representative_to_visit_at_establishment' =>'Merchant requested to NG Representative to visit at establishment',
  'Pay_later_Cleared_the_outstanding_of_account' => 'Pay-later : Cleared the outstanding of account',
  'Pay_later_Ready_to_pay_minimum_amount' => 'Pay-later : Ready to pay minimum amount',
  'Pay_later_Account_closed_from_merchant_end' => 'Pay-later :Account closed from merchant end',
  'Raised_Query_in_the_CRM' => 'Raised Query in the CRM',
  'Ready_to_clear_variance_as_per_merchant_comfort' => 'Ready to clear variance as per merchant comfort',
  'Ready_to_clear_variance_on_one_short' => 'Ready to clear variance on one short',
  'Ready_to_clear_variance_on_weekly_basis' => 'Ready to clear variance on weekly basis',
  'Requested_to_stop_NACH' => 'Requested to stop NACH',
  'Unable_to_clear_the_variance_on_weekly_basis' => 'Unable to clear the variance on weekly basis',
  'Unable_to_pay_variance_due_to_financial_issue' => 'Unable to pay variance due to financial issue',
  'Variance_is_already_cleared' => 'Variance is already cleared',
  'Want_to_avail_the_moratorium' => 'Want to avail the moratorium',
  'Want_to_avail_the_Sanjivani_facility' => 'Want to avail the Sanjivani facility',
  'Others_out_of_above_list' => 'Others out of above list'
);

