<?php
/*
+------------------------------------------------------------------------------+
|     e107 Facebook Connect Integration
|
|	File: 			install.php
|	Description:	This file just ensures that the required tables exist
|
|	Support: jdgeraci@gmail.com
|	(I'm not sure how much help I can be.  Still a n00b
|	For the e107 website system visit http://e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+------------------------------------------------------------------------------*/
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once('../../class2.php');

if (!ADMIN) {
	header("location:../../index.php");
	 exit;
}
require_once(HEADERF);
$caption = "Install Facebook Connect Integration";
//Tables to Look For
$FBConnect = MPREFIX."facebookconnect";
//Check for Main Facebook Connect Table
if( mysql_num_rows( mysql_query("SHOW TABLES LIKE '".$FBConnect."'"))){
$text .= "Table $FBConnect already exists.<br>";
}
else{
$create = "CREATE TABLE ".MPREFIX."facebookconnect (
	e107_id int(10) NOT NULL,
	fb_id VARCHAR( 60 ) NOT NULL ,
PRIMARY KEY ( e107_id)
	);
";
mysql_query($create) or die('Could not create table: '.$create.'<br>SQL Returned Error: '.mysql_error());
$text .= "Table $FBConnect created.<br>";
}
$text .= "<a href=\"".e_ADMIN."menus.php\">Click Here To Activate Menu (connect)</a>";
$ns->tablerender($caption, $text);
require_once(FOOTERF);
?>

