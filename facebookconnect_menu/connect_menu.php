<?php
/*
+------------------------------------------------------------------------------+
|     e107 Facebook Connect Integration
|
|	File: 			connect_menu.php
|	Description:	This file checks the user's status regarding accounts on
|					the e107 system and Facebook and displays messages accordingly
|
|	Support: jdgeraci@gmail.com
|	(I'm not sure how much help I can be.  Still a n00b
|	For the e107 website system visit http://e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+------------------------------------------------------------------------------+
*/
global $currentUser;
session_start();
// store session data
$_SESSION['refer']=e_SELF;
//Check Script for Errors
error_reporting(E_ALL);
ini_set('display_errors', '1');
//Call e107 Class to determine user status
if (!defined('e107_INIT')) { exit; }
//Get required plugins
require_once(e_PLUGIN ."/facebookconnect_menu/config.php");
require_once(e_PLUGIN ."/facebookconnect_menu/functions.php");
require_once(e_PLUGIN ."/facebookconnect_menu/facebook.php");
$facebook= new Facebook($config);
$access_token = $facebook->getAccessToken();
$uid=$facebook->getUser();
if ($uid > 0) { 

	//$_SESSION['access_token'] = $facebook->getAccessToken();
	//$_SESSION['user'] = $facebook->api('/me','GET');
	$my_url = "http://kool2zero.com/test";
	$_SESSION['state'] = md5( uniqid( rand( ), true)); // CSRF protection
	$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" . FACEBOOK_APP_ID . "&redirect_uri=" . urlencode( $my_url) . "&state=" . $_SESSION['state'] . "&scope=".$comma_separated;
 $user_profile = $facebook->api('/me','GET');
}
//Check Query String for Error
$error = @$_GET['error'];
if ($error == "create"){
	$displayerror = "<b>Facebook returned an error while creating your account</b><br>";
}
if ($error == "nouser"){
	$displayerror = "<center>To use Facebook Connect, you must already have a user account on this website that was approved by an administrator.<hr>If you do, log in normally to the website and click \"Link Account\".  Once you do this, you can login using using your Facebook credentials by clicking, \"Connect With Facebook\".<hr>If you do not have an account yet, <a href=\"".e_HTTP."signup.php\">click here</a> to sign up for a new account!</center>";
}
if ($error == "fbidexists"){
	$displayerror = "<center>This Facebook User has already connected to a different account.</center>";
}


//Start preliminary text
$caption = "Login with Facebook!";
$text = "<!doctype html>";
$text .= "<head>";
$text .= "<script type=\"text/javascript\">";
$text .= "function LinkAccounts(){ window.location = \"".e_PLUGIN ."facebookconnect_menu/actions.php?linkaccount=yes\";}";
$text .= "function Login(){window.location = \"".e_PLUGIN ."facebookconnect_menu/actions.php?login=yes&\";}";
//$text .= "FB.Connect.ifUserConnected(set_cookies_and_refresh());";
$text .= "</script>";
$text .= "</head><body>";
$text .= "<center>";

$text .= "<div id=\"facebookDIV\">";
$text .= $displayerror;

//Connect to e107 database
//dbFBConnect();

		//If a User is Logged In
		if ($currentUser['user_id'] != ""){
			$fbid = checkUser($currentUser['user_id']);
				//If User is Not Connected With Facebook Ask to Link Account
				if (!$fbid || $fbid == "" || $fbid == false){
					$text .= USERNAME. ", would you like to <a href=\"".e_PLUGIN ."facebookconnect_menu/actions.php?linkaccount=yes\">link your account with Facebook?</a><br>";
					$text .= "<div class=\"fb-login-button\" data-show-faces=\"true\" data-width=\"200\" data-max-rows=\"1\" data-scope=\"email,user_birthday\" data-onlogin=\"LinkAccounts();\">Link Accounts</div>";
					
											}
				else {
					if($uid == 0){
						$text .= "<div class=\"fb-login-button\" data-show-faces=\"true\" data-width=\"200\" data-max-rows=\"1\" data-scope=\"email,user_birthday\" data-onlogin=\"Login();\"></div>";
										}
					else{
						$text .= "<img src=\"https://graph.facebook.com/".$uid."/picture\"/><br>Welcome, ".$user_profile['name'];
						}
					}
											}
		else{
				$text .= "<fb:login-button perms=\"email,user_birthday\" onlogin=\"Login();\">Connect with Facebook</fb:login-button>";
			}
		
$text .= "</div>";

$text .= loadFBjs();
$text .= "</center>";
$text .= "</body></html>";
//Set to Table
$ns -> tablerender($caption, $text);
?>	