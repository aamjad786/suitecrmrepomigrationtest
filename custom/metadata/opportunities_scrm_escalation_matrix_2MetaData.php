<?php
// created: 2017-03-11 21:36:41
$dictionary["opportunities_scrm_escalation_matrix_2"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'opportunities_scrm_escalation_matrix_2' => 
    array (
      'lhs_module' => 'Opportunities',
      'lhs_table' => 'opportunities',
      'lhs_key' => 'id',
      'rhs_module' => 'scrm_Escalation_Matrix',
      'rhs_table' => 'scrm_escalation_matrix',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'opportunities_scrm_escalation_matrix_2_c',
      'join_key_lhs' => 'opportunities_scrm_escalation_matrix_2opportunities_ida',
      'join_key_rhs' => 'opportunities_scrm_escalation_matrix_2scrm_escalation_matrix_idb',
    ),
  ),
  'table' => 'opportunities_scrm_escalation_matrix_2_c',
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
      'name' => 'opportunities_scrm_escalation_matrix_2opportunities_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'opportunities_scrm_escalation_matrix_2scrm_escalation_matrix_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'opportunities_scrm_escalation_matrix_2spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'opportunities_scrm_escalation_matrix_2_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'opportunities_scrm_escalation_matrix_2opportunities_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'opportunities_scrm_escalation_matrix_2_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'opportunities_scrm_escalation_matrix_2scrm_escalation_matrix_idb',
      ),
    ),
  ),
);