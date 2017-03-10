<?php

/**
* Storage class for Filesystem type.
*/
class Storage
{
	/**
	* Check content file to see if it exists, then save data.
	* @param string $name Content area name.
	* @param string $data Content area data.
	*/
	function ContentSave($name, $data)
	{
		$path = NC_BASEPATH.'/../content/'.$name;
		$fh = fopen('content/'.$name, 'w') or die(NCUtility::Error("Could not open file: ".$name.". Make sure that this server has read and write permissions  the /nc-cms/content folder."));
		fwrite($fh, $data);
		fclose($fh);
	}

	/**
	* Check content file to see if it exists, if not, create it. Open it and return data.
	* @param string $name Content area name.
	* @return $data Data found in storage.
	*/
	function ContentLoad($name)
	{
		// Load content if file exists
		$path = NC_BASEPATH.'/../content/'.$name;
		$this->ContentCheck ($path, 'Edit me! ('.$name.')'); // Make sure content file exists.
		$fh = fopen($path, 'r') or die(NCCms::Error("Could not find file: ".$path));
		$data = fread($fh, filesize($path)) or die(NCUtility::Error("Could not read file: ".$path.". Make sure that this server has read and write permissions to the /nc-cms/content folder."));
		fclose($fh);

		return $data;
	}

	/**
	* USED INTERNALLY. Check content file to see if it exists. And if it doesn't, create it. $path contains the file path, $default contains the default text to go in the file if it is new.
	* @param string $name Content area name.
	*/
	function ContentCheck($path, $default)
	{
		// If file doesn't exist yet or is of 0 length, create and write something in it.
		if (!file_exists($path) || !filesize($path)) 
		{
			$fh = fopen($path, 'w') or die(NCUtility::Error("Could not write file: ".basename($path).". Make sure that this server has read and write permissions to the /nc-cms/content folder."));
			fwrite($fh, $default) or die(NCUtility::Error("Could not write file: ".basename($path).". Make sure that this server has read and write permissions to the /nc-cms/content folder."));
			fclose($fh);
		}

		clearstatcache(); // Clear status cache (so filesize() will do its work again)
	}
}
