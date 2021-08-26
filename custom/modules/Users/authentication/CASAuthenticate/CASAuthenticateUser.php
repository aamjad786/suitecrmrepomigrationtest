<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/




/**
 * This file is where the user authentication occurs. No redirection should happen in this file.
 *
 */
require_once('modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php');

define('DEFAULT_PORT', 389);
class CASAuthenticateUser extends SugarAuthenticateUser{

  function createOrUpdateuser() {
    $GLOBALS['log']->debug('Akshay check if user needs to be updated');
    $loginUserId = $_SESSION['authenticated_user_id'];
    if(isset( $loginUserId)) {
        $user = new User;
        $user->retrieve($loginUserId);
        $GLOBALS['log']->debug('Akshay ldap updated is :: '.empty($_SESSION['ldapudated']));
        if(empty($_SESSION['ldapudated'])) {
            if(!isset($user->date_entered)) {
              //save for first time
                $GLOBALS['log']->debug('Akshay will create new crm user');
                $user = new User;
                $user->new_schema = true;
                $user->new_with_id = true;
                
                //Add security group for new user
				global $db;
				//get security group id
				$sg_query = "select sg.id from securitygroups sg where sg.name='Case agent' and sg.deleted = 0";
				$sg_result = $db->query($sg_query);
				while($sg_row = $db->fetchByAssoc($sg_result)){
					$securitygroup_id = $sg_row['id'];
				}
				
				$sgu_id = create_guid();
				$sgu_query = "INSERT INTO securitygroups_users (id , date_modified , deleted , securitygroup_id , user_id , primary_group , noninheritable) VALUES('$sgu_id' , NOW() , '0' , '$securitygroup_id' , '$loginUserId' , '0' , '0')";
				$db->query($sgu_query);
				   
				//End security group Add
                
                
              }else {
              //update common attributes
                $user->new_with_id = false;
                $user->new_schema = false;
                $GLOBALS['log']->debug('Akshay CAS found user in crm. Hence update will be caried out');
            }

            $user->id = $loginUserId;
            $user->name = phpCAS::getAttribute('username');
            $user->user_name = phpCAS::getAttribute('username');

            $user->first_name = phpCAS::getAttribute('firstname');
            $user->last_name = phpCAS::getAttribute('lastname');
            // email update is disabled, for some users emails are empty from AD. So even though we update from crm ui, its rolled back to null   
            if(empty($user->email1)){
              $user->email1 = phpCAS::getAttribute('email');
            }
            // Stop syncing admin role
            // $user->is_admin = stripos(phpCAS::getAttribute('description'), 'crm') > -1;
            $user->authenticated = true;
            $user->description = phpCAS::getAttribute('LdapAuthenticationHandler.dn');
            $user->team_exists = false;
            $user->table_name = "users";
            $user->module_dir = 'Users';
            $user->object_name = "User";
            $user->status = "Active";

            //Account type for normal user
            $sAMAccountType = phpCAS::getAttribute('sAMAccountType');
            if($sAMAccountType  == 805306368) {
                $user->show_on_employees = 1;
            }else {
                $user->show_on_employees = 0;
            }

            $user->importable = true;
            $user->encodeFields = Array ("first_name", "last_name", "description");
            $user->save();
            
            $GLOBALS['log']->debug('Akshay CAS updated the user entry in CRM');

            // $user->retrieve($loginUserId);

            $_SESSION['ldapudated'] = 1;
        }else {
            $GLOBALS['log']->debug('Akshay skipping user entry updates from cas');
        }
    }else {
        $GLOBALS['log']->debug('Akshay cas could not createOrUpdateuser as login user id is not present');
        die("Contact techhhh support");
    }

    return $user;
  }

  function loadUserOnSession($user_id = '') {
    $GLOBALS['log']->debug("Akshay CAS trying to load user on session");
    if(!empty($user_id)) {
      $_SESSION['authenticated_user_id'] = $user_id;
    }

    if(phpCAS::isAuthenticated() == 1 && phpCAS::isSessionAuthenticated() ==1) {
        if(!empty($_SESSION['authenticated_user_id']) || !empty($user_id)){
          $GLOBALS['current_user'] = $this->createOrUpdateuser();
          if(isset($GLOBALS['current_user'])) {
            $GLOBALS['log']->debug("Akshay CAS loaded the user on session");
             return true;
          }
          // $GLOBALS['log']->debug("Akshay CAScompleted create or update");
          // $GLOBALS['current_user'] = new User();
          // if($GLOBALS['current_user']->retrieve($_SESSION['authenticated_user_id'])){

          //   return true;
          // }
        }
    }
    $GLOBALS['log']->debug("Akshay CAS could not load the user on session");
    return false;
  }


  function authenticateUser($name, $password, $fallback=false)
  {
      // $_SERVER['PHP_AUTH_USER'] = $name;
      // $_SERVER['PHP_AUTH_PW'] = 'Welcome@222';//$password;

      //Won't work on username password as if given basic authentication header

      $GLOBALS['log']->debug('Akshay CAS authenticateUser in authenticateUser '.$name.'  '.phpCAS::isSessionAuthenticated().phpCAS::isAuthenticated());

      $headers = apache_request_headers();
      $GLOBALS['log']->debug($headers);

      // $GLOBALS['log']->debug($_SERVER);
      // return '1';
      // session_id($_SERVER['HTTP_SESSIONID']);

       // $GLOBALS['log']->debug($_SERVER['QUERY_STRING']);
      phpCAS::forceAuthentication();

      $GLOBALS['log']->debug('Akshay CAS completed authenticateUser authentication process');

      if(phpCAS::isAuthenticated()) {
        $GLOBALS['log']->debug('Akshay cas authenticateUser authenticated user');
        $GLOBALS['log']->debug('Akshay cas  authenticateUser user details ');
        $GLOBALS['log']->debug(phpCAS::getAttributes());

        $_SESSION['authenticated_user_id'] = trim(phpCAS::getAttribute('nguid'), '{}');
        return $_SESSION['authenticated_user_id'];
      }
      $GLOBALS['log']->debug('Akshay cas cuold not authenticated user');
      return '';
  }

  function loadUserOnLogin($name, $password, $fallback = false, $PARAMS = array()) {
    global $login_error;


    $GLOBALS['log']->debug('Akshay  loadUserOnLogin  loadUserOnLogin ');

    $user_id = $this->authenticateUser($name, $input_hash, false);
    if(empty($user_id)) {
      $GLOBALS['log']->fatal('SECURITY: User authentication for '.$name.' failed');
      return false;
    }
    $this->loadUserOnSession($user_id);

    return true;
  }


}

?>

