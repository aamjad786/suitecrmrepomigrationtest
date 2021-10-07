<?php
 // created: 2016-09-16 16:46:41
$layout_defs["Prospects"]["subpanel_setup"]['prospects_scrm_disposition_history_1'] = array (
  'order' => 100,
  'module' => 'scrm_Disposition_History',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_PROSPECTS_SCRM_DISPOSITION_HISTORY_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
  'get_subpanel_data' => 'prospects_scrm_disposition_history_1',
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
