<?php

/**
 *
 *  MVC
 *  Model View Controller (MVC) design pattern for simple web applications.
 *
 *  @see     https://github.com/fabiodoppio/mvc
 *
 *  @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 *  @license https://opensource.org/license/mit/ MIT License
 *
 */


namespace MVC;

/**
 *
 *  Database Class
 *
 *  The Database class provides essential database operations for interacting with a MySQL database.
 *  It handles database connections, executing SQL queries, and managing query results.
 *
 */
class Database {

     /**
      *  @var  int       $insert_id     Property to store the last inserted ID after an INSERT operation.
      */
     public static $insert_id = 0;

     /**
      *  @var  \mysqli   $connection    Property to manage reusable database connections.
      */
     private static $connection = null;


     /**
      *
      *  Establish a connection to the MySQL database using the application's configuration.
      *
      *  @since     3.04                Changed way to connect to database for performance reasons.
      *  @since     2.0
      *  @return    \mysqli|false       A MySQLi object representing the database connection or false if the connection fails.
      *
      */
	private static function connect() {
          if (self::$connection instanceof \mysqli)
               return self::$connection;

          $mysqli = new \mysqli(
               App::get("DB_HOST"),
               App::get("DB_USERNAME"),
               App::get("DB_PASSWORD"),
               App::get("DB_DATABASE")
          );

          $mysqli->set_charset("utf8mb4");
          self::$connection = $mysqli;

		return $mysqli;
	}

	/**
      *
      *  Execute an SQL query on the database.
      *
      *  @since     3.04           Added different types for bind_params, performance tweaks.
      *  @since     2.2            Added specific error message on mysqli_sql_exception.
      *  @since     2.0
      *  @param     string         $sql      The SQL query to execute.
      *  @param     array          $params   Parameters to bind the prepared statement.
      *  @return    array                    An array of query results.
      *
      */
     public static function query(string $sql, array $params = []) {
          try {
               $mysqli = self::connect();
               $stmt = $mysqli->prepare($sql);

               if (!empty($params)) {
                    $types = '';
                    foreach($params as $p)
                         $types .= match(gettype($p)) {
                              'integer' => 'i',
                              'double'  => 'd',
                              'string'  => 's',
                              'NULL'    => 's',
                              default   => 'b',
                         };
                    $stmt->bind_param($types, ...$params);
               }

               $stmt->execute();
               $result = $stmt->get_result();
               self::$insert_id = $mysqli->insert_id;

               $rows = [];
               if ($result !== false) {
                    while ($row = $result->fetch_assoc())
                         $rows[] = $row;
                    $result->free();
               }

               $stmt->close();
               return $rows;
          }
          catch(\mysqli_sql_exception $e) {
               throw new Exception(_("Connection to MySQL database failed: ".$e->getMessage()), 1100);
          }
     }

}

?>