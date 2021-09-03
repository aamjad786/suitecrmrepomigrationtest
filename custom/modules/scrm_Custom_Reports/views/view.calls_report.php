<?php

if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');
class scrm_Custom_ReportsViewcalls_report extends SugarView {
	
	private $chartV;

    function __construct(){    
        parent::SugarView();
    }
    
	function getMatrixData($post)
	{
		
		global $db;	
		
		global $sugar_config;
	
		
			//From Date & To Date filter Condition
		$from_date = $_REQUEST['from_date'];
		if(!empty($from_date))
		{
			$tmp = explode("/",$from_date);
			if( count($tmp) == 3)
			{
				$from_date = $tmp[2].'-'.$tmp[1].'-'.$tmp[0];
			} else 
			$from_date = '';
		}
		$to_date = $_REQUEST['to_date'];
		if(!empty($to_date))
		{
			$tmp = explode("/",$to_date);
			if( count($tmp) == 3)
			{
				$to_date = $tmp[2].'-'.$tmp[1].'-'.($tmp[0]);
				$to_date = date('Y-m-d', strtotime($to_date. ' + 1 days'));
				//$to_date = $tmp[2].'-'.$tmp[1].'-'.($tmp[0]+1);
			} else 
			$to_date = '';
		}
             
			//$gen_query = "select count(*),delivery_status from sms_sms where date(smsreceivedon) = '2016-08-08' and description like '%Get Finance to boost your growth! %' group by delivery_status";
		//$query2 = "select distinct description,date(smsreceivedon) as date,date(date_entered) as de from sms_sms where $from_date $to_date and delivery_status='delivered' ";
        $from_date = "DATE(scheduled_time) >= '$from_date' ";
		$to_date = " and DATE(scheduled_time) <= '$to_date' ";
		$query = "select * from sms_campaign where $from_date $to_date";
        
        $result = $db->query($query);
		$data = array();
		$r = 1;
		global $current_user;

		while($row = $db->fetchByAssoc($result))
		{
			
			$rowdate = $row['scheduled_time'];
			$description  = $row['description'];
			$data[$r]['description'] = $description;
			
			// $mobile = $row['name'];
			// $Mobile = substr($mobile,2);
			$msg_substr = substr($description,0, 30);
			//die($msg_substr);
			$gen_query = "select count(*) as count,delivery_status from sms_sms where date(smsreceivedon) = '$rowdate' and description like '%$msg_substr%' group by delivery_status";
			//echo "\n<br>";
			//die($gen_query);
			$absent_count = $row['absent_subscriber_count'];
			$success_count = $row['delivered_count'];
			$dnd_count =  $row['ndnc_count'];
			$unknown_count =  $row['undelivered_count'];
			$invalid_count =  $row['invalid_subscriber_count'];
			$expired_count =  $row['expired_count'];
			$failedlist_count =  $row['undelivered_count'];
			$blacklist_count =  $row['blacklist_count'];
			$inboxfulllist_count =  $row['inbox_full_count'];
			$submittedlist_count =  $row['submitted_to_nw_count'];
			$queued_count =  $row['queued_count'];
			$duplicate_count =  $row['duplicate_msg_count'];
			$missed_call_count = $row['missed_call_count'];
			$rowid = $row['id'];
			 
				
			  
			  if ($success_count!=0){
			  	if(!$missed_call_count || $missed_call_count == '-1'){
			  		$end_date = date('Y-m-d H:i:s', strtotime($rowdate . ' +1 day'));
			  		$missed_call_query = "SELECT count(n.id)  as message_response from net_missed_calls n join sms_sms s where concat('91',n.name) =s.name and n.deleted=0 and s.deleted=0  and n.receiving_number='9222272881' and s.description like '%$msg_substr%' 
						and date(n.date_entered) >= date('$rowdate') and date(n.date_entered) <= date('$end_date')";
			  
					$result_response = $db->query($missed_call_query);
					$row_response = $db->fetchByAssoc($result_response);
					$response_count = $row_response['message_response'];

					$update_query = "update sms_campaign set missed_call_count=$response_count where id='$rowid'";
				  	echo ($update_query);
				  	$response = $db->query($update_query);
			  	}else{
			  		$response_count = $missed_call_count;
			  	}
			  }else{
			  	$response_count=0;
			  }
			 
			
				 $data[$r]['name'] = $row['description']."-". $row['scheduled_time'];
				 $data[$r]['date'] =  $row['date_entered'];
				 $data[$r]['sent_count'] = $row['total_count'];
				 $data[$r]['Success_count'] = $success_count;
				 $data[$r]['unknown_count'] = $unknown_count;
				 $data[$r]['dnd_count'] = $dnd_count;
				 $data[$r]['invalid_count'] = $invalid_count;
				 $data[$r]['duplicate_message_count'] = $duplicate_count;
				 $data[$r]['expired_count'] = $expired_count;
				 $data[$r]['absentsubscriber_count'] = $absent_count;
				 $data[$r]['blacklist_count'] = $blacklist_count;
				 $data[$r]['failedlist_count'] = $failedlist_count;
				 $data[$r]['inboxfulllist_count'] = $inboxfulllist_count;
				 $data[$r]['submittedlist_count'] = $submittedlist_count;
				 $data[$r]['queued_count'] = $queued_count;
				 $data[$r]['response_count'] = $response_count;
				$data[$r]['smsreceivedon'] = $row['scheduled_time'];
			  //die(var_dump($data));
			 $r++;
		}
		 return $data;
	}
	
	
	function array_sort_by_column(&$arr
	, $col, $dir = SORT_ASC) {
            $sort_col = array();
            foreach ($arr as $key=> $row) {
                $sort_col[$key] = $row[$col];
            }

            array_multisort($sort_col, $dir, $arr);
     }
		
