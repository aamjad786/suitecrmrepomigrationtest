<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');

require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
require_once 'custom/include/SendEmail.php';
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('include/MVC/View/views/view.list.php');


class scrm_Custom_ReportsViewrenewal_customer_profile extends ViewList{
    var $lead_latest_funded_date;
    var $crm_renewed_app_ids;
    function isEmpty($var){
        if(empty($var)){
            return "-";
        }
        return $var;
    }

    function curl_req($url, $headers = null){
        // return "";
        $ch = curl_init();
        if(empty($headers)){
            $headers  = ['Content-Type: application/json'];
        }
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $output = curl_exec($ch);
        curl_close($ch);
        // echo "here <br>";
        // print_r($output);
        // echo "<br>";
        return $output;
    }

    function __construct(){    
        parent::__construct();
        $this->lead_latest_funded_date = array();
        $this->crm_renewed_app_ids = array();
    }

    function getUserName($user_name){
        $user = BeanFactory::getBean('Users',$user_name);
        if($user)
            return $user->first_name." ".$user->last_name;
        return "";
    }

    function isRenewalsUser($roles){
        $results = false;
        if(empty($roles)) {
            return $results;
        }
        foreach ($roles as $role) {
            if(stripos($role,"Renewal") !== false){
                $results = true;
                break;
            }
        }   
        return $results;
    }

    function disableUpfrontCheckBoxScript(){
        $funded_date_json = json_encode($this->lead_latest_funded_date);
        echo $html = <<<SCRIPT_UPFRONT
    <script>
    //balaup
    console.log("in");
        $("#applications_table tr").not(":first").each(function () {

            var as_lead_id = $(this).find("td").find(".as_lead_id").text();
            as_lead_id = parseInt(as_lead_id);
            var hidden_funded_date = $(this).find("td").find(".hidden_funded_date").val();
            var lead_latest_funded_date = {$funded_date_json};
            // console.log(hidden_funded_date);
            // console.log(as_lead_id);
            // console.log(lead_latest_funded_date);
            // console.log('test1 = ' + lead_latest_funded_date['1000000']);
            // console.log('test2 = ' + lead_latest_funded_date[parseInt(as_lead_id)]);
            // console.log(jQuery.type(lead_latest_funded_date));
            // console.log(jQuery.inArray(as_lead_id, lead_latest_funded_date));
            // console.log(lead_latest_funded_date[as_lead_id] == hidden_funded_date);
            
            // continue if the checkbox is disabled already, continue
            if ($(this).find("td").find(".upfront_deduction").is(':disabled')){
                return;
            }
            if(lead_latest_funded_date[as_lead_id] !== undefined 
                && lead_latest_funded_date[as_lead_id] == hidden_funded_date){
                console.log("if");
                $(this).find("td").find(".upfront_deduction").removeAttr("disabled");
            }
            else{
                console.log("else");
                $(this).find("td").find(".upfront_deduction").attr("disabled", true);        
            }
        });    
    </script>
    <style>
    .DTFC_ScrollWrapper{
        border: 1px solid black;
    }
    </style>
SCRIPT_UPFRONT;
    }

    function getAppDetailsOfCustomer($customer_id){
        $as_api_base_url = getenv('SCRM_AS_API_BASE_URL');
        $url = $as_api_base_url."/crm/get_customer_profile?customer_id=".$customer_id;
        $auth_token = getenv("SCRM_AS_API_RENEWALS_AUTH_KEY");
        $headers = ["Authorization: $auth_token"];
        // echo "<br> in <br>";
        $response = $this->curl_req($url, $headers);
        return $response;
    }

