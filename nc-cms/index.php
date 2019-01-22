<?php
/** 
* This will display a Log In page for CMS management.
* It is accessible by visiting: http://localhost/nc-cms (or your own server equivalent).
*/
require('system/start.php');

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_EMAIL);

$cms = new NCCms();
$cms->Manage();
