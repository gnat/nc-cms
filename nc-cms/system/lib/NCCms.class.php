<?php

/**
* Main CMS class.
* @author Nathaniel Sabanski
* @link http://github.com/gnat/nc-cms
* @license zlib/libpng license
*/
class NCCms
{
	var $login_state = 0;
	var $path = 0;
	var $page_name = 0;
	var $load_time = 0;
	var $storage = null;

	/**
	* Initialize system.
	*/
	function NCCms()
	{
		// Check to see if user is logged in.
		$login = new NCLogin();
		$this->login_state = $login->Validate(NC_LOGIN_USER, NC_LOGIN_PASSWORD);

		// Page load timer, for measuring performance. 
		$time = explode(" ", microtime());
		$this->load_time = $time[1] + $time[0];

		$this->page_name = '';
		$this->path = NC_CMS_URL;

		// Make sure that NC_CMS_URL in /nc-cms/config.php is set correctly.
		if($this->URLPathRelative() == '')
		{
			$this->Error("NC_CMS_URL in /nc-cms/config.php is not set correctly.");
			exit();
		}

		$this->SetupStorage(NC_USE_DB);
	}

	/**
	* Setup our Storage system. Detect what types we can use and load the appropriate functionality module.
	* @param string $type Storage Type.
	*/
	function SetupStorage($type)
	{
		// Use database?
		if($type > 0)
		{
			$db_fail = false;
			
			// Test for database support
			if (!function_exists("mysqli_connect"))
			{
				nc_report_error("MySQL support in PHP environment not found. You can switch to filesystem storage by turning off database support in your /nc-cms/config.php file.");
				$db_fail = true;
			}
			else
			{
				// Support exists, test database.
				$db_link = @mysqli_connect(NC_DB_HOST, NC_DB_USER, NC_DB_PASSWORD);
				if (mysqli_connect_errno())
				{
					$nc_report_error = "MySQL reported: ".mysqli_connect_error();
					$nc_report_tip = "Double check to make sure that your database settings (host, user, password) found in <strong>/nc-cms/config.php</strong> are complete and correct. It's also possible that your host's database server may be down. If this is the case, contact your host.";
					$nc_db_fail = true;
				}
				else if(!mysqli_select_db($db_link, NC_DB_DATABASE))
				{
					$this->Error("MySQL reported: ".mysqli_error($db_link));
					$this->Tip("We were able to connect to the database server, but could not select your specified database. Double check to make sure that database settings (user, password, database name)  found in <strong>/nc-cms/config.php</strong> are complete and correct. Double check to make sure your specified user and database exists on the database server. If you are having troubles setting up your database, you should contact your host.");
					$db_fail = true;
				}
				else if(!mysqli_query($db_link, "SELECT name FROM ".NC_DB_PREFIX."content"))
				{
					$this->Error("MySQL reported: ".mysqli_error($db_link));
					$this->Tip("We were able to connect to the database server and select your specified database, but could not find the appropriate nc-cms tables. Double check your database prefix setting (NC_DB_PREFIX) in your /nc-cms/config.php file.");
					$this->Tip("If the tables do not exsist, run the nc-cms MySQL database setup script found at /nc-cms/setup_database_mysql.php in order to create them.");
					$db_fail = true;	
				}
				
				if($db_link)
					mysqli_close($db_link);
			}
			
			// Exit if any tests failed.
			if($db_fail)
				exit();
	
			require(NC_BASEPATH.'/lib/storage/MySQL.php');
		}
		else
			require(NC_BASEPATH.'/lib/storage/Filesystem.php');

		$this->storage = new Storage();
	}

	/**
	* Returns a clean version of the website URL as defined in config.php
	* @return string URL
	*/
	function SiteURL()
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

	/**
	* Returns a clean version of the cms URL as defined in config.php
	* @return string URL
	*/
	function SystemURL()
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

	/** 
	* Generate code for the CSS include
	* @param boolean $return True = Return. False = Echo.
	* @return string Control panel HTML.
	*/
	function CSS ($return = false)
	{
		$output = $this->URLPathRelative().'/system/css/cp.css';
		
		if($return)
			return $output;
		else
			echo $output;
	}

	/** 
	* Generate code for the control panel. Will only run if $login_state is true (the user is logged in).
	* @param boolean $return True = Return. False = Echo.
	* @return string Control panel HTML.
	*/
	function ControlPanel ($return = false)
	{
		if($this->login_state)
		{
			$output = '
			<div class="nc_cp">
				<div class="nc_logo">v'.NC_VERSION.'</div>
				<span class="nc_button nc_button_push_right"><a href="'.$this->URLPathRelative().'/index.php?action=logout"><span class="nc_icon nc_icon_logout">'.NC_LANG_LOG_OUT.'</span></a></span>';
				if($this->page_name != '') 
					$output .= ' <span class="nc_button nc_button_push_right"><a href="'.$this->URLPathRelative().'/index.php?action=edit_string&amp;name='.$this->page_name.'"><span class="nc_icon nc_icon_title">'.NC_LANG_EDIT_PAGE_TITLE.'</span></a></span>';
			$output .= '
				<div class="clear"></div>
			</div>';
			
			if($return)
				return $output;
			else
				echo $output;
		}
	}

