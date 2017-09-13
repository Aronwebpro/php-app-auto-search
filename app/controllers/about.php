<?php

require_once('controller.php');

class About extends Controller {
	
	protected function Index() {
		//Load page view
		$viewmodel = "about_view.php";
		$this->return_view($viewmodel, true);
	}
}


?>