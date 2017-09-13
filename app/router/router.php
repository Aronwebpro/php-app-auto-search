<?php
require_once(LIB_PATH.'config/loader.php');

// Router Class
class Router {
	private $controller;
	private $action;
	private $urlvalues;
	
	//store the URL values on object creation
	public function __construct($urlvalues) {
		$this->urlvalues = $urlvalues;
		if ($this->urlvalues['controller'] == "") {
			$this->controller = "home";
		} else {
			$this->controller = $this->urlvalues['controller'];
		}
		
		if ($this->urlvalues['action'] == NULL || $this->urlvalues['action'] == [] ) {
			$this->action = "index";
		} else {
			$this->action = $this->urlvalues['action'];
		}
	}
	
	//establish the requested controller as an object
	public function create_controller() {
		//does the class exist
		if (class_exists($this->controller, false)) {
			$parents = class_parents($this->controller);
			
			//does the requested controller extend the Main Controller class
			if (in_array("Controller", $parents)) {
				
				//does the class contain the requested method
				if (method_exists($this->controller, $this->action)) {
					return new $this->controller($this->action,$this->urlvalues);
				} else {
					return new $this->controller('index',$this->urlvalues);
				}
				
			} else {
				//bad controller error
				redirect_to('error');
			}
			
		} else {
			//bad controller error
			return new Error('index', $this->urlvalues );
		
		}
	}
}

/*Initiate router*********************/
$uri = str_replace("%3F", "?", urlencode($_SERVER['REQUEST_URI']));
$url = explode ("%2F",$uri);
$action = strtok($url[2], '?');

$_GET['parameter1'] = strtok($url[3], '?');
$_GET['parameter2'] = strtok($url[4], '?');
$_GET['parameter3'] = strtok($url[5], '?');


$urls = array(
	'controller' => $url[1],
	'action' => $action );
	
//Create Router instance	
$router = new Router($urls);

//Create Controller
$controller = $router->create_controller();

//Execute Controller's action
$controller->execute_action();


?>