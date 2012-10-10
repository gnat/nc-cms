<?php
/* 
	MAIN CONTROLLER
	For associated views, check out nc-cms/system/views
*/

// Make sure we have nc-cms initialized.
require('system/start.php');

// Determine action to take
$action = '';
if(isset($_GET['action']))
	$action = $_GET['action'];

// Take action!
if($action == 'logout')
{
	// Log out if user is in session
	if($nc_login_state)
		$nc_login->logout();
	
	// Load previous page as view
	$location = $_SERVER['HTTP_REFERER'];
	$location = substr($_SERVER['HTTP_REFERER'], 0, strrpos($location, "/"));
	header('Location: '.$location);
} 
else if($action == 'edit_string')
{
	nc_check_user();
	$name = '';
	$data = '';
	
	if(isset($_GET['name'])) // Required GET data
	{
		$name = basename($_GET['name']); // strip paths (protection from filesystem exploits)
		$data = nc_load_create_content($name);
	}
	else
		nc_boot_user(); // Posible hacking attempt
		
	include('system/views/edit_string.php'); // Load string editor view
}
else if($action == 'edit_html')
{
	nc_check_user();
	$name = '';
	$data = '';
	
	if(isset($_GET['name'])) // Required GET data
	{
		$name = basename($_GET['name']); // strip paths (protection from filesystem exploits)
		$data = nc_load_create_content($name);
	}
	else
		nc_boot_user(); // Posible hacking attempt
		
	include('system/views/edit_html.php'); // Load html editor view
}
else if($action == 'save')
{
	nc_check_user();

	if(isset($_GET['ref'])) // Required GET data
	{
		if(isset($_POST['name']) && isset($_POST['editordata'])) // Required POST data	
		{
			nc_save_content($_POST['name'], $_POST['editordata']);
		}
		header('Location: '.$_GET['ref']);
		exit();
	}
	else
		nc_boot_user();
}
else if($action == 'file_manager')
{
	nc_check_user();
	$status_message = '';
	include('system/views/file_manager.php'); // Load file manager view
}
else if($action == 'file_manager_upload')
{
	nc_check_user();
	$status_message = '';
	
	if ($_FILES['file']['error'] > 0) // Oh teh noes! There was trouble uploading! 
	{
		if($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE)
			$status_message = 'File is too large to upload.<br />Increase upload_max_filesize in your server\'s php.ini file.';
		if($_FILES['file']['error'] ==  UPLOAD_ERR_NO_FILE)
			$status_message = 'Nothing selected to upload.';
		else
			$status_message = 'Error uploading file. Error code: '. $_FILES['file']["error"] .'<br />See PHP manual for file upload error codes.';
	}
	else
	{
		$replacing_file = false;
		
		if(file_exists(NC_UPLOAD_DIRECTORY.$_FILES['file']['name']))
			$replacing_file = true;
			
		move_uploaded_file($_FILES['file']['tmp_name'], NC_UPLOAD_DIRECTORY.$_FILES['file']['name']); // Write the file
		
		if($replacing_file)
			$status_message = 'File successfully replaced!<br /><strong>'.$_FILES['file']['name'].' ('.nc_return_size_string($_FILES['file']['size']).')</strong>';
		else
			$status_message = 'File successfully uploaded!<br /><strong>'.$_FILES['file']['name'].' ('.nc_return_size_string($_FILES['file']['size']).')</strong>';
	}
	
	include('system/views/file_manager.php'); // Load file manager view
}
else if($action == 'file_manager_remove')
{
	nc_check_user();
	$status_message = '';
	$file = '';
	if(isset($_GET['file']))
		$file = $_GET['file'];
	
	basename($file);
	
	if(is_file(NC_UPLOAD_DIRECTORY.$file)) 
	{
		if(unlink(NC_UPLOAD_DIRECTORY.$file))
			$status_message = 'File removed successfully:<br /><strong>'.$file.'</strong>';
		else
			$status_message = 'Could not remove file:<br /><strong>'.$file.'</strong>';
	}
	else
		$status_message = 'File not removed. File does not exist:<br /><strong>'.$file.'</strong>';
	
	include('system/views/file_manager.php'); // Load file manager view
}
else // DEFAULT: Retrieve login page
{
	include('system/views/login.php'); // Load login view
}
?>