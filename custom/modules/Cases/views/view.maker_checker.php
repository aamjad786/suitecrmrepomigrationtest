<?php
if(!defined('sugarEntry')) define('sugarEntry', true);

ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once('custom/include/SendEmail.php');
require_once('modules/EmailTemplates/EmailTemplate.php');

class CasesViewMaker_checker extends SugarView {
    
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
        global $current_user;
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $roles = ACLRole::getUserRoleNames($current_user->id);
        if (strpos($url, 'crm.advancesuite.in') !== false) {
            $permitted_users = array("NG377", "NG855", "NG950", "NG1007", "NG660", "NG894","NG478","NG866","NG1647","NG536","NG2029","NG2054","NG2064");
            if (!$current_user->is_admin && !in_array($current_user->user_name, $permitted_users)) {
                die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
            }
        }
    }

   

    function displayForm(){
        $env = getenv('SCRM_SITE_URL');
        echo $html = <<<HTMLFORM
        <h1 class='text-center'>Maker & Checker Dashboard</h1><br/>
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
        
        global $db,$current_user;
        $date = date('Y-m-d', strtotime('-7 days'));
        $query = "select s.id,case_number,maker_comment_c,case_category_c,date_of_changes_c,date_of_request_c,case_subcategory_c,case_category_c_new_c, case_subcategory_c_new_c, first_name,last_name from cases s join cases_cstm c on s.id=c.id_c join users u on c.maker_id_c=u.id where case_category_approval_c=0 order by c.date_of_request_c desc";
      
        $res = $db->query($query);
        
        $env = getenv('SCRM_ENVIRONMENT');
        if($env =='prod'){
            $checker =['ng1647','ng536']; // Manisha,Yogesh
        } else {
            $checker = ['ng1273','ng619','ng1275']; // Nikhil, GOPI
        }

        if(in_array(strtolower($current_user->user_name),$checker)){

            echo '<input type="submit" class="btn btn-danger" style="float: right;background-color: red !important;" data-user_id="'.$current_user->id.'" id="reject_category" name="filterEod" value="Reject Category">
            <input type="submit" class="btn btn-success"  data-user_id="'.$current_user->id.'" style="float: right; " id="approve_category" name="filterEod" value="Approve Category">';
        }

       //<input type="checkbox" id="vehicle1" class="select_all" name="vehicle1" value="">
        echo '<table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: left;">S.No</th>
                <th style="text-align: left;">&nbsp;</th>
                <th style="text-align: left;">Case Number</th>
                <th style="text-align: left;">Current Category</th>
                <th style="text-align: left;">Current Sub Category</th>
                <th style="text-align: left;">Proposed Category</th>
                <th style="text-align: left;">Proposed Sub Category</th>
                <th style="text-align: left;">Maker Remark</th>
                <th style="text-align: left;">Date of Request</th>
                <th style="text-align: left;">Maker</th>
                <th style="text-align: left;">Link</th>
            </tr>
        </thead>
        <tbody>';
      
        $env = getenv('SCRM_ENVIRONMENT');
        if($env =='prod'){
            $url ='https://crm.advancesuite.in/SuiteCRM/index.php?module=Cases&action=DetailView&record='; 
        } else {
            $url = 'https://uat.advancesuite.in/SuiteCRM/index.php?module=Cases&action=DetailView&record=';
        }
        $i=1;
       
        while($row = $db->fetchByAssoc($res)){
            $url1 =$url.$row['id'];
            
           echo "<tr>";
           echo   "<td>".$i++."</td>";
           echo   "<td><input type='checkbox' id='case_data_id' name='case_data_id' class='checkBoxClass' value='".$row['id']."'></td>";
           echo   "<td>".$row['case_number']."</td>";
           echo   "<td>".$row['case_category_c']."</td>";
           echo   "<td>".$row['case_subcategory_c']."</td>";
           echo   "<td>".$row['case_category_c_new_c']."</td>";
           echo   "<td>".$row['case_subcategory_c_new_c']."</td>";
           echo   "<td>".$row['maker_comment_c']."</td>";
           echo   "<td>".(!empty($row['date_of_request_c'])?date('d-m-Y H:i:s',strtotime($row['date_of_request_c'])):'Not Found')."</td>";
           echo   "<td>".$row['first_name']." ".$row['last_name']."</td>";
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
<script type="text/javascript" src="custom/include/js/jquery.min.js"></script>
<script type="text/javascript" src="custom/include/js/moment.min.js"></script>
<script type="text/javascript" src="custom/include/js/jquery-3.3.1.js"></script>
<script>
$(document).on('click','.select_all',function(){
    if($(this).prop("checked") == true){
        $(".checkBoxClass").prop('checked', true);
    }
    else if($(this).prop("checked") == false){
        $(".checkBoxClass").prop('checked', false);
    }  
});


$(document).on('click','#approve_category',function(){
    var category = [];
    $. each($("input[name='case_data_id']:checked"), function(){
        category. push($(this). val());
    }); 
    var user_id = $(this).attr('data-user_id');
   
    if(category.length >=1){

        var r = confirm("Are you sure to bulk approve this change's!");
        if(r ==true){
            $.ajax({
                type	:	'POST',
                url:"JavascriptAPICall.php?api=bulkapproveCategory",
                cache	:	false,
                data	: 	{category:category,checker_comments:'Bulk Approval',user_id:user_id},				
                success : 	function(res){	 
                    if(res== 1){
                        alert('Thank you, for your approval!');
                        location.reload();
                    } else {
                        alert("Something went wrong");
                    }
                }	
            });
        }
    }   
});

$(document).on('click','#reject_category',function(){
    var category = [];
    $. each($("input[name='case_data_id']:checked"), function(){
        category. push($(this). val());
    }); 
    var user_id = $(this).attr('data-user_id');
   
    if(category.length >=1){
      
        var r = confirm("Are you sure to bulk reject this change's");
        if(r ==true){
            $.ajax({
                type	:	'POST',
                url:"JavascriptAPICall.php?api=bulkRejectCategory",
                cache	:	false,
                data	: 	{category:category,checker_comments:'Bulk Rejected',user_id:user_id},				
                success : 	function(res){	 
                    if(res== 1){
                        alert('Category/SubCategory change request Rejected!');
                        location.reload();
                    } else {
                        alert("Something went wrong");
                    }
                }	
            });
        }
    }   
});


</script>


