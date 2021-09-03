<?php
echo "<div class='detail'> <h4>SMS/EMAIL Payment Link</h4></div>";

		echo $HTML = <<<DISP10
		<form id='payment_link_form'>
			<table class='table'>
				<tr>
					<td></td>
					<td colspan='1'><input type='button' value='Send Payment Link' id='send_payment_link' name='send_payment_link'/></td>
				</tr>
			</table>
			<div id='payment_mail_status'></div>
		</form>
			<script>
				function validateTo(){
					var mobile_regex = /^\d{10}$/;
					var contact_no = ($('#contact_number').text()).trim();
					if(!contact_no.match(mobile_regex)){
				        alert("Mobile format should be 10 digit number");
				        return false;
					}
					return true;
				}
				$('#send_payment_link').click(function(){
					//console.log("inside function");
					if(validateTo()){
						var input = confirm('Are you sure you want to send the message & email to the customer?');
						if(input){
							var mobile_number 	= trim($('#contact_number').val());
							var email_id 		= trim($('#applicant_email_id').val());
							var contact_details	= [mobile_number, email_id,];
							var url = "./index.php?entryPoint=CustomDynamicCalls";
							//console.log(contact_details);
							$("#payment_mail_status").html("<p style='color:green'>Sending Link Via SMS/EMail......Kindly wait till confirmation</p>");
							$.ajax({
								type: "POST",
								url: url,
								data: {"contact_details":contact_details,"payment_link":1},
								async: true,
								success: function(data){
									console.log(data);
									$("#payment_mail_status").html("<p style='color:green'>" + data + "</p>");
									alert('Done');
								},
								error: function (XMLHttpRequest, textStatus, errorThrown) {
									alert('Some Error. Cannot send Payment Link via SMS/Email');
									$("#payment_mail_status").html("<p style='color:red'>Failed to sent SMS/Email.</p>");
									console.log(textStatus+' '+errorThrown);
								}
							});	
						}
					}
					return false;
				});
			</script>

DISP10;
?>