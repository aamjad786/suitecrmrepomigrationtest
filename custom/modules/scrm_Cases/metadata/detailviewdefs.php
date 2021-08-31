<?php
$module_name = 'scrm_Cases';
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
            'name' => 'name',
            'label' => 'LBL_NAME',
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
            'name' => 'defined_tat_in_days',
            'label' => 'LBL_TAT_IN_DAYS',
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
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
        5 => 
        array (
          0 => '',
          1 => '',
        ),
        6 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
    ),
  ),
);
?>
