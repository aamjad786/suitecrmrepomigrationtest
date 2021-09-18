<?php
// Sorting opportunities in reverse chronological order by default (on any date created)
require_once('include/MVC/View/views/view.edit.php');


class reg_regularizationViewEdit extends ViewEdit {


	 function CustomRegularizationViewEdit(){
          parent::RegularizationViewEdit();
        }
	function display() {
		$id = $this->bean->id;
	
		
		
		global $sugar_config;
		
echo $Currency_comma_sep_script=<<<EOQ
	<script>
	
		$(document).on('change','#welcome_call_status',function(){
			var value = $(this).val();
			var remark = $('#remark').val()
			var remerk_length = remark.length;
			var insurance = $('#insurance').val();
			var call_updation = $('#call_updation').val();
			var regularization_category= $('#regularization_category').val();
		
			var value_welcome_status = $('#welcome_call_status').val();

			if(value == 'CLOSED'){
				$("#remark_label").append("<span class='required'>*</span>");
				$("#insurance_label").append("<span class='required'>*</span>");
				$("#call_updation_label").append("<span class='required'>*</span>");
				$("#regularization_category_label").append("<span class='required'>*</span>");
			}
			
			$('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");
			
			if(value == 'CLOSED' && (remerk_length <=1 || insurance =='' || call_updation ==''|| regularization_category=='')) {
				alert('Please enter all required details ie. remarks, insurance, Regularization Category and Attempt No.');
				$('#remark').focus();
				$('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				$('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");

			}
		});


		$(document).on('keyup','#remark',function(){
			var value = $(this).val()
			var remerk_length = $.trim(value).length;
			var insurance = $('#insurance').val();
			var call_updation = $('#call_updation').val();
			var regularization_category= $('#regularization_category').val();
			console.log(remerk_length);
			var value_welcome_status = $('#welcome_call_status').val();

			$('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");

			if(value_welcome_status == 'CLOSED' && remerk_length <=1 ) {
	
				$('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				$('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");

			} 
			if(value_welcome_status == 'CLOSED' && insurance ==''){

				$('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				$('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");

			}
			if(value_welcome_status == 'CLOSED' && regularization_category ==''){

				$('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				$('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");

			} 
			
		});

		$(document).on('change','#insurance,#call_updation,#regularization_category',function(){
			
			var insurance = $('#insurance').val();
			var call_updation = $('#call_updation').val();
			var value_welcome_status = $('#welcome_call_status').val();
			var regularization_category= $('#regularization_category').val();
			
			$('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");

			if(value_welcome_status == 'CLOSED' && insurance  =='' ) {
				$('#insurance').focus();
				$('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				$('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");

			} 
			if(value_welcome_status == 'CLOSED' && call_updation ==''){
				$('#call_updation').focus();
				$('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				$('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");

			} 
			if(value_welcome_status == 'CLOSED' &&  regularization_category==''){
				$('#regularization_category').focus();
				$('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				$('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");

			} 
		});

		$(document).ready(function(){

			var insurance = $('#insurance').val();
			var remark = $('#remark').val()
			var remerk_length = remark.length;
			var call_updation = $('#call_updation').val();
			var value_welcome_status = $('#welcome_call_status').val();
			var regularization_category= $('#regularization_category').val();

			$('#SAVE_HEADER,#SAVE_FOOTER').prop("disabled", false).css("opacity", "1");

			if(value_welcome_status  == 'CLOSED'){
				$("#remark_label").append("<span class='required'>*</span>");
				$("#insurance_label").append("<span class='required'>*</span>");
				$("#call_updation_label").append("<span class='required'>*</span>");
				$("#regularization_category_label").append("<span class='required'>*</span>");
			}

			if(value_welcome_status == 'CLOSED' && (insurance ==''  || call_updation ==''|| regularization_category=='' || remerk_length <=1)){

				$('#SAVE_HEADER,#SAVE_FOOTER').attr('disabled',true);
				$('#SAVE_HEADER,#SAVE_FOOTER').attr("disabled", "disabled").css("opacity", "0.5");

			}
		});
		


	</script>
EOQ;
 		parent::display();
 	}
}