	/** 
	* Return string content area.
	* @param string $name Content name to reference.
	* @param boolean $return True = Return. False = Echo.
	* @return string Formatted content.
	*/
	function ContentString ($name, $return = false)
	{
		$output = $this->storage->ContentLoad($name); // Output the content we have prepared to webpage
		
		if($this->login_state) // Edit mode is on, load the editor
			$output .=  '<a href="'.$this->URLPathRelative().'/index.php?action=edit_string&amp;name='.$name.'" class="nc_edit"><img src="'.$this->URLPathRelative().'/system/images/edit.png" alt="Edit" title="Edit" border="0"/></a>';
		
		if($return)
			return $output;
		else
			echo $output;
	}

	/** 
	* Return HTML content area.
	* @param string $name Content name to reference.
	* @param boolean $return True = Return. False = Echo.
	* @return string Formatted content.
	*/
	function ContentHTML ($name, $return = false)
	{		
		$output = $this->storage->ContentLoad($name); // Output the content we have prepared to webpage
		
		if($this->login_state) // Edit mode is on, load the editor
			$output .= '<a href="'.$this->URLPathRelative().'/index.php?action=edit_html&amp;name='.$name.'" class="nc_edit"><img src="'.$this->URLPathRelative().'/system/images/edit.png" alt="Edit" title="Edit" border="0"/></a>';

		if($return)
			return $output;
		else
			echo $output;
	}

	/** 
	* Return page title.
	* @param string $name Content name to reference.
	* @param boolean $return True = Return. False = Echo.
	* @return string Formatted content.
	*/
	function Title ($name, $return = false)
	{
		$output = $this->storage->ContentLoad($name);
		$this->page_name = $name;
		
		if($return)
			return $output;
		else
			echo $output;
	}

	/** 
	* Generate code for a login link.
	* @param boolean $return True = Return link. False = Echo link.
	* @return string Formatted link.
	*/
	function LoginLink ($return = false)
	{		
		if($this->login_state)
			$output = '';
		else
			$output = '<a href="'.$this->URLPathRelative().'" class="nc_login_link"><img src="'.$this->URLPathRelative().'/system/images/key.png" alt="Log In" title="Log In" border="0"/></a>';
		
		if($return)
			return $output;
		else
			echo $output;
	}

	/**
	* Returns the path to CMS relative to the website URL. 
	* Used with file and image links in order to keep them functional in the event that you decide to switch domain names. 
	* (ex 1: http://www.example.com/nc-cms will be converted to /nc-cms)
	* (ex 2: http://www.example.com/~blah/nc-cms will be converted to /~blah/nc-cms)
	* @return string URL
	*/
	function URLPathRelative()
	{
		$url = trim($this->path);
		
		// Remove any protocol information
		$url = str_replace(array('http://', 'https://'), '', $url);
		
		// Grab everything after the first '/', including the '/'. 
		$url = stristr($url, '/');
		
		// Strip any trailing slash
		if(strrpos($url, '/') == strlen($url)-1)
			$url = substr($url, 0, strlen($url)-1);
			
		return $url;
	}

	/**
	* Immediately ends the current script, and returns the user to the site url.
	*/
	function UserBoot()
	{
		header("Location: ".$this->SiteURL());
		exit();
	}

	/** 
	* Disallow unauthorized access. $this->login_state must be set before calling this.
	*/
	function UserCheck()
	{
		if(!$this->login_state)
			$this->UserBoot();
	}

	/** 
	* Special display function for errors.
	* @param string $message Error message.
	* @param boolean $return True = Return formatted message. False = Echo formatted message.
	* @return string Formatted error message.
	*/
	static function Error($message, $return = false)
	{	
		NCUtility::Error($message, $return);
	}

	/** 
	* Special display function for tips.
	* @param string $message Tip message.
	* @param boolean $return True = Return formatted message. False = Echo formatted message.
	* @return string Formatted tip message.
	*/
	static function Tip($message,  $return = false)
	{	
		NCUtility::Tip($message, $return);
	}

	/** 
	* For measuring performance.
	* @return int Amount of time passed from first calling start.php.
	*/
	function LoadTime()
	{			
		$current_time = explode(" ",  microtime());
		$current_time = $current_time[1] + $current_time[0];

		return ($current_time - $this->load_time);
	}

