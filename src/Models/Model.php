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


namespace Classes\Models;

use \Classes\Database  as Database;
use \Classes\Exception as Exception;

/**
 * Model Class (Abstract)
 *
 * The Model class serves as an abstract base class for data models. 
 * It provides common methods for interacting with data
 * stored in a database table.
 */
abstract class Model {

    /**
     * The data associated with the model.
     *
     * @var     array   $data
     */
    protected $data;

    /**
     * The name of the database table associated with the model.
     *
     * @var     string  $table
     */
    protected $table;

    /**
     * The object ID or primary key value for the model.
     *
     * @var     mixed   $objectID
     */
    protected $objectID;

    /**
     * The primary key column name in the database table.
     *
     * @var     string  $primaryKey
     */
    protected $primaryKey;

    /**
     * Constructor method for the Model class.
     *
     * @param   mixed   $value      The object ID or primary key value for the model.
     * @throws                      Exception If the model data cannot be found in the database.
     */
    public function __construct($value) {
        $this->objectID = $value;
        if (empty($this->data = Database::select($this->table, $this->primaryKey." = '".$this->objectID."'")))
            throw new Exception("Model '".get_class($this).":".$this->objectID."' not found.");
    }
    
    /**
     * Get a value from the model's data.
     *
     * @param   string      $key    The key to retrieve the value for.
     * @param   int         $row    (optional) If the key represents an array, specify the row.
     * @return  mixed|null          The value associated with the key, or null if not found.
     */
    public function get($key, $row = 0) {
        return $this->data[$row][$key] ?? null;
    }
    
    /**
     * Set a value in the model's data and update the corresponding database record.
     *
     * @param   string  $key    The key to set the value for.
     * @param   mixed   $value  The value to set.
     */
    public function set($key, $value) {
        $this->data[0][$key] = $value;
        Database::update($this->table, $key." = '".$value."'", $this->primaryKey." = '".$this->objectID."'");
    }
    
    /**
     * Get the number of rows in the model's data.
     *
     * @return  int     The number of rows in the model's data.
     */
    public function length() {
        return count($this->data);
    }

}

?>