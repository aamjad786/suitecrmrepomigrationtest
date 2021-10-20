<?php
    function jobQueueTest($job) {

        require_once('custom/CustomLogger/CustomLogger.php');
        $logger =new CustomLogger('jobQueueTest');
        $data=json_decode($job->data, true);

        $logger->log('debug', 'Job Data: '.$job->data);
        
        if (!empty($job->data)) {
            $logger->log('debug', 'Job Data Is Not Empty: '. json_decode($job->data, true));
        }
    
        return true;
    }
