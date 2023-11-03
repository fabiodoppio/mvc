<?php

/**
 * mvc
 * Model View Controller (MVC) design pattern for simple web applications.
 *
 * @see     https://github.com/fabiodoppio/mvc
 *
 * @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 * @license https://opensource.org/license/mit/ MIT License
 */


namespace MVC;

/**
 * Database Class
 *
 * The Database class provides essential database operations for interacting with a MySQL database.
 * It handles database connections, executing SQL queries, and managing query results.
 */
class Database {
 
	// Property to store the last inserted ID after an INSERT operation.
    public static $insert_id = 0; 
    
	/**
     * Establish a connection to the MySQL database using the application's configuration.
     *
     * @return 	\mysqli|false 	A MySQLi object representing the database connection or false if the connection fails.
     * @throws 				Exception If the database connection fails.
     */
	public static function connect() { 
		if (!($mysqli = @mysqli_connect(App::get("DB_HOST") , App::get("DB_USERNAME"), App::get("DB_PASSWORD"), App::get("DB_DATABASE"))))
			throw new Exception(_("Connection to MySQL database failed."));

		return $mysqli;
	}
	
	/**
     * Execute an SQL query on the database.
     *
     * @param 	string 	     $sql 	The SQL query to execute.
     * @return array|null 	          An array of query results or null if no results are found.
     * @throws 				     Exception If the query execution fails.
     */
	public static function operate(string $sql) {
	     $mysqli = self::connect();
          mysqli_set_charset($mysqli, "UTF8");
          mysqli_real_escape_string($mysqli, $sql);
        
          $result = mysqli_query($mysqli, $sql);
          self::$insert_id = $mysqli->insert_id;

          $rows = [];
          if (is_object($result))
               while ($row = mysqli_fetch_assoc($result))
                    $rows[] = $row;
          
          mysqli_close($mysqli); 
          return $rows ?? null;
	}
	
	/**
     * Execute a SELECT query on the database and retrieve results.
     *
     * @param 	string 		$table 		The name of the database table.
     * @param 	string 		$filter 	     The WHERE clause filter for the query.
     * @return array|null 	               An array of query results or null if no results are found.
     */
	public static function select(string $table, string $filter) {
          return self::operate("SELECT * FROM ".$table." WHERE ".$filter);
	}
	
	/**
     * Execute an UPDATE query on the database.
     *
     * @param 	string 	$table 	The name of the database table.
     * @param 	string 	$values 	The SET clause for the UPDATE query.
     * @param 	string 	$filter 	The WHERE clause filter for the query.
     */
	public static function update(string $table, string $values, string $filter) {
		self::operate("UPDATE ".$table." SET ".$values." WHERE ".$filter);
	}
	
	/**
     * Execute an INSERT query on the database.
     *
     * @param 	string 	$table 	The name of the database table.
     * @param 	string 	$columns 	The column names for the INSERT query.
     * @param 	string 	$values 	The values to insert into the table.
     */
	public static function insert(string $table, string $columns, string $values) {
		self::operate("INSERT INTO ".$table." (".$columns.") VALUES (".$values.")");
	}
		
	/**
     * Execute a DELETE query on the database.
     *
     * @param 	string 	$table 		The name of the database table.
     * @param 	string 	$filter 	The WHERE clause filter for the query.
     */
	public static function delete(string $table, string $filter) {
		self::operate("DELETE FROM ".$table." WHERE ".$filter);
	}

}

?>