    function display()
	{
	
		global $db,$current_user;
		//print_r($_REQUEST);	
		$data='';
		$MData = $this->getMatrixData($_REQUEST);
		
		 if(!empty($MData))
			{
				foreach($MData as $d) 
				{
					//print_r($MData);
					$data .= '<tr height="25">';
					if(empty($_REQUEST['Export']))
					{
					//print_r($d);
					$data .= '<td align="center"><label>'.$d['name'].'</label></td>';
					$data .= '<td align="center"><label>'.$d['date'].'</label></td>';
					$data .= '<td align="center"><label>'.$d['sent_count'].'</label></td>';
					//$data .= '<td align="center"><label>'.$d['delivered_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['Success_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['unknown_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['dnd_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['invalid_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['duplicate_message_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['expired_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['absentsubscriber_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['blacklist_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['failedlist_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['inboxfulllist_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['submittedlist_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['queued_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['response_count'].'</lable></td>'; 									
					$data .= '<td align="center"><label>'.$d['smsreceivedon'].'</lable></td>'; 									
					$data .= '</tr>';
					}
					
					
				}
			
		}
		//print_r($_REQUEST);
		if($_REQUEST['Email'])
					{
						global $sugar_config;
						$email = $_REQUEST['email'];
						$Date = date('d/m/Y');
$emailObj = new Email(); 
$defaults = $emailObj->getSystemDefaultEmail(); 
$mail = new SugarPHPMailer(); 
$mail->setMailerForSystem(); 

						$body = <<<HTML_Data_header
		Below are the SMS sent on the date  $Date
		<div id="mainData">
			<div id="DataHeader">
			<table cellpadding="0" cellspacing="0"  border="1">
			<tr  height="25">
			<th  style="text-align:center"><label>Campaign Name</lable></th>
			<th  style="text-align:center"><label>Date Sent</lable></th>
			<th  style="text-align:center"><label>No.of SMS Sent</lable></th>
			<th  style="text-align:center"><label>No.of Success Delivered </lable></th>
			<th  style="text-align:center"><label>No.of Delivery Status Unknown </lable></th>
			<th  style="text-align:center"><label>No.of NDNC Rejected </lable></th>
			<th  style="text-align:center"><label>No.of Invalid Subscriber </lable></th>
			<th  style="text-align:center"><label>No.of Duplicate Message Dropped </lable></th>
			<th  style="text-align:center"><label>No.of Expired Messages </lable></th>
			<th  style="text-align:center"><label>No.of Absent Subscriber Messages </lable></th>
			<th  style="text-align:center"><label>No.of Black List Messages </lable></th>
			<th  style="text-align:center"><label>No.of Failed Messages </lable></th>
			<th  style="text-align:center"><label>No.of Message Inbox Full Messages </lable></th>
			<th  style="text-align:center"><label>No.of Submitted to Network Messages </lable></th>
			<th  style="text-align:center"><label>No.of Queued Messages </lable></th>
			<th  style="text-align:center"><label>Missed Call/Response Received </lable></th>
			<th  style="text-align:center"><label>SMS Received On </lable></th>
			</tr>
			$data
			</table>
			</div>
		</div>
HTML_Data_header;
$subject = 'SMS Report On the date'.$Date;
			//~ echo $from = $sugar_config['from_address'];
			$mail->AddAddress($email);
		$mail->IsHTML(true);
		$mail->Body = from_html($body);
		$mail->Subject = 'SMS Report On the date'.$Date;
		$mail->prepForOutbound();
		$mail->From = $defaults['email']; 
		$mail->FromName = $defaults['name']; 
		if(!$mail->Send()){ 
			$GLOBALS['log']->DEBUG("Could not send notification: ". $mail->ErrorInfo);
		}
	}
		echo '<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">';		
      
		$report_name = "Calls Report";
		echo $html =<<<HTML_Search
		<div id="mainBody">
			<div  id="imageLoading"></div
			<div id="TitleReport"> <h2>$report_name</h2></div>						
			<div id="SearchPanel">
			<form id="ReportRequest" name="ReportRequest" method="POST">
			<table cellpadding="0" cellspacing="10">
			<tr>
				<td><label>From Date: </label></td>
				<td> <input type="text" id="from_date" name="from_date" size="10" value='$_REQUEST[from_date]' /> 
					<img border="0" src="themes/SuiteR/images/jscalendar.gif" id="fromb" align="absmiddle" />
					<script type="text/javascript">
						Calendar.setup({inputField   : "from_date",
						ifFormat      :    "%d/%m/%Y", 
						button       : "fromb",
						align        : "right"});
					</script>
				 </td>
				 <td></td>
			     <td><label>To Date: </label></td>
			     <td> <input type="text" id="to_date" name="to_date" size="10" value='$_REQUEST[to_date]' /> 
					<img border="0" src="themes/SuiteR/images/jscalendar.gif" id="tob" align="absmiddle" />
					<script type="text/javascript">
						Calendar.setup({inputField   : "to_date",
						ifFormat      :    "%d/%m/%Y", 
						button       : "tob",
						align        : "right"});
					</script>
				   </td>
			       <td></td>
					<td><label>Email Address:</label></td>
			<td>
				 <td> <input type="text" id="email" name="email" size="30" value='$_REQUEST[email]' /> 				
				 </td>
			 </tr>
			</table>
			</br>
			<table cellspacing="10">
			<tr>
			<td><input type="submit" id="Run_Report" name="Run_Report" value="Run Report" />&nbsp;&nbsp;&nbsp;<input type="submit" id="clear" name="Clear" value="Clear">&nbsp;&nbsp;<input type="submit" id="Export" name="Export" value="Export" /> <!-- &nbsp;&nbsp;<input type="submit" id="Export_Chart" name="Export_Chart" value="Export Chart" /> -->&nbsp;&nbsp;&nbsp;<input type="submit" id="send_email" name="Email" value="Send Email" />
			</tr>
			</table>
			</form>
			</div>
		</div>
		
HTML_Search;
		echo $HTML_Data_header = <<<HTML_Data_header
		<div id="mainData">
			<div id="DataHeader">
			<table cellpadding="0" cellspacing="0" width="100%" id="table_data">
			<tr  height="25">
			<th  style="text-align:center"><label>Campaign Name</lable></th>
			<th  style="text-align:center"><label>Date Sent</lable></th>
			<th  style="text-align:center"><label>No.of SMS Sent</lable></th>
			<th  style="text-align:center"><label>No.of Success Delivered </lable></th>
			<th  style="text-align:center"><label>No.of Delivery Status Unknown  </lable></th>
			<th  style="text-align:center"><label>No.of NDNC Rejected </lable></th>
			<th  style="text-align:center"><label>No.of Invalid Subscriber </lable></th>
			<th  style="text-align:center"><label>No.of Duplicate Message Dropped </lable></th>
			<th  style="text-align:center"><label>No.of Expired Messages </lable></th>
			<th  style="text-align:center"><label>No.of Absent Subscriber Messages </lable></th>
			<th  style="text-align:center"><label>No.of Black List Messages </lable></th>
			<th  style="text-align:center"><label>No.of Failed Messages </lable></th>
			<th  style="text-align:center"><label>No.of Message Inbox Full Messages </lable></th>
			<th  style="text-align:center"><label>No.of Submitted to Network Messages </lable></th>
			<th  style="text-align:center"><label>No.of Queued Messages </lable></th>
			<th  style="text-align:center"><label>Missed Call/Response Received </lable></th>
			<th  style="text-align:center"><label>SMS Received On </lable></th>
			</tr>
			$data
			</table>
			</div>
		</div>
HTML_Data_header;

		echo $js=<<<JS
		<script>
		    
        $('#Export_Chart').click(function(){
              a = document.getElementById("group_by[]");
              if(a.options[a.selectedIndex].value!='')
                return true;
              else {
                alert("Please select Across option ");
                return false;
              }
        });
        
        
        
       function showLoadingMesg()
        {
          $('#imageLoading').css('display','block');
          return true;
        }
        $('#Run_Report').attr('onclick','showLoadingMesg()');
		$('#send_email').click(function(){
		 email = document.getElementById("email").value;
		 if(email =='')
		 {
			alert("Please provide Email Address");
			 return false;
		 }
		
		});
		
		$('#clear').click(function(){
			$('#from_date').val('');
			$('#to_date').val('');
			$('select option').removeAttr("selected");
			return false;
		});
</script>		
JS;

		
		if(!empty($_REQUEST['Export']))
		{
			$timestamp = date('Y_m_d_His'); 
			ob_end_clean();
			ob_start();	
			// output headers so that the file is downloaded rather than displayed
			header('Content-Type: text/csv; charset=utf-8');
			header("Content-Disposition: attachment; filename=SMS_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			fputcsv($output, array('Campaign Name ','Date Sent','No.of SMS Sent','No.of Success Delivered','No.of Delivery Status Unknown ','No.of NDNC Rejected','No.of Invalid Subscriber','No.of Duplicate Message Dropped','No.of Expired Messages','No.of Absent Subscriber Messages','No.of Black List Messages','No.of Failed Messages','No.of Message Inbox Full Messages','No.of Submitted to Network Messages','No.of Queued Messages','Missed Call/Response received','SMS Received on'));
			foreach ($MData as $v)
			{
				unset($v['date_entered']);
				unset($v['description']);
				unset($v['msg_type']);
				fputcsv($output,$v);
			}
			exit;
			
		}
	} //end of display
} //end of class
	
