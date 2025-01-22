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
use MVC\Mailer      as Mailer;
use MVC\Models      as Model;
use MVC\Template    as Template;

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
                if (!empty(Database::query("SELECT id FROM ".$this->table." WHERE username LIKE ?",[$value])[0]))
                    throw new Exception(_("This username is already taken."), 1900);

            if ($name == "email")
                if (!empty(Database::query("SELECT id FROM ".$this->table." WHERE email LIKE ?", [$value])[0]))
                    throw new Exception(_("This email address is already taken."), 1901);

            parent::set($name, $value);
        }
        else {
            if ($name == "avatar")
                if ($this->get("avatar"))
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
     *  Add an event to the accounts log.
     *
     *  @since  2.0
     *  @param  string      $event      The event to be added to the log
     *
     */
    public function log(string $event) {
        Database::query("INSERT INTO ".$this->table."_log (id, event) VALUES (?, ?)", [$this->get("id"), $event]);
    }

    /**
     *
     *  Attempt to log in with a password.
     *
     *  @since  2.0
     *  @param  string  $password   The password to authenticate with
     *
     */
    public function attempt_login(string $password) {
        if (count(Database::query("SELECT id FROM ".$this->table."_log WHERE id = ? AND event = ? AND timestamp BETWEEN (NOW() - INTERVAL ? MINUTE) AND NOW()",
            [$this->get("id"), "failed_login_attempt", 20])) > 2)
                throw new Exception(_("You have entered the password incorrectly too many times. Please wait 20 minutes and try again."), 1902);

        if (!password_verify($password, $this->get("password"))) {
            $this->log("failed_login_attempt");
            throw new Exception(_("You have entered an incorrect password."), 1903);
        }

        if ($this->get("role") < self::GUEST)
            throw new Exception(_("Your account is suspended or deactivated."), 1904);
    }

    /**
     *
     *  Request a 2FA pin code for the account.
     *
     *  @since  2.0
     *
     */
    public function request_2fa_code() {
        $this->set("code", rand(100000, 999999));
        $this->set("timestamp", strtotime('now'));

        Mailer::send(sprintf(_("2-Factor Authentication | %s"), App::get("APP_NAME")), $this->get("email"), Template::get("/_emails/account2fa.tpl", [
            "var" => (object) [
                "code" => substr_replace($this->get("code"), " ", 3, 0)
            ],
            "app" => (object) [
                "url" => App::get("APP_URL"),
                "name" => App::get("APP_NAME"),
            ],
            "account" => (object) [
                "username" => $this->get("username"),
                "meta" => (object) [
                    "displayname" => $this->get("displayname")
                ]
            ]
        ]));
    }

    /**
     *
     *  Attempt to log in with a 2FA pin code.
     *
     *  @since  2.0
     *  @param  string  $code       The 2FA pin code to authenticate with
     *
     */
    public function attempt_2fa_login(string $code) {
        if (count(Database::query("SELECT id FROM ".$this->table."_log WHERE id = ? AND event = ? AND timestamp BETWEEN (NOW() - INTERVAL ? MINUTE) AND NOW()",
            [$this->get("id"), "failed_2fa_login_attempt", 20])) > 2)
                throw new Exception(_("You have entered an invalid PIN code too many times. Please wait 20 minutes and try again."), 1905);

        if ($this->get("code") != str_replace(' ', '', $code) || $this->get("timestamp") < strtotime('-15 minutes')) {
            $this->log("failed_2fa_login_attempt");
            throw new Exception(_("This PIN code is invalid."), 1906);
        }

        $this->set("code", null);
        $this->set("timestamp", null);
    }

    /**
     *
     *  Request a recovery code for the account.
     *
     *  @since  2.0
     *
     */
    public function request_recovery_code() {
        if (count(Database::query("SELECT id FROM ".$this->table."_log WHERE id = ? AND event = ? AND timestamp BETWEEN (NOW() - INTERVAL ? MINUTE) AND NOW()",
            [$this->get("id"), "recovery_code_requested", 60])) > 2)
                throw new Exception(_("You have tried to request a confirmation code too many times. Please wait 60 minutes and try again."), 1907);

        $this->set("code", rand(100000, 999999));
        $this->set("timestamp", strtotime('now'));

        Mailer::send(sprintf(_("Account Recovery | %s"), App::get("APP_NAME")), $this->get("email"), Template::get("/_emails/accountRecovery.tpl", [
            "var" => (object) [
                "code" => substr_replace($this->get("code"), " ", 3, 0)
            ],
            "app" => (object) [
                "url" => App::get("APP_URL"),
                "name" => App::get("APP_NAME"),
            ],
            "account" => (object) [
                "username" => $this->get("username"),
                "meta" => (object) [
                    "displayname" => $this->get("displayname")
                ]
            ]
        ]));
    }

    /**
     *
     *  Recover the account using a recovery code.
     *
     *  @since  2.0
     *  @param  string  $code       The recovery code
     *  @param  string  $password   The new password for the account
     *
     */
    public function recover(string $code, string $password) {
        if (count(Database::query("SELECT id FROM ".$this->table."_log WHERE id = ? AND event = ? AND timestamp BETWEEN (NOW() - INTERVAL ? MINUTE) AND NOW()",
            [$this->get("id"), "failed_recovery", 60])) > 2)
                throw new Exception(_("You have entered an invalid confirmation code too many times. Please wait 60 minutes and try again."), 1908);

        if ($this->get("code") != str_replace(' ', '', $code) || $this->get("timestamp") < strtotime('-15 minutes')) {
            $this->log("failed_recovery");
            throw new Exception(_("This confirmation code is invalid."), 1909);
        }

        if ($this->get("role") < self::DEACTIVATED)
            throw new Exception(_("Your account cannot be restored."), 1910);

        $this->set("password", password_hash($password, PASSWORD_DEFAULT));
        $this->set("role", ($this->get("role") == self::DEACTIVATED || $this->get("role") == self::USER) ? self::VERIFIED : $this->get("role"));
        $this->set("token", App::generate_token());
        $this->set("code", null);
        $this->set("timestamp", null);
    }

    /**
     *
     *  Request a verification code for the account.
     *
     *  @since  2.0
     *
     */
    public function request_verification_code() {
        $this->set("code", rand(100000, 999999));
        $this->set("timestamp", strtotime('now'));

        Mailer::send(sprintf(_("Email Address Verification | %s"), App::get("APP_NAME")), $this->get("email"), Template::get("/_emails/accountVerify.tpl", [
            "var" => (object) [
                "code" => substr_replace($this->get("code"), " ", 3, 0)
            ],
            "app" => (object) [
                "url" => App::get("APP_URL"),
                "name" => App::get("APP_NAME"),
            ],
            "account" => (object) [
                "username" => $this->get("username"),
                "meta" => (object) [
                    "displayname" => $this->get("displayname")
                ]
            ]
        ]));
    }

    /**
     *
     *  Verify the account using a verification code.
     *
     *  @since  2.0
     *  @param  string  $code   The verification code
     *
     */
    public function verify(string $code) {
        if (count(Database::query("SELECT id FROM ".$this->table."_log WHERE id = ? AND event = ? AND timestamp BETWEEN (NOW() - INTERVAL ? MINUTE) AND NOW()", 
            [$this->get("id"), "failed_verification", 60])) > 2)
                throw new Exception(_("You have entered an invalid confirmation code too many times. Please wait 60 minutes and try again."), 1911);

        if ($this->get("code") != str_replace(' ', '', $code) || $this->get("timestamp") < strtotime('-15 minutes')) {
            $this->log("failed_verification");
            throw new Exception(_("This confirmation code is invalid."), 1912);
        }

        $this->set("role", ($this->get("role") == self::USER) ? self::VERIFIED : $this->get("role"));
        $this->set("code", null);
        $this->set("timestamp", null);
    }

    /**
     *
     *  Deactivate the account.
     *
     *  @since  2.4   Added noitification mail for admins.
     *  @since  2.0
     *
     */
    public function deactivate() {
        $this->set("role", self::DEACTIVATED);

        App::set_locale_runtime($this->get("language") ?? App::get("APP_LANGUAGE"));
        Mailer::send(sprintf(_("Account Deactivated | %s"), App::get("APP_NAME")), $this->get("email"), Template::get("/_emails/accountDeactivated.tpl", [
            "app" => (object) [
                "url" => App::get("APP_URL"),
                "name" => App::get("APP_NAME"),
            ],
            "account" => (object) [
                "username" => $this->get("username"),
                "meta" => (object) [
                    "displayname" => $this->get("displayname")
                ]
            ]
        ]));
        App::set_locale_runtime($_COOKIE["locale"] ?? App::get("APP_LANGUAGE"));

        if (App::get("NOTIFY_DEACTIVATED")) {
            App::set_locale_runtime(App::get("APP_LANGUAGE"));
            Mailer::send(sprintf(_("Account Deactivated | %s"), App::get("APP_NAME")), App::get("MAIL_RECEIVER"), Template::get("/_emails/adminDeactivated.tpl", [
                "var" => (object) [
                    "username"  => $this->get("username")
                ],
                "app" => (object) [
                    "url" => App::get("APP_URL"),
                    "name" => App::get("APP_NAME")
                ]
            ]));
            App::set_locale_runtime($_COOKIE["locale"] ?? App::get("APP_LANGUAGE"));
        }
    }

    /**
     *
     *  Block the account.
     *
     *  @since  3.0
     *
     */
    public function block() {
        $this->set("role", self::BLOCKED);

        App::set_locale_runtime($this->get("language") ?? App::get("APP_LANGUAGE"));
        Mailer::send(sprintf(_("Account Blocked | %s"), App::get("APP_NAME")), $this->get("email"), Template::get("/_emails/accountBlocked.tpl", [
            "app" => (object) [
                "url" => App::get("APP_URL"),
                "name" => App::get("APP_NAME"),
            ],
            "account" => (object) [
                "username" => $this->get("username"),
                "meta" => (object) [
                    "displayname" => $this->get("displayname")
                ]
            ]
        ]));
        App::set_locale_runtime($_COOKIE["locale"] ?? App::get("APP_LANGUAGE"));
    }

    /**
     *
     *  Get the accounts role name.
     *
     *  @since  3.0
     *  @return string      The accounts role name.
     *
     */
    public function get_role_name() {
        switch($this->get("role")) {
            case self::BLOCKED:
                return _("Blocked");
                break;
            case self::DEACTIVATED:
                return _("Deactivated");
                break;
            case self::USER:
                return _("Not Verified");
                break;
            case self::VERIFIED;
                return _("User");
                break;
            case self::SUPPORTER;
                return _("Supporter");
                break;
            case self::MODERATOR;
                return _("Moderator");
                break;
            case self::ADMINISTRATOR;
                return _("Administrator");
                break;
            default:
                return _("Undefined");
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
        return ["id", "username", "email", "password", "token", "role", "registered", "lastaction", "2fa", "displayname", "firstname", "lastname", "street", "postal", "city", "country", "company", "vat", "avatar", "newsletter", "language", "timestamp", "code", "remember_me"];
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

}

?>