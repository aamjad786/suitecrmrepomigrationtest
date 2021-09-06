<?php
 // created: 2018-05-17 17:09:57
$layout_defs["Neo_Customers"]["subpanel_setup"]['neo_customers_scrm_disposition_history_1'] = array (
  'order' => 100,
  'module' => 'scrm_Disposition_History',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_NEO_CUSTOMERS_SCRM_DISPOSITION_HISTORY_1_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
  'get_subpanel_data' => 'neo_customers_scrm_disposition_history_1',
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
