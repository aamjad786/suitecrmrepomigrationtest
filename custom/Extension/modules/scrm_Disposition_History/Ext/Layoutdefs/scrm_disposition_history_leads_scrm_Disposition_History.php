<?php
 // created: 2016-09-15 14:53:46
$layout_defs["scrm_Disposition_History"]["subpanel_setup"]['scrm_disposition_history_leads'] = array (
  'order' => 100,
  'module' => 'Leads',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_SCRM_DISPOSITION_HISTORY_LEADS_FROM_LEADS_TITLE',
  'get_subpanel_data' => 'scrm_disposition_history_leads',
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
