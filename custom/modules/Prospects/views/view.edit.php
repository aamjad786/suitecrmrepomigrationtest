<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2010 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *   
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

require_once('include/MVC/View/views/view.edit.php');

class ProspectsViewEdit extends ViewEdit {


 	function ProspectsViewEdit(){
 		parent::ViewEdit();
 	}

 	/**
 	 * display
 	 * Override the display method to support customization for the buttons that display
 	 * a popup and allow you to copy the account's address into the selected contacts.
 	 * The custom_code_billing and custom_code_shipping Smarty variables are found in
 	 * include/SugarFields/Fields/Address/DetailView.tpl (default).  If it's a English U.S.
 	 * locale then it'll use file include/SugarFields/Fields/Address/en_us.DetailView.tpl.
 	 */
 	function display(){
		global $current_user;
		$role = ACLRole::getUserRoleNames($current_user->id);
		
		
        if(($role[0] == 'Call Center Agent' || $role[0] == 'Call Center Manager') && empty($this->bean->fetched_row)){
			echo '<b style="color:red">You do not have access to this area. Contact your site administrator to obtain access.</b>';
			sugar_die();
		}
		
	 $id = $this->bean->id;
	//~ exit;
 $sssssssssjs = <<<EOD
	<script>
	$(document).ready(function(){
	alert("hi");
	
	         SubdispositionDropdown();
				
				$("#disposition_c").change(function(){
					SubdispositionDropdown();
				});
	
	           
	
	});
	
	function SubdispositionDropdown(){
				
				var city = $('#sub_disposition_c option:selected').text();
				
				var state = $("#disposition_c").find("option:selected").val().replace(/ /g,"_");
				
				var subdispositionOptions = SUGAR.language.languages.app_list_strings['cstm_'+state+'_list'];
				
				$('#sub_disposition_c').html('');
				subdispositionList = '';
				for(var key in subdispositionOptions)
				{
					if(city == subdispositionOptions[key]){
						subdispositionList += '<option value="'+key+'" selected="selected">'+subdispositionOptions[key]+'</option>';
					} else {
						subdispositionList += '<option value="'+key+'">'+subdispositionOptions[key]+'</option>';
					}
				}
$('#sub_disposition_c').append(subdispositionList);
				
			}
	</script>
EOD;

//Dependency Dropdown functionality of state and city. Added by Shakeer	
 $s33333333=<<<dd
		<script>
			var citiesList = '';
			$(document).ready(function(){
				CitiesDropdown();
				AltCitiesDropdown();
				$("#primary_address_state").change(function(){
					CitiesDropdown();
				});
				
				$('#alt_address_state').change(function(){
					AltCitiesDropdown();
				});
				
				$('#alt_checkbox').change(function(){
					AltAddressCopy();
				});
				
				$('#primary_address_city').change(function(){
					AltAddressCopy();
				});
			});
			
			function CitiesDropdown(){
				
				var city = $('#primary_address_city option:selected').text();
				
				var state = $("#primary_address_state").find("option:selected").val().replace(/ /g,"_");
				
				var citiesOptions = SUGAR.language.languages.app_list_strings['cstm_'+state+'_list'];
				
				$('#primary_address_city').html('');
				citiesList = '';
				for(var key in citiesOptions)
				{
					if(city == citiesOptions[key]){
						citiesList += '<option value="'+key+'" selected="selected">'+citiesOptions[key]+'</option>';
					} else {
						citiesList += '<option value="'+key+'">'+citiesOptions[key]+'</option>';
					}
				}
				
				var checkbox = $('#alt_checkbox').is(':checked')?true:false;
				
				if(checkbox){
					$('#primary_address_city,#alt_address_city').append(citiesList);
					AltAddressCopy();
				}else{
					$('#primary_address_city').append(citiesList);
				}
			}
			function AltCitiesDropdown(){
				
				var checkbox = $('#alt_checkbox').is(':checked')?true:false;
				if(checkbox){
					var city = $('#primary_address_city option:selected').text();
				}else{
					var city = $('#alt_address_city option:selected').text();
				}
				
				var state = $("#alt_address_state").find("option:selected").val().replace(/ /g,"_");
				
				var citiesOptions = SUGAR.language.languages.app_list_strings['cstm_'+state+'_list'];
				console.log(citiesOptions);
				$('#alt_address_city').html('');
				citiesList = '';
				for(var key in citiesOptions)
				{
					if(city == citiesOptions[key]){
						citiesList += '<option value="'+key+'" selected="selected">'+citiesOptions[key]+'</option>';
					} else {
						citiesList += '<option value="'+key+'">'+citiesOptions[key]+'</option>';
					}
				}
				$('#alt_address_city').append(citiesList);
			}
			
			function AltAddressCopy(){
				var checkbox = $('#alt_checkbox').is(':checked')?true:false;
				
				if(checkbox){
					var city = $('#primary_address_city option:selected').text();
					var state = $('#primary_address_state option:selected').text();
					var state_key = state.replace(/ /g,"_");
					
					$('#alt_address_state').html('');
					$('#alt_address_city').html('');
					
					citiesOptions = SUGAR.language.languages.app_list_strings['cstm_'+state_key+'_list'];
					stateOptions = SUGAR.language.languages.app_list_strings['cstm_states_list'];
					
					console.log(state);
					console.log(citiesOptions);
					
					for(var key in stateOptions)
					{
						if(state == stateOptions[key]){
							$('#alt_address_state').append('<option value="'+key+'" selected="selected">'+stateOptions[key]+'</option>');
						}
					}
					for(var key in citiesOptions)
					{
						if(city == citiesOptions[key]){
							$('#alt_address_city').append('<option value="'+key+'" selected="selected">'+citiesOptions[key]+'</option>');
						}
					}
					
				}else{
					var city = $('#alt_address_city option:selected').text();
					var state = $('#alt_address_state option:selected').text();
					var state_key = state.replace(/ /g, "_");
					$('#alt_address_state').html('');
					$('#alt_address_city').html('');
					
					citiesOptions = SUGAR.language.languages.app_list_strings['cstm_'+state_key+'_list'];
					stateOptions = SUGAR.language.languages.app_list_strings['cstm_states_list'];
					
					console.log(city);
					console.log(citiesOptions);
					
					for(var key in stateOptions)
					{
						if(state == stateOptions[key]){
							$('#alt_address_state').append('<option value="'+key+'" selected="selected">'+stateOptions[key]+'</option>');
						} else {
							$('#alt_address_state').append('<option value="'+key+'">'+stateOptions[key]+'</option>');
						}
					}
					for(var key in citiesOptions)
					{
						if(city == citiesOptions[key]){
							$('#alt_address_city').append('<option value="'+key+'" selected="selected">'+citiesOptions[key]+'</option>');
						} else {
							$('#alt_address_city').append('<option value="'+key+'">'+citiesOptions[key]+'</option>');
						}
					}
				}
			}
			
		</script>
dd;
 

 
	echo $js = <<<onload
		<script>
			$(document).ready(function(){
				
				//Attempts done is the readonly field
				$('#attempts_done_c').attr('readonly',true);
				
				//Hide Disposition and sub disposition when the lead is new
				if('$id' == '') {
					$("#check_disposition_c").parent().parent().hide();
					$("#disposition_c").parent().parent().hide();
				} else {
					$("#check_disposition_c").parent().parent().show();
					$("#disposition_c").parent().parent().show();
				}
				
				//change dispostion and sub disposition options once the lead is Interested and Lead generated
				change_dropdown_options();
				
				// show Opportunities related panel based on disposition and sub dispostion values
				loan_details(0);
				$('#sub_disposition_c').change(function() {
					loan_details(1);
				});
			});
			
			function change_dropdown_options() {
				var disposition = $("#disposition_c").find('option:selected').val();
				var sub_disposition = $("#sub_disposition_c").find('option:selected').val();
				
				if(disposition == "Interested" && sub_disposition == "Lead generated") {
					var dispositionOptions = SUGAR.language.languages.app_list_strings['cstm_disposition_list'];
					var sub_dispositionOptions = SUGAR.language.languages.app_list_strings['cstm_subdisposition_list'];
					
					$("#disposition_c").html("");
					$("#sub_disposition_c").html("");
					dispositionList = "";
					subdispositionList = "";
					for(var key in dispositionOptions)
					{
						if(disposition == dispositionOptions[key]){
							dispositionList += '<option value="'+key+'" selected="selected">'+dispositionOptions[key]+'</option>';
						}
					}
					$("#disposition_c").append(dispositionList);
					
					for(var key in sub_dispositionOptions)
					{
						if(sub_disposition == sub_dispositionOptions[key]){
							subdispositionList += '<option value="'+key+'" selected="selected">'+sub_dispositionOptions[key]+'</option>';
						}
					}
					$("#sub_disposition_c").append(subdispositionList);
				}
				
				
			}
			
			
			function loan_details(flag) {
				var sub_disposition = $('#sub_disposition_c').val();
				if(sub_disposition != 'Lead generated' || flag == 0){
					$('#detailpanel_2').hide();
				} else {
					$('#detailpanel_2').show();
					
					var mobile = $('#phone_mobile').val();
					var amount = $('#loan_amount_c').val();
					var pincode = $('#primary_address_postalcode').val();
					
					$('#loan_amount_required_c').val(amount);
					$('#pickup_contact_number_c').val(mobile);
					$('#pickup_appointment_pincode_c').val(pincode);
					
					var layout = '';
					layout += '<tbody>';
					layout += '<tr>';
					layout += '<td width="12.5%" valign="top" scope="col" id="loan_amount_required_c_label">Loan amount required:</td>';
					layout += '<td width="37.5%" valign="top"><input type="text" id="loan_amount_required_c" name="loan_amount_required_c" value=""  maxlength="5" size="30"/></td>';
					layout += '<td width="12.5%" valign="top" scope="col" id="pickup_appointment_date_time_c_label">Pickup/ appointment date/ time:</td>';
					layout += '<td width="37.5%" valign="top"><input type="text" id="pickup_appointment_date_time_c" name="pickup_appointment_date_time_c" value=""  maxlength="5" size="30"/></td>';
					layout += '</tr>';
					layout += '<tr>';
					layout += '<td width="12.5%" valign="top" scope="col" id="pickup_appointment_address_c_label">Pickup/ appointment address:</td>';
					layout += '<td width="37.5%" valign="top"><input type="text" id="pickup_appointment_address_c" name="pickup_appointment_address_c" value=""  maxlength="5" size="30"/></td>';
					layout += '<td width="12.5%" valign="top" scope="col" id="pickup_appointment_pincode_c_label">Pickup/ appointment pin code:</td>';
					layout += '<td width="37.5%" valign="top"><input type="text" id="pickup_appointment_pincode_c" name="pickup_appointment_pincode_c" value=""  maxlength="5" size="30"/></td>';
					layout += '</tr>';
					layout += '<tr>';
					layout += '<td width="12.5%" valign="top" scope="col" id="pickup_appointment_allocated_to_c_label">Pickup/ appointment allocated to:</td>';
					layout += '<td width="37.5%" valign="top"><input type="text" id="pickup_appointment_allocated_to_c" name="pickup_appointment_allocated_to_c" value=""  maxlength="5" size="30"/></td>';
					layout += '<td width="12.5%" valign="top" scope="col" id="pickup_appointment_feedback_c_label">Pickup/ appointment feedback:</td>';
					layout += '<td width="37.5%" valign="top"><input type="text" id="pickup_appointment_feedback_c" name="pickup_appointment_feedback_c" value=""  maxlength="5" size="30"/></td>';
					layout += '</tr>';
					layout += '<tr>';
					layout += '<td width="12.5%" valign="top" scope="col" id="remarks_c_label">Remarks:</td>';
					layout += '<td width="37.5%" valign="top"><input type="text" id="remarks_c" name="remarks_c" value=""  maxlength="5" size="30"/></td>';
					layout += '<td width="12.5%" valign="top" scope="col" id=""></td>';
					layout += '<td width="37.5%" valign="top"></td>';
					layout += '</tr>';
					layout += '</tbody></table>';
					
					//~ $('#LBL_EDITVIEW_PANEL3').html('').append(layout);
					
				}
			}
		</script>
onload;

//End

		echo $js222 = <<<disposition
		<script>
			$(document).ready(function(){
				//disable disposition_c options once it is uncheck
				if($("#check_disposition_c").is(':checked')){
					$('#disposition_c').attr('disabled',false);
					$('#sub_disposition_c').attr('disabled',false);
				}else{
					$('#disposition_c').attr('disabled',true);
					$('#sub_disposition_c').attr('disabled',true);
				}
				
				
				$("#check_disposition_c").change(function(){
				
					var status = $("#check_disposition_c").val();
					
					if($("#check_disposition_c").is(':checked')){
						$('#disposition_c').attr('disabled',false);
						$('#sub_disposition_c').attr('disabled',false);
					}else{
						$('#disposition_c').attr('disabled',true);
						$('#sub_disposition_c').attr('disabled',true);
					}
				
				});
				
			});
		</script>
disposition;
		
				
		//Disposition dependency By Vivek G
	echo $js22 = <<<EOD
	<script>
	$(document).ready(function(){
			
			var subdispositionList = '';
				SubdispositionDropdown();
				$("#disposition_c").change(function(){
					SubdispositionDropdown();
					loan_details();
				});
				
			});
			
		
			
				function SubdispositionDropdown(){
				
				var city = $('#sub_disposition_c option:selected').text();
				
				var state = $("#disposition_c").find("option:selected").val().replace(/ /g,"_");
				
				var subdispositionOptions = SUGAR.language.languages.app_list_strings['cstm_'+state+'_list'];
				
				$('#sub_disposition_c').html('');
				subdispositionList = '';
				for(var key in subdispositionOptions)
				{
					if(city == subdispositionOptions[key]){
						subdispositionList += '<option value="'+key+'" selected="selected">'+subdispositionOptions[key]+'</option>';
					} else {
						subdispositionList += '<option value="'+key+'">'+subdispositionOptions[key]+'</option>';
					}
				}
$('#sub_disposition_c').append(subdispositionList);
				
			}
				</script>
EOD;


echo $js33 = <<<EOD
	<script>
	$(document).ready(function(){
			
			var disposition = $("#disposition_c").find("option:selected").val();
								$("#pickup_appointmnet_c").parent().hide();
								$("#call_back_c").parent().hide();
								$("#pickup_appointmnet_c_label").parent().hide();
								$("#call_back_c_label").parent().hide();
								
								
				if((disposition == 'Call_back')||(disposition == 'Not contactable')||(disposition == 'Follow_up')){
				$("#call_back_c_label").parent().show();
				$("#pickup_appointmnet_c_label").parent().hide();
				$("#call_back_c").parent().show();
				$("#pickup_appointmnet_c").parent().hide();
				
				addToValidate('EditView', 'call_back_c', 'Datetime', 'true', 'Call-Back Date / Time');
				document.getElementById('call_back_c_label').innerHTML= 
				document.getElementById('call_back_c_label').innerHTML.replace("*","");
				if(!(document.getElementById('call_back_c_label').innerHTML.indexOf("*") > 0))
				{
				   document.getElementById('call_back_c_label').innerHTML += '<font color="red">*<font>';
				}
				
				}
				if((disposition != 'Call_back')&&(disposition != 'Not contactable')&&(disposition != 'Follow_up')){
				$("#call_back_c_label").parent().hide();
				$("#call_back_c").parent().hide();
				removeFromValidate('EditView', 'call_back_c');
					document.getElementById('call_back_c_label').innerHTML= document.getElementById('call_back_c_label').innerHTML.replace("*","");
				
				}
				
				if((disposition == 'Pickup generation_Appointment')||(disposition == 'Pickup fulfillment')){
				$("#call_back_c_label").parent().hide();
				$("#pickup_appointmnet_c_label").parent().show();
				$("#call_back_c").parent().hide();
				$("#pickup_appointmnet_c").parent().show();
				removeFromValidate('EditView', 'call_back_c');
					document.getElementById('call_back_c_label').innerHTML= document.getElementById('call_back_c_label').innerHTML.replace("*","");
						
				}
				
				if((disposition != 'Pickup generation_Appointment')&&(disposition != 'Pickup fulfillment')){
				$("#pickup_appointmnet_c").parent().hide();
				$("#pickup_appointmnet_c_label").parent().hide();
				}
				
				
						$("#disposition_c").change(function(){
				
				var disposition = $("#disposition_c").find("option:selected").val();
				
				if((disposition == 'Call_back')||(disposition == 'Not contactable')||(disposition == 'Follow_up')){ 
					
				$("#call_back_c_label").parent().show();
				$("#pickup_appointmnet_c_label").parent().hide();
				$("#call_back_c").parent().show();
				$("#pickup_appointmnet_c").parent().hide();
				addToValidate('EditView', 'call_back_c', 'Datetime', 'true', 'Call-Back Date / Time');
				document.getElementById('call_back_c_label').innerHTML= 
				document.getElementById('call_back_c_label').innerHTML.replace("*","");
				if(!(document.getElementById('call_back_c_label').innerHTML.indexOf("*") > 0))
				{
				   document.getElementById('call_back_c_label').innerHTML += '<font color="red">*<font>';
				}
				}
				if((disposition != 'Call_back')&&(disposition != 'Not contactable')&&(disposition != 'Follow_up')){
				$("#call_back_c_label").parent().hide();
				$("#call_back_c").parent().hide();
				removeFromValidate('EditView', 'call_back_c');
					document.getElementById('call_back_c_label').innerHTML= document.getElementById('call_back_c_label').innerHTML.replace("*","");
				}
				
				if((disposition == 'Pickup generation_Appointment')||(disposition == 'Pickup fulfillment')){
				$("#call_back_c_label").parent().hide();
				$("#pickup_appointmnet_c_label").parent().show();
				$("#call_back_c").parent().hide();
				$("#pickup_appointmnet_c").parent().show();
				removeFromValidate('EditView', 'call_back_c');
					document.getElementById('call_back_c_label').innerHTML= document.getElementById('call_back_c_label').innerHTML.replace("*","");
				}
				
				if((disposition != 'Pickup generation_Appointment')&&(disposition != 'Pickup fulfillment')){
				$("#pickup_appointmnet_c").parent().hide();
				$("#pickup_appointmnet_c_label").parent().hide();
				}
				
				});
				
			});
</script>
EOD;

	$id = $this->bean->id;
	if($id!='')
	{	
 $js = <<<EOD
	<script>
	$(document).ready(function(){
	 $("#batch_id_c").attr('disabled',true);
	 $("#agreement_no_c").attr('disabled',true);
	 $("#phone_mobile").attr('disabled',true);
	 $("#phone_work").attr('disabled',true);
	 $("#city_c").attr('disabled',true);
	 $("#operator_c").attr('disabled',true);
	 $("#loan_amount_c").attr('disabled',true);
	 $("#missed_call_number_c").attr('disabled',true);
	 $("#name_of_the_shop_c").attr('disabled',true);
	 $("#circle_c").attr('disabled',true);
	 $("#avg_sales_per_month_c").attr('disabled',true);
	 $("#last_name").attr('disabled',true);
	 $("#salutation").attr('disabled',true);
	 $('#first_name').attr('disabled',true);
	 $('#Leads0emailAddress0').attr('disabled',true);
	 $('#no_of_years_in_business_c').attr('disabled',true);
	 $('#lead_source').attr('disabled',true);
	 $('#btn_campaign_name').attr('disabled',true);
	 $('#btn_clr_campaign_name').attr('disabled',true);
	 $('#campaign_name').attr('disabled',true);
	 $('#assigned_user_name').attr('disabled',true);
	 $('#btn_assigned_user_name').attr('disabled',true);
	 $('#btn_clr_assigned_user_name').attr('disabled',true);
	});
	</script>
EOD;
	}

//Dependency Dropdown functionality of state and city Based on Pincode. Added by Shakeer	
 echo $dd=<<<dd
		<script>
			$(document).ready(function(){
			
				$("#primary_address_postalcode").after('<label id="pin_label">Please Enter PIN</label>');
				$("#alt_address_postalcode").after('<label id="alt_pin_label">Please Enter PIN</label>');
			
				fillPrimaryAddressDropdown();
				fillAlternateAddressDropdown();
				
				$("#primary_address_postalcode").change(function(){
				
					var pincode = $("#primary_address_postalcode").val();
					var pincodeList = SUGAR.language.languages.app_list_strings['city_pincodes_list'];
					
					if(pincodeList[pincode] == undefined) {
						$("#pin_label").attr("style", "color:red").text("Invalid PIN");
						$("#primary_address_postalcode").val('');
					} else {
						$("#pin_label").attr("style", "color:black").text("Valid PIN");
					}
					fillPrimaryAddressDropdown();
				});
				
				$("#alt_address_postalcode").change(function(){
				
					var pincode = $("#alt_address_postalcode").val();
					var pincodeList = SUGAR.language.languages.app_list_strings['city_pincodes_list'];
					
					if(pincodeList[pincode] == undefined) {
						$("#alt_pin_label").attr("style", "color:red").text("Invalid PIN");
						$("#alt_address_postalcode").val('');
					} else {
						$("#alt_pin_label").attr("style", "color:black").text("Valid PIN");
					}
					fillAlternateAddressDropdown();
				});
				
				$('#alt_checkbox').change(function(){
					copyPrimaryAddressDropdown();
				});
			});
			
			function copyPrimaryAddressDropdown() {
				var checkbox = $('#alt_checkbox').is(':checked')?true:false;
				
				if(checkbox) {
					fillPrimaryAddressDropdown();	
				} else {
					fillAlternateAddressDropdown();
				}
			}
			
			function fillPrimaryAddressDropdown() {
				var pincode = $("#primary_address_postalcode").val();
				var addressOptions = SUGAR.language.languages.app_list_strings['city_pincodes_list'];
				$('#primary_address_city').html('');
					
				
				if(pincode != '') {
					var city = '<option value="'+pincode+'" selected="selected">'+addressOptions[pincode]+'</option>';
				}
				
				var checkbox = $('#alt_checkbox').is(':checked')?true:false;
				
				if(checkbox) {
					$('#alt_address_city').html('');
					$('#primary_address_city,#alt_address_city').append(city);	
				} else {
					$('#primary_address_city').append(city);
				}
				
			}
			
			function fillAlternateAddressDropdown() {
				var pincode = $("#alt_address_postalcode").val();
				var addressOptions = SUGAR.language.languages.app_list_strings['city_pincodes_list'];
				$('#alt_address_city').html('');
				
				if(pincode != '') {
					var city = '<option value="'+pincode+'" selected="selected">'+addressOptions[pincode]+'</option>';
				}
				$('#alt_address_city').append(city);	
			}
			
		 //mobile number validation - Renuka Kamdi
			$(document).ready(function(){
				$('#phone_mobile').change(function () {  
                    var phoneno = /^\d{10}$/;  
                    var phone_value = $('#phone_mobile').val();
                      if(!(phone_value.match(phoneno)) && ($('#mobile_alert').text() == ''))  
                      {  
                        $('#phone_mobile').val('').parent().append('<label id="mobile_alert" style="color:red">Invalid Number</label>');
                      }
                      if((phone_value.match(phoneno) && ($('#mobile_alert').text() != ''))) 
                      {
							$('#mobile_alert').text('') ;
				      }                        
                  });  
				
				//first and last name validation - Renuka Kamdi
				  $('#first_name').change(function () {  
                    var name = /^[a-zA-Z\s]*$/;
                    var first_name = $('#first_name').val();
                      if(!(first_name.match(name)) && ($('#name_alert').text() == ''))  
                      {  
                        $('#first_name').val('').parent().append('<label id="name_alert" style="color:red">Invalid First Name</label>');
                      }
                      if((first_name.match(name) && ($('#name_alert').text() != ''))) 
                      {
						 	$('#name_alert').text('') ;
				      }                        
                  });
                  
				$('#last_name').change(function () {  
                    var name1 = /^[a-zA-Z\s]*$/;
                    var last_name = $('#last_name').val();
                      if(!(last_name.match(name1)) && ($('#name_alert1').text() == ''))  
                      {  
                        $('#last_name').val('').parent().append('<label id="name_alert1" style="color:red">Invalid Last Name</label>');
                      }
                      if((last_name.match(name1) && ($('#name_alert1').text() != ''))) 
                      {
							$('#name_alert1').text('') ;
				      }                        
                  });  
                  
				$('#merchant_name_c').change(function () {  
                    var name2 = /^[a-zA-Z\s]*$/;
                    var merchant_name_c = $('#merchant_name_c').val();
                      if(!(merchant_name_c.match(name2)) && ($('#name_alert2').text() == ''))  
                      {  
                        $('#merchant_name_c').val('').parent().append('<label id="name_alert2" style="color:red">Invalid Merchant Name</label>');
                      }
                      if((merchant_name_c.match(name2) && ($('#name_alert2').text() != ''))) 
                      {
							$('#name_alert2').text('') ;
				      }                        
                  }); 
                  var dq_score = $('#dq_score_c').val();
				if(dq_score != ''){
					$('#dq_score_c').attr('readonly','readonly');
				}
               });  
		</script>
dd;
 
 //End of Dependency dropdown	
 
//START For loan amount
	global $sugar_config;
		$sep=$sugar_config['default_number_grouping_seperator'];
echo $Currency_comma_sep_script=<<<EOQ
	<script>
	
	$(document).ready(function(){
		
		$('#loan_amount_c').after('<br/>');
		test_skill('loan_amount_c');
	
		$("#loan_amount_c").keyup(function(){
			test_skill('loan_amount_c');
			document.getElementById('loan_amount_c').value =test_remove_comma('loan_amount_c');	
			});
		
		});
	
		
	
	function test_remove_comma(amount_ID) {
		var sep='$sep';
		var amount=document.getElementById(amount_ID).value;
		var regex = new RegExp(',', 'g');
		//replace via regex
		amount = amount.replace(regex, '');	
				var x=amount;
    			x=x.toString();
				var lastThree = x.substring(x.length-3);
                var otherNumbers = x.substring(0,x.length-3);
                   if(otherNumbers != '')
					lastThree = sep + lastThree;
						var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, sep) + lastThree;
				return res;
	}
	
	
	
	
	function test_skill(amount_ID) {
		var amount=document.getElementById(amount_ID).value;
		var regex = new RegExp(',', 'g');
			//replace via regex
		amount = amount.replace(regex, '');	
		var junkVal=amount;
		junkVal=Math.floor(junkVal);
		var obStr=new String(junkVal);
		numReversed=obStr.split("");
		actnumber=numReversed.reverse();

		if(Number(junkVal) >=0){
			//do nothing
			$('.remove_loan_amount_c').html('');
		}
		else{
			//~ alert('wrong Number cannot be converted');
		   	$('.remove_loan_amount_c').html('');
			$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:red\">Wrong Number cannot be converted</span>');
			return false;
		}
    if(Number(junkVal)==0){
        //~Rupees Zero Only
        
        return false;
		}
		if(actnumber.length>9){
			//~ alert('Oops!!!! the Number is too big to covertes');
			$('.remove_loan_amount_c').html('');
			$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:red\">Oops!!!! the amount is too big to convert</span>');
			return false;
		}

		var iWords=["Zero", " One", " Two", " Three", " Four", " Five", " Six", " Seven", " Eight", " Nine"];
		var ePlace=['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
		var tensPlace=['dummy', ' Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety' ];

		var iWordsLength=numReversed.length;
		var totalWords="";
		var inWords=new Array();
		var finalWord="";
		j=0;
		for(i=0; i<iWordsLength; i++){
			switch(i)
			{
			case 0:
				if(actnumber[i]==0 || actnumber[i+1]==1 ) {
					inWords[j]='';
				}
				else {
					inWords[j]=iWords[actnumber[i]];
				}
				inWords[j]=inWords[j]+' Only';
				break;
			case 1:
				tens_complication();
				break;
			case 2:
				if(actnumber[i]==0) {
					inWords[j]='';
				}
				else if(actnumber[i-1]!=0 && actnumber[i-2]!=0) {
					inWords[j]=iWords[actnumber[i]]+' Hundred and';
				}
				else {
					inWords[j]=iWords[actnumber[i]]+' Hundred';
				}
				break;
			case 3:
				if(actnumber[i]==0 || actnumber[i+1]==1) {
					inWords[j]='';
				}
				else {
					inWords[j]=iWords[actnumber[i]];
				}
				if(actnumber[i+1] != 0 || actnumber[i] > 0){
					inWords[j]=inWords[j]+" Thousand";
				}
				break;
			case 4:
				tens_complication();
				break;
			case 5:
				if(actnumber[i]==0 || actnumber[i+1]==1) {
					inWords[j]='';
				}
				else {
					inWords[j]=iWords[actnumber[i]];
				}
				if(actnumber[i+1] != 0 || actnumber[i] > 0){
					inWords[j]=inWords[j]+" Lakh";
				}
				break;
			case 6:
				tens_complication();
				break;
			case 7:
				if(actnumber[i]==0 || actnumber[i+1]==1 ){
					inWords[j]='';
				}
				else {
					inWords[j]=iWords[actnumber[i]];
				}
				inWords[j]=inWords[j]+" Crore";
				break;
			case 8:
				tens_complication();
				break;
			default:
				break;
			}
			j++;
		}

		function tens_complication() {
			if(actnumber[i]==0) {
				inWords[j]='';
			}
			else if(actnumber[i]==1) {
				inWords[j]=ePlace[actnumber[i-1]];
			}
			else {
				inWords[j]=tensPlace[actnumber[i]];
			}
		}
		inWords.reverse();
		for(i=0; i<inWords.length; i++) {
			finalWord+=inWords[i];
		}
		//~ document.getElementById(amount_ID).innerHTML=obStr+'  '+finalWord;
		
		$('.remove_loan_amount_c').html('');
		$('#loan_amount_c').parent().append('<span id="loan_amount_c_word" class = \"remove_loan_amount_c\" style=\"color:green\"></br></span>');
		var span = document.getElementById('loan_amount_c_word');
		while( span.firstChild ) {
			span.removeChild( span.firstChild );
		}
		span.appendChild( document.createTextNode(finalWord) );
		
		
	}

	</script>
EOQ;


//END For loan amount 
 	 //TeleScript Popup   START
global $db;
	/*$Attempts = $this->bean->attempts_done_c;
    $TeleScript_result = $db->query("SELECT DISTINCT description AS TeleScript FROM scrm_telescript WHERE name='$Attempts' and deleted=0 ");
	$TeleScript_row = $db->fetchByAssoc($TeleScript_result);
	$TeleScript = $TeleScript_row['TeleScript'];
	$this->bean->telescript_c = $TeleScript;			
//TeleScript Popup   END

global $current_user;
		$acl_role_obj = new ACLRole();
		$user_roles = $acl_role_obj->getUserRoles($current_user->id);
		$roles = $user_roles[0];
 	if($roles=='Call Center Agent'){
		echo $TELEscript=<<<EOQ
	<script>
		$(document).ready(function(){
			$('#telescript_c').show();	
			$('#telescript_c_label').show();	
		})
	</script>
EOQ;
		}else{
				echo $TELEscript=<<<EOQ
			<script>
				$(document).ready(function(){
					$('#telescript_c').hide();	
					$('#telescript_c_label').hide();	
					
				})
			</script>
EOQ;
		}*/


	 parent::display();	
 	}	
}

?>



