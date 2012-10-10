<?php 		
//
//	nc_save_content()
//
//	Check if database content exists, then save data.
//
function nc_save_content ($name, $data)
{	
	nc_check_content ($name, "Edit Me!"); // Make sure database entry exists

	$nc_db_link = mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
	$nc_db_link = nc_db_link($nc_db_link, NC_DB_DATABASE);
	$nc_db_result = mysql_query("UPDATE ".NC_DB_PREFIX."content SET content='".nc_db_escape($data)."' WHERE name='".$name."'", $nc_db_link);
	
	if(!$nc_db_result) // Check for query errors
	{
		nc_report_error("MySQL reported: ".mysql_error());
		exit();
	}
	
	mysql_close($nc_db_link); // Close connection
}
//
//	nc_load_create_content()
//
//	Check if database content exists, if not, create it. Open it and return data.
//
function nc_load_create_content ($name)
{	
	nc_check_content ($name, "Edit Me! (".$name.")"); // Make sure database entry exists

	$nc_db_link = mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
	$nc_db_link = nc_db_link($nc_db_link, NC_DB_DATABASE);
	$nc_db_result = mysql_query("SELECT name,content FROM ".NC_DB_PREFIX."content WHERE name='".$name."'", $nc_db_link);
	
	if(!$nc_db_result) // Check for query errors
	{
		nc_report_error("MySQL reported: ".mysql_error());
		exit();
	}
		
	$row = mysql_fetch_row($nc_db_result);
	$data = $row[1];
	
	mysql_close($nc_db_link); // Close connection

	return $data;
}
//
//	nc_check_content - USED INTERNALLY
//
//	Check if database content exists. And if it doesn't, create it. $name contains the name field, $default contains the default text to go in the content field if it is new.
//
function nc_check_content ($name, $default)
{
	$create_entry = false;

	$nc_db_link = mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
	$nc_db_link = nc_db_link($nc_db_link, NC_DB_DATABASE);
	$nc_db_result = mysql_query("SELECT name FROM ".NC_DB_PREFIX."content WHERE name='".$name."'", $nc_db_link);
	
	if(!$nc_db_result) // Check for query errors
	{
		nc_report_error("MySQL reported: ".mysql_error());
		exit();
	}
	
	// See if a row exsists
	if(mysql_num_rows($nc_db_result) < 1)
		$create_entry = true;
	
	mysql_close($nc_db_link); // Close connection
	
	if ($create_entry) // No entries existed. Create one instead.
	{
		$nc_db_link = mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
		$nc_db_link = nc_db_link($nc_db_link, NC_DB_DATABASE);
		$nc_db_result = mysql_query("INSERT INTO ".NC_DB_PREFIX."content (name,content) VALUES ('".$name."','".$default."')");
		
		if(!$nc_db_result) // Check for query errors
		{
			nc_report_error("MySQL reported: ".mysql_error());
			exit();
		}
		
		mysql_close($nc_db_link); // Close connection
	}
}
//
//	nc_db_link - USED INTERNALLY
//
//	Checks the validity of the mysql link. Selects the database. Returns the db link, presents any errors if any are found.
//
function nc_db_link ($link, $_database)
{
	if ($link)
	{
		if (mysql_select_db($_database, $link))
			return $link;
		else
		{
			nc_report_error("MySQL reported: ".mysql_error());
			exit();
		}
	}
	else
	{
		nc_report_error("MySQL reported: ".mysql_error());
		exit();
	}
}
//
//	nc_db_escape - USED INTERNALLY
//
//	Escapes the string passed to it for secuirty.
//
function nc_db_escape ($string)
{
	$string = nc_remove_magic_quotes($string); // Remove PHP magic quotes because we don't want to double-escape.
		
	return mysql_real_escape_string($string);
}