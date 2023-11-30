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

use MVC\Ajax      as Ajax;
use MVC\App       as App;
use MVC\Auth      as Auth;
use MVC\Database  as Database;
use MVC\Email     as Email;
use MVC\Exception as Exception;
use MVC\Fairplay  as Fairplay;
use MVC\Models    as Model;
use MVC\Template  as Template;

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
     */
    public function beforeAction() {
        parent::beforeAction();
        if (empty($_POST["client"]))
            throw new Exception(_("Required input not found."), 1038);

        Auth::verify_client_token(Fairplay::string($_POST["client"]));

        if (time() < strtotime($this->account->get("lastaction")) + 2)
            throw new Exception(_("Too many requests in a short time."), 1039);

        $this->account->set("lastaction", date("Y-m-d H:i:s", time()));

        if ($this->account->get("role") < Model\Account::GUEST)
            throw new Exception(_("Your account is suspended or deactivated."), 1040);
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

                if (empty($_POST["language"]))
                    throw new Exception(_("Required input not found."), 1100);

                $this->account->set("language", Fairplay::string($_POST["language"]));
                Auth::set_locale_cookie($this->account->get("language"), time()+(60*60*24*90));

               Ajax::reload();
    
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1101);
        }   
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
                    throw new Exception(_("Required input not found."), 1041);

                $redirect = (!empty($_POST["redirect"])) ? Fairplay::string($_POST["redirect"]) : "";

                if ((!App::get("APP_LOGIN") || App::get("APP_MAINTENANCE")) && $redirect != "/admin")
                        throw new Exception(_("Log in not possible at the moment."), 1042);

                Auth::set_current_account(
                    Fairplay::string($_POST["credential"]),
                    Fairplay::string($_POST["pw"]),
                    (!empty($_POST["stay"])) ? Fairplay::integer($_POST["stay"]) : false
                );

                Ajax::add('.response', '<div class="success">'._("Please wait while redirecting..").'</div>');

                if ($redirect != "")
                    Ajax::redirect(App::get("APP_URL").$redirect);
                else 
                    Ajax::redirect(App::get("APP_URL")."/account");

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1043);
        }
    }

    /**
     * 
     *  Handles the logout action, including session and cookie termination.
     * 
     *  @since  2.0
     *  @param  string  $request    The requested action
     * 
     */
    public function logoutAction(string $request) {
        switch($request) {
            case "account/logout":

                Ajax::add('.response', '<div class="success">'._("Please wait while redirecting..").'</div>');
                Ajax::redirect(App::get("APP_URL")."/logout");

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1044);
        }
    }

    /**
     * 
     *  Handles the global logout action, including generating a new account token and cookie.
     * 
     *  @since  2.0
     *  @param  string  $request    The requested action
     * 
     */
    public function glogoutAction(string $request) {
        switch($request) {
            case "account/glogout":

                $this->account->set("token", Auth::get_instance_token());
                Auth::set_auth_cookie($this->account->get("id"), Auth::get_instance_token(), 0);
                Ajax::add(".response", '<div class="success">'._("Sessions successfully logged out."));
    
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1045);
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

                if (!App::get("APP_SIGNUP") || App::get("APP_MAINTENANCE"))
                    throw new Exception(_("Sign up not possible at the moment."), 1046);

                if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["pw1"]) || empty($_POST["pw2"])) 
                    throw new Exception(_("Required input not found."), 1047);

                Auth::set_new_account(
                    Fairplay::username($_POST["username"]), 
                    Fairplay::email($_POST["email"]), 
                    Fairplay::password($_POST["pw1"], $_POST["pw2"])
                );

                Ajax::add('.response', '<div class="success">'._("Please wait while redirecting..").'</div>');

                if (!empty($_POST["redirect"]))
                    Ajax::redirect(App::get("APP_URL").Fairplay::string($_POST["redirect"]));
                else 
                    Ajax::redirect(App::get("APP_URL")."/account/verify");

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1048);
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
                    throw new Exception(_("Required input not found."), 1049);

                $credential = Fairplay::string($_POST["credential"]);

                if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential])))
                    throw new Exception(_("There is no account with this username or email address."), 1050);
            
                $account = new Model\Account($account[0]["id"]);
            
                $code = Auth::get_confirmcode($credential);
                $link = App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential."/".$code));

                Email::send(sprintf(_("Account recovery | %s"), App::get("APP_NAME")), $account->get("email"), Template::get("email/recovery.tpl", [
                    "var" => (object) [
                        "code" => $code,
                        "link" => $link
                    ],
                    "app" => (object) [
                        "url" => App::get("APP_URL"),
                        "name" => App::get("APP_NAME")
                    ],
                    "account" => (object) [
                        "id" => $account->get("id"),
                        "email" => $account->get("email"),
                        "username" => $account->get("username"),
                        "displayname" => $account->get("displayname") ?? $account->get("username")
                    ]
                ]));

                Ajax::redirect(App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential)));
                Ajax::add('.response', '<div class="success">'._("Please wait while redirecting..").'</div>');

                break;
            case "account/recovery/submit":
    
                if (empty($_POST["credential"]) || empty($_POST["pw1"]) || empty($_POST["pw2"]) || empty($_POST["code"]))
                    throw new Exception(_("Required input not found."), 1051);

                $credential = Fairplay::string($_POST["credential"]);

                if (empty($account = Database::query("SELECT * FROM app_accounts WHERE email LIKE ? OR username = ?", [$credential, $credential])))
                    throw new Exception(_("There is no account with this username or email address."), 1052);
            
                $account = new Model\Account($account[0]["id"]);

                Auth::verify_confirmcode($credential, str_replace(' ', '', Fairplay::string($_POST["code"])));
                if ($account->get("role") < Model\Account::DEACTIVATED)
                    throw new Exception(_("Your account cannot be restored."), 1053);
                
                $account->set("password", password_hash(Fairplay::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT));
                $account->set("role", ($account->get("role") == Model\Account::DEACTIVATED) ? Model\Account::USER : $account->get("role"));
                $account->set("token", Auth::get_instance_token());

                Ajax::add('.recovery .main-content form', '<div class="success">'._("Account successfully restored. You can now log in as usual.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1054);
        }
    }

}

?>