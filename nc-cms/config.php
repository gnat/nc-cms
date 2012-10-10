<?php

/*
	GENERAL SETTINGS
	Set all of these before using nc-cms.
*/
define('NC_LOGIN_USER', "admin");						// Username for content administrator
define('NC_LOGIN_PASSWORD', "admin");					// Password for content administrator
define('NC_WEBSITE_NAME', "My Website");				// Your website's name
define('NC_WEBSITE_URL', "http://localhost");			// Your website's internet address. 
define('NC_CMS_URL', "http://localhost/nc-cms");		// Your website's internet nc-cms directory address.
define('NC_LANGUAGE', "english");						// Language pack to use. Currently only english is available.

/*
	DATABASE CONNECTIVITY SETTINGS
	Modify these settings if you wish to use nc-cms's database support.
*/
define('NC_USE_DB', false);				// Set to true if you wish to use database support, set to false to disable. Remember to configure variables below if database support is being used.
define('NC_DB_HOST', "");		// The hostname of your database server.
define('NC_DB_USER', "");			// The username used to connect to the database.
define('NC_DB_PASSWORD', "");			// The password used to connect to the database.
define('NC_DB_DATABASE', "");	// The name of the database you want to connect to.
define('NC_DB_PREFIX', "");				// Specify a prefix for the database tables here if you need one.
