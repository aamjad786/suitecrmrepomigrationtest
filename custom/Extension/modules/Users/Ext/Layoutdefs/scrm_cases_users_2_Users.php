<?php
 // created: 2018-06-26 17:19:07
$layout_defs["Users"]["subpanel_setup"]['scrm_cases_users_2'] = array (
  'order' => 100,
  'module' => 'scrm_Cases',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_SCRM_CASES_USERS_2_FROM_SCRM_CASES_TITLE',
  'get_subpanel_data' => 'scrm_cases_users_2',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
