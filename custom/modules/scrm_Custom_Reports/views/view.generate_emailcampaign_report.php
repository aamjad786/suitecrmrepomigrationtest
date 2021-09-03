<?php

if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');

require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
	include_once('modules/Administration/Administration.php');
class scrm_Custom_ReportsViewgenerate_emailcampaign_report extends SugarView {
	
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
        $from_date = "and DATE(date_entered) >= '$from_date' ";
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
             
			$to_date = " and DATE(date_entered) <= '$to_date' ";
			 $query = "SELECT id as  campaign_id from campaigns  where deleted=0  $from_date $to_date group by date_entered";
			 #$query = "SELECT c.id as campaign_id,e.id as marketing_id from campaigns c JOIN email_marketing e ON e.campaign_id = c.id and c.deleted=0 and e.deleted=0 $from_date $to_date group by c.date_entered";
			$result = $db->query($query);
			$data = array();
			$r = 1;
			global $current_user;
			while($row = $db->fetchByAssoc($result))
			{
					$campaign_id = $row['campaign_id'];
					 $query_campaign = "SELECT name from campaigns where id='$campaign_id' and deleted=0";
					$result_campaign = $db->query($query_campaign);
					$row_campaign = $db->fetchByAssoc($result_campaign);
					$campaign_name = $row_campaign['name'];
					
					//~ $query_prospect_list_id = "SELECT prospect_list_id from prospect_list_campaigns where campaign_id='$campaign_id' and deleted=0";
					//~ $result_prospect_list = $db->query($query_prospect_list_id);
					//~ $row_prospects_list_info = $db->fetchByAssoc($result_prospect_list);
					//~ $prospect_list_id = $row_prospects_list_info['prospect_list_id'];
					//~ $query_prospect = "SELECT count(related_id) as target_count from prospect_lists_prospects where prospect_list_id='$prospect_list_id' and deleted=0 and related_type='Prospects'";
					//~ $result_prospect = $db->query($query_prospect);
					//~ $row_prospect = $db->fetchByAssoc($result_prospect);
					//~ $prospect_count = $row_prospect['target_count'];
					//~ $data[$r]['prospect_count'] = $prospect_count;
					
					$query_target_count="SELECT activity_type, target_type, COUNT( * ) hits FROM campaign_log WHERE campaign_id =  '$campaign_id' AND activity_type =  'targeted' AND archived =0 AND deleted =0
					GROUP BY activity_type, target_type
					ORDER BY activity_type, target_type";
					$result_target_count = $db->query($query_target_count);
					$row_target_count = $db->fetchByAssoc($result_target_count);
					$target_count = $row_target_count['hits'];
					
					
					
					$query_bounced_count="SELECT activity_type, target_type, COUNT( * ) hits FROM campaign_log WHERE campaign_id =  '$campaign_id' AND activity_type =  'send error' AND archived =0 AND deleted =0
					GROUP BY activity_type, target_type
					ORDER BY activity_type, target_type";
					$result_bounced_count = $db->query($query_bounced_count);
					$row_bounced_count = $db->fetchByAssoc($result_bounced_count);
					$bounced_count = $row_bounced_count['hits'];
					
					
					$query_viewed_count="SELECT activity_type, target_type, COUNT( * ) hits FROM campaign_log WHERE campaign_id =  '$campaign_id' AND activity_type =  'viewed' AND archived =0 AND deleted =0
					GROUP BY activity_type, target_type
					ORDER BY activity_type, target_type";
					$result_viewed_count = $db->query($query_viewed_count);
					$row_viewed_count = $db->fetchByAssoc($result_viewed_count);
					$viewed_count = $row_viewed_count['hits'];
					
					
					$query_link_count="SELECT activity_type, target_type, COUNT( * ) hits FROM campaign_log WHERE campaign_id =  '$campaign_id' AND activity_type =  'link' AND archived =0 AND deleted =0
					GROUP BY activity_type, target_type
					ORDER BY activity_type, target_type";
					$result_link_count = $db->query($query_link_count);
					$row_link_count = $db->fetchByAssoc($result_link_count);
					$link_count = $row_link_count['hits'];
					
					
					$query_blocked_count="SELECT activity_type, target_type, COUNT( * ) hits FROM campaign_log WHERE campaign_id =  '$campaign_id' AND activity_type =  'blocked' AND archived =0 AND deleted =0
					GROUP BY activity_type, target_type
					ORDER BY activity_type, target_type";
					$result_blocked_count = $db->query($query_blocked_count);
					$row_blocked_count = $db->fetchByAssoc($result_blocked_count);
					$blocked_count = $row_blocked_count['hits'];
					
					
					$query_invalid_count="SELECT activity_type, target_type, COUNT( * ) hits FROM campaign_log WHERE campaign_id =  '$campaign_id' AND activity_type =  'invalid email' AND archived =0 AND deleted =0
					GROUP BY activity_type, target_type
					ORDER BY activity_type, target_type";
					$result_invalid_count = $db->query($query_invalid_count);
					$row_invalid_count = $db->fetchByAssoc($result_invalid_count);
					$invalid_count = $row_invalid_count['hits'];
					
					
					
					
					
					$query_removed_count="SELECT activity_type, target_type, COUNT( * ) hits FROM campaign_log WHERE campaign_id =  '$campaign_id' AND activity_type =  'removed' AND archived =0 AND deleted =0
					GROUP BY activity_type, target_type
					ORDER BY activity_type, target_type";
					$result_removed_count = $db->query($query_removed_count);
					$row_removed_count = $db->fetchByAssoc($result_removed_count);
					$removed_count = $row_removed_count['hits'];
					
					$query_applied_loan = "select n.id  FROM neo_web_signup n JOIN neo_web_signup_cstm nc ON nc.id_c = n.id JOIN campaign_log C JOIN prospects T ON T.id=C.target_id JOIN email_addresses E_table JOIN email_addr_bean_rel EB_table ON EB_table.email_address_id=E_table.id where C.campaign_id='$campaign_id' and C.deleted=0 and T.deleted=0  and C.activity_type='link' and EB_table.bean_id=T.id AND EB_table.bean_module='Prospects' and nc.utm_campaign_c='generic_email' and n.email = email group by nc.utm_campaign_c";
					$result_applied_loan = $db->query($query_applied_loan);
					$applied_for_loan = $db->getRowCount($result_applied_loan);
					//$applied_for_loan = $row_applied_loan['hits'];
					$email_delivered = $target_count-$invalid_count-$bounced_count-$blocked_count;
					$data[$r]['campaign_name']= $campaign_name;
					$data[$r]['target_count'] = $target_count;
					$data[$r]['email_delivered'] = $email_delivered;
					$data[$r]['bounced_count'] = $bounced_count;
					$data[$r]['viewed_count'] = $viewed_count;
					$data[$r]['link_count'] = $link_count;
					$data[$r]['applied_for_loan']=$applied_for_loan;
					$data[$r]['removed_count'] = $removed_count;
					$data[$r]['marked_spam']='';
					
					$r++;
					
			}
			return $data;
	}
	
    function display(){
		global $db,$current_user;
		//print_r($_REQUEST);	
		$data='';
		$MData = $this->getMatrixData($_REQUEST);
		//print_r($MData);
				 if(!empty($MData))
			{
				foreach($MData as $d) 
				{
					//print_r($MData);
					$data .= '<tr height="25">';
					if(empty($_REQUEST['Export']))
					{
					//print_r($d['sent_count']);
					$data .= '<td align="center"><label>'.$d['campaign_name'].'</label></td>';
					$data .= '<td align="center"><label>'.$d['target_count'].'</label></td>';
					$data .= '<td align="center"><label>'.$d['email_delivered'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['bounced_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['viewed_count'].'</label></td>'; 
					$data .= '<td align="center"><label>'.$d['link_count'].'</label></td>';   									
					$data .= '<td align="center"><label>'.$d['applied_for_loan'].'</label></td>';   							
					$data .= '<td align="center"><label>'.$d['removed_count'].'</label></td>';   									
					$data .= '<td align="center"><label>'.$d['marked_spam'].'</label></td>';   									
					$data .= '</tr>';
					}
					
					
				}
			
		}
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
						Below are the Campaigns sent on the date  $Date
		<div id="mainData">
			<div id="DataHeader">
			<table cellpadding="0" cellspacing="0" width="100%" id="table_data">
			<tr  height="25">
			<th  style="text-align:center"><label>Campaign Name</lable></th>
			<th  style="text-align:center"><label>No.of E-mails  Sent</lable></th>
			<th  style="text-align:center"><label>No.of Email Delivered</lable></th>
			<th  style="text-align:center"><label>No.of Emails Bounced </lable></th>
			<th  style="text-align:center"><label>Unique Opens</lable></th>
			<th  style="text-align:center"><label>Unique Clicks</lable></th>
			<th  style="text-align:center"><label>Applied for Loan</lable></th>
			<th  style="text-align:center"><label>Unsubscribed</lable></th>
			<th  style="text-align:center"><label>Marked as Spam</lable></th>
			</tr>
			$data
			</table>
			</div>
		</div>
HTML_Data_header;

$subject = 'Email Campaign Report On the date'.$Date;
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
      
		$report_name = "Email Campaign Report";
		echo $html =<<<HTML_Search
		<div id="mainBody">
			<div  id="imageLoading"></div>
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
			<th  style="text-align:center"><label>No.of E-mails  Sent</lable></th>
			<th  style="text-align:center"><label>No.of Email Delivered</lable></th>
			<th  style="text-align:center"><label>No.of Emails Bounced </lable></th>
			<th  style="text-align:center"><label>Unique Opens</lable></th>
			<th  style="text-align:center"><label>Unique Clicks</lable></th>
			<th  style="text-align:center"><label>Applied for Loan</lable></th>
			<th  style="text-align:center"><label>Unsubscribed</lable></th>
			<th  style="text-align:center"><label>Marked as Spam</lable></th>
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
			header("Content-Disposition: attachment; filename=Campaign_Report{$timestamp}.csv");

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');
			// output the column headings
			fputcsv($output, array('Campaign Name ','No.of Email Sent','No.of Email Delivered','No.of Emails Bounced','Unique Opens','Unique Clicks','Applied For Loan','UnSubscribed','Marked as Spam'));
			foreach ($MData as $v)
			{
				fputcsv($output,$v);
			}
			exit;
			
		}
		}
}
?>
