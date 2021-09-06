<?php
// created: 2017-03-11 21:36:41
$dictionary["scrm_Escalation_Matrix"]["fields"]["opportunities_scrm_escalation_matrix_2"] = array (
  'name' => 'opportunities_scrm_escalation_matrix_2',
  'type' => 'link',
  'relationship' => 'opportunities_scrm_escalation_matrix_2',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OPPORTUNITIES_SCRM_ESCALATION_MATRIX_2_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'opportunities_scrm_escalation_matrix_2opportunities_ida',
);
$dictionary["scrm_Escalation_Matrix"]["fields"]["opportunities_scrm_escalation_matrix_2_name"] = array (
  'name' => 'opportunities_scrm_escalation_matrix_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_SCRM_ESCALATION_MATRIX_2_FROM_OPPORTUNITIES_TITLE',
  'save' => true,
  'id_name' => 'opportunities_scrm_escalation_matrix_2opportunities_ida',
  'link' => 'opportunities_scrm_escalation_matrix_2',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["scrm_Escalation_Matrix"]["fields"]["opportunities_scrm_escalation_matrix_2opportunities_ida"] = array (
  'name' => 'opportunities_scrm_escalation_matrix_2opportunities_ida',
  'type' => 'link',
  'relationship' => 'opportunities_scrm_escalation_matrix_2',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_SCRM_ESCALATION_MATRIX_2_FROM_SCRM_ESCALATION_MATRIX_TITLE',
);
