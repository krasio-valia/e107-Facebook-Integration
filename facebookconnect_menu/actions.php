<?php
/*
+------------------------------------------------------------------------------+
|     e107 Facebook Connect Integration
|
|	File: 			actions.php
|	Description:	This file takes care of the necessary login functions and 
|					returns the user back to his/her referring page.
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
//Include class2.php
require_once("../../class2.php");
require_once("config.php");
require_once("functions.php");
require_once("facebook.php");
 if (!$facebook) {
$facebook= new Facebook($config);
 }
 $access_token = $facebook->getAccessToken();
$uid=$facebook->getUser();


/*-------------------------------
|Check page for queries
+-------------------------------*/
//Check for Referring Page
$refer = urldecode($_GET['refer']);
//Check for FB Login to Link Accounts
$fblinkaccount = @$_GET['linkaccount'];
if ($fblinkaccount == "yes"){
$updateuser = updateUser($currentUser['user_id'],$uid);
IF ($updateuser === TRUE){
echo  "<script type=\"text/javascript\">window.top.location= '".$refer."'; </script>";
}
Else {
echo "<script type=\"text/javascript\">window.top.location= '".$_SESSION['refer']."?error=fbidexists'; </script>";	
}
}
$fblogin = @$_GET['login'];
if ($fblogin == "yes"){
$createuser = createUserfromFacebook($uid);
if ($createuser == "created"){
echo  "<script type=\"text/javascript\">window.top.location= '".$_SESSION['refer']."'; </script>";
}
if ($createuser == "error"){
echo "<script type=\"text/javascript\">window.top.location= '".$_SESSION['refer']."?error=create'; </script>";
}
if ($createuser == "login"){
echo  "<script type=\"text/javascript\">window.top.location= '".$_SESSION['refer']."'; </script>";
}
if ($createuser == "nouser"){
echo "<script type=\"text/javascript\">window.top.location= '".$_SESSION['refer']."?error=nouser'; </script>";
}
}
$js = loadFBjs();
echo $js;
?>