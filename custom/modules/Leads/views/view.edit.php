<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

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
require_once('include/SugarTinyMCE.php');
class LeadsViewEdit extends ViewEdit
{


	public function __construct()
	{
		parent::__construct();
		$this->useForSubpanel = true;
		$this->useModuleQuickCreateTemplate = true;
	}

	/**
	 * display
	 * Override the display method to support customization for the buttons that display
	 * a popup and allow you to copy the account's address into the selected contacts.
	 * The custom_code_billing and custom_code_shipping Smarty variables are found in
	 * include/SugarFields/Fields/Address/DetailView.tpl (default).  If it's a English U.S.
	 * locale then it'll use file include/SugarFields/Fields/Address/en_us.DetailView.tpl.
	 */
	function display()
	{

		global $current_user;
		$current_user_id = $current_user->id;

		$opp = BeanFactory::getBean('Opportunities', $this->bean->opportunity_id);
		$app = $opp->application_id_c;

		$lead_id = $this->bean->id;
		$city = $opp->pickup_appointment_city_c;

		$date = date('m/d/Y', strtotime($opp->pickup_appointment_date_c));
		$hour = date('H', strtotime($opp->pickup_appointment_date_c));
		$min = date('i', strtotime($opp->pickup_appointment_date_c));
		$hear_about_us_c = $this->bean->hear_about_us_c;
?>
		<script>
			var app = "<?php echo !empty($app) ? $app : null ?>";
		</script>
<?php
		$js = <<<onload
		
		<script>

		function disableSave() {
			console.log("OK");
			$(document).ready(function() {
				$('input[type="submit"]').prop("disabled", true).css("opacity", "0.5");
				$('#save_and_continue').attr("disabled", "disabled").css("opacity", "0.5");
			})
		}
		
		if (app == null) {
			disableSave();
		}
		
		
		$(document).on('change', '#hear_about_us_c', function() {
			var selection = $(this).children("option:selected").val();
			if (selection == 'Others') {
				$('#mention_the_detail_c').val('');
				$('#mention_the_detail_label,#mention_the_detail_c').show();
				$('#mention_the_detail_label,#mention_the_detail_c').parent().parent().show();
		
			} else {
				$('#mention_the_detail_c').val('N/A');
				$('#mention_the_detail_c').parent().parent().hide();
				
		
			}
		});
		
		$(document).ready(function() {
		
			$('#mention_the_detail_c').val('N/A');
			$('#mention_the_detail_c').parent().parent().hide();

			function dispositionToggle() {
				if ($("#check_disposition_c").is(':checked')) {
					$('#disposition_c').attr('disabled', false);
					$('#sub_disposition_c').attr('disabled', false);
				} else {
					$('#disposition_c').attr('disabled', true);
					$('#sub_disposition_c').attr('disabled', true);
				}
			}
		
			dispositionToggle();
		
			$("#check_disposition_c").change(function() {
				dispositionToggle();
			});
	
		
			if (document.getElementById('pickup_appointment_date_time_c_date')) {
				document.getElementById('pickup_appointment_date_time_c_date').setAttribute('onchange', "pickUpAppointmentDateCheck()");
			}
		
		
			//Lead Source disable
			if ('$lead_id' != '') {
				$('#lead_source').attr('disabled', 'disabled');
			}
		
		
			//Attempts done is the readonly field
			$('#attempts_done_c').attr('readonly', true);
			$('#indicative_deal_amount_c').attr('readonly', true);
		
			//Hide Disposition and sub disposition when the lead is new
			if ('$lead_id' == '') {
				$("#check_disposition_c").parent().parent().hide();
				$("#disposition_c").parent().parent().hide();
				$("#sub_disposition_c").parent().parent().hide();
			} else {
				$("#check_disposition_c").parent().parent().show();
				$("#disposition_c").parent().parent().show();
				$("#sub_disposition_c").parent().parent().show();
			}
		
		
			// show Opportunities related panel based on disposition and sub dispostion values
			loan_details(0);
			$('#sub_disposition_c').change(function() {
				loan_details(1);
			});
		
			$('#loan_amount_c, #loan_amount_required_c').change(function() {
				var amount = $(this).val();
				$('#loan_amount_c, #loan_amount_required_c').val(amount);
			});
			$('#pickup_appointment_address_c, #primary_address_street').change(function() {
				var address = $(this).val();
				$('#pickup_appointment_address_c, #primary_address_street').val(address);
			});
			$('#pickup_contact_number_c, #phone_mobile').change(function() {
				var mobile = $(this).val();
				$('#pickup_contact_number_c, #phone_mobile').val(mobile);
			});
		
		});
		
		function loan_details(flag) {
			var sub_disposition = $('#sub_disposition_c').val();
			var disposition = $('#disposition_c').find('option:selected').val();
		
		
			if (flag == 0) {
				
				$('#loan_amount_required_c').val('');
				$('#pickup_contact_number_c').val('');
				$('#pickup_appointment_pincode_c').val('');
				$('#pickup_appointment_address_c').val('');
				$('#pickup_appointment_city_c').val('');
				$('#pickup_appointment_user_c').val('');
		
				$('#call_back_date_time_c_date').val('');
				$('#call_back_date_time_c_hours').val('');
				$('#call_back_date_time_c_minutes').val('');
				$('#user_id_c').val('');
				$('#detailpanel_2').hide();
				$('#detailpanel_0').prev().hide();
		
			} else if ((disposition == 'interested') || (disposition == 'pick_up')) {
				$('#detailpanel_2').show();
				$('#detailpanel_0').prev().show();
				$("#call_back_date_time_c").parent().parent().hide();
				$('#call_back_date_time_c_date').val('');
				$('#call_back_date_time_c_hours').val('');
				$('#call_back_date_time_c_minutes').val('');
		
		
		
				removeFromValidate('EditView', 'call_back_date_time_c');
				
		
				addToValidate('EditView', 'pickup_appointment_date_time_c', 'Datetime', 'true', 'Pickup / Appointment Date / Time');
				addToValidate('EditView', 'pickup_appointment_city_c', 'enum', true, 'Pickup City');
				
		
				
		
				var mobile = $('#phone_mobile').val();
				var amount = $('#loan_amount_c').val();
				var pincode = $('#primary_address_postalcode').val();
				var street = $('#primary_address_street').val();
				var city = $('#primary_address_city').val();
		
		
				$('#loan_amount_required_c').val(amount);
				$('#pickup_contact_number_c').val(mobile);
		
				$('#pickup_appointment_city_c').val('$city');
				$('#pickup_appointment_date_time_c_date').val('$date');
				$('#pickup_appointment_date_time_c_hours').val('$hour');
				$('#pickup_appointment_date_time_c_minutes').val('$min');
				$('#pickup_appointment_date_time_c').val('$opp->pickup_appointment_date_c');
		
			} else {
		
				removeFromValidate('EditView', 'pickup_appointment_date_time_c');
				removeFromValidate('EditView', 'pickup_appointment_city_c');
		
				$('#loan_amount_required_c').val('');
				$('#pickup_contact_number_c').val('');
				$('#pickup_appointment_pincode_c').val('');
				$('#pickup_appointment_address_c').val('');
				$('#pickup_appointment_city_c').val('');
				$('#pickup_appointment_user_c').val('');
				$('#pickup_appointment_city_c').val('');
				$('#detailpanel_0').hide();
				$('#detailpanel_0').prev().hide();
		
			}
		}
		
		function pickUpAppointmentDateCheck() {
			var cam_user_id = $('#user_id_c').val();
			if (cam_user_id != '')
				setTimeout(checkAppointmentDate, 10000);
		}
		
		function checkAppointmentDate() {
			console.log('check appointment date');
			var pickup_date = $('#pickup_appointment_date_time_c').val();
			var cam_user_id = $('#user_id_c').val();
			if (pickup_date != '' && cam_user_id != '') {
		
				$.ajax({
					url: 'index.php?module=Leads&entryPoint=checkAppointmentDate',
					async: true,
					type: 'POST',
					data: { pickup_date: pickup_date, cam_user_id: cam_user_id, current_user_id: '$current_user_id' },
					success: function(response) {
		
						if (response > 0) {
							YAHOO.SUGAR.MessageBox.show({ msg: 'Duplicate Pickup/ appointment Date / Time: ' + pickup_date, type: 'plain', close: true, title: 'Alert:', width: '190', height: '5' });
							$('#pickup_appointment_date_time_c').val('');
							$('#pickup_appointment_date_time_c_date').val('');
							$('#pickup_appointment_date_time_c_hours').val('');
							$('#pickup_appointment_date_time_c_minutes').val('');
							$('#pickup_appointment_date_time_c_meridiem').val('');
						}
					}
				});
		
			}
		}
		
		</script>
onload;


		echo $js;

		parent::display();
	}
}
