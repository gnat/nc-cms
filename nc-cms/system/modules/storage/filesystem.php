<?php
		
//
//	nc_save_content()
//
//	Check content file to see if it exists, then save data.
//
function nc_save_content ($name, $data)
{	
	// Save away.
	$path = NC_BASEPATH.'/../content/'.$name;
	$fh = fopen('content/'.$name, 'w') or die(nc_report_error("Could not open file: ".$name.". Make sure that this server has read and write permissions  the /nc-cms/content folder."));
	fwrite($fh, nc_remove_magic_quotes($data));
	fclose($fh);
}
//
//	nc_load_create_content()
//
//	Check content file to see if it exists, if not, create it. Open it and return data.
//
function nc_load_create_content ($name)
{	
	// Load content if file exists
	$path = NC_BASEPATH.'/../content/'.$name;
	nc_check_content ($path, 'Edit me! ('.$name.')'); // Make sure content file exists
	$fh = fopen($path, 'r') or die(nc_report_error("Could not find file: ".$path));
	$data = fread($fh, filesize($path)) or die(nc_report_error("Could not read file: ".$path.". Make sure that this server has read and write permissions to the /nc-cms/content folder."));
	fclose($fh);

	return $data;
}
//
//	nc_check_content - USED INTERNALLY
//
//	Check content file to see if it exists. And if it doesn't, create it. $path contains the file path, $default contains the default text to go in the file if it is new.
//
function nc_check_content ($path, $default)
{
	// If file doesn't exist yet or is of 0 length, create and write something in it.
	if (!file_exists($path) || !filesize($path)) 
	{
	   $fh = fopen($path, 'w') or die(nc_report_error("Could not write file: ".basename($path).". Make sure that this server has read and write permissions to the /nc-cms/content folder."));
	   fwrite($fh, $default) or die(nc_report_error("Could not write file: ".basename($path).". Make sure that this server has read and write permissions to the /nc-cms/content folder."));
	   fclose($fh);
	}
	
	clearstatcache(); // Clear status cache (so filesize() will do its work again)
}