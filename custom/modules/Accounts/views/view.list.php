<?php
require_once('include/MVC/View/views/view.list.php');
class AccountsViewList extends ViewList
{
	 function CustomAccountsViewList(){
          parent::AccountsViewList();
        }
	  function listViewPrepare() {    
              $_REQUEST['orderBy'] = strtoupper('date_entered');            
              $_REQUEST['sortOrder'] = 'desc'; 
           parent::listViewPrepare(); 
       }

       function display(){
       	print '<style type="text/css">#create_link, #create_image{ display:none; }</style>';
       	parent::display();
       }
}
