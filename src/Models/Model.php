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


namespace MVC\Models;

use MVC\Database  as Database;
use MVC\Exception as Exception;

/**
 *
 *  Model Class (Abstract)
 *
 *  The Model class serves as an abstract base class for data models.
 *  It provides common methods for interacting with data
 *  stored in a database table.
 *
 */
abstract class Model {

    /**
     *  @var    array   $data           The data associated with the model
     */
    protected $data;

    /**
     *  @var    string  $table          The name of the database table associated with the model
     */
    protected $table;

    /**
     *  @var    mixed   $objectID       The object ID or primary key value for the model
     */
    protected $objectID;

    /**
     *  @var    string  $primaryKey     The primary key column name in the database table
     */
    protected $primaryKey;


    /**
     *
     *  Constructor method for the Model class.
     *
     *  @since  2.0
     *  @param  mixed   $value      The object ID or primary key value for the model
     *
     */
    public function __construct(mixed $value) {
        $this->objectID = $value;
        if (empty($this->data = Database::query("SELECT * FROM ".$this->table." WHERE ".$this->primaryKey." = ? LIMIT 1", [$this->objectID])))
            throw new Exception(sprintf(_("Model %1\$s:%2\$s not found."), get_class($this), $this->objectID), 2100);
    }

    /**
     *
     *  Get a value from the model's data.
     *
     *  @since  2.0
     *  @param  string      $name   The name to retrieve the value for.
     *  @return mixed|null          The value associated with the name, or null if not found.
     *
     */
    public function get(string $name) {
        return $this->data[0][$name] ?? null;
    }

    /**
     *
     *  Set a value in the model's data and update the corresponding database record.
     *
     *  @since  2.0
     *  @param  string  $name   The name to set the value for.
     *  @param  mixed   $value  The value to set.
     *
     */
    public function set(string $name, mixed $value) {
        Database::query("UPDATE ".$this->table." SET ".$name." = ? WHERE ".$this->primaryKey." = ?", [$value, $this->objectID]);
        $this->data[0][$name] = $value;
    }

    /**
     *
     *  Delete model.
     *
     *  @since  2.0
     *
     */
    public function delete() {
        Database::query("DELETE FROM ".$this->table." WHERE ".$this->primaryKey." = ?", [$this->objectID]);
    }

    /**
     *
     *  Get all data associated with the model.
     *
     *  @since  2.0
     *  @return array   An array containing all the data
     *
     */
    public function get_data() {
        return $this->data[0] ?? [];
    }

}

?>