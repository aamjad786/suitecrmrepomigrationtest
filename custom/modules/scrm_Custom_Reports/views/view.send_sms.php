<?php
/*
Created By : Manasa Munnaluri
Purpose : Generate SMS Report.
*/
if(!defined('sugarEntry')) define('sugarEntry', true);
//ini_set('display_errors','On');
ini_set('memory_limit','-1');
require_once('include/SugarCharts/SugarChartFactory.php');
require_once('include/MVC/View/SugarView.php');
include_once('include/SugarPHPMailer.php');
include_once('modules/Administration/Administration.php');

class scrm_Custom_ReportsViewsend_sms extends SugarView {
	
	private $chartV;

    function __construct(){    
        parent::SugarView();
    }
    function display()
	{
		global $current_user;
		global $db;

		$header_style="style=\"background-color:black; padding: 10px;color:white;\"";
		$td_style="style=\"border: 1px solid #000;padding: 10px !important;\"";

		if ($current_user->is_admin) {
			$query = "SELECT sms_sms.*,users.first_name,users.last_name FROM sms_sms JOIN users on users.id=sms_sms.created_by WHERE messageid='scrm_Custom_Reports' ORDER BY sms_sms.date_entered DESC LIMIT 10";
		}else{
			$query = "SELECT * FROM sms_sms WHERE messageid='scrm_Custom_Reports' AND created_by='$current_user->id' ORDER BY date_entered DESC LIMIT 10";
		}
		$result = $db->query($query);
		$data = "";

		while($row = $db->fetchByAssoc($result)){
	    	$data .= '<tr height="25">';
	    	$phonelink = "index.php?module=SMS_SMS&action=DetailView&record=".$row['id'];
			$data .= "<td $td_style><a href='$phonelink' target='_blank'>".$row['name'].'</a></label></td>';
			$data .= "<td $td_style>".$row['delivery_status'].'</label></td>';
			$data .= "<td $td_style>".$row['description'].'</label></td>';
			$data .= "<td $td_style>".date('Y-m-d H:i:s',strtotime('+5 hour +30 minutes',strtotime($row['date_entered']))).'</label></td>';
			if ($current_user->is_admin) {
				$data .= "<td $td_style>".$row['first_name']." ".$row['last_name']."</label></td>";
				
			}
	    	$data .= '</tr>';
	    }

		?>
		<style>
		table td{
			padding:5px;
		}
		</style>
		<script type="text/javascript">
			$(document).ready(function(){
				var phoneno = /^\d{10}$/;
				var appIdFormat = /^\d{7}$/;

				function validatePhone(text){
					if(!text.match(phoneno) ){
				        alert("Mobile format should be 10 digit number");
				        return false;
					}
					return true;

				}
				function validateFrom(){
					var text = trim($('#from').val());
					var smsText = trim($('#sms').val());
					smsText = smsText.replace('$phone',text);
					$('#sms').val(smsText);
					return validatePhone(text);
				}
				function validateTo(){
					var text = trim($('#to').val());
					return validatePhone(text);
				}
				$('#from').change(function(){
					validateFrom();
				});
				$('#to').change(function(){
					validateTo();				       
				});
				$('#submit').click(function(){
					if(validateFrom() && validateTo()){
						var input = confirm('Are you sure, You wish to send the below message to customer?');
						if(input){
							// alert('Sending the messeage..');
							// var to = trim($('#to').val());
							// var message = trim($('#sms').val());
							// $.ajax('http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=354979&username=9920608616&password=neogrowth@123&To='+to+'&Text='+message)
							// .done(function(){
						 //    	alert('Send SMS successfully.');
						 //    }).fail(function() {
							//     alert( "error" );
							// })


							var data123 = $("#smsForm").serialize();
							var url = "./index.php";
							$.ajax({
								type: "POST",
								url: url,
								data: data123,
								async: true,
								success: function(data){
									console.log(data);
									alert('Done');
									window.location.reload(true);
								},
								error: function (XMLHttpRequest, textStatus, errorThrown) {
									alert('error');
									console.log(textStatus+' '+errorThrown);
								}
							});

							
							
						}else{
							alert('Please make modifications and press submit again.');
						}
					}
					return false;
				});
			// 	$('#appId').change(function(){
			// 		var appId = ($(this).val());
			// 		var smsText = $('#sms').val();
			// 		var phone = ($('#from').val());
			// 		var to = $('#to').val();
			// 		var full_name = $('#current_user_full_name').val();
			// 		validatePhone(phone);
			// 		validatePhone(to);
			// 		if(!appId.match(appIdFormat) ){
			// 	        alert("AppID format should be 7 digit number");
			// 	        return false;
			// 		}
			// 		
   //                  // alert(url);
   //                  console.log(url);
   //                  $.getJSON(url, function( data ) {
   //                      if((data.length)>0){
   //                          console.log(data);
   //                        // console.log(data[0]);
   //                        data = data[0];
   //                        smsText = smsText.replace('$customer',data['Applicant Person']);
   //                        // smsText = smsText.replace('$phone',data['Applicant Number']);
   //                        customer_number = data['Applicant Number'];
   //                        if(customer_number != to){
   //                        	alert('User mobile number entered is different, In our records it is '+customer_number);
   //                        }
   //                        // smsText = smsText.replace('$user',full_name);
   //                        $('#sms').val(smsText);
                          
   //                      }else{
   //                          console.log('No user found');
   //                      }
   //                    });
			// 	});
			});
		</script>
		<link rel="stylesheet" href="custom/modules/scrm_Custom_Reports/Report.css" type="text/css">

		<h1>Customer SMS Form</h1><br/>
		<form id='smsForm' method='POST' action='index.php'>
		<input type='hidden' id='module' name='module' value='Administration'>
	    <input type='hidden' id='action' name='action' value='FireTextSMS'>
	    <input type='hidden' id='sugar_body_only' name='sugar_body_only' value='1'>
	    <input type='hidden' id='option' name='option' value='send'>
	    <input type='hidden' id='personalizeh' name='personalizeh' value='0'>
	    <input type='hidden' id='masssms' name='masssms' value='0'>
		<table>
		<tr>
			<td>From Number:</td><td><input type='text' name='from' id='from'/></td><td>*(Service Manager's Number)</td>
		</tr><br/>
		<tr>
			<td>To Number:</td><td><input type='text' name='to' id='to'/></td><td>*(Customer's Number)</td>
		</tr><br/>
		<!-- <tr>
			<td>App Id:</td><td><input type='text' name='appId' id='appId'/></td><td>*(Application Id of customer for fetching name and contact number)</td>
		</tr> -->
		<tr>
			<td>Sms Content:</td><td><textarea rows="4" cols="50" name="sms" id='sms'>Dear Neogrowth Customer,
Your Service manager <?= $current_user->full_name; ?> is available from Mon to Sat between 10 am to 7pm on $phone & via email on helpdesk@neogrowth.in.
We appreciate your association with us.</textarea></td><td>*(Message which will actually go out to user)</td>
		</tr>
		<tr>
			<td></td><td colspan="1"><input type='submit' value='Submit' id='submit' name='submit'/></td>
		</tr>
		</table>
		</form>
		<input type='hidden' value='<?php echo $current_user->phone_mobile;?>' id='user_mobile'/>
		<br>
		<h5>Recent SMS List</h5>
		<div id="mainData">
			<div id="DataHeader">
			<table cellpadding="0" cellspacing="0"  border="1">
			<tr  height="25">
				<th  $header_style><label>Phone Number</lable></th>
				<th  $header_style><label>Delivery Status</lable></th>
				<th  $header_style><label>Message</lable></th>
				<th  $header_style><label>Date Created</lable></th>
				<?php
				if ($current_user->is_admin) {
					echo "<th><label>Created By</lable></th>";
				}
				?>
			</tr>
			<?php
			echo $data;
			?>
			</table>
			</div>
		</div>
		<?php
	}
} //end of class
