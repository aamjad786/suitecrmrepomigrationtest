<?php 

    $job_strings[]='DeleteOldAowRecord';

    function DeleteOldAowRecord()
    {
        global $db;
        
        $DeleteQuery = "DELETE FROM aow_processed WHERE date(date_entered) < now() - interval 15 DAY";

        $appIds = $db->query($DeleteQuery);

        $processedDeleteQuery = "DELETE FROM aow_processed_aow_actions WHERE date(date_modified) < now() - interval 15 DAY";

        $appIds = $db->query($processedDeleteQuery);

        return true;

    }
?>