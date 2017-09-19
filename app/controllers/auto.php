<?php

require(LIB_PATH.'config/loader.php');

class Auto extends Controller {

	private $api_key = API_KEY;
	protected $vin_respond; 
	
	/************ Page Actions ***********/
	//Index Action
	protected function index() {
		$this->return_view('auto_view/auto_view.php', true);
	}


	
	/*****************Parts page select fields**********************/

	//Action Generate Makers List 
	protected function makers() {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    

		    $maker = new Maker();
		    $makers_list = $maker->find_all();
		    $makers_list = json_encode($makers_list);
		    echo $makers_list;
				
		} else {
			redirect_to('/auto');
		}
	}	
	
	//Action to get model list by Maker and Year
	public function model() {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    		if (isset($_GET['maker']) && isset($_GET['year'])) {
	    
		    $maker = $_GET['maker'];
		    $year = $_GET['year'];
		    
		    $model = new Car();
		    
		    $model_list = $model->find_car_model($maker, $year);
		    $model_list = json_encode($model_list);
		    
		    echo $model_list;
			}	
		}
	}
	
	/*****************NHTSA functions**********************/
	//Vin Data by NHTSA
	protected function nhtsa_vin() {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    		if (isset($_GET['vin'])) {
    			$vin = $_GET['vin'];
    			$format =  'json';
				//$vin = 'JTDKB20U057031782';
				
				$apiURL = "https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/".$vin."?format=".$format;
				
				$fp = fopen($apiURL, 'rb', false);
				
				if(!$fp)
				{
					echo "in first if";
				}
				$response = @stream_get_contents($fp);
				if($response == false)
				{
					echo "in second if";
				}
				echo $response;
				//echo $apiURL;
			}	
		}
	}
	

	
	/*****************Get Data by VIN or Style ID **********************/
	
	//Get Edmunds Data by VIN
	protected function edm_vin() {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    		if (isset($_GET['vin'])) {
    			global 	$edm_vin_data;
    			global $vin;
			    $vin = htmlentities($_GET['vin']);
			    $manufactoreCode = '';
				
				$api_key = $this->api_key;
				$apiURL = "https://api.edmunds.com/api/vehicle/v2/vins/".$vin."?manufacturerCode=".$manufactoreCode."&fmt=json&api_key=".$api_key;
				
				
				$edm_vin_data = $this->api_request($apiURL);
			}	
		} else {
			array_push($this->errors, 'Direct request');
		}
	}
	//Get Edmunds Data by Style Id
	protected function edm_data_by_style_id($edmund_style_id = '') {

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    		global $edm_data_by_style_id; 
		
			if ($edmund_style_id == '') {
				$edmund_style_id = $_GET['styleId'];
			}
    		if ($edmund_style_id !== '') {
				
				$api_key = $this->api_key;
				$apiURL =  "https://api.edmunds.com/api/vehicle/v2/styles/".$edmund_style_id."?fmt=json&api_key=".$api_key;
							
				$edm_data_by_style_id = $this->api_request($apiURL);
			}	
		} else {
			array_push($this->errors, 'Direct request');
		}
	}
	//Get Edmunds Photos by Style Id
	protected function edm_photos_by_style_id($edmund_style_id = '') {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    	global $edm_images; 
			if ($edmund_style_id == '') {
				$edmund_style_id = $_GET['styleId'];
			}
    		
    		if ($edmund_style_id !== '') {
				
				$api_key = $this->api_key;
				$apiURL =  "https://api.edmunds.com/api/media/v2/styles/".$edmund_style_id."/photos?api_key=".$api_key."&fmt=json";
				
				$edm_images = $this->api_request($apiURL);
				
			}	
		} else {
			array_push($this->errors, 'Direct request');
		}
	}
	//Get Edmunds Reviews by Style Id
	protected function edm_reviews_by_style_id($edmund_style_id = '') {

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    		global $edm_reviews; 
		
			if ($edmund_style_id == '') {
				$edmund_style_id = $_GET['styleId'];
			}
    		
    		if ($edmund_style_id !== '') {
				
				$api_key = $this->api_key;
							
				$apiURL =  "https://api.edmunds.com/api/vehiclereviews/v2/styles/".$edmund_style_id."?fmt=json&api_key=".$api_key;
				
				$edm_reviews = $this->api_request($apiURL);
				
			}	
		} else {
			array_push($this->errors, 'Direct request');
		}
	}
	
	
	/*****************For Make/Model/Model select**********************/
	
	//Get Edmunds all available strims by Make, Year, Model
	protected function edm_styles() {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    		if (isset($_GET['maker']) && isset($_GET['year']) && isset($_GET['model'])) {
    			$maker = htmlentities($_GET['maker']);
			    $year = htmlentities($_GET['year']);
			    $model = htmlentities($_GET['model']);

				
				$api_key = $this->api_key;

				$apiURL = "https://api.edmunds.com/api/vehicle/v2/".$maker."/".$model."/".$year."/styles?json&api_key=".$api_key;

				$response = $this->api_request($apiURL);
				
				if(!$this->errors) {
					echo $response;	
				} else {
					$error['error'] = $this->errors;
					echo json_encode($error);
				}

				
			}	
		}
	}
	//Get Edmunds Photos by Make, Year, Model
	protected function edm_photos() {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    		global $edm_photo_by_model;
    		if (isset($_GET['maker']) && isset($_GET['year']) && isset($_GET['model'])) {
    			$maker = htmlentities($_GET['maker']);
			    $year = htmlentities($_GET['year']);
			    $model = htmlentities($_GET['model']);
				
				$api_key = $this->api_key;
				$apiURL = "https://api.edmunds.com/api/media/v2/".$maker."/".$model."/".$year."/photos?api_key=".$api_key;

				
				$edm_photo_by_model = $this->api_request($apiURL);
				//echo $edm_photo_by_model;
				
			}	
		} else {
			array_push($this->errors, 'Direct request');
		}
	}
	//Get Edmunds Photos by Make, Year, Model
	protected function edm_reviews() {
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {    
    		global $edm_reviews_by_model;
    		if (isset($_GET['maker']) && isset($_GET['year']) && isset($_GET['model'])) {
    			$maker = htmlentities($_GET['maker']);
			    $year = htmlentities($_GET['year']);
			    $model = htmlentities($_GET['model']);
				
				$api_key = $this->api_key;
				$apiURL = "https://api.edmunds.com/api/vehiclereviews/v2/".$maker."/".$model."/".$year."?fmt=json&api_key=".$api_key;
					
				$edm_reviews_by_model = $this->api_request($apiURL);
			}	
		} else {
			array_push($this->errors, 'Direct request');
		}
	}



	/*****************Pages **********************/
	
	//Vin Details Page****************************************
	protected function vin() {

		try {
			$this->edm_vin();
		}
		catch(Exception $e) {
			$this->errors = $e->getMessage();
		}
		
		if(!$this->errors) {
			$this->execute_vin_action();	
		} else {
			echo '<script>alert("Sorry this API has reach over limit"); window.location = "'.ROOT_URL.'/auto"; </script>';
		}
	}
	protected function execute_vin_action() {

		/************** Model details part *****************/
		global $edm_vin_data;
		global $vin;
		//Parse VIN code with Edmunds API
		
		if ($edm_vin_data) {
			
			$vin_data = $edm_vin_data;
			$decoded_vin = json_decode($vin_data);

			//Get Edmunds Style Id
			$edmund_style_id = $decoded_vin->years[0]->styles[0]->id;
			
			//Assign Header part variables
			$maker = $decoded_vin->make->name;
		    $year = $decoded_vin->years[0]->year;
			$model = $decoded_vin->model->name;
			
			//Assign Vehicle details part variables
			$type = $decoded_vin->years[0]->styles[0]->submodel->body;
		    preg_match('#\((.*?)\)#', $decoded_vin->years[0]->styles[0]->name, $engine);
		    $engine = $engine[1];
		    $trim = $decoded_vin->years[0]->styles[0]->trim; 
		    $doors = $decoded_vin->numOfDoors."D";
		    $transmission =  $decoded_vin->transmission->name." ".$decoded_vin->transmission->transmissionType;
		    $driven_wheels = $decoded_vin->drivenWheels;
		    $gas_mileage = $decoded_vin->MPG->city."/".$decoded_vin->MPG->highway;
		    $exterior_color = $decoded_vin->colors[1]->options[0]->name;
		    $interior_color = $decoded_vin->colors[0]->options[0]->name;
		}
		
		    
		/************** Model image part *****************/    
	    
	    global $edm_images; 
	    
	    if ($edmund_style_id) {
	    //Get Json of Edmunds Photos
	    if(!$this->errors) {
			$this->edm_photos_by_style_id($edmund_style_id);
		}
	    	
	    }
		if ($edm_images) {
			$decoded_photos = json_decode($edm_images);
		    $edmunds_path = 'https://media.ed.edmunds-media.com'; 
		    //Assing Title and Model Images
			$image_title_path = $decoded_photos->photos[0]->sources[10]->link->href;
		    $image_title = $edmunds_path.$image_title_path;
		    if(!isset($image_title_path)) {
				$image_title = '/assets/images/vehicle_title_placeholdar150x100.png';
			}
			$image_model_path = $decoded_photos->photos[1]->sources[1]->link->href;
		    $image_model = $edmunds_path.$image_model_path;
		    if(!isset($image_model_path)) {
				$image_model = '/assets/images/model_image_placeholder.png';
			}   
			
			//Edmunds Model Images
			$images = $decoded_photos->photos;
		}
		
		
		
	    /************** Model Review part *****************/ 
	    global $edm_reviews;
	    //Get Edm reviews
	    $this-> edm_reviews_by_style_id($edmund_style_id);
	    
		$decoded_reviews = json_decode($edm_reviews);
		$reviews = $decoded_reviews->reviews;																					

		//Rating number
		$rating_number = round($decoded_reviews->averageRating);
		if(!$rating_number) {
			$rating_number = '0';
		}

			

		//View Files	
		$view_file = 'auto_view/vehicle_details_by_vin.php';
		include(LIB_PATH."/template/"."header.php");
	 	include(LIB_PATH."/template/"."body.php");
	 	include(LIB_PATH."/template/"."footer.php");
	}
	
	
	
	//Model Details Page***************************************
	protected function emodel() {
		try {
			$this->edm_data_by_style_id();
			} 
		catch(Exception $e) {
			$this->errors = $e->getMessage();
		}
		
		if(!$this->errors) {
			$this->execute_custom_model_action();
		} else {
			redirect_to('auto');
		}
	}
	protected function execute_custom_model_action() {
		/************** Model details part *****************/
		global $edm_data_by_style_id;
	
			if ($edm_data_by_style_id !== NULL) {
				$decoded_model = json_decode($edm_data_by_style_id);
				//Assign Header part variables
				$maker = $decoded_model->make->name;
			    $year = $decoded_model->year->year;
				$model = $decoded_model->model->name;
					
				//Assign Vehicle details part variables
				$type = $decoded_model->submodel->body;
			    preg_match('#\((.*?)\)#', $decoded_model->name, $engine);
			    $engine_name = $engine[1];
			    $engine = substr($engine[1],0, -2);
			    $trim = $decoded_model->trim; 
			    $doors = substr($decoded_model->name, 0, 1)." Doors";
			    if(substr($engine_name, -1) == 'A') { $trans = ' Automatic'; } else if (substr($engine_name, -1) == 'M'){ $trans = ' Manual'; }
			    $transmission = substr(substr($engine_name, -2), 0, 1)."-Speed".$trans ;
		    
			} else {
				$maker = 'Maker';
			    $year = 'Year';
				$model = 'Model';
				$type = 'N/A';
			    $engine_name = 'N/A';
			    $engine = 'N/A';
			    $trim = 'N/A'; 
			    $doors = 'N/A';
			    $transmission = 'N/A';
			}
			    
			/************** Model image part *****************/    
		    
		    //Get Json of Edmunds Photos
		    if(!$this->errors) {
				$this->edm_photos();
			}
		    global $edm_photo_by_model; 
		    
			$decoded_photos = json_decode($edm_photo_by_model);
		    $edmunds_path = 'https://media.ed.edmunds-media.com'; 
			    
			    
			if ($edm_photo_by_model !== NULL) {
				    
			    //Assing Title and Model Images
				$image_title_path = $decoded_photos->photos[0]->sources[10]->link->href;
			    $image_title = $edmunds_path.$image_title_path;
			    if(!isset($image_title_path)) {
					$image_title = ROOT_URL.'assets/images/vehicle_title_placeholdar150x100.png';
				}
				
				$image_model_path = $decoded_photos->photos[1]->sources[1]->link->href;
			    $image_model = $edmunds_path.$image_model_path;
			    if(!isset($image_model_path)) {
					$image_model = ROOT_URL.'assets/images/model_image_placeholder.png';
				}    	
		    	
		    	
				//Edmunds Model Images
				$images = $decoded_photos->photos;
			} else {
				$images = '';
				$image_title = ROOT_URL.'assets/images/vehicle_title_placeholdar150x100.png';
				$image_model = ROOT_URL.'assets/images/model_image_placeholder.png';
			}
			
			
	
			
		    /************** Model Review part *****************/ 
		    if(!$this->errors) {
				$this-> edm_reviews();
			}
		    global $edm_reviews_by_model;
			$decoded_reviews = json_decode($edm_reviews_by_model);

			if ($edm_reviews_by_model !== NULL && $decoded_reviews->reviews !== NULL) {
				
				$reviews = $decoded_reviews->reviews;																					
				$rating_number = round($decoded_reviews->averageRating);
			} else {
				$rating_number = 0;
				$reviews = '';
			}
			
			
			//View Files	
			$view_file = 'auto_view/vehicle_details_by_model.php';
			$page_name = 'auto';
			include(LIB_PATH."/template/"."header.php");
		 	include(LIB_PATH."/template/"."body.php");
		 	include(LIB_PATH."/template/"."footer.php");

	}
	
	//Search Page**********************************************
	protected function search() {
		if (!empty($_GET['parameter1'])) {
			$parameters = explode (".", $_GET['parameter1']);
			$maker = $parameters[0];
			$year = $parameters[1];
			$model = $parameters[2];
			
			
			if(!empty($maker) ) {
				$maker_obj = new Maker();
				$exist_maker = $maker_obj->find_car_maker(str_replace('-', ' ', $maker));
				if($exist_maker == false) {
				 $maker = null;
				 redirect_to('/auto/search/');
				} 
			}
			if(empty($year) && $maker !== null ) {
				$year_obj = new Year();
				$all_years = $year_obj->get_all_years();
				$search_body_view = 'search_view/search_body_view/search_years.php';
			} 
			if(!empty($year)) {
				$year_obj = new Year();
				$exist_year = $year_obj->check_year($year);
				if($exist_year == false) {
					echo $year;
					$year = null;
					redirect_to('/auto/search/'.$maker);
					
				}
			}
			if(empty($model) && $year !== null ) {
				$model_obj = new Car();
				$exist_model = $model_obj->find_unique_car_model(str_replace('-', ' ', $maker), $year);
				$search_body_view = 'search_view/search_body_view/search_models.php';
				
			} 
			if(!empty($model)) {
				$model_obj = new Car();
				$exist_model = $model_obj->check_model(str_replace('-', ' ', $model), $year);
				if($exist_model == false) {
					$model= null;
					 redirect_to(ROOT_URL.'/auto/search/'.$maker.'.'.$year);
				} else {
					$search_body_view = 'search_view/search_model_details.php';
				}
			}

		} else {
			$maker_obj = new Maker();
	    	$makers_list = $maker_obj->find_all();
	    	
		    
		    $search_body_view = 'search_view/search_body_view/search_all_makers.php';
		}
		
		//View Files	
		$view_file = 'search_view/search_view.php';
		$global_variable = [];
		include(LIB_PATH."/template/"."header.php");
	 	include(LIB_PATH."/template/"."body.php");
	 	include(LIB_PATH."/template/"."footer.php");	
	}
	
	
	
	
	
	//Load Vehicle details page 
	protected function emodel_by_style_id() {
		global $edm_data_by_style_id;
		$this->edm_data_by_style_id();
		
		/************** Model details part *****************/
		
		//Get DATA with Edmunds API
		$decoded_model = json_decode($edm_data_by_style_id);

		
		//Assign Header part variables
		$maker = $decoded_model->make->name;
	    $year = $decoded_model->year->year;
		$model = $decoded_model->model->name;
			
			
		
		//Assign Vehicle details part variables
		$type = $decoded_model->submodel->body;
	    preg_match('#\((.*?)\)#', $decoded_model->name, $engine);
	    $engine_name = $engine[1];
	    $engine = substr($engine[1],0, -2);
	    $trim = $decoded_model->trim; 
	    $doors = substr($decoded_model->name, 0, 1)." Doors";
	    if(substr($engine_name, -1) == 'A') { $trans = ' Automatic'; } else if (substr($engine_name, -1) == 'M'){ $trans = ' Manual'; }
	    
	    $transmission = substr(substr($engine_name, -2), 0, 1)."-Speed".$trans ;
	    $driven_wheels = $decoded_model->drivenWheels;
	    $gas_mileage = $decoded_model->MPG->city."/".$decoded_vin->MPG->highway;
	    $exterior_color = $decoded_model->colors[1]->options[0]->name;
	    $interior_color = $decoded_model->colors[0]->options[0]->name;
		    
		 
		 
		 
		/************** Model image part *****************/    
	    
	    //Get Json of Edmunds Photos
	    global $edm_images; 
	    $this->edm_photos_by_style_id();
		$decoded_photos = json_decode($edm_images);
	    $edmunds_path = 'https://media.ed.edmunds-media.com'; 
		    
		    
		    
	    //Assing Title and Model Images
		$image_title_path = $decoded_photos->photos[0]->sources[10]->link->href;
	    $image_title = $edmunds_path.$image_title_path;
	    if(!isset($image_title_path)) {
			$image_title = ROOT_URL.'assets/images/vehicle_title_placeholdar150x100.png';
		}
		
		$image_model_path = $decoded_photos->photos[1]->sources[1]->link->href;
	    $image_model = $edmunds_path.$image_model_path;
	    if(!isset($image_model_path)) {
			$image_model = ROOT_URL.'assets/images/model_image_placeholder.png';
		}    	
    	
    	
		//Edmunds Model Images
		$images = $decoded_photos->photos;
		
		

		
	    /************** Model Review part *****************/ 
	    
	    
	    global $edm_reviews;
	    $this-> edm_reviews_by_style_id();
		
		$decoded_reviews = json_decode($edm_reviews);
		$reviews = $decoded_reviews->reviews;																					

		
		$rating_number = round($decoded_reviews->averageRating);
		if(!$rating_number) {
			$rating_number = '0';
		}

			

		//View Files	
		$view_file = 'auto_view/vehicle_details_by_model.php';
		$page_name = 'auto';
		include(LIB_PATH."/template/"."header.php");
	 	include(LIB_PATH."/template/"."body.php");
	 	include(LIB_PATH."/template/"."footer.php");
	}
	
	
	//Methods to generate template parts********************
	
	//Generate List of Review View by Edm Api
	protected function generate_reviews($reviews_array='') {
		$review_id_number = 0;
		//round_review_rating($user_rating);
		
		if($reviews_array !=='') {
			foreach($reviews_array as $review) {
				$review_title = $review->title;
				$review_text = $review->text;
				$review_author = $review->author->authorName;
				$user_rating = round_review_rating($review->userRating);
				$review_date = date('m/d/Y', $review->created/1000);
				include(LIB_PATH."/views/"."reviews_widget.php");
				$review_id_number++;
			}
		}
	}
	
	//Generate Images Carousel template
	protected function image_carousel($images) {

		if(!images) {
			$image_class = 'carousel-image';
		}
		//Placeholder fot
		$image_frame = ROOT_URL."/assets/images/image_gallery_placeholder.png";
		
		include(LIB_PATH."/views/view_images_carousel_items/"."carousel_template.php");
	}
	
	//Generate List of Images View by Edm Api Args = images or indicators
	protected function generate_images_list($photos_array='', $args) {
		
		$x = $photos_array[0]->sources;
		$photos_index = getBiggestSize($x);
		$edmunds_path = 'https://media.ed.edmunds-media.com';
		$image_frame = ROOT_URL."/assets/images/image_gallery_placeholder.png";
		$i = 0;
		//Generate list of images
		if($args == 'images') {
			foreach($photos_array as $photo) {
				if($i == 0) {
					$active = 'active';
				} else {
					$active = '';
				}
				$model_image = $photo->sources[$photos_index]->link->href;
				include(LIB_PATH."/views/view_images_carousel_items/"."images_for_carousel.php");
				$i++;
			}
		}
		//Generate list of indicators
		if($args == 'indicators') {
			foreach ($photos_array as $indicator) {
				$indicator_number = $i;
				include(LIB_PATH."/views/view_images_carousel_items/"."carousel_indicators.php");
				$i++;
			}
		}
	}
	
	protected function generate_find_parts_modal($maker = '', $year='', $model='', $engine='', $trim = '', $image_title = 'assets/images/vehicle_title_placeholdar150x100.png') {
		include(LIB_PATH."/views/auto_view/modal_view.php");
	}


} ?>






















