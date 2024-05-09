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
use MVC\Cache       as Cache;
use MVC\Database    as Database;
use MVC\Exception   as Exception;
use MVC\Fairplay    as Fairplay;
use MVC\Mailer      as Mailer;
use MVC\Models      as Model;
use MVC\Uploader    as Uploader;

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
     *  @since  2.0
     *  
     */
    public function beforeAction() {
        parent::beforeAction();
        if (empty($_POST["token"]))
            throw new Exception(_("Required input not found."), 1037);

        App::verify_instance_token(Fairplay::string($_POST["token"]));

        if (time() < strtotime($this->account->get("lastaction")) + 2)
            throw new Exception(_("Too many requests in a short time."), 1038);

        $this->account->set("lastaction", date("Y-m-d H:i:s", time()));

        if ($this->account->get("role") < Model\Account::GUEST)
            throw new Exception(_("Your account is suspended or deactivated."), 1039);
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
            case "account/login":

                if (empty($_POST["credential"]) || empty($_POST["pw"]))
                    throw new Exception(_("Required input not found."), 1040);

                $redirect = (!empty($_POST["redirect"])) ? Fairplay::string(urldecode($_POST["redirect"])) : "";

                if ((!App::get("APP_LOGIN") || App::get("APP_MAINTENANCE")) && !str_contains($redirect, "admin"))
                    throw new Exception(_("Log in not possible at the moment."), 1041);

                Model\Account::login(
                    Fairplay::string($_POST["credential"]),
                    Fairplay::string($_POST["pw"]),
                    (!empty($_POST["stay"])) ? Fairplay::integer($_POST["stay"]) : false
                );

                Ajax::add('form[data-request="account/login"]', '<div class="success">'._("Please wait while redirecting..").'</div>');
                Ajax::redirect(App::get("APP_URL").($redirect?:"/account"));
                
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1042);
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
            case "account/signup":

                if (!App::get("APP_SIGNUP") || App::get("APP_MAINTENANCE") || !empty($_POST["firstname"]))
                    throw new Exception(_("Sign up not possible at the moment."), 1043);

                if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["pw1"]) || empty($_POST["pw2"])) 
                    throw new Exception(_("Required input not found."), 1044);

                Model\Account::create(
                    Fairplay::username($_POST["username"]), 
                    Fairplay::email($_POST["email"]), 
                    Fairplay::password($_POST["pw1"], $_POST["pw2"])
                );

                Ajax::add('form[data-request="account/signup"]', '<div class="success">'._("Please wait while redirecting..").'</div>');
                Ajax::redirect(App::get("APP_URL").(!empty($_POST["redirect"]) ? Fairplay::string(urldecode($_POST["redirect"])) : "/account/email"));

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1045);
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
            case "account/recovery/request":

                if (empty($_POST["credential"]))
                    throw new Exception(_("Required input not found."), 1046);

                $credential = Fairplay::string($_POST["credential"]);

                if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential])))
                    throw new Exception(_("There is no account with this username or email address."), 1047);
            
                $account = new Model\Account($account[0]["id"]);
            
                $code = Model\Account::get_confirmcode($credential);
                $link = App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential."/".$code));

                Mailer::send(sprintf(_("Account Recovery | %s"), App::get("APP_NAME")), $account->get("email"), Cache::get("/_emails/recovery.tpl", [
                    "var" => (object) [
                        "code" => $code,
                        "link" => $link
                    ],
                    "app" => (object) [
                        "url" => App::get("APP_URL"),
                        "name" => App::get("APP_NAME"),
                    ],
                    "account" => (object) [
                        "username" => $this->account->get("username"),
                        "meta" => (object) [
                            "displayname" => $account->get("displayname")
                        ]
                    ]
                ]));

                Ajax::redirect(App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential)));
                Ajax::add('form[data-request="account/recovery/request"] .response', '<div class="success">'._("Please wait while redirecting..").'</div>');

                break;
            case "account/recovery/submit":
    
                if (empty($_POST["credential"]) || empty($_POST["pw1"]) || empty($_POST["pw2"]) || empty($_POST["code"]))
                    throw new Exception(_("Required input not found."), 1048);

                $credential = Fairplay::string($_POST["credential"]);

                if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential])))
                    throw new Exception(_("There is no account with this username or email address."), 1049);
            
                $account = new Model\Account($account[0]["id"]);

                Model\Account::verify_confirmcode($credential, str_replace(' ', '', Fairplay::string($_POST["code"])));
                if ($account->get("role") < Model\Account::DEACTIVATED)
                    throw new Exception(_("Your account cannot be restored."), 1050);
                
                $account->set("password", password_hash(Fairplay::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT));
                $account->set("role", ($account->get("role") == Model\Account::DEACTIVATED) ? Model\Account::VERIFIED : $account->get("role"));
                $account->set("token", App::get_instance_token());

                Ajax::add('form[data-request="account/recovery/submit"]', '<div class="success">'._("Account successfully restored. You can now log in as usual.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1051);
        }
    }

    /**
     * 
     * Handles user editing-related actions, including updating username, avatar, password, and custom fields.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function personalAction(string $request) {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 1052);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 1053);

        switch($request) {
            case "account/personal/edit":

                $current_meta = $this->account->get("meta");

                if (isset($_POST["displayname"]) && $_POST["displayname"] != $this->account->get("displayname"))
                    $this->account->set("displayname", Fairplay::string($_POST["displayname"]));

                if (isset($_POST["company"]) && $_POST["company"] != $this->account->get("company"))
                    $this->account->set("company", Fairplay::string($_POST["company"]));

                if (isset($_POST["firstname"]) && $_POST["firstname"] != $this->account->get("firstname"))
                    $this->account->set("firstname", Fairplay::string($_POST["firstname"]));

                if (isset($_POST["lastname"]) && $_POST["lastname"] != $this->account->get("lastname"))
                    $this->account->set("lastname", Fairplay::string($_POST["lastname"]));

                switch($this->account->get("displayname")) {
                    case $current_meta["company"]??"":
                        $this->account->set("displayname", $this->account->get("company"));
                        break;
                    case $current_meta["firstname"]??"": 
                        $this->account->set("displayname", $this->account->get("firstname"));
                        break;
                    case $current_meta["lastname"]??"": 
                        $this->account->set("displayname", $this->account->get("lastname"));
                        break;
                    case ($current_meta["firstname"]??"")." ".($current_meta["lastname"]??""): 
                        $this->account->set("displayname", $this->account->get("firstname")." ".$this->account->get("lastname"));
                        break;
                    case ($current_meta["lastname"]??"")." ".($current_meta["firstname"]??"");
                        $this->account->set("displayname", $this->account->get("lastname")." ".$this->account->get("firstname"));
                        break;
                }

                if (isset($_POST["street"]) && $_POST["street"] != $this->account->get("street"))
                    $this->account->set("street", Fairplay::string($_POST["street"]));

                if (isset($_POST["postal"]) && $_POST["postal"] != $this->account->get("postal"))
                    $this->account->set("postal", Fairplay::string($_POST["postal"]));

                if (isset($_POST["city"]) && $_POST["city"] != $this->account->get("city"))
                    $this->account->set("city", Fairplay::string($_POST["city"]));

                if (isset($_POST["country"]) && $_POST["country"] != $this->account->get("country"))
                    $this->account->set("country", Fairplay::string($_POST["country"]));

                Ajax::add('form[data-request="account/personal/edit"] .response', '<div class="success">'._("Changes successfully saved.").'</div>');
                
                break;
            case "account/personal/avatar/upload":

                if (!isset($_FILES["avatar"]))
                    throw new Exception(_("Required input not found."), 1054);

                if ($this->account->get("role") < Model\Account::VERIFIED)
                    throw new Exception(_("You have to verify your email address before you can upload an avatar."), 1055);

                $this->account->set("avatar", Uploader::upload($_FILES["avatar"], Uploader::AVATAR));

                Ajax::add('.account .avatar', '<img src="'.App::get("APP_URL").App::get("DIR_MEDIA")."/avatars/".$this->account->get("avatar").'"/>');
                Ajax::add('form[data-request="account/personal/avatar/upload"] .response', '<div class="success">'._("Avatar successfully uploaded.").'</div>');

                break;
            case "account/personal/avatar/delete":

               $this->account->set("avatar", null);

                Ajax::remove('.account .avatar img');
                Ajax::add('form[data-request="account/personal/avatar/delete"] .response', '<div class="success">'._("Avatar successfully deleted.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1056);
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
            throw new Exception(_("App currently offline. Please try again later."), 1057);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 1058);

        switch($request) {
            case "account/security/edit":

                if (!empty($_POST["pw"]) && !empty($_POST["pw1"]) && !empty($_POST["pw2"])) {
                    if (!password_verify($_POST["pw"], $this->account->get("password"))) 
                        throw new Exception(_("Your current password does not match."), 1059);
                
                    $this->account->set("password", password_hash(Fairplay::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT));
                    $this->account->set("token", App::get_instance_token());
                    Model\Account::set_auth_cookie($this->account->get("id"), App::get_instance_token(), 0);
                }

                Ajax::add('form[data-request="account/security/edit"] .response', '<div class="success">'._("Changes successfully saved.").'</div>');
                
                break;
            case "account/security/logout":
    
                $this->account->set("token", App::get_instance_token());
                Model\Account::set_auth_cookie($this->account->get("id"), App::get_instance_token(), 0);
                Ajax::add('form[data-request="account/security/logout"] .response', '<div class="success">'._("Sessions successfully logged out."));
        
                break;
            case "account/security/deactivate":

                if ($this->account->get("role") == Model\Account::ADMINISTRATOR)
                    throw new Exception(_("You can not deactivate your account."), 1060);

                $this->account->set("role", Model\Account::DEACTIVATED);

                Mailer::send(sprintf(_("Account Deactivated | %s"), App::get("APP_NAME")), $this->account->get("email"), Cache::get("/_emails/deactivated.tpl", [
                    "app" => (object) [
                        "url" => App::get("APP_URL"),
                        "name" => App::get("APP_NAME"),
                    ],
                    "account" => (object) [
                        "username" => $this->account->get("username"),
                        "meta" => (object) [
                            "displayname" => $this->account->get("displayname")
                        ]
                    ]
                ]));

                Ajax::add('form[data-request="account/security/deactivate"]', '<div class="success">'._("Please wait while redirecting..").'</div>');
                Ajax::redirect(App::get("APP_URL")."/goodbye");

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1061);
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
            throw new Exception(_("App currently offline. Please try again later."), 1062);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 1063);

        switch($request) {
            case "account/email/edit":

                if (!empty($_POST["email"]) && $_POST["email"] != $this->account->get("email")) {
                    $this->account->set("email", strtolower(Fairplay::email($_POST["email"])));
                    $this->account->set("role", ($this->account->get("role") == Model\Account::VERIFIED) ? Model\Account::USER : $this->account->get("role"));
                }

                if (isset($_POST["newsletter"]) && $_POST["newsletter"] != $this->account->get("newsletter"))
                    $this->account->set("newsletter", Fairplay::integer($_POST["newsletter"]));

                Ajax::add('form[data-request="account/email/edit"] .response', '<div class="success">'._("Changes successfully saved.").'</div>');
                
                break;
            case "account/email/verify":

                if ($this->account->get("role") > Model\Account::USER)
                    throw new Exception(_("Your account does not have the required role."), 1064);

                if (empty($_POST["code"])) {
                    $code = Model\Account::get_confirmcode($this->account->get("email"));
                    $link = App::get("APP_URL")."/account/email?code=".str_replace('=', '', base64_encode($code));

                    Mailer::send(sprintf(_("Email Address Verification | %s"), App::get("APP_NAME")), $this->account->get("email"), Cache::get("/_emails/verify.tpl", [
                        "var" => (object) [
                            "code" => $code,
                            "link" => $link
                        ],
                        "app" => (object) [
                            "url" => App::get("APP_URL"),
                            "name" => App::get("APP_NAME"),
                        ],
                        "account" => (object) [
                            "username" => $this->account->get("username"),
                            "meta" => (object) [
                                "displayname" => $this->account->get("displayname")
                            ]
                        ]
                    ]));

                    Ajax::add('form[data-request="account/email/verify"]', Cache::get("/_includes/verify.tpl", ["request" => (object) ["code" => "", "ajax" => true]]));
                }
                else {
                    Model\Account::verify_confirmcode($this->account->get("email"), str_replace(' ', '', Fairplay::string($_POST["code"])));
                    $this->account->set("role", ($this->account->get("role") == Model\Account::USER) ? Model\Account::VERIFIED : $this->account->get("role"));
                    Ajax::add('form[data-request="account/email/verify"]', '<div class="success">'._("Email address successfully verified.").'</div>');
                }

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1066);
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
            case "account/locale":

                if (empty($_POST["value"]))
                    throw new Exception(_("Required input not found."), 1067);

                $this->account->set("language", Fairplay::string($_POST["value"]));
                Model\Account::set_locale_cookie($this->account->get("language"), time()+(60*60*24*90));

                Ajax::add('form[data-request="account/locale"]', '<div class="success">'._("Please wait while redirecting..").'</div>');
                Ajax::reload();
    
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1068);
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
            case "account/help":
            case "account/contact":
            case "account/feedback":

                if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["message"]) || !empty($_POST["firstname"]))
                    throw new Exception(_("Required input not found."), 1069);

                if (isset($_FILES["attachment"]) && $this->account->get("role") < Model\Account::VERIFIED)
                    throw new Exception(_("You have to verify your email address before you can send a message with an attachment."), 1070);

                Mailer::send(sprintf(_("New Message | %s"), App::get("APP_NAME")), App::get("MAIL_RECEIVER"), Cache::get("/_emails/message.tpl", [
                    "var" => (object) [
                        "name"  => Fairplay::string($_POST["name"]),
                        "email" => Fairplay::email($_POST["email"]),
                        "subject" => Fairplay::string($_POST["subject"] ?? ""),
                        "platform" => Fairplay::string($_POST["platform"] ?? ""),
                        "message" => Fairplay::string($_POST["message"]),
                        "attachment" => (isset($_FILES["attachment"])) ? Uploader::upload($_FILES["attachment"], Uploader::ATTACHMENT) : ""
                    ],
                    "app" => (object) [
                        "url" => App::get("APP_URL"),
                        "name" => App::get("APP_NAME"),
                        "directory" => (object) [
                            "media" => App::get("DIR_MEDIA")
                        ]
                    ]
                ])); 
    
                Ajax::add('form[data-request="'.$request.'"]', '<div class="success">'._("Message successfully sent.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1071);
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