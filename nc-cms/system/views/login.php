<?php  if (!defined('NC_BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>nc-cms | <?php echo NC_LANG_LOGIN; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<meta name="robots" content="noindex" />
		<meta name="robots" content="nofollow" />
		<link rel="stylesheet" type="text/css" media="screen" href="system/css/login.css"/>
		<script type="text/javascript" src="system/js/jquery.js"></script>
		<script type="text/javascript">
		function keypress_action(event)
		{
			if (event.which == 13)
			{
				document.loginform.submit();
			}
		}
		
		function setup() // jQuery calls this first
		{
			$("input").keypress(keypress_action);
			document.loginform.user.focus();
		}
		
		$(document).ready(setup); // Go jQuery !
		</script>
	</head>
	<body>
		<div id="wrapper">
			<div id="login">
				<h1 title="Powered by nc-cms"><?php echo NC_WEBSITE_NAME; ?></h1>
				<form name="loginform" id="loginform" method="post" action="<?php echo NCUtility::Referrer(); ?>/">	
					<p>
						<label for="user" class="label"><?php echo NC_LANG_USERNAME; ?></label><br />
						<input type="text" name="user" id="user" class="textfield" size="24" />
					</p>
					<p style="margin-top: 10px;">
						<label for="pass" class="label"><?php echo NC_LANG_PASSWORD; ?></label><br />
						<input type="password" name="pass" id="pass" class="textfield" size="24" />
					</p>
					<br />
					<span class="button"><a href="javascript:document.loginform.submit()"><span class="icon icon_go"> <?php echo NC_LANG_LOGIN_RETURN; ?></span></a></span>
				</form>
				<div class="footer"></div>
			</div>
			<p class="powered_by">Powered by <a href="http://www.nconsulting.ca/nc-cms/" target="_blank">nc-cms</a>.</p>
			<p id="backtosite"><a href="<?php echo NC_WEBSITE_URL; ?>" title="Are you lost?">&laquo;  <?php echo NC_LANG_BACK_TO; ?> <?php echo NC_WEBSITE_NAME; ?></a></p>
		</div>
	</body>
</html>
