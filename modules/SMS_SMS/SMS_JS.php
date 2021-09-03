<?php
//prevents directly accessing this file from a web browser
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class SMS_SMSJS {

    function echoJavaScript($bean, $event, $arguments="") {
        
        //echo "Asif".$_REQUEST['module'];
        /*print_r($bean);*/
        /*$fp = fopen('1debug.txt', 'a+');
        fwrite($fp, "\r\n-------Asifnew req------\r\n"); 
        fwrite($fp, var_export($_REQUEST,true));
        fwrite($fp, var_export($_REQUEST['module'],true));*/
        // console.log($_REQUEST['module']);
        if ((!isset($_REQUEST['sugar_body_only']) || $_REQUEST['sugar_body_only'] != true) && $_REQUEST['action'] != 'modulelistmenu' &&
             $_REQUEST['action'] != "favorites" && $_REQUEST['action'] != 'Popup' && empty($_REQUEST['to_pdf']) &&
            (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv']) && $_REQUEST['action'] != 'Login' &&
            $_REQUEST['module'] != 'Timesheets' && $_REQUEST['module'] != 'ExpressionEngine' && ($_REQUEST['module'] == 'Contacts' || $_REQUEST['module'] == 'Leads' || $_REQUEST['module'] == 'Cases' || $_REQUEST['module'] == 'SMAcc_SM_Account' || $_REQUEST['module'] == 'neo_Paylater_Open' || $_REQUEST['module'] == 'Calls' || $_REQUEST['module'] == 'ProspectLists') && $_REQUEST['action'] !='EditView') {

            {
                //JS Third-Party Libraries
                if( preg_match("/^6\.[1-4]/",$GLOBALS['sugar_version']) ) {
                    echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>';
                    echo '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js" type="text/javascript"></script>';
                }
                echo '<script src="modules/SMS_SMS/javascripts/freeTextSMS.js?v=1" type="text/javascript"></script>';
                if($_REQUEST['module'] == 'ProspectLists'){
                    echo '<script>$(document).ready(function()
                    { 
                        var targetId = $(\'input[name="record"]\', document.forms[\'DetailView\']).attr(\'value\');
                        var button = $(\'<li><a href="javascript:void(0)" onclick="openPopupAjax(\\\'\\\', \\\'ProspectLists\\\', \\\'\'+targetId+\'\\\', \\\'1\\\');">Send SMS</a></li>\');
                        $("#detail_header_action_menu").sugarActionMenu(\'addItem\',{item:button});
                    });</script>';
                }
            }
        }
    }
}

function getConfigBool($index,$defaultValue=0) {
    //return "1";
    if( !empty( $GLOBALS['sugar_config'][$index]) ) {
        return $GLOBALS['sugar_config'][$index];
    }
    else {
        return $defaultValue;
    }
}

?>
