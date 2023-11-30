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

use MVC\App       as App;
use MVC\Auth      as Auth;
use MVC\Database  as Database;
use MVC\Exception as Exception;
use MVC\Fairplay  as Fairplay;
use MVC\Models    as Model;
use MVC\Template  as Template;

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
            "framework" => [
                "name"          => App::get("SRC_NAME"),
                "package"       => App::get("SRC_PACKAGE"),
                "description"   => App::get("SRC_DESCRIPTION"),
                "author"        => App::get("SRC_AUTHOR"),
                "version"       => App::get("SRC_VERSION")
            ],
            "app" => [
                "url"           => App::get("APP_URL"),
                "name"          => App::get("APP_NAME"),
                "title"         => App::get("APP_TITLE"),
                "author"        => App::get("APP_AUTHOR"),
                "description"   => App::get("APP_DESCRIPTION"),
                "language"      => App::get("APP_LANGUAGE"),
                "theme"         => App::get("APP_THEME"),
                "debug"         => App::get("APP_DEBUG"),
                "login"         => App::get("APP_LOGIN"),
                "signup"        => App::get("APP_SIGNUP"),
                "maintenance"   => App::get("APP_MAINTENANCE"),
                "metafields"    => array_filter(json_decode(App::get("APP_METAFIELDS"))),
                "badwords"      => array_filter(json_decode(App::get("APP_BADWORDS"))),
                "properties"    => App::get_properties()
            ],
            "upload" => [
                "filetypes"     => array_filter(json_decode(App::get("UPLOAD_FILE_TYPES"))),
                "filesize"      => App::get("UPLOAD_FILE_SIZE")
            ],
            "cron" => [
                "active"        => App::get("CRON_ACTIVE"),
                "url"           => App::get("APP_URL")."/cron?key=".str_replace('=', '', base64_encode(App::get('CRON_AUTH')))
            ],
            "mail" => [
                "host"          => App::get("MAIL_HOST"),
                "username"      => App::get("MAIL_USERNAME"),
                "sender"        => App::get("MAIL_SENDER")
            ],
            "directory" => [
                "assets"        => App::get("DIR_ASSETS"),
                "fonts"         => App::get("DIR_FONTS"),
                "scripts"       => App::get("DIR_SCRIPTS"), 
                "styles"        => App::get("DIR_STYLES"),
                "vendor"        => App::get("DIR_VENDOR"),
                "media"         => App::get("DIR_MEDIA"),
                "uploads"       => App::get("DIR_UPLOADS")
            ],
            "account" => [
                "id"            => $this->account->get("id"),
                "username"      => $this->account->get("username"),
                "email"         => $this->account->get("email"),
                "role"          => $this->account->get("role"),
                "registered"    => $this->account->get("registered"),
                "lastaction"    => $this->account->get("lastaction"),
                "displayname"   => $this->account->get("username"),
                "metafields"    => $this->account->get_meta()
            ],
            "client" => [
                "id"            => Auth::get_client_token()
            ],
            "custom" => [
                "css"           => array_filter(json_decode(App::get("CUSTOM_CSS"))),
                "js"            => array_filter(json_decode(App::get("CUSTOM_JS")))
            ]
        ];

        foreach (App::get_properties() as $name => $value)
            $this->env["app"][$name] = $value;

        foreach ($this->account->get_meta() as $name => $value)
            $this->env["account"][$name] = $value;
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
            throw new Exception(_("App currently offline. Please try again later."), 407);

        switch($request) {
            case "/":

                echo Template::get(
                    "home.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/",
                            "title"         => App::get("APP_TITLE") ?: sprintf(_("Homepage")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "index, follow",
                            "canonical"     => App::get("APP_URL")
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($request);
        }
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
        $redirect = (!empty($_GET["redirect"])) ? urldecode(Fairplay::string($_GET["redirect"])) : "";

        if (!App::get("APP_LOGIN") && ($redirect != "/admin"))
            throw new Exception(_("Log in not possible at the moment."), 404);

        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch($request) {
            case "/login":

                echo Template::get(
                    "login.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/login",
                            "title"         => sprintf(_("Log In")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/login"
                        ],
                        "request"           => (object) ["redirect" => $redirect],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($request);
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
                echo Template::get(
                    "logout.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/logout",
                            "title"         => sprintf(_("Log Out")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/logout"
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($request);
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
        $redirect = (!empty($_GET["redirect"])) ? urldecode(Fairplay::string($_GET["redirect"])) : "";

        if (!App::get("APP_SIGNUP"))
            throw new Exception(_("Sign up not possible at the moment."), 404);

        if ($this->account->get("role") > Model\Account::GUEST)
            throw new Exception(_("Your account does not have the required role."), 405);

        switch($request) {
            case "/signup":

                echo Template::get(
                    "signup.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/signup",
                            "title"         => sprintf(_("Sign Up")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/signup"
                        ],
                        "request"           => (object) ["redirect" => $redirect],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($redirect);
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
                $parts = explode('/',$base);
                $credential = $parts[0]??"";
                $code = $parts[1]??"";

                echo Template::get(
                    "recovery.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/recovery",
                            "title"         => sprintf(_("Account recovery")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/recovery"
                        ],
                        "request"           => (object) ["credential" => $credential, "code" => $code],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($request);
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
            throw new Exception(_("App currently offline. Please try again later."), 407);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 403);
        
        switch($request) {
            case "/account":
    
                header("Location: ".App::get("APP_URL")."/account/dashboard");
                exit();

                break;
            case "/account/dashboard":

                echo Template::get(
                    "account/dashboard.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/account/dashboard",
                            "title"         => sprintf(_("My Account")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/account/dashboard"
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            case "/account/personal":

                echo Template::get(
                    "account/personal.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/account/personal",
                            "title"         => sprintf(_("Personal Information")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/account/personal"
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            case "/account/security":

                echo Template::get(
                    "account/security.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/account/security",
                            "title"         => sprintf(_("Password & Security")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/account/security"
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            case "/account/verify":

                $redirect = (!empty($_GET["redirect"])) ? urldecode(Fairplay::string($_GET["redirect"])) : "";

                if ($this->account->get("role") > Model\Account::USER)
                    throw new Exception(_("Your account does not have the required role."), 405);

                $base = (!empty($_GET["code"])) ? base64_decode(Fairplay::string($_GET["code"])) : "";
                $parts = explode('/',$base);
                $email = $parts[0]??"";
                $code = $parts[1]??"";

                echo Template::get(
                    "account/verify.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/account/verify",
                            "title"         => sprintf(_("Email address verification")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/account/verify"
                        ],
                        "request"           => (object) ["redirect" => $redirect, "email" => $email, "code" => $code],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($request);
        }
    }

    /**
     * 
     *  Displaying the website's page for administrators.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function adminAction(string $request) {
        if ($this->account->get("role") < Model\Account::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."), 403);
        
        switch($request) {
            case "/admin":

                header("Location: ".App::get("APP_URL")."/admin/dashboard");
                exit();

                break;
            case "/admin/dashboard":

                echo Template::get(
                    "admin/dashboard.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/admin/dashboard",
                            "title"         => sprintf(_("Administration")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/admin/dashboard"
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "upload"            => (object) $this->env["upload"],
                        "directory"         => (object) $this->env["directory"],
                        "cron"              => (object) $this->env["cron"],
                        "mail"              => (object) $this->env["mail"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            case "/admin/settings":

                echo Template::get(
                    "admin/settings.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/admin/settings",
                            "title"         => sprintf(_("App Settings")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/admin/settings"
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "upload"            => (object) $this->env["upload"],
                        "directory"         => (object) $this->env["directory"],
                        "cron"              => (object) $this->env["cron"],
                        "mail"              => (object) $this->env["mail"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            case "/admin/pages":

                $items = array();
                foreach (Database::query("SELECT * FROM app_pages") as $page) {
                    $page = new Model\Page($page["id"]);
                    $env = [
                        "page" => [
                            "id"            => $page->get("id"),
                            "slug"          => $page->get("slug"),
                            "title"         => $page->get("title"),
                            "description"   => $page->get("description"),
                            "robots"        => $page->get("robots"),
                            "canonical"     => $page->get("canonical"),
                            "template"      => $page->get("template"),
                            "role"          => $page->get("role"),
                            "metafields"    => $page->get_meta()
                        ]
                    ];

                    foreach ($page->get_meta() as $name => $value)
                        $env["page"][$name] = $value;

                    $items[] = (object) $env["page"];
                }

                $pages = ceil(count($items)/20);
                $items = array_slice($items, 0, 20);
        
                echo Template::get(
                    "admin/pages.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/admin/pages",
                            "title"         => sprintf(_("Custom Pages")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/admin/pages",
                            "pagination"    => (object) ["page" => 1, "pages" => $pages]
                        ],
                        "pages"             => $items,
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "upload"            => (object) $this->env["upload"],
                        "directory"         => (object) $this->env["directory"],
                        "cron"              => (object) $this->env["cron"],
                        "mail"              => (object) $this->env["mail"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            case "/admin/accounts":

                $items = array();
                foreach (Database::query("SELECT * FROM app_accounts") as $account) {
                    $account = new Model\Account($account["id"]);
                    $env = [
                        "account" => [
                            "id"            => $account->get("id"),
                            "username"      => $account->get("username"),
                            "email"         => $account->get("email"),
                            "role"          => $account->get("role"),
                            "registered"    => $account->get("registered"),
                            "lastaction"    => $account->get("lastaction"),
                            "metafields"    => $account->get_meta(),
                            "displayname"   => $account->get("displayname"),
                            "avatar"        => $account->get("avatar"),
                        ]
                    ];

                    foreach ($account->get_meta() as $name => $value)
                        $env["account"][$name] = $value;

                    $items[] = (object) $env["account"];
                }

                $pages = ceil(count($items)/20);
                $items = array_slice($items, 0, 20);

                echo Template::get(
                    "admin/accounts.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/admin/accounts",
                            "title"         => sprintf(_("Accounts")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/admin/accounts",
                            "pagination"    => (object) ["page" => 1, "pages" => $pages]
                        ],
                        "accounts"          => $items,
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "upload"            => (object) $this->env["upload"],
                        "directory"         => (object) $this->env["directory"],
                        "cron"              => (object) $this->env["cron"],
                        "mail"              => (object) $this->env["mail"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($request);
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

                echo Template::get(
                    "oops.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/oops",
                            "title"         => sprintf(_("oops!")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/oops"
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($request);
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

                echo Template::get(
                    "maintenance.tpl", [
                        "page" => (object) [
                            "id"            => 0,
                            "slug"          => "/maintenance",
                            "title"         => sprintf(_("Maintenance")." | %s", App::get("APP_NAME")),
                            "description"   => App::get("APP_DESCRIPTION"),
                            "robots"        => "noindex, nofollow",
                            "canonical"     => App::get("APP_URL")."/maintenance"
                        ],
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "account"           => (object) $this->env["account"],
                        "client"            => (object) $this->env["client"],
                        "custom"            => (object) $this->env["custom"]
                ]);

                break;
            default:
                $this->customAction($request);
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
                    throw new Exception(_("Key not found."), 1081);

                if (App::get("CRON_AUTH") != base64_decode(Fairplay::string($_GET["key"])))
                    throw new Exception(_("Key does not match."), 1082);

                echo Template::get(
                    "cron.tpl", [
                        "framework"         => (object) $this->env["framework"],
                        "app"               => (object) $this->env["app"],
                        "directory"         => (object) $this->env["directory"],
                        "mail"              => (object) $this->env["mail"]
                ]);

                break;
            default:
                $this->customAction($request);
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
    public function customAction(string $request) {
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 407);
        
        if (empty($page = Database::query("SELECT * FROM app_pages WHERE slug = ?", [$request])))
            throw new Exception(_("Page not found."), 404);

        $page = new Model\Page($page[0]["id"]);

        if ($this->account->get("role") < Model\Account::VERIFIED && $page->get("role") == Model\Account::VERIFIED)
            throw new Exception(_("Your account does not have the required role."), 406); 

        if ($this->account->get("role") < $page->get("role"))
            throw new Exception(_("Your account does not have the required role."), 403);

        $env = [
            "page" => [
                "id"            => $page->get("id"),
                "slug"          => $page->get("slug"),
                "title"         => sprintf(_($page->get("title"))." | %s", App::get("APP_NAME")),
                "description"   => $page->get("description") ?: App::get("APP_DESCRIPTION"),
                "robots"        => $page->get("robots") ?: "index, follow",
                "canonical"     => App::get("APP_URL").$page->get("slug"),
                "metafields"    => $page->get_meta()
            ]
        ];

        foreach ($page->get_meta() as $name => $value)
            $env["page"][$name] = $value;

        echo Template::get(
            $page->get("template"), [
                "page"              => (object) $env["page"],
                "framework"         => (object) $this->env["framework"],
                "app"               => (object) $this->env["app"],
                "directory"         => (object) $this->env["directory"],
                "cron"              => (object) $this->env["cron"],
                "mail"              => (object) $this->env["mail"],
                "account"           => (object) $this->env["account"],
                "client"            => (object) $this->env["client"],
                "custom"            => (object) $this->env["custom"]
        ]);
    }

}

?>