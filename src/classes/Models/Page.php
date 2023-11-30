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

use MVC\Database;

/**
 * 
 *  Page Class
 *
 *  The Page class represents a custom webpage. 
 *  It extends the Model class and provides methods 
 *  for interacting with the page data in the database.
 * 
 */
class Page extends Model {

    /**
     *  @var    array   $meta           The meta data associated with the custom page
     */
    protected $meta;

    /**
     *  @var    string  $table          The name of the database table associated with custom pages
     */
    protected $table = "app_pages";

    /**
     *  @var    string  $primaryKey     The primary key column name in the database table
     */
    protected $primaryKey = "app_pages.id";


    /**    
     * 
     *  Constructor method for the parent class extended by retrieving metadata associated with the custom page
     *
     *  @since  2.0
     *  @param  mixed   $value      The object ID or primary key value for the page
     * 
     */
    public function __construct($value) {
        parent::__construct($value);
        foreach(Database::query("SELECT * FROM app_pages_meta WHERE id = ?", [$this->get("id")]) as $meta) 
            $this->meta[$meta["name"]] = $meta["value"];
    }

    /**
     * 
     *  Get a specific attribute of the custom page
     *
     *  @since  2.0
     *  @param  string      $name   The name of the attribute
     *  @return mixed|null          The value of the attribute, or null if not found
     * 
     */
    public function get(string $name) {
        return $this->data[0][$name] ?? $this->meta[$name] ?? null;
    }

    /**
     * 
     *  Set a specific attribute of the custom page
     *
     *  @since  2.0
     *  @param  string  $name   The name of the attribute
     *  @param  mixed   $value  The new value of the attribute
     * 
     */
    public function set(string $name, mixed $value) {
        if (isset($this->data[0][$name]))
            parent::set($name, $value);
        else {
            if (isset($this->meta[$name]))
                if ($value !== "" && $value !== null)
                    Database::query("UPDATE app_pages_meta SET value = ? WHERE id = ? AND name = ?", [$value, $this->get("id"), $name]);
                else
                    Database::query("DELETE FROM app_pages_meta WHERE id = ? AND name = ?", [$this->get("id"), $name]);
            else
                if ($value !== "" && $value !== null)
                    Database::query("INSERT INTO app_pages_meta (id, name, value) VALUES (?, ?, ?)", [$this->get("id"), $name, $value]);
        
            $this->meta[$name] = $value;
        }
    }

    /**
     * 
     *  Get all meta data associated with the custom page.
     *
     *  @since  2.0
     *  @return array   An array containing all the meta data
     * 
     */
    public function get_meta() {
        return $this->meta ?? [];
    }
}

?>