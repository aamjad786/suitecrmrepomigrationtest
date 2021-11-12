<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class PullUser{

	function insertUser($nguid){
		$results = false;
	try{
			$GLOBALS['log']->debug("Pulling user " . $nguid);
			$url = getenv("SCRM_AD_UTILITY_HOST")."/json2ldap";
			$params = "{\n  \"method\"  : \"ldap.search\",\n  \"params\"  : { \n  \"filter\": \"(sAMAccountName=$nguid)\",\n  \"attributes\": \"*\",\n    \"binaryAttributes\": [\"objectGUID\", \"objectSid\"]\n  },\n  \"decoded\" : true\n}";
			$headers = array(
				    "cache-control: no-cache",
				    "content-type: application/json"
			);
			$max_redirects = 10;
			$timeout = 30;
			$port = "3009";
			// $curl = curl_init();

			// curl_setopt_array($curl, array(
			//   CURLOPT_PORT => ,
			//   CURLOPT_URL => $url,
			//   CURLOPT_RETURNTRANSFER => true,
			//   CURLOPT_ENCODING => "",
			//   CURLOPT_MAXREDIRS => $max_redirects,
			//   CURLOPT_TIMEOUT => $timeout,
			//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			//   CURLOPT_CUSTOMREQUEST => "POST",
			//   CURLOPT_POSTFIELDS => $params,
			//   CURLOPT_HTTPHEADER => $headers
			// ));
			// $results = curl_exec($curl);
			// $err = curl_error($curl);

			// curl_close($curl);

			require_once('custom/include/CurlReq.php');
			$curl_req   = new CurlReq();

			$result     = $curl_req->curl_req($url, 'post', $params, $headers, '', '', $max_redirects, $timeout, true, $port);
			$results   	= $result['response'];
			$err        = $result['error'];

			if ($err) {
			  echo "cURL Error #:" . $err;
			  $GLOBALS['log']->error("cURL Error #:" . $err);
			  die();
			}

			$results = (json_decode($results, true));
			$results = $results['matches'];
			foreach ($results as $result) {
				try{
					$loginUserId = $result['objectGUID'][0];
				
					if(!isset($loginUserId)) {
						echo "<br>";
						echo "<span style=\"color: red;\">Username ".$nguid." does not exists </span>";
						echo "<br>";
						$GLOBALS['log']->debug("Username ".$nguid." does not exists");
						continue;
					}
					$user = new User;
					$user->retrieve($loginUserId);
										// die();
					if(empty($user->date_entered)) {
					  //save for first time
					    
						$user->new_schema = true;
						$user->new_with_id = true;
						$user->id = $loginUserId;
						$user->name = $result['cn'][0];
						$user->user_name = $result['sAMAccountName'][0];

						$user->first_name = $result['givenName'][0];
						// echo $result['givenName']." is the givenName<Br>";
						$user->last_name = $result['sn'][0];
						$user->email1 = $result['mail'][0];
						$user->department = $result['department'][0];
						$user->is_admin = stripos($result['description'], 'crm') > -1;
						$user->authenticated = true;
						$user->description = $result['DN'];
						$user->team_exists = false;
						$user->table_name = "users";
						$user->module_dir = 'Users';
						$user->object_name = "User";
						$user->status = "Active";
						// $user->photo = $result['manager'][0];
						//Account type for normal user
						// $sAMAccountType = $result['sAMAccountType'];
						// $user->show_on_employees = 0;
						// if($sAMAccountType  == 805306368) {
						//     $user->show_on_employees = 1;
						// }else {
						    
						// }

						$user->importable = true;
						$user->encodeFields = Array ("first_name", "last_name", "description");
						$user->save();
						echo "<br>";
						echo $result['sAMAccountName'][0]." user saved\n";
						$GLOBALS['log']->debug($result['sAMAccountName'][0]." user saved");
						$i++;
					}
					
				}catch(Exception $e){
					echo "Exception in saving $nguid".$e->getMessage();
					$GLOBALS['log']->error("Exception in saving" . $nguid . " : " . $e->getMessage());
					$results = false;
				}
				
			}
			echo "Total $i users created";
			$GLOBALS['log']->debug("Total ". $i . " users created");
			if($i>0){
				$results = true;
			}
		}catch(Exception $e){
			echo "Exception occured in script execution".$e->getMessage();
			$GLOBALS['log']->error("Exception in script execution" . $e->getMessage());
			$results = false;
		}
		return $results;
	}	

	/*update users u1 join users u2 set u1.reports_to_id=u2.id  where u2.description=u1.photo;*/
	//modifify the return as required
	function updateManagerInfo(){
		$bean = BeanFactory::getBean('Users');
	    $query = 'users.deleted=0 and users.photo != ""';
	    $items = $bean->get_full_list('',$query);
	    // var_dump($items);
	   // ob_start('ob_file_callback');
	   // echo $query;
	   $GLOBALS['resavingRelatedBeans']=true;
	   $count=0;
	   if ($items){
	        foreach($items as $item){
	        	$manager_dn =  $item->photo;
	        	// $item->photo = '';
	        	$GLOBALS['resavingRelatedBeans']=true;
	        	// $item->save();
	        	// echo "Manager $manager->user_name updated for $item->user_name<br>";
	        	// break;
	        	if (!empty($manager_dn)){
	        	
		        	$query = "users.description='$manager_dn'";
		        	$manager = $bean->get_full_list('',$query);
		        	$manager = $manager[0];
		        	// var_dump($manager[0]->id);
		        	// break;
		        	if (!empty($manager)) {
			        	$item->reports_to_id = $manager->id;
			        	echo "Manager $manager->user_name updated for $item->user_name<br>";
		        	}else{
			        	echo "Manager not found for $item->user_name<br>";
		        	}
		        }else{
		        	echo "Not updated $item->user_name<br>";
		        }
	        	$item->photo = '';
	        	$item->save();
	        }
	    }
	    echo "All updates finished<br>";
	    $GLOBALS['resavingRelatedBeans']=false;
	}	

}
