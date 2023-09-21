<?php

namespace Classes;

use \Classes\Models as Model;


class Auth {

    private static $token;
    private static $instance;

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

    public static function get_current_account() {
        if (!isset($_COOKIE["account"]))
            return new Model\Guest();

        if (empty($cookie = explode('$', $_COOKIE["account"])))
            throw new Exception("Unerlaubter Zugriff", 403);

        if (empty($account = Database::select("app_accounts", "id = '".$cookie[1]."'")))
            throw new Exception("Unerlaubter Zugriff", 403);

        $account = new Model\Account($account[0]["id"]);
        $hash = hash_hmac('sha256', $account->get("id").$account->get("token"), hash_hmac('md5', $account->get("id").$account->get("token"), App::get("SALT_COOKIE")));

        if (!hash_equals($cookie[0], $hash))
            throw new Exception("Unerlaubter Zugriff", 403);

        return $account;
    }

    public static function set_current_account(string $credential, string $password, bool $stay = false) {
        if (empty($account = Database::select("app_accounts", "email LIKE '".$credential."' OR username = '".$credential."'")))
            throw new Exception("Es gibt keinen Account mit diesem Benutzernamen oder dieser E-Mail Adresse.");

        $account = new Model\Account($account[0]["id"]);

        if ($account->get_suspicions("set_current_account", 10) > 3)
            throw new Exception("Du hast das Passwort zu oft falsch eingegeben. Bitte warte 10 Minuten und versuche es dann erneut.");

        if (!password_verify($password, $account->get("password"))) {
            $account->set_suspicious("set_current_account");
            throw new Exception("Du hast ein falsches Passwort eingegeben.");
        }

        if ($account->get("role") < Model\Role::GUEST)
            throw new Exception("Dein Account wurde gesperrt oder deaktiviert.");

        self::set_cookie($account->get("id"), $account->get("token"), ($stay)?time()+(60*60*24*30):0);
    }

    public static function set_new_account(string $username, string $email, string $password) {
        if (!empty(Database::select("app_accounts", "username LIKE '".$username."'")[0]))
            throw new Exception("Benutzername schon vergeben.");

        if (!empty(Database::select("app_accounts", "email LIKE '".$email."'")[0]))
            throw new Exception("E-Mail Adresse schon vergeben.");

        Database::insert("app_accounts", "email, username, password, token, role", "'".strtolower($email)."', '".$username."', '".password_hash($password, PASSWORD_DEFAULT)."', '".self::get_instance_token()."', '".Model\Role::USER."'");

        self::set_cookie(Database::$insert_id, self::get_instance_token());
    }

    public static function set_cookie(int $id, string $token, ?string $expiry = "0") {
        $hash = hash_hmac('sha256', $id.$token, hash_hmac('md5', $id.$token, App::get("SALT_COOKIE")));
        setcookie("account", $hash."$".$id, $expiry, "/", $_SERVER['SERVER_NAME'], 1);
        $_COOKIE["account"] =  $hash."$".$id;
    }

    public static function unset_cookie() {
        setcookie("account", "", -1, "/", $_SERVER['SERVER_NAME'], 1);
    }

    public static function get_confirmcode(string $credential) {
        if (empty($account = Database::select("app_accounts", "email LIKE '".$credential."' OR username = '".$credential."'")))
            throw new Exception("Es gibt keinen Account mit diesem Benutzernamen oder dieser E-Mail Adresse.");

        $account = new Model\Account($account[0]["id"]);

        if ($account->get_suspicions("get_confirmcode", 60) > 3)
            throw new Exception("Du hast zu oft versucht einen Bestätigungscode anzufordern. Bitte warte 60 Minuten und versuche es dann erneut.");

        $account->set_suspicious("get_confirmcode");
        $hash = hash_hmac('sha256', date("dmYH").$account->get("id").$account->get("token"), hash_hmac('md5', date("dmYH").$account->get("id").$account->get("token"), App::get("SALT_TOKEN")));
        $code = strtoupper(substr($hash,0,3)."-".substr($hash,29,3)."-".substr($hash, -3));
        
        return $code;
    } 

    public static function verify_confirmcode(string $credential, string $confirmcode) {
        if (empty($account = Database::select("app_accounts", "email LIKE '".$credential."' OR username = '".$credential."'"))) 
            throw new Exception("Es gibt keinen Account mit diesem Benutzernamen oder dieser E-Mail Adresse.");

        $account = new Model\Account($account[0]["id"]);

        $hash = hash_hmac('sha256', date("dmYH").$account->get("id").$account->get("token"), hash_hmac('md5', date("dmYH").$account->get("id").$account->get("token"), App::get("SALT_TOKEN")));
        $code = strtoupper(substr($hash,0,3)."-".substr($hash,29,3)."-".substr($hash, -3));

        if (!hash_equals($confirmcode, $code))
            throw new Exception("Dein Bestätigungscode ist ungültig.");
    }

    public static function get_client_token() {
        $_SESSION["client"][self::get_instance_token()] = hash_hmac('sha256', self::get_instance_token(), hash_hmac('md5', self::get_instance_token(), App::get("SALT_TOKEN")));
        return self::get_instance_token();
    }

    public static function verify_client_token(string $token) {
        if (!hash_equals($_SESSION["client"][$token], hash_hmac('sha256', $token, hash_hmac('md5', $token, App::get("SALT_TOKEN")))))
            throw new Exception("Illegale Aktivität festgestellt.");
    }

}

?>