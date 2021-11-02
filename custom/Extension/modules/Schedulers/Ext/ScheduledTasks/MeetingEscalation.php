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

	$subject = "Meeting Is Not Creating Since 24 Hr.";
    $body = "Email Body";



	$getOppIdQuery="SELECT 
						op.id as opp_id
					FROM
						opportunities op
							LEFT JOIN
						meetings m ON op.id = m.parent_id
					WHERE
						m.parent_id IS NULL AND op.deleted = 0
						AND  DATE_SUB(now(),INTERVAL '05:30' HOUR_MINUTE)>=ADDDATE(op.date_entered,INTERVAL 24 hour)";


	$result = $db->query($getOppIdQuery);
	
	while ($row = $db->fetchByAssoc($result)) {
		
		$cc = array();
		$oppId = $row['opp_id'];
		$logger->log('debug', 'Precessing Opportunity Id: '.$oppId);

		// echo $oppId;
		echo "<br>";

		$oppBean = new Opportunity();
		$oppBeanData=$oppBean->retrieve($oppId);

		$userBean=new User();
		$assignUserBeanData=$userBean->retrieve($oppBeanData->assigned_user_id);

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