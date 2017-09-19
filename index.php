<?php 

// Define Directory separator (\ for windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

//Define SiteRoot Path
defined('SITE_ROOT') ? null : define('SITE_ROOT', __DIR__.DS);

//Define App folder
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.'app'.DS);

//Define Root Url
defined('ROOT_URL') ? null : define('ROOT_URL', "");

//Load Necessary Modules
require_once(LIB_PATH.'config/loader.php'); 


//Require Router
require_once(LIB_PATH.'router/router.php');





//Close Database connection after actions
$db->closed_connection();

?>
