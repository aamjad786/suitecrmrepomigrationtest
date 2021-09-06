<?php
require_once('include/MVC/View/views/view.list.php');
class Neo_CustomersViewList extends ViewList
{
	

	function fetchLocationInRequest(){
		$inRequestLocationArr = array();
		if(isset($_REQUEST['location_advanced'])){
			array_push($inRequestLocationArr, $_REQUEST['location_advanced']);
		}
		print_r($inRequestLocationArr);
	    if(!empty($inRequestLocationArr))
	    	$inRequestLocationArr = implode(",", $inRequestLocationArr);
		return $inRequestLocationArr;
	}
	function listViewPrepare() {    
		global $current_user;
		?>
		<script>
				$(document).ready(function(){
				
					$('td[field="customer_id"]').each(function(){
						var $this=$(this);
						var html = $this.html(); 
						var customer_id = parseInt(html.replace(/,/g, ''), 10);
						$this.html("<a href='?module=scrm_Custom_Reports&action=RenewalCustomerProfile&customerID="+customer_id+"&details=Get+Details' target='_blank'>"+customer_id+"</a>");
					});
				});
		</script>
		<?php
		parent::listViewPrepare(); 


	}

	function preDisplay(){
		$this->lv = new ListViewSmarty();
  //       $this->lv->delete = false;
	}

	public function listViewProcess()
	{
		$this->processSearchForm();
		$this->lv->searchColumns = $this->searchForm->searchColumns;

		if(!$this->headers)
			return;
		if(empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false){
			$this->lv->ss->assign("SEARCH",true);

			$tplFile = 'include/ListView/ListViewGeneric.tpl';
			
			require_once('modules/Neo_Customers/Renewals_functions.php');
    		$renewals = new Renewals_functions();
    		global $current_user;
    		$details = $renewals->getRenewalUserById($current_user->id);
    		if(!empty($details)){
	    		$ticket_size = $details['ticket_size'];
	    		$city = $details['city'];
	    		$role = $details['role'];
	    		if($role == 'Renewal manager'){
	    			if(!empty($this->where)){
					    $this->where .= " AND ";
					}
	    			$this->where .= "(".$renewals->getQueryManager($city,$ticket_size,1).")";
	    		}
            	// print_r(($this->where));
            }
			$this->lv->setup($this->seed, $tplFile, $this->where, $this->params);
			$savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
			echo $this->lv->display();
		}
 	}



}