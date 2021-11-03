<?php 

    $job_strings[]='UpdateCaseOwners';

    function UpdateCaseOwners()
    {
        global $db;
        
        $query = "update cases_cstm JOIN cases ON cases.id=cases_cstm.id_c set cases_cstm.attended_by_c=(select CONCAT(users.first_name,' ',users.last_name) from users where id=cases.assigned_user_id) where date(cases.date_entered)>='2021-09-20' and cases.state !='Closed' and cases_cstm.attended_by_c LIKE '%Administrator%'";
        
        $db->query($query);


        return true;

    }
?>