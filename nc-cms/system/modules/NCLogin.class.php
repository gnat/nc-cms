<?php

/**
* NCLogin. For managing secure PHP sessions for secure content editor logins.
* @author Nathaniel Sabanski
* @link http://github.com/gnat/nc-cms
* @license zlib/libpng license
*/
class NCLogin 
{
	var $lock_file = "nccms_lock_file";
	var $lock_time = 2; // In seconds. Brute force protection.

	/**
	* Constructor. Initializes PHP session.
	*/
	function NCLogin()
	{
		session_start();
		header("Cache-control: private");
		$this->lock_file = rtrim(sys_get_temp_dir(), '/').'/'.$this->lock_file; // Generate lock file path.
	}

	/**
	* Logout. Closes PHP session. Reset to default behavior.
	*/
	static function Logout()
	{		
		unset($_SESSION['nc_login_user']);
		unset($_SESSION['nc_login_status']);
		session_destroy();
	}

	/**
	* Validate session. Call this on each page that needs login security. Checks if the user is currently logged in. If so, returns TRUE. If not, returns FALSE.
	* @param string $username User name.
	* @param string $password User password.
	* @return True on success. False on failure.
	*/
	function Validate($username, $password)
	{
		// Already logged in, check credentials.
		if (isset($_SESSION['nc_login_status']))
		{
			if ($_SESSION['nc_login_status'] == true)
				return true; // User is logged in!
			else
				return false; // User isn't logged in.
		}
		// If user isn't logged in, check POST variables to see if user is attempting to log in.
		else
		{
			if(isset($_POST['user']) && isset($_POST['pass']))
			{
				// Allow enough time to pass for the lock to clear for an individual user.
				sleep($this->lock_time+1);

				// Check to see if we're currently login rate limited to prevent brute forcing the password.
				if(file_exists($this->lock_file))
				{
					$lock_time = file_get_contents($this->lock_file);

					if($lock_time + $this->lock_time > time())
						return false; // Many logins are happening too quickly. Site may be getting brute forced. Login failed.						
				}

				file_put_contents($this->lock_file, time()); // Update lock file.

				// Compare user name and password. Help minimize user error by making the user name case-insensitive, and by trimming any surrounding whitespace.
				if(trim(strtolower($_POST['user'])) == trim(strtolower($username)) && trim($_POST['pass']) == trim($password))
				{
					// User is logged in! Set up variables and return true.
					$_SESSION['nc_login_user'] = $username;
					$_SESSION['nc_login_status'] = true;
					
					return true;
				}
				else
					return false; // Username or password incorrect. Login failed.
			} 
			else
				return false; // User isn't logged in or logging in.
		}
	}
}