<style type="text/css">
#dlg_c .yui-panel .bd{padding: 10px 0 0 0;}
#dlg_c .bd .yui-icon{margin-right:0}
</style>
<?php  
function custom_trim($phoneNumber)
{
    $phoneNumber = str_replace(" ", "", trim($phoneNumber));
    if(strlen($phoneNumber)==10){
        return $phoneNumber;

    }else if(strlen($phoneNumber)>10){
        return substr($phoneNumber, -10);
    }
}

?>
    <form id='form_fireTextSMS123' name='form_fireTextSMS' method='POST' action='index.php'>
    <input type='hidden' id='module' name='module' value='Administration'>
    <input type='hidden' id='action' name='action' value='FireTextSMS'>
    <input type='hidden' id='sugar_body_only' name='sugar_body_only' value='1'>
    <input type='hidden' id='option' name='option' value='netcore'>
    <input type='hidden' id='personalizeh' name='personalizeh' value='0'>
    <input type='hidden' id='masssms' name='masssms' value='<?php echo $masssms; ?>'>
    Campaign Name * :
    <input type='text' id='campaign-name' name='campaign-name' value=''>
  <div style="height:5px;clear:both"></div>
    <input type='hidden' id='moduleNm' name='moduleNm' value='<?php echo $moduleNm;?>'>

    <input type='hidden' id='pid' name='pid' value='<?php echo $pid; ?>'>
    <input type='hidden' id='char_limit' name="char_limit" value='<?php echo $sms_msg_length; ?>'>
    
    <div style="width:52px; float:left; <?php echo $massHide;?>">Phone #:</div> <input type='text' name='number' id='number' value='<?php echo custom_trim($phoneNumber)?>' style='<?php echo $massHide;?>'>
    <div style="clear:both"></div>
    <div style="height:5px;clear:both; <?php echo $massHide;?>"></div>
    <div style="width:52px; float:left">Template:</div> <?php $email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name','sms_only=1','name');
            ?>
    <select name="template_id" id='template_id' onchange='load_template(this.value, "<?php echo $moduleNm;?>", "<?php echo $pid; ?>", "<?php echo $masssms; ?>");'><?php echo get_select_options_with_id($email_templates_arr, "")?></select> <span id="load_template_id" style="vertical-align:middle; display:none"><img src="custom/themes/default/images/loading.gif" width="16" height="16" /></span>
    <div style="clear:both"></div>
    <div style="height:5px;clear:both"></div>
    <div style="width:52px; float:left">Message*:</div> <textarea name='sms_messag' id='sms_messag' rows='3' cols="40" onkeyup="checksms_len();" style='font-size:12px;'></textarea><br />
            <span id='max_sms_len' style='color:red; padding-left:55px'>Limit your message up to <?php echo $sms_msg_length; ?> characters only.</span>
    <div style="clear:both"></div>
    <div style="height:5px;clear:both"></div>
    <!-- (Will prepend "Hello FNAME LASTNAME, ") -->
    <br/>
 <input type="checkbox" name="personalize" id='personalize' onclick="if(this.checked)document.getElementById('personalizeh').value = '1';else document.getElementById('personalizeh').value = '0';" value="1"> Personalize message <br> 
<!-- <input type="radio" name="optionVal" onchange="document.getElementById('option').value='netcore'" value="netcore" checked> Netcore<br> -->
  <!-- <input type="radio" name="optionVal" onchange="document.getElementById('option').value='twilio'" checked value="twilio"> Twilio<br> -->
<br/>
<div style="width:52px; float:left">Date & Time:</div> 
<input type="datetime-local" id="datetime-l" name="datetime-l" value="<?php echo date("Y-m-d\TH:i:s",time()); ?>"/>
    <div style="padding-left:55px;"><input type='submit' class='button' id='send' value='Send' onclick='if(validate_sms("<?php echo $masssms;?>")) return send_sms("<?php echo $moduleNm;?>", "<?php echo $pid; ?>"); return false;' style='float:left;'></div>
    <div style="clear:both"></div>
    </form>
    <div style="height:10px;clear:both"></div>

