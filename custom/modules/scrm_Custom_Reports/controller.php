<?php
if(!defined('sugarEntry')) die('Not a Valid Entry Point');

//require_once('modules/bhea_Reports/report_utils.php');

class scrm_Custom_ReportsController extends SugarController {

    
    function action_SMSReport() {
        $this->view = 'generate_rt_eecbyleadsource_report';
    }
    function action_hierarchy() {
        $this->view = 'hierarchy';
    }
    function action_CampaignReport(){
        
            $this->view ='generate_emailcampaign_report';
    }
    function action_AttemptWiseMISReport(){
        
            $this->view ='generate_attemptwisemis_report';
    }
    function action_TimeSlotWiseReport(){
        
            $this->view ='generate_timeslotwise_report';  
    }
    function action_SubDispositionReport(){
        
            $this->view ='generate_subdisposition_report';
    }
    function action_camcitywiseReport(){
        
        $this->view ='generate_camcitywise_report';
    }   
    function action_CampaignWiseReport(){
        
        $this->view ='generate_campaignwise_report';    
           
    }
    function action_AgentLevelSnapshot(){
       
        $this->view ='generate_agentlevel_report';  
           
    }
    function action_ProjectLevelSnapshot(){
        
        $this->view = 'generate_projectlevel_report';
    }
    function action_DispositionReport(){
        
        $this->view = 'generate_disposition_report';
    }
    function action_LeadSourceReport(){
        
        $this->view = 'generate_lead_source_report';
    }

    function action_SupportMasterTracker(){
        
            $this->view = 'generate_support_master_tracker';
    }

    function action_SendSms(){
        $this->view = 'send_sms'; 
    }
    function action_AssignUser(){
        $this->view = 'assign_user';  
        $env = getenv('SCRM_ENVIRONMENT');
      if($env!='prod'){
        if( !ACLController::checkAccess('scrm_Custom_Reports', 'create') )  {
         ACLController::displayNoAccess(true);
        }
      }  
    }
    function action_CustomerApplicationProfile(){
        header('Location: ?module=Cases&action=customer_application_profile');
        die();
      global $current_user;
      $department = $current_user->department;
      if(in_array($department, array('COLLECTION','Customer Service','Customer Support','Customer Experience'))|| $current_user->is_admin){
        $this->view = 'customer_application_profile';   
      }else{
        echo "You do not have access. Contact CRM Team crmteam@neogrowth.in";
        die();
      }
    }

    function action_covid19()
    {
        header('Location: ?module=Cases&action=covid19');
        die();
    }
    function action_CustomerProfile(){
        
        $this->view = 'customer_application_profile';  
    }
    function action_RenewalCustomerProfile(){
        $this->view = 'renewal_customer_profile';  
    }
    
    function action_CloudAgent(){

        $this->view = 'cloud_agent';  
    
    }
    function action_RenewalCustomerReport() {
        
      $this->view = 'renewal_customer_report';
        
    }

    function action_DocumentRequestsReport() {
        
      $this->view = 'document_requests_report';
        
    }
    function action_PL2Report() {
        
        $this->view_object_map['report_type'] = 'PL2';
        $this->view_object_map['report_name'] = "Campaign wise Conversions & TAT Summary";
        $this->view = 'campaign_wise_summary_report';

    }
    function action_PL4Report() {
        
        $this->view_object_map['report_type'] = 'PL4';
        $this->view_object_map['report_name'] = "TAT Summary for Conversions";
        $this->view = 'campaign_wise_summary_report';

    }
    function action_PL1Report() {
       
        $this->view_object_map['report_type'] = 'PL4';
        $this->view_object_map['report_name'] = "TAT Summary for Conversions";
        $this->view = 'partner_wise_summary_report';
    }
    function action_PL3Report() {
    
        $this->view_object_map['report_type'] = 'PL3';
        $this->view_object_map['report_name'] = "Partner wise Month on Month leads & Conversions Summary ";
        $this->view = 'partner_wise_summary_report2';
    }
     
