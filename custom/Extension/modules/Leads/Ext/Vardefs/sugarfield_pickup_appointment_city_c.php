<?php
$dictionary['Lead']['fields']['pickup_appointment_city_c'] = array(
    'name' => 'pickup_appointment_city_c',
    'vname' => 'LBL_PICKUP_APPOINTMENT_CITY_C',
    'type' => 'enum',
    'source' => 'non-db',
    'len' => '100',
    'audited'=>false,
    'required'=>false,
    'options'=>'cluster_cities',
    'comment' => 'pickup appointment CITY',
    'studio' => array('visible'=>true, 'searchview'=>true),
);

?>
