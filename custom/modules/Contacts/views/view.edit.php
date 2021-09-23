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

class ContactsViewEdit extends ViewEdit {


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
 	function display(){
	$id = $this->bean->id;
	
	
		global $current_user;
		$releObject=new ACLRole();
		$role = $releObject->getUserRoleNames($current_user->id);
		
		
        if(($role[0] == 'Customer Acquisition Manager' || $role[0] == 'Cluster Manager' || $role[0] == 'Regional Manager' || $role[0] == 'Functional Head') && empty($this->bean->fetched_row)){
			echo '<b style="color:red">You do not have access to this area. Contact your site administrator to obtain access.</b>';
			sugar_die();
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
		</script>
dd;
 
 //End of Dependency dropdown
	 parent::display();	
 	}	
}

?>
