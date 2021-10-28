<?php
// created: 2016-08-28 13:25:41
$dictionary["sms_sms_prospects_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'sms_sms_prospects_1' => 
    array (
      'lhs_module' => 'SMS_SMS',
      'lhs_table' => 'sms_sms',
      'lhs_key' => 'id',
      'rhs_module' => 'Prospects',
      'rhs_table' => 'prospects',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'sms_sms_prospects_1_c',
      'join_key_lhs' => 'sms_sms_prospects_1sms_sms_ida',
      'join_key_rhs' => 'sms_sms_prospects_1prospects_idb',
    ),
  ),
  'table' => 'sms_sms_prospects_1_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'sms_sms_prospects_1sms_sms_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'sms_sms_prospects_1prospects_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'sms_sms_prospects_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'sms_sms_prospects_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'sms_sms_prospects_1sms_sms_ida',
        1 => 'sms_sms_prospects_1prospects_idb',
      ),
    ),
  ),
);