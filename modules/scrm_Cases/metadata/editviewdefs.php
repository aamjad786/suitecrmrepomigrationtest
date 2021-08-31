<?php
$module_name = 'scrm_Cases';
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
            'name' => 'issue_type',
            'studio' => 'visible',
            'label' => 'LBL_ISSUE_TYPE',
          ),
          1 => 
          array (
            'name' => 'sub_issue_type',
            'studio' => 'visible',
            'label' => 'LBL_SUB_ISSUE_TYPE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'spoc',
            'label' => 'LBL_SPOC',
          ),
          1 => 
          array (
            'name' => 'spoc_team',
            'label' => 'LBL_SPOC_TEAM',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'tat_1',
            'label' => 'LBL_TAT_1',
          ),
          1 => 
          array (
            'name' => 'scrm_cases_users_name',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'tat_2',
            'label' => 'LBL_TAT_2',
          ),
          1 => 
          array (
            'name' => 'scrm_cases_users_1_name',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'tat_3',
            'label' => 'LBL_TAT_3',
          ),
          1 => 
          array (
            'name' => 'scrm_cases_users_2_name',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
          ),
          1 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
?>
