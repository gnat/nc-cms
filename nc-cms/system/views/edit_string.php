<?php  if (!defined('NC_BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
	<head>
		<title>nc-cms | Text Editor</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<meta name="robots" content="noindex" />
		<meta name="robots" content="nofollow" />
		<link rel="stylesheet" type="text/css" media="screen" href="system/css/editor.css"/>
		<!--[if lt IE 7]><link rel="stylesheet" type="text/css" media="screen" href="system/css/ie.css"/><![endif]-->
		<script type="text/javascript">
			// onbeforeunload() does not work correctly in certain browsers. Disable this functionality if not using Firefox/Chrome.
			var confirmed_exit = true;
			if(!navigator.appName.indexOf("Netscape")) 
				confirmed_exit = false;
				
			window.onbeforeunload = function () 
			{	
				if(!confirmed_exit)
					return "You have not saved yet.  If you continue, your work will not be saved."
			}	
			
			function save_confirmation() 
			{
				var answer = confirm("Are you sure you want to save?\nAny changes you have made to the web page will go live.");
				if(answer)
				{
					confirmed_exit = true;
					document.editorform.submit();
				}
			}
			function cancel_confirmation() 
			{
				var answer = confirm("Are you sure you want to cancel?\nAny changes you have made to the web page will not be saved.");
				if(answer)
				{
					confirmed_exit = true;
					this.location.href = "<?php echo $_SERVER['HTTP_REFERER']; ?>";
				}
			}
		</script>
	</head>
	<body>
		<div id="wrapper">
			<div id="editor">
				<h1 title="Powered by nc-cms"><?php echo NC_WEBSITE_NAME; ?>
				</h1>
				<form name="editorform" id="editorform" method="post" action="index.php?action=save&ref=<?php echo $_SERVER['HTTP_REFERER']; ?>">
					<p>
						<br />
						<input type="text" name="editordata" id="user" class="textfield" size="24" value="<?php echo htmlspecialchars($data); ?>" />
					</p>
					<input name="name" id="name" type="hidden" value="<?php echo $name; ?>" />
					<br />
					<span class="button"><a href="javascript:save_confirmation()"><span class="icon icon_accept">Save</span></a></span>
					<span class="button"><a href="javascript:cancel_confirmation()"><span class="icon icon_delete">Cancel</span></a></span>
				</form>
				<div class="footer"></div>
			</div>
		</div>
	</body>
</html>
