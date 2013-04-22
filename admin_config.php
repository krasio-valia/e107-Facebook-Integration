<?php
/*
+------------------------------------------------------------------------------+
|     e107 Facebook Connect Integration
|
|	File: 			admin_config.php
|	Description:	This file will allow the administrator to modify certain
|					of the Facebook Connnect Integration
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
require_once("../../class2.php");
//Check for Admin Status
require_once(e_ADMIN."auth.php");
if (!ADMIN) {
	header("location:../../index.php");
	 exit;
}
//Check to make sure tables installed
//Tables to Look For
$FBConnect = MPREFIX."facebookconnect";
$FBAlbum = MPREFIX."facebook_albums";
$FBPictures = MPREFIX."facebookpics";
$text="";
//Check for Main Facebook Connect Table
if( mysql_num_rows( mysql_query("SHOW TABLES LIKE '".$FBConnect."'"))){
//
}
else{
$create = "CREATE TABLE ".MPREFIX."facebookconnect (
	e107_id int(10) NOT NULL,
	fb_id VARCHAR( 60 ) NOT NULL ,
PRIMARY KEY ( e107_id)
	) TYPE=MyISAM;
";
mysql_query($create) or die('Could not create table: '.$create.'<br>SQL Returned Error: '.mysql_error());
$text .= "Table $FBConnect created.<br>";
}

//Set Header
$text .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"> ";
$text .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:fb=\"http://www.facebook.com/2008/fbml\">";
$text .= "<head>";
$text .= "<script type=\"text/javascript\">";
$text .= "FB.Connect.ifUserConnected(set_cookies_and_refresh());";
$text .= "</script>";
$text .= "<link type=\"text/css\" href=\"style.css\" rel=\"stylesheet\" />";
$text .= "</head><body>";
$text .= "<center>";



require_once("config.php");
require_once("functions.php");
//Constants
$mainpage = "<a href=\"".e_SELF."\">Return to Main Page</a>";
$action = @$_REQUEST['action'];
//Show Facebook Users
if ($action == "facebookusers"){
$caption = "Show Facebook Users";
$text .= "<table border=\"0\" width=\"100%\" style=\"margin-left: auto; margin-right: auto;\">";
$facebookusers = getFacebookUsers();
$num_rows = mysql_num_rows($facebookusers);
//If a user could not be found with a Facebook ID
if ($num_rows == 0){
$text .= "<tr><td colpan=\"2\"><center>No Facebook Users!</center></td></tr>";
}
else {
$text .= "<tr><td colspan=\"2\"><center>Currently, there are $num_rows Facebook Connect Users</center></td></tr>";
while ($row = mysql_fetch_array($facebookusers, MYSQL_BOTH)) {
$text .= showFacebookUsers($row['e107_id'],$row['fb_id']);
}
$text .= "</table>";
$text .= $mainpage;
}
}
//Main Menu
if (!$action){
$caption = "Work with Facebook Connect";
$text .= "<center>";
$text .= "<a href=\"".e_SELF."?action=facebookusers\">Show users who use Facebook Connect</a><hr>";
$text .= "</center>";
}
$text .= loadFBjs();
$text .= "</center>";
$text .= "</body></html>";
//Spit Out Table
$ns->tablerender($caption, $text);

?>