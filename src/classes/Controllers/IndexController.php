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

use MVC\App         as App;
use MVC\Auth        as Auth;
use MVC\Exception   as Exception;
use MVC\Fairplay    as Fairplay;
use MVC\Models      as Model;
use MVC\Template    as Template;

/**
 * 
 *  IndexController Class
 *
 *  The IndexController displays the requested page, including the home, login, logout, 
 *  signup, recovery, account, admin, maintenance, cron, error and custom page
 * 
 */
class IndexController extends Controller {

    /**
     *  @var    array   The environment variables used for templates.
     */
    protected $env;


    /**
     * 
     *  Executes actions before the main action, including setting up environment variables.
     * 
     *  @since  2.0
     * 
     */
    public function beforeAction() {
        parent::beforeAction();

        $this->env = [
            "framework"     => (object) [
                "name"          => App::get("SRC_NAME"),
                "package"       => App::get("SRC_PACKAGE"),
                "description"   => App::get("SRC_DESCRIPTION"),
                "author"        => App::get("SRC_AUTHOR"),
                "version"       => App::get("SRC_VERSION")
            ],
            "app"           => (object) [
                "url"               => App::get("APP_URL"),
                "name"              => App::get("APP_NAME"),
                "title"             => App::get("APP_TITLE"),
                "author"            => App::get("APP_AUTHOR"),
                "description"       => App::get("APP_DESCRIPTION"),
                "language"          => App::get("APP_LANGUAGE"),
                "debug"             => App::get("APP_DEBUG"),
                "login"             => App::get("APP_LOGIN"),
                "signup"            => App::get("APP_SIGNUP"),
                "maintenance"       => App::get("APP_MAINTENANCE"),
                "directory"         => (object) [
                    "root"              => App::get("DIR_ROOT"),
                    "assets"            => App::get("DIR_ASSETS"),
                    "fonts"             => App::get("DIR_FONTS"),
                    "scripts"           => App::get("DIR_SCRIPTS"), 
                    "styles"            => App::get("DIR_STYLES"),
                    "vendor"            => App::get("DIR_VENDOR"),
                    "views"             => App::get("DIR_VIEWS"),
                    "media"             => App::get("DIR_MEDIA")
                ]
            ],
            "account" => (object) [
                "id"            => $this->account->get("id"),
                "username"      => $this->account->get("username"),
                "email"         => $this->account->get("email"),
                "role"          => $this->account->get("role"),
                "roles"         => (object) [
                    "blocked"           => Model\Account::BLOCKED,
                    "deactivated"       => Model\Account::DEACTIVATED,
                    "guest"             => Model\Account::GUEST,
                    "user"              => Model\Account::USER,
                    "verified"          => Model\Account::VERIFIED,
                    "supporter"         => Model\Account::SUPPORTER,
                    "moderator"         => Model\Account::MODERATOR,
                    "administrator"     => Model\Account::ADMINISTRATOR
                ],
                "registered"    => $this->account->get("registered"),
                "lastaction"    => $this->account->get("lastaction"),
                "meta"          => (object) $this->account->get("meta")
            ],
            "client" => (object) [
                "id"            => Auth::get_client_token()
            ],
            "request" => (object) [
                "get"           => (object) $_GET,
                "post"          => (object) $_POST
            ]
        ];
    }

    /**
     * 
     *  Displaying the website's home page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function homeAction(string $request) {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 406);
        
        switch($request) {
            case "/":

                echo Template::get("/home.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's custom page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function pageAction(string $request) {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 406);

        if (!in_array($request, App::get("APP_PAGES")))
            throw new Exception(_("Page not found."), 404);

        echo Template::get($request.".tpl", $this->env);
    }

    /**
     * 
     *  Displaying the website's login page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function loginAction(string $request) {
        if (!App::get("APP_LOGIN") && !str_contains($_GET["redirect"], "admin"))
            throw new Exception(_("Page not found."), 404);

        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch($request) {
            case "/login":

                echo Template::get("/login.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's logout page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function logoutAction(string $request) {
        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Page not found."), 404);

        switch($request) {
            case "/logout":
    
                Auth::unset_auth_cookie();
                $this->account = Auth::get_current_account();

                echo Template::get("/logout.tpl", $this->env);
    
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's signup page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function signupAction(string $request) {
        if (!App::get("APP_SIGNUP"))
            throw new Exception(_("Page not found."), 404);

        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch($request) {
            case "/signup":
        
                echo Template::get("/signup.tpl", $this->env);
    
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's recovery page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function recoveryAction(string $request) {
        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch($request) {
            case "/recovery":

                $base = (!empty($_GET["code"])) ? base64_decode(Fairplay::string($_GET["code"])) : "";

                $this->env["request"] = (object) [
                    "credential" => explode('/',$base)[0] ?? "",
                    "code" => explode('/',$base)[1] ?? ""
                ];

                echo Template::get("/recovery.tpl", $this->env);
    
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's account page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function accountAction(string $request) {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 406);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 403);
        
        switch($request) {
            case "/account":

                header("Location: ".App::get("APP_URL")."/account/personal");
                exit;
    
                break;
            case "/account/email":

                $base = (!empty($_GET["code"])) ? base64_decode(Fairplay::string($_GET["code"])) : "";

                $this->env["request"] = (object) [
                    "email" => explode('/',$base)[0] ?? "",
                    "code" => explode('/',$base)[1] ?? ""
                ];

            case "/account/personal":
            case "/account/security":
            case "/account/help":
    
                echo Template::get($request.".tpl", $this->env);

                break;
            default:
                $this->pageAction($request);
        }
    }

    /**
     * 
     *  Displaying the website's cron page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function cronAction(string $request) {
        if (!App::get("CRON_ACTIVE"))
            throw new Exception(_("Page not found."), 404);

        switch($request) {
            case "/cron":
    
                if (empty($_GET["key"]))
                    throw new Exception(_("Key not found."), 1071);

                if (App::get("CRON_AUTH") != base64_decode(Fairplay::string($_GET["key"])))
                    throw new Exception(_("Key does not match."), 1072);

                echo Template::get("/cron.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's maintenance page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function maintenanceAction(string $request) {
        switch($request) {
            case "/maintenance":

                echo Template::get("/maintenance.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's error page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function oopsAction(string $request) {
        switch($request) {
            case "/oops":

                echo Template::get("/oops.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

}

?>