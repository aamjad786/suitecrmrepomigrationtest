<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2016 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/
require_once('include/MVC/View/views/view.edit.php');
require_once('include/SugarTinyMCE.php');

class CasesViewEdit extends ViewEdit {

    function __construct(){
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function CasesViewEdit(){

      
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';

        if(isset($GLOBALS['log'])) {

            $GLOBALS['log']->deprecated($deprecatedMessage);

        } else {

            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }

        self::__construct();
    }


    function display(){
       
        $this->ss->assign("CURRENT_USER_NAME", $GLOBALS['current_user']->user_name);
        $this->ss->assign("CURRENT_USER_DEPARTMENT", $GLOBALS['current_user']->department);
        $case_details_c = $this->bean->case_details_c;

        //JS to make field mendatory 
        $user = BeanFactory::getBean('Users',$this->bean->assigned_user_id);
        

        $department = $user->department;
       
        $jsscript = <<<EOQ
   <script>
        $(document).ready(function(){
            var str1 = $('#case_subcategory_c').val();
            var str2 = "terminal";
            //$('#processor_name_c').hide();  processor_name_label
            if(str1.indexOf(str2) != -1){
                console.log(str2 + " found");

            } else {
                $('#processor_name_c').hide();
            }
        
           
            $("#case_subcategory_c").change(function(){
                // console.log('here');
                if($(this).val()=='information_closure_process'){
                    console.log("ok");
                    $("#LBAL_c").show();
                    $("#min_preclosure_amount_c").show();
                    $("#proposed_preclosure_amount_c").show();
                    }
                if($(this).val()!='information_closure_process'){
                    $("#LBAL_c").hide();
                $("#min_preclosure_amount_c").hide();
                $("#proposed_preclosure_amount_c").hide();
                }
                var sub_category_c = $(this).val();
                console.log(sub_category_c);

                $(qrc_ftr_mapping).each(function(index, value){ //loop through your elements
                    if(value.parent == sub_category_c){ //check the sub_category_c
                        // console.log('qrc_ftr_mapping');
                        console.log(value);
                        $("#type").val(value.qrc); //select the option element as a string
                        if(role_type>1)
                        {
                            $( "#priority" ).prop( "disabled", false );
                        }
                        if(value.qrc=='query')
                        {
                            $('#priority').val('P3');
                        }
                        else if(value.qrc=='request')
                        {
                            $('#priority').val('P2');
                        }
                        else if(value.qrc=='complaint')
                        {
                            $('#priority').val('P1');
                        }
                        else if(value.qrc=='system')
                        {
                            $('#priority').val('P2');
                        }
                        if(role_type>1)
                        {
                            $( "#priority" ).prop( "disabled", true );
                        }
                        $("#case_action_code_c").val(value.ftr); //select the option element as a string
                    }
                });
            });
	$("#LBAL_c").hide();
    $("#min_preclosure_amount_c").hide();
    $("#proposed_preclosure_amount_c").hide();

    $("#case_subcategory_c").change(function() {
        // console.log('here');
        if ($(this).val() == 'information_closure_process') {
            document.getElementById("LBAL_c").value = null;
            console.log("ok");
            $("#LBAL_c").show();
            $("#min_preclosure_amount_c").show();
            $("#proposed_preclosure_amount_c").show();
        }
        if ($(this).val() != 'information_closure_process') {
            document.getElementById("LBAL_c").value = 0;
            $("#LBAL_c").hide();
            $("#min_preclosure_amount_c").hide();
            $("#proposed_preclosure_amount_c").hide();

        }
    });
    $('#type').change(function() {
        var v=$("#type option:selected").val();
        if(v==='query'){
            $('select option[value="P3"]').attr("selected",true);
            $('select option[value="P2"]').attr("selected",false);
            $('select option[value="P1"]').attr("selected",false);
        }
        if(v==='request'){
            $('select option[value="P2"]').attr("selected",true);
            $('select option[value="P3"]').attr("selected",false);
            $('select option[value="P1"]').attr("selected",false);
        }
        if(v==='complaint'){
            $('select option[value="P1"]').attr("selected",true);
            $('select option[value="P2"]').attr("selected",false);
            $('select option[value="P3"]').attr("selected",false);
        }
        if(v==='system'){
            $('select option[value="P1"]').attr("selected",false);
            $('select option[value="P2"]').attr("selected",true);
            $('select option[value="P3"]').attr("selected",false);
        }
   });
  });
       // Change priority to the field of your module
        makerequired(); //Call at onload while editing a case record
       $('#priority').change(function() {
            // console.log("priority change");
            makerequired(); // onchange call function to mark the field required
       });
        function makerequired()
        {
        var status = $('#priority').val(); // get current value of the field 
         if(status == 'P4'){ // check if it matches the condition: if true,
                // console.log("priority change P4");
                addToValidate('EditView','sub_priority_c','enum',true,'Escalataion Source');    // mark sub_priority field required
                $('#sub_priority_label').html('Escalation Source: <font color="red">*</font>'); // with red * sign next to label
            }
            else{
                // console.log("priority change not P4");
                removeFromValidate('EditView','sub_priority_c');  // else remove the validtion applied
                $('#sub_priority_label').html('Escalation Source: '); // and give the normal label back 
            }
        }
</script>
EOQ;

    $jsscript_case_details_c = <<<EOQ1
    <script>
        
        //{parent_key: '', key: '', value: ''},

        var qrc_ftr_mapping =  [
          { parent : 'alteration_address' ,                     qrc: 'request',     ftr:'non_ftr' },
          { parent : 'alteration_bank_account' ,                qrc: 'request',     ftr:'non_ftr' },
          { parent : 'alteration_contact_no' ,                  qrc: 'request',     ftr:'ftr' },
          { parent : 'alteration_email_id' ,                    qrc: 'request',     ftr:'ftr' },
          { parent : 'alteration_guarator' ,                    qrc: 'request',     ftr:'non_ftr' },
          { parent : 'alteration_repayment' ,                   qrc: 'request',     ftr:'non_ftr' },
          { parent : 'alteration_gst_updation' ,                qrc: 'request',     ftr:'ftr' },

          { parent : 'bureaus_update' ,                         qrc: 'request',     ftr:'non_ftr' },
          { parent : 'bureaus_rectification' ,                  qrc: 'complaint',  ftr:'non_ftr' },
          { parent : 'bureaus_details_requested_by_bureaus' ,   qrc: 'query',  ftr:'ftr' },

          { parent : 'documentation_batchwise_settlement' ,     qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_no_due_certificate' ,       qrc: 'request',     ftr:'non_ftr' },
          { parent : 'documentation_gst_invoice' ,              qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_interest_certificate' ,     qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_loan_agreement' ,           qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_repayment_schedule' ,       qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_sanction_letter' ,          qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_welcome_letter' ,           qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_statement_hard' ,           qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_statement_soft' ,           qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_insurance_copy' ,           qrc: 'request',     ftr:'ftr' },
          { parent : 'documentation_customer_unused_cheques',   qrc: 'request',     ftr:'ftr' },

          { parent : 'financial_live_excess_recovery' ,         qrc: 'complaint',   ftr:'non_ftr' },
          { parent : 'financial_live_refund_excess_fee' ,       qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_loan_amount_not_received', qrc: 'query',       ftr:'non_ftr' },
          { parent : 'financial_live_non_receipt_payout' ,      qrc: 'query',       ftr:'non_ftr' },
          { parent : 'financial_live_incorrect_loan_amount' ,   qrc: 'query',       ftr:'non_ftr' },
          { parent : 'financial_live_ach_deactivation' ,        qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_ach_activation' ,          qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_variance_recovery' ,       qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_tds_refund_adjustment' ,   qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_tds_refund' ,              qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_payment_confirmation' ,    qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_represent_bounce_check' ,  qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_loan_restructure' ,        qrc: 'request',     ftr:'ftr' },
          { parent : 'financial_live_loan_defund' ,             qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_clarification_on_delayed_payment_charges' ,  qrc: 'query',     ftr:'ftr' },
          { parent : 'financial_live_delay_Charges_waiver' ,    qrc: 'request',     ftr:'non_ftr' },
          { parent : 'financial_live_settlement_waiver_request', qrc: 'request',    ftr:'non_ftr' },

          { parent : 'financial_closed_excess_recovery' ,       qrc: 'complaint',   ftr:'non_ftr' },
          { parent : 'financial_closed_excess_terminal_fees_recovery' ,       qrc: 'request',   ftr:'non_ftr' },
          { parent : 'financial_closed_tds_refund' ,            qrc: 'request',     ftr:'non_ftr' },

          { parent : 'settlement_discount_update' ,             qrc: 'request',     ftr:'non_ftr' },

          { parent : 'information_ex_gratia' ,          qrc: 'query',     ftr:'ftr' },
          { parent : 'information_variance_recovery' ,          qrc: 'request',     ftr:'' },
          { parent : 'information_ng_fees' ,                    qrc: 'request',     ftr:'' },
          { parent : 'information_pan' ,                        qrc: 'request',     ftr:'ftr' },
          { parent : 'information_disbursement_request_guidance', qrc: 'query',     ftr:'ftr' },
          { parent : 'information_loan_breakup' ,               qrc: 'request',     ftr:'ftr' },
          { parent : 'information_closure_process' ,            qrc: 'query',       ftr:'non_ftr' },
          { parent : 'information_loan_outstanding' ,           qrc: 'query',       ftr:'ftr' },
          { parent : 'information_merchant_app_login' ,         qrc: 'request',     ftr:'non_ftr' },
          { parent : 'information_check_bounce_reason' ,        qrc: 'request',     ftr:'non_ftr' },
          { parent : 'information_variance_amount' ,            qrc: 'request',     ftr:'' },
          { parent : 'information_statement_discrepancy' ,      qrc: 'complaint',   ftr:'non_ftr' },
          { parent : 'information_discrepancy_statement' ,      qrc: 'query',       ftr:'non_ftr' },
          { parent : 'information_loan_rejection_reason' ,      qrc: 'request',     ftr:'ftr' },
          { parent : 'information_customer_unclear_issue' ,     qrc: 'query',       ftr:'ftr' },
          { parent : 'information_website_call_back' ,          qrc: 'request',     ftr:'ftr' },
          { parent : 'information_branch_office_details' ,      qrc: 'request',     ftr:'ftr' },
          { parent : 'information_suspicious_transaction' ,     qrc: 'system',      ftr:'non_ftr' },
          { parent : 'information_credit_limit' ,               qrc: 'query',       ftr:'ftr' },
          { parent : 'information_account_status' ,             qrc: 'query',       ftr:'ftr' },
          { parent : 'information_account_purchases' ,          qrc: 'query',       ftr:'ftr' },
          { parent : 'information_payment_guidance' ,           qrc: 'query',       ftr:'ftr' },
          { parent : 'information_clarification_of_details',    qrc: 'query',       ftr:'ftr' },
          { parent : 'legal_withdrawl' ,                        qrc: 'request',     ftr:'non_ftr' },
          { parent : 'legal_clarification' ,                    qrc: 'query',       ftr:'ftr' },

          { parent : 'pos_machine_card' ,                       qrc: 'request',     ftr:'non_ftr' },
          { parent : 'pos_machine_technical_issue' ,            qrc: 'request',     ftr:'non_ftr' },
          { parent : 'pos_machine_termainal_handover' ,         qrc: 'request',     ftr:'non_ftr' },
          { parent : 'pos_machine_paper_rolls' ,                qrc: 'request',     ftr:'non_ftr' },
          { parent : 'pos_machine_terminal_deactivation' ,      qrc: 'request',     ftr:'non_ftr' },
          { parent : 'pos_machine_terminal_installation' ,      qrc: 'request',     ftr:'non_ftr' },
          { parent : 'pos_machine_reversal_ng_fees',            qrc: 'query',       ftr:'ftr' },

          { parent : 'sales_fresh_loan' ,                       qrc: 'query',       ftr:'ftr' },
          { parent : 'sales_renewal' ,                          qrc: 'request',     ftr:'ftr' },
          { parent : 'sales_misselling' ,                       qrc: 'complaint',   ftr:'non_ftr' },

          { parent : 'suggestion_feedback' ,                    qrc: 'request',     ftr:'ftr' },

          { parent : 'others_others' ,                          qrc: 'query',       ftr:'ftr' },
          { parent : 'others_merchant_portal_access' ,          qrc: 'request',     ftr:'non_ftr' },
          { parent : 'others_account_setup_issue_in_plms' ,     qrc: 'request',     ftr:'non_ftr' },
          { parent : 'others_spam_email_ack' ,                  qrc: 'query',       ftr:'ftr' },
          { parent : 'others_DSA_Payout' ,                      qrc: 'query',       ftr:'ftr' },
          { parent : 'others_mails_incorrectly_marked' ,        qrc: 'query',       ftr:'ftr' },

          { parent : 'penalty_cheque_bounce' ,                  qrc: 'query',       ftr:'ftr' },
          { parent : 'penalty_ach_bounce' ,                     qrc: 'query',       ftr:'ftr' },
          { parent : 'penalty_legal_charge' ,                   qrc: 'query',       ftr:'ftr' },
          { parent : 'penalty_late_payment' ,                   qrc: 'query',       ftr:'ftr' },
          { parent : 'penalty_interest' ,                   qrc: 'query',       ftr:'ftr' },
            
          { parent : 'paylater_welcome_call' ,                  qrc: 'request',     ftr:'non_ftr' },
          { parent : 'paylater_registered_email' ,              qrc: 'request',     ftr:'ftr' },
          { parent : 'paylater_interest_penalty_charges_calculation' ,  qrc: 'request',     ftr:'ftr' },
          { parent : 'paylater_business_residence_address' ,    qrc: 'request',     ftr:'non_ftr' },
          { parent : 'paylater_credit_period' ,                 qrc: 'query',       ftr:'ftr' },
          { parent : 'paylater_transferring_repayment' ,        qrc: 'request',     ftr:'ftr' },
          { parent : 'paylater_registered_mobile' ,             qrc: 'request',     ftr:'ftr' },
          { parent : 'paylater_due_amount_paid_getting_follow_up_calls' ,  qrc: 'query',  ftr:'non_ftr' },
          { parent : 'paylater_statement_not_received' ,        qrc: 'request',     ftr:'ftr' },
          { parent : 'paylater_purchasing_on_partner' ,         qrc: 'query',       ftr:'ftr' },
          { parent : 'paylater_updates_available_limit' ,       qrc: 'query',       ftr:'ftr' },
          { parent : 'paylater_bill_due_date' ,                 qrc: 'query',       ftr:'ftr' },
          { parent : 'paylater_billing_address' ,               qrc: 'request',     ftr:'ftr' },
          { parent : 'paylater_payment_through_neft' ,          qrc: 'query',       ftr:'ftr' },
          { parent : 'paylater_current_available_balance_not_correct' ,    qrc: 'query',     ftr:'ftr' },
          { parent : 'paylater_sms_received_not_purchased' ,    qrc: 'query',     ftr:'non_ftr' },
          { parent : 'paylater_otp_not_received' ,              qrc: 'query',     ftr:'non_ftr' },
        ];
    </script>
EOQ1;
        
        global $current_user, $db;

        $userId = $current_user->id;

        $isAdmin            = $current_user->is_admin;
        $access=in_array($current_user->user_name,array('Roshni.Shaikh','NG866','NG478','Rohan.Supugade','hamza.thariya','Rohit.Ghag','NG722','bhargav.boda','sudhir.manwada','saajan.simon','NG887','Gaurav.Bavkar'));
        $case_created_by    = $this->bean->attended_by_c;
        $old_category       = $this->bean->fetched_row->case_category_c;
        
        $old_subcategory    = $this->bean->fetched_row->case_subcategory_c;
        $new_category       = $this->bean->case_category_c;
        //echo $old_category;exit;
        $new_sub_categoty   = $this->bean->case_subcategory_c;
        $userDepartment     = $current_user->department;

        $departmentUserIDs = array();       

        $queryToGetUserOfTheDepartment = "SELECT 
                                            id, 
                                            department, 
                                            user_name, 
                                            LTRIM(RTRIM(CONCAT(IFNULL(first_name,''),' ',IFNULL(last_name, '')))) as user_full_name 
                                         FROM
                                            users 
                                        WHERE 
                                            department= '$userDepartment'";

        $departmentUsersData = $db->query($queryToGetUserOfTheDepartment);

        while($row = $db->fetchByAssoc($departmentUsersData)){

            array_push($departmentUserIDs, $row['user_full_name']);
        }
        
        require_once('custom/include/ng_utils.php');

        $utils = new Ng_utils();

        $users = $utils->getUserNameForRole(null,'Customer support executive Assignment Dynamic');

        foreach($users as $user){

            $name = $user['name'];

            array_push($departmentUserIDs, $name);

        }
        
        parent::display();

        echo $jsscript; //echo the script

        $on_load = 1;   //on load is used for added jquery logics

        echo $jsscript_case_details_c;

        global $mod_strings;    

        global $sugar_config;
        
        global $current_user;

        /*
        role type = 1 //admin
        role type = 2 //Customer support executive Assignment Dynamic
        role type = 3 //Customer support executive
        role type = 4 // non support
        */

        $groupFocus = new ACLRole();

        $roles = $groupFocus->getUserRoles($current_user->id);
        
        if($current_user->is_admin){

            $role_type=1;

        }else if(in_array('Case Manager',$roles)) {

            $role_type=1;

        }else if(in_array('Customer support executive Assignment Dynamic',$roles) || in_array('Customer support executive Assignment',$roles) ) {

            $role_type=2;

        }else if(in_array('Customer support executive',$roles)) {

            $role_type=3;

        }else{

            $role_type=4;

        }
        ?>
            <script>
                $(document).on('change','#case_details_c',function(){
                    if($(this).val() == 'information_closure_process_campaign' && 
                    $('#case_category_c').val() =='information' && 
                    $('#case_subcategory_c').val()=='information_closure_process'){
                            $('#case_sub_source_c').val('others');
                        }
                });
                var role_type = <?php echo json_encode($role_type); ?>;
                var isAdmin = <?php echo $isAdmin; ?>

                console.log(role_type);
               
                if(role_type !=1 && role_type !=2){

                    $( "#case_action_code_c" ).click(function( event ) {

                        $(this).children('option:not(:selected)').prop('disabled', true);

                        return false;

                    });
                }

                $( "#type" ).hide();
                $( "#case_source_c" ).hide();


                $(document).on('change','#case_subcategory_c',function(){
                    
                    var str1 = $(this).val();
                    var str2 = "terminal";
                    if(str1.indexOf(str2) != -1){
                        //$('#processor_name_c').show();
                        $('#processor_name_c').hide();
                    } else {
                        $('#processor_name_c').hide();
                    }
                });
            </script>
        <?php
        $new = empty($this->bean->id);
        
        if($new){
            
            ?>
                <script>
                    // $(document).on('click','#SAVE_HEADER',function(){
                    //     alert(77);
                    // });
                    if(role_type>1)
                    {
                        $( "#priority" ).prop( "disabled", true );
                    }

                    console.log("role_type "+role_type);

                    $(document).ready(function(){

                        $('#update_text').closest('td').html('');

                        $('#update_text_label').closest('td').html('');

                        $('#internal').closest('td').html('');

                        $('#internal_label').closest('td').html('');

                        $('#addFileButton').closest('td').html('');

                        $('#case_update_form_label').closest('td').html('');

                        var state=document.getElementById('state');

                        state.options[2].disabled="true";

                        state.options[3].disabled="true";

                        $('#complaintaint_c').attr('disabled', 'disabled');

                        $('#attended_by_c').attr('disabled', 'disabled');

                    });

                        $("#LBAL_c").hide();

                        $("#min_preclosure_amount_c").hide();

                        $("#proposed_preclosure_amount_c").hide();
                        
                    $("#case_subcategory_c").change(function(){

                        // console.log('here');
                        if($(this).val()=='information_closure_process'){

                            console.log("ok");

                            $("#LBAL_c").show();

                            $("#min_preclosure_amount_c").show();

                            $("#proposed_preclosure_amount_c").show();

                        }

                        if($(this).val()!='information_closure_process'){

                            $("#LBAL_c").hide();

                            $("#min_preclosure_amount_c").hide();

                            $("#proposed_preclosure_amount_c").hide();

                        }

                        var sub_category_c = $(this).val();
                        console.log(sub_category_c);

                        $(qrc_ftr_mapping).each(function(index, value){ //loop through your elements

                            if(value.parent == sub_category_c){ //check the sub_category_c

                                console.log('qrc_ftr_mapping');
                                console.log(value);
                                $("#type").val(value.qrc); //select the option element as a string
                                if(role_type>1)
                                {
                                    $('#priority').children('option:not(:selected)').prop('disabled', true);
                                    //return false;
                                    //$( "#priority" ).prop( "disabled", false );
                                }
                                if(value.qrc=='query')
                                {
                                    $('#priority').val('P3');
                                }
                                else if(value.qrc=='request')
                                {
                                    $('#priority').val('P2');
                                }
                                else if(value.qrc=='complaint')
                                {
                                    $('#priority').val('P1');
                                }
                                else if(value.qrc=='system')
                                {
                                    $('#priority').val('P2');
                                }
                                $("#case_action_code_c").val(value.ftr); //select the option element as a string
                            }
                        });
                    });
                    
                    $("#case_category_c").change(function(){

                        $("#type").val(''); 
                        $("#case_action_code_c").val('');

                    });

                    $('#case_source_c').val('internal');

                    if(role_type==1 || role_type==2){

                        $('#assigned_user_name').show();
                        $('#btn_assigned_user_name').removeAttr('disabled');
                        $('#btn_clr_assigned_user_name').removeAttr('disabled');

                    } else {

                        $('#assigned_user_name').hide();
                        $('#btn_assigned_user_name').attr('disabled', 'disabled');
                        $('#btn_clr_assigned_user_name').attr('disabled', 'disabled');
                    }
                    
                </script>
            <?php
           
        } else { //if($new){
            ?>
            
                <script>  

                  $('#complaintaint_c').attr('disabled', 'disabled');
                  // $('#attended_by_c').attr('disabled', 'disabled');
                    $(document).ready(function(){    
                      $("#merchant_establisment_c").prop("readonly", true);
                    });
                    console.log('in edit case role_type ='+role_type);
                        $('#assigned_user_name').show();
                        $('#btn_assigned_user_name').removeAttr('disabled');
                        $('#btn_clr_assigned_user_name').removeAttr('disabled');
                    
                    if(role_type==3 || role_type==4){
                       
                    	$('#resolution').attr('disabled', 'disabled');
                        $('#case_source_c').attr('disabled', 'disabled');
                        var state=document.getElementById('state');
                        state.options[2].disabled="true";
                        state.options[3].disabled="true";
                        $('#case_action_code_c').children('option:not(:selected)').prop('disabled', true);
                        $('#attended_by_c').attr('disabled', 'disabled');
                        $('#complaintaint_c').attr('disabled', 'disabled');
                        validate_assigned_user();
                    }
                    if(role_type==2){
                        $('#attended_by_c').attr('disabled', 'disabled');
                    }
                    if(role_type!=1){
                        var priority_level = $("#priority").val();
                        // console.log(priority_level);
                        if(priority_level == 'P4'){
                            $("#sub_priority_c").attr('disabled','disabled');
                            $("#priority").attr('disabled','disabled');       
                        }
                        else{
                            $("#sub_priority_c").hide();
                            $("#sub_priority_label").hide();
                            $("#priority option[value='P4']").remove();                           
                        }
                    }         
                    
                    
                    
                </script>
                <style>
                  
                  .required{
                    box-shadow: 4px 4px 20px rgba(200, 0, 0, 0.85);
                  }
                  

                </style>
            <?php
                } //else if($new){
                
            ?>
            <script>
                $('#not_apply_c').change(function() {
                    validates();
                    });

              // validate();
            	function disableSave(){
                    $("input[type=submit]").attr("disabled", "disabled").css("opacity", "0.5");
                    $('#save_and_continue').attr("disabled", "disabled").css("opacity", "0.5");
                }

                function enableSave(){
                  $('input[type="submit"]').prop("disabled", false).css("opacity", "1");
                  $('#save_and_continue').prop("disabled", false).css("opacity", "1");
                }

                 $('#state').change(function(){
                   validates();
                 });

                 $('#sub_priority_c').change(function(){
                   validates();

                 });
                
                $('#financial_year_c').change(function(){
                  validates();
                });

                $('#processor_name_c,#case_subcategory_c').change(function(){
                  validates();
                });
                $('#quarter_c').change(function(){
                  validates();
                });
                // $('#assigned_user_id').trigger('change');
            	$('#assigned_user_name').change(function(){
                	validates();
                });

                function validateProcessorName(){
                	console.log('inside validateProcessorName');
                  if($('#processor_name_c').val()==''){
                     // $('#processor_name_c').addClass('required');
                      console.log("validateProcessorName failed");
                      return false;
                      
                    
                   }else{
                   	console.log("validateProcessorName passed");
                    //$('#processor_name_c').removeClass('required');
                      return true;
                    }
                }


                function validates(){
                
                  if(validateMinefield() && validate_assigned_user() && validateCaseDetails() && validateProcessorName()){


                  	if($('#state').val()=="Closed"||$('#state').val()=="Resolved"){
  		            		if(/*validateFinancialQuarterAndYear() &&*/ validateCaseDetails() && validateNonFTR() && validate_assigned_user()){
  		            			console.log("All validations passed");
  		            			enableSave();
  		            		}else{
  		            			console.log("Validation failed");
  		            			disableSave();
  		            		}
  		            	}else{
                      // if(){
    		            		console.log("All validations passed");
    		            		enableSave();
                      // }
  		            	}
                  }else{
                    disableSave();
                  }
	            	}
                
                    $(document).on('change','#state',function(){
                      
                        var state = $('#state').val();
                        var resolution = $('#resolution').val();
                        var len = $.trim(resolution).length;
                        if(state == 'Closed' && len <=1){ 
                            alert('Please add Resolution comment before closing a case');
                            $("input[type=submit]").attr("disabled", "disabled").css("opacity", "0.5");
                            $('#save_and_continue').attr("disabled", "disabled").css("opacity", "0.5");
                            return false;
                        
                        }
                    });
                    $(document).on('keyup','#resolution',function(){
                        
                        var state = $('#state').val();
                        var resolution = $('#resolution').val();
                        var len = $.trim(resolution).length;
                        if(state == 'Closed' && len <=1){ 
                            //alert('Please add Resolution comment before closing a cases');
                            $("input[type=submit]").attr("disabled", "disabled").css("opacity", "0.5");
                            $('#save_and_continue').attr("disabled", "disabled").css("opacity", "0.5");
                            return false;
                        } else {
                            enableSave();
                        }
                    });
                    $(document).on('click','#SAVE_HEADER',function(){
                        var state = $('#state').val();
                        var resolution = $('#resolution').val();
                        var len = $.trim(resolution).length;
                        if(state == 'Closed' && len <=1){ 
                            alert('Please add Resolution comment before closing a case');
                            return false;
                        }

                        // Check the Maker Remarks empty.
                        // var maker = $('#maker_comment_c').val();
                        // var maker_len = $.trim(maker).length;
                        // if( maker_len <= 1){ 
                        //     alert('Please add Maker remarks!');
                        //     return false;
                        // }
                    });
                
                
                function validate_assigned_user(){
                    console.log('inside validate_assigned_user');
                    var js_array_of_allowed_users_id = <?php echo json_encode($departmentUserIDs); ?>;
                    var case_owner = <?php  echo json_encode($case_created_by); ?>;
                    console.log("array of allowed user "+js_array_of_allowed_users_id);
                    
                   //  	var assigned_user_id = $('#assigned_user_id').val();
	                  // console.log("department = "+getDepartment(assigned_user_id) ) ;
                        var assigned_user = $('#assigned_user_name').val().trim();
                        if(assigned_user == ""){
                          alert("Assigned user can not be blank");
                          return false;
                        }
                        if(role_type==3 || role_type==4){
                          var owner = $('#attended_by_c').val().trim();
                          if(assigned_user == owner){
                              $('#assigned_user_error').hide();
                              // enableSave();
                              return true;
                          }
                          else if(js_array_of_allowed_users_id.includes(assigned_user)){
                              $('#assigned_user_error').hide();
                              // enableSave();
                              return true;
                          } else {
                              $('#assigned_user_error').show();
                              // disableSave();

                              var text='';
                              if(case_owner && trim(case_owner)!='')
                                  text = "You can only assign to the active users in your department or case owner( "+case_owner+" )";
                              else
                                  text = "You can only assign to the active users in your department or people from customer support.";
                              $( "<p id = 'assigned_user_error' style='color:red'>"+text+"</p>" ).insertAfter( "#btn_clr_assigned_user_name" );
  														return false;
                          }
                        }
                    return true;
                }         


                function validateNonFTR(){
                	console.log('inside validateNonFTR');
                 	// if($('#state').val()=="Closed"||$('#state').val()=="Resolved"){
                   	
		                 	if($('#case_action_code_c').val()=='non_ftr' )  {
		                 		var department = "<?= $department ?>";
		                 		// console.log(department);

		                 	// 	var user_id = $('#assigned_user_id').val().trim();
			                	// // console.log('inside getDepartment');
			                	// var url = "get_user_details.php?user_id="+user_id;
			                	// console.log(url);
			                	// $.getJSON(url, function( data ) {
			                 //        department = (data['department']);
			                 //        console.log(data);
			                 //        if(department.toLowerCase().includes('customer')){
				                //    			// console.log('here');
						              //    			alert('This is non FTR case please select correct user name before closing the case');
						              //    			disableSave();
						              //    			return false;
						              //    		}else{
						              //    			enableSave();
						              //    			return true;
						              //    		}
			                 //      });	
			                	if(department.toLowerCase().includes('customer')){
	                   			// console.log('here');
			                 			alert('This is non FTR case please select correct user name before closing the case');
			                 			console.log("validateNonFTR failed");
			                 			return false;
			                 		}else{
			                 			console.log("validateNonFTR passed");
			                 			return true;
			                 		}
		                 		
		                 }
		                	else{
		                		enableSave();
		                		return true;
		                	}
                   // }
                 }

                function validateCaseDetails(){
                	//console.log('inside 1111 validateCaseDetails');
                  if( ($('#case_details_c > option').length >1) && $('#case_details_c').val()=='' && $("#not_apply_c").is(":checked")==false){
                      $('#case_details_c').addClass('required');
                      console.log("validateCaseDetails failed");
                      return false;
                      
                    
                   }else{
                   	console.log("validateCaseDetails passed");
                      $('#case_details_c').removeClass('required');
                      return true;
                    }
                }

                function validateFinancialQuarterAndYear(){
                    console.log('inside validateFinancialQuarterAndYear');
                    if($('#case_subcategory_c').val()=='financial_live_tds_refund_adjustment' && 
                    ($('#financial_year_c').val()==''||$('#quarter_c').val()=='') ) {
                            $('#financial_year_c').addClass('required');
                            $('#quarter_c').addClass('required');
                            console.log("validateFinancialQuarterAndYear passed");
                            return false;

                    }else{
                            $('#financial_year_c').removeClass('required');
                            $('#quarter_c').removeClass('required');
                            console.log("validateFinancialQuarterAndYear passed");
                            return true;
                    }
                }

                function validateMinefield(){
                  var username = $('#current_user_name').val().toLowerCase();
                  var priority = $('#priority').val();
                  if(priority=='P4' && $.inArray(username,['ng690','ng866','ng478','ng1962','ng1647','ng2029','ng2054','ng2064'])==-1){
                    alert('This is a minefield case and cannot be saved by you');
                    disableSave();
                    console.log("validateMinefield failed");
                    return false;
                  }else{
                    enableSave();
                    console.log("validateMinefield passed");
                    return true;
                  }
                }
                
                function pre_fill_data(data){
                    $("#merchant_email_id_c").val(data['email']);
                    $("#merchant_name_c").val(data['merchant_name']);
                    $("#merchant_contact_number_c").val(data['merchant_number']);
                    $("#merchant_establisment_c").val(data['entity_name']);
                }   
                // getDepartment('58477FAC-30B2-49D6-ABDF-D4E5ABDA99D5');
                function getDepartment(){
                	var user_id = $('#assigned_user_id').val().trim();
                	console.log('inside getDepartment');
                	var url = "get_user_details.php?user_id="+user_id;
                	console.log(url);
                	var department="";
                	$.getJSON(url, function( data ) {
                        department = (data['department']);
                        console.log(data);
                      });	
                	return department;
                }

                function fill_paylater_app_details(acc_id){
                    if(!$.isNumeric( acc_id)){
                        return;
                    } 
                    $.ajax({
                        url: 'JavascriptAPICall.php?api=getApplicationDataFromLMM&application_id='+acc_id,
                        success: function (response) {
                            var data = JSON.parse(response);
                            pre_fill_data(data.merchant);
                        }
                    });
                }             
                function fill_details(app_id){
                     if(!$.isNumeric( app_id)) 
                        return;
                    // url = "/get_merchant_details?ApplicationID="+$app_id;
                    url = "get_merchant_details.php?application_id="+app_id;
                    console.log(url);
                    $.getJSON(url, function( data ) {
                        if((data.length)>0){
                            console.log(data);
                          // console.log(data[0]);
                          data = data[0];
                          // console.log(data['Applicant Email Id']);
                          $("#merchant_email_id_c").val(data['Applicant Email Id']);
                          $("#merchant_name_c").val(data['Applicant Person']);
                          $("#merchant_contact_number_c").val(data['Applicant Number']);
                          $("#merchant_establisment_c").val(data['Company Name']);
                          $("#case_location_c").val((data['Branch Name']).toLowerCase());
                        }else{
                            console.log('No user found');
                        }
                      });
                }
                
                
                $( document ).ready(function() {
                    var email_id = $("#merchant_email_id_c").val();
                    var name = $("#merchant_name_c").val();
                    var contact = $("#merchant_contact_number_c").val();
                    var establisment = $("#merchant_establisment_c").val();
                    var location = $("#case_location_c").val();
                    var app_id = $('#merchant_app_id_c').val();
                    $('#merchant_app_id_c').change(function(){
                        fill_details($('#merchant_app_id_c').val());
                    });
                    validates();
                    $('#case_category_c').change(function(){
                        var case_category = $('#case_category_c').val();
                        if(case_category == 'paylater'){
                            fill_paylater_app_details($('#merchant_app_id_c').val());
                        } else {
                            fill_details($('#merchant_app_id_c').val());
                        }
                    });
                    
                  if((email_id=="" || name== "" || contact == "" || establisment==""||location=="") && app_id != ""){
                     fill_details(app_id);
                  }
                  $("#addFileButton").on('click',function(){
                        $("input[name='case_update_file[]'").attr('multiple','multiple');
                        $("input[name='case_update_file[]'").on('change',function () {
                            var index = $(this).parent().prevAll().length;
                            var files = $(this).prop("files");
                            var names = $.map(files, function(val) { return val.name; });
                            names = names.join(' <br/> ');
                            if($("#fileNames_"+index).length == 0) {
                                $("<p id= fileNames_"+index+"> <b>Files Selected:</b><br/> "+names+" </p><br/>").appendTo($(this).parent());
                            } else {
                                $('#fileNames_'+index).html("<b>Files Selected:</b><br/> "+names+"<br/>");
                            }
                            $(".caseDocumentTypeSelect:eq("+(index)+")").on('change', function () {
                                if($(this).val()==='external'){
                                    $('#fileNames_'+index).show();
                                }else if($(this).val()==='internal'){
                                    $('#fileNames_'+index).hide();
                                }
                            });
                        });
                    });
                });

                <?php $new = empty($this->bean->id)?0:1;
                $new = empty($this->bean->case_category_c)?0:1;
                $new = empty($this->bean->case_subcategory_c)?0:1;
                $new = $this->bean->category_count_c<1?0:1;
                $csteamcheck=!in_array('Customer support executive',$roles);
                ?>
                var new_case="<?php echo $new?>";
                var csteam="<?php echo $csteamcheck ?>";
                if(new_case==1 || csteam)
                {
                $(document).ready(function(){
                    $('#maker_comment_label,#maker_comment_c').hide();
                    var env = "<?php   echo getenv('SCRM_ENVIRONMENT') ?>";
                    var maker;
                    if(env =='prod'){
                        maker =['ng478','ng866','ng887','sudhir.manwada','saajan.simon', 'ng2029','ng2054','Gaurav.Bavkar'];
                    } else {
                        maker = ['ng1273','ng1274', 'nucsoft1'];
                    }
                  
                    var username = $('#current_user_name').val().toLowerCase();
                    
                    var old_subcategory = "<?php echo $this->bean->case_subcategory_c ?>";
                    var old_category = "<?php echo $this->bean->case_category_c ?>";
                 
                 
                    if( old_subcategory =='' || old_category =='')
                    {
                        enable();
                    } else {
                       
                        disable();
                    }

                    if(jQuery.inArray(username, maker) != -1) {
                        $('#maker_comment_label , #maker_comment_c').show();
                        $('#case_subcategory_c').attr('name', 'case_subcategory_c_new_c');
                        $('#case_category_c').attr('name', 'case_category_c_new_c');
                        enable();
                        
                    }else if(isAdmin == 1 ){
                        enable();
                    }
                     
                
                });

                function disable() {
                    document.getElementById("case_subcategory_c").disabled=true;
                    document.getElementById("case_category_c").disabled=true;
                }
               
                function enable() {
                    document.getElementById("case_category_c").disabled=false;
                    document.getElementById("case_subcategory_c").disabled=false;
                }
                
                $(document).on('change','#case_category_c,#case_subcategory_c',function(){
                    var env = "<?php   echo getenv('SCRM_ENVIRONMENT') ?>";
                    var maker;
                    if(env =='prod'){
                        maker =['ng478','ng866','ng887', 'saajan.simon','sudhir.manwada','ng2029','ng2054','Gaurav.Bavkar'];
                    } else {
                        maker = ['ng1273','ng1274','nucsoft1'];
                    }
                    var cat="<?php echo $this->bean->case_category_c?>";
                    var sub_cat="<?php echo $this->bean->case_subcategory_c?>";
                    var change=0;
                    if(cat!=$('#case_category_c').val() || sub_cat!=$('#case_subcategory_c').val())
                    {  
                        change=1;
                    }
                    var username = $('#current_user_name').val().toLowerCase();
                    if(jQuery.inArray(username, maker) != -1 && change==1) {
                        var maker_remark = $('#maker_comment_c').val();
                        var len = $.trim(maker_remark).length;
                        if(len <=1){ 
                            alert('Please enter maker remark!');
                            $('#maker_comment_label').html('Maker Remark: <font color="red">*</font>'); 
                            $('#maker_comment_c').focus();
                            disableSave();
                        }
                    } else {
                        enableSave();
                    }
                    
                  
                   
                });


                $(document).on('keyup','#maker_comment_c',function(){
                    var env = "<?php   echo getenv('SCRM_ENVIRONMENT') ?>";
                    var maker_remark = $('#maker_comment_c').val();
                    var len = $.trim(maker_remark).length;
                    var maker;
                    if(env =='prod'){
                        maker =['ng478','ng866','ng887','sudhir.manwada', 'saajan.simon','ng2029','ng2054','Gaurav.Bavkar'];
                    } else {
                        maker = ['ng1273','ng1274', 'nucsoft1'];
                    }
                    var username = $('#current_user_name').val().toLowerCase();
                    if(jQuery.inArray(username, maker) != -1) {

                        if(len <=1){ 
                            $("input[type=submit]").attr("disabled", "disabled").css("opacity", "0.5");
                            $('#save_and_continue').attr("disabled", "disabled").css("opacity", "0.5");
                            return false;
                        } else {
                            enableSave();
                        }
                    }
                });
                } else{
                    $('#maker_comment_label,#maker_comment_c').hide();
                }

                
            </script>
        <?php
      
        $tiny = new SugarTinyMCE();
        echo $tiny->getInstance('update_text,description', 'email_compose_light');
       
        ?>
        <script>
        $(document).ready(function()
        {
            var l_value = "<?php echo $this->bean->LBAL_c ?>";
            setTimeout(function() {
                var myAttr = $('#LBAL_c').attr('style');
                
                if (typeof myAttr !== 'undefined' && myAttr !== false) {
                    
                    if($('#LBAL_c').val() >= 0){
                        $('#LBAL_c').removeAttr('style');
                        $('#LBAL_c').val(l_value);
                        
                    }
                }
            }, 1700);
        });
        </script>
        <?php
    }
}
