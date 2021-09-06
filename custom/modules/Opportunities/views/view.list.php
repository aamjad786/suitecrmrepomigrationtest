<?php
// Sorting opportunities in reverse chronological order by default (on any date created)
require_once('include/MVC/View/views/view.list.php');
class OpportunitiesViewList extends ViewList
{
       public function __construct()
       {

              parent::__construct();
       }

       function listViewPrepare()
       {

              $_REQUEST['orderBy'] = strtoupper('date_entered');
              $_REQUEST['sortOrder'] = 'desc';
              parent::listViewPrepare();
       }
}
