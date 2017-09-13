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
			echo '<script>alert("Sorry this API has reach over limit"); window.location = "/auto"; </script>';
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
					$image_title = 'assets/images/vehicle_title_placeholdar150x100.png';
				}
				
				$image_model_path = $decoded_photos->photos[1]->sources[1]->link->href;
			    $image_model = $edmunds_path.$image_model_path;
			    if(!isset($image_model_path)) {
					$image_model = 'assets/images/model_image_placeholder.png';
				}    	
		    	
		    	
				//Edmunds Model Images
				$images = $decoded_photos->photos;
			} else {
				$images = '';
				$image_title = 'assets/images/vehicle_title_placeholdar150x100.png';
				$image_model = 'assets/images/model_image_placeholder.png';
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
					 redirect_to('/auto/search/'.$maker.'.'.$year);
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
			$image_title = 'assets/images/vehicle_title_placeholdar150x100.png';
		}
		
		$image_model_path = $decoded_photos->photos[1]->sources[1]->link->href;
	    $image_model = $edmunds_path.$image_model_path;
	    if(!isset($image_model_path)) {
			$image_model = 'assets/images/model_image_placeholder.png';
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
		$image_frame = "/assets/images/image_gallery_placeholder.png";
		
		include(LIB_PATH."/views/view_images_carousel_items/"."carousel_template.php");
	}
	
	//Generate List of Images View by Edm Api Args = images or indicators
	protected function generate_images_list($photos_array='', $args) {
		
		$x = $photos_array[0]->sources;
		$photos_index = getBiggestSize($x);
		$edmunds_path = 'https://media.ed.edmunds-media.com';
		$image_frame = "/assets/images/image_gallery_placeholder.png";
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





	//Methods for Testing*********************************** 
	protected function vin_test() {
		
		/************** Model details part *****************/
		//Vin Sample
		$vin1 = '{"make":{"id":200003381,"name":"Toyota","niceName":"toyota"},"model":{"id":"Toyota_Yaris_iA","name":"Yaris iA","niceName":"yaris-ia"},"transmission":{"id":"401670366","name":"6A","equipmentType":"TRANSMISSION","availability":"STANDARD","automaticType":"Shiftable automatic","transmissionType":"AUTOMATIC","numberOfSpeeds":"6"},"drivenWheels":"front wheel drive","numOfDoors":"4","options":[{"category":"Additional Fees","options":[{"id":"401670397","name":"50 State Emissions","equipmentType":"OPTION","availability":"All"}]}],"colors":[{"category":"Interior","options":[{"id":"401670407","name":"Mid-Blue Black","equipmentType":"COLOR","availability":"USED"}]},{"category":"Exterior","options":[{"id":"401670410","name":"Sapphire","equipmentType":"COLOR","availability":"USED"}]}],"price":{"baseMSRP":17050.0,"baseInvoice":16369.0,"deliveryCharges":885.0,"estimateTmv":false},"categories":{"market":"N/A","EPAClass":"Subcompact Cars","vehicleSize":"Compact","primaryBodyType":"Car","vehicleStyle":"Sedan","vehicleType":"Car"},"vin":"3MYDLBYV2HY150229","squishVin":"3MYDLBYVHY","years":[{"id":401628159,"year":2017,"styles":[{"id":401670365,"name":"4dr Sedan (1.5L 4cyl 6A)","submodel":{"body":"Sedan","modelName":"Yaris iA Sedan","niceName":"sedan"},"trim":"Base"}]}],"matchingType":"VIN","MPG":{"highway":"40","city":"32"}}';
		//$vin2 = '{"make":{"id":200003381,"name":"Toyota","niceName":"toyota"},"model":{"id":"Toyota_Yaris","name":"Yaris","niceName":"yaris"},"transmission":{"id":"200039504","name":"4A","equipmentType":"TRANSMISSION","availability":"STANDARD","transmissionType":"AUTOMATIC","numberOfSpeeds":"4"},"drivenWheels":"front wheel drive","numOfDoors":"4","options":[],"colors":[{"category":"Interior","options":[{"id":"200039433","name":"Dark Charcoal","equipmentType":"COLOR","availability":"USED"}]},{"category":"Exterior","options":[{"id":"200039420","name":"Meteorite Metallic","equipmentType":"COLOR","availability":"USED"}]}],"manufacturerCode":"1462","price":{"baseMSRP":13705,"baseInvoice":13020,"deliveryCharges":760,"usedTmvRetail":6136,"usedPrivateParty":4773,"usedTradeIn":3564,"estimateTmv":false,"tmvRecommendedRating":0},"categories":{"market":"Hatchback","EPAClass":"Subcompact Cars","vehicleSize":"Compact","primaryBodyType":"Car","vehicleStyle":"4dr Hatchback","vehicleType":"Car"},"vin":"JTDKT4K38A5284884","squishVin":"JTDKT4K3A5","years":[{"id":100529009,"year":2010,"styles":[{"id":101200500,"name":"4dr Hatchback (1.5L 4cyl 4A)","submodel":{"body":"Hatchback","modelName":"Yaris Hatchback","niceName":"hatchback"},"trim":"Base"}]}],"matchingType":"SQUISHVIN","MPG":{"highway":"35","city":"29"}}';

		//Parse VIN code with Edmunds API
		//$this->edm_vin();
		//$vin = $this->vin_respond;
		$vin = 'JTDKT4K38A5284884';
		$decoded_vin = json_decode($vin1);

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
		    
		 
		 
		 
		/************** Model image part *****************/    
	    
	    $edm_images2 = '{"photos":[{"title":"2017 Toyota Yaris iA Base Sedan Exterior","category":"EXTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"FQ","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Exterior","category":"EXTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"RQ","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Headlamp Detail","category":"EXTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"EDETAIL","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Interior","category":"INTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"I","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Dashboard","category":"INTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"D","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Interior","category":"INTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"RSD","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]}],"photosCount":6,"links":[{"rel":"first","href":"/api/media/v2/styles/401670365/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=1"},{"rel":"last","href":"/api/media/v2/styles/401670365/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=1"}]}';
	    //$edm_images3 = '{"photos":[{"title":"2010 Toyota Yaris Sedan","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Sedan"],"shotTypeAbbreviation":"FQ","styleIds":["101200501","101200502"],"exactStyleIds":["101200501","101200502"]},{"title":"2010 Toyota Yaris Sedan","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Sedan"],"shotTypeAbbreviation":"FQ","styleIds":["101200501","101200502"],"exactStyleIds":["101200501","101200502"]},{"title":"2010 Toyota Yaris 2dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"FQ","styleIds":["101200497","101200498"],"exactStyleIds":["101200497","101200498"]},{"title":"2010 Toyota Yaris 4dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"FQ","styleIds":["101200499","101200500"],"exactStyleIds":["101200499","101200500"]},{"title":"2010 Toyota Yaris 2dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"FQ","styleIds":["101200497","101200498"],"exactStyleIds":["101200497","101200498"]},{"title":"2010 Toyota Yaris Sedan","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Sedan"],"shotTypeAbbreviation":"RQ","styleIds":["101200501","101200502"],"exactStyleIds":["101200501","101200502"]},{"title":"2010 Toyota Yaris 2dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"RQ","styleIds":["101200497","101200498"],"exactStyleIds":["101200497","101200498"]},{"title":"2010 Toyota Yaris 4dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"RQ","styleIds":["101200499","101200500"],"exactStyleIds":["101200499","101200500"]},{"title":"2010 Toyota Yaris 4dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"S","styleIds":["101200499","101200500"],"exactStyleIds":["101200499","101200500"]},{"title":"2010 Toyota Yaris Sedan","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Sedan"],"shotTypeAbbreviation":"S","styleIds":["101200501","101200502"],"exactStyleIds":["101200501","101200502"]}],"photosCount":39,"links":[{"rel":"first","href":"/api/media/v2/styles/101200500/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=1"},{"rel":"next","href":"/api/media/v2/styles/101200500/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=2"},{"rel":"last","href":"/api/media/v2/styles/101200500/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=4"}]}';
	    
	    
	    //Get Json of Edmunds Photos
	    //global $edm_images; 
	    $this->edm_photos_by_style_id($edmund_style_id);
		$decoded_photos = json_decode($edm_images2);
	    $edmunds_path = 'https://media.ed.edmunds-media.com'; 
		    
		    
		    
	    //Assing Title and Model Images
		$image_title_path = $decoded_photos->photos[0]->sources[10]->link->href;
	    $image_title = $edmunds_path.$image_title_path;
	    if(!isset($image_title_path)) {
			$image_title = 'assets/images/vehicle_title_placeholdar150x100.png';
		}
		
		$image_model_path = $decoded_photos->photos[1]->sources[1]->link->href;
	    $image_model = $edmunds_path.$image_model_path;
	    if(!isset($image_model_path)) {
			$image_model = 'assets/images/model_image_placeholder.png';
		}    	
    	
    	
		//Edmunds Model Images
		$images = $decoded_photos->photos;
		
		

		
	    /************** Model Review part *****************/ 
	    //global $edm_reviews;
	    //$this-> edm_reviews_by_style_id($edmund_style_id);
	    $edm_reviews1 = '{"links":[],"averageRating":"5","reviewsCount":1,"reviews":[{"id":"1049628521825918976","legacyId":"1049628521825918976","link":{"rel":"_self","href":"/api/vehiclereviews/v2/1049628521825918976"},"author":{"authorName":"Nicholas","cognitoId":"us-east-1:90e4f7a2-c34f-4016-9cd2-d220cc4de868"},"created":1482152670369,"updated":1482850615609,"title":"I bought this instead of a 2017 Camry LE","text":"If you sit inside of the more expensive Corolla or even a Camry and get a feel for their infotainment system and touchscreen interface and controls and then go inside of the iA and use its center control dial system and gorgeous upright 7-inch  display I dare say that you would heavily consider the iA over the Corolla and yes even the Camry.  It works so well and feels like it was intended for a Mercedes or BMW.  The control inputs show up FAST on the screen and that backup camera is incredibly sharp - I can make out individual blades of grass and pebbles of gravel.  Yes, outward visibility on the iA is very good and with that 7-inch backup camera image quality parking is extremely easy.  No, the perfectly positioned upright 7-inch screen does NOT get washed out by sun glare whatsoever.  Push-button start is a great feature for convenience and if you prefer the silence of not having keys jingle over bumps or swinging and scraping your steering column.  You have to pay for that by going up to the highest trim level in the Corolla and Camry models, but EVERY iA comes with push button start as a standard feature.  As aforementioned the iA is really more Mazda than Toyota and when it comes to seat geometry and comfort this seat hands down is more comfortable than the power adjustable seat on a 2017 Toyota Camry LE, and certainly the absolute torture device of a driver seat in the 2015 RAV4 that I had to trade in last year for its discomfort, and my friend\'s 2016 Corolla which pushes your shoulders in and strains your head forward in a way similar to the RAV4.  Even though the Camry seat has power adjustable lumbar support it is placed too high towards the middle of my back and no matter how much I raise and lower the seat the lumbar support fails to give support where I need it.  Whereas the general shape and architecture of the iA seat is more comfortable even without a manually adjustable lumbar support feature and I have room to spread and rest my shoulders back.  Keep in mind that I have a touchy back after suffering a joint sprain at the L2 and L3 vertebrae several years ago.  However, despite my back problem, I still think that the iA seat provides better overall support compared to the stiffer flatter seats in the Camry.  It\'s also worth mentioning that you can actually rest your head back on the headrest at an upright angle and not feel like the headrest is pushing your head and neck forward like so many other vehicles do these days, especially like the RAV4 and Corolla do.   I tested my perception of the seat comfort of both the iA and Camry by going on 100+ mile test drives over some terribly bumpy roads at highway and in-town speeds and still, even after owning the car for 3 weeks now, the iA is a very comfortable car.  Plus the thick waffle weave fabric on the Camry seats seems overdone and looks like it will trap dirt very easily and be difficult to (keep) clean.  \n\tThe hybrid-competitive and, in some cases, hybrid-besting gas mileage is what moved me to pull the trigger on the iA.  The base 4 cylinder Camry might get into the low 30\'s mpg with conservative driving which, as many reviewers have noted, is subpar for today\'s fuel efficient sedans.  While the iA, even before the engine was fully broken in, was giving me an average of 40.5 mpg of mixed highway and city driving.  Cruising along at a highway speed between 55-65 mph the fuel mileage calculator shows a stable 50+ mpg, well above the EPA rating of 42 mpg.  After I used up the first full tank of gas that came with the car when I bought it I reset my trip meter to see how many miles I would clock before having to fill up again.  The low fuel light did not come on until I had 450 miles showing on the trip meter.  I looked in the manual to see how many gallons are left in the tank when the low fuel indicator light comes on and it\'s about 2 gallons.  I tested that and went on for another 52 miles to get a total of 502.3 miles on the trip meter.  When I topped off the tank I noticed that I did not reach the 12 (11.9) gallon limit, which means that I still had close to half a gallon of gas still left in the tank.  Mind you that I am getting this gas mileage in a regular gasoline 1.5 liter engine with zero assistance from any hybrid technology all the while running the AC intermittently and defrosters in December.  The reported gas mileage of the iA is legitimate and even easily exceeds the EPA ratings.  You will not be disappointed with the fuel economy.     I swear that for around $5,000 less than a 2017 Camry LE I do not find the iA to be much if any noisier at all, save for some light whistling coming in near the top of the driver side window sill at hwy speeds on a windy day.  Overall, the iA is very nimble and even fun to drive, safe, gets unbelievable gas mileage without the price and worry over hybrid tech, has handsome enough looks, and is certainly comfortable for a single person or married couple with small children.","thumbsUpDownCounter":{"thumbsDown":0,"thumbsUp":16},"ratings":[{"type":"PERFORMANCE","value":0},{"type":"COMFORT","value":0},{"type":"FUEL_ECONOMY","value":0},{"type":"FUN_TO_DRIVE","value":0},{"type":"INTERIOR_DESIGN","value":0},{"type":"EXTERIOR_DESIGN","value":0},{"type":"BUILD_QUALITY","value":0},{"type":"RELIABILITY","value":0}],"commentsCount":0,"averageRating":"5","styleId":"401670365","styleName":"4dr Sedan (1.5L 4cyl 6A)","comfortRatingDto":{"comfortRating":5,"frontSeats":"EXCELLENT","rearSeats":"OKAY","gettingInOut":"OKAY","noiseAndVibration":"OKAY","rideComfort":"EXCELLENT"},"interiorRatingDto":{"interiorRating":5,"cargoStorage":"OKAY","instrumentation":"EXCELLENT","interiorDesign":"EXCELLENT","logicOfControls":"EXCELLENT","qualityOfMaterials":"EXCELLENT"},"performanceRatingDto":{"performanceRating":5,"acceleration":"EXCELLENT","braking":"EXCELLENT","roadHolding":"EXCELLENT","shifting":"EXCELLENT","steering":"EXCELLENT"},"reliabilityRatingDto":{"reliabilityRating":5,"repairFrequency":"OKAY","dealershipSupport":"EXCELLENT","engine":"OKAY","transmission":"OKAY","electronics":"OKAY"},"safetyRatingDto":{"safetyRating":5,"headlights":"EXCELLENT","outwardVisibility":"EXCELLENT","parkingAids":"EXCELLENT","rainSnowTraction":"OKAY","activeSafety":"OKAY"},"technologyRatingDto":{"technologyRating":5,"entertainment":"EXCELLENT","navigation":"OKAY","bluetooth":"EXCELLENT","usbPorts":"EXCELLENT","climateControl":"EXCELLENT"},"valueRatingDto":{"valueRating":5,"fuelEconomy":"EXCELLENT","maintenanceCost":"EXCELLENT","purchaseCost":"EXCELLENT","resaleValue":"OKAY","warranty":"OKAY"},"userRating":5,"newReview":true,"version":0,"carDetailsDto":{"combinedMpg":40.5,"odometerMiles":1200,"pricePaid":18000,"purchaseDate":"12/01/2016"}}]} ' ;
		//$edm_reviews2 = '{"links":[],"averageRating":"4.75","reviewsCount":4,"reviews":[{"id":"198801723","legacyId":"198801723","link":{"rel":"_self","href":"/api/vehiclereviews/v2/198801723"},"author":{"authorName":"Doug"},"created":1273260984000,"updated":0,"title":"Fun Car/GR8 MPG","text":"Fun car to drive. Three fill ups to date: 36.7, 37.7 and 38.7 MPG. Lots of room. Pretty smooth ride. Got a GR8 deal and 5 Year 0% financing. What\'s not to like! An absolute winner! Wife thinks it\'s \"really cute.\"","favoriteFeatures":"Seats are pretty comfortable and provide ample room. Also like the center console controls.","suggestedImprovements":"Only one \"would like to have.\" A telescoping steering column would give the driver more choice in driving positions.","thumbsUpDownCounter":{"thumbsDown":0,"thumbsUp":0},"ratings":[{"type":"PERFORMANCE","value":5},{"type":"COMFORT","value":4},{"type":"FUEL_ECONOMY","value":5},{"type":"FUN_TO_DRIVE","value":5},{"type":"INTERIOR_DESIGN","value":5},{"type":"EXTERIOR_DESIGN","value":5},{"type":"BUILD_QUALITY","value":5},{"type":"RELIABILITY","value":5}],"commentsCount":0,"averageRating":"4.875","styleId":"101200500","comfortRatingDto":{"comfortRating":4},"interiorRatingDto":{"interiorRating":5},"performanceRatingDto":{"performanceRating":5},"reliabilityRatingDto":{"reliabilityRating":5},"userRating":4.875,"newReview":false,"version":0},{"id":"195261737","legacyId":"195261737","link":{"rel":"_self","href":"/api/vehiclereviews/v2/195261737"},"author":{"authorName":"mkyaris10"},"created":1268343177000,"updated":0,"title":"A smart choice.","text":"I expected a simple get-around-town ho hum experience. I have been pleasantly surprised. The Yaris won\'t win any drag races, but the versatility of the interior cubbies along with the great seats and good ride quality will make up for it. The gas mileage has been good, 30-36 mpg. My lowest was 28.5 mpg on an all city driving week. I\'d recommend the 5 dr, and go ahead and load it up with all the power options. You won\'t save that much by leaving them off, and they\'re worth it. Edmunds exaggerates the manual vs auto transmission. The auto is fine. The gates are good, and the shifts are smooth. You can\'t beat the Yaris. Just go test drive one.","favoriteFeatures":"The driver side glove box, center mounted gauge cluster, bright headlights, comfortable seats, light in the cargo area, and most of all the great mpg.","suggestedImprovements":"Gas gauge needs a better low level indicator. The flashing gray lcd isn\'t enough of an eye catcher. When you only get gas once every week or two, you need that reminder. Center armrest should be standard. Traction control needs a manual off switch.","thumbsUpDownCounter":{"thumbsDown":1,"thumbsUp":0},"ratings":[{"type":"PERFORMANCE","value":4},{"type":"COMFORT","value":5},{"type":"FUEL_ECONOMY","value":5},{"type":"FUN_TO_DRIVE","value":5},{"type":"INTERIOR_DESIGN","value":5},{"type":"EXTERIOR_DESIGN","value":4},{"type":"BUILD_QUALITY","value":5},{"type":"RELIABILITY","value":5}],"commentsCount":0,"averageRating":"4.75","styleId":"101200500","comfortRatingDto":{"comfortRating":5},"interiorRatingDto":{"interiorRating":5},"performanceRatingDto":{"performanceRating":4},"reliabilityRatingDto":{"reliabilityRating":5},"userRating":4.75,"newReview":false,"version":0},{"id":"191701715","legacyId":"191701715","link":{"rel":"_self","href":"/api/vehiclereviews/v2/191701715"},"author":{"authorName":"Art Adams"},"created":1264618559000,"updated":0,"title":"Love it!","text":"I have only owned this car for 3 weeks so I cannot comment on reliability but I have driven a friends Camry extensively and I am impressed with Toyota in general.  The Yaris is a ball to drive and my only problem is that I want to be traveling all the time and that could eventually get expensive.  The car is, for it\'s size powerful, comfortable and attractive.  The gas tank is only 11.1 gal and I am accustomed to a 20 gal tank but it will go twice as far on a gallon as the Cherokee I traded in so the smaller tank is OK.  All in all I am delighted with it.  ","favoriteFeatures":"MPG, Comfort, power.","suggestedImprovements":"I can\'t think of any.","thumbsUpDownCounter":{"thumbsDown":0,"thumbsUp":0},"ratings":[{"type":"PERFORMANCE","value":5},{"type":"COMFORT","value":5},{"type":"FUEL_ECONOMY","value":5},{"type":"FUN_TO_DRIVE","value":5},{"type":"INTERIOR_DESIGN","value":5},{"type":"EXTERIOR_DESIGN","value":5},{"type":"BUILD_QUALITY","value":5},{"type":"RELIABILITY","value":5}],"commentsCount":0,"averageRating":"5","styleId":"101200500","comfortRatingDto":{"comfortRating":5},"interiorRatingDto":{"interiorRating":5},"performanceRatingDto":{"performanceRating":5},"reliabilityRatingDto":{"reliabilityRating":5},"userRating":5,"newReview":false,"version":0},{"id":"187163035","legacyId":"187163035","link":{"rel":"_self","href":"/api/vehiclereviews/v2/187163035"},"author":{"authorName":"limee925"},"created":1259806643000,"updated":0,"title":"Yaris 4 door","text":"Bought this vehicle as a second car that would be ideal for parking in the city. It fits the purpose there. Surprisingly sprite for a small engine and find that I\'m more than able to keep up with the larger vehicles on the road. It needs more input from the driver than larger cars, but that makes it more engaging and does add a little to the \"fun factor!\" It\'s a good car for what it was built to do. Cheap commuting and relatively comfortable with it. Throttle tip in can be a little aggressive and sudden, but have gotten used to it after 3000 miles.","favoriteFeatures":"Gas mileage. Averaging 36mpg with mixed driving. Dead easy to find a parking space in the city. Relatively spacious in the back with the rear seats reclined.","suggestedImprovements":"Better sound system (it\'s pitiful). Better positioning of the gas pedal as it is set high and causes foot fatigue on long journeys. Instrument panel needs placement in front of the driver.","thumbsUpDownCounter":{"thumbsDown":0,"thumbsUp":0},"ratings":[{"type":"PERFORMANCE","value":4},{"type":"COMFORT","value":4},{"type":"FUEL_ECONOMY","value":5},{"type":"FUN_TO_DRIVE","value":4},{"type":"INTERIOR_DESIGN","value":4},{"type":"EXTERIOR_DESIGN","value":4},{"type":"BUILD_QUALITY","value":5},{"type":"RELIABILITY","value":5}],"commentsCount":0,"averageRating":"4.375","styleId":"101200500","comfortRatingDto":{"comfortRating":4},"interiorRatingDto":{"interiorRating":4},"performanceRatingDto":{"performanceRating":4},"reliabilityRatingDto":{"reliabilityRating":5},"userRating":4.375,"newReview":false,"version":0}]}';
		
		
		$decoded_reviews = json_decode($edm_reviews1);
		$reviews = $decoded_reviews->reviews;																					

		
		$rating_number = round($decoded_reviews->averageRating);
		if(!$rating_number) {
			$rating_number = '0';
		}

			

		//Render Template files	
		$view_file = 'auto_view/vehicle_details_by_vin.php';
		include(LIB_PATH."/template/"."header.php");
	 	include(LIB_PATH."/template/"."body.php");
	 	include(LIB_PATH."/template/"."footer.php");
	}
	
	protected function emodel_test() {
		global $edm_data_by_style_id;
		$edm_data_by_style_id1 = '{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488449,"name":"4dr Sedan (3.5L 6cyl 6A)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"Base"}'; 
		
		/************** Model details part *****************/


		//Parse DATA code with Edmunds API

		$decoded_model = json_decode($edm_data_by_style_id1);

		//Get Edmunds Style Id
		$edmund_style_id = $decoded_model->id;
		
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
	    
	    $edm_images2 = '{"photos":[{"title":"2017 Toyota Yaris iA Base Sedan Exterior","category":"EXTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_fq_oem_2_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"FQ","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Exterior","category":"EXTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"RQ","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Headlamp Detail","category":"EXTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_edetail_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"EDETAIL","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Interior","category":"INTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_i_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"I","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Dashboard","category":"INTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_d_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"D","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]},{"title":"2017 Toyota Yaris iA Base Sedan Interior","category":"INTERIOR","tags":["toyota","toyota-yaris-ia","2017-toyota-yaris-ia"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_1280.jpg"},"extension":"JPG","size":{"width":1280,"height":854}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_1600.jpg"},"extension":"JPG","size":{"width":1600,"height":1067}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_815.jpg"},"extension":"JPG","size":{"width":815,"height":544}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_717.jpg"},"extension":"JPG","size":{"width":717,"height":478}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_600.jpg"},"extension":"JPG","size":{"width":600,"height":400}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris-ia/2017/oem/2017_toyota_yaris-ia_sedan_base_rsd_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris iA"],"years":["2017"],"color":"Graphite","submodels":["Yaris iA Sedan"],"shotTypeAbbreviation":"RSD","styleIds":["401670365","401628167"],"exactStyleIds":["401670365","401628167"]}],"photosCount":6,"links":[{"rel":"first","href":"/api/media/v2/styles/401670365/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=1"},{"rel":"last","href":"/api/media/v2/styles/401670365/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=1"}]}';
	    //$edm_images3 = '{"photos":[{"title":"2010 Toyota Yaris Sedan","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_2_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Sedan"],"shotTypeAbbreviation":"FQ","styleIds":["101200501","101200502"],"exactStyleIds":["101200501","101200502"]},{"title":"2010 Toyota Yaris Sedan","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_fq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Sedan"],"shotTypeAbbreviation":"FQ","styleIds":["101200501","101200502"],"exactStyleIds":["101200501","101200502"]},{"title":"2010 Toyota Yaris 2dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_2_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"FQ","styleIds":["101200497","101200498"],"exactStyleIds":["101200497","101200498"]},{"title":"2010 Toyota Yaris 4dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_fq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"FQ","styleIds":["101200499","101200500"],"exactStyleIds":["101200499","101200500"]},{"title":"2010 Toyota Yaris 2dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_fq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"FQ","styleIds":["101200497","101200498"],"exactStyleIds":["101200497","101200498"]},{"title":"2010 Toyota Yaris Sedan","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_rq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Sedan"],"shotTypeAbbreviation":"RQ","styleIds":["101200501","101200502"],"exactStyleIds":["101200501","101200502"]},{"title":"2010 Toyota Yaris 2dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_2dr-hatchback_base_rq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"RQ","styleIds":["101200497","101200498"],"exactStyleIds":["101200497","101200498"]},{"title":"2010 Toyota Yaris 4dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_rq_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"RQ","styleIds":["101200499","101200500"],"exactStyleIds":["101200499","101200500"]},{"title":"2010 Toyota Yaris 4dr Hatchback","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_4dr-hatchback_base_s_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Hatchback"],"shotTypeAbbreviation":"S","styleIds":["101200499","101200500"],"exactStyleIds":["101200499","101200500"]},{"title":"2010 Toyota Yaris Sedan","category":"EXTERIOR","tags":["toyota","yaris","toyota-yaris","2010","2010-yaris","2010-toyota-yaris"],"provider":"OEM","sources":[{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_98.jpg"},"extension":"JPG","size":{"width":98,"height":65}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_300.jpg"},"extension":"JPG","size":{"width":300,"height":200}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_196.jpg"},"extension":"JPG","size":{"width":196,"height":131}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_500.jpg"},"extension":"JPG","size":{"width":500,"height":315}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_276.jpg"},"extension":"JPG","size":{"width":276,"height":184}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_150.jpg"},"extension":"JPG","size":{"width":150,"height":100}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_175.jpg"},"extension":"JPG","size":{"width":175,"height":110}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_185.jpg"},"extension":"JPG","size":{"width":185,"height":123}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_396.jpg"},"extension":"JPG","size":{"width":396,"height":264}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_400.jpg"},"extension":"JPG","size":{"width":400,"height":267}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_423.jpg"},"extension":"JPG","size":{"width":423,"height":282}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_131.jpg"},"extension":"JPG","size":{"width":131,"height":87}},{"link":{"rel":"self","href":"/toyota/yaris/2010/oem/2010_toyota_yaris_sedan_base_s_oem_1_87.jpg"},"extension":"JPG","size":{"width":87,"height":55}}],"makes":["Toyota"],"models":["Yaris"],"years":["2010"],"submodels":["Yaris Sedan"],"shotTypeAbbreviation":"S","styleIds":["101200501","101200502"],"exactStyleIds":["101200501","101200502"]}],"photosCount":39,"links":[{"rel":"first","href":"/api/media/v2/styles/101200500/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=1"},{"rel":"next","href":"/api/media/v2/styles/101200500/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=2"},{"rel":"last","href":"/api/media/v2/styles/101200500/photos?fmt=json&api_key=ckeqq5qst5dngzmrpzfma2n9&pagesize=10&pagenum=4"}]}';
	    
	    
	    //Get Json of Edmunds Photos
	    global $edm_images; 
	    //$this->edm_photos_by_style_id($edmund_style_id);
		$decoded_photos = json_decode($edm_images2);
	    $edmunds_path = 'https://media.ed.edmunds-media.com'; 
		    
		    
		    
	    //Assing Title and Model Images
		$image_title_path = $decoded_photos->photos[0]->sources[10]->link->href;
	    $image_title = $edmunds_path.$image_title_path;
	    if(!isset($image_title_path)) {
			$image_title = 'assets/images/vehicle_title_placeholdar150x100.png';
		}
		
		$image_model_path = $decoded_photos->photos[1]->sources[1]->link->href;
	    $image_model = $edmunds_path.$image_model_path;
	    if(!isset($image_model_path)) {
			$image_model = 'assets/images/model_image_placeholder.png';
		}    	
    	
    	
		//Edmunds Model Images
		$images = $decoded_photos->photos;
		
		

		
	    /************** Model Review part *****************/ 
	    global $edm_reviews_by_model;
	    //$this-> edm_reviews_by_style_id($edmund_style_id);
	    $edm_reviews = '{"links":[],"averageRating":"5","reviewsCount":1,"reviews":[{"id":"1049628521825918976","legacyId":"1049628521825918976","link":{"rel":"_self","href":"/api/vehiclereviews/v2/1049628521825918976"},"author":{"authorName":"Nicholas","cognitoId":"us-east-1:90e4f7a2-c34f-4016-9cd2-d220cc4de868"},"created":1482152670369,"updated":1482850615609,"title":"I bought this instead of a 2017 Camry LE","text":"If you sit inside of the more expensive Corolla or even a Camry and get a feel for their infotainment system and touchscreen interface and controls and then go inside of the iA and use its center control dial system and gorgeous upright 7-inch  display I dare say that you would heavily consider the iA over the Corolla and yes even the Camry.  It works so well and feels like it was intended for a Mercedes or BMW.  The control inputs show up FAST on the screen and that backup camera is incredibly sharp - I can make out individual blades of grass and pebbles of gravel.  Yes, outward visibility on the iA is very good and with that 7-inch backup camera image quality parking is extremely easy.  No, the perfectly positioned upright 7-inch screen does NOT get washed out by sun glare whatsoever.  Push-button start is a great feature for convenience and if you prefer the silence of not having keys jingle over bumps or swinging and scraping your steering column.  You have to pay for that by going up to the highest trim level in the Corolla and Camry models, but EVERY iA comes with push button start as a standard feature.  As aforementioned the iA is really more Mazda than Toyota and when it comes to seat geometry and comfort this seat hands down is more comfortable than the power adjustable seat on a 2017 Toyota Camry LE, and certainly the absolute torture device of a driver seat in the 2015 RAV4 that I had to trade in last year for its discomfort, and my friend\'s 2016 Corolla which pushes your shoulders in and strains your head forward in a way similar to the RAV4.  Even though the Camry seat has power adjustable lumbar support it is placed too high towards the middle of my back and no matter how much I raise and lower the seat the lumbar support fails to give support where I need it.  Whereas the general shape and architecture of the iA seat is more comfortable even without a manually adjustable lumbar support feature and I have room to spread and rest my shoulders back.  Keep in mind that I have a touchy back after suffering a joint sprain at the L2 and L3 vertebrae several years ago.  However, despite my back problem, I still think that the iA seat provides better overall support compared to the stiffer flatter seats in the Camry.  It\'s also worth mentioning that you can actually rest your head back on the headrest at an upright angle and not feel like the headrest is pushing your head and neck forward like so many other vehicles do these days, especially like the RAV4 and Corolla do.   I tested my perception of the seat comfort of both the iA and Camry by going on 100+ mile test drives over some terribly bumpy roads at highway and in-town speeds and still, even after owning the car for 3 weeks now, the iA is a very comfortable car.  Plus the thick waffle weave fabric on the Camry seats seems overdone and looks like it will trap dirt very easily and be difficult to (keep) clean.  \n\tThe hybrid-competitive and, in some cases, hybrid-besting gas mileage is what moved me to pull the trigger on the iA.  The base 4 cylinder Camry might get into the low 30\'s mpg with conservative driving which, as many reviewers have noted, is subpar for today\'s fuel efficient sedans.  While the iA, even before the engine was fully broken in, was giving me an average of 40.5 mpg of mixed highway and city driving.  Cruising along at a highway speed between 55-65 mph the fuel mileage calculator shows a stable 50+ mpg, well above the EPA rating of 42 mpg.  After I used up the first full tank of gas that came with the car when I bought it I reset my trip meter to see how many miles I would clock before having to fill up again.  The low fuel light did not come on until I had 450 miles showing on the trip meter.  I looked in the manual to see how many gallons are left in the tank when the low fuel indicator light comes on and it\'s about 2 gallons.  I tested that and went on for another 52 miles to get a total of 502.3 miles on the trip meter.  When I topped off the tank I noticed that I did not reach the 12 (11.9) gallon limit, which means that I still had close to half a gallon of gas still left in the tank.  Mind you that I am getting this gas mileage in a regular gasoline 1.5 liter engine with zero assistance from any hybrid technology all the while running the AC intermittently and defrosters in December.  The reported gas mileage of the iA is legitimate and even easily exceeds the EPA ratings.  You will not be disappointed with the fuel economy.     I swear that for around $5,000 less than a 2017 Camry LE I do not find the iA to be much if any noisier at all, save for some light whistling coming in near the top of the driver side window sill at hwy speeds on a windy day.  Overall, the iA is very nimble and even fun to drive, safe, gets unbelievable gas mileage without the price and worry over hybrid tech, has handsome enough looks, and is certainly comfortable for a single person or married couple with small children.","thumbsUpDownCounter":{"thumbsDown":0,"thumbsUp":16},"ratings":[{"type":"PERFORMANCE","value":0},{"type":"COMFORT","value":0},{"type":"FUEL_ECONOMY","value":0},{"type":"FUN_TO_DRIVE","value":0},{"type":"INTERIOR_DESIGN","value":0},{"type":"EXTERIOR_DESIGN","value":0},{"type":"BUILD_QUALITY","value":0},{"type":"RELIABILITY","value":0}],"commentsCount":0,"averageRating":"5","styleId":"401670365","styleName":"4dr Sedan (1.5L 4cyl 6A)","comfortRatingDto":{"comfortRating":5,"frontSeats":"EXCELLENT","rearSeats":"OKAY","gettingInOut":"OKAY","noiseAndVibration":"OKAY","rideComfort":"EXCELLENT"},"interiorRatingDto":{"interiorRating":5,"cargoStorage":"OKAY","instrumentation":"EXCELLENT","interiorDesign":"EXCELLENT","logicOfControls":"EXCELLENT","qualityOfMaterials":"EXCELLENT"},"performanceRatingDto":{"performanceRating":5,"acceleration":"EXCELLENT","braking":"EXCELLENT","roadHolding":"EXCELLENT","shifting":"EXCELLENT","steering":"EXCELLENT"},"reliabilityRatingDto":{"reliabilityRating":5,"repairFrequency":"OKAY","dealershipSupport":"EXCELLENT","engine":"OKAY","transmission":"OKAY","electronics":"OKAY"},"safetyRatingDto":{"safetyRating":5,"headlights":"EXCELLENT","outwardVisibility":"EXCELLENT","parkingAids":"EXCELLENT","rainSnowTraction":"OKAY","activeSafety":"OKAY"},"technologyRatingDto":{"technologyRating":5,"entertainment":"EXCELLENT","navigation":"OKAY","bluetooth":"EXCELLENT","usbPorts":"EXCELLENT","climateControl":"EXCELLENT"},"valueRatingDto":{"valueRating":5,"fuelEconomy":"EXCELLENT","maintenanceCost":"EXCELLENT","purchaseCost":"EXCELLENT","resaleValue":"OKAY","warranty":"OKAY"},"userRating":5,"newReview":true,"version":0,"carDetailsDto":{"combinedMpg":40.5,"odometerMiles":1200,"pricePaid":18000,"purchaseDate":"12/01/2016"}}]} ' ;
		//$edm_reviews2 = '{"links":[],"averageRating":"4.75","reviewsCount":4,"reviews":[{"id":"198801723","legacyId":"198801723","link":{"rel":"_self","href":"/api/vehiclereviews/v2/198801723"},"author":{"authorName":"Doug"},"created":1273260984000,"updated":0,"title":"Fun Car/GR8 MPG","text":"Fun car to drive. Three fill ups to date: 36.7, 37.7 and 38.7 MPG. Lots of room. Pretty smooth ride. Got a GR8 deal and 5 Year 0% financing. What\'s not to like! An absolute winner! Wife thinks it\'s \"really cute.\"","favoriteFeatures":"Seats are pretty comfortable and provide ample room. Also like the center console controls.","suggestedImprovements":"Only one \"would like to have.\" A telescoping steering column would give the driver more choice in driving positions.","thumbsUpDownCounter":{"thumbsDown":0,"thumbsUp":0},"ratings":[{"type":"PERFORMANCE","value":5},{"type":"COMFORT","value":4},{"type":"FUEL_ECONOMY","value":5},{"type":"FUN_TO_DRIVE","value":5},{"type":"INTERIOR_DESIGN","value":5},{"type":"EXTERIOR_DESIGN","value":5},{"type":"BUILD_QUALITY","value":5},{"type":"RELIABILITY","value":5}],"commentsCount":0,"averageRating":"4.875","styleId":"101200500","comfortRatingDto":{"comfortRating":4},"interiorRatingDto":{"interiorRating":5},"performanceRatingDto":{"performanceRating":5},"reliabilityRatingDto":{"reliabilityRating":5},"userRating":4.875,"newReview":false,"version":0},{"id":"195261737","legacyId":"195261737","link":{"rel":"_self","href":"/api/vehiclereviews/v2/195261737"},"author":{"authorName":"mkyaris10"},"created":1268343177000,"updated":0,"title":"A smart choice.","text":"I expected a simple get-around-town ho hum experience. I have been pleasantly surprised. The Yaris won\'t win any drag races, but the versatility of the interior cubbies along with the great seats and good ride quality will make up for it. The gas mileage has been good, 30-36 mpg. My lowest was 28.5 mpg on an all city driving week. I\'d recommend the 5 dr, and go ahead and load it up with all the power options. You won\'t save that much by leaving them off, and they\'re worth it. Edmunds exaggerates the manual vs auto transmission. The auto is fine. The gates are good, and the shifts are smooth. You can\'t beat the Yaris. Just go test drive one.","favoriteFeatures":"The driver side glove box, center mounted gauge cluster, bright headlights, comfortable seats, light in the cargo area, and most of all the great mpg.","suggestedImprovements":"Gas gauge needs a better low level indicator. The flashing gray lcd isn\'t enough of an eye catcher. When you only get gas once every week or two, you need that reminder. Center armrest should be standard. Traction control needs a manual off switch.","thumbsUpDownCounter":{"thumbsDown":1,"thumbsUp":0},"ratings":[{"type":"PERFORMANCE","value":4},{"type":"COMFORT","value":5},{"type":"FUEL_ECONOMY","value":5},{"type":"FUN_TO_DRIVE","value":5},{"type":"INTERIOR_DESIGN","value":5},{"type":"EXTERIOR_DESIGN","value":4},{"type":"BUILD_QUALITY","value":5},{"type":"RELIABILITY","value":5}],"commentsCount":0,"averageRating":"4.75","styleId":"101200500","comfortRatingDto":{"comfortRating":5},"interiorRatingDto":{"interiorRating":5},"performanceRatingDto":{"performanceRating":4},"reliabilityRatingDto":{"reliabilityRating":5},"userRating":4.75,"newReview":false,"version":0},{"id":"191701715","legacyId":"191701715","link":{"rel":"_self","href":"/api/vehiclereviews/v2/191701715"},"author":{"authorName":"Art Adams"},"created":1264618559000,"updated":0,"title":"Love it!","text":"I have only owned this car for 3 weeks so I cannot comment on reliability but I have driven a friends Camry extensively and I am impressed with Toyota in general.  The Yaris is a ball to drive and my only problem is that I want to be traveling all the time and that could eventually get expensive.  The car is, for it\'s size powerful, comfortable and attractive.  The gas tank is only 11.1 gal and I am accustomed to a 20 gal tank but it will go twice as far on a gallon as the Cherokee I traded in so the smaller tank is OK.  All in all I am delighted with it.  ","favoriteFeatures":"MPG, Comfort, power.","suggestedImprovements":"I can\'t think of any.","thumbsUpDownCounter":{"thumbsDown":0,"thumbsUp":0},"ratings":[{"type":"PERFORMANCE","value":5},{"type":"COMFORT","value":5},{"type":"FUEL_ECONOMY","value":5},{"type":"FUN_TO_DRIVE","value":5},{"type":"INTERIOR_DESIGN","value":5},{"type":"EXTERIOR_DESIGN","value":5},{"type":"BUILD_QUALITY","value":5},{"type":"RELIABILITY","value":5}],"commentsCount":0,"averageRating":"5","styleId":"101200500","comfortRatingDto":{"comfortRating":5},"interiorRatingDto":{"interiorRating":5},"performanceRatingDto":{"performanceRating":5},"reliabilityRatingDto":{"reliabilityRating":5},"userRating":5,"newReview":false,"version":0},{"id":"187163035","legacyId":"187163035","link":{"rel":"_self","href":"/api/vehiclereviews/v2/187163035"},"author":{"authorName":"limee925"},"created":1259806643000,"updated":0,"title":"Yaris 4 door","text":"Bought this vehicle as a second car that would be ideal for parking in the city. It fits the purpose there. Surprisingly sprite for a small engine and find that I\'m more than able to keep up with the larger vehicles on the road. It needs more input from the driver than larger cars, but that makes it more engaging and does add a little to the \"fun factor!\" It\'s a good car for what it was built to do. Cheap commuting and relatively comfortable with it. Throttle tip in can be a little aggressive and sudden, but have gotten used to it after 3000 miles.","favoriteFeatures":"Gas mileage. Averaging 36mpg with mixed driving. Dead easy to find a parking space in the city. Relatively spacious in the back with the rear seats reclined.","suggestedImprovements":"Better sound system (it\'s pitiful). Better positioning of the gas pedal as it is set high and causes foot fatigue on long journeys. Instrument panel needs placement in front of the driver.","thumbsUpDownCounter":{"thumbsDown":0,"thumbsUp":0},"ratings":[{"type":"PERFORMANCE","value":4},{"type":"COMFORT","value":4},{"type":"FUEL_ECONOMY","value":5},{"type":"FUN_TO_DRIVE","value":4},{"type":"INTERIOR_DESIGN","value":4},{"type":"EXTERIOR_DESIGN","value":4},{"type":"BUILD_QUALITY","value":5},{"type":"RELIABILITY","value":5}],"commentsCount":0,"averageRating":"4.375","styleId":"101200500","comfortRatingDto":{"comfortRating":4},"interiorRatingDto":{"interiorRating":4},"performanceRatingDto":{"performanceRating":4},"reliabilityRatingDto":{"reliabilityRating":5},"userRating":4.375,"newReview":false,"version":0}]}';
		
		if($edm_reviews) {
			$decoded_reviews = json_decode($edm_reviews);
		} else {
			$decoded_reviews = '';
		}
		

			
		if ($decoded_reviews->status !== 'NOT_FOUND' && $decoded_reviews->reviews !== '' && $decoded_reviews != NULL) {
			$reviews = $decoded_reviews->reviews;																					
			$rating_number = round($decoded_reviews->averageRating);
		} else {
			$rating_number = 0;
			$reviews = '';
		}
			

		//View Files	
		$view_file = 'auto_view/vehicle_details_by_model.php';
		$page_name = 'auto';
		require(LIB_PATH."/template/"."header.php");
		require(LIB_PATH."/template/"."body.php");
 		require(LIB_PATH."/template/"."footer.php");
		//$this->ReturnView('auto_view/vehicle_details_by_model.php', true);
	}
	
	protected function edm_styles_1() {
		$json = '{"styles":[{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488454,"name":"Technology Package 4dr Sedan (3.5L 6cyl 6A)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"Technology Package"},{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488453,"name":"Advance Package 4dr Sedan (3.5L 6cyl 6A)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"Advance Package"},{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488456,"name":"SH-AWD w/Technology Package 4dr Sedan AWD (3.7L 6cyl 6M)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"SH-AWD w/Technology Package"},{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488455,"name":"SH-AWD w/Technology Package 4dr Sedan AWD (3.7L 6cyl 6A)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"SH-AWD w/Technology Package"},{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488450,"name":"SH-AWD 4dr Sedan AWD (3.7L 6cyl 6A)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"SH-AWD"},{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488449,"name":"4dr Sedan (3.5L 6cyl 6A)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"Base"},{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488452,"name":"SH-AWD w/Advance Package 4dr Sedan AWD (3.7L 6cyl 6A)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"SH-AWD w/Advance Package"},{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_TL","name":"TL","niceName":"tl"},"id":200488451,"name":"Special Edition 4dr Sedan (3.5L 6cyl 6A)","year":{"id":200488448,"year":2014},"submodel":{"body":"Sedan","modelName":"TL Sedan","niceName":"sedan"},"trim":"Special Edition"}],"stylesCount":8}';
		echo json_encode($json);
	}
	
	protected function edm_styles_2() {
		$json = '{"styles":[{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_NSX","name":"NSX","niceName":"nsx"},"id":25,"name":"2dr Coupe","year":{"id":953,"year":2000},"submodel":{"body":"Coupe","modelName":"NSX Coupe","niceName":"coupe"},"trim":"Base"},{"make":{"id":200002038,"name":"Acura","niceName":"acura"},"model":{"id":"Acura_NSX","name":"NSX","niceName":"nsx"},"id":12,"name":"T 2dr Coupe","year":{"id":953,"year":2000},"submodel":{"body":"Coupe","modelName":"NSX Coupe","niceName":"coupe"},"trim":"T"}],"stylesCount":2}';
		echo json_encode($json);
	}	
	
	
} ?>






















