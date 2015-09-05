<?php

// All CMS actions start here.
// For associated views, check out /nc-cms/system/views.

// Make sure we have the nc-cms system loaded.
require('system/start.php');

$cms = new NCCms();
$cms->Run();
