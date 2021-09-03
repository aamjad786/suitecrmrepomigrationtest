<?php
$module_name = 'SMS_SMS';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
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
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
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
          1 => 
          array (
            'name' => 'smsreceivedon',
            'label' => 'LBL_SMS_RECEIVED_ON',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'msg_response',
            'label' => 'LBL_MSG_RESPONSE',
          ),
          1 => 
          array (
            'name' => 'delivery_status',
            'studio' => 'visible',
            'label' => 'LBL_DELIVERY_STATUS',
          ),
        ),
        2 => 
        array (
          0 => 'description',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'parent_name',
            'studio' => 'visible',
            'label' => 'LBL_FLEX_RELATE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'cases_sms_sms_1_name',
          ),
          1 => 
          array (
            'name' => 'messageid',
            'label' => 'LBL_MESSAGEID',
          ),
        ),
        6 => 
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
