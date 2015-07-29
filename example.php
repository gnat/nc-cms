<?php require('nc-cms/system/start.php'); ?> <!-- #1 Include CMS header. -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php nc_title('home_title'); ?></title> <!-- #2 Allow website title editing. -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php nc_get_cp_css_directory(); ?>" /> <!-- #3 Include CSS. -->
	</head>
	<body>
		<?php nc_get_cp(); ?> <!-- #4 Include CMS control panel. -->
		<div class="content">
			<?php nc_content_html('home_content'); ?> <!-- #5 Add editable content area. -->
		</div>
		<div class="footer">
			<?php nc_login_link(); ?> <!-- #6 Generate login link. -->
		</div>
	</body>
</html>
