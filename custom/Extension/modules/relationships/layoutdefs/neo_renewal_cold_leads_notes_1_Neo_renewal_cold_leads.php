<?php
 // created: 2018-05-17 16:51:14
$layout_defs["Neo_renewal_cold_leads"]["subpanel_setup"]['neo_renewal_cold_leads_notes_1'] = array (
  'order' => 100,
  'module' => 'Notes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_NEO_RENEWAL_COLD_LEADS_NOTES_1_FROM_NOTES_TITLE',
  'get_subpanel_data' => 'neo_renewal_cold_leads_notes_1',
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
