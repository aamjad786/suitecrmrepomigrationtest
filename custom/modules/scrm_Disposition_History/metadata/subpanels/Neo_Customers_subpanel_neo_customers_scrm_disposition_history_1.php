<?php
// created: 2018-05-18 12:56:27
$subpanel_layout['list_fields'] = array (
  'disposition_c' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_DISPOSITION',
    'width' => '20%',
  ),
  'sub_disposition_c' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_SUB_DISPOSITION',
    'width' => '20%',
  ),
  'description' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '25%',
    'default' => true,
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Users',
    'target_record_key' => 'modified_user_id',
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '45%',
    'default' => true,
  ),
);