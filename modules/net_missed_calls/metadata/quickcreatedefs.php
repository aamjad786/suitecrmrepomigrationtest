<?php
$module_name = 'net_missed_calls';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
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
        'LBL_QUICKCREATE_PANEL1' => 
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
          1 => 'assigned_user_name',
        ),
      ),
      'lbl_quickcreate_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'circle',
            'label' => 'LBL_CIRCLE',
          ),
          1 => 
          array (
            'name' => 'operator',
            'label' => 'LBL_OPERATOR',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'user_mobile_number',
            'label' => 'LBL_USER_MOBILE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'call_received_at',
            'label' => 'LBL_CALL_RECEIVED_AT',
          ),
        ),
      ),
    ),
  ),
);
?>
