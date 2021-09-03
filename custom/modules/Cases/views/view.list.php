<?php

require_once('include/MVC/View/views/view.list.php');
require_once('modules/Cases/CasesListViewSmarty.php');

class CasesViewList extends ViewList {

    function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function CasesViewList(){
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if(isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        }
        else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


    function preDisplay(){
        $this->lv = new CasesListViewSmarty();
    }

    function display(){
        
        global $sugar_config;
        echo $script = <<<SCRIPT
        <script>
            $( document ).ready(function() {
                $('td[field="merchant_app_id_c"]').each(function(index) {
                        var app_id = $(this).text().trim();
                        var url = "index.php?module=Cases&action=customer_application_profile&applicationID="+app_id;
                        $(this).html("<a href=" + url + ">" + app_id + "</a>");
                });
            });
        </script>
SCRIPT;
        parent::display();
    }
}

?>