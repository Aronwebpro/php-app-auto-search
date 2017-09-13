<?php 
require_once(LIB_PATH.'config/loader.php');


class Car extends Model {
    
    
    protected static $table_name="cars";
	protected static $db_fields=array('id', 'maker', 'year', 'model', 'engine');
	public $id;
	public $maker;
	public $year;
	public $model;
	public $engine;	
	
	public static function find_car_model($maker='', $year='') {
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE maker='".$database->escape_value($maker)."' AND  year='".$database->escape_value($year)."'");
		return !empty($result_array) ? $result_array : false;
	}
	
	public static function check_model($model,$year) {
		global $database;
		$result_array = static::find_by_sql("SELECT model FROM ".static::$table_name." WHERE model='".$database->escape_value($model)."' AND  year='".$database->escape_value($year)."' LIMIT 1");
		return !empty($result_array) ? true : false;
	}
	public static function find_unique_car_model($maker='', $year='') {
		global $database;
		$result_array = static::find_by_sql("SELECT DISTINCT model FROM ".static::$table_name." WHERE maker='".$database->escape_value($maker)."' AND  year='".$database->escape_value($year)."'");
		return !empty($result_array) ? $result_array : false;
	}
	

	
}




?>