    function display(){ 
        global $current_user;
        $roles = ACLRole::getUserRoleNames($current_user->id);
        $permitted_users = array("NG618","NG586","NG660","NG894");
        if (!$current_user->is_admin  && !(in_array(strtoupper($current_user->user_name), $permitted_users)) && !$this->isRenewalsUser($roles)) {
            die("<p style='color:red'>You cannot access this page. Please contact admin</p>");
        }

        echo $html = <<<SCRIPT
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">


        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
        

        <script>
            $(document).ready( function () {
                $('#applications_table').DataTable({
                        /* dom:            "Bfrtip",*/
                        scrollY:        "300px",
                        scrollX:        true,
                        scrollCollapse: true,
                        paging:         false,
                        /* buttons:        [ 'colvis' ],*/
                        fixedColumns:   {
                            leftColumns: 6,
                            rightColumns: 1
                        }
                    });
            } );

            $(document).ready(function() {
            $("#btn").click(function(){
                var upfront_deduction = [];
                $.each($("input[name='upfront_deduction']:checked"), function(){            
                    upfront_deduction.push($(this).val());
                });
                $("#upfront_deduction_list").val(upfront_deduction);
            });
            });
        </script>
SCRIPT;
        echo $html = <<<HTMLFORM
        <link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">
        <h1><center><b>Renewal Customer Profiles View</b></center></h1>
        <form action="index.php?module=scrm_Custom_Reports&action=RenewalCustomerProfile" method='get'>
        <table>
        <tr>
            <td>Customer ID: &nbsp</td>
            <input type='hidden' name='module' id='module' value='scrm_Custom_Reports'/>
            <input type='hidden' name='action' id='action' value='RenewalCustomerProfile'/>
            <td><input type='text' name='customerID' id='customerID' value='$_REQUEST[customerID]'/></td>
            <td colspan="1"><input type='submit' value='Get Details' id='details' name='details'/></td>
        </tr>
        </table>
        </form>
HTMLFORM;
        
        if(!empty($_REQUEST['customerID'])){
            global $db;
            $customerID = $_REQUEST['customerID'];
            echo $HTML = <<<TITLE
            <div>
                <h2><span id="classification" style="font-size:40px"></span>&nbsp&nbsp Customer ID - $customerID</h2>
                <input type='hidden' id='customerID' value=$customerID>
            </div>
TITLE;
            $is_eligible = true;
            $bean = BeanFactory::getBean('Neo_Customers');
            $query = "neo_customers.deleted=0 and neo_customers.customer_id = '$customerID'";
            $items = $bean->get_full_list('',$query);
            $queue_type = $bean->queue_type;
            require_once('modules/Neo_Customers/Renewals_functions.php');
            $renewals = new Renewals_functions();

            if(!empty($items)){
                $app_id_list = array();
                $this->crm_renewed_app_ids = $renewals->fetchCrmRenewedNeoAppIds($customerID);
                foreach ($items as $item) {
                     
                    if($item->queue_type == "not_eligible"){
                        $is_eligible = false;
                    }else if($item->instant_renewal_eligibility == 1){
                        $is_eligible = false;
                    }else if($item->tentative_offer_requested ==1){
                        global $db;
                        $is_eligible = false;
                        $myfile = fopen("Logs/RenewalsJob.log", "a");
                        fwrite($this->log, "\n----Renewal Customer profile---\n");
                        $query = "select count(*) as count from neo_customers_audit where parent_id='$bean->id' and date_created> (NOW() - INTERVAL 1 MONTH) and field_name = as_stage;";
                        fwrite($this->log, "\nquery =$query\n");
                        $result = $db->query($query);
                        $row    = $db->fetchByAssoc($result);
                        fwrite($this->log, "\nresult =".print_r($row,true));
                        fwrite($this->log, "\n----Renewal Customer profile end---\n");
                        if(!empty($row)){
                            $count = $row['count'];
                            if((int)$count>0){
                                $is_eligible=true;
                            }
                        }
                        // if($is_eligible && !empty($item->tentative_offer_requested_time)){
                        //     $time = strtotime( "+1 month", strtotime( $item->tentative_offer_requested_time ) );
                        //     if(strtotime('now')<$time){
                        //         $is_eligible = true;
                        //     }else{
                        //         $is_eligible = false;
                        //     }
                        // }else{
                        //     $is_eligible = false;
                        // }
                    }
                    $app_id_list = explode(",",$item->app_id_list);
                    if(sizeof($app_id_list)<1){
                        echo "<b>No Application ID found for customer " . $customerID . "</b>";
                        continue;
                    }
                    $loan_status_list = explode(",",$item->loan_status_list);
                    $deduction_list = explode(",", $item->upfront_deduction_app_list);
                    $json_response = "";
                    $response = $this->getAppDetailsOfCustomer($customerID);
                    if(!empty($response)){
                        $json_response = json_decode($response, true);
                        if(empty($json_response) && count($json_response)==0){
                            echo "<b>No Application ID found for customer in AS. Please contact ADMIN" . $customerID . "</b>";
                            continue;
                        }
                    }
                    // var_dump($json_response);
                    if(!empty($json_response['message'])){
                        // echo "<br>in<br>";
                        if(!empty($json_response['message'][0])){
                            $this->displayCustomerDetails($json_response['message'][0]);
                            if(sizeof($app_id_list) != sizeof($loan_status_list)){
                                // $loan_status_list=array_fill("-", sizeof($app_id_list), -1);
                               $loan_status_list=array_fill(0, sizeof($app_id_list), "-");
                            }
                            $i=0;
                            echo "<tbody>";
                        }
                        foreach ($json_response['message'] as $app_details) {
                            // echo "<br>";print_r($app_details);echo "<br>";
                            $this->displayApplicationDetails($deduction_list,$app_details);
                        }
                    }
                    echo "</tbody></table>";
                    $this->disableUpfrontCheckBoxScript();
                    // echo "done";print_r($this->lead_latest_funded_date);echo "done";
                    if(!empty($item->app_id_list)){
                        echo "<form id = 'form1' action='?module=Neo_Customers&action=get_tentative_deals' method='post' target='_blank'>";
                        echo "<input type='hidden' id='upfront_deduction_list' name='upfront_deduction_list'>";
                        echo "<input type='hidden' id='customer_id' name='customer_id' value=$customerID>";
                        if($is_eligible)
                        echo "<input type='submit' id='btn' value='Request Tentative Deal'>";
                        else
                            echo "<input disabled='disabled' type='submit' id='btn' value='Request Tentative Deal' style='background-color:#777777 !important'>";
                        echo "</form>";
                    }
                }
                $this->displayQueries($app_id_list);
                // $this->displayQueries($app_id_list);
            }
            else{
                echo "<b>No customer details found for this id " . $customerID . "</b>"; 
            }
            echo "<div id='response'></div>";
        }
    }

