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
        Auth::verify_client_token(Request::string("client"));

        if (time() < strtotime($this->account->get("lastaction")) + 2)
            throw new Exception(_("Too many requests in a short time."));

        $this->account->set("lastaction", date("Y-m-d H:i:s", time()));

        if ($this->account->get("role") < Model\Account::GUEST)
            throw new Exception(_("Your account has been suspended or deactivated."));
    }

    /**
     * Handles user login attempts.
     *
     * @throws Exception If login is not allowed, or if there are issues with the login credentials.
     */
    public function loginAction() {
        switch(Request::string("request")) {
            case "account/login":
                $redirect = (Request::isset("redirect")) ? Request::string("redirect") : "";

                if ((!App::get("APP_LOGIN") || !App::get("APP_MAINTENANCE")) && $redirect != "/admin")
                        throw new Exception(_("Login not possible at the moment."));

                Auth::set_current_account(
                    Request::string("credential"), 
                    Request::string("pw"),
                    Request::isset("stay") ? Request::boolean("stay") : false);

                if ($redirect != "")
                    Ajax::redirect(App::get("APP_URL").$redirect);
                else 
                    Ajax::redirect(App::get("APP_URL")."/account");
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

    /**
     * Handles the logout action for the user, logging them out and redirecting to the home page.
     */
    public function logoutAction() {
        switch(Request::string("request")) {
            case "account/logout":
                Auth::unset_cookie();
                Ajax::redirect(App::get("APP_URL")."/");
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

    /**
     * Handles the global logout action for the user, change the user's token and set a new cookie.
     */
    public function glogoutAction() {
        switch(Request::string("request")) {
            case "account/glogout":
                $this->account->set("token", Auth::get_instance_token());
                Auth::set_cookie($this->account->get("id"), Auth::get_instance_token(), 0);
                Ajax::add(".response", '<div class="success">'._("Sessions successfully logged out."), "success");
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }   
    }

    /**
     * Handles user registration/signup.
     *
     * @throws Exception If signup is not allowed or if there are issues with the registration data.
     */
    public function signupAction() {
        if (!App::get("APP_SIGNUP") || !App::get("APP_MAINTENANCE"))
            throw new Exception(_("Signup not possible at the moment."));

        switch(Request::string("request")) {
            case "account/signup":
                Auth::set_new_account(
                    Request::username(), 
                    Request::email(),
                    Request::password()
                );

                Ajax::redirect(App::get("APP_URL")."/account/verify");
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

    /**
     * Handles account recovery requests, including sending recovery emails and setting new passwords.
     *
     * @throws Exception If there are issues with the recovery process.
     */
    public function recoveryAction() {
        $credential = Request::string("credential");
        if (empty($account = Database::select("app_accounts", "email LIKE '".$credential."' OR username = '".$credential."'")))
            throw new Exception(_("There is no account with this username or email address."));

        $account = new Model\Account($account[0]["id"]);

        switch(Request::string("request")) {
            case "account/recovery/request":
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
            case "account/recovery/submit":
                Auth::verify_confirmcode($credential, str_replace(' ', '', Request::string("code")));
                if ($account->get("role") < Model\Account::DEACTIVATED)
                    throw new Exception(_("Your account cannot be restored."));
            
                $account->set("password", password_hash(Request::password(), PASSWORD_DEFAULT));
                $account->set("role", ($account->get("role") == Model\Account::DEACTIVATED) ? Model\Account::USER : $account->get("role"));
                $account->set("token", Auth::get_instance_token());

                Ajax::add('.main-content form', '<div class="success">'._("Your account has been successfully restored. You can now log in as usual."), "success");
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

}

?>