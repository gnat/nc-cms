<?php  if (!defined('NC_BASEPATH')) exit('No direct script access allowed'); 

function file_type($string)
{
	$tmp = strtolower($string);
	$tmp = substr($tmp, -3);

	if($tmp == "gif" || $tmp == "png" || $tmp == "jpg" || $tmp == "peg" || $tmp == "tif" || $tmp == "bmp" || $tmp == "svg") // image
		return 1; // image
	else if($tmp == "zip" || $tmp == "rar" || $tmp == "7z" || $tmp == "bz" || $tmp == "bz2" || $tmp == "tar" || $tmp == "gz" || $tmp == "z" || $tmp == "lz" || $tmp == "lzma" || $tmp == "cab" || $tmp == "ace" || $tmp == "dmg" || $tmp == "sit" || $tmp == "itx") // archive
		return 2; // archive
	else if($tmp == "wav" || $tmp == "mp3" || $tmp == "ogg" || $tmp == "mid" || $tmp == "flac" || $tmp == "aiff" || $tmp == "raw" || $tmp == "wma" || $tmp == "mp4" || $tmp == "m4a" || $tmp == "au") // audio
		return 3; // audio
	else
		return 0; // regular file
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
	<head>
		<title>nc-cms | File Manager</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<meta name="robots" content="noindex" />
		<meta name="robots" content="nofollow" />
		<link rel="stylesheet" type="text/css" media="screen" href="system/css/editor.css"/>
		<link rel="stylesheet" type="text/css" media="screen" href="system/css/editor_file_manager.css"/>
		<!--[if lt IE 7]><link rel="stylesheet" type="text/css" media="screen" href="system/css/ie.css"/><![endif]-->
		<script type="text/javascript" src="system/modules/jquery.js"></script>
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
			var answer = confirm("Are you sure you want to remove this file?");
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
					src: "<?php echo nc_get_cms_path_relative(); ?>/content/upload/"+tmp }); 
			}
			
			$(".file_insert").attr({ 
					href: "javascript:insert_file('<?php echo nc_get_cms_path_relative(); ?>/content/upload/"+tmp+"')" });
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
				<div><strong>Choose a file or image  to upload.</strong>
				</div>
				<p>
					<input type="file" name="file" id="file" /> 
					<br />
					<?php echo "Maximum: ".ini_get("upload_max_filesize"); ?>
				</p>
				<span class="button"><a href="javascript:document.loginform.submit()"><span class="icon icon_upload"> Upload</span></a></span>
			</form>
			<br /><br />
			<div class="file_options_info">
				<strong>Or select a file from the list to the right.</strong>
			</div>
			<div class="file_options" style="display: none;">
				<div style="height: 288px;">
					<p class="file_selected"></p>
					<div class="image_preview">
						<strong>Image Preview</strong>
						<br />
						<img src="/images/lolwut" alt="" />
					</div>
					<br />
				</div>
				<span class="button"><a class="file_insert" href="javascript:insert_file()"><span class="icon icon_insert"> Insert to Editor</span></a></span>
				<span class="button"><a class="file_remove" href="#"><span class="icon icon_delete"> Remove</span></a></span>
			</div>
		</div>
		<div id="wrapper-right">
			Files and images uploaded to <strong><?php echo NC_UPLOAD_DIRECTORY; ?></strong>
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
						if(file_type($file) == 1)
							echo '<a class="icon_picture file_selectable" href="'.nc_get_cms_path_relative().'/content/upload/'.$file.'" target="_self">'.$file.'</a>';
						else if(file_type($file) == 2)
							echo '<a class="icon_archive file_selectable" href="'.nc_get_cms_path_relative().'/content/upload/'.$file.'" target="_self">'.$file.'</a>';
						else if(file_type($file) == 3)
							echo '<a class="icon_audio file_selectable" href="'.nc_get_cms_path_relative().'/content/upload/'.$file.'" target="_self">'.$file.'</a>';
						else	
							echo '<a class="icon_file file_selectable" href="'.nc_get_cms_path_relative().'/content/upload/'.$file.'" target="_self">'.$file.'</a>';
					}
				}
				closedir($handle);
			}
			?> 
			</div>
		</div>
	</body>
</html>