    function displayCustomerDetails($json_response){
        // print_r($json_response);
        if(empty($json_response)) return ;
        $industry       = "-";
        $contact_name   = "-";
        $lead_source    = "-";
        $agent_source   = "-";
        $company_name   = "-";

        $industry       = $this->isEmpty($json_response['industry']);
        $contact_name   = $this->isEmpty($json_response['contact_name']);
        $lead_source    = $this->isEmpty($json_response['lead_source']);
        $agent_source   = $this->isEmpty($json_response['agent_source']);
        $company_name   = $this->isEmpty($json_response['company_name']);

        echo $HTML = <<<TITLE2
            <div id='detailpanel_1' class='detail view  detail508 expanded'>
            <h4>
                <a href="javascript:void(0)" class="collapseLink" onclick="collapsePanel(1);">
                <img border="0" id="detailpanel_1_img_hide" src="themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA"></a>
                <a href="javascript:void(0)" class="expandLink" onclick="expandPanel(1);">
                <img border="0" id="detailpanel_1_img_show" src="themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA"></a>
                Personal Details
                <script>
                    document.getElementById('detailpanel_1').className += ' expanded';
                </script>
            </h4>

            <table id='LBL_LISTVIEW_PANEL1' class="panelContainer" cellspacing='0'>
                <tr>
                    <td width='12.5%' scope="col">
                        Company Name:
                    </td>
                    <td class="" type="varchar" field="company_name" width='37.5%'  >
                        <span class="sugar_field" id="company_name">$company_name</span>
                    </td>
                </tr>
                <tr>
                    <td width='12.5%' scope="col">
                        Contact Name:
                    </td>
                    <td class="" type="varchar" field="contact_name" width='37.5%'  >
                        <span class="sugar_field" id="contact_name">$contact_name</span>
                    </td>
                </tr>
                <tr>
                    <td width='12.5%' scope="col">
                        Industry:
                    </td>
                    <td class="" type="enum" field="industry" width='37.5%'  >
                        <span class="sugar_field" id="industry">$industry</span>
                    </td>
                </tr> 
                <tr>
                    <td width='12.5%' scope="col">
                        Lead Source:
                    </td>
                    <td class="" type="varchar" field="lead_source" width='37.5%'  >
                        <span class="sugar_field" id="lead_source">$lead_source</span>
                    </td>
                </tr> 
                <tr>
                    <td width='12.5%' scope="col">
                        Agent Source:
                    </td>
                    <td class="" type="varchar" field="agent_source" width='37.5%'  >
                        <span class="sugar_field" id="agent_source">$agent_source</span>
                    </td>
                </tr>                
            </table>
            </div>
TITLE2;
    echo "
        <hr>
        <h4>
            Loan Details
        </h4>
        <table id = 'applications_table' class='stripe row-border order-column' style='width:100%'>
        ";

    echo $HTML = <<<DISP1

        <thead>
            <tr>
                <th>App ID</th>
                <th>Lead ID</th>
                <th>Funded Date</th>
                <th>AS Stage</th>
                <th>AS Sub Stage</th>
                <th>Is Renewals</th>
                <th>Funded Amount<br>(INR)</th>
                <th>LBAL</th>
                <th>% paid</th>
                <th>Repayment Frequency</th>
                <th>Annualized Flat Rate</th>
                <th>Term</th>
                <th>WH %</th>
                <th>Status</th>
                <th>Performance %</th>
                <th>Repayment Mode</th>
                <th>N/T</th>
                <th>Exclusivity</th>
                <th>Upfront Deduction</th>
            </tr>
            </thead>
DISP1;
    }


