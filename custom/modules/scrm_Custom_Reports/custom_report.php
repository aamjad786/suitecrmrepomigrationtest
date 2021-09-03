 <?php  
 		global $current_user;
 		$is_admin = false;
 		
 		// die();
        if(!$current_user->is_admin)  {
            $objACLRole = new ACLRole();
        	$roles = $objACLRole->getUserRoles($current_user->id);
        	if(in_array('Admin',$roles)) {
        		$is_admin = true;
        	}
	    }else{
	    	$is_admin = true;
	    }
	    
 ?>
<html>
    <head>

    </head> 
    <body>
   <!--   <h2>List Of Reports</h2>-->
    <form name="frmsales" id="frmsales" action="" method="post">
        <input type="hidden" id="pathurl"  name="pathurl" value="<?php global $sugar_config;$url=$sugar_config['site_url'];{echo $url;}?>"/>
        <table width="100%" cellspacing="20" cellpadding="0" border="0" class="list view">       
			<tr>
				<td>
						<table  cellspacing="10" cellpadding="0" border="0" >  
							   
							 	  <th><h2>Reports</h2></th> 
							 	  <?php if ($is_admin ){ ?> 
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=SMSReport"><span style ="font-family: Arial;font-size:14px;" ><b>1. SMS Report</b></span></a> 
									</td>
								</tr>

								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=AttemptWiseMISReport"><span style ="font-family: Arial;font-size:14px;" ><b>2. Attemptwise MIS Report</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=CampaignReport"><span style ="font-family: Arial;font-size:14px;" ><b>3. Email Campaign Report</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=TimeSlotWiseReport"><span style ="font-family: Arial;font-size:14px;" ><b>4. Time-Slot wise Report</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=CampaignWiseReport"><span style ="font-family: Arial;font-size:14px;" ><b>5. Campaign Wise Report</b></span></a>
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=SubDispositionReport"><span style ="font-family: Arial;font-size:14px;" ><b>6. SubDisposition Report</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=camcitywiseReport"><span style ="font-family: Arial;font-size:14px;" ><b>7. Cam City Wise Report</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=AgentLevelSnapshot"><span style ="font-family: Arial;font-size:14px;" ><b>8. Agent Level Snapshot</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=ProjectLevelSnapshot"><span style ="font-family: Arial;font-size:14px;" ><b>9. Project Level Snapshot</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=DispositionReport"><span style ="font-family: Arial;font-size:14px;" ><b>10. Disposition Report</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=LeadSourceReport"><span style ="font-family: Arial;font-size:14px;" ><b>11. Lead Source Report</b></span></a> 
									</td>
								</tr>
								<th><h2>Custom modules</h2></th>  
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=fileUpload"><span style ="font-family: Arial;font-size:14px;" ><b>1.Csv File Upload </b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=file_proccessed_status"><span style ="font-family: Arial;font-size:14px;" ><b>2. File Processed status</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=Cases&action=customer_application_profile"><span style ="font-family: Arial;font-size:14px;" ><b>3. Customer Application Profile View</b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=AssignUser"><span style ="font-family: Arial;font-size:14px;" ><b>4. User Management</b></span></a> 
									</td>
								</tr>
								
							<?php } 
							if($is_admin || in_array('Paylater Admin',$roles)){
							?>
								<th><h2>Paylater Reports</h2></th>  
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=PL1Report"><span style ="font-family: Arial;font-size:14px;" ><b>1. Partner Wise Summary </b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=PL2Report"><span style ="font-family: Arial;font-size:14px;" ><b>2. Campaign wise Conversions & TAT Summary </b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=PL3Report"><span style ="font-family: Arial;font-size:14px;" ><b>3. Partner wise Month on Month leads & Conversions Summary  </b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=PL4Report"><span style ="font-family: Arial;font-size:14px;" ><b>4. TAT Summary for Conversions </b></span></a> 
									</td>
								</tr>
							<?php } 
							if($is_admin || $is_renewal_user){
							?>
								<th><h2>Renewals Reports</h2></th>  
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=RenewalCustomerReport"><span style ="font-family: Arial;font-size:14px;" ><b>1. Renewals Customer Reports </b></span></a> 
									</td>
								</tr>
							<?php	
							}
							if($is_admin || (in_array('Customer support executive',$roles))) {
								?>
								<th><h2>Customer Support Reports</h2></th>  
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=CallsReport"><span style ="font-family: Arial;font-size:14px;" ><b>1. Calls Reports </b></span></a> 
									</td>
								</tr>
								<tr class="oddListRowS1" height="20">
									<td class="nowrap" width="1%">
									<a target="_blank" style="text-decoration: none" href="?module=scrm_Custom_Reports&action=document_requests_report"><span style ="font-family: Arial;font-size:14px;" ><b>2. Document Request </b></span></a> 
									</td>
								</tr>
								<?php
							}
							?>
								
								<!-- < -->
						</table>
					</td>
            </tr>
	   </table>
    </form>
</body>
</html>