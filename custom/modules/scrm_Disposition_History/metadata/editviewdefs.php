<?php
$module_name = 'scrm_Disposition_History';
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
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'party_type_c',
            'studio' => 'visible',
            'label' => 'LBL_PARTY_TYPE',
          ),
          1 => 
          array (
            'name' => 'call_disposition_history_c',
            'studio' => 'visible',
            'label' => 'LBL_CALL_DISPOSITION_HISTORY',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'industry_c',
            'studio' => 'visible',
            'label' => 'LBL_INDUSTRY',
          ),
          1 => 
          array (
            'name' => 'remarks_c',
            'studio' => 'visible',
            'label' => 'LBL_REMARKS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'disposition_c',
            'studio' => 'visible',
            'label' => 'LBL_DISPOSITION',
          ),
          1 => 
          array (
            'name' => 'sub_disposition_c',
            'studio' => 'visible',
            'label' => 'LBL_SUB_DISPOSITION',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'product_pitched_c',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_PITCHED',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'promise_to_take_c',
            'studio' => 'visible',
            'label' => 'LBL_PROMISE_TO_TAKE',
          ),
        ),
        5 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'alternate_number_c',
            'label' => 'LBL_ALTERNATE_NUMBER',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'leads_scrm_disposition_history_1_name',
            'label' => 'LBL_LEADS_SCRM_DISPOSITION_HISTORY_1_FROM_LEADS_TITLE',
          ),
          1 => 
          array (
            'name' => 'prospects_scrm_disposition_history_1_name',
            'label' => 'LBL_PROSPECTS_SCRM_DISPOSITION_HISTORY_1_FROM_PROSPECTS_TITLE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'neo_paylater_leads_scrm_disposition_history_name',
            'label' => 'LBL_NEO_PAYLATER_LEADS_SCRM_DISPOSITION_HISTORY_FROM_NEO_PAYLATER_LEADS_TITLE',
          ),
          1 => 
          array (
            'name' => 'neo_customers_scrm_disposition_history_1_name',
          ),
        ),
      ),
    ),
  ),
);
;
?>
