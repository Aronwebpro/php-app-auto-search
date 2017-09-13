<?php 
require_once(LIB_PATH.'config/loader.php');

class Model {

		
	public static function find_all() {		
		
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
	}

	public static function find_by_id($id=0) {
		global $database;
		$result_array = static::find_by_sql("SELECT * FROM 
			".static::$table_name." WHERE id=".$database->escape_value($id)." LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		return array_shift($row);
	}
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}	

	private static function instantiate($record) {
		//Could check that $record exist and is an array
		$class_name = get_called_class();
		$object = new $class_name;

		//More dynamic, short-form approach
		foreach ($record as $attribute => $value) {
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}

	private function has_attribute($attribute) {
		//get_object_vars returns an associative array with all attributes
		//(incl. private ones!) as the keys and their current values as the value
		$object_vars = get_object_vars($this);
		//We don't care about the value, we just want to know if the key exist
		//Will return true or false
		return array_key_exists($attribute, $object_vars);
	}
	
	public function save() {
		// A new record won't have an id yet.
		return isset($this->id) ? $this->update() : $this->create();
	}

	private function has_sql_attribute() {
		//get_object_vars returns an associative array with all attributes
		//(incl. private ones!) as the keys and their current values as the value
		$object_vars = $this->sql_attributes();
		//We don't care about the value, we just want to know if the key exist
		//Will return true or false
		return array_key_exists($attribute, $object_vars); 
	}

	protected function sql_attributes() {
		// return an array of attribute keys and their values
		$sql_attributes = array(); 
		foreach (static::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$sql_attributes[$field] = $this->$field;
			}
		}
		return $sql_attributes;
	}

	protected function sanitized_sql_attributes() {
		global $database;
		$clean_attributes = array();
		//sanitize the values before submitting
		//Note: does not alter the actual value of each attribute
		foreach ($this->sql_attributes() as $key => $value) {
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
		//return get_object_vars($this);
	}

	public function create() {
		global $database;
		$attributes = $this->sanitized_sql_attributes();
		$sql = "INSERT INTO ".static::$table_name." ( ";
		$sql .= join(", ", array_keys($attributes));
		$sql .= " ) VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "' )";

		if($database->query($sql)) {
			$this->id = $database->insert_id();
			return true;
		} else {
			return false;

		}	
	}

	protected function update() {
		$attributes = $this->sanitized_sql_attributes();
		$attributes_pairs = array();
		foreach ($$attributes as $key => $value) {
			$attributes_pairs[] = "{$key}='{$value}'";
		}

		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attributes_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

	public function delete() {
		global $database;

		$sql = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE id=". $database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	public function file_size_as_text() {
		if($this->size<1024) {
			return "{$this->size} bytes";
		} elseif($this->size<1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}

}
 
?> 