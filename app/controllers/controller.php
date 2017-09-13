<?php 
require_once(LIB_PATH.'config/loader.php');

class Controller {
	protected $urlvalues;
	protected $action;
	protected $page_title;
	protected $errors = [];
	
	public function __construct($action, $urlvalues) {
		$this->action = $action;
		$this->urlvalues = $urlvalues;
		$this->pageName();
	}
	

	/*
	*********** Page Actions **********
	*/
	public function execute_action() {
		return $this->{$this->action}();
	}
	
	
	/*
	*********** Load Page Template **********
	*/
	public function return_view($view_file, $full_view = true, $global_variables = [], $template_parts = array('header' => '', 'body' => '', 'footer' => '') ) {
		if ($full_view == true) {
			include(LIB_PATH."/template/"."header.php");
		 	include(LIB_PATH."/template/"."body.php");
		 	include(LIB_PATH."/template/"."footer.php");
		} else {
			include(LIB_PATH."/views/".$view_file);
		}
	}
	
	/*
	*********** Template Parts **********
	*/
	
	//Load Header
	protected function loadHeader() {
		require(LIB_PATH.'template/header.php');
	}
	//Load page Footer
	protected function loadFooter() {
		require(LIB_PATH.'template/footer.php');
	}
	
	//Set Page Name
	protected function pageName() {
		$page = get_class($this);
		if ($page == 'Controller') {
			$this->page_title = 'Home';
		} else {
			$this->page_title = $page;
		}
	}
	
	//Load page Head Assets
	protected function loadHeadAssets() {
		require(LIB_PATH.'config/headAssets.php');
	}
	
	protected function loadFooterAssets() {
		require(LIB_PATH.'config/footerAssets.php');
	}
	

	//Establish connection to API
	protected function api_request($apiURL) {
		
		try {
			$fp = @fopen($apiURL, 'r', false);
			if(!$fp) {
				throw new Exception($http_response_header[0]);
				$response = NULL;
			} else {
				$response = @stream_get_contents($fp);
			}
		}
		catch(Exception $e) {
			if ($e->getMessage() == 'HTTP/1.1 403 Forbidden') {
				array_push($this->errors, 'Sorry, this request has gone over quota limit');
			} else {
				array_push($this->errors, 'Sorry, we can\'t complete this request');
			}
			
		} 
 
		 return $response;
	}
	
	
	
}

?>