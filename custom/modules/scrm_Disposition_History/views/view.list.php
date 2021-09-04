<?php
// Sorting opportunities in reverse chronological order by default (on any date created)
require_once('include/MVC/View/views/view.list.php');
class scrm_Disposition_HistoryViewList extends ViewList
{
	 function Customscrm_Disposition_HistoryViewList(){
          parent::scrm_Disposition_HistoryViewList();
        }
	  function listViewPrepare() {    
        
              $_REQUEST['orderBy'] = strtoupper('date_entered');            
              $_REQUEST['sortOrder'] = 'desc'; 

           parent::listViewPrepare(); 
       }
       function display(){
		   
		   if(!$this->bean || !$this->bean->ACLAccess('list')){
            ACLController::displayNoAccess();
        } else {
            $this->listViewPrepare();
            $this->listViewProcess();
        }
        
        global $current_user, $db;
        
			echo $js=<<<EOF
				<script>
				$(document).ready(function(){
					$('.tabFormAdvLink').parent('td').hide();
				});
				</script>
EOF;
	}
}
