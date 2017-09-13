<?php 
require_once(LIB_PATH.'config/loader.php');


class Year extends Model {
    
    
    protected static $table_name="years";
	protected static $db_fields=array('id', 'year_number');
	public $id;
	public $year_number;

	
	public static function get_all_years() {
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name);
		return !empty($result_array) ? $result_array : false;
	}
	
	public static function check_year($year_number) {
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE year_number='".$database->escape_value($year_number)."'");
		return !empty($result_array) ? true : false;
	}
	
}




?>