<?php
 // created: 2016-12-04 22:37:14
$layout_defs["Users"]["subpanel_setup"]['scrm_targets_history_users'] = array (
  'order' => 100,
  'module' => 'scrm_Targets_History',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_SCRM_TARGETS_HISTORY_USERS_FROM_SCRM_TARGETS_HISTORY_TITLE',
  'get_subpanel_data' => 'scrm_targets_history_users',
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
