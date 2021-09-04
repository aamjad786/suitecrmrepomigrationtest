<?php
 // created: 2021-09-01 17:21:53
$layout_defs["Leads"]["subpanel_setup"]['leads_sms_sms_1'] = array (
  'order' => 100,
  'module' => 'SMS_SMS',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_SMS_SMS_1_FROM_SMS_SMS_TITLE',
  'get_subpanel_data' => 'leads_sms_sms_1',
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
