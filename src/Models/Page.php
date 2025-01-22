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

use MVC\Database    as Database;
use MVC\Models      as Model;

/**
 *
 *  Page Class
 *
 *  The Page class represents a custom page.
 *
 */
class Page extends Model\Model {

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
     *  Constructor method for the parent class extended by retrieving metadata associated with the custom page.
     *
     *  @since  3.0
     *  @param  mixed   $value      The object ID or primary key value for the custom page
     *
     */
    public function __construct($value) {
        parent::__construct($value);

        foreach(Database::query("SELECT * FROM ".$this->table."_meta WHERE id = ?", [$this->get("id")]) as $meta)
            $this->data[0]["meta"][$meta["name"]] = $meta["value"];
    }

    /**
     *
     *  Get a specific attribute of the custom page.
     *
     *  @since  3.0
     *  @param  string      $name   The name of the attribute
     *  @return mixed|null          The value of the attribute, or null if not found
     *
     */
    public function get(string $name) {
        return $this->data[0][$name] ?? $this->data[0]["meta"][$name] ?? null;
    }

    /**
     *
     *  Set a specific attribute of the custom page.
     *
     *  @since  3.0
     *  @param  string  $name   The name of the attribute
     *  @param  mixed   $value  The new value of the attribute
     *
     */
    public function set(string $name, mixed $value) {
        if (isset($this->data[0][$name]))
            parent::set($name, $value);
        else {
            if (isset($this->data[0]["meta"][$name]))
                if ($value !== "" && $value !== null)
                    Database::query("UPDATE ".$this->table."_meta SET value = ? WHERE id = ? AND name = ?", [$value, $this->get("id"), $name]);
                else
                    Database::query("DELETE FROM ".$this->table."_meta WHERE id = ? AND name = ?", [$this->get("id"), $name]);
            else
                if ($value !== "" && $value !== null)
                    Database::query("INSERT INTO ".$this->table."_meta (id, name, value) VALUES (?, ?, ?)", [$this->get("id"), $name, $value]);

            $this->data[0]["meta"][$name] = $value;
        }
    }

    /**
     *
     *  Get an array with protected table column and meta names.
     *
     *  @since  3.0
     *
     *  @return array   The Array with protected names.
     *
     */
    public static function get_protected_names() {
        return ["id", "slug", "template", "requirement", "maintenance", "active", "title", "description", "robots", "class"];
    }

}

?>