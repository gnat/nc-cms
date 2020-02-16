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

		$this->db_link = @mysqli_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
		$this->db_link = $this->DatabaseLink($this->db_link, NC_DB_DATABASE);
		$db_result = mysqli_query($this->db_link, "UPDATE ".NC_DB_PREFIX."content SET content='".$this->EscapeString($this->db_link, $data)."' WHERE name='".$name."'");
		
		if(!$db_result) // Check for query errors.
		{
			NCUtility::Error("MySQL reported: ".mysqli_error($this->db_link));
			exit();
		}
		
		mysqli_close($this->db_link); // Close connection.
	}

	/**
	* Check content file to see if it exists, if not, create it. Open it and return data.
	* @param string $name Content area name.
	* @return $data Data found in storage.
	*/
	function ContentLoad($name)
	{
		$this->ContentCheck($name, "Edit Me! (".$name.")"); // Make sure database entry exists.

		$this->db_link = mysqli_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
		$this->db_link = $this->DatabaseLink($this->db_link, NC_DB_DATABASE);
		$db_result = mysqli_query($this->db_link, "SELECT name,content FROM ".NC_DB_PREFIX."content WHERE name='".$name."'");
		
		if(!$db_result) // Check for query errors.
		{
			NCUtility::Error("MySQL reported: ".mysqli_error($this->db_link));
			exit();
		}
			
		$row = mysqli_fetch_row($db_result);
		$data = $row[1];
		
		mysqli_close($this->db_link); // Close connection.

		return $data;
	}

	/**
	* USED INTERNALLY. Check content file to see if it exists. And if it doesn't, create it. $path contains the file path, $default contains the default text to go in the file if it is new.
	* @param string $name Content area name.
	*/
	function ContentCheck($name, $default)
	{
		$create_entry = false;

		$this->db_link = mysqli_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
		$this->db_link = $this->DatabaseLink($this->db_link, NC_DB_DATABASE);
		$db_result = mysqli_query($this->db_link, "SELECT name FROM ".NC_DB_PREFIX."content WHERE name='".$name."'");
		
		if(!$db_result) // Check for query errors.
		{
			NCUtility::Error("MySQL reported: ".mysqli_error($this->db_link));
			exit();
		}
		
		// See if a row exsists
		if(mysqli_num_rows($db_result) < 1)
			$create_entry = true;
		
		mysqli_close($this->db_link); // Close connection.
		
		if ($create_entry) // No entries existed. Create one instead.
		{
			$this->db_link = mysqli_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
			$this->db_link = $this->DatabaseLink($this->db_link, NC_DB_DATABASE);
			$db_result = mysqli_query($this->db_link, "INSERT INTO ".NC_DB_PREFIX."content (name,content) VALUES ('".$name."','".$default."')");
			
			if(!$db_result) // Check for query errors.
			{
				NCUtility::Error("MySQL reported: ".mysqli_error($this->db_link));
				exit();
			}
			
			mysqli_close($this->db_link); // Close connection.
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
			if (mysqli_select_db($link, $_database))
				return $link;
			else
			{
				NCUtility::Error("MySQL reported: ".mysqli_error($link));
				exit();
			}
		}
		else
		{
			NCUtility::Error("MySQL reported: ".mysqli_error($link));
			exit();
		}
	}

	/**
	* USED INTERNALLY. Escapes the string passed to it for secuirty.
	* @param string $name Content area name.
	*/
	function EscapeString($link, $string)
	{
		if(get_magic_quotes_gpc())
			$string = stripslashes($string); // Remove PHP magic quotes because we don't want to double-escape.	
		return mysqli_real_escape_string($link, $string);
	}
}
