<?php

    $dictionary['Neo_Paylater_Leads']['indices'][] = array(
         'name' => 'idx_mobile_phone_cstm',
         'type' => 'index',
         'fields' => array(
             0 => 'phone_mobile',
         ),
         'source' => 'non-db',
    );
    