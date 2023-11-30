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

use MVC\Database as Database;

/**
 * 
 *  Account Class
 *
 *  The Account class represents a user account. 
 *  It extends the Model class and provides methods 
 *  for interacting with user account data in the database.
 * 
 */
class Account extends Model {

    /**
     *  @var    array   $meta           The meta data associated with the account
     */
    protected $meta;

    /**
     *  @var    string  $table          The name of the database table associated with user accounts
     */
    protected $table = "app_accounts";

    /**
     *  @var    string  $primaryKey     The primary key column name in the database table
     */
    protected $primaryKey = "app_accounts.id";

    /**
     *  @var    int     BLOCKED         Constant representing the role for blocked users
     *  @var    int     DEACTIVATED     Constant representing the role for deactivated users
     *  @var    int     GUEST           Constant representing the role for guests
     *  @var    int     USER            Constant representing the role for common users
     *  @var    int     VERIFIED        Constant representing the role for verified users
     *  @var    int     MODERATOR       Constant representing the role for moderators
     *  @var    int     ADMINISTRATOR   Constant representing the role for administrators
     */
    public const        BLOCKED         = 1;
    public const        DEACTIVATED     = 2;
    public const        GUEST           = 3;
    public const        USER            = 4;
    public const        VERIFIED        = 5;
    public const        MODERATOR       = 6;
    public const        ADMINISTRATOR   = 7;


    /**    
     * 
     *  Constructor method for the parent class extended by retrieving metadata associated with the user account.
     *
     *  @since  2.0
     *  @param  mixed   $value      The object ID or primary key value for the account
     * 
     */
    public function __construct($value) {
        parent::__construct($value);
        foreach(Database::query("SELECT * FROM app_accounts_meta WHERE id = ?", [$this->get("id")]) as $meta) 
            $this->meta[$meta["name"]] = $meta["value"];
    }

    /**
     * 
     *  Get a specific attribute of the account.
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
     *  Set a specific attribute of the account.
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
                    Database::query("UPDATE app_accounts_meta SET value = ? WHERE id = ? AND name = ?", [$value, $this->get("id"), $name]);
                else
                    Database::query("DELETE FROM app_accounts_meta WHERE id = ? AND name = ?", [$this->get("id"), $name]);
            else
                if ($value !== "" && $value !== null)
                    Database::query("INSERT INTO app_accounts_meta (id, name, value) VALUES (?, ?, ?)", [$this->get("id"), $name, $value]);
        
            $this->meta[$name] = $value;
        }
    }

    /**
     * 
     *  Get all meta data associated with the account.
     *
     *  @since  2.0
     *  @return array   An array containing all the meta data
     * 
     */
    public function get_meta() {
        return $this->meta ?? [];
    }

    /**
     * 
     *  Add a request to the watchlist for the account.
     *
     *  @since  2.0
     *  @param  string  $request    The request to be added to the watchlist
     * 
     */
    public function set_watch(string $request) {
        Database::query("INSERT INTO app_accounts_watchlist (id, request) VALUES (?, ?)", [$this->get("id"), $request]);
    }

    /**
     * 
     *  Get the count of requests in the watchlist for the account within a specified timespan.
     *
     *  @since  2.0
     *  @param  string  $request    The request to be checked in the watchlist
     *  @param  float   $timespan   The timespan in minutes within which to check for requests
     *  @return int                 The count of requests in the watchlist
     * 
     */
    public function get_watch(string $request = "", float $timespan = 1440) {
        return count(Database::query("SELECT * FROM app_accounts_watchlist WHERE id = ? AND request = ? AND detected BETWEEN (NOW() - INTERVAL ? MINUTE) AND NOW()", [$this->get("id"), $request, $timespan]));
    }

}

?>