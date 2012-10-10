<?php  if (!defined('NC_BASEPATH')) exit('No direct script access allowed'); 

// Use filesystem or database content managment functions?
if(NC_USE_DB)
{
	$nc_db_fail = false;
	
	// Test for database support
	if (!function_exists("mysql_connect"))
	{
		nc_report_error("MySQL support in PHP environment not found. You can switch to filesystem storage by turning off database support in your /nc-cms/config.php file.");
		$nc_db_fail = true;
	}
	else
	{
		// Support exists, test database.
		$nc_db_link = @mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
		if(!$nc_db_link)
		{
			nc_report_error("MySQL reported: ".mysql_error());
			nc_report_tip("Double check to make sure that your database settings (host, user, password) found in <strong>/nc-cms/config.php</strong> are complete and correct. It's also possible that your host's database server may be down. If this is the case, contact your host.");
			$nc_db_fail = true;
		}
		else if(!mysql_select_db(NC_DB_DATABASE, $nc_db_link))
		{
			nc_report_error("MySQL reported: ".mysql_error());
			nc_report_tip("We were able to connect to the database server, but could not select your specified database. Double check to make sure that database settings (user, password, database name)  found in <strong>/nc-cms/config.php</strong> are complete and correct. Double check to make sure your specified user and database exists on the database server. If you are having troubles setting up your database, you should contact your host.");
			$nc_db_fail = true;
		}
		else if(!mysql_query("SELECT name FROM ".NC_DB_PREFIX."content"))
		{
			nc_report_error("MySQL reported: ".mysql_error());
			nc_report_tip("We were able to connect to the database server and select your specified database, but could not find the appropriate nc-cms tables. Double check your database prefix setting (NC_DB_PREFIX) in your /nc-cms/config.php file.");
			nc_report_tip("If the tables do not exsist, run the nc-cms MySQL database setup script found at /nc-cms/setup_database_mysql.php in order to create them.");
			$nc_db_fail = true;	
		}
		
		if($nc_db_link)
			mysql_close($nc_db_link);
	}
	
	// Exit if any tests failed.
	if($nc_db_fail)
	{
		exit();
	}
			
	require('mysql.php');
}
else
	require('filesystem.php');