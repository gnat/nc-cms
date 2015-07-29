<?php require('nc-cms/system/start.php'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php nc_title('home_title'); ?></title>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php nc_get_cp_css_directory(); ?>" />
	</head>
	<body>
		<?php nc_get_cp(); ?>
		<div class="content">
			<?php nc_content_html('home_content'); ?>
		</div>
		<div class="footer">
			<?php nc_login_link(); ?>
		</div>
	</body>
</html>
