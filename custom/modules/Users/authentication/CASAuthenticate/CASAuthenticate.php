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
 * This file is used to control the authentication process.
 * It will call on the user authenticate and controll redirection
 * based on the users validation
 *
 */


require_once('modules/Users/authentication/SugarAuthenticate/SugarAuthenticate.php');
// require_once('modules/Users/authentication/CASAuthenticate/lib/phpCAS/CAS.php');

// global $sugar_config;
// phpCAS::setDebug();
// phpCAS::setVerbose(true);
// phpCAS::client(CAS_VERSION_3_0, $sugar_config['CAS_host'], (int)$sugar_config['CAS_port'], $sugar_config['CAS_context']);
// phpCAS::setNoCasServerValidation();



class CASAuthenticate extends SugarAuthenticate {
	var $userAuthenticateClass = 'CASAuthenticateUser';
	var $authenticationDir = 'CASAuthenticate';
	/**
	 * Constructs SAMLAuthenticate
	 * This will load the user authentication class
	 *
	 * @return SAMLAuthenticate
	 */
	function __construct(){
		parent::__construct();
	}

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    function CASAuthenticate(){
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';


        if(isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        }
        else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }



    /**
     * pre_login
     *
     * Override the pre_login function from SugarAuthenticate so that user is
     * redirected to SAML entry point if other is not specified
     */
    function pre_login()
    {
        parent::pre_login();
        $this->redirectToLogin($GLOBALS['app']);
    }

    /**
     * Called when a user requests to logout
     *
     * Override default behavior. Redirect user to special "Logged Out" page in
     * order to prevent automatic logging in.
     */
    public function logout() {
        $GLOBALS['log']->debug('Akshay CAS tryiing to logout user'.$GLOBALS['current_user']->user_name);
        // handle incoming logout requests

        $protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
        $url = $protocol."://".$_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        echo $url;
        phpCAS::logoutWithUrl($url);
        session_destroy();
        ob_clean();
        // header('Location: index.php?module=Users&action=LoggedOut');
        sugar_cleanup(true);
        $GLOBALS['log']->debug('Akshay CAS logout successful');
    }

    /**
     * Redirect to login page
     *
     * @param SugarApplication $app
     */
    public function redirectToLogin(SugarApplication $app)
    {

        //  if (isset($_REQUEST['login_module']) && isset($_REQUEST['login_action'])) {
        //     phpCAS::forceAuthentication();
        //     $GLOBALS['log']->debug('akki   woill go for it  -  ');
        //     $_SESSION['authenticated_user_id'] = phpCAS::getAttribute('nguid');
        // }

        phpCAS::forceAuthentication();
        $GLOBALS['log']->debug('Akshay cas provided the attributes');
        $GLOBALS['log']->debug(phpCAS::getAttributes());

        $casProvidedId = phpCAS::getAttribute('objectguid');
        $GLOBALS['log']->debug('Akshay cas provided LDAP nguid '.$casProvidedId);
        $memberOf = phpCAS::getAttribute('memberOf');
        global $sugar_config;
        $AD_TOKEN = $sugar_config['AD_CRM_TOKEN'];
        if(is_array($memberOf)){
            $memberOf = implode(" ",$memberOf);
        }
        if (false && strpos($memberOf, $AD_TOKEN) === false) {
            $header = "Access not allowed";
            $message = "Contact IT admin! You are not authorized to access CRM.<a href='index.php?module=Users&amp;action=Logout'>Logout</a>";
            $str = file_get_contents('custom/page.html');
            $str = str_replace('$header', $header, $str);
            $str = str_replace('$message', $message, $str);
            //<a role="menuitem" id="logout_link" href="index.php?module=Users&amp;action=Logout" class="utilsLink">Log Out</a>
            echo $str;
            die();
        }else{

            if(isset($casProvidedId)) {
                $_SESSION['authenticated_user_id'] = trim(phpCAS::getAttribute('objectguid'), '{}');
                // echo ($_SESSION['authenticated_user_id']);
                $GLOBALS['log']->debug('Akshay cas trying to log in user with id '.$_SESSION['authenticated_user_id']);
            }else {
                 $GLOBALS['log']->debug('Akshay cas could not find login user id');
                die("Contact tech suuuuuupport");
            }
        }
        // var_dump(phpCAS::getUser());
        // var_dump(phpCAS::getAttribute('nguid'));
        // var_dump(phpCAS::isSessionAuthenticated());
         // var_dump(phpCAS::isAuthenticated());
        //   var_dump(phpCAS::checkAuthentication());
        //   var_dump(phpCAS::getAttributes());

          // $_SESSION['unique_key'] = phpCAS::getAttribute('nguid');

        // phpCAS::allowProxyChain(new CAS_ProxyChain(array($pgtUrlRegexp)));

        // SugarApplication::redirect($url);
    }


    function postSessionAuthenticate(){
        $GLOBALS['log']->debug('Akshay CAS checking session validation in postSessionAuthenticate');
        global $action, $allowed_actions, $sugar_config;
        $_SESSION['userTime']['last'] = time();
        $user_unique_key = (isset ($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';

        // (isset ($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';
        $server_unique_key = (isset ($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';

        $GLOBALS['log']->debug('Akshay CAS '.$server_unique_key."  ".$user_unique_key);


        // // CHECK IF USER IS CROSSING SITES
        // if (($user_unique_key != $server_unique_key) && (!in_array($action, $allowed_actions)) && (!isset ($_SESSION['login_error']))) {

        //     $GLOBALS['log']->debug('Destroying Session User has crossed Sites');
        //     session_destroy();
        //     header("Location: index.php?action=Login&module=Users".$GLOBALS['app']->getLoginRedirect());
        //     sugar_cleanup(true);
        // }
        if (!$this->userAuthenticate->loadUserOnSession($_SESSION['authenticated_user_id'])) {
            session_destroy();
            header("Location: index.php?action=Login&module=Users&loginErrorMessage=LBL_SESSION_EXPIRED");
            $GLOBALS['log']->debug('Current user session does not exist redirecting to login');
            sugar_cleanup(true);
        }
        $GLOBALS['log']->debug('Akshay Current user for crm is: '.$GLOBALS['current_user']->user_name);
        return true;
    }

}
