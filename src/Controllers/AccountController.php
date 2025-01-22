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


namespace MVC\Controllers;

use MVC\Ajax        as Ajax;
use MVC\App         as App;
use MVC\Exception   as Exception;
use MVC\Mailer      as Mailer;
use MVC\Models      as Model;
use MVC\Template    as Template;
use MVC\Uploader    as Uploader;
use MVC\Validator   as Validator;

/**
 *
 *  AccountController Class
 *
 *  The AccountController class is a specific controller handling actions related to user accounts,
 *  such as login, logout, signup, and account recovery.
 *
 */
class AccountController extends Controller {

    /**
     *
     *  Executes actions before the main action, including client verification,
     *  rate limiting, and checking the user account status.
     *
     *  @since  2.2     Token is no longer verified via POST but via Header Authorization.
     *  @since  2.0
     *
     */
    public function beforeAction() {
        parent::beforeAction();

        App::verify_instance_token(App::get_bearer_token());

        if (time() < strtotime($this->account->get("lastaction")) + 2)
            throw new Exception(_("Too many requests in a short time."), 1600);

        $this->account->set("lastaction", date("Y-m-d H:i:s", time()));

        if ($this->account->get("role") < Model\Account::GUEST)
            throw new Exception(_("Your account is suspended or deactivated."), 1601);
    }

