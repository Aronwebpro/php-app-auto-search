<?php

require_once('controller.php');

class Error extends Controller {
	
	protected function Index() {
		$viewmodel = "error.php";
		$this->return_view($viewmodel, true);
	}
}


?>