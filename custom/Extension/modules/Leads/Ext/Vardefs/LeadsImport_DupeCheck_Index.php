<?php
$dictionary['Lead']['indices'][] = array(
    'name' => 'idx_dupe_check_cstm',
    'type' => 'index',
    'fields' => array(
        0 => 'phone_mobile',
    ),
    'source' => 'non-db',
    'dupeCheckFunction' => 'dupeCheckfindDuplicateLead'
);
