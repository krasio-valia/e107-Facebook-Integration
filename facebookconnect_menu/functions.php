<?php
/*
+------------------------------------------------------------------------------+
|     e107 Facebook Connect Integration
|
|	File: 			functions.php
|	Description:	This file establishes functions for use with the Facebook
|					Connect system.
|
|	Support: jdgeraci@gmail.com
|	(I'm not sure how much help I can be.  Still a n00b
|	For the e107 website system visit http://e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+------------------------------------------------------------------------------+
*/
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("config.php");
require_once( "facebook.php");
//Facebook Config
$config=array( );
$config['appId']=FACEBOOK_APP_ID;
$config['secret']=FACEBOOK_SECRET;
$config['fileUpload']=false; // optional
$facebook=new Facebook($config);
$uid=$facebook->getUser();
if ($uid >0) { 
	$_SESSION['access_token'] = $facebook->getAccessToken();
} 
//Update user record with Facebook ID
function updateUser($userid, $fbid){
	//First find the e107 ID from the Facebook Connect table
	$selectsql = "SELECT count(e107_id) FROM e107_facebookconnect WHERE fb_id='".$fbid."'";
	$result = mysql_query($selectsql);
	if (!$result) {die('Could Not Do Selection to get e107 ID<br>'.$selectsql);}
	$row = mysql_fetch_array($result, MYSQL_BOTH);
	if ($row[0] > 0) {
		return FALSE;
	}
	else {
$updatesql = "INSERT INTO e107_facebookconnect (e107_id,fb_id) VALUES (".$userid.",'".$fbid."')";
//$updatesql = "UPDATE e107_user SET fbid='".$fbid."' WHERE user_id=".$userid;
mysql_query($updatesql) or die('Could not update: '.$updatesql);
return TRUE;
	}
}
//Login to e107
function loginfromFacebook($fbid){
global $pref;
//First find the e107 ID from the Facebook Connect table
$selectsql = "SELECT * FROM e107_facebookconnect WHERE fb_id='".$fbid."'";
$result = mysql_query($selectsql);
if (!$result) {die('Could Not Do Selection to get e107 ID<br>'.$selectsql);}
$e107id = mysql_fetch_array($result, MYSQL_BOTH);
//Once you have the e107 ID, log in with credentials.
$selectsql = "SELECT * FROM e107_user WHERE user_id='".$e107id['e107_id']."'";
$result = mysql_query($selectsql);
if (!$result) {die('Could Not Do Selection<br>'.$selectsql);}
$row = mysql_fetch_array($result, MYSQL_BOTH);
$cookieval = $row['user_id'].".".md5($row['user_password']);

            if ($pref['user_tracking'] == "session") {
                $_SESSION[$pref['cookie_name']] = $cookieval;
            } else {
                cookie($pref['cookie_name'], $cookieval, (time()+3600 * 24 * 30));
            }
			}
