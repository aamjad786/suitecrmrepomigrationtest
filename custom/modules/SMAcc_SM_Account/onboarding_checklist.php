

<style> 
    .customTable {
        border: 1px solid #dddddd;
        border-collapse: collapse;
        height: 50px;
        width: 100%;
    }

    .button {
        border: none;
        padding: 5px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .table th {
        text-align: left;   
    }
</style>

<div>
    <form action="?module=SMAcc_SM_Account&action=onboardingListUpdate" method="post" enctype="multipart/form-data" id="onboarding_list_form">
    
        <h1 style="margin-top: 20px">Onboarding checklist</h1>
        <div style = "float:left; margin:10px 10px 10px 0px;" >
                        <button class = "button" id="onboarding_list_form_submit">Save Onboarding Checklist</button>
                    </div>
        <input type="hidden" id="beanID" name="beanID" value="<?php echo $beanId ?>" />


        <table style="border: 1px solid #D3D3D3;" class="customTable table">
            <tr class="customTable">
                <th class="col-lg-3">List</th>
                <th class="col-lg-2">Option 1</th>
                <th class="col-lg-3">Option 2</th>
                <th class="col-lg-3">Option 3</th>
                <th class="col-lg-3">Free Update</th>
            </tr>
            <?php
            global $app_list_strings;
            $onboardingChecklist = $app_list_strings['onboarding_list'];
           
            foreach ($onboardingChecklist as $key => $value) {
               
                if (!empty($key) && !empty($value)) {
                    ?>
                    <tr class="customTable">
                        <td class="col-lg-3" id="<?php echo "$key" ?>_name">
                            <?php echo $value; ?>
                        </td>
                        <td class="col-lg-3">
                            <?php
                            if (($key == "phone_number") || ($key == "onboarding_email_id") || ($key == "business_address") || ($key == "pan_card") || ($key == "aadhar_card") || ($key == "bank_account_registered") || ($key == "establishment_name") || ($key == "ckyc_number") || ($key == "tan_number") ) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "CONFIRMED" ?>"> <?php echo "Confirmed" ?><br>
                                <?php
                            } else if (($key == "merchant_portal") || ($key == "nps_survey_link") || ($key == "bill_desk_payment") || ($key == "deferral_cheque") || ($key == "delayed_payment_charges") || ($key == "information_nach_registration") || ($key == 'terminal_monthly_rental') || ($key == 'virtual_account') ) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "PITCHED" ?>"> <?php echo "Pitched" ?><br>
                                <?php
                            } else if (($key == "welcome_letter") || ($key == "sanction_letter") || ($key == "repayment_schedule") ||  ($key == "insurance_copy") ) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "TRIGGERED" ?>"> <?php echo "Triggered" ?><br>
                                <?php
                            } else if (($key == "onboarding_activity_status")) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "COMPLETED" ?>"> <?php echo "Completed" ?><br>
                                <?php
                            } else if (($key == "ims_documents")) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "RECEIVED" ?>"> <?php echo "Received" ?><br>
                                <?php
                            } else if (($key == "nach_form_upload")) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "UPLOADED" ?>"> <?php echo "Uploaded" ?><br>
                                <?php
                            } else if (($key == "terminal_installation")) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "INSTALLED" ?>"> <?php echo "Installed" ?><br>
                                <?php
                            } else if (($key == "nach_activation_status")) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "ACTIVATED" ?>"> <?php echo "Activated" ?><br>
                                <?php
                            } else if (($key == "advanced_amount")|| ($key == 'repayment_amount') || ($key == 'repayment_mode') || ($key == 'loan_tenure_in_days') || ($key == 'emi_amount_as_per_frequency') || ($key == 'processing_fees') || ($key == 'loan_funded_date') ) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "INFORMED" ?>"> <?php echo "Informed" ?><br>
                                <?php
                            } else if (($key == "ims_flagging")) {
                                ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "DONE" ?>"> <?php echo "Done" ?><br>
                                <?php }
                            ?>
                        </td> 

                        <td class="col-lg-3">
                            <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "PENDING" ?>"> <?php echo "Pending" ?><br>
                        </td> 
                        <td class="col-lg-1">
                            <?php if(($key == "ckyc_number") || ($key == 'tan_number')){
                            ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "NOT_AWARE" ?>"> <?php echo "Not Aware" ?><br>
                            <?php
                            } else if(($key == "insurance_copy") || ($key == 'terminal_monthly_rental')|| ($key == 'processing_fees') || ($key == 'terminal_installation')){
                            ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "NOT_APPLICABLE" ?>"> <?php echo "Not Applicable" ?><br>
                            <?php
                            
                            } else if(($key == "nach_activation_status")){
                            ?>
                                <input type="radio" name="<?php echo "$key" ?>" value="<?php echo "REJECTED" ?>"> <?php echo "Rejected" ?><br>
                            <?php
                            } else { ?>
                                <span></span>
                           <?php  }?>
                        </td>
                        <td class="col-lg-2">
                            <textarea cols="10" rows="1" name="description_data<?php echo "$key" ?>" id="description_data<?php echo "$key" ?>"></textarea><br>
                        </td>
                    </tr>
                <?php }
            }
            ?>
        </table>
    </form>
</div>

<?php
global $db, $app_list_strings;
$onboardingChecklistArray = array();
$onboardingChecklistQuery = "SELECT * from onboarding_checklist";
$responseOfOnboardingChecklist = $db->query($onboardingChecklistQuery);
while ($row = $db->fetchByAssoc($responseOfOnboardingChecklist)) {
    $onboardingChecklistArray[$row['id']] = $row['list'];
}
$getOnboardingMapping = "SELECT * from smaccount_onboarding_mapping where smacc_sm_account_id = '$beanId'";
$onboardingListSavedData = $db->query($getOnboardingMapping);
while ($row = $db->fetchByAssoc($onboardingListSavedData)) {
    $onboardingChecklistId = $row['onboarding_checklist_id'];
    $statusCode = $row['status'];
    $description = $row['description'];
    $option = !empty($onboardingChecklistArray[$onboardingChecklistId])?$onboardingChecklistArray[$onboardingChecklistId]:'';
    $status = array_search($statusCode, $app_list_strings['onboarding_checklist_status']);
    ?>
    <script>
        var status = '<?php echo $status; ?>';
        var description = '<?php echo $description; ?>';
        var element = '<?php echo $option; ?>';
        $('#description_data'+element).val(description);
        console.log(element);
        console.log(status);
        $("input[name='" + element + "'][value='" + status + "']").prop('checked', true);

    //            $('#'+element).html(status);
    </script>
<?php }
?>
