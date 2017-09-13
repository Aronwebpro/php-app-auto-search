<?php

require_once('controller.php');

class Home extends Controller {

	protected function Index() {
		//Load page view
		$viewmodel = "home_view.php";
		$this->return_view($viewmodel, true);
	}
	
	
}

?>