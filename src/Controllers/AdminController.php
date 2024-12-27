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

use MVC\Ajax        as Ajax;
use MVC\App         as App;
use MVC\Exception   as Exception;
use MVC\Database    as Database;
use MVC\Mailer      as Mailer;
use MVC\Models      as Model;
use MVC\Template    as Template;
use MVC\Validator   as Validator;

/**
 * 
 *  AdminController Class
 *
 *  The AdminController class is a specific controller handling actions related to admin actions,
 *  such as editing, deleting or creating accounts and pages.
 * 
 */
class AdminController extends AccountController {

    /**
     * 
     *  Executes actions before the main action.
     * 
     *  @since  3.0
     *  
     */
    public function beforeAction() {
        parent::beforeAction();

        if ($this->account->get("role") < Model\Account::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."), 1065);
    }

    /**
     * 
     * Handles account editing-related actions, including updating account details, creating or deleting an account.
     *
     *  @since  3.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function accountAction(string $request) {
        switch($request) {
            case "/admin/account/create":

                if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["pw1"]) || empty($_POST["pw2"])) 
                    throw new Exception(_("Required input not found."), 1066);

                $email = Validator::email($_POST["email"]);
                $username = Validator::username($_POST["username"]);
                $password = Validator::password($_POST["pw1"], $_POST["pw2"]);
                
                if (!empty(Database::query("SELECT * FROM app_accounts WHERE username LIKE ?",[$username])[0]))
                    throw new Exception(_("This username is already taken."), 1067);
                
                if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [$email])[0]))
                    throw new Exception(_("This email address is already taken."), 1068);
            
                $token = App::generate_token();
                Database::query("INSERT INTO app_accounts (email, username, password, token, role) VALUES (?, ?, ?, ?, ?)", [strtolower($email), $username, password_hash($password, PASSWORD_DEFAULT), $token, Model\Account::USER]);

                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully created.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/account/edit":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1069);

                $account = new Model\Account(Validator::integer($_POST["id"]));
                $current_meta = $account->get("meta");

                foreach($_POST["custom"] ?? [] as $key => $value)
                    if (isset($_POST["custom"][$key]) && $_POST["custom"][$key] != $account->get($key))
                        $account->set(Validator::string($key), Validator::string($value));

                if (isset($_POST["displayname"]) && $_POST["displayname"] != $account->get("displayname"))
                    $account->set("displayname", Validator::string($_POST["displayname"]));

                if (isset($_POST["company"]) && $_POST["company"] != $account->get("company"))
                    $account->set("company", Validator::string($_POST["company"]));

                if (isset($_POST["firstname"]) && $_POST["firstname"] != $account->get("firstname"))
                    $account->set("firstname", Validator::string($_POST["firstname"]));

                if (isset($_POST["lastname"]) && $_POST["lastname"] != $account->get("lastname"))
                    $account->set("lastname", Validator::string($_POST["lastname"]));

                switch($account->get("displayname")) {
                    case $current_meta["company"]??"":
                        $account->set("displayname", $account->get("company"));
                        break;
                    case $current_meta["firstname"]??"": 
                        $account->set("displayname", $account->get("firstname"));
                        break;
                    case $current_meta["lastname"]??"": 
                        $account->set("displayname", $account->get("lastname"));
                        break;
                    case ($current_meta["firstname"]??"")." ".($current_meta["lastname"]??""): 
                        $account->set("displayname", $account->get("firstname")." ".$account->get("lastname"));
                        break;
                    case ($current_meta["lastname"]??"")." ".($current_meta["firstname"]??"");
                        $account->set("displayname", $account->get("lastname")." ".$account->get("firstname"));
                        break;
                }

                if (isset($_POST["street"]) && $_POST["street"] != $account->get("street"))
                    $account->set("street", Validator::string($_POST["street"]));

                if (isset($_POST["postal"]) && $_POST["postal"] != $account->get("postal"))
                    $account->set("postal", Validator::string($_POST["postal"]));

                if (isset($_POST["city"]) && $_POST["city"] != $account->get("city"))
                    $account->set("city", Validator::string($_POST["city"]));

                if (isset($_POST["country"]) && $_POST["country"] != $account->get("country"))
                    $account->set("country", Validator::string($_POST["country"]));

                if (!empty($_POST["email"]) && $_POST["email"] != $account->get("email")) {
                    $account->set("email", strtolower(Validator::email($_POST["email"])));
                    
                    Ajax::add('table.accounts tr[data-id="'.$_POST["id"].'"] td:nth-child(3)', '<a href="mailto:'.$account->get('email').'" title="E-Mail schreiben">'.$account->get('email').'</a>');
                }

                if (!empty($_POST["pw1"]) && !empty($_POST["pw2"])) {
                    $new_token = App::generate_token();
                    $account->set("password", password_hash(Validator::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT));
                    $account->set("token", $new_token);
                }

                Ajax::add('#response', '<div class="alert is--success">'._("Changes successfully saved.").'</div>');
                
                break;
            case "/admin/account/delete/avatar":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1070);

                $account = new Model\Account(Validator::integer($_POST["id"]));
                $account->set("avatar", null);

                Ajax::remove('table.accounts tr[data-id="'.$_POST["id"].'"] .avatar img');
                Ajax::add('#response', '<div class="alert is--success">'._("Avatar successfully deleted.").'</div>');

                break;
            case "/admin/account/verify":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1071);

                $account = new Model\Account(Validator::integer($_POST["id"]));
                $account->set("role", Model\Account::VERIFIED);

                Ajax::add('table.accounts tr[data-id="'.$_POST["id"].'"] td:nth-child(4)', $account->get_role_name());
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully verified.").'</div>');

                break;
            case "/admin/account/block":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1072);

                $account = new Model\Account(Validator::integer($_POST["id"]));
                $account->set("role", Model\Account::BLOCKED);

                Ajax::remove('table.accounts tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully blocked.").'</div>');

                break;
            case "/admin/account/restore":
    
                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1073);

                $account = new Model\Account(Validator::integer($_POST["id"]));
                $account->set("role", Model\Account::VERIFIED);

                Ajax::add('table.accounts tr[data-id="'.$_POST["id"].'"] td:nth-child(4)', $account->get_role_name());
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully restored.").'</div>');

                break;
            case "/admin/account/deactivate":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1074);

                $account = new Model\Account(Validator::integer($_POST["id"]));
                $account->set("role", Model\Account::DEACTIVATED);

                Ajax::add('table.accounts tr[data-id="'.$_POST["id"].'"] td:nth-child(4)', $account->get_role_name());
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully deactivated.").'</div>');

                break;
            case "/admin/account/delete":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1075);

                $account = new Model\Account(Validator::integer($_POST["id"]));

                if ($account->get("role") != Model\Account::DEACTIVATED)
                    throw new Exception(_("Account must be deactivated first."), 1076);

                $account->delete();

                Ajax::remove('table.accounts tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully deleted.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1077);
        }
    }

    /**
     * 
     * Handles page editing-related actions, including updating page details, creating or deleting a page.
     *
     *  @since  3.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function pageAction(string $request) {
        switch($request) {
            case "/admin/page/create":

                if (empty($_POST["slug"]) || empty($_POST["template"])) 
                    throw new Exception(_("Required input not found."), 1078);

                if (!empty(Database::query("SELECT * FROM app_pages WHERE slug LIKE ?",[$_POST["slug"]])[0]))
                    throw new Exception(_("This slug is already taken."), 1079);
            
                Database::query("INSERT INTO app_pages (slug, title, description, robots, canonical, class, template) VALUES (?, ?, ?, ?, ?, ?, ?)", [Validator::regex($_POST["slug"]), Validator::string($_POST["title"]), Validator::string($_POST["description"]), Validator::string($_POST["robots"]), Validator::string($_POST["canonical"]), Validator::string($_POST["class"]), Validator::string($_POST["template"])]);

                Ajax::add('#response', '<div class="alert is--success">'._("Page successfully created.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/page/edit":

                if (empty($_POST["id"]) || empty($_POST["slug"]) || empty($_POST["template"])) 
                    throw new Exception(_("Required input not found."), 1080);

                $page = new Model\Page(Validator::integer($_POST["id"]));

                if (!empty($_POST["slug"]) && $_POST["slug"] != $page->get("slug")) {
                    $page->set("slug", Validator::regex($_POST["slug"]));
                    Ajax::add('table.pages tr[data-id="'.$_POST["id"].'"] td:nth-child(4)', $_POST["slug"]);
                }

                if (isset($_POST["title"]) && $_POST["title"] != $page->get("title")) {
                    $page->set("title", Validator::string($_POST["title"]));
                    Ajax::add('table.pages tr[data-id="'.$_POST["id"].'"] td:nth-child(4)', $_POST["title"]);
                }

                if (isset($_POST["description"]) && $_POST["description"] != $page->get("description"))
                    $page->set("description", Validator::string($_POST["description"]));

                if (isset($_POST["robots"]) && $_POST["robots"] != $page->get("robots"))
                    $page->set("robots", Validator::string($_POST["robots"]));

                if (isset($_POST["canonical"]) && $_POST["canonical"] != $page->get("canonical"))
                    $page->set("canonical", Validator::string($_POST["canonical"]));

                if (isset($_POST["class"]) && $_POST["class"] != $page->get("class"))
                    $page->set("class", Validator::string($_POST["class"]));
                
                if (!empty($_POST["template"]) && $_POST["template"] != $page->get("template"))
                    $page->set("slug", Validator::string($_POST["template"]));

                Ajax::add('#response', '<div class="alert is--success">'._("Changes successfully saved.").'</div>');
                
                break;
            case "/admin/page/activate":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1081);

                $page = new Model\Page(Validator::integer($_POST["id"]));
                $page->set("active", 1);

                Ajax::add('table.pages tr[data-id="'.$_POST["id"].'"]', "");
                Ajax::add('#response', '<div class="alert is--success">'._("Page successfully activated.").'</div>');

                break;
            case "/admin/page/deactivate":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1082);

                $page = new Model\Page(Validator::integer($_POST["id"]));
                $page->set("active", 0);

                Ajax::add('table.pages tr[data-id="'.$_POST["id"].'"]', "");
                Ajax::add('#response', '<div class="alert is--success">'._("Page successfully deactivated.").'</div>');

                break;
            case "/admin/page/delete":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1083);

                (new Model\Page(Validator::integer($_POST["id"])))->delete();

                Ajax::remove('table.pages tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Page successfully deleted.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1084);
        }
    }

    /**
     * 
     * Handles newsletter actions, including sending newsletter to all accounts.
     *
     *  @since  3.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function newsletterAction(string $request) {
        switch($request) {
            case "/admin/newsletter/send":

                if (empty($_POST["subject"]) || empty($_POST["message"]) || empty($_POST["queue"])) 
                    throw new Exception(_("Required input not found."), 1085);

                $subject = Validator::string($_POST["subject"]);
                $message = Validator::string($_POST["message"]);
                $accountQueue = array_map('trim', explode(',', Validator::string($_POST["queue"])));
                $ignoreNewsletterPreference = (!empty($_POST["ignore"])) ? true : false;

                $count = 0;
                foreach($accountQueue ?? [] as $key => $account) {
                    if ($count <= 5) {
                        $account = new Model\Account($account["id"]);
                        if ($account->get("newsletter") !== "0" || $ignoreNewsletterPreference) {
                            App::set_locale_runtime($account->get("language") ?? App::get("APP_LANGUAGE"));
                            Mailer::send(sprintf(_($subject." | %s"), App::get("APP_NAME")), $account->get("email"), Template::get("/_emails/accountNewsletter.tpl", [
                                "var" => (object) [
                                    "message" => $message
                                ],
                                "app" => (object) [
                                    "url" => App::get("APP_URL"),
                                    "name" => App::get("APP_NAME"),
                                ],
                                "account" => (object) [
                                    "username" => $account->get("username"),
                                    "meta" => (object) [
                                        "displayname" => $account->get("displayname")
                                    ]
                                ]
                            ]), null, null, App::get("MAIL_RECEIVER")); 
                            $count++;
                        }
                        unset($accountQueue[$key]);
                    }
                }

                if (count($accountQueue) > 0)
                    Ajax::add('[name="queue"]', implode(',', $accountQueue));
                else {
                    App::set_locale_runtime($_COOKIE["locale"] ?? App::get("APP_LANGUAGE"));
                    Ajax::add('#response', '<div class="alert is--success">'._("Newsletter successfully sent.").'</div>');    
                }

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1086);
        }
    }

}

?>