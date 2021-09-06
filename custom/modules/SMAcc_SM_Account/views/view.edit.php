<?php
require_once('include/MVC/View/views/view.edit.php');
require_once('include/SugarTinyMCE.php');

class SMAcc_SM_AccountViewEdit extends ViewEdit {

    function __construct() {
        parent::__construct();
    }

    function display() {
        parent::display();

        $beanId = $this->bean->id;

        if (!is_admin($GLOBALS['current_user'])) {
            ?>
            <script>
                document.getElementById("status").options[23].disabled = true;
            </script>
            <?php
        }
        require_once("custom/modules/SMAcc_SM_Account/onboarding_checklist.php");
        ?>
        <script>
            $('#create_link').hide();
            $('#advance_amount').prop("readonly", true);
            $('#email_id').prop("readonly", true);
            $('#total_repayment_amount').prop("readonly", true);
            $('#loan_tenure').prop("readonly", true);
            $('#rate_of_interest').prop("readonly", true);
            $('#repayment_frequency').prop("readonly", true);
            $('#repayment_mode').prop("readonly", true);
            $('#funded_date').prop("readonly", true);
            $('#processing_fee').prop("readonly", true);
            var attempt=$('#call_updation').val();
            var call_attempt_status = $('#call_attempt_status').val();

            $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled","disabled");
            $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");

            $(document).ready(function(){

                $("#call_updation option[value='']").attr("disabled","disabled");
                $("#call_updation option[value='attempt_one']").attr("disabled","disabled");
                $("#call_updation option[value='attempt_two']").attr("disabled","disabled");
                $("#call_updation option[value='attempt_three']").attr("disabled","disabled");
                $("#call_updation option[value='attempt_four']").attr("disabled","disabled");
                $("#call_updation option[value='attempt_five']").attr("disabled","disabled");
                $('#call_updation option:selected').prop('disabled',false);
                $('#call_updation option:selected').next().prop('disabled',false);
                var value=$('#call_attempt_status').val();
                var attempt=$('#call_updation').val();
                
                if(value == 'LANGUAGE_BARRIER'){
                    $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled","disabled");
                    $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");
                    $("#welcome_call_status option[value='NON_CONTACTABLE']").prop("disabled",false);
                    $("#welcome_call_status").val('NON_CONTACTABLE');
                    $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
			    }
                else{
                var dispositions=['LANGUAGE_BARRIER','SWITCHED_OFF','NO_ANSWER','CALL_BACK','NOT_REACHABLE','BUSY','WRONG_NUMBER'];
                if (dispositions.indexOf(value)==-1)
                {
                    $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled",'disabled');
                    $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled","disabled");
                    $("#welcome_call_status option[value='CLOSED']").attr("disabled",false);
                    $('#welcome_call_status').val('CLOSED');
                }
                else{
                    if(attempt=='attempt_five')
                    {
                        $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled",false);
                        $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");
                        $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled","disabled");
                        $("#welcome_call_status").val('NON_CONTACTABLE');
                        $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                    }
                    else{
                        $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled",'disabled');
                        $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");
                        $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled",false);
                        $("#welcome_call_status").val('IN_PROGRESS');
                        $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                    }
                }
                }
                if($("#call_updation").val()=='attempt_five')
                {
                    $("#welcome_call_status").prop('disabled','disabled');
                    $("#call_updation").prop('disabled','disabled');
                    $("#call_attempt_status").prop('disabled','disabled');
                }

                if($("#call_attempt_status").val()=="" && $("#call_updation").val()=="")
                {
                    $("#welcome_call_status").val("");
                }

                if ($('#welcome_call_status').val()=='CLOSED')
                {
                    $("#welcome_call_status").prop('disabled','disabled');
                    $("#call_updation").prop('disabled','disabled');
                    $("#call_attempt_status").prop('disabled','disabled');
                    $("#call_remark_label").append("<span class='required'>*</span>");
                    var call_remark=$('#call_remark').val();
                    var len=call_remark.length;
                        if(len<3)
                        {
                            alert('Please enter call remarks when closing the welcome call');
                            $('#call_remark').focus();
                            $('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				            $('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");
                        }
                        else{
                            $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                        }
                }
            
            });

            $(document).on('keyup','#call_remark',function(){
                if($.trim($('#call_remark').val()).length<3 && $('#welcome_call_status').val()=='CLOSED')
                {
                            $('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				            $('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");   
                }
                else{
                     $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                }
            })
            

            $(document).on('change','#call_attempt_status',function(){
			var value = $(this).val();
            var attempt=$('#call_updation').val();
			if(value == 'LANGUAGE_BARRIER'){
                $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled","disabled");
                $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");
                $("#welcome_call_status option[value='NON_CONTACTABLE']").prop("disabled",false);
                $("#welcome_call_status").val('NON_CONTACTABLE');
                $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
			}
            else{
                var dispositions=['LANGUAGE_BARRIER','SWITCHED_OFF','NO_ANSWER','CALL_BACK','NOT_REACHABLE','BUSY','WRONG_NUMBER'];
                if (dispositions.indexOf(value)==-1)
                {
                    $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled",'disabled');
                    $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled","disabled");
                    $("#welcome_call_status option[value='CLOSED']").attr("disabled",false);
                    $('#welcome_call_status').val('CLOSED');
                    $("#call_remark_label").append("<span class='required'>*</span>");
                    if($('#call_remark').val().length<3)
                    {
                        alert('Please enter call remarks when closing the welcome call');
                        $('#call_remark').focus();
                        $('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				        $('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");
                    }
                    else{
                        $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                    }
                }
                else{
                    if(attempt=='attempt_five')
                    {
                        $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled",false);
                        $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");
                        $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled","disabled");
                        $("#welcome_call_status").val('NON_CONTACTABLE');
                    }
                    else{
                        $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled",'disabled');
                        $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");
                        $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled",false);
                        $("#welcome_call_status").val('IN_PROGRESS');
                        $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                    }
                }
                
            }
		});
            

           

            $(document).on('change','#call_updation',function(){
			var value = $(this).val();
            var call_attempt_status = $('#call_attempt_status').val();
            if(call_attempt_status!='LANGUAGE_BARRIER')
            {
			
                var dispositions=['LANGUAGE_BARRIER','SWITCHED_OFF','NO_ANSWER','CALL_BACK','NOT_REACHABLE','BUSY','WRONG_NUMBER'];
                if (dispositions.indexOf(call_attempt_status)==-1)
                {
                    $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled",'disabled');
                    $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled","disabled");
                    $("#welcome_call_status option[value='CLOSED']").attr("disabled",false);
                    $('#welcome_call_status').val('CLOSED');
                    $("#call_remark_label").append("<span class='required'>*</span>");
                    if($('#call_remark').val().length<3)
                    {
                        alert('Please enter call remarks when closing the welcome call');
                        $('#call_remark').focus();
                        $('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				        $('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");
                    }
                    else{
                        $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                    }
                }
                else{
                    if(value=='attempt_five')
                    {
                        $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled",false);
                        $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");
                        $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled","disabled");
                        $("#welcome_call_status").val('NON_CONTACTABLE');
                        $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                    }
                    else{
                        $("#welcome_call_status option[value='NON_CONTACTABLE']").attr("disabled",'disabled');
                        $("#welcome_call_status option[value='CLOSED']").attr("disabled","disabled");
                        $("#welcome_call_status option[value='IN_PROGRESS']").attr("disabled",false);
                        $("#welcome_call_status").val('IN_PROGRESS');
                        $('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
                    }
                }
			}
		});
        
            
            document.getElementById("app_id").setAttribute("readonly", true);
            document.getElementById("contact").setAttribute("readonly", true);
            document.getElementById("merchant_name").setAttribute("readonly", true);
//            document.getElementById("opening_dpd_dash_group").setAttribute("readonly", true);
//            document.getElementById("current_dpd_dash_group").setAttribute("readonly", true);
//            document.getElementById("branch").setAttribute("readonly", true);
//            document.getElementById("lbal").setAttribute("readonly", true);
//            document.getElementById("d_varinace").setAttribute("readonly", true);
//            document.getElementById("d_dpd_dash").setAttribute("readonly", true);
            $('#team').prop("disabled", true);
            var bean_id = "<?php echo $beanId; ?>";

            $('#SAVE_FOOTER').click(function () { //TODO Need to handle on click of SAVE_HEADER or save_and_continue
                var status = $('#status').val();
                if (status == "send_to_collection") {
                    $.ajax({
                        url: 'serviceManagerMassSendToCollection.php',
                        type: 'POST',
                        data: {id: bean_id,
                        },
                        success: function (data) {
                            window.location.href = "index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DSMAcc_SM_Account%26action%3Dindex";
                        }
                    });
                }
            });
        </script><?php
    }

}
