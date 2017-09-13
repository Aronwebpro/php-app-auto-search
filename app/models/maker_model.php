<?php 
require_once(LIB_PATH.'config/loader.php');


class Maker extends Model {
    
    
    protected static $table_name="makers";
	protected static $db_fields=array('id', 'Make_ID', 'Make_Name');
	public $id;
	public $Make_ID;
	public $Make_Name;
	
	

	public static function find_car_maker($maker='') {
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE Make_Name='".$database->escape_value($maker)."'");
		return !empty($result_array) ? $result_array : false;
	}
	
}




?>