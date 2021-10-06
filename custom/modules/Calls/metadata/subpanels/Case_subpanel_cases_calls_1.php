<?php
// created: 2019-04-29 16:14:25
$subpanel_layout['list_fields'] = array (
  'contact_number_c' => 
  array (
    'type' => 'varchar',
    'vname' => 'Contact Number',
    'width' => '10%',
    'default' => true,
  ),
  'date_start' => 
  array (
    'vname' => 'LBL_DATE_TIME',
    'width' => '10%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'target_record_key' => 'assigned_user_id',
    'target_module' => 'Employees',
    'width' => '10%',
    'default' => true,
  ),
  'duration_hours' => 
  array (
    'type' => 'int',
    'vname' => 'LBL_DURATION_HOURS',
    'width' => '10%',
    'default' => true,
  ),
  'duration_minutes' => 
  array (
    'type' => 'int',
    'vname' => 'LBL_DURATION_MINUTES',
    'width' => '10%',
    'default' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'time_start' => 
  array (
    'usage' => 'query_only',
  ),
  'recurring_source' => 
  array (
    'usage' => 'query_only',
  ),
);