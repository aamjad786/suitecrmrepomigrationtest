<?php
require_once('include/DetailView/DetailView2.php');
class Neo_CustomersViewDetail extends SugarView
{
	public function preDisplay() {
        $metadataFile = $this->getMetaDataFile();
        $this->dv = new DetailView2();
        $this->dv->ss = & $this->ss;
        $this->dv->setup($this->module, $this->bean, $metadataFile, get_custom_file_if_exists('include/DetailView/DetailView.tpl'));
    }
	
	public function display() {
		$this->dv->process();
        echo $this->dv->display();
        require_once('modules/Neo_Customers/Renewals_functions.php');
        $renewals = new Renewals_functions();
        $is_eligible = $renewals->isEligibleForTentativeOffer($this->bean);
        // var_dump($is_eligible);die();
        ?>
		<script>
			var app_id = "<?= $this->bean->app_id; ?>";
			var mobile = "<?= $this->bean->mobile; ?>";
            var queue_type = "<?= $this->bean->queue_type; ?>";
            var instant_renewal_eligibility = "<?= $this->bean->instant_renewal_eligibility; ?>";
            var id = "<?= $this->bean->id; ?>";
            console.log(queue_type + ' - ' + instant_renewal_eligibility);
            var is_eligible = "<?= $is_eligible; ?>";
            console.log('is_eligible:'+is_eligible);
            if(is_eligible){
                var get_tentative_deal_button = "&nbsp;<a target='_blank' id='create_link' href='?module=Neo_Customers&action=tentativeofferdetails&id="+id+"' class='utilsLink'>View tentative offer given</a>";
                $(".utils").append(get_tentative_deal_button);
                
            }
            $(document).ready(function () {
                var app_id_list = $('#app_id_list').text();
                var cust_id = $('#customer_id').text();
                cust_id = cust_id.replace(/,/g,"");
            	$('td[field="mobile"]').append('   &nbsp;&nbsp;<a href="?module=Neo_Customers&action=phones&app_id='+app_id_list+'&phone='+mobile+'&customerID='+cust_id+'" target="_blank">View Details</a>');
            	var app_id_arr = app_id_list.split(',');
            	var str = "";
            	for(var i = 0; i < app_id_arr.length; i++) {
            		app_id = app_id_arr[i];
            		if(i>0){
            			str+=", ";
            		}
            		str+= "<a href='?module=Cases&action=customer_application_profile&applicationID="+app_id+"&details=Get+Details' target='_blank'>"+app_id+"</a>";
            	}
                linked_cust_id = "<a href='?module=scrm_Custom_Reports&action=RenewalCustomerProfile&customerID="+cust_id+"&details=Get+Details' target='_blank'>"+cust_id+"</a>";
                $('#customer_id').html(linked_cust_id);
                
            	$('#app_id_list').text();
            	$('#app_id_list').html(str);

                $('#location').append('   &nbsp;&nbsp;<a href="?module=Neo_Customers&action=address&app_id='+app_id_list+'" target="_blank">View Address Details</a>');
                 $('td[field="location"]').append('   &nbsp;&nbsp;<a href="?module=Neo_Customers&action=address&app_id='+app_id_list+'" target="_blank">View Address Details</a>');


            });
        </script>
	    <?php
	}
	


}