<?php
echo "<div class='detail'> <h4>SMS Merchant</h4></div>";
		$sms_template_list = $GLOBALS['app_list_strings']['sms_template_list'];
		$sms_template_options = "";
		$sms_templates = array();
		foreach ($sms_template_list as $key => $value) {
			$sms_template_options .="<option name='$key' value='$key' data-description='$value'>$key"; 
			$sms_template_options .="</option>"; 
			$sms_templates[$key] = $value;
		}
		echo $HTML = <<<DISP8
		<form id='smsForm'>
			<input type='hidden' id='module' name='module' value='Administration'>
		    <input type='hidden' id='action' name='action' value='FireTextSMS'>
		    <input type='hidden' id='sugar_body_only' name='sugar_body_only' value='1'>
		    <input type='hidden' id='option' name='option' value='send'>
		    <input type='hidden' id='personalizeh' name='personalizeh' value='0'>
		    <input type='hidden' id='masssms' name='masssms' value='0'>
		    <input type='hidden' id='to' name='to'>
			<table class='table'>
				<tr>
				<td>Template: </td>
					<td>
						<select name='template' id='template' value='$_REQUEST[template]'>
						<option value='Select a template' selected>Select a template</option>
						$sms_template_options;
						</select>
					</td>
				</tr>
				<tr>
					<td>Context:</td>
					<td>
						<textarea rows='5' cols='70' name='sms' type='textarea' id='sms' value='$_REQUEST[sms]'>
						</textarea>
					</td>
				</tr>
				<tr>
					<td></td>
					<td colspan='1'><input type='button' value='Send SMS' id='send_sms' name='send_sms'/></td>
				</tr>
			</table>
			<p id='mail_status'></p>
		</form>
			<script>
				function validateTo(){
					var mobile_regex = /^\d{10}$/;
					var contact_no = ($('#contact_number').text()).trim();
					if(!contact_no.match(mobile_regex)){
				        alert("Mobile format should be 10 digit number");
				        return false;
					}
					$('#to').val(trim($('#contact_number').text()));
					// $('#to').val('9787114353');
					return true;
				}
				$('#template').change(function(){
					$('textarea#sms').val($('#template option:selected').attr('data-description'));
				});
				$('#send_sms').click(function(){
					if(validateTo()){
						var input = confirm('Are you sure you want to send the message to the customer? Please verify the data in message is accurate');
						console.log($('#smsForm').serialize());
						if(input){
							var data123 = $('#smsForm').serialize();
							var url = "./index.php";
							console.log(data123);
							$.ajax({
								type: "POST",
								url: url,
								data: data123,
								async: true,
								success: function(data){
									console.log(data);
									alert('Done');
								},
								error: function (XMLHttpRequest, textStatus, errorThrown) {
									alert('Some Error. Cannot send SMS.');
									console.log(textStatus+' '+errorThrown);
								}
							});	
						}
					}
					return false;
				});
			</script>

DISP8;
?>