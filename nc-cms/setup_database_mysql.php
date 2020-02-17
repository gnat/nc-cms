<?php

// Setup MySQL Database.
// This file is only needed if you intend to use nc-cms database support.
// It may be safely removed after database support is set up.

error_reporting(0);
require('./config.php');
require('./system/lib/NCUtility.class.php');

$nc_already_setup = false;
$nc_db_fail = false;
$nc_report_error = "";
$nc_report_tip = "";

// Do database tests.	
	if (!function_exists("mysqli_connect"))
	{
		$nc_report_error = "MySQL support in PHP environment not found. Cannot continue with setup.";
		$nc_db_fail = true;
	}
	else
	{
	// Test.
	$nc_db_link = @mysqli_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
	if (mysqli_connect_errno())
	{
		$nc_report_error = "MySQL reported: ".mysqli_connect_error();
		$nc_report_tip = "Double check to make sure that your database settings (host, user, password) found in <strong>/nc-cms/config.php</strong> are complete and correct. It's also possible that your host's database server may be down. If this is the case, contact your host.";
		$nc_db_fail = true;
	}
	else if(!mysqli_select_db( $nc_db_link, NC_DB_DATABASE))
	{
		$nc_report_error = "MySQL reported: ".mysqli_error($nc_db_link);
		$nc_report_tip = "We were able to connect to the database server, but could not select your specified database. Double check to make sure that database settings (user, password, database name)  found in <strong>/nc-cms/config.php</strong> are complete and correct. Double check to make sure your specified user and database exists on the database server. If you are having troubles setting up your database, you should contact your host.";
		$nc_db_fail = true;
	}
	else if(mysqli_query($nc_db_link, "SELECT name FROM ".NC_DB_PREFIX."content"))
	{
		$nc_already_setup = true;
	}
	if($nc_db_link)
		mysqli_close($nc_db_link);
}

$output = "";

// Determine action to take.
$action = '';
if(isset($_GET['action']))
	$action = $_GET['action'];
	
// Good to install.
if($action == 'install' && $nc_db_fail == false)
{
	// Table creation code.
	$nc_db_query = "CREATE TABLE `".NC_DB_DATABASE."`.`".NC_DB_PREFIX."content` (
					  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
					  `name` VARCHAR(80),
					  `content` TEXT,
					  PRIMARY KEY (`id`)
					)
					ENGINE = InnoDB
					CHARACTER SET utf8 COLLATE utf8_general_ci;";

	$nc_db_link = mysqli_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
	
	if($nc_db_link)
	{
		if(mysqli_select_db($nc_db_link, NC_DB_DATABASE))
		{
			if(!mysqli_query($nc_db_link, $nc_db_query)) // Check for query errors.
				$output .= NCUtility::Error("MySQL reported: ".mysqli_error($nc_db_link));
		}
		else
			$output .= NCUtility::Error("MySQL reported: ".mysqli_error($nc_db_link));
	}
	else
		$output .= NCUtility::Error("MySQL reported: ".mysqli_error($nc_db_link));

	if($nc_db_link)
		mysqli_close($nc_db_link); // Close connection.
	
	// Refresh page if no errors were reported.
	if($output == "")
	{
		$location = NC_CMS_URL.'/'.basename(__FILE__); // Load default installer page.
		header('Location: '.$location);
	}
}
else // DEFAULT: Retrieve login page.
{
	$output .= '<p>Welcome to the nc-cms MySQL database setup. This script will install the appropriate database tables for use with nc-cms.</p>';
	
	if($nc_db_fail)
	{
		if($nc_report_error != "")
			$output .= NCUtility::Error($nc_report_error);
		if($nc_report_tip != "")
			$output .= NCUtility::Tip($nc_report_tip);
			
		$output .= '<p>You may delete this file if you do not plan to use nc-cms\'s database support.</p>';
	}
	else
	{
		if($nc_already_setup)
			$output .= '<h2><strong>Database Setup Complete!</strong></h2> <p>The required nc-cms tables have already been setup in your database. You may now delete this file if you wish.</p>';
		else
		{
			$output .= '<p style="width: 200px; margin: 0 auto; text-align: center;"><span class="button"><a href="?action=install" ><span class="icon icon_setup_database" > Setup MySQL Database</span></a></span></p><br />';
			$output .= '<p>You may delete this file if you do not plan to use nc-cms\'s database support.</p>';
		}
	}
}
?>
	
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>nc-cms | MySQL Database Setup</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<meta name="robots" content="noindex" />
		<meta name="robots" content="nofollow" />
		<link rel="stylesheet" type="text/css" media="screen" href="system/css/setup.css"/>
	</head>
	<body>
		<div id="wrapper">
			<div id="login">
				<h1><a href="https://github.com/gnat/nc-cms" title="Powered by nc-cms" target="_blank"><?php echo NC_WEBSITE_NAME; ?></a>
				</h1>
				<div style="padding: 0 14px 0 14px;">
				
				<?php echo $output; ?>
				
				<br />
				</div>
				<div class="footer"></div>
			</div>
		</div>
	</body>
</html>
