<?php

require_once('include/MVC/View/views/view.list.php');
require_once('modules/Cases/CasesListViewSmarty.php');

class SMAcc_SM_AccountViewList extends ViewList {

    function __construct() {
        parent::__construct();
    }

    function display() {
        parent::display();
        global $current_user;
      ?>

        <script>
            
            $('#create_link').hide();


        </script>
        <?php

    }

}
?>
