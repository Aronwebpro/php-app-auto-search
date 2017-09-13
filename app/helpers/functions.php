<?php 

function strip_zero_from_date($marked_string="" ) {
	//first remove the marked zeros
	$no_zeros = str_replace('*0', '', $marked_string);
	//then remove any remaining marks
	$cleaned_string = str_replace('*', '', $no_zeros);
	return $cleaned_string;
}

function redirect_to( $location = NULL ) {
	if ($location != NULL ) {
		header("Location: {$location}");
		exit;
	}
}
function output_message($message="") {
	if (!empty($message)) {
		return "<p class=\"message\">{$message}</p>";
	} else {
		return "";
	}
}

function __autoload($class_name) {
	$class_name = strtolower($class_name);
	$path = LIB_PATH.DS."{$class_name}.php";
	if (file_exists($path)) {
		require_once($path);
	} else {
		die("The file {$class_name}.php could not be found.");
	}
}

function include_view($template="") {
	include(SITE_ROOT.DS.'public'.DS.'view'.DS.$template);
}

function include_admin_view($template="") {
	include(SITE_ROOT.DS.'public'.DS.'admin'.DS.'view'.DS.$template);
}


function log_action($action, $message="") {
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
	if($handle = fopen($logfile, 'a')) { //append
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\r\n";
		fwrite($handle, $content);
		fclose($handle);
		if($new) {
			chmod($logfile, 0755);
		} 
	} else {
		echo "Could not open log file for writing.";
	}
}

function datetime_to_text($datetime="") {
	$unixdatetime = strtotime($datetime);
	return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}

//Load CSS or JavaScrip asstes on site
function getJavascript_local($js_file="") {
	$js_html = '<script type="text/javascript" src="/assets/js/'.$js_file.'"></script>';
	echo $js_html;
}
function getJavascript_cdn($js_link="") {
	$js_html = $js_link;
	echo $js_html;
}
function hosts_url($ssl=false) {
	$headers = apache_request_headers(); 
	if($ssl == true) {
		$host = 'https://'.$headers['host'];
	} else {
		$host = 'http://'.$headers['host'];
	}

	return $host;
}
function getCss_local($css_file="") {
	global $host;
	$css_html = '<link rel="stylesheet" type="text/css" href="/assets/css/'.$css_file.'">';
	echo $css_html;
}
function getCss_cdn($css_file="") {
	$css_html = $css_file;
	echo $css_html;
}
function isJson($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

function round_review_rating($user_rating) {

	if($user_rating < 0.5) {
		 $user_rating = 0; 
	} elseif ($user_rating >= 0.5 && $user_rating < 1) {
		$user_rating = 0.5;
	} elseif ($user_rating >= 1 && $user_rating < 1.5) {
		$user_rating = 1;
	} elseif ($user_rating >= 1.5 && $user_rating < 2) {
		$user_rating = 1.5;
	} elseif ($user_rating >= 2 && $user_rating < 2.5) {
		$user_rating = 2;
	} elseif ($user_rating >= 2.5 && $user_rating < 3) {
		$user_rating = 2.5;
	} elseif ($user_rating >= 3 && $user_rating < 3.5) {
		$user_rating = 3;
	} elseif ($user_rating >= 3.5 && $user_rating < 4) {
		$user_rating = 3.5;
	} elseif ($user_rating >= 4 && $user_rating <= 4.5) {
		$user_rating = 4;
	} elseif ($user_rating >= 4.5 && $user_rating < 5) {
		$user_rating = 4.5;
	} elseif ($user_rating >= 5) {
		$user_rating = 5;
	}
	
	return $user_rating;
}

function getBiggestSize($array='') {
	$size_array = [];
	
	foreach($array as $item) {
		$image_width = $item->size->width;
	    array_push($size_array, $image_width);
	}	
	$bigest_image_index = array_keys($size_array, max($size_array));
	
	return $bigest_image_index[0];
}

function error_message($errors = []) {
	echo '<div class="error-block"><ul>';
	if ($errors != '') {
			foreach($errors as $error) {
			echo '<li>'.$error.'</li>';
		}
	}
	echo '</ul></div>';
}

function remove_utf8_bom($text) {
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}


?>