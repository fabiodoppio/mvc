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
use MVC\Database    as Database;
use MVC\Exception   as Exception;
use MVC\Models      as Model;
use MVC\Template    as Template;
use MVC\Validator   as Validator;

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
                "debug"             => App::get("APP_DEBUG"),
                "maintenance"       => App::get("APP_MAINTENANCE"),
                "cron"              => App::get("APP_CRON"),
                "login"             => App::get("APP_LOGIN"),
                "signup"            => App::get("APP_SIGNUP"),
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

        $this->env["page"] = (object) [
            "meta" => (object) [
                "title"         => sprintf(_(App::get("APP_TITLE") ?: "Homepage | %s"), App::get("APP_NAME")),
                "description"   => App::get("APP_DESCRIPTION"),
                "robots"        => "index, follow",
                "class"         => "page home"
            ]
        ];

        echo Template::get("/home.tpl", $this->env);
    }

    /**
     *
     *  Displaying the website's custom page.
     *
     *  @since  3.0     Get page data from database.
     *  @since  2.2     Added regex detection in slugs.
     *  @since  2.0
     *  @param  string  $request    The requested action.
     *
     */
    public function pageAction(string $request) {
        $pageFound = false;
        foreach(Database::query("SELECT id, slug FROM app_pages WHERE active = ?", [1]) as $page)
            if ($pageFound = preg_match('#^'.$page['slug'].'$#', $request)) {
                $page = new Model\Page($page["id"]);

                if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
                    if ($page->get("maintenance") == 1)
                        throw new Exception(_("App currently offline. Please try again later."), 406);

                if ($this->account->get("role") < $page->get("requirement") ?? 0)
                    throw new Exception(_("Your account does not have the required role."), 403);

                $this->env["page"] = (object) [
                    "meta" => (object) $page->get("meta")
                ];

                echo Template::get($page->get("template") ?? "", $this->env);
                break;
            }

        if (!$pageFound)
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
                    "meta" => (object) [
                        "title"         => sprintf(_("Log In | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "account login"
                    ]
                ];

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

                App::unset_auth_cookie();
                $this->env["account"]->role = (new Model\Guest())->get("role");

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Log Out | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "account logout"
                    ]
                ];

                echo Template::get("/logout.tpl", $this->env);

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
                    "meta" => (object) [
                        "title"         => sprintf(_("Goodbye | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "account goodbye"
                    ]
                ];

                echo Template::get("/goodbye.tpl", $this->env);

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
                    "meta" => (object) [
                        "title"         => sprintf(_("Sign Up | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "account signup"
                    ]
                ];

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

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Account Recovery | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "account recovery"
                    ]
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
             case "/account/personal":

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Personal Data | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "account personal"
                    ]
                ];

                echo Template:: get("/account/personal.tpl", $this->env);

                break;
            case "/account/security":

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Account & Security | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "account security"
                    ]
                ];

                echo Template:: get("/account/security.tpl", $this->env);

                break;
            case "/account/email":

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Email Settings | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "account email"
                    ]
                ];

                echo Template:: get("/account/email.tpl", $this->env);

                break;
            default:
                $this->pageAction($request);
        }
    }

    /**
     *
     *  Displaying the website's admin page.
     *
     *  @since  3.1     Added cron job page.
     *  @since  3.0
     *  @param  string  $request    The requested action.
     *
     */
    public function adminAction(string $request) {
        if ($this->account->get("role") < Model\Account::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."), 403);

        switch($request) {
            case "/admin":

                header("Location: ".App::get("APP_URL")."/admin/accounts");
                exit;

                break;
             case "/admin/accounts":

                $role   = Validator::string($_GET["role"] ?? "");
                $query  = (!empty($role)) ? "&role=".$role : "";
                $filter = (!empty($role)) ? " WHERE role = ? ORDER BY username" : " WHERE role >= ? ORDER BY id DESC";
                $params = (!empty($role)) ? [$role] : [Model\Account::USER];

                $result = Database::query("SELECT id FROM app_accounts".$filter, $params);

                if (!empty($search = Validator::string($_GET["search"]??""))) {
                    $query .= "&search=".$search;
                    foreach($result as $key => $value) {
                        $matchFound = false;
                        $account = new Model\Account($value["id"]);
                        foreach (["id", "username", "firstname", "lastname", "displayname", "email"] as $meta)
                            if (strcasecmp($account->get($meta), $search) == 0) {
                                $matchFound = true;
                                break;
                            }

                        if (!$matchFound)
                            $result[$key] = null;
                    }
                    $result = array_filter($result);
                }

                $count  = count($result);
                $pages  = ceil(count($result) / 20);
                $page   = Validator::integer($_GET["page"] ?? 1);
                $page   = ($page > $pages || $page < 1) ? 1 : $page;
                $result = array_slice($result, ($page - 1) * 20, 20);

                $this->env["var"] = (object) [
                    "count"  => $count,
                    "query"  => $query,
                    "result" => array_map(fn($entry) => new Model\Account($entry["id"]), $result),
                    "protected" => Model\Account::get_protected_names(),
                    "pagination" => (object) [
                        "page"  => $page,
                        "pages" => $pages
                    ]
                ];

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("All Accounts | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "admin account accounts"
                    ]
                ];

                echo Template:: get("/admin/accounts.tpl", $this->env);

                break;
            case "/admin/pages":

                $query  = "";
                $result = Database::query("SELECT id FROM app_pages ORDER BY slug");

                if (!empty($search = Validator::string($_GET["search"]??""))) {
                    $query .= "&search=".$search;
                    foreach($result as $key => $value) {
                        $matchFound = false;
                        $page = new Model\Page($value["id"]);
                        foreach (["id", "slug", "template", "title", "description"] as $meta)
                            if (strcasecmp($page->get($meta), $search) == 0) {
                                $matchFound = true;
                                break;
                            }

                        if (!$matchFound)
                            $result[$key] = null;
                    }
                    $result = array_filter($result);
                }

                $count  = count($result);
                $pages  = ceil(count($result) / 20);
                $page   = Validator::integer($_GET["page"] ?? 1);
                $page   = ($page > $pages || $page < 1) ? 1 : $page;
                $result = array_slice($result, ($page - 1) * 20, 20);

                $this->env["var"] = (object) [
                    "count"  => $count,
                    "query"  => $query,
                    "result" => array_map(fn($entry) => new Model\Page($entry["id"]), $result),
                    "protected" => Model\Page::get_protected_names(),
                    "pagination" => (object) [
                        "page"  => $page,
                        "pages" => $pages
                    ]
                ];

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Custom Pages | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "admin account pages"
                    ]
                ];

                echo Template:: get("/admin/pages.tpl", $this->env);

                break;
            case "/admin/filters":

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Filter Settings | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "admin account filters"
                    ]
                ];

                echo Template:: get("/admin/filters.tpl", $this->env);

                break;
            case "/admin/cronjobs":

                $result = Database::query("SELECT id FROM app_cronjobs ORDER BY name");
                $count  = count($result);
                $pages  = ceil(count($result) / 20);
                $page   = Validator::integer($_GET["page"] ?? 1);
                $page   = ($page > $pages || $page < 1) ? 1 : $page;
                $result = array_slice($result, ($page - 1) * 20, 20);

                $this->env["var"] = (object) [
                    "count"  => $count,
                    "result" => array_map(fn($entry) => new Model\Cronjob($entry["id"]), $result),
                    "pagination" => (object) [
                        "page"  => $page,
                        "pages" => $pages
                    ]
                ];

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Scheduled Tasks | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "admin account cronjobs"
                    ]
                ];

                echo Template:: get("/admin/cronjobs.tpl", $this->env);

                break;
            case "/admin/newsletter":

                $accounts = array_column(Database::query("SELECT id FROM app_accounts WHERE role >= ?", [Model\Account::USER]), 'id');

                $this->env["var"] = (object) [
                    "accounts" => implode(',', $accounts),
                    "counter"  => count($accounts)
                ];

                $this->env["page"] = (object) [
                    "meta" => (object) [
                        "title"         => sprintf(_("Newsletter | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "admin account newsletter"
                    ]
                ];

                echo Template:: get("/admin/newsletter.tpl", $this->env);

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
                    "meta" => (object) [
                        "title"         => sprintf(_("Contact | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "page contact"
                    ]
                ];

                echo Template::get("/contact.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     *
     *  Displaying the website's cron page.
     *
     *  @since  3.1     Removed cron.tpl from views, added Cronjob class.
     *  @since  2.0
     *  @param  string  $request    The requested action.
     *
     */
    public function cronAction(string $request) {
        if (!App::get("APP_CRON"))
            throw new Exception(_("Page not found."), 404);

        switch($request) {
            case "/cron":

                foreach(Database::query("SELECT id FROM app_cronjobs WHERE active = 1") as $cron) {
                    $cron = new Model\Cronjob($cron["id"]);
                    if ($cron->should_run())
                            $cron->exec();
                }

                echo _("Cron jobs executed.");

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
                    "meta" => (object) [
                        "title"         => sprintf(_("Maintenance | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "page maintenance"
                    ]
                ];

                echo Template::get("/maintenance.tpl", $this->env);

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
                    "meta" => (object) [
                        "title"         => sprintf(_("oops! | %s"), App::get("APP_NAME")),
                        "description"   => App::get("APP_DESCRIPTION"),
                        "robots"        => "noindex, nofollow",
                        "class"         => "page oops"
                    ]
                ];

                http_response_code(404);
                echo Template::get("/oops.tpl", $this->env);

                break;
            default:
                throw new Exception(_("Page not found."), 404);
        }
    }

    /**
     *
     *  Executes actions after the main action.
     *
     *  @since  2.3
     *
     */
    public function afterAction() {
        return;
    }

}

?>