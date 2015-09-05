<?php

// General Settings. Set all before using nc-cms.

define('NC_LOGIN_USER', "admin");  // Username for content editor.
define('NC_LOGIN_PASSWORD', "admin");  // Password for content editor.
define('NC_WEBSITE_NAME', "My Website");  // Your website name.
define('NC_WEBSITE_URL', "http://localhost");  // Your website internet address. 
define('NC_CMS_URL', "http://localhost/nc-cms");  // Your website internet nc-cms directory address.
define('NC_LANGUAGE', "english");  // Language pack to use. Currently only english is available.

// Database Settings. Modify these settings if you wish to use nc-cms's database support.

define('NC_USE_DB', false);  // Set to true if you wish to use database support, set to false to disable.
define('NC_DB_HOST', "");  // Hostname for your database server.
define('NC_DB_USER', "");  // Username for database account.
define('NC_DB_PASSWORD', "");  // Password for database account.
define('NC_DB_DATABASE', "");  // Name of database you want to connect to.
define('NC_DB_PREFIX', "");  // Optional prefix for database tables.
