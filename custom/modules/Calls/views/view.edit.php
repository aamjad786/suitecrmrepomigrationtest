<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/


require_once('include/json_config.php');

class CallsViewEdit extends ViewEdit
{
 	/**
 	 * @see SugarView::preDisplay()
 	 */
 	public function preDisplay()
 	{
 		if($_REQUEST['module'] != 'Calls' && isset($_REQUEST['status']) && empty($_REQUEST['status'])) {
	       $this->bean->status = '';
 		} //if
        if(!empty($_REQUEST['status']) && ($_REQUEST['status'] == 'Held')) {
	       $this->bean->status = 'Held';
 		}
 		parent::preDisplay();
 	}

 	/**
 	 * @see SugarView::display()
 	 */
 	public function display()
 	{
 		global $json;
        $json = getJSONobj();
        $json_config = new json_config();
		if (isset($this->bean->json_id) && !empty ($this->bean->json_id)) {
			$javascript = $json_config->get_static_json_server(false, true, 'Calls', $this->bean->json_id);

		} else {
			$this->bean->json_id = $this->bean->id;
			$javascript = $json_config->get_static_json_server(false, true, 'Calls', $this->bean->id);

		}
 		$this->ss->assign('JSON_CONFIG_JAVASCRIPT', $javascript);
            
		$this->ss->assign('remindersData', Reminder::loadRemindersData('Calls', $this->bean->id, $this->ev->isDuplicate));
		$this->ss->assign('remindersDataJson', Reminder::loadRemindersDataJson('Calls', $this->bean->id, $this->ev->isDuplicate));
		$this->ss->assign('remindersDefaultValuesDataJson', Reminder::loadRemindersDefaultValuesDataJson());
		$this->ss->assign('remindersDisabled', json_encode(false));

 		if($this->ev->isDuplicate){
	        $this->bean->status = $this->bean->getDefaultStatus();
 		} //if
 		parent::display();
                ?>
                <script>
                    $( "<p id = 'app_detail_link'></p>" ).insertAfter( "#app_id_c" );
                    
                    $("#app_id_c").change(function(){
                        app_id_c = $("#app_id_c").val();
                        LoadAppDetails(app_id_c);
                       
                    });

                    function fill_data(url, data){
                        if(data){
                            var parsed_json = JSON.parse(data);
                            if(url=="get_merchant_details"){
                                var parsed_data = parsed_json[0];
                                $('#establishment_name_c').val(parsed_data['Company Name']);
                                $('#branch_c').val(parsed_data['Branch Name']);
                                $('#email_id_c').val(parsed_data['Applicant Email Id']);
                                $('#contact_number_c').val(parsed_data['Applicant Number']);
                            }else if(url=="get_application_repaymec_details"){
                                var parsed_data = parsed_json[0];
                                 $('#repayment_mode_c').val(parsed_data['Repayment Mode']);
                            }else if(url=="get_application_funding_details"){
                                var parsed_data = parsed_json[0];
                                $('#funded_date_c').val(parsed_data['Funded Date']);
                            }else if(url=="get_app_status"){
                                $('#loan_status_c').val(parsed_json['app_status'] == 'Y' ? 'Closed' : 'Active');
                            }

                        }
                    }

                    function getAjaxData(url,app_id_c){
                        $.ajax({
                                type: "GET",
                                url: "ajax_call.php?URL="+url+"&ENV=SCRM_AS_API_BASE_URL&application_id="+app_id_c,
                                async: true,
                                success: function(data){
                                    console.log(data);
                                    fill_data(url,data);
                                    
                                },
                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    console.log('Some error in ajax_call '+url);
                                    console.log(textStatus+' '+errorThrown);
                                }
                            }); 
                    }

                    function LoadAppDetails(app_id_c){
                        if(app_id_c == "")return;
                        
                        getAjaxData("get_merchant_details",app_id_c);
                        getAjaxData("get_application_repaymec_details",app_id_c);
                        getAjaxData("get_application_funding_details",app_id_c);
                        getAjaxData("get_app_status",app_id_c);
                        
                        var url = "index.php?module=Cases&action=customer_application_profile&applicationID="+app_id_c;
                        $("#app_detail_link").html("<a target='_blank' href='"+url+"''>View Application Details</a>");
                        
                    }
                </script>
                <?php
 	}
}
