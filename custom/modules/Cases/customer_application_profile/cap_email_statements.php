<?php

		echo "<div class='daetail'> <h4>Email Statements</h4></div>";	
		$current_month =date('Y-m-d');
		echo $HTML = <<<DISP7
		<br>
		<form>
		<table class='table'>
		<tr>
			<td>Document Type: </td>
			<td>
				<input type='radio' name='document_type' value='2' /> &nbspInterest Certificate&nbsp&nbsp
				<input type='radio' name='document_type' value='1' /> &nbspLoan Statement&nbsp&nbsp
				 <input type='radio' name='document_type' id='sanction_letter' value='4' /> <span id='sanction_letter_label'>&nbspSanction Letter&nbsp&nbsp </span>
				<input type='radio' name='document_type' value='3' /> &nbspRepayment Schedule
				<input type='radio' name='document_type' id="welcome_letter" value='6' /> <span id="welcome_letter_label">&nbspWelcome Letter</span>
            	 <input type='radio' name='document_type' id='agreement' value='7' /> <span id='agreement_label'>&nbspLoan agreement</span>
			</td>
		</tr>
		<tr id='tr_from' style='visibility:collapse'>
			<td>From Date:</td>
			<td>
				<input type='date' id='start_month' name='start_month' min="{$funded_date}" max="{$current_month}">
				
			</td>
		</tr>
		<tr id='tr_to' style='visibility:collapse'>			
			<td>To Date:</td>
			<td>
				<input type='date' id='end_month' name='end_month' min="{$funded_date}" max="{$current_month}">
			</td>
		</tr>
		<tr>
			<td></td><td colspan="1"><input type='button' value='Email Merchant' id='email_merchant' name='email_merchant'/></td>
		</tr>
		</table>
		<div  id="imageLoading"></div>
		<p id='mail_status'></p>
		</form>
		<script>

		$( document ).ready(function() {
			if($('#scheme_c').text()=="COVID 19")
			{
				$('#agreement').hide();
				$('#agreement_label').hide();
				$('#sanction_letter').hide();
				$('#sanction_letter_label').hide();
				$('#welcome_letter').hide();
				$('#welcome_letter_label').hide()
			}
		});

		$('#email_merchant').click(function() {
			
			$("#mail_status").html('');
			var from_month = $('#start_month').val();
			var to_month = $('#end_month').val();
			var d1 = new Date(from_month);
			var d2 = new Date(to_month);
			
			if(d1>d2){
				alert("From value can not be greater than to value");
				return false;
			}
			$('#imageLoading').css('display','block');
			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: {
					entryPoint: 'send_merchant_email',
					application_id: $('#app_id').text(),
					email_id: $('#applicant_email_id').text(),
					document_type: parseInt($('input:radio[name="document_type"]:checked').val()),
					from_month: $('#start_month').val(),
					to_month: $('#end_month').val(),
					financial_year_c: $('#financial_year_c').val(),
					submit: 1,
					establishment: $('#company_name').text()
				},
				success: function(msg) {
					$('#imageLoading').css('display','none');
					console.log(msg);
					var obj = JSON.parse(msg);
					if(obj['error'] == ''){
						$('#mail_status').html(obj['message']);
						$('#mail_status').attr('style', 'color:black');
					}else{
						$('#mail_status').html(obj['error']);
						$('#mail_status').attr('style', 'color:red');
					}
				},
				error: function(request,status,error) {
					$('#imageLoading').css('display','none');
					console.log(request.responseText);
					console.log(status);
					console.log(error);
					console.log(msg);
					var obj = JSON.parse(msg);
					if(obj['error'] == ''){
							$('#mail_status').html(obj['message']);
							$('#mail_status').attr('style', 'color:black');
					}else{
							$('#mail_status').html(obj['error']);
							$('#mail_status').attr('style', 'color:red');
					}
				}
			});
		});

			
		$('input:radio[name="document_type"]').change(function(){
			$("#mail_status").html('');
			var document_type = parseInt($(this).val());
			var email = "aa";//$('#applicant_email_id').text().trim();
			var funded_date = $('#funded_date').text().trim();
			if(!email){
				$('#mail_status').attr('style', 'color:red');
				$("#mail_status").html('<br/>Email id not found for this app id');
			}else{
				if([1,2].includes(document_type) ){
					if(!funded_date){
						$('#mail_status').attr('style', 'color:red');
						$("#mail_status").html('<br/>Funded date not found for this app id');
					}else{
						$('#tr_from').css('visibility','visible');
						$('#tr_to').css('visibility','visible');
					}
				}else{
					$('#tr_from').css('visibility','collapse');
					$('#tr_to').css('visibility','collapse');
				}
			}
			
			
		});
	</script>

DISP7;
?>