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
     * Constructor method for the Model class extended by retrieving metadata associated with the user account.
     *
     * @param   mixed   $value      The object ID or primary key value for the model.
     * @throws                      Exception If the account cannot be found in the database.
     */
    public function __construct($value) {
        parent::__construct($value);
        foreach(Database::select("app_accounts_meta", "id = '".$this->get("id")."'") as $meta)
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
        if (Database::operate("SHOW COLUMNS FROM ".$this->table." LIKE '".$key."'")['num_rows'] > 0)
            Database::update($this->table, $key." = '".$value."'", $this->primaryKey." = '".$this->objectID."'");
        else 
            if ($this->get($key))
                if ($value == null)
                    Database::delete("app_accounts_meta", "id = '".$this->get("id")."' AND name = '".$key."'");
                else
                    Database::update("app_accounts_meta", "value = '".$value."'", "id = '".$this->get("id")."' AND name = '".$key."'");
            else
                Database::insert("app_accounts_meta", "id, name, value", "'".$this->get("id")."', '".$key."', '".$value."'");

        $this->data[0][$key] = $value;
    }

    /**
     * Set a user account as suspicious in the watchlist.
     *
     * @param   string  $request    The suspicious request or activity to record.
     */
    public function set_suspicious(string $request) {
        Database::insert("app_accounts_watchlist", "id, request", "'".$this->get("id")."', '".$request."'");
    }

    /**
     * Get the count of suspicions for a user account within a specified time span.
     *
     * @param   string  $request    (optional) The specific suspicious request to count.
     * @param   float   $timespan   (optional) The time span in minutes to consider for counting suspicions.
     * @return  int                 The number of suspicions for the user account.
     */
    public function get_suspicions(string $request = "", float $timespan = 1440) {
        return count(Database::select("app_accounts_watchlist", "id = '".$this->get("id")."' AND request = '".$request."' AND detected BETWEEN (DATE_SUB(NOW(),INTERVAL ".$timespan." MINUTE)) AND NOW()"));
    }

}

?>