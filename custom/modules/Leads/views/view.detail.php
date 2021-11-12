<?php
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

require_once('include/DetailView/DetailView2.php');

/**
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class LeadsViewDetail extends SugarView
{
    /**
     * @see SugarView::$type
     */
    public $type = 'detail';

    /**
     * @var DetailView2 object
     */
    public $dv;

    /**
     * Constructor
     *
     * @see SugarView::SugarView()
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function LeadsViewDetail(){
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if(isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        }
        else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
 	    $metadataFile = $this->getMetaDataFile();
 	    $this->dv = new DetailView2();
 	    $this->dv->ss =&  $this->ss;
 	    $this->dv->setup($this->module, $this->bean, $metadataFile, get_custom_file_if_exists('include/DetailView/DetailView.tpl'));
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
		global $app_list_strings;
		$total_sales = $this->bean->total_sales_per_month_c;
		$loan_amount_c = $this->bean->loan_amount_c;
		
        if(empty($this->bean->id)){
            sugar_die($GLOBALS['app_strings']['ERROR_NO_RECORD']);
        }
        
		$acc_id = $this->bean->account_id;
		$opp_id = $this->bean->opportunity_id;
		$opp=BeanFactory::getBean('Opportunities',$this->bean->opportunity_id);
		
        if(!empty($this->bean->primary_address_city)){
			
			$this->bean->primary_address_city = ($app_list_strings['city_pincodes_list'][$this->bean->primary_address_city]?$app_list_strings['city_pincodes_list'][$this->bean->primary_address_city]:$this->bean->primary_address_city);
		}
        if(!empty($this->bean->alt_address_city)){
			
			$this->bean->primary_address_city = ($app_list_strings['city_pincodes_list'][$this->bean->primary_address_city]?$app_list_strings['city_pincodes_list'][$this->bean->primary_address_city]:$this->bean->primary_address_city);
			$this->bean->alt_address_city = ($app_list_strings['city_pincodes_list'][$this->bean->alt_address_city]?$app_list_strings['city_pincodes_list'][$this->bean->alt_address_city]:$this->bean->alt_address_city);
		}
		
		$city = $opp->pickup_appointment_city_c;
		$contant_no = $opp->pickup_appointment_contact_c;
		$date = date('m/d/Y',strtotime($opp->pickup_appointment_date_c));
		$hour = date('H',strtotime($opp->pickup_appointment_date_c));
		$min = date('i',strtotime($opp->pickup_appointment_date_c));

        echo $js = <<<onload
        <script>

        	function parseCurrency(el,text){
        		if(!text){
        			var text = el.text();
        		}
        		var floatVal = parseFloat(text);
        		console.log(floatVal);
        		if(floatVal){
        			parsed =  floatVal.toLocaleString('en-IN', {
							    maximumFractionDigits: 2,
							    style: 'currency',
							    currency: 'INR'
							});
					el.text(parsed);
        		}
        	}
			$(document).ready(function(){

				console.log('document is ready');
				if('$opp_id' == '' || '$opp_id' == 'NULL'){
					console.log('hiding opp panel');
					$('#top-panel-0').prev().hide();
					$('#top-panel-0').hide();
				}

				$('#leads_scrm_disposition_history_1_create_button').parent().parent().hide();
				$('#pickup_appointment_city_c').after('<span>$city</span>');
				$('#pickup_contact_number_c').text('$contant_no');
				$('#pickup_appointment_date_time_c').text('$opp->pickup_appointment_date_c');
				$('#pickup_appointment_pincode_c').text('$opp->pickup_appointment_pincode_c');


				if('$opp_id' != ''){
					var opp_name = $("#opportunity_name").text();
					$("#opportunity_name").html('').append('<a href="index.php?module=Opportunities&action=DetailView&record=$opp_id"><span id="opportunity_name" class="sugar_field">'+opp_name+'</span></a>');
				}
				if('$acc_id' != ''){
					var acc_name = $("#account_name").text();
					$("#account_name").html('').append('<a href="index.php?module=Accounts&action=DetailView&record=$acc_id"><span id="account_name" class="sugar_field">'+acc_name+'</span></a>');
				}
				
				parseCurrency($('#total_sales_per_month_c'),'$total_sales');
				parseCurrency($('#average_total_monthly_sales_c'));
				parseCurrency($('#loan_amount_c'),'$loan_amount_c');
			});
        </script>
onload;
        
      $js = <<<EOD
	<script>
	$(document).ready(function(){
			
			var disposition = $("#disposition_c").val();
			console.log('disposition: '+disposition);
			if(disposition == 'call_back'){
				$("#call_back_date_time_c_label").parent().show();
				$("#pickup_appointment_c_label").parent().hide();
				$("#call_back_date_time_c").parent().show();
				$("#pickup_appointment_c").parent().hide();
			}
			if(disposition != 'call_back'){
				$("#call_back_date_time_c_label").parent().hide();
				$("#call_back_date_time_c").parent().hide();
			}
				
			if(disposition == 'Pickup generation_Appointment'){
				$("#call_back_date_time_c_label").parent().hide();
				$("#pickup_appointment_c_label").parent().show();
				$("#call_back_date_time_c").parent().hide();
				$("#pickup_appointment_c").parent().show();
			}
			if(disposition != 'Pickup generation_Appointment'){
				$("#pickup_appointment_c").parent().hide();
				$("#pickup_appointment_c_label").parent().hide();
			}
				
				
			});
</script>
EOD;


?>

<script>

	$(document).ready(function(){
		$('#description').html($('#description').text());
	});
</script>
	<?php

        $this->dv->process();
        echo $this->dv->display();
    }
}

