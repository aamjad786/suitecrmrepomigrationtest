<?php
require_once('include/MVC/View/views/view.list.php');
class Neo_CustomersViewEdit extends ViewEdit
{

	
    function display(){
    	$this->ev->process();
		$display = '<div style="padding-top:30px"><center><h2>You can\'t insert.</h2></center></div>';
        require_once 'modules/ACLRoles/ACLRole.php';
        $objACLRole = new ACLRole();
        $roles = $objACLRole->getUserRoles($this->bean->assigned_user_id);
        $renewal_admin = false;
        if(in_array('Renewal Admin',$roles)) {
            $renewal_admin = true;
        }
		if(empty($this->bean->id)) {
			echo $display;
		}else
		{
        	parent::display();
            // print_r($this->bean);die();
            ?>
            <script>
            	var queue_type = "<?= $this->bean->queue_type; ?>";
                var renewal_admin = "<?= $renewal_admin; ?>";
                console.log("hie"+queue_type);
            	$('#detailpanel_1 input').attr('disabled',true);
            	$('#detailpanel_3 input').attr('disabled',true);
            	$('#detailpanel_4 input').attr('disabled',true);
            	$('#detailpanel_3 select').attr('disabled',true);
            	$('#detailpanel_1 select').attr('disabled',true);
                $('#hot_lead_trigger_time').attr('disabled',true);
                $('#hot_lead_trigger_time_trigger').hide();
                console.log(renewal_admin);
                if(!renewal_admin && queue_type == "not_eligible"){
                	$('#disposition').attr('disabled',true);
                	$('#subdisposition').attr('disabled',true);
                    $('#assigned_user_name').attr('disabled',true);
                    $('#assigned_user_id').attr('disabled',true);
                }
                // $('input[name="hot_lead"]').attr('disabled',false);
                // $('#assigned_user_name').attr('disabled',false);
                // $('#assigned_user_id').attr('disabled',false);
            	// $('#source').attr('disabled',true);
            </script>
            <?php
        }
    }



}