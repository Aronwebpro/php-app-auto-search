<?php
//Here is placed old functions and method used to build 


	protected function view2() {
		    $maker = 'Acura';
		    $year = '2010';
			$model = 'RDX';
		    $engine = '2.3L L4 Turbocharged';
		    $type = "Sedan";
		    $doors = "4D";
		    $power = "200 hp";
		    $transmission = 'Automatic';
		    $gas_mileage = '24/34';
		    $acceleration = '0-60mph - 6.5s';
		    

		    $view_file = 'parts_view2.php';
			include(LIB_PATH."/template/"."header.php");
		 	include(LIB_PATH."/template/"."body.php");
		 	//include(LIB_PATH."/template/"."footer.php");
	}
	
	
	protected function view3() {
		  
		    $view_file = 'vehicle_details_by_vin.php';
			include(LIB_PATH."/template/"."header.php");
		 	include(LIB_PATH."/template/"."body.php");
		 	include(LIB_PATH."/template/"."footer.php");
	}






?>