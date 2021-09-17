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

$dictionary['reg_regularization'] = array(
	'table'=>'reg_regularization',
	'audited'=>true,
    'inline_edit'=>true,
		'duplicate_merge'=>true,
		'fields'=>array (
			'app_id' => 
				array (
					'required' => true,
					'name' => 'app_id',
					'vname' => 'Application ID',
					'type' => 'varchar',
					'len' => '255',
					'merge_filter' => 'enabled',
				),
			'merchant_name' => 
				array (
					'required' => true,
					'name' => 'merchant_name',
					'vname' => 'Merchant Name',
					'type' => 'varchar',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
			'regularization_date' =>
				array(
					'name' => 'regularization_date',
					'vname' => 'Regularization Date',
					'massupdate' => false,
					'type' => 'date',
					'comment' => 'Regularization Date',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
			'ppt_date' =>
				array(
					'name' => 'ppt_date',
					'vname' => 'PTP Date',
					'massupdate' => false,
					'type' => 'date',
					'comment' => 'PPT Date',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
			'ppt_amount' =>
				array(
					'name' => 'ppt_amount',
					'vname' => 'PTP Amount',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'PPT Date',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
			'ppt_mode' =>
				array(
					'name' => 'ppt_mode',
					'vname' => 'PTP Mode',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'PPT Mode',
					'len' => '255',
					'merge_filter' => 'enabled',
				),
			'welcome_call_status' => 
				array (
					'required' => false,
					'name' => 'welcome_call_status',
					'vname' => 'LBL_WELCOME_CALL_STATUS',
					'type' => 'enum',
					'massupdate' => 0,
					'no_default' => false,
					'comments' => '',
					'help' => '',
					'importable' => 'true',
					'duplicate_merge' => 'disabled',
					'duplicate_merge_dom_value' => '0',
					'audited' => false,
					'inline_edit' => true,
					'reportable' => true,
					'unified_search' => false,
					'merge_filter' => 'disabled',
					'len' => 100,
					'size' => '20',
					'options' => 'neo_cash_status',
					'studio' => 'visible',
					'dependency' => false,
				),
			'tid' => 
				array (
					'required' => true,
					'name' => 'tid',
					'vname' => 'TID',
					'type' => 'varchar',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
			'mid' => 
				array (
					'required' => true,
					'name' => 'mid',
					'vname' => 'MID',
					'type' => 'varchar',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
			'regularized' =>
				array(
					'name' => 'regularized',
					'vname' => 'Regularized',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'regularized',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
			'remark' =>
				array(
					'name' => 'remark',
					'vname' => 'Remarks',
					'massupdate' => false,
					'type' => 'text',
					'comment' => 'Remarks',
					'merge_filter' => 'disabled',
				),
			'insurance' => 
				array (
					'required' => false,
					'name' => 'insurance',
					'vname' => 'Insurance',
					'type' => 'enum',
					'massupdate' => 0,
					'no_default' => false,
					'comments' => '',
					'help' => '',
					'importable' => 'true',
					'duplicate_merge' => 'disabled',
					'duplicate_merge_dom_value' => '0',
					'audited' => false,
					'inline_edit' => true,
					'reportable' => true,
					'unified_search' => false,
					'merge_filter' => 'disabled',
					'len' => 100,
					'size' => '20',
					'options' => 'insurance_detail',
					'studio' => 'visible',
					'dependency' => false,
				),
			'insurance_remarks' =>
				array(
					'name' => 'insurance_remarks',
					'vname' => 'Insurance Remarks',
					'massupdate' => false,
					'type' => 'text',
					'comment' => 'Remarks',
					'merge_filter' => 'disabled',
				),
			'cam_name' =>
				array(
					'name' => 'cam_name',
					'vname' => 'CAM Name',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'CAM Name',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
				'phone' =>
				array(
					'name' => 'phone',
					'vname' => 'Contact No.',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'Phone no.',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
				'email' =>
				array(
					'name' => 'email',
					'vname' => 'Email Id',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'email',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
				'branch' =>
				array(
					'name' => 'branch',
					'vname' => 'Branch',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'branch',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
				'emi' =>
				array(
					'name' => 'emi',
					'vname' => 'EMI',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'emi',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
				'terminal_maker' =>
				array(
					'name' => 'terminal_maker',
					'vname' => 'Terminal Maker',
					'massupdate' => false,
					'type' => 'varchar',
					'comment' => 'terminal maker',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
			'date_on_cam_request' =>
				array(
					'name' => 'date_on_cam_request',
					'vname' => 'Date on CAM Request',
					'massupdate' => false,
					'type' => 'date',
					'comment' => 'Date on CAM Request',
					'len' => '255',
					'merge_filter' => 'disabled',
				),
				'call_updation' => 
					array (
						'required' => false,
						'name' => 'call_updation',
						'vname' => 'LBL_CALL_UPDATION',
						'type' => 'enum',
						'massupdate' => 0,
						'no_default' => false,
						'comments' => '',
						'help' => '',
						'importable' => 'true',
						'duplicate_merge' => 'disabled',
						'duplicate_merge_dom_value' => '0',
						'audited' => true,
						'inline_edit' => true,
						'reportable' => true,
						'unified_search' => false,
						'merge_filter' => 'disabled',
						'len' => 100,
						'size' => '20',
						'options' => 'call_updation_list',
						'studio' => 'visible',
						'dependency' => false,
					),
				'regularization_category' => 
					array (
						'required' => false,
						'name' => 'regularization_category',
						'vname' => 'Regularization Category',
						'type' => 'enum',
						'massupdate' => 0,
						'no_default' => false,
						'comments' => '',
						'help' => '',
						'importable' => 'true',
						'duplicate_merge' => 'disabled',
						'duplicate_merge_dom_value' => '0',
						'audited' => true,
						'inline_edit' => true,
						'reportable' => true,
						'unified_search' => false,
						'merge_filter' => 'disabled',
						'len' => 100,
						'size' => '20',
						'options' => 'regularization_category_list',
						'studio' => 'visible',
						'dependency' => false,
					),
				'call_attempt_status' => 
					array (
						'required' => false,
						'name' => 'call_attempt_status',
						'vname' => 'LBL_CALL_ATTEMPT_STATUS',
						'type' => 'enum',
						'massupdate' => 0,
						'no_default' => false,
						'comments' => '',
						'help' => '',
						'importable' => 'true',
						'duplicate_merge' => 'disabled',
						'duplicate_merge_dom_value' => '0',
						'audited' => false,
						'inline_edit' => true,
						'reportable' => true,
						'unified_search' => false,
						'merge_filter' => 'disabled',
						'len' => 100,
						'size' => '20',
						'options' => 'call_attempt_status',
						'studio' => 'visible',
						'dependency' => false,
					),  
				'processor_name' =>
					array(
						'name' => 'processor_name',
						'vname' => 'Processor Name',
						'massupdate' => false,
						'type' => 'varchar',
						'comment' => 'Processor Name',
						'len' => '255',
						'merge_filter' => 'disabled',
					)
),
	'relationships'=>array (
),
	'optimistic_locking'=>true,
		'unified_search'=>true,
	);
if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('reg_regularization','reg_regularization', array('basic','assignable','security_groups'));