    function displayQueries($app_id_list){      
        if(!empty($app_id_list)) {
            $bean = BeanFactory::getBean('Cases');
        if ($app_id_list){
            echo "
                <div id='detailpanel_4' class='' style='overflow-x:auto;'>
                <h4>
                    <a href='javascript:void(0)' class='collapseLink' onclick='collapsePanel(4);'>
                    <img border='0' id='detailpanel_4_img_hide' src='themes/SuiteR/images/basic_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
                    <a href='javascript:void(0)' class='expandLink' onclick='expandPanel(4);'>
                    <img border='0' id='detailpanel_4_img_show' src='themes/SuiteR/images/advanced_search.gif?v=s4x4C4dlTyXYwTkkd0QXjA'></a>
                    Queries/Requests/Complaints
                    <script>
                        document.getElementById('detailpanel_4').className += ' expanded';
                    </script>
                </h4>
                <table border='0' cellpadding='0' cellspacing='0' width='100%' class='panelContainer list view table default footable-loaded footable' >
                ";

            echo $HTML = <<<DISP5
            <div style='border-bottom:1px solid #dddddd; align:left;'>
                <th scope='col'>
                    <div style='white-space: normal;' align='left'>
                            Request ID
                            &nbsp;&nbsp;
                    </div>
                </th>
                <th scope='col'>
                    <div style='white-space: normal;' align='left'>
                            Application ID
                            &nbsp;&nbsp;
                    </div>
                </th>
                <th scope='col'>
                    <div style='white-space: normal;' align='left'>
                            Subject
                            &nbsp;&nbsp;
                    </div>
                </th>
                <th scope='col'>
                    <div style='white-space: normal;' align='left'>
                            Type
                            &nbsp;&nbsp;
                    </div>
                </th>
                <th scope='col'>
                    <div style='white-space: normal;' align='left'>
                            Case Category
                            &nbsp;&nbsp;
                    </div>
                </th>
                <th scope='col'>
                    <div style='white-space: normal;' align='left'>
                            Date Created
                            &nbsp;&nbsp;
                    </div>
                </th>
                <th scope='col'>
                    <div style='white-space: normal;' align='left'>
                            Resolution TAT
                            &nbsp;&nbsp;
                    </div>
                </th>
            </div>

DISP5;
            foreach ($app_id_list as $key => $value) {
                $query = "cases.deleted=0 and cases_cstm.merchant_app_id_c='$value'";  
                $items = $bean->get_full_list('case_number desc',$query);
                if(!empty($items)){
                    $this->displayCases($items,$value);
                }
            }                
                
            }else{
                echo "<tr><td>No Cases with the APP ID $app_id</h2></td>";
            }
            echo "</table></div>";
        }
    }