    /**
     *
     *  Handles the login action, including account authentication.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action
     *
     */
    public function loginAction(string $request) {
        switch($request) {
            case "/account/login":

                if (!App::get("APP_LOGIN"))
                    throw new Exception(_("Log in not possible at the moment."), 1602);

                if (isset($_SESSION["tmp_post"])) {
                    $_POST = array_merge($_SESSION["tmp_post"], $_POST);
                    unset($_SESSION["tmp_post"]);
                    session_regenerate_id();
                }

                if (empty($_POST["credential"]) || empty($_POST["pw"]))
                    throw new Exception(_("Required input not found."), 1603);

                $credential = Validator::string($_POST["credential"]);
                $password   = Validator::string($_POST["pw"]);
                $account    = App::get_account_by_credential($credential);
                $remember   = (!empty($_POST["remember"])) ? Validator::integer($_POST["remember"]) : null;
                $redirect   = (!empty($_POST["redirect"])) ? Validator::string($_POST["redirect"]) : "";

                $account->attempt_login($password);

                if ($account->get("2fa"))
                    if (empty($_POST["code"])) {

                        $_SESSION["tmp_post"] = [
                            "credential" => $credential,
                            "pw"         => $password,
                            "remember"   => $remember,
                            "redirect"   => $redirect
                        ];

                        $account->request_2fa_code();
                        $account->log("2fa_code_requested");
                        Ajax::add('form[data-request="account/login"', Template::get("/_forms/2fa.tpl", ["app" => (object) ["url" => App::get("APP_URL")]]), Ajax::REPLACE);

                        return;
                    }
                    else
                        $account->attempt_2fa_login(Validator::string($_POST["code"]));

                if (isset($_SESSION["tmp_post"])) {
                    unset($_SESSION["tmp_post"]);
                    session_regenerate_id();
                }

                $account->set("remember_me", $remember);

                App::set_auth_cookie($account->get("id"), $account->get("token"), $remember);
                App::set_locale_cookie($account->get("language")??App::get("APP_LANGUAGE"));
                Ajax::add('form[data-request="account/login"]', '<div class="alert is--success">'._("Please wait while redirecting..").'</div>');
                Ajax::redirect(App::get("APP_URL").($redirect ? urldecode($redirect) : "/account"));

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1604);
        }
    }

    /**
     *
     *  Handles the signup action, including creating of a new account.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action
     *
     */
    public function signupAction(string $request) {
        switch($request) {
            case "/account/signup":

                if (!App::get("APP_SIGNUP") || App::get("APP_MAINTENANCE") || !empty($_POST["firstname"]))
                    throw new Exception(_("Sign up not possible at the moment."), 1605);

                if ($this->account->get("role") != Model\Account::GUEST)
                    throw new Exception(_("Your account does not have the required role."), 1606);

                if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["pw1"]) || empty($_POST["pw2"]))
                    throw new Exception(_("Required input not found."), 1607);

                $this->account->signup(Validator::username($_POST["username"]), Validator::email($_POST["email"]), Validator::password($_POST["pw1"], $_POST["pw2"]));
                Ajax::add('form[data-request="account/signup"]', '<div class="alert is--success">'._("Please wait while redirecting..").'</div>');
                Ajax::redirect(App::get("APP_URL").(!empty($_POST["redirect"]) ? Validator::string(urldecode($_POST["redirect"])) : "/"));

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1608);
        }
    }

    /**
     *
     *  Handles the account recovery actions, including sending recovery emails and restoring accounts.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action
     *
     */
    public function recoveryAction(string $request) {
        switch($request) {
            case "/account/recovery":

                if (isset($_SESSION["tmp_post"]))
                    $_POST = array_merge($_SESSION["tmp_post"], $_POST);

                if (empty($_POST["credential"]))
                    throw new Exception(_("Required input not found."), 1609);

                $credential = Validator::string($_POST["credential"]);
                $account    = App::get_account_by_credential($credential);

                if (empty($_POST["code"])) {
                    $_SESSION["tmp_post"] = [
                        "credential" => $credential
                    ];

                    $account->request_recovery_code();
                    $account->log("recovery_code_requested");
                    Ajax::add('form[data-request="account/recovery"]', Template::get("/_forms/recovery.tpl", ["app" => (object) ["url" => App::get("APP_URL")]]), Ajax::REPLACE);
                }
                else {
                    if (empty($_POST["pw1"]) || empty($_POST["pw2"]))
                        throw new Exception(_("Required input not found."), 1610);

                    if (isset($_SESSION["tmp_post"])) {
                        unset($_SESSION["tmp_post"]);
                        session_regenerate_id();
                    }

                    $account->recover(Validator::string($_POST["code"]), Validator::password($_POST["pw1"], $_POST["pw2"]));
                    Ajax::add('form[data-request="account/recovery"]', '<div class="alert is--success">'.sprintf(_("Account successfully restored. You can now <a href=\"%s/login\">log in</a> as usual."), App::get("APP_URL")).'</div>');
                }

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1611);
        }
    }

    /**
     *
     * Handles account editing-related actions, including updating username, avatar, password, and custom fields.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     *
     */
    public function personalAction(string $request) {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 1612);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 1613);

        switch($request) {
            case "/account/personal/edit":

                $current_meta = $this->account->get("meta");

                if (isset($_POST["displayname"]) && $_POST["displayname"] != $this->account->get("displayname"))
                    $this->account->set("displayname", Validator::string($_POST["displayname"]));

                if (isset($_POST["company"]) && $_POST["company"] != $this->account->get("company"))
                    $this->account->set("company", Validator::string($_POST["company"]));

                if (isset($_POST["firstname"]) && $_POST["firstname"] != $this->account->get("firstname"))
                    $this->account->set("firstname", Validator::string($_POST["firstname"]));

                if (isset($_POST["lastname"]) && $_POST["lastname"] != $this->account->get("lastname"))
                    $this->account->set("lastname", Validator::string($_POST["lastname"]));

                switch($this->account->get("displayname") ?: null) {
                    case $current_meta["company"] ?? "":
                        $this->account->set("displayname", $this->account->get("company"));
                        break;
                    case $current_meta["firstname"] ?? "":
                        $this->account->set("displayname", $this->account->get("firstname"));
                        break;
                    case $current_meta["lastname"] ?? "":
                        $this->account->set("displayname", $this->account->get("lastname"));
                        break;
                    case ($current_meta["firstname"] ?? "")." ".($current_meta["lastname"] ?? ""):
                        $this->account->set("displayname", $this->account->get("firstname")." ".$this->account->get("lastname"));
                        break;
                    case ($current_meta["lastname"] ?? "")." ".($current_meta["firstname"] ?? "");
                        $this->account->set("displayname", $this->account->get("lastname")." ".$this->account->get("firstname"));
                        break;
                }

                if (isset($_POST["street"]) && $_POST["street"] != $this->account->get("street"))
                    $this->account->set("street", Validator::string($_POST["street"]));

                if (isset($_POST["postal"]) && $_POST["postal"] != $this->account->get("postal"))
                    $this->account->set("postal", Validator::string($_POST["postal"]));

                if (isset($_POST["city"]) && $_POST["city"] != $this->account->get("city"))
                    $this->account->set("city", Validator::string($_POST["city"]));

                if (isset($_POST["country"]) && $_POST["country"] != $this->account->get("country"))
                    $this->account->set("country", Validator::string($_POST["country"]));

                if (isset($_POST["vat"]) && $_POST["vat"] != $this->account->get("vat"))
                    $this->account->set("vat", Validator::string($_POST["vat"]));

                $this->account->log("personal_data_saved");

                Ajax::add('#response', '<div class="alert is--success">'._("Changes successfully saved.").'</div>');

                break;
            case "/account/personal/avatar/upload":

                if (!isset($_FILES["avatar"]))
                    throw new Exception(_("Required input not found."), 1614);

                if ($this->account->get("role") < Model\Account::VERIFIED)
                    throw new Exception(_("You have to verify your email address before you can upload an avatar."), 1615);

                $this->account->set("avatar", Uploader::upload($_FILES["avatar"], Uploader::AVATAR));
                $this->account->log("avatar_uploaded");
                Ajax::add('.avatar', '<img src="'.App::get("APP_URL")."/media/avatars/".$this->account->get("avatar").'"/>');
                Ajax::add('#response', '<div class="alert is--success">'._("Avatar successfully uploaded.").'</div>');

                break;
            case "/account/personal/avatar/delete":

                $this->account->set("avatar", null);
                $this->account->log("avatar_deleted");
                Ajax::remove('.avatar img');
                Ajax::add('#response', '<div class="alert is--success">'._("Avatar successfully deleted.").'</div>');

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1616);
        }
    }

    /**
     *
     * Handles user securiry-related actions, including updating passwords and global logout.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     *
     */
    public function securityAction(string $request) {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 1617);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 1618);

        switch($request) {
            case "/account/security/edit":

                if (!empty($_POST["pw"]) && !empty($_POST["pw1"]) && !empty($_POST["pw2"])) {
                    if (!password_verify($_POST["pw"], $this->account->get("password")))
                        throw new Exception(_("Your current password does not match."), 1619);

                    $new_token = App::generate_token();
                    $this->account->set("password", password_hash(Validator::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT));
                    $this->account->set("token", $new_token);
                    App::set_auth_cookie($this->account->get("id"), $new_token, 0);

                    $this->account->log("security_data_saved");

                    Ajax::add('#response', '<div class="alert is--success">'._("Changes successfully saved.").'</div>');
                }

                break;
            case "/account/security/2fa":

                if ($this->account->get("role") < Model\Account::VERIFIED)
                    throw new Exception(_("You have to verify your email address before you can acitvate the 2-Factor Authentication."), 1620);

                $this->account->set("2fa", $this->account->get("2fa") ? null : 1);

                if ($this->account->get("2fa")) {
                    $new_token = App::generate_token();
                    $this->account->set("token", $new_token);
                    $this->account->log("2fa_added");
                    App::set_auth_cookie($this->account->get("id"), $new_token, $this->account->get("remember_me"));
                    Ajax::add('form[data-request="account/security/2fa"] .btn.is--primary', _("Deactivate"));
                    Ajax::add('form[data-request="account/security/2fa"] .alert.is--error', '<div class="alert is--success"><i class="fas fa-shield-heart"></i> '._("Your account is protected by the 2-Factor Authentication.").'</div>', Ajax::REPLACE);
                }
                else {
                    $this->account->log("2fa_removed");
                    Ajax::add('form[data-request="account/security/2fa"] .btn.is--primary', _("Activate"));
                    Ajax::add('form[data-request="account/security/2fa"] .alert.is--success', '<div class="alert is--error"><i class="fas fa-shield-halved"></i> '._("Your account is <b>not</b> protected by the 2-Factor Authentication.").'</div>', Ajax::REPLACE);
                }

                break;
            case "/account/security/logout":

                $new_token = App::generate_token();
                $this->account->set("token", $new_token);
                $this->account->log("renew_auth_cookie");
                App::set_auth_cookie($this->account->get("id"), $new_token, $this->account->get("remember_me"));
                Ajax::add('#response', '<div class="alert is--success">'._("Sessions successfully logged out."));

                break;
            case "/account/security/deactivate":

                if ($this->account->get("role") == Model\Account::ADMINISTRATOR)
                    throw new Exception(_("You can not deactivate your account."), 1621);

                $this->account->deactivate();
                $this->account->log("account_deactivated");
                Ajax::add('#response', '<div class="alert is--success">'._("Please wait while redirecting..").'</div>');
                Ajax::redirect(App::get("APP_URL")."/goodbye");

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1622);
        }
    }

    /**
     *
     * Handles user email-related actions, including updating email, and verification.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     *
     */
    public function emailAction(string $request) {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 1623);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 1624);

        switch($request) {
            case "/account/email/edit":

                if (!empty($_POST["email"]) && $_POST["email"] != $this->account->get("email")) {
                    $this->account->set("email", strtolower(Validator::email($_POST["email"])));
                    $this->account->set("2fa", ($this->account->get("role") <= Model\Account::VERIFIED) ? null : $this->account->get("2fa"));
                    $this->account->set("role", ($this->account->get("role") == Model\Account::VERIFIED) ? Model\Account::USER : $this->account->get("role"));

                    $new_token = App::generate_token();
                    $this->account->set("token", $new_token);
                    App::set_auth_cookie($this->account->get("id"), $new_token, $this->account->get("remember_me"));

                    if ($this->account->get("role") < Model\Account::VERIFIED)
                        Ajax::add('form[data-request="account/email/verify"]', '<div class="alert is--error"><i class="fas fa-circle-xmark"></i> '._("Your email adress is <b>not</b> verified.").'</div><button class="btn is--primary is--submit">'._("Request").'</button>');
                }

                if (isset($_POST["newsletter"]) && $_POST["newsletter"] != $this->account->get("newsletter"))
                    $this->account->set("newsletter", Validator::integer($_POST["newsletter"]));

                $this->account->log("email_data_saved");

                Ajax::add('#response', '<div class="alert is--success">'._("Changes successfully saved.").'</div>');

                break;
            case "/account/email/verify":

                if ($this->account->get("role") > Model\Account::USER)
                    throw new Exception(_("Your account does not have the required role."), 1625);

                if (empty($_POST["code"])) {
                    $this->account->request_verification_code();
                    Ajax::add('form[data-request="account/email/verify"]', Template::get("/_forms/verify.tpl", ["app" => (object) ["url" => App::get("APP_URL")]]), Ajax::REPLACE);
                }
                else {
                    $this->account->verify(Validator::string($_POST["code"]));
                    $this->account->log("email_verified");
                    Ajax::add('form[data-request="account/email/verify"]', '<div class="alert is--success">'._("Email address successfully verified.").'</div>');
                }

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1626);
        }
    }

    /**
     *
     *  Handles the locale action, including changing to account's preferred language.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action
     *
     */
    public function localeAction(string $request) {
        switch($request) {
            case "/account/locale":

                if (empty($_POST["value"]))
                    throw new Exception(_("Required input not found."), 1627);

                $this->account->set("language", Validator::string($_POST["value"]));

                App::set_locale_cookie($this->account->get("language"));
                Ajax::add('#response', '<div class="alert is--success">'._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1628);
        }
    }

    /**
     *
     *  Handles requests from any contact form.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action
     *
     */
    public function helpAction(string $request) {
        switch($request) {
            case "/account/help":
            case "/account/contact":
            case "/account/feedback":

                if (empty($_POST["displayname"]) || empty($_POST["email"]) || empty($_POST["message"]) || !empty($_POST["firstname"]))
                    throw new Exception(_("Required input not found."), 1629);

                if (isset($_FILES["attachment"]) && $this->account->get("role") < Model\Account::VERIFIED)
                    throw new Exception(_("You have to verify your email address before you can send a message with an attachment."), 1630);

                $prefix = (!empty($_POST["emergency"]) && $_POST["emergency"] == 1) ? "["._("URGENT")."] " : "";

                App::set_locale_runtime(App::get("APP_LANGUAGE"));
                Mailer::send($prefix.sprintf(_("New Message | %s"), App::get("APP_NAME")), App::get("MAIL_RECEIVER"), Template::get("/_emails/adminMessage.tpl", [
                    "var" => (object) [
                        "prefix" => $prefix,
                        "displayname"  => Validator::string($_POST["displayname"]),
                        "email" => Validator::email($_POST["email"]),
                        "subject" => Validator::string($_POST["subject"] ?? ""),
                        "platform" => Validator::string($_POST["platform"] ?? ""),
                        "message" => Validator::string($_POST["message"]),
                        "attachment" => (isset($_FILES["attachment"])) ? Uploader::upload($_FILES["attachment"], Uploader::ATTACHMENT) : ""
                    ],
                    "app" => (object) [
                        "url" => App::get("APP_URL"),
                        "name" => App::get("APP_NAME")
                    ]
                ]), $_POST["email"]);
                App::set_locale_runtime($_COOKIE["locale"] ?? App::get("APP_LANGUAGE"));

                if (App::get("NOTIFY_RECEIVED"))
                    Mailer::send(sprintf(_("We received your message | %s"), App::get("APP_NAME")), $_POST["email"] , Template::get("/_emails/guestMessage.tpl", [
                        "var" => (object) [
                            "displayname"  => $_POST["displayname"],
                        ],
                        "app" => (object) [
                            "url" => App::get("APP_URL"),
                            "name" => App::get("APP_NAME")
                        ]
                    ]), App::get("MAIL_RECEIVER"));

                Ajax::add('form[data-request="account/help"]', '<div class="alert is--success">'._("Message successfully sent.").'</div>');
                Ajax::add('form[data-request="account/contact"]', '<div class="alert is--success">'._("Message successfully sent.").'</div>');
                Ajax::add('form[data-request="account/feedback"]', '<div class="alert is--success">'._("Message successfully sent.").'</div>');

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1631);
        }
    }

    /**
     *
     *  Synonym action name for helpAction(string $request) above.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action
     *
     */
    public function contactAction(string $request) {
        $this->helpAction($request);
    }

    /**
     *
     *  Synonym action name for helpAction(string $request) above.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action
     *
     */
    public function feedbackAction(string $request) {
        $this->helpAction($request);
    }

}

?>