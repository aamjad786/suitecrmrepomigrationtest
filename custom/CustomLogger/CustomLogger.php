<?php

require_once 'include/SugarLogger/SugarLogger.php';

class CustomLogger extends SugarLogger
{
    protected $logfile ;
    protected $ext = '.log';
    protected $dateFormat = '%c';
    protected $logSize = '10MB';
    protected $maxLogs = 10;
    protected $filesuffix = "";
    protected $date_suffix = "";
    protected $log_dir = './Logs/';

    public function __construct($logFileName)
    {
        $this->logfile=$logFileName;
        $this->_doInitialization();
    }
}