//Create e107 account with a facebook account			
function createUserfromFacebook($fbid){
global $accountcreation;
$selectsql = "SELECT * FROM e107_facebookconnect WHERE fb_id='".$fbid."'";
$result = mysql_query($selectsql);
if (!$result) {die('Could Not Do Selection<br>'.$selectsql);}
$num_rows = mysql_num_rows($result);
//If a user could not be found with a Facebook ID
if ($num_rows == 0 && $accountcreation == "Y"){
require_once("facebook.php");

$facebook = new Facebook(array(
  'appId'  => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_SECRET,
));
$access_token = $facebook->getAccessToken();
$uid = $facebook->getUser();

if ($uid > 0){
 $user_profile = $facebook->api('/'.$fbid,'GET');
//$returnedfbdata=array();
$returnedfbdata['fbuserid'] = $uid;
$returnedfbdata['first_name'] = $user_profile['first_name']; 
$returnedfbdata['last_name'] = $user_profile['last_name'];
$name = $user_profile['name'];
$returnedfbdata['email'] = $user_profile['email']; 
$returnedfbdata['pic_big'] = GRAPHAPI.$uid."/picture"; 
$returnedfbdata['timezone'] = $user_profile['timezone']; 
//Check to see if user already exists by email address
$selectemailsql = "SELECT * FROM e107_user WHERE user_email='".$returnedfbdata['email']."'";
$emailresult = mysql_query($selectemailsql);
if (!$emailresult) {die('Could Not Do Selection<br>'.$selectemailsql);}
$num_rows = mysql_num_rows($emailresult);
//If a user could not be found with a email, create both, else, just link.
if ($num_rows == 0){
$action = "add";
}
if ($action == "add"){
$insertsql = "INSERT INTO e107_user (user_name,user_login,user_loginname,user_password,user_email,user_image,user_timezone,user_ban) VALUES ('".$name."','".$returnedfbdata['fbuserid']."','".$name."','".md5($fbid)."','".$returnedfbdata['email']."','".$returnedfbdata['pic_big']."','".$returnedfbdata['timezone']."',0);";
$result = mysql_query($insertsql);
if (!$result) {die('Could Not Do Selection<br>'.$insertsql);}
}
//Return User ID for new User
$returnuserid = "SELECT * FROM e107_user WHERE user_email='".$returnedfbdata['email']."'";
$returnedid = mysql_query($returnuserid);
if (!$returnedid) {die('Could Not Do Selection to get e107 ID<br>'.$returnuserid);}
$e107id = mysql_fetch_array($returnedid, MYSQL_BOTH);
//Once User ID is returned, insert record into Facebook Connect Table
$insertsql = "INSERT INTO e107_facebookconnect (e107_id,fb_id) VALUES (".$e107id['user_id'].",".$returnedfbdata['fbuserid'].");";
$result = mysql_query($insertsql);
if (!$result) {die('Could Not Do Selection<br>'.$insertsql);}
//Once user is created, log in
loginfromFacebook($returnedfbdata['fbuserid']);
$return = "created";
return $return;
}
else{
$return = "error";
return $return;
}
}
elseif ($num_rows == 0 && $accountcreation == "N"){
$return = "nouser";
return $return;
}
else {
// If a user was found with the facebook ID, log in
loginfromFacebook($fbid);
$return = "login";
return $return;
}
}
function getFacebookUsers(){
$selectsql = "SELECT * FROM e107_facebookconnect";
$result = mysql_query($selectsql);
if (!$result) {die('Could Not Do Selection<br>'.$selectsql);}
return $result;
}
function showFacebookUsers($e107user,$fbuser){

//Get e107 User Info
$selectsql = "SELECT * FROM e107_user where user_id=".$e107user;
$e107result = mysql_query($selectsql);
$row = mysql_fetch_array($e107result, MYSQL_BOTH);
if (!$e107result) {die('Could Not Do Selection<br>'.$selectsql);}
require_once("facebook.php");

$facebook = new Facebook(array(
  'appId'  => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_SECRET,
));
$access_token = $facebook->getAccessToken();
 $user_profile = $facebook->api('/'.$fbuser,'GET');
  
$return = "<tr><td width=\"51px\"><img src=\"https://graph.facebook.com/".$fbuser."/picture\"/></td><td valign=\"top\"><table width=\"100%\"><tr><td width=\"20%\"><b>Name: </b></td><td>".$user_profile['name']."</td></tr><tr><td><b>Website Username:<b></td><td>".$row['user_loginname']."</td></tr><tr><td colspan=\"2\"> </td></tr></table></tr>";
return $return;


}
//New Facebook Integration FB Cookie
function get_facebook_cookie($app_id, $application_secret) {
  $args = array();
  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
  ksort($args);
  $payload = '';
  foreach ($args as $key => $value) {
    if ($key != 'sig') {
      $payload .= $key . '=' . $value;
    }
  }
  if (md5($payload . $application_secret) != $args['sig']) {
    return null;
  }
  return $args;
}
function loadFBjs(){
$js =  "<div id=\"fb-root\"></div>
    <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '".FACEBOOK_APP_ID."', // App ID
              status     : true, // check login status
              cookie     : true, // enable cookies to allow the server to access the session
	xfbml: true,
	oauth: true
            });
        FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });
          };
          // Load the SDK Asynchronously
          (function(d){
             var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement('script'); js.id = id; js.async = true;
             js.src = \"//connect.facebook.net/en_US/all.js\";
             ref.parentNode.insertBefore(js, ref);
           }(document));
        </script>";
		return $js;
		
}


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
?>