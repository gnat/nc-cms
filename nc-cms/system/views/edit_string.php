<?php  if (!defined('NC_BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>nc-cms | <?php echo NC_LANG_EDITOR_TEXT; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<meta name="robots" content="noindex" />
		<meta name="robots" content="nofollow" />
		<link rel="stylesheet" type="text/css" media="screen" href="system/css/editor.css"/>
		<script type="text/javascript">

			// onbeforeunload() does not work correctly in certain browsers. Disable this functionality if not using Firefox/Chrome.
			var confirmed_exit = true;
			if(!navigator.appName.indexOf("Netscape")) 
				confirmed_exit = false;
				
			window.onbeforeunload = function () 
			{	
				if(!confirmed_exit)
					return "<?php echo NC_LANG_REDIRECT_WARN; ?>";
			}	
			
			function save_confirmation() 
			{
				var answer = confirm("<?php echo NC_LANG_SAVE_CONFIRM; ?>");
				if(answer)
				{
					confirmed_exit = true;
					document.editorform.submit();
				}
			}

			function cancel_confirmation() 
			{
				var answer = confirm("<?php echo NC_LANG_CANCEL_CONFIRM; ?>");
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
				<form name="editorform" id="editorform" method="post" action="index.php?action=save&amp;ref=<?php echo $_SERVER['HTTP_REFERER']; ?>">
					<p>
						<br />
						<input type="text" name="editordata" id="user" class="textfield" size="24" value="<?php echo htmlspecialchars($data); ?>" />
					</p>
					<input name="name" id="name" type="hidden" value="<?php echo $name; ?>" />
					<br />
					<span class="button"><a href="javascript:save_confirmation()"><span class="icon icon_accept"><?php echo NC_LANG_SAVE; ?></span></a></span>
					<span class="button"><a href="javascript:cancel_confirmation()"><span class="icon icon_delete"><?php echo NC_LANG_CANCEL; ?></span></a></span>
				</form>
				<div class="footer"></div>
			</div>
		</div>
	</body>
</html>
