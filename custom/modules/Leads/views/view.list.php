<?php
require_once('include/MVC/View/views/view.list.php');
class LeadsViewList extends ViewList
{
	 function CustomLeadsViewList(){
          parent::LeadsViewList();
        }
	  function listViewPrepare() {    
              $_REQUEST['orderBy'] = strtoupper('date_entered');            
              $_REQUEST['sortOrder'] = 'desc'; 
              parent::listViewPrepare(); 
       }
}