    function IND_money_format($money){
        $len = strlen($money);
        $m = '';
        $money = strrev($money);
        for($i=0;$i<$len;$i++){
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$len){
                $m .=',';
            }
            $m .=$money[$i];
        }
        return strrev($m);
    }

    function displayApplicationDetails($deduction_list,$json_response){

        $app_id                         = "-";
        $paid_remaining                 = "-";
        $paid_percentage                = "-";
        $funded_date                    = "-";
        $current_balance                = "-";   
        $funded_amount                  = "-";
        $performance                    = "-";
        $funded_date_strot              = "-";  
        $repayment_frequency            = "-";
        $product_nt                     = "-";
        $terminal_exclusivity_status    = "-";
        $repayment_mode                 = "-";   
        $term_days                      = "-";
        $annualized_flat_rate           = "-";
        $with_holding_rate              = "-";   
        if(!empty($json_response)){
            $app_id                         = $this->isEmpty($json_response['application_id']);
            $funded_date                    = $this->isEmpty($json_response['funded_date']);
            $performance                    = $this->isEmpty(round($json_response['performance']))."%";
            $repayment_frequency            = $this->isEmpty($json_response['frequency']);
            $product_nt                     = $this->isEmpty($json_response['product_nt']);
            $terminal_exclusivity_status    = $this->isEmpty($json_response['exclusivity']);
            $repayment_mode                 = $this->isEmpty($json_response['repay_mode']);
            $term_days                      = $this->isEmpty($json_response['tenure']);
            $annualized_flat_rate           = $this->isEmpty(round($json_response['annualised_flat_rate']))."%";
            $with_holding_rate              = $this->isEmpty(round($json_response['withholding_rate']))."%";
            $as_lead_id                     = $this->isEmpty($json_response['lead_id']);
            $as_stage                       = $this->isEmpty($json_response['current_stage']);
            $as_sub_stage                   = $this->isEmpty($json_response['current_sub_stage']);
            $loan_status                    = $this->isEmpty($json_response['status']);
            $paid_percentage                 = $this->isEmpty($json_response['per_paid']);
            $current_balance                = $this->isEmpty(round($json_response['lbal']));   
            $funded_amount                  = round($json_response['funded_amount']);

            $paid_percentage                = round($paid_percentage)."%";

            $funded_date_strot              = strtotime($funded_date);
            $funded_date                    = date_create($funded_date);
            $funded_date                    = date_format($funded_date,"d-m-Y");


            if(empty($app_id) || $app_id == '-'){
                return;
            }
            if($current_balance == "-0"){
                $current_balance = "0";
            }

            if(!empty($current_balance)){
                if($current_balance<0){
                    $current_balance = "-".$this->IND_money_format(abs($current_balance));    
                }

                else{
                    $current_balance = $this->IND_money_format(abs($current_balance));

                }
            }
            if(!empty($funded_amount)){
                if($funded_amount<0){
                    $funded_amount = "-".$this->IND_money_format(abs($funded_amount));    
                }
                else{
                    $funded_amount = $this->IND_money_format(abs($funded_amount));
                }
            }
        }
        
        if(in_array($app_id, $this->crm_renewed_app_ids)){
            $is_renewal = 'Yes';
        }
        else{
            $is_renewal = 'No';   
        } 

        //AS Stores loan -> is_closed as 'Y' if load is closed, no mapping to check it its open.
        $status = "CLOSED";
        // var_dump($loan_status);
        if($loan_status === "-"){
            $status = "LIVE";
        }
        else if($loan_status === "Y"){
            $status = "CLOSED";
        }
        else{
            $status = "N/A";
        }

        echo "<tr>";
        // enable for testing latest lead - app disable flow  - start
        // if(empty($as_lead_id) || $as_lead_id = '-'){
        //     $as_lead_id = 1;
        // }
        // if(empty($funded_date) || $funded_date = '-'){
        //     $funded_date_strot = strtotime(" +" . rand(1, 10) . " days");
        //     $funded_date = date('Y-m-d', $funded_date_strot);
        // }
        // - end
        echo "<td><a href='index.php?module=Cases&action=customer_application_profile&applicationID=$app_id&details=Get+Details'>$app_id</a></td>";

        echo "<td><label class = 'as_lead_id'>$as_lead_id</label></td>";

        echo "
            <td >
                <label class = 'funded_date'>$funded_date</label>
                <input class='hidden_funded_date' type='hidden' value=$funded_date_strot>
            </td>
        ";

        echo "<td>$as_stage</td>";

        echo "<td>$as_sub_stage</td>";

        echo "<td>$is_renewal</td>";
        
        echo "<td>$funded_amount</td>";

        echo "<td>$current_balance</td>";

        echo "<td>$paid_percentage</td>";

        echo "<td>$repayment_frequency</td>";

        echo "<td>$annualized_flat_rate</td>";

        echo "<td>$term_days</td>";

        echo "<td>$with_holding_rate</td>";
        
        echo "<td>$status</td>";
        
        echo "<td>$performance</td>";

        echo "<td>$repayment_mode</td>";
        
        echo "<td>$product_nt</td>";

        echo "<td>$terminal_exclusivity_status</td>";
        
        $upfront_deduction = " ";
        if(in_array($app_id, $deduction_list, true)){
            $upfront_deduction = "checked";
        } 
        //diable check box if status is not closed, not empty, app id is not latest for its lead id(funded date)
        $this->isLatestAppForThisLead($as_lead_id, $funded_date_strot);
        $disable = " ";
        if(empty($status) || $status=="N/A" || $status=="CLOSED"){
            $disable = "disabled";
        }

        echo "<td><input class = 'upfront_deduction' type='checkbox' name='upfront_deduction' value=$app_id ".$disable." ".$upfront_deduction."></td>";

        echo "</tr>";
    }
    /**
     * Purpose of this function is to check wether for given lead id, given funded date is latest. Compare by storing latest * date globally 
     */
    function isLatestAppForThisLead($as_lead_id, $funded_date){
        // echo "lead id :: $as_lead_id <br>";
        // echo "funded_date:: $funded_date <br>";
        // echo "funded_date_frmt:: ".date('Y-m-d', $funded_date)." <br>";
        if(empty($as_lead_id) || $as_lead_id == '-' || empty($funded_date) || $funded_date == '-'){
            return;
        }
        $last_app_funded = "";
        if(!empty($this->lead_latest_funded_date[$as_lead_id])){
            $last_app_funded = $this->lead_latest_funded_date[$as_lead_id];
            // echo "latest funded_date::  ".date('Y-m-d', $funded_date).", stored_date :: ".date('Y-m-d', $last_app_funded)."<br>";
            if(($funded_date > $last_app_funded)){
                // echo "updated coz latest funded_date::  $funded_date <br>";
                $this->lead_latest_funded_date[$as_lead_id] = $funded_date;
            }    
            else{
                // echo "update failed not latest funded_date::  $funded_date, stored_date ::$last_app_funded <br>";
            }
        }
        else{
            // echo "updated coz first::  $funded_date <br>";
            $this->lead_latest_funded_date[$as_lead_id] = $funded_date;
        }
        // print_r($this->lead_latest_funded_date);echo "<br><br>";
        return;
    }

    function displayCases($items,$app_id){
            foreach($items as $key=>$item){
                $key +=1;

                echo "<tr style='border-bottom:1px solid #dddddd; align:left;'>";
                
                echo "<td style='background-color:#f6f6f6;' valign='top' type='int' field='case_number' class='footable-visible footable-first-column'>$item->case_number</td>";

                echo "<td style='background-color:#f6f6f6;' valign='top' type='int' field='app_id' class='footable-visible footable-first-column'><a href='index.php?module=Cases&action=customer_application_profile&applicationID=$app_id&details=Get+Details'>$app_id</a></td>";

                echo "<td style='background-color:#f6f6f6;' valign='top' type='name' field='name' class='footable-visible footable-first-column' style='white-space: normal;'><a href='index.php?module=Cases&return_module=Cases&action=DetailView&record=$item->id'><b>$item->name</b></a></td>";

                echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='type' class='footable-visible footable-first-column'>$item->type</td>";

                echo "<td style='background-color:#f6f6f6;' valign='top' type='varchar' field='case_category_c' class='footable-visible footable-first-column'>$item->case_category_c</td>";

                echo "<td style='background-color:#f6f6f6;' valign='top' type='date' field='date_entered' class='footable-visible footable-first-column'>$item->date_entered</td>";
                $resolved_tat = "-";
                if(!empty($item->date_resolved_c)){
                    $resolved_tat = date_diff(date_create($item->date_entered), date_create($item->date_resolved_c));
                    $resolved_tat = $resolved_tat->format("%R%a days");
                }
                echo "<td style='background-color:#f6f6f6;' valign='top' type='date' field='resolved_tat' class='footable-visible footable-first-column'>".$resolved_tat."</td>";

                echo "</tr>";
            }       
    }

}

?>