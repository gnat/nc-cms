Embeddable, lightweight PHP CMS.
================================

Website "add-on" style integration, while retaining the most important features of a modern day CMS: User login, file uploads, edit of content areas and page titles.

Can optionally use a database for content storage (MySQL, etc.) However, a database is not required, and nc-cms uses fast flat file storage by default.

For documentation, see: http://www.nconsulting.ca/nc-cms nc-cms was designed and produced by Nathaniel Sabanski of NConsulting.ca. Licensed under the zlib/libpng license.

### Some kind words from the community...

> "I can't begin to describe to you what a life saver it is not having to rebuild an entire site on a CMS platform."

> "Overall, a very quick way to start up a stable, dynamic site with minimal overhead!"

> "Thanks for such a killer cms that is so simple.. great concept!"

**Sample Website**
<img src="http://i.imgur.com/I8Kktc2.png" alt="nc-cms Screenshot 1" />

**Login**
<img src="http://i.imgur.com/CFfEaFg.png" alt="nc-cms Screenshot 2" />

**Editor**
<img src="http://i.imgur.com/kd5S8I9.png" alt="nc-cms Screenshot 3" />

[...](#instructions)

### Integration Example
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

### Installation

1. Use **git clone** or [download a zip of nc-cms](https://github.com/gnat/nc-cms/archive/master.zip).

2. Edit **/nc-cms/config.php**. Be sure to set all of the general settings. If you would like to use nc-cms's database support (optional), set **NC_USE_DB** to true and configure the database connectivity settings as well.

3. Copy over your newly configured **/nc-cms** to your web server. Upload it to where **NC_CMS_URL** points to from your **/nc-cms/config.php** file. The root of your website domain is recommended (**http://www.example.com/nc-cms/**).

4. If you've set up nc-cms's database support in step 2, run **/nc-cms/setup_database_mysql.php** from your web server now.

5. Installation complete! Move onto the next section to integrate your first page and see it in action.

### Integration Instructions

Integrate into any web page file (**.html**, **.htm**, **.php**). If you would like to use it with an **.html** or **.htm** file, first convert it to **.php** by changing the file extension. (Also be sure to change your internal links to **.php** where necessary.)

1. To activate nc-cms on a page, insert the following code before your opening `<html>` tag. It is possible that you may need to change this path depending on where you've uploaded nc-cms.

        <?php require('nc-cms/system/start.php'); ?>

2. To enable "title editing", insert the following code inside your `<title>` tag. **custom_name** can be anything you like. Alphanumeric and underscore characters are supported. We personally recommend a naming convention of page_title (Examples: **home_title**, **about_title**). *TIP:* If you'd like another page to use the same title, use the same **custom_name**.

        <?php nc_title('custom_name'); ?>

3. Include the CSS used by nc-cms: insert the following code inside of your `<head>` tag.

        <link rel="stylesheet" type="text/css" media="screen" href="<?php nc_get_cp_css_directory(); ?>" />

4. When logged into nc-cms, this will enable the control panel to appear. Insert the following code just after your opening `<body>` tag.

        <?php nc_get_cp(); ?>

5. The following lines will place editable content areas. These will display any content assigned to the **custom_name**, and when you are logged in, will enable you to edit them. If the assigned content does not exist, a placeholder will be created automatically. Insert these anywhere in between your `<body>` tags.

    These editable content areas come in two flavours: **HTML** and **string**.

    The **HTML** content area is the most common. These areas can contain many paragraphs, images, headers, links and more. Define an HTML area like this:

        <?php nc_content_html('custom_name'); ?>

    The **string** content area is used for single lines of text. When used, these are generally placed inline between the header and paragraph tags themselves as per your convenience. Define a String area like this:

        <?php nc_content_string('custom_name'); ?>

    Again, **custom_name** can be anything you wish. I personally recommend a naming convention of page_content (Example: home_main, home_sidebar, about_contact, all_copyright etc.).

    *TIP:* If you would like to display the same content across multiple pages, use the same **custom_name**. This is useful for content such as *copyright information*.

6. Login Link (Optional). You may want to place a link on the page for easy access to the nc-cms login page. If this is the case, use the following line of code.

        <?php nc_login_link(); ?>

    Or even simpler:

        <a href="/nc-cms">Login</a>

### Managing Content

1. Visit the page you would like to edit (you will be re-directed here after login).

2. If you created a login link, use that now. Otherwise you can get to the login page by visiting the **/nc-cms** directory directly (Example: **http://www.example.com/nc-cms**. If the login is a success, you will re-directed to the previous page in editor mode.

3. You will notice two things after successfully logging in: A new control panel will appear at the top of your page. Depending on how many editable content areas you added during your integration, there will be a number of edit buttons scattered throughout your web page. Use these buttons to edit the section associated with it.

4. Edit buttons will direct you to a content editor. Make your changes here and click save. If all has been done correctly, you should be redirected to your live page, with your changes applied.

5. Logout! Logging out is essential for preventing unauthorized changes to your website by anyone using the same computer.

### Upgrading

You may want to upgrade your version of nc-cms down the road.

1. Back up your settings and content: FTP into your web server. Go to the **/nc-cms** directory and back up your **/nc-cms/config.php** file and **/nc-cms/content** directory.

2. Delete the **/nc-cms** directory and replace it with the new version. Download the latest version of nc-cms. Remove the **/nc-cms** directory that is currently on your web server. Extract and upload the new **/nc-cms** folder to the former installation location on your web server.

3. Re-upload your **config.php** file and **/content** directory to the new nc-cms installation. Replace as necessary.

4. Upgrade complete! You can confirm your update by logging into nc-cms. The version number is displayed in the top-left of the menu bar. Remember, you may need to explicitly clear your browser cache after the update in order to see all of the changes.
