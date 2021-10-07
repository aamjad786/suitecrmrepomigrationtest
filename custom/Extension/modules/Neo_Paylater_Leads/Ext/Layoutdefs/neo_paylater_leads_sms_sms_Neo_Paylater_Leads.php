<?php
 // created: 2018-02-15 14:35:25
$layout_defs["Neo_Paylater_Leads"]["subpanel_setup"]['neo_paylater_leads_sms_sms'] = array (
  'order' => 100,
  'module' => 'SMS_SMS',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_NEO_PAYLATER_LEADS_SMS_SMS_FROM_SMS_SMS_TITLE',
  'get_subpanel_data' => 'neo_paylater_leads_sms_sms',
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
