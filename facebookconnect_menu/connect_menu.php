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
require(e_PLUGIN ."/facebookconnect_menu/config.php");
require(e_PLUGIN ."/facebookconnect_menu/functions.php");
require_once("facebook.php");

$facebook = new Facebook(array(
  'appId'  => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_SECRET,
));
$access_token = $facebook->getAccessToken();
$uid = $facebook->getUser();
 $user_profile = $facebook->api('/me','GET');
//Check Query String for Error
$error = @$_GET['error'];
if ($error == "create"){
$displayerror = "<b>Facebook returned an error while creating your account</b>";
}
if ($error == "nouser"){
$displayerror = "<center>To use Facebook Connect, you must already have a user account on this website that was approved by an administrator.<hr>If you do, log in normally to the website and click \"Link Account\".  Once you do this, you can login using using your Facebook credentials by clicking, \"Connect With Facebook\".<hr>If you do not have an account yet, <a href=\"".e_HTTP."signup.php\">click here</a> to sign up for a new account!</center>";
}
//Start preliminary text
$caption = "Login with Facebook!";
$text = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"> ";
$text .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:fb=\"http://www.facebook.com/2008/fbml\">";
$text .= "<head>";
$text .= "<script type=\"text/javascript\">";
$text .= "function LinkAccounts(){ window.location = \"".e_PLUGIN ."facebookconnect_menu/actions.php?linkaccount=yes\";}";
$text .= "function Login(){window.location = \"".e_PLUGIN ."facebookconnect_menu/actions.php?login=yes&\";}";
$text .= "FB.Connect.ifUserConnected(set_cookies_and_refresh());";
$text .= "</script>";
$text .= "</head><body>";
$text .= "<center>";

$text .= "<div id=\"facebookDIV\">";
$text .= $displayerror;
//Check to see if e107 user has a Facebook ID attached
function checkUser($userid){
global $currentUser;
$selectsql = "SELECT * FROM e107_facebookconnect WHERE e107_id='".$userid."'";
$result = mysql_query($selectsql);
if (!$result) {die('Could Not Do Selection<br>'.$selectsql);}
$row = mysql_fetch_array($result, MYSQL_BOTH);
if (!$row['fb_id'] || $row['fb_id'] == "" || $row['fb_id'] == null){
return false;
}
else {
$return = $row['fb_id'];
return $return;
}
}
//Connect to e107 database
//dbFBConnect();

		//If a User is Logged In
		if ($currentUser['user_id'] != ""){
			$fbid = checkUser($currentUser['user_id']);
				//If User is Not Connected With Facebook Ask to Link Account
				if (!$fbid || $fbid == "" || $fbid == false){
					$text .= $user_profile['name'].", would you like to link your account with Facebook?<br>";
						//Check to see if user is already connected to Facebook
						if($uid == 0){
							$text .= "<fb:login-button perms=\"email,user_birthday\" onlogin=\"LinkAccounts();\">Link Account with Facebook</fb:login-button>";
											}
						//If the user is connected to Facebook and logged in, ask to link accounts.
						else {
							$text .= "<fb:login-button perms=\"email,user_birthday\" onlogin=\"LinkAccounts();\">Link Account with Facebook</fb:login-button>";
								}
										}
				else {
					if($uid == 0){
						$text .= "<fb:login-button perms=\"email,user_birthday\" onlogin=\"Login();\">Connect with Facebook</fb:login-button>";
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