<?php
if(!defined('sugarEntry')) die('Not a Valid Entry Point');

//require_once('modules/bhea_Reports/report_utils.php');

class neo_Paylater_OpenController extends SugarController {

    function action_paylater_open_bill_generator()
    {
        header('Location: ?module=neo_Paylater_Open&action=paylater_open_bill_generator');
        die();
    }
}
?>