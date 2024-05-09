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

use MVC\App         as App;
use MVC\Database    as Database;
use MVC\Exception   as Exception;
use MVC\Models      as Model;

/**
 * 
 *  Account Class
 *
 *  The Account class represents a user account. 
 *  It extends the Model class and provides methods 
 *  for interacting with user account data in the database.
 * 
 */
class Account extends Model\Model {

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
     *  @var    int     SUPPORTER       Constant representing the role for supporters
     *  @var    int     MODERATOR       Constant representing the role for moderators
     *  @var    int     ADMINISTRATOR   Constant representing the role for administrators
     */
    public const        BLOCKED         = 1;
    public const        DEACTIVATED     = 2;
    public const        GUEST           = 3;
    public const        USER            = 4;
    public const        VERIFIED        = 5;
    public const        SUPPORTER       = 6;
    public const        MODERATOR       = 7;
    public const        ADMINISTRATOR   = 8;


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

        foreach(Database::query("SELECT * FROM ".$this->table."_meta WHERE id = ?", [$this->get("id")]) as $meta) 
            $this->data[0]["meta"][$meta["name"]] = $meta["value"];
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
        return $this->data[0][$name] ?? $this->data[0]["meta"][$name] ?? null;
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
        if (isset($this->data[0][$name])) {
            if ($name == "username")
                if (!empty(Database::query("SELECT * FROM ".$this->table." WHERE username LIKE ?",[$value])[0]))
                    throw new Exception(_("This username is already taken."), 1073);

            if ($name == "email")
                if (!empty(Database::query("SELECT * FROM ".$this->table." WHERE email LIKE ?", [$value])[0]))
                    throw new Exception(_("This email address is already taken."), 1074);

            parent::set($name, $value);
        }
        else {
            if ($name == "avatar" && $this->get("avatar"))
                if (file_exists($file = App::get("DIR_ROOT").App::get("DIR_MEDIA")."/avatars/".$this->get("avatar")))
                    unlink($file);

            if ($name == "displayname")
                if (!in_array($value, 
                        [
                            $this->get("username"), 
                            $this->get("company"), 
                            $this->get("firstname"), 
                            $this->get("lastname"), 
                            $this->get("firstname")." ".$this->get("lastname"), 
                            $this->get("lastname")." ".$this->get("firstname")
                        ]
                    ))
                    $value = null;
                    

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
     *  Add a request to the watchlist for the account.
     *
     *  @since  2.0
     *  @param  string  $request    The request to be added to the watchlist
     * 
     */
    public function add_to_watchlist(string $request) {
        Database::query("INSERT INTO ".$this->table."_watchlist (id, request) VALUES (?, ?)", [$this->get("id"), $request]);
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
    public function count_in_watchlist(string $request = "", float $timespan = 43200) {
        return count(Database::query("SELECT * FROM ".$this->table."_watchlist WHERE id = ? AND request = ? AND detected BETWEEN (NOW() - INTERVAL ? MINUTE) AND NOW()", [$this->get("id"), $request, $timespan]));
    }

    /**
     * 
     *  Create a new model.
     *
     *  @since  2.0
     *  @param  string  $username   Username for the new account
     *  @param  string  $email      Email address for the new account
     *  @param  string  $password   Password for the new account
     *  @param  int     $role       Role for the new accoutn
     *  @return int                 New created user id
     * 
     */
    public static function create(string $username, string $email, string $password, int $role = self::USER) {

        if (!empty(Database::query("SELECT * FROM app_accounts WHERE username LIKE ?",[$username])[0]))
            throw new Exception(_("This username is already taken."), 1075);
        
        if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [$email])[0]))
            throw new Exception(_("This email address is already taken."), 1076);
        
        Database::query("INSERT INTO app_accounts (email, username, password, token, role) VALUES (?, ?, ?, ?, ?)", [strtolower($email), $username, password_hash($password, PASSWORD_DEFAULT), App::get_instance_token(), $role]);

