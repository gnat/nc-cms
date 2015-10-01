<?php  if (!defined('NC_BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>nc-cms | <?php echo NC_LANG_EDITOR_FILE; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<meta name="robots" content="noindex" />
		<meta name="robots" content="nofollow" />
		<link rel="stylesheet" type="text/css" media="screen" href="system/css/editor.css"/>
		<link rel="stylesheet" type="text/css" media="screen" href="system/css/editor_file_manager.css"/>
		<script type="text/javascript" src="system/js/jquery.js"></script>
		<script type="text/javascript">
		function get_filename(str)
		{
			str_occur = str.lastIndexOf("/");
			return str.slice(str_occur+1);
		}
		
		function insert_file(href) 
		{
			var editor_content = '';
			if(is_image(href))
				editor_content += '<img alt="'+get_filename(href)+'" src="'+href+'" align="none" />';
			else
				editor_content += '<a href="'+href+'">'+get_filename(href)+'</a>';
			
			opener.tinyMCE.execCommand('mceInsertContent', true, editor_content);
		}
		
		function remove_confirmation(href) 
		{
			var answer = confirm("<?php echo NC_LANG_EDITOR_FILE_REMOVE; ?>");
			if(answer)
				window.location = href;
		}
		
		function is_image(str)
		{
			str = str.toLowerCase();
			str = str.substr(str.length-3, 3);
			if(str == "gif" || str == "png" || str == "jpg" || str == "peg" || str == "tif" || str == "bmp")
				return true;
			else
				return false;
		}
		
		function file_selectable_action(event)
		{
			event.preventDefault();
			$(".message").hide();
			$(".file_options_info").hide();
			$(".image_preview").hide();
			var content_ref = this;
			var tmp = $(content_ref).html();
			
			$(".file_selected").html("<strong>Selected File</strong><br />"+$(content_ref).html()); 
			
			if(is_image(tmp)) // If selected file is an image, show the image preview box.
			{
				$(".image_preview").show();
				$(".image_preview img").attr({ 
					src: "<?php echo NC_CMS_URL; ?>/content/upload/"+tmp }); 
			}
			
			$(".file_insert").attr({ 
					href: "javascript:insert_file('<?php echo NC_CMS_URL; ?>/content/upload/"+tmp+"')" });
			$(".file_remove").attr({ 
					href: "javascript:remove_confirmation('index.php?action=file_manager_remove&file="+tmp+"')" });
			$(".file_options").show(); 
		}
		
		function setup() // jQuery calls this first
		{
			$("a.file_selectable").click(file_selectable_action);
		}
		
		$(document).ready(setup); // Go jQuery !
		</script>
	</head>

	<body>
		<div id="wrapper-left">
			<?php if($status_message != '') echo '<div class="message">'.$status_message.'</div>'; ?>
			<form action="index.php?action=file_manager_upload" name="loginform" method="post" enctype="multipart/form-data">
				<div><strong><?php echo NC_LANG_EDITOR_FILE_CHOOSE; ?></strong>
				</div>
				<p>
					<input type="file" name="file" id="file" /> 
					<br />
					<?php echo "Maximum: ".ini_get("upload_max_filesize"); ?>
				</p>
				<span class="button"><a href="javascript:document.loginform.submit()"><span class="icon icon_upload"> <?php echo NC_LANG_UPLOAD; ?></span></a></span>
			</form>
			<br /><br />
			<div class="file_options_info">
				<strong><?php echo NC_LANG_EDITOR_FILE_SELECT; ?></strong>
			</div>
			<div class="file_options" style="display: none;">
				<div style="height: 288px;">
					<p class="file_selected"></p>
					<div class="image_preview">
						<strong><?php echo NC_LANG_IMAGE_PREVIEW; ?></strong>
						<br />
						<img src="/images/lolwut" alt="" />
					</div>
					<br />
				</div>
				<span class="button"><a class="file_insert" href="javascript:insert_file()"><span class="icon icon_insert"> <?php echo NC_LANG_EDITOR_INSERT; ?></span></a></span>
				<span class="button"><a class="file_remove" href="#"><span class="icon icon_delete"> <?php echo NC_LANG_REMOVE; ?></span></a></span>
			</div>
		</div>
		<div id="wrapper-right">
			<?php echo NC_LANG_EDITOR_FILE_HELP; ?> <strong><?php echo NC_UPLOAD_DIRECTORY; ?></strong>
			<br /><br />
			<div class="listing">
			<?php
			if($handle = opendir(NC_UPLOAD_DIRECTORY)) 
			{
				while($file = readdir($handle)) 
				{
					clearstatcache();
					if(is_file(NC_UPLOAD_DIRECTORY.$file)) 
					{
						if(NCUtility::FileType($file) == 1)
							echo '<a class="icon_picture file_selectable" href="'.NC_BASEPATH.'/../content/upload/'.$file.'" target="_self">'.$file.'</a>';
						else if(NCUtility::FileType($file) == 2)
							echo '<a class="icon_archive file_selectable" href="'.NC_BASEPATH.'/../content/upload/'.$file.'" target="_self">'.$file.'</a>';
						else if(NCUtility::FileType($file) == 3)
							echo '<a class="icon_audio file_selectable" href="'.NC_BASEPATH.'/../content/upload/'.$file.'" target="_self">'.$file.'</a>';
						else	
							echo '<a class="icon_file file_selectable" href="'.NC_BASEPATH.'/../content/upload/'.$file.'" target="_self">'.$file.'</a>';
					}
				}
				closedir($handle);
			}
			?> 
			</div>
		</div>
	</body>
</html>