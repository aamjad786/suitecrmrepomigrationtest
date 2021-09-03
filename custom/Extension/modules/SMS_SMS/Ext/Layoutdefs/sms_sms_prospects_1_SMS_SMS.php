<?php
 // created: 2016-08-28 13:25:41
$layout_defs["SMS_SMS"]["subpanel_setup"]['sms_sms_prospects_1'] = array (
  'order' => 100,
  'module' => 'Prospects',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_SMS_SMS_PROSPECTS_1_FROM_PROSPECTS_TITLE',
  'get_subpanel_data' => 'sms_sms_prospects_1',
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
