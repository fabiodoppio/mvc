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


namespace MVC\Controllers;

use MVC\Ajax      as Ajax;
use MVC\App       as App;
use MVC\Auth      as Auth;
use MVC\Database  as Database;
use MVC\Email     as Email;
use MVC\Exception as Exception;
use MVC\Fairplay  as Fairplay;
use MVC\Models    as Model;
use MVC\Request   as Request;
use MVC\Template  as Template;

/**
 * AccountController Class
 *
 * The AccountController class handles user account-related actions, such as login, signup, and account recovery.
 */
class AccountController extends Controller {

    /**
     * Executes before performing any action in the controller.
     * It performs necessary checks, such as verifying the client token, user activity rate, server status, and user role.
     *
     * @throws Exception If any of the checks fail.
     */
    public function beforeAction() {
        parent::beforeAction();
        Auth::verify_client_token(Request::get("client"));

        if (time() < strtotime($this->account->get("lastaction")) + 2)
            throw new Exception(_("Too many requests in a short time."));

        $this->account->set("lastaction", date("Y-m-d H:i:s", time()));

        if (!App::get("APP_ONLINE") && $this->account->get("role") != Model\Role::ADMINISTRATOR)
            throw new Exception(_("Server currently offline."));

        if ($this->account->get("role") < Model\Role::GUEST)
            throw new Exception(_("Your account has been suspended or deactivated."));
    }

    /**
     * Handles user login attempts.
     *
     * @throws Exception If login is not allowed, or if there are issues with the login credentials.
     */
    public function loginAction() {
        if (!App::get("APP_LOGIN"))
            throw new Exception(_("Login not possible at the moment."));

        Auth::set_current_account(
            Fairplay::string(Request::get("credential")), 
            Fairplay::string(Request::get("pw")),
            Fairplay::boolean(Request::isset("stay") ? Request::get("stay") : false));

        Ajax::redirect(App::get("APP_URL")."/account");
    }

    /**
     * Handles user registration/signup.
     *
     * @throws Exception If signup is not allowed or if there are issues with the registration data.
     */
    public function signupAction() {
        if (!App::get("APP_SIGNUP"))
            throw new Exception(_("Signup not possible at the moment."));

        Auth::set_new_account(
            Fairplay::username(Request::get("username")), 
            Fairplay::email(Request::get("email")),
            Fairplay::password(Request::get("pw1"), Request::get("pw2")));

        Ajax::redirect(App::get("APP_URL")."/account/verify");
    }

    /**
     * Handles account recovery requests, including sending recovery emails and setting new passwords.
     *
     * @throws Exception If there are issues with the recovery process.
     */
    public function recoveryAction() {
        $credential = Fairplay::string(Request::get("credential"));
        if (empty($account = Database::select("app_accounts", "email LIKE '".$credential."' OR username = '".$credential."'")))
            throw new Exception(_("There is no account with this username or email address."));

        $account = new Model\Account($account[0]["id"]);

        switch(Request::get("requestParts")[2]??"") {
            case "request":
                $code = Auth::get_confirmcode($credential);
                $link = App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential."/".$code));

                Email::send(sprintf(_("Account recovery | %s"), App::get("APP_NAME")), $account->get("email"), Template::get("email/recovery.tpl", [
                    "username" => $account->get("username"),
                    "app_name" => App::get("APP_NAME"),
                    "code" => $code,
                    "link" => $link
                ]));

                Ajax::redirect(App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential)));
                break;
            case "submit":
                Auth::verify_confirmcode($credential, Fairplay::string(str_replace(' ', '', Request::get("code"))));
                if ($account->get("role") < Model\Role::DEACTIVATED)
                    throw new Exception(_("Your account cannot be restored."));
            
                $account->set("password", password_hash(Fairplay::password(Request::get("pw1"), Request::get("pw2")), PASSWORD_DEFAULT));
                $account->set("role", ($account->get("role") == Model\Role::DEACTIVATED) ? Model\Role::USER : $account->get("role"));
                $account->set("token", Auth::get_instance_token());

                Ajax::add('.main-content form', '<div class="success">'._("Your account has been successfully restored. You can now log in as usual.").'</div>');
                break;
        }
    }

}

?>