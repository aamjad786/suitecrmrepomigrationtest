<?php
$module_name = 'SMS_SMS';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '12%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DELIVERY_STATUS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_DELIVERY_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '55%',
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'SMSRECEIVEDON' => 
  array (
    'type' => 'datetime',
    'default' => true,
    'label' => 'LBL_SMS_RECEIVED_ON',
    'width' => '10%',
  ),
  'TEAM_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => false,
  ),
  'MSG_RESPONSE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_MSG_RESPONSE',
    'width' => '10%',
    'default' => false,
  ),
);
?>
