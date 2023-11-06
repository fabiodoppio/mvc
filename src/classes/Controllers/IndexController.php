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
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 407);

        switch(Request::string("request")) {
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
                $this->customAction();
        }
    }

    /**
     * Displaying the website's login page.
     */
    public function loginAction() {
        $redirect = (Request::isset("redirect")) ? urldecode(Request::string("redirect")) : "";

        if (!App::get("APP_LOGIN") && ($redirect != "/admin"))
            throw new Exception(_("Login not possible at the moment."), 404);

        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch(Request::string("request")) {
            case "/login":
                echo Template::get(
                    "login.tpl", [
                        "title" => sprintf(_("Login | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/login",
                        "account" => $this->account,
                        "redirect" => $redirect
                ]);
                break;
            default:
                $this->customAction();
        }
    }

    /**
     * Displaying the website's logout page.
     */
    public function logoutAction() {
        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Page not found."), 404);

        switch(Request::string("request")) {
            case "/logout":
                Auth::unset_cookie();
                $this->account = Auth::get_current_account();
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
                $this->customAction();
        }
    }

    /**
     * Displaying the website's signup page.
     */
    public function signupAction() {
        if (!App::get("APP_SIGNUP"))
            throw new Exception(_("Signup not possible at the moment."), 404);

        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch(Request::string("request")) {
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
                $this->customAction();
        }
    }

    /**
     * Displaying the website's recovery page (lost password).
     */
    public function recoveryAction() {
        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch(Request::string("request")) {
            case "/recovery":
                $base = (Request::isset("code")) ? base64_decode(Request::string("code")) : "";
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
                $this->customAction();
        }
    }

    /**
     * Displaying the website's account page.
     */
    public function accountAction() {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 407);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 403);
        
        switch(Request::string("request")) {
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
                if ($this->account->get("role") > Model\Account::USER)
                    throw new Exception(_("Your account does not have the required role."), 405);

                $base = (Request::isset("code")) ? base64_decode(Request::string("code")) : "";
                $parts = explode('/',$base);
                $email = $parts[0]??"";
                $code = $parts[1]??"";

                $redirect = (Request::isset("redirect")) ? urldecode(Request::string("redirect")) : "";

                echo Template::get(
                    "account/verify.tpl", [
                        "title" => sprintf(_("Email address verification | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/account/verify",
                        "email" => $email,
                        "code" => $code,
                        "redirect" => $redirect,
                        "account" => $this->account
                ]);
                break;
            default:
                $this->customAction();
        }
    }

    /**
     * Displaying the website's admin page.
     */
    public function adminAction() {
        if ($this->account->get("role") < Model\Account::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."), 403);
        
        switch(Request::string("request")) {
            case "/admin":
                echo Template::get(
                        "admin/admin.tpl", [
                            "title" => sprintf(_("Administration | %s"), App::get("APP_NAME")),
                            "description" => App::get("APP_DESCRIPTION"),
                            "robots" => "noindex, nofollow",
                            "canonical" => App::get("APP_URL")."/admin",
                            "account" => $this->account
                ]);
                break;
            case "/admin/settings":
                echo Template::get(
                        "admin/settings.tpl", [
                            "title" => sprintf(_("Settings | %s"), App::get("APP_NAME")),
                            "description" => App::get("APP_DESCRIPTION"),
                            "robots" => "noindex, nofollow",
                            "canonical" => App::get("APP_URL")."/admin/settings",
                            "account" => $this->account
                ]);
                break;
            case "/admin/pages":
                $items = array();
                foreach (Database::select("app_pages", "id IS NOT NULL") as $page)
                    $items[] = new Model\Page($page['id']);

                $pages = ceil(count($items)/20);
                $items = array_slice($items, 0, 20);
        
                echo Template::get(
                        "admin/pages.tpl", [
                            "title" => sprintf(_("Pages | %s"), App::get("APP_NAME")),
                            "description" => App::get("APP_DESCRIPTION"),
                            "robots" => "noindex, nofollow",
                            "canonical" => App::get("APP_URL")."/admin/pages",
                            "items" => $items,
                            "page" => 1,
                            "pages" => $pages,
                            "account" => $this->account
                ]);
                break;
            case "/admin/accounts":
                $items = array();
                foreach (Database::select("app_accounts", "id IS NOT NULL") as $account)
                    $items[] = new Model\Account($account['id']);

                $pages = ceil(count($items)/20);
                $items = array_slice($items, 0, 20);

                echo Template::get(
                        "admin/accounts.tpl", [
                            "title" => sprintf(_("Accounts | %s"), App::get("APP_NAME")),
                            "description" => App::get("APP_DESCRIPTION"),
                            "robots" => "noindex, nofollow",
                            "canonical" => App::get("APP_URL")."/admin/accounts",
                            "items" => $items,
                            "page" => 1,
                            "pages" => $pages,
                            "account" => $this->account
                ]);
                break;
            default:
                $this->customAction();
        }
    }

    /**
     * Displaying the website's error page.
     */
    public function oopsAction() {
        switch(Request::string("request")) {
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
                $this->customAction();
        }
    }

    /**
     * Displaying the website's maintenance page.
     */
    public function maintenanceAction() {
        if (!App::get("APP_MAINTENANCE"))
            throw new Exception(_("Page not found."), 404);
            
        switch(Request::string("request")) {
            case "/maintenance":
                echo Template::get(
                    "maintenance.tpl", [
                        "title" => sprintf(_("Maintenance | %s"), App::get("APP_NAME")),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/maintenance",
                        "account" => $this->account
                ]);
                break;
            default:
                $this->customAction();
        }
    }

    /**
     * Displaying the website's maintenance page.
     */
    public function cronAction() {
        if (!App::get("APP_CRONJOB"))
            throw new Exception(_("Page not found."), 404);

        switch(Request::string("request")) {
            case "/cron":
                if (!Request::isset("key"))
                    throw new Exception("Key not found.");

                if (App::get("AUTH_CRON") != Request::string("key"))
                    throw new Exception("Key does not match.");

                echo Template::get("cron.tpl");
                break;
            default:
                $this->customAction();
        }
    }

    /**
     * Displaying the website's custom page from database.
     */
    public function customAction() {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 407);
        
        if (empty($page = Database::select("app_pages", "slug = '".Request::string("request")."'")))
            throw new Exception(_("Page not found."), 404);

        $page = new Model\Page($page[0]["id"]);

        if ($this->account->get("role") < Model\Account::VERIFIED && $page->get("role") == Model\Account::VERIFIED)
            throw new Exception(_("Your account does not have the required role."), 406); 

        if ($this->account->get("role") < $page->get("role"))
            throw new Exception(_("Your account does not have the required role."), 403);

        echo Template::get(
            $page->get("template"), [
                "title" => sprintf(_($page->get("title")." | %s"), App::get("APP_NAME")),
                "description" => $page->get("description") ?: App::get("APP_DESCRIPTION"),
                "robots" => $page->get("robots"),
                "canonical" => App::get("APP_URL").$page->get("slug"),
                "id" => $page->get("id"),
                "account" => $this->account,
                
        ]);
    }

}

?>