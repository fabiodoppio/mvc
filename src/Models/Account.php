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