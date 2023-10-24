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
        switch(Request::get("request")) {
            case "/":
                echo Template::get(
                    "home.tpl", [
                        "title" => App::get("APP_TITLE") ?: sprintf(_("Homepage | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "index, follow",
                        "canonical" => App::get("APP_URL"),
                        "account" => $this->account
                ]);
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * Displaying the website's login page.
     */
    public function loginAction() {
        if (!App::get("APP_LOGIN"))
            throw new Exception(_("Signup not possible at the moment."), 404);

        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch(Request::get("request")) {
            case "/login":
                echo Template::get(
                    "login.tpl", [
                        "title" => sprintf(_("Login | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/login",
                        "account" => $this->account
                ]);
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * Displaying the website's logout page.
     */
    public function logoutAction() {
        if ($this->account->get("role") < Model\Role::USER)
            throw new Exception(_("Your account does not have the required role."), 403);

        switch(Request::get("request")) {
            case "/logout":
                Auth::unset_cookie();
                echo Template::get(
                    "logout.tpl", [
                        "title" => sprintf(_("Logout | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/logout",
                        "account" => $this->account
                ]);
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * Displaying the website's signup page.
     */
    public function signupAction() {
        if (!App::get("APP_SIGNUP"))
            throw new Exception(_("Signup not possible at the moment."), 404);

        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch(Request::get("request")) {
            case "/signup":
                echo Template::get(
                    "signup.tpl", [
                        "title" => sprintf(_("Signup | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/signup",
                        "account" => $this->account
                ]);
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * Displaying the website's recovery page (lost password).
     */
    public function recoveryAction() {
        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch(Request::get("request")) {
            case "/recovery":
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
                        "credential" => $credential,
                        "code" => $code,
                        "account" => $this->account
                ]);
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * Displaying the website's account page.
     */
    public function accountAction() {
        if ($this->account->get("role") < Model\Role::USER)
            throw new Exception(_("Your account does not have the required role."), 403);
        
        switch(Request::get("request")) {
            case "/account":
                echo Template::get(
                    "account/account.tpl", [
                        "title" => sprintf(_("My Account | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/account",
                        "account" => $this->account
                ]);
                break;
            case "/account/verify":
                if ($this->account->get("role") > Model\Role::USER)
                    throw new Exception(_("Your account does not have the required role."), 405);

                $base = (Request::isset("code")) ? base64_decode(Fairplay::string(Request::get("code"))) : "";
                $parts = explode('/',$base);
                $email = $parts[0]??"";
                $code = $parts[1]??"";

                echo Template::get(
                    "account/verify.tpl", [
                        "title" => sprintf(_("Email address verification | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/account/verify",
                        "email" => $email,
                        "code" => $code,
                        "account" => $this->account
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
        
        switch(Request::get("request")) {
            case "/admin":
                echo Template::get(
                        "admin/admin.tpl", [
                            "title" => sprintf(_("Backend | %s"), App::get("APP_NAME")),
                            "description" => App::get("APP_DESCRIPTION"),
                            "robots" => "noindex, nofollow",
                            "canonical" => App::get("APP_URL")."/admin",
                            "account" => $this->account
                ]);
                break;
            case "/admin/pages":
                $pages = array();
                foreach (Database::select("app_pages", "slug IS NOT NULL") as $page)
                    $pages[] = new Model\Page($page['slug']);
        
                echo Template::get(
                        "admin/pages.tpl", [
                            "title" => sprintf(_("Pages | %s"), App::get("APP_NAME")),
                            "description" => App::get("APP_DESCRIPTION"),
                            "robots" => "noindex, nofollow",
                            "canonical" => App::get("APP_URL")."/admin/pages",
                            "pages" => $pages,
                            "account" => $this->account
                ]);
                break;
            case "/admin/users":
                $accounts = array();
                foreach (Database::select("app_accounts", "id IS NOT NULL") as $user)
                    $accounts[] = new Model\Account($user['id']);
        
                echo Template::get(
                        "admin/users.tpl", [
                            "title" => sprintf(_("Users | %s"), App::get("APP_NAME")),
                            "description" => App::get("APP_DESCRIPTION"),
                            "robots" => "noindex, nofollow",
                            "canonical" => App::get("APP_URL")."/admin/users",
                            "accounts" => $accounts,
                            "account" => $this->account
                ]);
                break;
            case "/admin/roles":
                    $roles = array();
                    foreach (Database::select("app_roles", "id IS NOT NULL") as $role)
                        $roles[] = new Model\Role($role['id']);
            
                    echo Template::get(
                            "admin/roles.tpl", [
                                "title" => sprintf(_("Roles | %s"), App::get("APP_NAME")),
                                "description" => App::get("APP_DESCRIPTION"),
                                "robots" => "noindex, nofollow",
                                "canonical" => App::get("APP_URL")."/admin/roles",
                                "roles" => $roles,
                                "account" => $this->account
                    ]);
                    break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * Displaying the website's error page.
     */
    public function oopsAction() {
        switch(Request::get("request")) {
            case "/oops":
                echo Template::get(
                    "oops.tpl", [
                        "title" => sprintf(_("oops! | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/oops",
                        "account" => $this->account
                ]);
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * Displaying the website's custom page from database.
     */
    public function customAction() {
        $page = new Model\Page(Request::get("request"));

        if ($this->account->get("role") < Model\Role::VERIFIED && $page->get("role") == Model\Role::VERIFIED)
            throw new Exception(_("Your account does not have the required role."), 406); 

        if ($this->account->get("role") < $page->get("role"))
            throw new Exception(_("Your account does not have the required role."), 403);

        echo Template::get(
            $page->get("template"), [
                "title" => sprintf(_($page->get("title")." | %s"), App::get("APP_NAME")),
                "description" => $page->get("description"),
                "robots" => $page->get("robots"),
                "canonical" => App::get("APP_URL").$page->get("slug"),
                "account" => $this->account
        ]);
    }

}

?>