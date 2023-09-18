<?php

namespace Classes;

class Database
{ 
    public static $insert_id = 0; 
    
	public static function connect() { 
		return mysqli_connect(App::get("DB_HOST") , App::get("DB_USERNAME"), App::get("DB_PASSWORD"), App::get("DB_DATABASE"));
	}
	
	public static function operate(string $sql) {
		$mysqli = self::connect();
        mysqli_set_charset($mysqli, "UTF8");
        mysqli_real_escape_string($mysqli, $sql);
        
        mysqli_query($mysqli, $sql);
        self::$insert_id = $mysqli->insert_id;
        mysqli_close($mysqli); 
	}
	
	public static function select(string $table, string $filter) {
		$sql = "SELECT * FROM ".$table." WHERE ".$filter;
		
		$mysqli = self::connect();
		mysqli_set_charset($mysqli, "UTF8");
		mysqli_real_escape_string($mysqli, $sql);
		 
		$result = mysqli_query($mysqli, $sql);
		$rows = [];
		while ($row = mysqli_fetch_assoc($result))
			$rows[] = $row;
		
		mysqli_close($mysqli);
		return $rows ?? null;
	}
	
	public static function update(string $table, string $values, string $filter) {
		self::operate("UPDATE ".$table." SET ".$values." WHERE ".$filter);
	}
	
	public static function insert(string $table, string $columns, string $values) {
		self::operate("INSERT INTO ".$table." (".$columns.") VALUES (".$values.")");
	}
		
	public static function delete(string $table, string $filter) {
		self::operate("DELETE FROM ".$table." WHERE ".$filter);
	}
}

?>