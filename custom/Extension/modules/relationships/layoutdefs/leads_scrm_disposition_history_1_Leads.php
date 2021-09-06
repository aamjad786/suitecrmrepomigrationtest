<?php
 // created: 2021-09-04 10:05:32
$layout_defs["Leads"]["subpanel_setup"]['leads_scrm_disposition_history_1'] = array (
  'order' => 100,
  'module' => 'scrm_Disposition_History',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_SCRM_DISPOSITION_HISTORY_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
  'get_subpanel_data' => 'leads_scrm_disposition_history_1',
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
