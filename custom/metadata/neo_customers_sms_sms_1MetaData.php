<?php
// created: 2018-08-17 13:21:15
$dictionary["neo_customers_sms_sms_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'neo_customers_sms_sms_1' => 
    array (
      'lhs_module' => 'Neo_Customers',
      'lhs_table' => 'neo_customers',
      'lhs_key' => 'id',
      'rhs_module' => 'SMS_SMS',
      'rhs_table' => 'sms_sms',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'neo_customers_sms_sms_1_c',
      'join_key_lhs' => 'neo_customers_sms_sms_1neo_customers_ida',
      'join_key_rhs' => 'neo_customers_sms_sms_1sms_sms_idb',
    ),
  ),
  'table' => 'neo_customers_sms_sms_1_c',
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
      'name' => 'neo_customers_sms_sms_1neo_customers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'neo_customers_sms_sms_1sms_sms_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'neo_customers_sms_sms_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'neo_customers_sms_sms_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'neo_customers_sms_sms_1neo_customers_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'neo_customers_sms_sms_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'neo_customers_sms_sms_1sms_sms_idb',
      ),
    ),
  ),
);