       function action_file_proccessed_status(){
        if( !ACLController::checkAccess('scrm_Custom_Reports', 'create') )  {
            ACLController::displayNoAccess(true);
           }
        // die("hello test");
        global $sugar_config;
        $databasehost = $sugar_config['dbconfig']['db_host_name'];
        $databasename = 'vaaradhi';//$sugar_config['dbvaradhiconfig']['db_name'];
        $databasetable = "file_process_step_tracker";
        $databaseusername = $sugar_config['dbconfig']['db_user_name'];
        $databasepassword = $sugar_config['dbconfig']['db_password'];

        mysql_connect($databasehost, $databaseusername, $databasepassword);
        mysql_select_db($databasename) or die(mysql_error());
        if(isset($_POST["submit"])) {
            // echo "hello";
            $this->view_object_map['tableOutput'] = "";
            $file_id = $_POST['filen'];
        if ($file_id == ""){
            die('You must choose a file name');
            //continue;
        }
            try{
                // global $sugar_config;
                


                $show_result = "select f.id_file,f.file_name,f.file_location,f.file_no_rows,f.upload_date,f.uploaded_by,s.step_name,fs.step_status,fs.no_record_processed,fs.step_start_time, fs.step_end_time from file_process_tracker f  left JOIN  file_process_step_tracker fs on f.id_file=fs.fk_file   inner join  step_detail_master s  on s.id_step=fs.fk_step where f.id_file=".$file_id." order by fs.fk_step*1";
                $qry_result = mysql_query($show_result) or die(mysql_error());
            }catch(Exception $e){
                die($e->message);
            }

            $html_result="<table id=\"table\" border=\"1\" cellspacing=0 cellpadding=0>";
            $header_style="style=\"background-color:black; padding: 10px;color:white;\"";
            $td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";
            $i=0;



            //Build Result String
           $this->view_object_map['tableOutput'] .= "<table id=\"table\" border=\"1\" cellspacing=0 cellpadding=0>";
           $this->view_object_map['tableOutput'] .= "<tr>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">file name</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">file id</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">total no. of rows</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">upload date</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">uploaded by</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step name</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step status</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">records processed</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step start time</td>";
           $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step end time</td>";
           $this->view_object_map['tableOutput'] .= "</tr>";
           
           // Insert a new row in the table for each person returned
           while($row = mysql_fetch_array($qry_result)) {
              $this->view_object_map['tableOutput'] .= "<tr>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[file_name]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[id_file]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[file_no_rows]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[upload_date]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[uploaded_by]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_name]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_status]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[no_record_processed]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_start_time]</td>";
              $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_end_time]</td>";
              $this->view_object_map['tableOutput'] .= "</tr>";
           }
       // echo "Query: " . $query . "<br />";
            $this->view_object_map['tableOutput'] .= "</table>";
            // echo $this->view_object_map['tableOutput'];
            // die($_POST['filen']);
         }
         {
            
            $statement = ("select id_file,file_name from file_process_tracker order by upload_date desc");
            //echo $statement;die();
            $qry_result = mysql_query($statement) or die(mysql_error());
//          $select_st = "<select id='filen' name='filen'>";
            $select_st = "<select id='filen' name='filen'><option value=''>-Choose a file from below:-</option>";
             while($row = mysql_fetch_assoc($qry_result)) {
                // echo $row['file_name']."\n";
                // var_dump($row);
                $select_st .= "<option value='".$row['id_file']."' >".$row['file_name']."</option>";
                //break;
             }
             $select_st .= "</select>";
             $this->view_object_map['select_st'] = $select_st;
        }
         
