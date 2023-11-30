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


namespace MVC;

use MVC\Models as Model;

/**
 * 
 *  Auth Class
 *
 *  The Auth class provides authentication and authorization functionalities for the application.
 *  It handles user authentication, session management, and security features like generating tokens
 *  and hashing passwords. It also interacts with the database to validate user credentials and manage
 *  user accounts.
 * 
 */
class Auth {

    /**
     *  @var    string  $token      Generated token for current instance
     */
    private static $token;

    /**
     *  @var    Auth    $instance   Instance of current class
     */
    private static $instance;

    
    /**
     * 
     *  Generate a unique instance token for the application.
     *
     *  @since  2.0
     *  @return string  The generated instance token.
     * 
     */
    public static function get_instance_token() {
        if (!is_null(self::$instance))
            return self::$token;

        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);
    
        for ($i=0; $i < 32; $i++)
            $token .= $codeAlphabet[random_int(0, $max-1)];
    
        self::$token = $token;
        self::$instance = new self();
        
        return $token;
    }

    /**
     * 
     *  Get the current user account based on the session or cookie.
     *
     *  @since  2.0
     *  @return Model\Account|Model\Guest   The current user account or a guest account if not logged in.
     * 
     */
    public static function get_current_account() {
        if (!isset($_COOKIE["account"]))
            return new Model\Guest();

        if (empty($cookie = explode('$', $_COOKIE["account"])))
            throw new Exception(_("Unauthorized Access."), 403);

        if (empty($account = Database::query("SELECT * FROM app_accounts WHERE id = ?", [$cookie[1]])))
            throw new Exception(_("Unauthorized Access."), 403);

        $account = new Model\Account($account[0]["id"]);
        $hash = hash_hmac('sha256', $account->get("id").$account->get("token"), hash_hmac('md5', $account->get("id").$account->get("token"), App::get("SALT_COOKIE")));

        if (!hash_equals($cookie[0], $hash))
            throw new Exception(_("Unauthorized Access."), 403);

        return $account;
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
    public static function set_current_account(string $credential, string $password, bool $stay = false) {
        if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential])))
            throw new Exception(_("There is no account with this username or email address."), 1003);

        $account = new Model\Account($account[0]["id"]);

        if ($account->get_watch("set_current_account", 10) > 3)
            throw new Exception(_("You have entered the password incorrectly too many times. Please wait 10 minutes and try again."), 1004);

        if (!password_verify($password, $account->get("password"))) {
            $account->set_watch("set_current_account");
            throw new Exception(_("You have entered an incorrect password."), 1005);
        }

        if ($account->get("role") < Model\Account::GUEST)
            throw new Exception(_("Your account is suspended or deactivated."), 1006);

        self::set_auth_cookie($account->get("id"), $account->get("token"), ($stay)?time()+(60*60*24*90):0);
        self::set_locale_cookie($account->get("language")??App::get("APP_LANGUAGE"), time()+(60*60*24*180));
    }

    /**
     * 
     *  Set a new user account with the provided username, email, and password.
     *
     *  @since  2.0
     *  @param  string  $username   The username for the new account.
     *  @param  string  $email      The email address for the new account.
     *  @param  string  $password   The password for the new account.
     * 
     */
    public static function set_new_account(string $username, string $email, string $password) {
        if (!empty(Database::query("SELECT * FROM app_accounts WHERE username LIKE ?", [$username])[0]))
            throw new Exception(_("This username is already taken."), 1007);

        if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [$email])[0]))
            throw new Exception(_("This email address is already taken."), 1008);

        Database::query("INSERT INTO app_accounts (email, username, password, token, role) VALUES (?, ?, ?, ?, ?)", [strtolower($email), $username, password_hash($password, PASSWORD_DEFAULT), self::get_instance_token(), Model\Account::USER]);

        self::set_auth_cookie(Database::$insert_id, self::get_instance_token());
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
        unset($_COOKIE["account"]);
        setcookie("account", "", -1, "/", $_SERVER['SERVER_NAME'], 1);
    }

    /**
     * 
     *  Set a user account's locale cookie.
     *
     *  @since  2.0
     *  @param  string      $lang       The user account's preferred language.
     *  @param  string|null $expiry     (optional) The expiration time for the cookie.
     * 
     */
    public static function set_locale_cookie(string $lang, ?string $expiry = "0") {
        setcookie("locale", $lang, $expiry, "/", $_SERVER['SERVER_NAME'], 1);
        $_COOKIE["locale"] =  $lang;
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
            throw new Exception(_("There is no account with this username or email address."), 1009);

        $account = new Model\Account($account[0]["id"]);

        if ($account->get_watch("get_confirmcode", 60) > 3)
            throw new Exception(_("You have tried to request a confirmation code too many times. Please wait 60 minutes and try again."), 1010);

        $account->set_watch("get_confirmcode");
        $hash = hash_hmac('sha256', date("dmYH").$account->get("id").$account->get("token"), hash_hmac('md5', date("dmYH").$account->get("id").$account->get("token"), App::get("SALT_TOKEN")));
        $code = strtoupper(substr($hash,0,3)."-".substr($hash,29,3)."-".substr($hash, -3));
        
        return $code;
    } 

    /**
     * 
     *  Verify a confirmation code for a user account.
     *
     *  @since  2.0
     *  @param  string  $credential     The email or username of the user.
     *  @param  string  $confirmcode    The confirmation code to verify.
     * 
     */
    public static function verify_confirmcode(string $credential, string $confirmcode) {
        if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential]))) 
            throw new Exception(_("There is no account with this username or email address."), 1011);

        $account = new Model\Account($account[0]["id"]);

        $hash = hash_hmac('sha256', date("dmYH").$account->get("id").$account->get("token"), hash_hmac('md5', date("dmYH").$account->get("id").$account->get("token"), App::get("SALT_TOKEN")));
        $code = strtoupper(substr($hash,0,3)."-".substr($hash,29,3)."-".substr($hash, -3));

        if (!hash_equals($confirmcode, $code))
            throw new Exception(_("This confirmation code is invalid."), 1012);
    }

    /**
     * 
     *  Generate a client token for the current session.
     *
     *  @since  2.0
     *  @return string  The generated client token.
     * 
     */
    public static function get_client_token() {
        $_SESSION["client"][self::get_instance_token()] = hash_hmac('sha256', self::get_instance_token(), hash_hmac('md5', self::get_instance_token(), App::get("SALT_TOKEN")));
        return self::get_instance_token();
    }

    /**
     * 
     *  Verify a client token for the current session.
     *
     *  @since  2.0
     *  @param  string  $token  The client token to verify.
     * 
     */
    public static function verify_client_token(string $token) {
        if (!hash_equals($_SESSION["client"][$token], hash_hmac('sha256', $token, hash_hmac('md5', $token, App::get("SALT_TOKEN")))))
            throw new Exception(_("Illegal activity detected."), 1013);
    }

}

?>