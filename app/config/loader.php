<?php  

//load config file
require_once("config.php");


//load helper functions and classes
require_once(LIB_PATH.'helpers/functions.php');
require_once(LIB_PATH."helpers/mysqldatabase.php");

//load controllers
require_once(LIB_PATH."controllers/controller.php");
require_once(LIB_PATH."controllers/home.php");
require_once(LIB_PATH."controllers/about.php");
require_once(LIB_PATH."controllers/auto.php");
require_once(LIB_PATH."controllers/error.php");
require_once(LIB_PATH."controllers/playground.php");

//load models
//require_once(LIB_PATH."models/database.php");
require_once(LIB_PATH."models/model.php");
require_once(LIB_PATH."models/part_model.php");
require_once(LIB_PATH."models/car_model.php");
require_once(LIB_PATH."models/maker_model.php");
require_once(LIB_PATH."models/year_model.php");



?>