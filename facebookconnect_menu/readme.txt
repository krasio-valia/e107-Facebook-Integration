+------------------------------------------------------------------------------+
|     e107 Facebook Connect Integration
|
|
|	Support: jdgeraci@gmail.com
|   Website: http://www.facebook.com/pages/Kool2Zero-Webs/117894404904876
|	(I'm not sure how much help I can be.  Still a n00b)
|	For the e107 website system visit http://e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+------------------------------------------------------------------------------+

First, you will need to get an API key from Facebook:
http://developers.facebook.com/setup/

Then, fill out config.php with your API information and database info.

Install the plugin with plugin manager enable the menu, and you are ready to rock.

If you have previously installed, uninstall the plugin and reinstall it again to clear settings.  If you are not using the
Media Gallery feature, feel free to disable the menu link, or get rid of it completely in plugin.php before you upload.

Change Log:
+------------------------------------------------------------------------------+
|     v 0.1
+------------------------------------------------------------------------------+
.:. Basic Integration - Creates table for FB Connect, Link Accounts, Create Account with FB
+------------------------------------------------------------------------------+
|     v 0.2
+------------------------------------------------------------------------------+
.:. Added Admin Control Panel
	.:. Change Configuration Settings
	.:. View Users Who Have Activated FB Connect
	.:. View Facebook API status via RSS Feed

+------------------------------------------------------------------------------+
|     v 0.42
+------------------------------------------------------------------------------+
.:. Added Facebook Albums
	.:. Show/Hide Facebook Albums based on User ID on your site!
	.:. Gallery (nice effects thanks to High slide)

+------------------------------------------------------------------------------+
|     v 0.5
+------------------------------------------------------------------------------+
.:. Now uses Graph API
	.:. The new version of the Facebook connect interface is faster and doesn't
		require you to download the API because it is now hosted by Facebook

.:. Option not to create account with Facebook
	.:. This plugin has the ability to either create a new account with Facebook
		credentials or it can link an account previously created with the e107
		website system.  If the account creation variable is set to "N" in 
		config.php, the user will be asked to create an account through the e107
		system, where they can then link the accounts.  This is good if you wish
		to verify users before they can become members.
	

+------------------------------------------------------------------------------+
|     Features/Instructions
+------------------------------------------------------------------------------+
.:.Upon first run on admin_config.php, it will create all necessary tables to run the plugin.  It will check each subsequent
   visit to ensure that all relevant tables are created.  If you delete a table, it will recreate it on its next run.

.:.You are able to edit your config preferences from admin_config by clicking, "Edit Configuration Settings", you can then
   enter/change your Facebook Connect API keys, your User ID for Media Gallery, and decide if you would like to allow this plugin
   to create new users
	.:.Media Gallery
		.:. You can use a personal Facebook ID, which is viewable from the Developer workbench on Facebook.
		.:. You can use a "fan" page id, which is viewable in the link of the fan page
		.:. Eg http://www.facebook.com/pages/**REGION**/**PAGE NAME**/((ID))
		.:. More information about this feature in another section.

.:.Show Users Using Facebook Connect is very aptly named.  If a user on your site has linked their e107 account with their
   Facebook account, they will show up.  (Whether it's a feature or a "creep"-ture is up for debate, but it allows you to
   get a handle of who is using the plugin.

.:.I was having trouble using the Facebook API on one of my webhosts and I wasn't sure whether it was due to the API or
   due to the web host.  So, I just decided it would be a good idea to view the API RSS feed from the Control Panel.  If you have
   no use for it, or think of it as bloat, by all means edit the code not to use it.

.:.Media Gallery
	.:. There is quite a latency generating all the albums and photos inside those albums using the Facebook API every time.
	    Therefore, you will need to "Cache the Album Information Locally".  This will run through all of the Albums and Photos a
	    single time and store the information into your local database.  This decreases the time for a user to load the album.
	.:. Once the ALL albums are generated, you will have to choose which albums you would like to display on the site.  You can select
	    individually or you can select all.
	.:. Currently, you can only select to hide and show albums and not individual pictures.  I will implement the ability to hide show
	    individual pictures at a later date.  If you would like to do it manually, you can go into the e107_facebookpics table in the DB and set
	    "Display" equal to "N".
	.:. The High Slide gallery provides a lot of versatility.  If you would like to change the layout, the settings are in showalbum.php around line 24.
	    You can find examples at http://highslide.com/

Comments/Suggestions are welcome.  This is pretty rudimentary, if you have a more efficient way to handle some of the
tasks feel free to let me know!

If you are receiving errors from Facebook, make sure your server has CURL turned on, and PHP is > version 5.2 .

Thanks!

Jim