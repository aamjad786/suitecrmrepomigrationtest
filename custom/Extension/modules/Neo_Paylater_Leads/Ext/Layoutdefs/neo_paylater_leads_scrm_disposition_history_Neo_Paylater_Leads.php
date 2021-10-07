<?php
 // created: 2018-02-15 14:35:25
$layout_defs["Neo_Paylater_Leads"]["subpanel_setup"]['neo_paylater_leads_scrm_disposition_history'] = array (
  'order' => 100,
  'module' => 'scrm_Disposition_History',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_NEO_PAYLATER_LEADS_SCRM_DISPOSITION_HISTORY_FROM_SCRM_DISPOSITION_HISTORY_TITLE',
  'get_subpanel_data' => 'neo_paylater_leads_scrm_disposition_history',
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
