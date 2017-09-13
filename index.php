<?php 

// Define Directory separator (\ for windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

//Define SiteRoot Path
defined('SITE_ROOT') ? null : define('SITE_ROOT','');

//Define App folder
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.'app'.DS);

//Load Necessary Modules
require_once(LIB_PATH.'config/loader.php'); 


//Require Router
require_once(LIB_PATH.'router/router.php');





//Close Database connection after actions
$db->closed_connection();

?>
