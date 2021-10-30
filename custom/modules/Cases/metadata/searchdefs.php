<?php
$searchdefs ['Cases'] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'merchant_app_id_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_MERCHANT_APP_ID',
        'width' => '10%',
        'name' => 'merchant_app_id_c',
      ),
      'open_only' => 
      array (
        'name' => 'open_only',
        'label' => 'LBL_OPEN_ITEMS',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'case_number' => 
      array (
        'name' => 'case_number',
        'default' => true,
        'width' => '10%',
      ),
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'state' => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_STATE',
        'width' => '10%',
        'name' => 'state',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'type' => 'enum',
        'label' => 'LBL_ASSIGNED_TO',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'case_subcategory_c' => 
      array (
        'type' => 'dynamicenum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CASE_SUBCATEGORY',
        'width' => '10%',
        'name' => 'case_subcategory_c',
      ),
      'current_user_department_c' => 
      array (
        'type' => 'enum',
        'label' => 'Current User Department',
        'width' => '10%',
        'default' => true,
        'name' => 'current_user_department_c',
      ),
      'attended_by_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_ATTENDED_BY',
        'width' => '10%',
        'name' => 'attended_by_c',
      ),
      'priority' => 
      array (
        'name' => 'priority',
        'default' => true,
        'width' => '10%',
      ),
      'merchant_app_id_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_MERCHANT_APP_ID',
        'width' => '10%',
        'name' => 'merchant_app_id_c',
      ),
      'merchant_name_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_MERCHANT_NAME',
        'width' => '10%',
        'name' => 'merchant_name_c',
      ),
      'merchant_email_id_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_MERCHANT_EMAIL_ID',
        'width' => '10%',
        'name' => 'merchant_email_id_c',
      ),
      'merchant_contact_number_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_MERCHANT_CONTACT_NUMBER',
        'width' => '10%',
        'name' => 'merchant_contact_number_c',
      ),
      'case_location_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CASE_LOCATION',
        'width' => '10%',
        'name' => 'case_location_c',
      ),
      'case_details_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CASE_DETAILS',
        'width' => '10%',
        'name' => 'case_details_c',
      ),
      'scheme_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'label' => 'LBL_SCHEME',
        'width' => '10%',
        'name' => 'scheme_c',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'case_action_code_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CASE_ACTION_CODE',
        'width' => '10%',
        'name' => 'case_action_code_c',
      ),
      'escalation_level_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ESCALATION_LEVEL',
        'width' => '10%',
        'name' => 'escalation_level_c',
      ),
      'complaintaint_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_COMPLAINTAINT',
        'width' => '10%',
        'name' => 'complaintaint_c',
      ),
      'age_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_AGE',
        'width' => '10%',
        'name' => 'age_c',
      ),
      'case_category_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CASE_CATEGORY',
        'width' => '10%',
        'name' => 'case_category_c',
      ),
      'case_source_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CASE_SOURCE',
        'width' => '10%',
        'name' => 'case_source_c',
      ),
      'case_sub_source_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CASE_SUB_SOURCE',
        'width' => '10%',
        'name' => 'case_sub_source_c',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'email_source' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_EMAIL_SOURCE',
        'width' => '10%',
        'default' => true,
        'name' => 'email_source',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>
