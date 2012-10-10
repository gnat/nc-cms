<?php

/*
	NOTE
	The parent script must include config.php beforehand in order to use this class properly. 		
*/

//
//	ncLogin
//
//	Class for managing a secure login session.
//
class ncLogin 
{
	//
	//	[Constructor]
	//
	//	Initializes session.
	//
	function ncLogin()
	{
		session_start();
		header("Cache-control: private");
	}
	//
	//	logout()
	//
	//	Closes session. Reset to default behavior.
	//
	function logout()
	{		
		unset($_SESSION['nc_login_user']);
		unset($_SESSION['nc_login_status']);
		session_destroy();
	}
	//
	//	check_login()
	//
	//	Call this on each page that needs login security. Checks if the user is currently logged in. If so, returns TRUE. If not, returns FALSE.
	//
	function check_login()
	{
		// User is already logged in, check credentials
		if (isset($_SESSION['nc_login_status']))
		{
			if ($_SESSION['nc_login_status'] == true)
			{
				// User is logged in!
				return true;
				exit();
			}
			else
			{
				// User isn't logged in
				return false;
				exit();
			}
		}
		// If user isn't logged in, check POST variables to see if user is logging in
		else
		{
			if(isset($_POST['user']) && isset($_POST['pass']))
			{
				// Compare user name and password. Help minimize user error by making the user name case-insensitive, and by trimming any surrounding whitespace.
				if(trim(strtolower($_POST['user'])) == trim(strtolower(NC_LOGIN_USER)) && trim($_POST['pass']) == trim(NC_LOGIN_PASSWORD))
				{
					// User is logged in! Set up variables and return true.
					$_SESSION['nc_login_user'] = NC_LOGIN_USER;
					$_SESSION['nc_login_status'] = true;
					
					return true;
					exit();
				}
				else
				{
					// Username or password incorrect. Login failed.
					return false;
					exit();
				}
			} 
			else
			{
				// User isn't logged in or logging in.
				return false;
				exit();
			}
		}
	}
}