<?php
require_once("custom/modules/Neo_Paylater_Leads/paylater_as_api.php");
//related to lead vcard 
/*$mandatoryDocs = "#approved_by_credit_agreement_name,#nach_form_name,#cancelled_cheque_name,#application_form_name";

$validation = "$('#approved_by_credit_agreement')[0].files.length == 0
                    || $('#nach_form')[0].files.length == 0
                    || $('#cancelled_cheque')[0].files.length == 0
                    || $('#application_form')[0].files.length == 0";

$arrayOfForms = array('ap_form' => 'Enquiry Form', 'pan' => 'Personal PAN of Application', 'res_proof' => 'Proof of Residence', 'bus_add_proof' => 'Business Address Proof', 'bus_reg_proof' => 'Business Registration Proof',
    'others' => 'Others', 'approved_by_credit_agreement' => 'Agreements', 'nach_form' => 'NACH Form', 'cancelled_cheque' => 'Cancelled Cheque', 'application_form' => 'Application Form', 'aadhaar_application' => 'Aadhaar of Application',
    'business_pan' => 'Business PAN', 'bank_statement' => 'Bank Statement', 'business_constitution_proof' => 'Business Constitution Proof', 'gst_returns' => 'GST Returns', 'audited_financials' => 'Audited Financials');
if (!empty($responseArray)) {
    foreach ($responseArray as $key => $value) {
        if (!empty($value) && ($value['DocumentType'] != "Remark")) {
            $document = $value['DocumentType'];
            $documentId = $asMapping[$document];
            if (!empty($documentId)) {
                $mandatoryDocs .= ",#" . $documentId . "_name";
                $validation .= " || $('#" . $documentId . "')[0].files.length == 0";
            }
        }
    }
}
?>
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
</style>
<div>
    <form action="?module=Neo_Paylater_Leads&action=upload" method="post" enctype="multipart/form-data" id="document_form" style="display:none;">
        <h1 style="margin-top: 20px">Upload documents :</h1>
        <input type="hidden" id="beanID" name="beanID" value="<?php echo $beanID ?>" />
        <input type="hidden" id="AS_DOC_upload" name="AS_DOC_upload" value="0" />
        <input type="hidden" id="AS_RE_UPLOAD" name="AS_RE_UPLOAD" value="0" />

        <table style="border: 1px solid #D3D3D3;" class="customTable">
            <tr class="customTable">
                <th class="col-lg-3">Document Name</th>
                <th class="col-lg-2">Upload</th>
                <th class="col-lg-3">Existing documents</th>
                <th class="col-lg-2">Answer</th>
                <th class="col-lg-3">Description</th>                
            </tr>
            <?php
            if (!empty($arrayOfForms)) {
                foreach ($arrayOfForms as $key => $value) {
                    $name = $value;
                    ?>
                    <tr class="customTable">
                        <td class="col-lg-3" id="<?php echo "$key" ?>_name">
                            <?php echo $name; ?>
                        </td>
                        <td class="col-lg-2">
                            <input type="file" name="<?php echo "$key" ?>[]" id="<?php echo "$key" ?>" multiple/> (Multiple)
                        </td> 
                        <td id="<?php echo "$key" ?>_data" class="col-lg-3">
                            <?php
                            if (!empty($allDocuments->$key) && sizeof($allDocuments->$key) > 0) {
                                foreach ($allDocuments->$key as $i => $value) {
                                    $urlArray = explode('/', $value);
                                    echo ($i + 1) . ". <a href='$value'>" . $urlArray[count($urlArray) - 1] . "</a><br/>";
                                }
                            }
                            ?>
                        </td>
                        <td class="col-lg-2" id="<?php echo "$key" ?>_answer">
                        </td>
                        <td class="col-lg-3" id="<?php echo "$key" ?>_description" >
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
            <tr class="customTable">
                <td colspan="5" class="customTable">
                    <div style = "float:right; margin:16px 20px 2px 16px;" >
                        <button class = "button" id="document_form_submit">Save</button>
                    </div>
                </td>

            </tr>
        </table>
    </form>
</div>
<script>
    var document_uploaded = "<?= $this->bean->documents_uploaded_c; ?>";
    var as_documents_uploaded_c = "<?= $this->bean->as_documents_uploaded_c; ?>";
    var AS_lead_status = $('#as_lead_status_c').val();
    if (document_uploaded == 0) {
        $("#ap_form_name, #res_proof_name, #bus_add_proof_name, #bus_reg_proof_name").append("*").css('color', 'red');
    }
    if (AS_lead_status == "Approved" && (as_documents_uploaded_c == 0)) {
        // $("#approved_by_credit_agreement_name, #nach_form_name, #cancelled_cheque_name, #application_form_name").append("*").css('color', 'red');
        $("<?= $mandatoryDocs; ?>").append("*").css('color', 'red');
    }
    $('#document_form_submit').click(function () {
        if (document_uploaded == 0) {
            if ($('#ap_form')[0].files.length == 0
                    || $('#bus_add_proof')[0].files.length == 0
                    || $('#bus_reg_proof')[0].files.length == 0
                    || $('#res_proof')[0].files.length == 0
                    ) {
                alert('Please input mandatory files marked by star');
                return false;
            } else {
                uploadDoc();
            }
        } else if ((document_uploaded == 1) && (AS_lead_status == "Approved") && (as_documents_uploaded_c == 0)) {
            if (<?php echo $validation; ?>) {
                alert('Please upload mandatory AS document files marked red');
                return false;
            } else {
                $('input[name=AS_DOC_upload]').val('1');
                uploadDoc();
            }
        } else {
            uploadDoc();
        }

        // return false;
    });

    function uploadDoc() {
        $("form#document_form").submit(function (e) {
            e.preventDefault();
            $('#document_form').hide();
            $('#uploading').show().text("Documents are being uploaded, please wait");
            disposition = $('#disposition').val();
            subdisposition = $('#subdisposition').val();
            $('input[name=AS_RE_UPLOAD]').val('1');
            var formData = new FormData($("#document_form")[0]);
            var form = $("#document_form");
            $.ajax({
                url: $(form).prop("action"),
                type: 'POST',
                data: formData,
                success: function (data) {
                    $('input[type="submit"]').show();
                    $('#save_and_continue').show();
                    var _form = document.getElementById('EditView');
                    _form.action.value = 'Save';
                    if (check_form('EditView'))
                        SUGAR.ajaxUI.submitForm(_form);
                    $('#uploading').hide();
//                    $('#upload_success').show();
                    $('#document_form').hide();
                },
                error: function (data) {
                    $('#upload_failure').show().text("Document upload failed, please try again");
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    }
</script>
<?php
//This is here because, all the HTML elements should be loaded before I append the values to the same.
if (!empty($responseArray)) {
    foreach ($responseArray as $key => $value) {
        if (!empty($value) && ($value['DocumentType'] != "Remark")) {
            $document = $value['DocumentType'];
            $documentId = $asMapping[$document];
            $answer = $value['Answer'];
            $description = $value['Description'];
            ?>
            <script>
                $('#<?= $documentId ?>_answer').append('<?= $answer ?>');
                $('#<?= $documentId ?>_description').append('<?= $description ?>');
            </script>   
            <?php
        }
    }
}*/
?>
