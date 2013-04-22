<?php
// ***************************************************************
// *
// *		Plugin		:	Facebook Connect (e107 v7+)
// *
// *		Author		:	Jim Geraci (c) 2010
// *
// *		Web site	:	http://www.facebook.com/pages/Kool2Zero-Webs/117894404904876
// *
// *		Description	:	Install plugin
// *
// *		Version		:	0.5
// *
// *		Date		:	25 January 2010 - First Version
// *		Revisions		02 February 2010 - Added Media Gallery
// *						30 April 2010 - Updated to use Graph API
// *
// ***************************************************************
/*
+---------------------------------------------------------------+
|        Facebook Connect Plugin
|
|        This module will allow users to login to your
|		 e107 powered website by using their Facebook
|		 credentials.
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
|
+---------------------------------------------------------------+
*/
// Plugin info -------------------------------------------------------------------------------------------------------
$eplug_name = "Facebook Connect Menu";
$eplug_version = "0.6";
$eplug_author = "Kool2Zero";
$eplug_url = "";
$eplug_email = "jdgeraci@gmail.com";
$eplug_description = "This module will allow users to login to/create accounts on your e107 website";
$eplug_compatible = "e107v7";
$eplug_readme = ""; // leave blank if no readme file
$eplug_compliant = true;
// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder = "facebookconnect_menu";
// Name of menu item for plugin ----------------------------------------------------------------------------------
$eplug_menu_name = "Facebook Connect";
// Name of the admin configuration file --------------------------------------------------------------------------
$eplug_conffile = "admin_config.php";
// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon = $eplug_folder."/images/logo32.png";
$eplug_icon_small = $eplug_folder."/images/logo16.png";
$eplug_caption = "Facebook Connect";
// List of preferences -----------------------------------------------------------------------------------------------
// List of table names -----------------------------------------------------------------------------------------------
$eplug_table_names = "";
// List of sql requests to create tables -----------------------------------------------------------------------------
$eplug_tables = "";

// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done = "Sucessfully installed";
// upgrading ... //
$upgrade_add_prefs = "";

$upgrade_remove_prefs = "";

$upgrade_alter_tables = "";

$eplug_upgrade_done = "Upgrade Done!";

?>