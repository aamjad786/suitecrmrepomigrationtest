<?php
$module_name = 'scrm_Escalation_Matrix';
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
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'email_template',
            'studio' => 'visible',
            'label' => 'LBL_EMAIL_TEMPLATE',
          ),
          1 => 
          array (
            'name' => 'escalation_hours',
            'studio' => 'visible',
            'label' => 'LBL_ESCALATION_HOURS',
          ),
        ),
      ),
    ),
  ),
);
?>
