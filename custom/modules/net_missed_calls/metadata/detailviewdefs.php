<?php
$module_name = 'net_missed_calls';
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
        'LBL_DETAILVIEW_PANEL1' => 
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
        1 => 
        array (
          0 => 'date_entered',
          1 => 'date_modified',
        ),
        2 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_detailview_panel1' => 
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
            'name' => 'receiving_number',
            'label' => 'LBL_RECEIVING_NUMBER',
          ),
          1 => 
          array (
            'name' => 'user_mobile_number',
            'label' => 'LBL_USER_MOBILE_NUMBER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'call_received_at',
            'label' => 'LBL_CALL_RECEIVED_AT',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
?>
