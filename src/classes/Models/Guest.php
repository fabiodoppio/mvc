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

use MVC\App     as App;
use MVC\Models  as Model;

/**
 * 
 *  Guest Class
 *
 *  The Guest class represents a guest user account with limited functionality.
 *  It extends the Account class and provides methods for accessing and managing
 *  guest account data stored in the session.
 * 
 */
class Guest extends Model\Account {

    /**
     *
     *  Constructor method for the Guest class.
     *
     *  If the session does not contain guest account data, it initializes
     *  the session with default values for a guest account, including a unique
     *  token generated by the Auth class.
     * 
     *  @since 2.0
     * 
     */
    public function __construct() {
        if (empty($_SESSION["account"] ?? []))
            $_SESSION["account"] = [
                "data" => [
                    "token" => App::get_instance_token(),
                    "role" => self::GUEST,
                    "lastaction" => date('Y-m-d H:i:s', time()),
                    "registered" => date('Y-m-d H:i:s', time()),
                    "meta" => [
                        "language" => $_COOKIE["locale"] ?? App::get("APP_LANGUAGE")
                    ]
                ]
            ];
    }

    /**
     * 
     *  Get a specific attribute of the guest account.
     *
     *  @since  2.0
     *  @param  string      $name   The name of the attribute
     *  @return mixed|null          The value of the attribute, or null if not found
     * 
     */
    public function get(string $name) {
        return $_SESSION["account"]["data"][$name] ?? $_SESSION["account"]["data"]["meta"][$name] ?? null;
    }

    /**
     * 
     *  Set a specific attribute of the guest account.
     *
     *  @since  2.0
     *  @param  string  $name   The name of the attribute
     *  @param  mixed   $value  The new value of the attribute
     * 
     */
    public function set(string $name, mixed $value) {
        if (isset($_SESSION["account"]["data"][$name]))
            $_SESSION["account"]["data"][$name] = $value;
        else
            $_SESSION["account"]["data"]["meta"][$name] = $value;
    }

    /**
     * 
     *  Get all data associated with the guest account.
     *
     *  @since  2.0
     *  @return array   An array containing all the data
     * 
     */
    public function get_data() {
        return $_SESSION["account"]["data"];
    }

    /**
     * 
     *  Delete model.
     *
     *  @since  2.0
     * 
     */
    public function delete() {
        unset($_SESSION["account"]);
    }

}

?>