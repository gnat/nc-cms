<?php

/**
* Storage class for MySQL type.
*/
class Storage
{
	var $db_link = 0;

	/**
	* Check content file to see if it exists, then save data.
	* @param string $name Content area name.
	* @param string $data Content area data.
	*/
	function ContentSave($name, $data)
	{
		$this->ContentCheck($name, "Edit Me!"); // Make sure database entry exists.

		$this->db_link = @mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
		$this->db_link = $this->DatabaseLink($this->db_link, NC_DB_DATABASE);
		$db_result = mysql_query("UPDATE ".NC_DB_PREFIX."content SET content='".$this->EscapeString($data)."' WHERE name='".$name."'", $this->db_link);
		
		if(!$db_result) // Check for query errors.
		{
			NCUtility::Error("MySQL reported: ".mysql_error());
			exit();
		}
		
		mysql_close($this->db_link); // Close connection.
	}

	/**
	* Check content file to see if it exists, if not, create it. Open it and return data.
	* @param string $name Content area name.
	* @return $data Data found in storage.
	*/
	function ContentLoad($name)
	{
		$this->ContentCheck($name, "Edit Me! (".$name.")"); // Make sure database entry exists.

		$this->db_link = mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
		$this->db_link = $this->DatabaseLink($this->db_link, NC_DB_DATABASE);
		$db_result = mysql_query("SELECT name,content FROM ".NC_DB_PREFIX."content WHERE name='".$name."'", $this->db_link);
		
		if(!$db_result) // Check for query errors.
		{
			NCUtility::Error("MySQL reported: ".mysql_error());
			exit();
		}
			
		$row = mysql_fetch_row($db_result);
		$data = $row[1];
		
		mysql_close($this->db_link); // Close connection.

		return $data;
	}

	/**
	* USED INTERNALLY. Check content file to see if it exists. And if it doesn't, create it. $path contains the file path, $default contains the default text to go in the file if it is new.
	* @param string $name Content area name.
	*/
	function ContentCheck($name, $default)
	{
		$create_entry = false;

		$this->db_link = mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
		$this->db_link = $this->DatabaseLink($this->db_link, NC_DB_DATABASE);
		$db_result = mysql_query("SELECT name FROM ".NC_DB_PREFIX."content WHERE name='".$name."'", $this->db_link);
		
		if(!$db_result) // Check for query errors.
		{
			NCUtility::Error("MySQL reported: ".mysql_error());
			exit();
		}
		
		// See if a row exsists
		if(mysql_num_rows($db_result) < 1)
			$create_entry = true;
		
		mysql_close($this->db_link); // Close connection.
		
		if ($create_entry) // No entries existed. Create one instead.
		{
			$this->db_link = mysql_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
			$this->db_link = $this->DatabaseLink($this->db_link, NC_DB_DATABASE);
			$db_result = mysql_query("INSERT INTO ".NC_DB_PREFIX."content (name,content) VALUES ('".$name."','".$default."')");
			
			if(!$db_result) // Check for query errors.
			{
				NCUtility::Error("MySQL reported: ".mysql_error());
				exit();
			}
			
			mysql_close($this->db_link); // Close connection.
		}
	}

	/**
	* USED INTERNALLY. Checks the validity of the mysql link. Selects the database. Returns the db link, presents any errors if any are found.
	* @param string $name Content area name.
	*/
	function DatabaseLink($link, $_database)
	{
		if ($link)
		{
			if (mysql_select_db($_database, $link))
				return $link;
			else
			{
				NCUtility::Error("MySQL reported: ".mysql_error());
				exit();
			}
		}
		else
		{
			NCUtility::Error("MySQL reported: ".mysql_error());
			exit();
		}
	}

	/**
	* USED INTERNALLY. Escapes the string passed to it for secuirty.
	* @param string $name Content area name.
	*/
	function EscapeString($string)
	{
		if(get_magic_quotes_gpc())
			$string = stripslashes($string); // Remove PHP magic quotes because we don't want to double-escape.	
		return mysql_real_escape_string($string);
	}
}
