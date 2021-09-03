<?php
 // created: 2017-08-23 11:29:58
$layout_defs["Cases"]["subpanel_setup"]['cases_sms_sms_1'] = array (
  'order' => 100,
  'module' => 'SMS_SMS',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CASES_SMS_SMS_1_FROM_SMS_SMS_TITLE',
  'get_subpanel_data' => 'cases_sms_sms_1',
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
