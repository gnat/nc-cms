Embeddable, lightweight PHP CMS.
================================

Embeddable, lightweight PHP Content Management System (CMS). Quick single file "website add-on" style integration, while retaining the most important features of a modern day CMS (User login, file uploads, edit of content areas and page titles.)

Can optionally use a database for content storage (MySQL, etc.) However, a database is not required, and nc-cms uses fast flat file storage by default.

For documentation, see: http://www.nconsulting.ca/nc-cms nc-cms was designed and produced by Nathaniel Sabanski of NConsulting.ca. Licensed under the zlib/libpng license. Will run on any web server that supports PHP 5 or higher. Do you like nc-cms?

**Some kind words from the community...**

> "I can't begin to describe to you what a life saver it is not having to rebuild an entire site on a CMS platform."

> "Overall, a very quick way to start up a stable, dynamic site with minimal overhead!"

> "Thanks for such a killer cms that is so simple.. great concept!"

**Sample Website**
<img src="http://i.imgur.com/I8Kktc2.png" alt="nc-cms Screenshot 1" />

**Login**
<img src="http://i.imgur.com/CFfEaFg.png" alt="nc-cms Screenshot 2" />

**Editor**
<img src="http://i.imgur.com/kd5S8I9.png" alt="nc-cms Screenshot 3" />

**Integration Example**
```php
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
```
