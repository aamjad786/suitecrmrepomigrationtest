<?php
//use function GuzzleHttp\json_encode;

//add the job key to the list of job strings

        $myfile = fopen("Logs/push_lead_eos.log", "a");
        $bean = BeanFactory::getBean('Opportunities');
        fwrite($myfile,"\nStarting lead push\n".date('Y-M-d H:i:s')."\n");
        $DayDate = date('Y-m-d', strtotime('-3 day'));
        global $db;
        $lead_list= $bean->get_full_list("", "opportunities.id IN ('42e9222e-ae36-36de-1777-6178e43fe12d','bcf4ebbb-70ab-05c8-1617-616fc0cd33b7','c0307b51-6c47-d788-ebdb-61600ac1484d')");

        //and (disposition_c is NULL or disposition_c='' or disposition_c=' ') 
        require_once('custom/include/CurlReq.php');
        fwrite($myfile, "\nTotal leads are ".count($lead_list));
        foreach($lead_list as $lead){
            
            $dsa_code = array('Nineroot Technologies Private Limited','NINEROOT TECHNOLOGIES PRIVATE LIMITED','C Connect Market India Pvt Ltd','C CONNECT MARKET INDIA PVT LTD');
            if(in_array($lead->dsa_code_c,$dsa_code)) {
                $lead->push_count = 100;
                $lead->save();
            }

            if(!in_array($lead->dsa_code_c,$dsa_code)) {
                if(($lead->lead_source != 'Marketing') && ($lead->lead_source!='Alliances')){
                    $lead->push_count = 100;
                    $lead->save();
                    fwrite($myfile,"\nSkipping as lead source is $lead->lead_source \n");
                    continue;
                }

                // if($lead->dsa_code_c=='Nomisma Mobile Solutions Pvt Ltd' || $lead->dsa_code_c=='Nineroot Technologies Private Limited'){
                    
                //  $lead->opportunity_status_c = 'appointment_done_will_get_documents_later';
                // }
                //Added new condition as Opp Status should not be Appointment Done Cam to visit Customer
                if((!empty($lead->opportunity_status_c)) && ($lead->opportunity_status_c !== "appointment_done_cam_to_visit_customer")){ 
                    fwrite($myfile,"\nSkipping as lead source is $lead->lead_source but Status is $lead->opportunity_status_c \n");
                    $lead->push_count = 100;    
                    $lead->save();
                    continue;
                }
                if(!empty($lead->application_id_c) || ($lead->application_id_c!="" && $lead->application_id_c!=" ")) {
                    fwrite($myfile,"\nSkipping as lead source is $lead->lead_source but Application id is $lead->application_id_c \n");
                    $lead->push_count = 100;    
                    $lead->save();
                    continue;
                }
                if(!empty($lead->sub_status) || ($lead->sub_status!="" && $lead->sub_status!=" ")) {
                    fwrite($myfile,"\nSkipping as lead source is $lead->lead_source but Sub-Status is $lead->sub_status \n");
                    $lead->push_count = 100;    
                    $lead->save();
                    continue;
                }
                if(!empty($lead->remarks) || ($lead->remarks!="" && $lead->remarks!=" ")) {
                    fwrite($myfile,"\nSkipping as lead source is $lead->lead_source but remarks is $lead->remarks \n");
                    $lead->push_count = 100;    
                    $lead->save();
                    continue;
                }
                if($lead->sales_stage!="Open" && !empty($lead->sales_stage) && $lead->sales_stage!=" ") {
                    fwrite($myfile,"\nSkipping as lead source is $lead->lead_source but Sales Stage is $lead->sales_stage \n");
                    $lead->push_count = 100;    
                    $lead->save();
                    continue;
                }
                //Take only the Qualified Offline with Appointment done cam to visit customer
                if(($lead->qualified_offline_c == 'Yes') && ($lead->opportunity_status_c !== 'appointment_done_cam_to_visit_customer')) {
                    fwrite($myfile,"\nSkipping as lead opportunity status is $lead->opportunity_status_c but Qualified offline is yes  \n");
                    $lead->push_count = 100;    
                    $lead->save();
                    continue;
                }
                $id=$lead->id;
                $lead_bean=BeanFactory::getBean('Leads');
                $list=$lead_bean->get_full_list("","leads.opportunity_id='$id'");

                if(empty($list[0]->id)){
                    fwrite($myfile,"\nSkipping as lead not present for opportunity $id \n");
                    $lead->push_count = 100;    
                    $lead->save();
                    continue;
                }
                $arr= array();
                $arr['Lead_Source'] = $lead->lead_source;
                $arr['Sub_Source']=$list[0]->sub_source_c;
                $arr['DSA_code']=$lead->dsa_code_c;
                $arr['First_Name'] = $lead->name;
                $arr['Last_Name'] = "";
                $arr['Mobile_Number'] = $lead->pickup_appointment_contact_c;
                $arr['EmailID'] = $list[0]->email1;
                $arr['Business_Trading_Name'] = $lead->merchant_name_c;
                $arr['Lead_ID']=$lead->id;
                $arr['City']=$lead->pickup_appointment_city_c;
                $arr['product']=$lead->product_type;
                $arr['remarks']=$lead->remarks;
                $arr['Loan_amount']=$lead->loan_amount_c;
                $arr['stage_drop_off']=$lead->stage_drop_off;
                $arr['Address_Street']=$list[0]->primary_address_street;
                $arr['Address_pin']=$list[0]->primary_address_postalcode;
                if($lead->sales_stage=="Sanctioned")
                {
                    $arr['stage_drop_off']="Customer Deal Generated";
                }
                $arr['app_form_link']=$lead->app_form_link;
                $arr['product_type']=$lead->product_type;
                $arr['loan_amount_c']=$lead->loan_amount_c;
                if($lead->opportunity_status_c == 'appointment_done_cam_to_visit_customer') {
                    $arr['qualified_offline_c']= "Yes";
                }
                $json_body = json_encode($arr);
                $curl = new CurlReq();
                $headers  = null;
                fwrite($myfile,print_r($arr,true));
                $output = $curl->curl_req("http://125.16.125.52/Neogroth_API/api/leads","post",$json_body,$headers);
                if($lead->qualified_offline_c == 'Yes') {
                    $arr['qualified_offline_c']= "Yes";
                } 
                $lead->push_count=1;
                $lead->save();
                $message=json_decode($output);
                if(empty($message) || $message=="")
                {
                    $output = $curl->curl_req("http://114.143.182.243/Neogroth_API/api/leads","post",$json_body,$headers);
                    $lead->push_count=1;
                    $lead->save();
                    $message=json_decode($output);
                }
                if(preg_match("/Success/",$message->{'Message'}))
                {
                    $lead->pushed_lead=explode("_",$message->{'Message'})[1];
                    $time=date("Y-m-d H:i:s");
                    $time=strtotime($time)-(330*60);
                    $lead->date_sent_to_EOS=date("Y-m-d H:i:s", $time);
                    if($lead->qualified_offline_c == 'Yes') {
                        $lead->sent_count = 1;
                    }
                    $lead->save();
                }
                if($message->{'Message'}=="Mobile Number already Exist.")
                {
                    $lead->pushed_lead=-1;
                    $lead->save();
                }
                if($message->{'Message'}=="The field Mobile_Number must be a string or array type with a minimum length of '10'."){
                    $lead->pushed_lead=-2;
                    $lead->save();
                }
                fwrite($myfile,print_r($message,true));
            }
        }
        
        //return true for completed
            // return true;

    
?>