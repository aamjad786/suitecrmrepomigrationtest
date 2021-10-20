<?php

function getCamReportingToCM($bean=null) {
    
    // require_once 'custom/CustomLogger/CustomLogger.php';
    // $logger =new CustomLogger('getCamReportingToCM');
    // $logger->log('debug', 'getCamReportingToCM called: ');
    
    global $db;    
    $users = array();
    $users[''] = '';
    
    if($bean!=null){

        $query = "SELECT id,user_name,CONCAT(first_name, ' ', last_name) AS 'name' FROM users u join users_cstm ucstm on u.id=ucstm.id_c  WHERE deleted = 0 and status = 'Active' and (reports_to_id='".$bean->assigned_user_id."' AND designation_c LIKE '%Customer Acquisition%')";
    
        $results = $db->query($query);
        $i = 0;
        while ($row = $db->fetchByAssoc($results)) {
            if (!empty($row['user_name']) && !empty($row['name'])) {
                $users[$row['id']] = $row['name'];
            }
        }
    }
    
    return $users;
}
