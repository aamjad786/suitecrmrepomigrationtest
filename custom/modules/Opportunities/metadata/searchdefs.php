<?php
$searchdefs ['Opportunities'] = 
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
      'pickup_appointment_contact_c' => 
      array (
        'type' => 'phone',
        'default' => true,
        'label' => 'LBL_PICKUP_APPOINTMENT_CONTACT',
        'width' => '10%',
        'name' => 'pickup_appointment_contact_c',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'account_name' => 
      array (
        'name' => 'account_name',
        'default' => true,
        'width' => '10%',
      ),
      'amount' => 
      array (
        'name' => 'amount',
        'default' => true,
        'width' => '10%',
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
      'sales_stage' => 
      array (
        'name' => 'sales_stage',
        'default' => true,
        'width' => '10%',
      ),
      'opportunity_status_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_OPPORTUNITY_STATUS',
        'width' => '10%',
        'name' => 'opportunity_status_c',
      ),
      'pickup_appointment_contact_c' => 
      array (
        'type' => 'phone',
        'default' => true,
        'label' => 'LBL_PICKUP_APPOINTMENT_CONTACT',
        'width' => '10%',
        'name' => 'pickup_appointment_contact_c',
      ),
      'lead_source' => 
      array (
        'name' => 'lead_source',
        'default' => true,
        'width' => '10%',
      ),
      'dsa_code_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_DSA_CODE',
        'width' => '10%',
        'name' => 'dsa_code_c',
      ),
      'pickup_appointment_city_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_PICKUP_APPOINTMENT_CITY',
        'width' => '10%',
        'name' => 'pickup_appointment_city_c',
      ),
      'pickup_appointment_date_c' => 
      array (
        'type' => 'datetimecombo',
        'default' => true,
        'label' => 'LBL_PICKUP_APPOINTMENT_DATE',
        'width' => '10%',
        'name' => 'pickup_appointment_date_c',
      ),
      'application_id_c' => 
      array (
        'type' => 'varchar',
        'default' => true,
        'label' => 'LBL_APPLICATION_ID',
        'width' => '10%',
        'name' => 'application_id_c',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'control_program' => 
      array (
        'type' => 'varchar',
        'label' => 'Control Program',
        'width' => '20%',
        'default' => true,
        'name' => 'control_program',
      ),
      'alliance_opp_status_c' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ALLIANCE_OPP_STATUS_C',
        'width' => '10%',
        'name' => 'alliance_opp_status_c',
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
;
?>
