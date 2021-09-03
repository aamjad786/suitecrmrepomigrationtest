<?php
require ('aws/aws-autoloader.php');
require_once('data/BeanFactory.php');
use Aws\Sns\SnsClient; 
use Aws\Exception\AwsException;

class EmailAutomation{
 
    function call_sns($bean, $event, $arguments){
        $date = date('Y-m-d H:i:s');
        $myfile=fopen("Logs/snscall.log","a");
        fwrite($myfile,"\n$date\n");
        if($bean->classify_c!=1)
        {
            $SnSclient = new SnsClient([
                //'profile' => 'SNS',
                'region' => 'ap-south-1',
                'version' => '2010-03-31'
            ]);
                $subject=$bean->name;
                $description=$bean->description;
                $number=$bean->case_number;
            $message='{"subject": "'.$subject.'","description": "","description_html": "'.$description.'","case_id": '.$number.'}';
            $topic = 'arn:aws:sns:ap-south-1:854483613921:Invoke-CRM-Email-Automation-Lambda-UAT';

            try {
                $result = $SnSclient->publish([
                    'Message' => $message,
                    'TopicArn' => $topic,
                ]);
                fwrite($myfile,print_r($result,true));
            } catch (AwsException $e) {
                // output error message if fails
                error_log($e->getMessage());
            }
        }
    }

}
?>