	/** 
	* Main routine for content editors using the CMS.
	*/
	function Manage()
	{
		// Determine which action to take.
		$action = '';
		if(isset($_GET['action']))
			$action = $_GET['action'];

		// Take action!
		if($action == 'logout')
		{
			// Log out if user is in session.
			$login = new NCLogin();
			$login->Logout();
			
			// Load previous page as view.
			$location = $_SERVER['HTTP_REFERER'];
			$location = substr($_SERVER['HTTP_REFERER'], 0, strrpos($location, "/"));
			header('Location: '.$location);
		} 
		else if($action == 'edit_string')
		{
			$this->UserCheck();
			$name = '';
			$data = '';
			
			if(isset($_GET['name'])) // Required GET data.
			{
				$name = basename($_GET['name']); // Strip paths.
				$data = $this->storage->ContentLoad($name);
			}
			else
				$this->UserBoot(); // Posible hacking attempt.
				
			include(NC_BASEPATH.'/views/edit_string.php'); // Load string editor view.
		}
		else if($action == 'edit_html')
		{
			$this->UserCheck();
			$name = '';
			$data = '';
			
			if(isset($_GET['name'])) // Required GET data.
			{
				$name = basename($_GET['name']); // Strip paths.
				$data = $this->storage->ContentLoad($name);
			}
			else
				$this->UserBoot(); // Posible hacking attempt.
				
			include(NC_BASEPATH.'/views/edit_html.php'); // Load html editor view.
		}
		else if($action == 'save')
		{
			$this->UserCheck();

			if(isset($_GET['ref'])) // Required GET data.
			{
				if(isset($_POST['name']) && isset($_POST['editordata'])) // Required POST data.
				{
					$this->storage->ContentSave($_POST['name'], $_POST['editordata']);
				}
				header('Location: '.$_GET['ref']);
				exit();
			}
			else
				$this->UserBoot();
		}
		else if($action == 'file_manager')
		{
			$this->UserCheck();
			$status_message = '';
			include(NC_BASEPATH.'/views/file_manager.php'); // Load file manager view.
		}
		else if($action == 'file_manager_upload')
		{
			$this->UserCheck();
			$status_message = '';
			
			if ($_FILES['file']['error'] > 0) // There was trouble uploading! 
			{
				if($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE)
					$status_message = NC_LANG_FILE_INI_SIZE;
				if($_FILES['file']['error'] ==  UPLOAD_ERR_NO_FILE)
					$status_message = NC_LANG_FILE_NOT_FOUND;
				else
					$status_message = NC_LANG_FILE_ERROR.'<br />'. $_FILES['file']["error"] .'<br />'.NC_LANG_ERROR_PHP_MANUAL;
			}
			else
			{
				$replacing_file = false;

				if(file_exists(NC_UPLOAD_DIRECTORY.$_FILES['file']['name']))
					$replacing_file = true;

				// Disallow PHP file uploads.
				$test = strrev(trim(strtolower($_FILES['file']['name'])));
				if (stripos($test, "php") === 0)
				{
					$status_message = NC_LANG_FILE_ERROR_PHP_TYPE;
				}
				else
				{
					move_uploaded_file($_FILES['file']['tmp_name'], NC_UPLOAD_DIRECTORY.$_FILES['file']['name']); // Write the file

					if($replacing_file)
						$status_message = NC_LANG_FILE_REPLACED.'<br /><strong>'.$_FILES['file']['name'].' ('.NCUtility::ReturnStringSize($_FILES['file']['size']).')</strong>';
					else
						$status_message = NC_LANG_FILE_UPLOADED.'<br /><strong>'.$_FILES['file']['name'].' ('.NCUtility::ReturnStringSize($_FILES['file']['size']).')</strong>';
				}
			}
			
			include(NC_BASEPATH.'/views/file_manager.php'); // Load file manager view.
		}
		else if($action == 'file_manager_remove')
		{
			$this->UserCheck();
			$status_message = '';
			$file = '';

			if(isset($_GET['file']))
				$file = $_GET['file'];
			
			basename($file);
			
			if(is_file(NC_UPLOAD_DIRECTORY.$file)) 
			{
				if(unlink(NC_UPLOAD_DIRECTORY.$file))
					$status_message = NC_LANG_FILE_REMOVED.'<br /><strong>'.$file.'</strong>';
				else
					$status_message = NC_LANG_FILE_REMOVED_ERROR.'<br /><strong>'.$file.'</strong>';
			}
			else
				$status_message = NC_LANG_FILE_REMOVED_ERROR_NOT_FOUND.'<br /><strong>'.$file.'</strong>';
			
			include(NC_BASEPATH.'/views/file_manager.php'); // Load file manager view.
		}
		else // DEFAULT: Retrieve login page.
		{
			include(NC_BASEPATH.'/views/login.php'); // Load login view.
		}
	}
}
