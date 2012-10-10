<?php
//
//	nc_get_site_url()
//
//	Prepares and returns a clean version of the website URL as defined in config.php
//
function nc_get_site_url()
{
	$url = trim(NC_WEBSITE_URL);
	
	// Make sure http:// is there
	if(stripos($url, 'http://') != 0 && stripos($url, 'https://') != 0)
		$url = 'http://'.$url;
	
	// Strip any trailing slash
	if(strrpos($url, '/') == strlen($url)-1)
		$url = substr($url, 0, strlen($url)-1);
	
	return $url;
}
//
//	nc_get_cms_url()
//
//	Prepares and returns a clean version of the website cms URL as defined in config.php
//
function nc_get_cms_url()
{
	$url = trim(NC_CMS_URL);
	
	// Make sure http:// is there
	if(stripos($url, 'http://') != 0 && stripos($url, 'https://') != 0)
		$url = 'http://'.$url;
	
	// Strip any trailing slash
	if(strrpos($url, '/') == strlen($url)-1)
		$url = substr($url, 0, strlen($url)-1);
		
	return $url;
}
//
//	nc_get_cms_path_relative()
//
//	This function returns the path to nc-cms relative to the website URL. This is to be used with file and image links in order to keep them functional in the event that the user decides to switch domain names. 
//	(ex 1: http://www.example.com/nc-cms will be converted to /nc-cms)
//	(ex 2: http://www.example.com/~blah/nc-cms will be converted to /~blah/nc-cms)
//
function nc_get_cms_path_relative()
{
	$url = trim(NC_CMS_URL);
	
	// Remove any protocol information
	$url = str_replace(array('http://', 'https://'), '', $url);
	
	// Grab everything after the first '/', including the '/'. 
	$url = stristr($url, '/');
	
	// Strip any trailing slash
	if(strrpos($url, '/') == strlen($url)-1)
		$url = substr($url, 0, strlen($url)-1);
		
	return $url;
}
//
//	nc_boot_user()
//
//	Ends the script, and returns the user to the site url.
//
function nc_boot_user()
{
	header("Location: ".nc_get_site_url());
	exit();
}
//
//	nc_check_user()
//
//	Disallow unauthorized access. $nc_login_state must be set before calling this function.
//
function nc_check_user()
{
	global $nc_login_state;
	
	if(!$nc_login_state)
		nc_boot_user();
}
//
//	nc_report_error()
//
//	Special display function for errors. If $return is true, return the message instead of echoing
//
function nc_report_error($message, $return = false)
{	
	$message = '<p><span style="color: #AA0000; font-weight: bold;">ERROR:</span> '.$message.'</p>';
	
	if($return)
		return $message;
	else
		echo $message;
}
//
//	nc_report_tip()
//
//	Special display function for errors. If $return is true, return the message instead of echoing
//
function nc_report_tip($message,  $return = false)
{	
	$message = '<p><span style="color: #0000AA; font-weight: bold;">TIP:</span> '.$message.'</p>';
	
	if($return)
		return $message;
	else
		echo $message;
}
//
//	nc_get_load_time()
//
//	For measuring performance. Returns the amount of time passed from first calling start.php.
//
function nc_get_load_time()
{	
	global $nc_load_time_start;
	
	$current_time = explode(" ",  microtime());
	$current_time = $current_time[1] + $current_time[0];

	return ($current_time - $nc_load_time_start);
}
//
//	nc_return_size_string()
//
//	Pass a byte value to convert to string equivalent rounded  (Example: 2097152 == '2098 KB')
//
function nc_return_size_string($val) 
{
	if($val > 1048576)
		 return round(($val/1048576), 2).' MB';
	if($val > 1024)
		 return round((integer)($val/1024), 2).' KB';
	
    return ($val).' Bytes';
}
//
//	nc_return_bytes()
//
//	Pass ini_get("upload_max_filesize") string to get the value in bytes. (Example: '2M' == 2097152)
//
function nc_return_bytes($val) 
{
    $val = trim($val);
    $last = strtolower(substr($val, -1));
	
	if($last == 'g')
		$val = $val*1024*1024*1024;
	if($last == 'm')
		$val = $val*1024*1024;
	if($last == 'k')
		$val = $val*1024;
		
    return $val;
}
//
//	nc_get_referrer
//
//	Get the referring page. If one isn't found, go to NC_WEBSITE_URL
//
function nc_get_referrer ()
{
	$output = '';
	
	if(isset($_SERVER['HTTP_REFERER']))
		$output = $_SERVER['HTTP_REFERER'];
	else
		$output = nc_get_site_url().'/';

	return $output;
}
//
//	nc_remove_magic_quotes
//
//	If PHP magic quotes are on, take that into account and strip the extra slashes.
//
function nc_remove_magic_quotes ($string)
{
	if(get_magic_quotes_gpc())
		$string = stripslashes($string);
		
	return $string;
}