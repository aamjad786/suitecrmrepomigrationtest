<?php
require_once('include/MVC/View/views/view.list.php');
class Neo_Paylater_LeadsViewList extends ViewList
{
	function listViewPrepare() {    
		global $current_user;
		// die();
		?>
		<script>
				// $(document).ready(function(){
				// 	$('.selectActions .sugar_action_button').html($( "a:contains('Export')" ).parent().html());
				// 	$('.selectActionsDisabled a').text('Export');
				// 	$('.selectActionsDisabled span').remove();
				// });
		</script>
		<?php
		parent::listViewPrepare(); 


	}


}
