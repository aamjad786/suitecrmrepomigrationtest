<?php
 // created: 2018-05-02 18:21:04
$layout_defs["SMAcc_SM_Account"]["subpanel_setup"]['smacc_sm_account_notes_1'] = array (
  'order' => 100,
  'module' => 'Notes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_SMACC_SM_ACCOUNT_NOTES_1_FROM_NOTES_TITLE',
  'get_subpanel_data' => 'smacc_sm_account_notes_1',
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