        self::set_auth_cookie(Database::$insert_id, App::get_instance_token());
    }

    /**
     * 
     *  Delete model.
     *
     *  @since  2.0
     * 
     */
   public function delete() {
        $this->set("avatar", null);
        parent::delete();
    }

    /**
     * 
     *  Set the current user account based on provided credentials and password.
     *
     *  @since  2.0
     *  @param  string  $credential     The email or username of the user.
     *  @param  string  $password       The user's password.
     *  @param  bool    $stay           (optional) Whether to set a long-lasting cookie.
     * 
     */
    public static function login(string $credential, string $password, bool $stay = false) {
        if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential])))
            throw new Exception(_("There is no account with this username or email address."), 1003);

        $account = new Model\Account($account[0]["id"]);

        if ($account->count_in_watchlist("set_current_account", 10) > 3)
            throw new Exception(_("You have entered the password incorrectly too many times. Please wait 10 minutes and try again."), 1004);

        if (!password_verify($password, $account->get("password"))) {
            $account->add_to_watchlist("set_current_account");
            throw new Exception(_("You have entered an incorrect password."), 1005);
        }

        if ($account->get("role") < Model\Account::GUEST)
            throw new Exception(_("Your account is suspended or deactivated."), 1006);

        self::set_auth_cookie($account->get("id"), $account->get("token"), ($stay)?time()+(60*60*24*90):0);
        self::set_locale_cookie($account->get("language")??App::get("APP_LANGUAGE"), time()+(60*60*24*180));
    }

    /**
     * 
     *  Set a user account's authentication cookie.
     *
     *  @since  2.0
     *  @param  int         $id         The user account's ID.
     *  @param  string      $token      The user account's authentication token.
     *  @param  string|null $expiry     (optional) The expiration time for the cookie.
     * 
     */
    public static function set_auth_cookie(int $id, string $token, ?string $expiry = "0") {
        $hash = hash_hmac('sha256', $id.$token, hash_hmac('md5', $id.$token, App::get("SALT_COOKIE")));
        setcookie("account", $hash."$".$id, $expiry, "/", $_SERVER['SERVER_NAME'], 1);
        $_COOKIE["account"] =  $hash."$".$id;
    }

    /**
     *  Unset the user's authentication cookie.
     * 
     *  @since 2.0
     */
    public static function unset_auth_cookie() {
        setcookie("account", "", -1, "/", $_SERVER['SERVER_NAME'], 1);
        unset($_COOKIE["account"]);
    }

    /**
     * 
     *  Set a user's locale cookie.
     *
     *  @since  2.0
     *  @param  string      $lang       The user's preferred language.
     *  @param  string|null $expiry     (optional) The expiration time for the cookie.
     * 
     */
    public static function set_locale_cookie(string $lang, ?string $expiry = "0") {
        setcookie("locale", $lang, $expiry, "/", $_SERVER['SERVER_NAME'], 1);
        $_COOKIE["locale"] = $lang;
    }

    /**
     * 
     *  Get a confirmation code for a user account based on their credentials.
     *
     *  @since  2.0
     *  @param  string  $credential     The email or username of the user.
     *  @return string                  The generated confirmation code.
     * 
     */
    public static function get_confirmcode(string $credential) {
        if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential])))
            throw new Exception(_("There is no account with this username or email address."), 1007);

        $account = new Model\Account($account[0]["id"]);

        if ($account->count_in_watchlist("get_confirmcode", 60) > 3)
            throw new Exception(_("You have tried to request a confirmation code too many times. Please wait 60 minutes and try again."), 1008);

        $account->add_to_watchlist("get_confirmcode");
        $hash = hash_hmac('sha256', date("dmYH").$account->get("id").$account->get("token"), hash_hmac('md5', date("dmYH").$account->get("id").$account->get("token"), App::get("SALT_TOKEN")));
        $code = strtoupper(substr($hash,0,3)."-".substr($hash,29,3)."-".substr($hash, -3));
        
        return $code;
    } 

    /**
     * 
     *  Verify a confirmation code for a user account.
     *
     *  @since  2.08092
     *  @param  string  $credential     The email or username of the user.
     *  @param  string  $confirmcode    The confirmation code to verify.
     * 
     */
    public static function verify_confirmcode(string $credential, string $confirmcode) {
        if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential]))) 
            throw new Exception(_("There is no account with this username or email address."), 1009);

        $account = new Model\Account($account[0]["id"]);
        
        if ($account->count_in_watchlist("verify_confirmcode", 60) > 3)
            throw new Exception(_("You have tried to verify a confirmation code too many times. Please wait 60 minutes and try again."), 1010);

        $account->add_to_watchlist("verify_confirmcode");
        $hash = hash_hmac('sha256', date("dmYH").$account->get("id").$account->get("token"), hash_hmac('md5', date("dmYH").$account->get("id").$account->get("token"), App::get("SALT_TOKEN")));
        $code = strtoupper(substr($hash,0,3)."-".substr($hash,29,3)."-".substr($hash, -3));

        if (!hash_equals($confirmcode, $code))
            throw new Exception(_("This confirmation code is invalid."), 1011);
    }

}

?>