        // die($select_st);
        $this->view = 'file_processed_status';
    }


    function action_fileUpload() {
        if( !ACLController::checkAccess('scrm_Custom_Reports', 'create') )  {
        ACLController::displayNoAccess(true);
       }
        try{
            global $sugar_config;
        $this->view_object_map['tableOutput'] = "";
            $databasehost = $sugar_config['dbconfig']['db_host_name'];
        $databasename = 'vaaradhi';//$sugar_config['dbvaradhiconfig']['db_name'];
        $databasetable = "file_process_step_tracker";
        $databaseusername = $sugar_config['dbconfig']['db_user_name'];
        $databasepassword = $sugar_config['dbconfig']['db_password'];

        mysql_connect($databasehost, $databaseusername, $databasepassword);
        mysql_select_db($databasename) or die(mysql_error());

        $show_result = "select f.file_name,f.file_location,f.file_no_rows,f.upload_date,f.uploaded_by,s.step_name,fs.step_status,fs.no_record_processed,fs.step_start_time, fs.step_end_time from file_process_tracker f  left JOIN  file_process_step_tracker fs on f.id_file=fs.fk_file   inner join  step_detail_master s  on s.id_step=fs.fk_step order by f.upload_date DESC limit 10";
        $qry_result = mysql_query($show_result) or die(mysql_error());
    }catch(Exception $e){
        die($e->message);
    }

    $html_result="<table id=\"table\" border=\"1\" cellspacing=0 cellpadding=0>";
    $header_style="style=\"background-color:black; padding: 10px;color:white;\"";
    $td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";
    $i=0;



        //Build Result String
       $this->view_object_map['tableOutput'] .= "<table id=\"table\" border=\"1\" cellspacing=0 cellpadding=0>";
       $this->view_object_map['tableOutput'] .= "<tr>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">file name</td>";
//     $this->view_object_map['tableOutput'] .= "<td ".$header_style.">file location</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">total no. of rows</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">upload date</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">uploaded by</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step name</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step status</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">records processed</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step start time</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step end time</td>";
       $this->view_object_map['tableOutput'] .= "</tr>";
       
       // Insert a new row in the table for each person returned
       while($row = mysql_fetch_array($qry_result)) {
          $this->view_object_map['tableOutput'] .= "<tr>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[file_name]</td>";
//        $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[file_location]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[file_no_rows]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[upload_date]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[uploaded_by]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_name]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_status]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[no_record_processed]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_start_time]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_end_time]</td>";
          $this->view_object_map['tableOutput'] .= "</tr>";
       }
       // echo "Query: " . $query . "<br />";
       $this->view_object_map['tableOutput'] .= "</table>";
       try{
             $this->view = 'file_process';
        }catch(Exception $e){
            die($e->getMessage());
        }
}


    function action_fileProcess() {
        if( !ACLController::checkAccess('scrm_Custom_Reports', 'create') )  {
            ACLController::displayNoAccess(true);
           }
        //echo "hello";
        require_once 'csvUpload/config.php';
        global $sugar_config;
    global $current_user;
        $uie_pdo = "";
        $varadhi_pdo = "";
        $this->view_object_map['myDataKey'] = "";
        
        require_once 'csvUpload/function.php';
        $target_dir = $sugar_config['upload_dir'] . "uie_unclaimed/";
        if (! is_dir($target_dir)) {
            mkdir($target_dir, 0770);
        }
        $filename = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $csvFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        if(isset($_POST["submit"])) {
            $file_mime = file_mime_type($_FILES["fileToUpload"]["tmp_name"]);
            if(substr($file_mime, 0, 11) == "text/plain;") {
                //echo "File is a csv - " . $file_mime . ".\n";
                $this->view_object_map['myDataKey'] .= "File is a csv - " . $file_mime . ".\n";
                $uploadOk = 1;
            } else {
                
                $this->view_object_map['myDataKey'] .="File is not a csv.\n";
                $uploadOk = 0;
                die( "File is not a csv.\n");
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
             echo "Sorry, file already exists.";
                $uploadOk = 0;
                exit;
        }
        if ($_FILES["fileToUpload"]["size"] > $sugar_config['upload_maxsize']) {
            
            $this->view_object_map['myDataKey'] .= "Sorry, your file is too large.";
            $uploadOk = 0;
            die( "Sorry, your file is too large.");
        }
        // Allow certain file formats
        if($csvFileType != "csv") {
            
            $this->view_object_map['myDataKey'] .= "Sorry, only csv files are allowed.";
            $uploadOk = 0;
            die( "Sorry, only csv files are allowed.");
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            die( "\nSorry, your file was not uploaded.");
            $this->view_object_map['myDataKey'] .= "\nSorry, your file was not uploaded.";
            exit;
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //echo "\nThe file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.\n";
                $this->view_object_map['myDataKey'] .= "\n<br/>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.\n";
            } else {
                die( "\nSorry, there was an error uploading your file.\n");
                $this->view_object_map['myDataKey'] .= "\nSorry, there was an error uploading your file.\n";
                exit;
            }
        }

        $last_id = "";
        $databasehost = $sugar_config['dbconfig']['db_host_name'];
        $databasename = 'vaaradhi';//$sugar_config['dbconfig']['db_name'];
        $databasetable = "file_process_tracker";
        $databaseusername = $sugar_config['dbconfig']['db_user_name'];
        $databasepassword = $sugar_config['dbconfig']['db_password'];
       try {
            $link = new PDO("mysql:host=$databasehost;dbname=$databasename", 
                $databaseusername, $databasepassword,
                array(
                    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
            $varadhi_pdo = $link;
        } catch (PDOException $e) {
            die("varadhi database connection failed: ".$e->getMessage());
        }

        try {
            $cur_date = date('Y-m-d H:i:s');
            $statement = $link->prepare("INSERT INTO $databasetable(file_location,file_name,upload_date,uploaded_by) 
                    VALUES(?,?,?,?)");
             $statement->execute(array($target_dir,$filename,$cur_date,$current_user->name));
             $last_id = $link->lastInsertId();
             // echo $last_id;
            // set the PDO error mode to exception

            // $this->view_object_map['myDataKey'] =  "Connected successfully"; 
        }catch(PDOException $e){
            die( "Query Execution failed: " . $e->getMessage());
            $this->view_object_map['myDataKey'] = $e->getMessage();
        }//die();





        $databasehost = $sugar_config['dbconfig']['db_host_name'];
        $databasename = $sugar_config['dbconfig']['db_name'];
        $databasetable = "uie_stage__uie_target_unclaimed";
        $databaseusername = $sugar_config['dbconfig']['db_user_name'];
        $databasepassword = $sugar_config['dbconfig']['db_password'];
        $fieldseparator = ","; 
        $lineseparator = "\n";
        $csvfile = $target_file;
        //echo "with $csvfile\n<br>";
        $this->view_object_map['myDataKey'] .= "with $csvfile\n<br>";

        if(!file_exists($csvfile)) {
            die("File not found. Make sure you specified the correct path.");
        }

        try {
            $pdo = new PDO("mysql:host=$databasehost;dbname=$databasename", 
                $databaseusername, $databasepassword,
                array(
                    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
            $uie_pdo = $pdo;
        } catch (PDOException $e) {
            die("database connection failed: ".$e->getMessage());
        }
        $cur_start_time = date('Y-m-d H:i:s');
        require_once 'csvUpload/CsvImporter.php';
        $csvImporter = new CsvImporter("$csvfile", true, $fieldseparator);
        $csvHeader = $csvImporter->getHeader();
        $query = "INSERT INTO $databasetable (";
        $queryParam = "";
        $first = true;
        foreach ($csvHeader as $k => $v) {
            if ($first == true) {
                $first = false;
                $query = $query . $v; 
                $queryParam = $queryParam . ":" . $v; 
            }
            else {
                $query = $query . ", " . $v; 
                $queryParam = $queryParam . ", :" . $v; 
            }
        }
        $query = $query . ", " . "fk_file";
        $queryParam = $queryParam . ", :" . "fk_file"; 
        $query = $query . ") VALUES (" . $queryParam . ")";//die($query);
        //echo "$query <br>";
        $count = 0;
        $batch = 100;
        $date_fields = array(
                'date_entered' => 1,
                'date_modified' => 1,
                'fetch_date' => 1,
                'created_at' => 1,
                'updated_at' => 1,
                'appointment_time' => 1 
                );
                // UIE-79 appointment_time = 0 bug; added appointment_time above
        $ins_err_ids = array();
        $ins_succ = 0;
        $ins_dupl = 0;
        $total = 0;
        //$stmt = $pdo->prepare($query);
        //ob_start();
        //header( 'Content-type: text/html; charset=utf-8' );
        require_once 'csvUpload/progress.php';
        while ($data = $csvImporter->get($batch)) {
            $ins_errs = count($ins_err_ids);
            // outputMessageProgress("\t\t\tReading $batch lines after line no. $count . Stats ($ins_dupl/$ins_errs/$ins_succ/$total)");
            $count += $batch;
            //flush();
            //ob_flush();

            for ($i = 0; $i < $batch && $i < count($data); $i++) {
                ++$total;
                $debA = array();
                foreach ($csvHeader as $k => $v) {
                    $bindK = ":$v";
                    $bindV = $data[$i][$v];
                    //print "$bindK => $bindV<br>\n";
                    if (is_null($bindV) || strtolower($bindV) == "null") {
                        $bindV = null;
                        //$stmt->bindParam($bindK, $bindV, PDO::PARAM_NULL);
                    }
                    else {
                        if (array_key_exists(substr($bindK,1), $date_fields)) {
                            
                            $bindV = date("Y-m-d H:i:s", strtotime($bindV));
                            // echo $bindK;
                            // if ($bindK == 'date_entered') {
                            //  $cur_insert_date = $bindV;
                            // }
                            
                            //$stmt->bindParam($bindK, $bindV, PDO::PARAM_STR);
                        }
                        else {
                            $bindV = str_replace("'", " ", $bindV);
                            //$stmt->bindParam($bindK, $bindV);
                        }
                    }

                    $debA[$bindK] = $bindV;
                }
                $debA[':fk_file'] = intval($last_id);
                // if (array_key_exists(':date_entered', $debA)) {
                //  $cur_insert_date = $bindV;
                // }

                // Hash to keep unique records
                if (array_key_exists(':hash', $debA) 
                            && (!is_null($debA[':hash']))
                                && $debA[':hash'] != "") {
                        //$debA[':hash'] = $hash;
                }
                else {
                    if (array_key_exists(':source_url', $debA)
                            && (!is_null($debA[':source_url']))
                                && $debA[':source_url'] != "") {
                            $hash = hash("sha256", $debA[':source_url'] . "\n");
                            $debA[':hash'] = $hash;
                    }
                    else {
                            $hash = hash("sha256", $debA[':id']);
                            $debA[':hash'] = $hash;
                    }
                }

                if (array_key_exists(':created_by', $debA) 
                            && (is_null($debA[':created_by']))
                                || $debA[':created_by'] == "") {
                        $debA["created_by"] = $current_user->name;
                }

                $debug = sql_debug($query, $debA);
                //var_dump($debug);
                //bind_array_value($stmt, $debA);
                //$stmt->execute();
                try {
                    $pdo->exec($debug);
                    ++$ins_succ;
                    $id = $debA[':id'];
                    echo "inserted id = ".$id;
                } catch (PDOException $e) {
                    $id = $debA[':id'];
                    $ins_err_ids[$id] = $e->getMessage();
                    if (preg_match("/.*duplicate.*key.*primary.*/i", 
                                $e->getMessage())) {
                        $ins_dupl++;
                    }
                    else {
                        $this->view_object_map['myDataKey'] .= "Insert Error: $id" . " " . $e->getMessage() . "<br>\n";
                    }
                }
            }
        }
        $fail_count = count($ins_err_ids);
        $this->view_object_map['myDataKey'] .=  "<br><h3>Failed $fail_count/$total records from this csv file.</h3><br>\n";
        $this->view_object_map['myDataKey'] .= "<h3>Duplicate $ins_dupl/$total records found in this csv file.</h3><br>\n";
        $this->view_object_map['myDataKey'] .=  "<br><h3>Loaded a total of $ins_succ/$total records from this csv file.</h3><br>\n";
        // echo $cur_insert_date;
        //var_dump($varadhi_pdo);//die();
        $cur_end_time = date('Y-m-d H:i:s');

        try {
            $cur_date = date('Y-m-d H:i:s');
            $statement = $varadhi_pdo->prepare("update file_process_tracker set file_no_rows = :file_no_rows where id_file = :id");
                    // VALUES(?,?,?,?)");
            $statement->bindValue(":file_no_rows", $total);
            $statement->bindValue(":id", $last_id);
            $count = $statement->execute();

            // $this->view_object_map['myDataKey'] =  "Connected successfully"; 
        }catch(PDOException $e){
            echo "Query Execution failed: " . $e->getMessage();
            $this->view_object_map['myDataKey'] = $e->getMessage();
        }//die();


        $databasehost = $sugar_config['dbconfig']['db_host_name'];
        $databasename = 'vaaradhi';//$sugar_config['dbconfig']['db_name'];
        $databasetable = "file_process_step_tracker";
        $databaseusername = $sugar_config['dbconfig']['db_user_name'];
        $databasepassword = $sugar_config['dbconfig']['db_password'];

        mysql_connect($databasehost, $databaseusername, $databasepassword);
        mysql_select_db($databasename) or die(mysql_error());
        $statement = ("insert into file_process_step_tracker (fk_step, fk_file,step_status, step_start_time, step_end_time, no_record_processed) values 
            ('56', $last_id, 'done', '$cur_start_time', '$cur_end_time',  $ins_succ)");
        //echo $statement;die();
        $qry_result = mysql_query($statement) or die(mysql_error());
        $show_result = "select f.file_name,f.file_location,f.file_no_rows,f.upload_date,f.uploaded_by,s.step_name,fs.step_status,fs.no_record_processed,fs.step_start_time, fs.step_end_time from file_process_tracker f  left JOIN  file_process_step_tracker fs on f.id_file=fs.fk_file   inner join  step_detail_master s  on s.id_step=fs.fk_step order by f.upload_date DESC limit 10";
        $qry_result = mysql_query($show_result) or die(mysql_error());
        // die($qry_result);
        $html_result="<table id=\"table_".$query_id."\" border=\"1\" cellspacing=0 cellpadding=0>";
        $header_style="style=\"background-color:black; padding: 10px;color:white;\"";
        $td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";
        $i=0;



        //Build Result String
       $this->view_object_map['tableOutput'] .= "<table id=\"table\" border=\"1\" cellspacing=0 cellpadding=0>";
       $this->view_object_map['tableOutput'] .= "<tr>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">file name</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">file location</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">total no. of rows</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">upload date</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">uploaded by</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step name</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step status</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">records processed</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step start time</td>";
       $this->view_object_map['tableOutput'] .= "<td ".$header_style.">step end time</td>";
       $this->view_object_map['tableOutput'] .= "</tr>";
       
       // Insert a new row in the table for each person returned
       while($row = mysql_fetch_array($qry_result)) {
          $this->view_object_map['tableOutput'] .= "<tr>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[file_name]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[file_location]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[file_no_rows]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[upload_date]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[uploaded_by]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_name]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_status]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[no_record_processed]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_start_time]</td>";
          $this->view_object_map['tableOutput'] .= "<td ".$td_style.">$row[step_end_time]</td>";
          $this->view_object_map['tableOutput'] .= "</tr>";
       }
       // echo "Query: " . $query . "<br />";
       $this->view_object_map['tableOutput'] .= "</table>";
       try{
             $this->view = 'file_process';
        }catch(Exception $e){
            die($e->getMessage());
        }
//die();
        // $this->view = 'file_process';
    }
}

?>
