<?php

    array_push($job_strings, 'escalation_matrix_scheduler_job');
    function escalation_matrix_scheduler_job()
    {
       global $db;
       include_once('EscalationMatrixScheduler.php');
       $ems = new EscalationMatrixScheduler();
	   $ems->escalate_to_reporting_user();
       
        return true;
    }
