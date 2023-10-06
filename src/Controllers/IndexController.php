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

use MVC\App       as App;
use MVC\Auth      as Auth;
use MVC\Database  as Database;
use MVC\Exception as Exception;
use MVC\Fairplay  as Fairplay;
use MVC\Models    as Model;
use MVC\Request   as Request;
use MVC\Template  as Template;

/**
 * IndexController Class
 *
 * This controller class handles actions related to the website's index, including the home-, login-, logout-, signup-,
 * recovery-, account- and error- page.
 */
class IndexController extends Controller {

    /**
     * Displaying the website's home page.
     */
    public function homeAction() {
        echo Template::get(
            "home.tpl", [
                "title" => sprintf(_("Homepage | %s"), App::get("APP_NAME")),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "index, follow",
                "canonical" => App::get("APP_URL"),
                "client" => Auth::get_client_token()
        ]);
    }

    /**
     * Displaying the website's login page.
     */
    public function loginAction() {
        if (!App::get("APP_LOGIN"))
            throw new Exception(_("Signup not possible at the moment."), 404);

        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        echo Template::get(
            "login.tpl", [
                "title" => sprintf(_("Login | %s"), App::get("APP_NAME")),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => App::get("APP_URL")."/login",
                "client" => Auth::get_client_token()
        ]);
    }

    /**
     * Displaying the website's logout page.
     */
    public function logoutAction() {
        Auth::unset_cookie();
        header("Location: ".App::get("APP_URL")."/");
        exit();
    }

    /**
     * Displaying the website's signup page.
     */
    public function signupAction() {
        if (!App::get("APP_SIGNUP"))
            throw new Exception(_("Signup not possible at the moment."), 404);

        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        echo Template::get(
            "signup.tpl", [
                "title" => sprintf(_("Signup | %s"), App::get("APP_NAME")),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => App::get("APP_URL")."/signup",
                "client" => Auth::get_client_token()
        ]);
    }

    /**
     * Displaying the website's recovery page (lost password).
     */
    public function recoveryAction() {
        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        $base = (Request::isset("code")) ? base64_decode(Fairplay::string(Request::get("code"))) : "";
        $parts = explode('/',$base);
        $credential = $parts[0]??"";
        $code = $parts[1]??"";

        echo Template::get(
            "recovery.tpl", [
                "title" => sprintf(_("Account recovery | %s"), App::get("APP_NAME")),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => App::get("APP_URL")."/recovery",
                "client" => Auth::get_client_token(),
                "credential" => $credential,
                "code" => $code
        ]);
    }

    /**
     * Displaying the website's account page.
     */
    public function accountAction() {
        if ($this->account->get("role") < Model\Role::USER)
            throw new Exception(_("Your account does not have the required role."), 403);
        
        switch(Request::get("requestParts")[2]??"") {
            case "":
                echo Template::get(
                    "account.tpl", [
                        "title" => sprintf(_("My Account | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/account",
                        "client" => Auth::get_client_token()
                ]);
            break;
            case "verify":
                if ($this->account->get("role") > Model\Role::USER)
                    throw new Exception("", 405);

                $base = (Request::isset("code")) ? base64_decode(Fairplay::string(Request::get("code"))) : "";
                $parts = explode('/',$base);
                $email = $parts[0]??"";
                $code = $parts[1]??"";

                echo Template::get(
                    "verify.tpl", [
                        "title" => sprintf(_("Email address verification | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/verify",
                        "client" => Auth::get_client_token(),
                        "email" => $email,
                        "code" => $code
                ]);
            break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * Displaying the website's admin page.
     */
    public function adminAction() {
        if ($this->account->get("role") < Model\Role::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."), 403);

        $accounts = array();
        foreach (Database::select("app_accounts", "id > 0") as $user)
            $accounts[] = new Model\Account($user['id']);

        echo Template::get(
                "admin.tpl", [
                    "title" => sprintf(_("Backend | %s"), App::get("APP_NAME")),
                    "description" => App::get("APP_DESCRIPTION"),
                    "robots" => "noindex, nofollow",
                    "canonical" => App::get("APP_URL")."/admin",
                    "client" => Auth::get_client_token(),
                    "accounts" => $accounts
        ]);
    }

}

?>