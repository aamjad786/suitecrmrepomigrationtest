<?php
require_once 'custom/CustomLogger/CustomLogger.php';
require_once('custom/include/CurlReq.php');
require_once('custom/include/SendEmail.php');

$job_strings[] = 'MeetingEscalation';


function MeetingEscalation()
{
	$logger = new CustomLogger('MeetingEscalation');
	$logger->log('debug', '<=============== MeetingEscalation Started ====================>');
	
	global $db;
	$email = new SendEmail();

    $body = "Email Body";



	$getOppIdQuery="SELECT 
						op.id AS opp_id
					FROM
						opportunities op
							LEFT JOIN
						meetings m ON op.id = m.parent_id
							LEFT JOIN
						users user ON user.id = op.assigned_user_id
							LEFT JOIN
						users_cstm u_cstm ON user.id = u_cstm.id_c
					WHERE
						(m.parent_id IS NULL AND op.deleted = 0
							AND u_cstm.designation_c LIKE '%Customer Acquisition%')
							AND DATE_SUB(NOW(),	INTERVAL '05:30' HOUR_MINUTE) >= ADDDATE(op.date_entered,INTERVAL 24 HOUR)";


	$result = $db->query($getOppIdQuery);
	
	while ($row = $db->fetchByAssoc($result)) {
		
		$cc = array();
		$oppId = $row['opp_id'];
		$logger->log('debug', 'Precessing Opportunity Id: '.$oppId);

		// echo $oppId;

		$oppBean = new Opportunity();
		$oppBeanData=$oppBean->retrieve($oppId);

		$userBean=new User();
		$assignUserBeanData=$userBean->retrieve($oppBeanData->assigned_user_id);

		$subject = "Meeting Is Not Creating Since 24 Hr. For ".$oppBeanData->pickup_appointment_contact_c;

		// Cluster Manager 
		$clusterManagerData=$userBean->retrieve($assignUserBeanData->reports_to_id);
		$clusterManagerEmail=$clusterManagerData->email1;

		if (!empty($clusterManagerEmail)) {
			$to = array($clusterManagerEmail);
            $email->send_email_to_user($subject, $body, $to, $cc);
			$logger->log('debug', 'Sending Email To Cluster Manager: '.$clusterManagerData->first_name.' Email: '.$clusterManagerEmail);
        }

		// Regional Manager 
		$regionalManagerData=$userBean->retrieve($clusterManagerData->reports_to_id);
		$regionalManagerEmail=$regionalManagerData->email1;
		
		if (!empty($regionalManagerEmail)) {
			$to = array($regionalManagerEmail);
            $email->send_email_to_user($subject, $body, $to, $cc);
			$logger->log('debug', 'Sending Email To Regional Manager: '.$regionalManagerData->first_name.' Email: '.$regionalManagerEmail);
        }

		// Zonal Manager
		$zonalManagerData=$userBean->retrieve($regionalManagerData->reports_to_id);
		$zonalManagerEmail=$zonalManagerData->email1;

		if (!empty($zonalManagerEmail)) {
			$to = array($zonalManagerEmail);
            $email->send_email_to_user($subject, $body, $to, $cc);
			$logger->log('debug', 'Sending Email To Zonal Manager: '.$zonalManagerData->first_name.' Email: '.$zonalManagerEmail);
        }
	}

	return true;
}

// MeetingEscalation();