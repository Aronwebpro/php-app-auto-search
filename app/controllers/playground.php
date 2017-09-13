<?php

require_once('controller.php');

class Playground extends Controller {

	protected function Index() {
		//Load page view
		$viewmodel = "play_view.php";
		$this->return_view($viewmodel, true);
	}
	
	
}

?>