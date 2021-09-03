<?php
$module_name = 'SMS_SMS';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'team_name',
            'displayParams' => 
            array (
              'display' => true,
            ),
          ),
        ),
        2 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'cases_sms_sms_1_name',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'neo_paylater_leads_sms_sms_name',
            'label' => 'LBL_NEO_PAYLATER_LEADS_SMS_SMS_FROM_NEO_PAYLATER_LEADS_TITLE',
          ),
          1 => 
          array (
            'name' => 'neo_customers_sms_sms_1_name',
          ),
        ),
      ),
    ),
  ),
);
?>
