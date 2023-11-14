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


namespace MVC\Models;

use MVC\Database as Database;

/**
 * Account Class
 *
 * The Account class represents a user account. 
 * It extends the Model class and provides methods 
 * for interacting with user account data in the database.
 */
class Account extends Model {

    /**
     * The name of the database table associated with user accounts.
     *
     * @var     string  $table
     */
    protected $table = "app_accounts";

    /**
     * The primary key column name in the database table.
     *
     * @var     string  $primaryKey
     */
    protected $primaryKey = "app_accounts.id";

    /**
     * Constant representing blocked user role.
     */
    public const BLOCKED = 1;

    /**
     * Constant representing deactivated user role.
     */
    public const DEACTIVATED = 2;

    /**
     * Constant representing the guest user role.
     */
    public const GUEST = 3;

    /**
     * Constant representing the default user role.
     */
    public const USER = 4;

    /**
     * Constant representing the verified user role.
     */
    public const VERIFIED = 5;

    /**
     * Constant representing the moderator role.
     */
    public const MODERATOR = 6;

    /**
     * Constant representing the administrator role.
     */
    public const ADMINISTRATOR = 7;

    /**
     * Constructor method for the Model class extended by retrieving metadata associated with the user account.
     *
     * @param   mixed   $value      The object ID or primary key value for the model.
     * @throws                      Exception If the account cannot be found in the database.
     */
    public function __construct($value) {
        parent::__construct($value);
        foreach(Database::query("SELECT * FROM app_accounts_meta WHERE id = ?", [$this->get("id")]) as $meta)
            $this->data[0][$meta["name"]] = $meta["value"];
    }

    /**
     * Set a value in the model's data and update the corresponding database record. 
     * If the column key does not exist, it sets or updates the metadata associated with the user account 
     * based on the given name and value. If the metadata with the provided name already exists, its value 
     * is updated. If the provided value is null, the metadata is deleted. If the metadata does not exist, 
     * a new entry is created.
     *
     * @param   string  $key    The key to set the value for.
     * @param   mixed   $value  The value to set.
     */
    public function set($key, $value) {
        if (in_array($key, ["id", "username", "email", "password", "token", "role", "registered", "lastaction"]))
            Database::query("UPDATE ".$this->table." SET ".$key." = ? WHERE id = ?", [$value, $this->objectID]);
        else 
            if (!empty(Database::query("SELECT * FROM app_accounts_meta WHERE id = ? AND name LIKE ?", [$this->get("id"), $key])))
                if ($value === null || $value == "")
                    Database::query("DELETE FROM app_accounts_meta WHERE id = ? AND name = ?", [$this->get("id"), $key]);
                else
                    Database::query("UPDATE app_accounts_meta SET value = ? WHERE id = ? AND name = ?", [$value, $this->get("id"), $key]);
            else
                if ($value !== null && $value != "")
                    Database::query("INSERT INTO app_accounts_meta (id, name, value) VALUES (?, ?, ?)", [$this->get("id"), $key, $value]);

        $this->data[0][$key] = $value;
    }

    /**
     * Set a user account as suspicious in the watchlist.
     *
     * @param   string  $request    The suspicious request or activity to record.
     */
    public function set_suspicious(string $request) {
        Database::query("INSERT INTO app_accounts_watchlist (id, request) VALUES (?, ?)", [$this->get("id"), $request]);
    }

    /**
     * Get the count of suspicions for a user account within a specified time span.
     *
     * @param   string  $request    (optional) The specific suspicious request to count.
     * @param   float   $timespan   (optional) The time span in minutes to consider for counting suspicions.
     * @return  int                 The number of suspicions for the user account.
     */
    public function get_suspicions(string $request = "", float $timespan = 1440) {
        return count(Database::query("SELECT * FROM app_accounts_watchlist WHERE id = ? AND request = ? AND detected BETWEEN (DATE_SUB(NOW(),INTERVAL ? MINUTE)) AND NOW()", [$this->get("id"), $request, $timespan]));
    }

}

?>