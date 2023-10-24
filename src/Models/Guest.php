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

use MVC\Auth as Auth;

/**
 * Guest Class
 *
 * The Guest class represents a guest user account with limited functionality.
 * It extends the Account class and provides methods for accessing and managing
 * guest account data stored in the session.
 */
class Guest extends Account {

    /**
     * Constructor method for the Guest class.
     *
     * If the session does not contain guest account data, it initializes
     * the session with default values for a guest account, including a unique
     * token generated by the Auth class.
     */
    public function __construct() {
        if (empty($this->data = $_SESSION["account"]??[])) {
            $_SESSION["account"] = [
                "id" => null,
                "email" => null,
                "username" => null,
                "password" => null,
                "token" => Auth::get_instance_token(),
                "role" => Account::GUEST,
                "lastaction" => date('Y-m-d H:i:s', time()),
                "registered" => date('Y-m-d H:i:s', time())
            ];
            $this->data = $_SESSION["account"];
        }
    }

    /**
     * Get a value from the guest account data by key.
     * 
     * @param   string      $key    The key to retrieve the value for.
     * @param   int         $row    (optional) If the key represents an array, specify the row.
     * @return  mixed|null          The value associated with the key, or null if not found.
     */
    public function get($key, $row = 0) {
        return $this->data[$key] ?? null;
    }
    
    /**
     * Set a value in the guest account data by key.
     *
     * @param   string      $key    The key to set the value for.
     * @param   mixed       $value  The value to set.
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
        $_SESSION["account"][$key] = $value;
    }

    /**
     * Get the number of elements in the guest account data.
     *
     * @return  int     The number of elements in the guest account data.
     */
    public function length() {
        return count($this->data);
    }

}

?>