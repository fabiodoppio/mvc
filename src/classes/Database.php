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

    // Property to manage a pool of reusable database connections.
    private static $connections = [];
    
     /**
     * Establish a connection to the MySQL database using the application's configuration.
     *
     * @return      \mysqli|false       A MySQLi object representing the database connection or false if the connection fails.
     * @throws                          Exception If the database connection fails.
     */
	public static function connect() { 
          if (!empty(self::$connections))
               return array_pop(self::$connections);

		if (!($mysqli = mysqli_connect(App::get("DB_HOST") , App::get("DB_USERNAME"), App::get("DB_PASSWORD"), App::get("DB_DATABASE"))))
			throw new Exception(_("Connection to MySQL database failed."), 1014);

		return $mysqli;
	}
	
	/**
     * Execute an SQL query on the database.
     *
     * @param       string         $sql      The SQL query to execute.
     * @param       array          $params   Parameters to bind the prepared statement.
     * @return      array|null               An array of query results or null if no results are found.
     * @throws                               Exception If the query execution fails.
     */
     public static function query(string $sql, array $params = []) {
          $mysqli = self::connect();
          $mysqli->set_charset("UTF8");
          $stmt = $mysqli->prepare($sql);
     
          if (!empty($params)) {
               $types = str_repeat('s', count($params));
               $stmt->bind_param($types, ...$params);
          }
     
          if (!$stmt->execute())
               throw new Exception(_("Execute statement to MySQL database failed."), 1016);

          $result = $stmt->get_result();
          self::$insert_id = $mysqli->insert_id;
     
          $rows = [];
          if ($result !== false) 
               while ($row = $result->fetch_assoc())
                    $rows[] = $row;
     
          $stmt->close();
          self::$connections[] = $mysqli;
     
          return $rows;
     }

}

?>