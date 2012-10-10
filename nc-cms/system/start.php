<?php

define('NC_VERSION', '2.2.0');
define('NC_UPLOAD_DIRECTORY', './content/upload/');

/*
	PHP ERROR REPORTING LEVEL
	By default nc-cms runs with error reporting set to ALL.  For security reasons you are encouraged to change this from E_ALL to E_ERROR when your site goes live.
	For more info visit:  http://www.php.net/error_reporting
*/
error_reporting(E_ALL);

/*
	SET UP PAGE LOADING TIMER
	For measuring performance. Call nc_get_load_time() after start.php has run to return the seconds of time passed from this point.
*/
$time = explode(" ", microtime());
$nc_load_time_start = $time[1] + $time[0];

/*
	NC_BASEPATH
	This helper variable can be used anywhere in your PHP code when you need the nc-cms/system directory on the filesystem.
*/	
define('NC_BASEPATH', realpath(dirname(__FILE__)));

require(NC_BASEPATH.'/../config.php');
require(NC_BASEPATH.'/modules/utility.php');
require(NC_BASEPATH.'/modules/storage/general.php');
require(NC_BASEPATH.'/modules/login.php');
require(NC_BASEPATH.'/modules/cms.php');

// Select and load language pack. Currently only english is available.
if(NC_LANGUAGE == 'english')
	require(NC_BASEPATH.'/language/english.php');
else
	require(NC_BASEPATH.'/language/blank.php');
	
// Make sure that NC_CMS_URL in config.php is set correctly.
if(nc_get_cms_path_relative() == '')
{
	nc_report_error("NC_CMS_URL in config.php is not set correctly.");
	exit();
}

// Initialize login system, and check to see if user is logged in.
$nc_login = new ncLogin();
$nc_login_state = $nc_login->check_login();
$nc_page_title = '';
