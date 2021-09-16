<?php
require_once('include/MVC/View/views/view.edit.php');
require_once('include/SugarTinyMCE.php');

class neo_Paylater_OpenViewEdit extends ViewEdit {

    function __construct() {
        parent::__construct();
    }

    function display() {
        parent::display();  
        
        ?>
        <script>
            $('#create_link').hide();
            $("#escalation_level").prop("readonly", true);
            $("#application_id").prop("readonly", true);
            $("#name").prop("readonly", true);
            $("#email_id").prop("readonly", true);
            $("#alternate_email_id").prop("readonly", true);
            $("#phone_number").prop("readonly", true);
            $("#city").prop("readonly", true);   
            $("#alternate_phone_number").prop("readonly", true);    
            $("#entity_name").prop("readonly", true);                 
            $('#transaction_status').attr("disabled", true); 

        </script>    
        <?php
    }

}
