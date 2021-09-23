<?php
require_once('include/MVC/View/views/view.list.php');
class ContactsViewList extends ViewList
{
	 function CustomContactsViewList(){
          parent::ContactsViewList();
     }
	 function listViewPrepare() {    
            $_REQUEST['orderBy'] = strtoupper('date_entered');            
            $_REQUEST['sortOrder'] = 'desc'; 
   			parent::listViewPrepare(); 
     }
}
