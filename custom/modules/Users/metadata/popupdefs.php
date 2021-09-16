<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
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
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
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
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


$popupMeta = array(
    'moduleMain' => 'User',
    'varName' => 'USER',
    'orderBy' => 'user_name',
    'whereClauses' => array(
        'first_name' => 'users.first_name',
        'last_name' => 'users.last_name',
        'user_name' => 'users.user_name',
        'is_group' => 'users.is_group',
        'reports_to_id' => 'users.reports_to_id',
        'status' => 'users.status',
  'is_admin' => 'users.is_admin',
  'title' => 'users.title',
  'department' => 'users.department',
  'phone' => 'users.phone',
  'address_street' => 'users.address_street',
  'email' => 'users.email',
  'address_city' => 'users.address_city',
  'address_state' => 'users.address_state',
  'address_postalcode' => 'users.address_postalcode',
  'address_country' => 'users.address_country',
    ),
    'whereStatement'=> " users.status = 'Active' and users.portal_only= '0'",
    'whereStatement'=> " users.status = 'Active' and users.portal_only= '0'",
    'searchInputs' => array(
        'first_name',
        'last_name',
        'user_name',
        'is_group',
        'reports_to_id',
        'status',
        'is_admin',
        'title',
        'department',
        'phone',
        'address_street',
        'email',
        'address_city',
        'address_state',
        'address_postalcode',
        'address_country',
    ),
    'searchdefs' => array (
      'first_name' => 
      array (
        'name' => 'first_name',
        'width' => '10%',
      ),
      'last_name' => 
      array (
        'name' => 'last_name',
        'width' => '10%',
      ),
      'user_name' => 
      array (
        'name' => 'user_name',
        'width' => '10%',
      ),
      'reports_to_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_REPORTS_TO_NAME',
    'id' => 'REPORTS_TO_ID',
    'width' => '10%',
    'name' => 'reports_to_name',
   'displayParams' => array(
            'initial_filter' => '&reports_to_name_advanced='.$current_user->user_name,
        ),
  ),
  'status' => 
  array (
    'name' => 'status',
    'width' => '10%',
  ),
  'is_admin' => 
  array (
    'name' => 'is_admin',
    'width' => '10%',
  ),
  'title' => 
  array (
    'name' => 'title',
    'width' => '10%',
  ),
  'is_group' => 
  array (
    'name' => 'is_group',
    'width' => '10%',
  ),
  'department' => 
  array (
    'name' => 'department',
    'width' => '10%',
  ),
  'phone' => 
  array (
    'name' => 'phone',
    'label' => 'LBL_ANY_PHONE',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_street' => 
  array (
    'name' => 'address_street',
    'label' => 'LBL_ANY_ADDRESS',
    'type' => 'name',
    'width' => '10%',
  ),
  'email' => 
  array (
    'name' => 'email',
    'label' => 'LBL_ANY_EMAIL',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_city' => 
  array (
    'name' => 'address_city',
    'label' => 'LBL_CITY',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_state' => 
  array (
    'name' => 'address_state',
    'label' => 'LBL_STATE',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_postalcode' => 
  array (
    'name' => 'address_postalcode',
    'label' => 'LBL_POSTAL_CODE',
    'type' => 'name',
    'width' => '10%',
  ),
  'address_country' => 
  array (
    'name' => 'address_country',
    'label' => 'LBL_COUNTRY',
    'type' => 'name',
    'width' => '10%',
  ),
    ),
);