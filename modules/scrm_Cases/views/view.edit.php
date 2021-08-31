<?php
require_once('include/MVC/View/views/view.list.php');
class scrm_CasesViewEdit extends ViewEdit
{
// var _form = document.getElementById('EditView'); 
// _form.action.value='Save'; 
// if(check_form('EditView'))
//     SUGAR.ajaxUI.submitForm(_form);
// return false;
	
    function display(){
    	$this->ev->process();
        	parent::display();
            // print_r($this->bean);die();
            ?>
            <script>
            $(document).ready(function() {
                // $('#scrm_cases_users_name_label').html('Escalation Level 1 User');
                // $('#scrm_cases_users_1_name_label').html('Escalation Level 2 User');
                // $('#scrm_cases_users_2_name_label').html('Escalation Level 3 User');
                //removed required, because for some cases we want assigned user's reporting manager to be Escalation user 1
                //$('#scrm_cases_users_name').prop('required',true);
                //$('#scrm_cases_users_1_name').prop('required',true);
                //$('#scrm_cases_users_2_name').prop('required',true);
                $('#issue_type').change(updateName);
                $('#sub_issue_type').change(updateName);
                $('#name').prop('readonly',true);

                //var on_click_script = $("#SAVE_HEADER").attr('onclick');
                //console.log(on_click_script);

                var on_click_script_new = "var _form = document.getElementById('EditView'); _form.action.value='Save'; if(check_form('EditView')){var do_submit = confirm('Do you want to submit this form ?');console.log(do_submit);if(do_submit && userNullCheck()){SUGAR.ajaxUI.submitForm(_form);return do_submit};}return false;";
                $("#SAVE_HEADER").attr('onclick',on_click_script_new);
                $("#SAVE_FOOTER").attr('onclick',on_click_script_new);
 
            });
            // function userNullCheck(){
            //     var user1 = ('$scrm_cases_users_name').val().trim();
            //     var user2 = ('$scrm_cases_users_1_name').val().trim();
            //     var user3 = ('$scrm_cases_users_2_name').val().trim();
            //     if(user1 && user2 && user3){
            //         return true;
            //     }
            //     else{
            //         return false;
            //     }
            // }
            function updateName(){
                var issue_type = $('#issue_type').val();
                var sub_issue_type = $('#sub_issue_type').val();
                //var new_name = issue_type + '|' + sub_issue_type;
                var new_name = sub_issue_type;
                console.log('name - ' + new_name);
                $('#name').val(new_name);
            }
            </script>
            <?php
    }



}