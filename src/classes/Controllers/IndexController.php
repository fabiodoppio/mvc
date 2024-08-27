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
use MVC\Cache       as Cache;
use MVC\Exception   as Exception;
use MVC\Models      as Model;

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
     *  @since  2.3     Removed asset files in env variable for performance reasons.
     *  @since  2.0
     * 
     */
    public function beforeAction() {
        parent::beforeAction();

        $this->env = [
            "framework"     => (object) [
                "name"          => App::get("SRC_NAME"),
                "package"       => App::get("SRC_PACKAGE"),
                "url"           => App::get("SRC_URL"),
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
                "languages"         => App::get("APP_LANGUAGES"),
                "cron"              => App::get("APP_CRON"),
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
            "instance" => (object) [
                "token"         => App::get_instance_token()
            ],
            "request" => (object) [
                "get"           => (object) $_GET,
                "post"          => (object) $_POST,
                "uri"           => App::get_request_in_parts()[2]
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

                $this->env["page"] = (object) [
                    "title"         => sprintf(_(App::get("APP_TITLE") ?: "Homepage | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "index, follow",
                    "canonical"     => App::get("APP_URL")."/",
                    "class"         => "page home"
                ];

                echo Cache::get("/home.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's custom page.
     *
     *  @since  2.3     Option for ignoring maintenance mode.
     *  @since  2.2     Added regex detection in slugs.
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function pageAction(string $request) {
        $page_found = false;
        foreach(App::get("APP_PAGES") as $page)
            if ($page_found = preg_match('#^'.$page['slug'].'$#', $request)) {
                $this->env["page"] = (object) [
                    "title"         => sprintf(_(($page["title"] ?? $page["slug"])." | %s"), App::get("APP_NAME")),
                    "description"   => $page["description"] ?? App::get("APP_DESCRIPTION"),
                    "robots"        => $page["robots"] ?? "index, follow",
                    "canonical"     => $page["canonical"] ?? App::get("APP_URL").$page["slug"],
                    "class"         => $page["class"] ?? "page"
                ];

                if (!isset($page["ignore_maintenance"]) || $page["ignore_maintenance"] === false)
                    if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
                        throw new Exception(_("App currently offline. Please try again later."), 406);

                echo Cache::get($page["template"] ?? "", $this->env);
                break;
            }

        if (!$page_found)
            throw new Exception(_("Page not found."), 404);
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
        if (!App::get("APP_LOGIN"))
            throw new Exception(_("Page not found."), 404);

        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch($request) {
            case "/login":

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Log In | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/login",
                    "class"         => "account login"
                ];

                echo Cache::get("/login.tpl", $this->env);

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
    
                App::unset_auth_cookie();
                $this->env["account"]->role = (new Model\Guest())->get("role");

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Log Out | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/logout",
                    "class"         => "account logout"
                ];

                echo Cache::get("/logout.tpl", $this->env);
    
                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's goodbye page.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function goodbyeAction(string $request) {
        if ($this->account->get("role") != Model\Account::DEACTIVATED)
            throw new Exception(_("Page not found."), 404);

        switch($request) {
            case "/goodbye":
    
                App::unset_auth_cookie();
                $this->env["account"]->role = (new Model\Guest())->get("role");

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Goodbye | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/goodbye",
                    "class"         => "account goodbye"
                ];

                echo Cache::get("/goodbye.tpl", $this->env);
    
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

        if (App::get("APP_MAINTENANCE"))
            throw new Exception(_("App currently offline. Please try again later."), 406);

        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch($request) {
            case "/signup":

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Sign Up | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/signup",
                    "class"         => "account signup"
                ];
        
                echo Cache::get("/signup.tpl", $this->env);
    
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

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Account Recovery | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/recovery",
                    "class"         => "account recovery"
                    
                ];

                echo Cache::get("/recovery.tpl", $this->env);
    
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
             case "/account/personal":

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Personal Data | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/account/personal",
                    "class"         => "account personal"
                ];

                echo Cache:: get("/account/personal.tpl", $this->env);

                break;
            case "/account/security":

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Account & Security | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/account/security",
                    "class"         => "account security"
                ];

                echo Cache:: get("/account/security.tpl", $this->env);

                break;
            case "/account/email":

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Email Settings | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/account/email",
                    "class"         => "account email"
                ];

                echo Cache:: get("/account/email.tpl", $this->env);

                break;
            default:
                $this->pageAction($request);
        }
    }

    /**
     * 
     *  Displaying the website's contact page.
     *
     *  @since  2.3     Ignoring maintenance mode if active.
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function contactAction(string $request) {
        switch($request) {
            case "/contact":

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Contact | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/contact",
                    "class"         => "page contact"
                ];

                echo Cache::get("/contact.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
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
        if (!App::get("APP_CRON"))
            throw new Exception(_("Page not found."), 404);

        switch($request) {
            case "/cron":
    
                echo Cache::get("/cron.tpl", $this->env);

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
        if (!App::get("APP_MAINTENANCE"))
            throw new Exception(_("Page not found."), 404);

        switch($request) {
            case "/maintenance":

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("Maintenance | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/maintenance",
                    "class"         => "page maintenance"
                ];

                echo Cache::get("/maintenance.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     * 
     *  Displaying the website's error page.
     *  @since  2.3     Added http response code 404.
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function oopsAction(string $request) {
        switch($request) {
            case "/oops":

                $this->env["page"] = (object) [
                    "title"         => sprintf(_("oops! | %s"), App::get("APP_NAME")),
                    "description"   => App::get("APP_DESCRIPTION"),
                    "robots"        => "noindex, nofollow",
                    "canonical"     => App::get("APP_URL")."/oops",
                    "class"         => "page oops"
                ];

                http_response_code(404);
                echo Cache::get("/oops.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

}

?>