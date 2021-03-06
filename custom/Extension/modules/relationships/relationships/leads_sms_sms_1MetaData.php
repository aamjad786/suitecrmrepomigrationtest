<?php
// created: 2021-09-01 17:21:53
$dictionary["leads_sms_sms_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'leads_sms_sms_1' => 
    array (
      'lhs_module' => 'Leads',
      'lhs_table' => 'leads',
      'lhs_key' => 'id',
      'rhs_module' => 'SMS_SMS',
      'rhs_table' => 'sms_sms',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'leads_sms_sms_1_c',
      'join_key_lhs' => 'leads_sms_sms_1leads_ida',
      'join_key_rhs' => 'leads_sms_sms_1sms_sms_idb',
    ),
  ),
  'table' => 'leads_sms_sms_1_c',
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
      'name' => 'leads_sms_sms_1leads_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'leads_sms_sms_1sms_sms_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'leads_sms_sms_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'leads_sms_sms_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'leads_sms_sms_1leads_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'leads_sms_sms_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'leads_sms_sms_1sms_sms_idb',
      ),
    ),
  ),
);