<?php

class neo_Paylater_OpenViewDetail extends SugarView {

    /**
     * @see SugarView::$type
     */
    public $type = 'detail';

    /**
     * @var DetailView2 object
     */
    public $dv;

    /**
     * Constructor
     *
     * @see SugarView::SugarView()
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay() {
        $metadataFile = $this->getMetaDataFile();
        $this->dv = new DetailView2();
        $this->dv->ss = & $this->ss;
        $this->dv->setup($this->module, $this->bean, $metadataFile, get_custom_file_if_exists('include/DetailView/DetailView.tpl'));
    }

    /**
     * @see SugarView::display()
     */
    public function display() {

        global $db;
        $this->dv->process();
        echo $this->dv->display();
        $applicationId = $this->bean->application_id;
        ?>
        <div>
            <div id="detailpanel_1" class="detail view  detail508 expanded">
                <table id="DEFAULT" class="panelContainer" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="12.5%" scope="col">
                                Sanctioned Limit:
                            </td>
                            <td class="" type="varchar" field="sanctioned_limit" width="37.5%">
                                <span class="sugar_field" id="sanctioned_limit"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Processing Fee:
                            </td>
                            <td class="" type="name" field="processing_fee" width="37.5%">
                                <span class="sugar_field" id="processing_fee"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Tenure:
                            </td>
                            <td class="" type="varchar" field="tenure" width="37.5%">
                                <span class="sugar_field" id="tenure"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Account Valid Till:
                            </td>
                            <td class="" type="varchar" field="account_valid_till" width="37.5%">
                                <span class="sugar_field" id="account_valid_till"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Mode of Repayment:
                            </td>
                            <td class="" type="varchar" field="mode_of_payment" width="37.5%">
                                <span class="sugar_field" id="mode_of_payment">NEFT/ IMPS/ RTGS</span>
                            </td>
                            <td width="12.5%" scope="col">
                                Interest Rate:
                            </td>
                            <td class="" type="enum" field="interest_rate" width="37.5%">
                                <span class="sugar_field" id="interest_rate">2%</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Available Limit:
                            </td>
                            <td class="" type="varchar" field="available_limit" width="37.5%">
                                <span class="sugar_field" id="available_limit"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Account Activation Date:
                            </td>
                            <td class="" type="enum" field="account_activation_date" width="37.5%">
                                <span class="sugar_field" id="account_activation_date"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Account Status:
                            </td>
                            <td class="" type="varchar" field="account_status" width="37.5%">
                                <span class="sugar_field" id="account_status"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Total Amount Due:
                            </td>
                            <td class="" type="enum" field="total_amount_due" width="37.5%">
                                <span class="sugar_field" id="total_amount_due"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Minimum Amount Due:
                            </td>
                            <td class="" type="varchar" field="minimum_amout_due" width="37.5%">
                                <span class="sugar_field" id="minimum_amout_due"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Payment Due Date:
                            </td>
                            <td class="" type="enum" field="payment_due_date" width="37.5%">
                                <span class="sugar_field" id="payment_due_date"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Principal Outstanding:
                            </td>
                            <td class="" type="varchar" field="principal_outstanding" width="37.5%">
                                <span class="sugar_field" id="principal_outstanding"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Interest Outstanding:
                            </td>
                            <td class="" type="enum" field="interest_outstanding" width="37.5%">
                                <span class="sugar_field" id="interest_outstanding"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Finance Charges Outstanding:
                            </td>
                            <td class="" type="varchar" field="finance_charges_outstanding" width="37.5%">
                                <span class="sugar_field" id="finance_charges_outstanding"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Statement:
                            </td>
                            <td class="" type="enum" field="statement" width="37.5%">
                                <span class="sugar_field" id="statement"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Merchant Bank Name:
                            </td>
                            <td class="" type="varchar" field="bank_name" width="37.5%">
                                <span class="sugar_field" id="bank_name"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Merchant Bank Details: 
                            </td>
                            <td class="" type="enum" field="merchant_bank_details" width="37.5%">
                                <span class="sugar_field" id="merchant_bank_details"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Beneficiary Name:
                            </td>
                            <td class="" type="varchar" field="benificiary_name" width="37.5%">
                                <span class="sugar_field" id="benificiary_name"> NeoGrowth Credit Private Limite</span>
                            </td>
                            <td width="12.5%" scope="col">
                                IFSC Code:
                            </td>
                            <td class="" type="enum" field="ifsc_code" width="37.5%">
                                <span class="sugar_field" id="ifsc_code">YESB0CMSNOC</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="12.5%" scope="col">
                                Virtual Account Number:
                            </td>
                            <td class="" type="varchar" field="account_number" width="37.5%">
                                <span class="sugar_field" id="account_number"></span>
                            </td>
                            <td width="12.5%" scope="col">
                                Bill till date:
                            </td>
                            <td class="" type="enum" field="bill_till_date" width="37.5%">
                            <span class="sugar_field" id="bill_till_date"><a href="paylater_open_bill_generator.php?application_id=<?php echo $applicationId; ?>" target = "_blank">Generate Bill</a></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        $emailVerification = $this->bean->is_primary_email_verified;
        $secondaryEmailVerification = $this->bean->is_secondary_email_verified;

        $email = $this->bean->email_id;
        $secondaryEmail = $this->bean->alternate_email_id;
        ?>
        <script>
        var phoneNumber =  "<?php echo $this->bean->phone_number; ?>";
        var moduleName = "neo_Paylater_Open";
        var beanId = "<?php echo $this->bean->id; ?>";
        var alternate_phone_number = "<?php echo $this->bean->alternate_phone_number; ?>";
        $('#phone_number').append('&nbsp;&nbsp;<img style="border:none;cursor:pointer;" title="Click to make a call. Opening the editor may take a moment. Please give it some time." src="custom/themes/default/images/cellphone.gif" onclick="openPopupAjax(\''+phoneNumber+'\',\''+moduleName+'\', \''+beanId+'\', \'0\');">&nbsp;');
        if(alternate_phone_number !== ''){
            $('#alternate_phone_number').append('&nbsp;&nbsp;<img style="border:none;cursor:pointer;" title="Click to make a call. Opening the editor may take a moment. Please give it some time." src="custom/themes/default/images/cellphone.gif" onclick="openPopupAjax(\''+alternate_phone_number+'\',\''+moduleName+'\', \''+beanId+'\', \'0\');">&nbsp;');
        }   
        $('#create_link').hide();
        <?php if (!empty($email) && ($emailVerification != 1)) { ?>
                $("#email_id").append("<span id = 'email_id_link'><a href='javascript:;'> (Send Email Verification link)</a></span>");
        <?php }
        ?>
            $.ajax({
                url: 'JavascriptAPICall.php?api=getApplicationDataFromLMM&application_id=<?php echo $this->bean->application_id; ?>',
                success: function (response) {
                    var obj = $.parseJSON(response);
                    $('#sanctioned_limit').html(obj.credit_limit);
                    $('#processing_fee').html(obj.processing_fee);
                    $('#tenure').html("22 Months");
                    $('#account_valid_till').html(obj.account_valid_till);
                    $('#available_limit').html(obj.available_limit);
                    $('#account_activation_date').html(obj.activation_date);
                    $('#account_status').html(obj.account_status);
                    $('#total_amount_due').html(obj.account_ledger.statement_amount);
                    $('#minimum_amout_due').html(obj.account_ledger.minimum_payment);
                    $('#payment_due_date').html(obj.account_ledger.payment_due_date);
                    $('#principal_outstanding').html(obj.principal_outstanding);
                    $('#interest_outstanding').html(obj.current_interest);
                    $('#finance_charges_outstanding').html(obj.account_charges_outstanding);
                    $('#statement').html('<a href = ' + obj.account_ledger.statement_url + ' target="_blank" >'+obj.account_ledger.statement_url+'</a>');
                    $('#bank_name').html('YES Bank');
                    $('#merchant_bank_details').html(obj.bank_details.account_number);
                    $('#account_number').html('868686' + obj.account_number);                    

                }
            });

            $("#email_id_link").click(function () {
                $.ajax({
                    url: 'JavascriptAPICall.php?api=SendVerificationEmail&email=<?php echo $email ?>&application_id=<?php echo $this->bean->application_id; ?>',
                    success: function (result) {
                        console.log(result);
                        if(result !== ''){ 
                            alert("Email has been sent successfully");
                        };
                    }
                });
            });
        </script>


        <?php
    }

    function setDecodeHTML() {
        $this->bean->description = html_entity_decode(($this->bean->description));
    }

}
