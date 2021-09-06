<?php
 // created: 2018-08-17 13:21:15
$layout_defs["Neo_Customers"]["subpanel_setup"]['neo_customers_sms_sms_1'] = array (
  'order' => 100,
  'module' => 'SMS_SMS',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_NEO_CUSTOMERS_SMS_SMS_1_FROM_SMS_SMS_TITLE',
  'get_subpanel_data' => 'neo_customers_sms_sms_1',
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
