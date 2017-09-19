<?php

require_once('controller.php');

class ErrorPage extends Controller {
	
	protected function Index() {
		$viewmodel = "error.php";
		$this->return_view($viewmodel, true);
	}
}


?>