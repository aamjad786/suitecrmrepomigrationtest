<?php
$dictionary['Lead']['fields']['loan_amount_required_c'] = array(
    'name' => 'loan_amount_required_c',
    'vname' => 'LBL_LOAN_AMOUNT_REQUIRED_C',
    'type' => 'currency',
    'source' => 'non-db',
    'comment' => 'Unconverted amount of the opportunity',
    'duplicate_merge'=>'1',
  	'options' => 'numeric_range_search_dom',
    'enable_range_search' => true,
    'studio' => array('visible'=>true, 'searchview'=>true),
);
 ?>
