<?php

/**
* NCUtility. Various utility functions.
* @author Nathaniel Sabanski
* @link http://github.com/gnat/nc-cms
* @license zlib/libpng license
*/
class NCUtility
{
	/** 
	* Special display function for errors.
	* @param string $message Error message.
	* @param boolean $return True = Return formatted message. False = Echo formatted message.
	* @return string Formatted error message.
	*/
	static function Error($message, $return = false)
	{	
		$message = '<p><span style="color: #AA0000; font-weight: bold;">ERROR:</span> '.$message.'</p>';
		
		if($return)
			return $message;
		else
			echo $message;
	}

	/** 
	* Special display function for tips.
	* @param string $message Tip message.
	* @param boolean $return True = Return formatted message. False = Echo formatted message.
	* @return string Formatted tip message.
	*/
	static function Tip($message,  $return = false)
	{	
		$message = '<p><span style="color: #0000AA; font-weight: bold;">TIP:</span> '.$message.'</p>';
		
		if($return)
			return $message;
		else
			echo $message;
	}

	/** 
	* Find out the file type by file extension.
	* @param string $string File Name.
	* @return int File type number.
	*/
	static function FileType($string)
	{
		$tmp = strtolower($string);
		$tmp = substr($tmp, -3);

		if($tmp == "gif" || $tmp == "png" || $tmp == "jpg" || $tmp == "peg" || $tmp == "tif" || $tmp == "bmp" || $tmp == "svg") // image
			return 1; // Image.
		else if($tmp == "zip" || $tmp == "rar" || $tmp == "7z" || $tmp == "bz" || $tmp == "bz2" || $tmp == "tar" || $tmp == "gz" || $tmp == "z" || $tmp == "lz" || $tmp == "lzma" || $tmp == "cab" || $tmp == "ace" || $tmp == "dmg" || $tmp == "sit" || $tmp == "itx") // archive
			return 2; // Archive.
		else if($tmp == "wav" || $tmp == "mp3" || $tmp == "ogg" || $tmp == "mid" || $tmp == "flac" || $tmp == "aiff" || $tmp == "raw" || $tmp == "wma" || $tmp == "mp4" || $tmp == "m4a" || $tmp == "au") // audio
			return 3; // Audio.
		else
			return 0; // Regular file.
	}

	/** 
	* Pass a byte value to convert to string equivalent rounded.
	* (Example: 2097152 == '2098 KB')
	* @param int $val Size value.
	* @return string Humanized Size value.
	*/
	static function ReturnStringSize($val) 
	{
		if($val > 1048576)
			 return round(($val/1048576), 2).' MB';
		if($val > 1024)
			 return round((integer)($val/1024), 2).' KB';

		return ($val).' Bytes';
	}

	/** 
	* Find exact bytes of given string. Pass string from ini_get("upload_max_filesize").
	* (Example: '2M' == 2097152)
	* @param string $val Size string.
	* @return int Size in bytes.
	*/
	static function ReturnBytes($val) 
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

	/** 
	* Get the referring page. If one isn't found, go to NC_WEBSITE_URL
	* @return string Referring URL.
	*/
	static function Referrer()
	{
		$output = '';
		
		if(isset($_SERVER['HTTP_REFERER']))
			$output = $_SERVER['HTTP_REFERER'];
		else
			$output = NC_WEBSITE_URL;

		return $output;
	}
}
