<?php

// Bootstrap file for nc-cms. 
// Embed nc-cms into your website by including this file.
define('NC_VERSION', '3.3');
define('NC_UPLOAD_DIRECTORY', './content/upload/');

// Error Reporting Level.
// By default nc-cms runs with error reporting set to E_ALL & ~E_DEPRECATED.  
// For security reasons you are encouraged to change this from E_ALL to E_ERROR when your site goes live.
// For more info visit:  http://www.php.net/error_reporting
error_reporting(E_ALL & ~E_DEPRECATED);

// Feel free to use NC_BASEPATH in your modifications as a filesystem anchor.
define('NC_BASEPATH', realpath(dirname(__FILE__)));

// Include the nc-cms system.
require(NC_BASEPATH.'/../config.php');
require(NC_BASEPATH.'/lib/NCUtility.class.php');
require(NC_BASEPATH.'/lib/NCLogin.class.php');
require(NC_BASEPATH.'/lib/NCCms.class.php');

// Select and load language pack. 
// Currently only english is available. More can be added here.
if(NC_LANGUAGE == 'english')
	require(NC_BASEPATH.'/language/english.php');
else
	require(NC_BASEPATH.'/language/blank.php');
