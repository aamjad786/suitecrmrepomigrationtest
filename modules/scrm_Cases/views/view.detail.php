<?php
require_once('include/DetailView/DetailView2.php');
class scrm_CasesViewDetail extends SugarView
{

	function __construct(){
 		parent::__construct();
 	}
	
	public function preDisplay() {
        $metadataFile = $this->getMetaDataFile();
        $this->dv = new DetailView2();
        $this->dv->ss = & $this->ss;
        $this->dv->setup($this->module, $this->bean, $metadataFile, get_custom_file_if_exists('include/DetailView/DetailView.tpl'));
    }

	public function display() {
		$this->dv->process();
		echo $this->dv->display();

		?>
		<script>
		
		$(document).ready(function() {
			var user_1 = "<?php echo $this->bean->esc_1_user; ?>";
			var user_2 = "<?php echo $this->bean->esc_2_user; ?>";
			var user_3 = "<?php echo $this->bean->esc_3_user; ?>";

			$('#scrm_cases_usersusers_ida').html(user_1);
			$('#scrm_cases_users_1users_ida').html(user_2);
			$('#scrm_cases_users_2users_ida').html(user_3);
		});
		</script>

		<?php

	}
	


}
?>