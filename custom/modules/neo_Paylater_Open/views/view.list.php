<?php

require_once('include/MVC/View/views/view.list.php');
require_once('modules/Cases/CasesListViewSmarty.php');

class neo_Paylater_OpenViewList extends ViewList {

    function __construct(){
        parent::__construct();
    }
    function preDisplay(){
        $this->lv = new CasesListViewSmarty();
    }

    function display(){
        parent::display();
        ?>
            <script>
                $('#create_link').hide();
            </script>
        <?php
    }
}

?>