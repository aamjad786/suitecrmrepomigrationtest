

<?php
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/* * *******************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2010 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * ****************************************************************************** */

require_once('include/MVC/View/views/view.edit.php');

class Neo_Paylater_LeadsViewEdit extends ViewEdit {

    function LeadsViewEdit() {
        parent::ViewEdit();
    }

    /**
     * display
     * Override the display method to support customization for the buttons that display
     * a popup and allow you to copy the account's address into the selected contacts.
     * The custom_code_billing and custom_code_shipping Smarty variables are found in
     * include/SugarFields/Fields/Address/DetailView.tpl (default).  If it's a English U.S.
     * locale then it'll use file include/SugarFields/Fields/Address/en_us.DetailView.tpl.
     */
    function display() {

        global $current_user;
        $role = ACLRole::getUserRoleNames($current_user->id);
        $current_user_id = $current_user->id;
        $user_role = $role[0];
        $beanID = $this->bean->id;

        $bean = BeanFactory::getBean('Neo_Paylater_Leads', $beanID);
        $allDocuments = "";
        if ($bean->documents_uploaded_c) {
            $string = $bean->documents_json_c;
            $string = htmlspecialchars_decode($string);
            $allDocuments = json_decode($string);
        }
        parent::display();
        ?>

        <center><p id = "uploading" style="color: blue; font-size: 20px; display:none"><b></b></p></center>
        <!--<center><p id = "upload_success" style="color: green; font-size: 20px; display:none"><b>Documents uploaded, please save to update the other details</b></p></center>-->
        <center><p id = "upload_failure" style="color: red; font-size: 20px; display:none"><b></b></p></center>
                    <?php
                    require_once("custom/modules/Neo_Paylater_Leads/neo_paylater_Upload_doc_forms.php");
                    ?>

        <script>

            var beanid = "<?= $this->bean->id; ?>";
            $('input[type="submit"]').removeAttr('onclick');
            disposition = $('#disposition').val();
            var AS_lead_status = $('#as_lead_status_c').val();
            var document_uploaded = "<?= $this->bean->documents_uploaded_c; ?>";
            var as_documents_uploaded_c = "<?= $this->bean->as_documents_uploaded_c; ?>";
            var ng_portal_status = "<?= $this->bean->ng_portal_status; ?>";

            $("#as_lead_status_c").prop("readonly", true);
            $("#ng_portal_status").prop("readonly", true);


            if (disposition == "sent_to_ng_login") {
                $('#check_disposition').prop('checked', true);
                if (AS_lead_status != "Approved") {
                    $('#check_disposition').attr('disabled', 1);
                }
            }
            if(AS_lead_status == "Rejected by Ops"){
                $("#check_disposition").removeAttr("disabled");
            } 
            if (AS_lead_status != "Approved") {
                document.getElementById("disposition").options[11].disabled = true;
            }
            
            $("#disposition").change(function() {
                var changed_disposition = $("#disposition").val()
                if((changed_disposition == "send_to_ng_login") && (document_uploaded != "1")){
                    alert("Documents not uploaded yet");
                    $("#disposition").val(disposition);
                }
            })
            
            $('input[type="submit"]').click(function () {
                if (beanid != "") {
                    disposition = $('#disposition').val();
                }
                if ($("#check_disposition").is(':checked')) {
                    sub_disposition = $('#subdisposition').val();
                    meeting_time = $('#meeting').val();
                    callback_time = $('#callback').val();
                    if (disposition == 'meeting_fixed' && meeting_time == "") {
                        $('#meeting_label').next().children().css('border', '1px solid red');
                        return false;
                    } else if (sub_disposition == 'follow_up_rescheduled' && callback_time == "") {
                        $('#callback_label').next().children().css('border', '1px solid red');
                        return false;
                    } else if ((disposition == "documents_collected") || (disposition == "documents_collected" && document_uploaded != "1") || (document_uploaded == 1 && ((AS_lead_status == "Approved") || (AS_lead_status == "Rejected by Ops"))) || (disposition == "send_to_as_approval")) {
                        $('html, body').animate({scrollTop: $(document).height()}, 'slow');
                        if ((as_documents_uploaded_c == 1) && (document_uploaded == 1)) {
                            if ($('#document_form').css('display') != 'none')
                            {
                                var _form = document.getElementById('EditView');
                                _form.action.value = 'Save';
                                if (check_form('EditView'))
                                    SUGAR.ajaxUI.submitForm(_form);
                                return true;
                            } else {
                                $('#document_form').show();
                            }
                        } else {
                            $('input[type="submit"]').hide();
                            $('#save_and_continue').hide();
                            $('#document_form').show();
                            return false;
                        }
                        return false;
                    }
                }
                var _form = document.getElementById('EditView');
                _form.action.value = 'Save';
                if (check_form('EditView'))
                    SUGAR.ajaxUI.submitForm(_form);
                return false;
            });
            $(document).on('change', '#check_disposition', function () {
                if ($("#check_disposition").is(':checked')) {
                    if (disposition == "sent_to_ng_login") {
                        // $('html, body').animate({scrollTop:$(document).height()}, 'slow');
                        // $('#document_form').show(); //This will need to handle how to add docs without paylater app Id being there already (Through Logichooks)
                    }
                }
            });

            function disposition_state_check() {
                if (($("#check_disposition").is(':checked')) && (disposition == "sent_to_ng_login") && ((AS_lead_status != "Approved") || (AS_lead_status != "Rejected by Ops"))) {
                    $('#disposition').attr('disabled', true);
                    $('#subdisposition').attr('disabled', true);
                } else {
                    $('#disposition').attr('disabled', false);
                    $('#subdisposition').attr('disabled', false);
                }
            }
            $(document).ready(function () {
                disposition_state_check();
                $("#check_disposition").change(function () {
                    disposition_state_check();
                });
                $("[id=primary_address_city]:eq(1)").attr('disabled', true);

            });
            $(document).on('change', '#partner_name', function () {
                changeDisposition();
            });
            function changeDisposition() {
                var partner_name = $('#partner_name').val();
                if (partner_name == "Metro Cash and Carry") {
                    if (ng_portal_status == "Incomplete") {
                        $("#disposition").val('0');
                        $("#subdisposition").val('0');
                        $('#check_disposition').prop('checked', false); // Unchecks it
                        $('#disposition').attr("disabled", true);
                    } else if (ng_portal_status == "Completed") {
                        document.getElementById("disposition").options[9].selected = true;
                        $('#check_disposition').prop('checked', true);
                        $('#disposition').attr("disabled", true);
                    }
                }
            }

        </script>
        <?php
    }

}
?>
