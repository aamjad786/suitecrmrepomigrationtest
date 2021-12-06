<?php
if(!defined('sugarEntry')) define('sugarEntry', true);

ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once 'custom/include/SendEmail.php';
require_once('modules/EmailTemplates/EmailTemplate.php');

class CasesViewMaker_checker_history extends SugarView {
    
    private $log;
    function __construct() {
        parent::SugarView();
        $this->log = fopen("Logs/CaseAgentAttendance.log", "a");
        $this->form_message = "";
        $this->file_message = "";
    }
    function __destruct() {
        fclose($this->log);
    }
    function checkAccess(){
        global $current_user, $sugar_config;
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $roles = ACLRole::getUserRoleNames($current_user->id);
        if (strpos($url, $sugar_config['AS_CRM_Domain']) !== false) {
            $permitted_users = $sugar_config['maker_checker_h_permitted_user'];
            if (!$current_user->is_admin && !in_array($current_user->user_name, $permitted_users)) {
                die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
            }
        }
    }

   

    function displayForm(){
        $env = getenv('SCRM_SITE_URL');
        echo $html = <<<HTMLFORM
        <h1 class='text-center'>Maker & Checker History</h1><br/>
HTMLFORM;
    }
    function displayForm2(){
        echo $html = <<<HTMLFORM_2
        <hr/>
        <br>
        <script type="text/javascript" src="custom/include/js/jquery.min.js"></script>
        <script type="text/javascript" src="custom/include/js/moment.min.js"></script>
      
        <script type="text/javascript" src="custom/include/js/jquery-3.3.1.js"></script>
        <script type="text/javascript" src="custom/include/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="custom/include/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="custom/include/js/jszip.min.js"></script>
        <script type="text/javascript" src="custom/include/js/pdfmake.min.js"></script>
        <script type="text/javascript" src="custom/include/js/vfs_fonts.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="custom/include/js/buttons.print.min.js"></script>
        <script type="text/javascript" src="custom/include/js/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="custom/include/css/daterangepicker.css"/>
        <link rel="stylesheet" type="text/css" href="custom/include/css/dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="custom/include/css/buttons.dataTables.min.css"/>
       
        <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'excel'
                ]
            } );
        } );
           
            $(function () {
                $('input[name="datefilter"]').daterangepicker({
                    autoUpdateInput: false,
                    minDate: new Date(),
                    changeMonth: true, 
                    changeYear: false, 
                    dateFormat: 'Y-m-d',
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });
                $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
                        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                    });
                $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
                        $(this).val('');
                    });
                $('input[name="datefilter"]').on('change.daterangepicker', function (ev, picker) {
                    console.log($(this).val());
                });
            });
            $(document).on('click','.data',function(){
                var id = $(this).attr('data-id');
                var r = confirm('Are you sure delete this record');
                if(r== true){
                  $.ajax({
                    url:"JavascriptAPICall.php?api=deleteAttandance",
                    type: "post",
                    data:{id:id},
                    success:function(result){
                        console.log(result);
                        if(result == 1){
                            location.reload();
                        } else {
                            alert("Something went wrong");
                        }
                    }
                  });
                }
            });
        </script>
HTMLFORM_2;
    }

    function fetchUserId($user_id){
        global $db;
        $query = "select id from users where user_name = '$user_id'";
        $res = $db->query($query);
        $row = $db->fetchByAssoc($res);
        return $row['id'];
    }

    function handleFormUpload(){
        
        global $db;
        $date = date('Y-m-d', strtotime('-7 days'));
        $query = "select s.id,case_number,checker_comment_c,maker_comment_c,case_category_c,case_category_old_c,case_subcategory_old_c,date_of_changes_c,date_of_request_c,case_subcategory_c,case_category_c_new_c,checker_c,case_subcategory_c_new_c, checker_c, first_name,last_name,case_category_approval_c,case_category_counts_c,(select concat(first_name,'',last_name) from users where id=checker_c) as checker_name from cases s join cases_cstm c on s.id=c.id_c join users u on c.maker_id_c=u.id where case_category_approval_c IN (1,2) order by c.date_of_changes_c desc";
        
        $res = $db->query($query);
       
        echo '<table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: left;">S.No</th>
                <th style="text-align: left;">Case Number</th>
                <th style="text-align: left;">Previous <br>Category</th>
                <th style="text-align: left;">Previous <br>Sub Category</th>
                <th style="text-align: left;">Current Category</th>
                <th style="text-align: left;">Current <br>Sub Category</th>
                <th style="text-align: left;">Proposed <br>Category</th>
                <th style="text-align: left;">Proposed </br>Sub Category</th>
                <th style="text-align: left;">Maker Comment</th>
                <th style="text-align: left;">Checker Comment</th>
                <th style="text-align: left;">Date of <br>Approval</th>
                <th style="text-align: left;">Date of <br>Request</th>
                <th style="text-align: left;">Approval<br> count</th>
                <th style="text-align: left;">Maker</th>
                <th style="text-align: left;">Checker</th>
                <th style="text-align: left;">Status</th>
                <th style="text-align: left;">Link</th>
            </tr>
        </thead>
        <tbody>';
      
        $url = getenv('SCRM_SITE_URL')."index.php?module=Cases&action=DetailView&record=";
        $i=1;
        while($row = $db->fetchByAssoc($res)){
            $url1 =$url.$row['id'];
           echo "<tr>";
           echo   "<td>".$i++."</td>";
           echo   "<td>".$row['case_number']."</td>";
           echo   "<td>".$row['case_category_old_c']."</td>";
           echo   "<td>".$row['case_subcategory_old_c']."</td>";
           echo   "<td>".$row['case_category_c']."</td>";
           echo   "<td>".$row['case_subcategory_c']."</td>";
           echo   "<td>".$row['case_category_c_new_c']."</td>";
           echo   "<td>".$row['case_subcategory_c_new_c']."</td>";
           echo   "<td>".$row['maker_comment_c']."</td>";
           echo   "<td>".$row['checker_comment_c']."</td>";
           echo   "<td>".(!empty($row['date_of_changes_c'])?date('d-m-Y H:i:s',strtotime($row['date_of_changes_c'])):'Not Found')."</td>";
           echo   "<td>".(!empty($row['date_of_request_c'])?date('d-m-Y H:i:s',strtotime($row['date_of_request_c'])):'Not Found')."</td>";
           echo    "<td>".(!empty($row['case_category_counts_c'])?$row['case_category_counts_c']:'0')."</td>";
           echo   "<td>".$row['first_name']." ".$row['last_name']."</td>";
           echo   "<td>".$row['checker_name']."</td>";
           echo   "<td>".($row['case_category_approval_c']==1?'<span style="color:green">Approved</span>':'<span style="color:red">Rejected</span>')."</td>";
           echo   "<td><a href=".$url1." target='_blank'>Link</a></td>";
           echo "</tr>";
        }
      echo  '</tbody>
    </table>';
    }

    function display() {
       
        $this->checkAccess();
        $this->displayForm();
        echo $this->file_message;
        $this->displayForm2();
            $this->handleFormUpload();
        echo $this->form_message;
    }


}
?>

