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
use MVC\Exception as Exception;
use MVC\Fairplay  as Fairplay;
use MVC\Models    as Model;
use MVC\Request   as Request;
use MVC\Template  as Template;

/**
 * AdminController Class
 *
 * This controller class handles actions related to admin-specific functionality.
 */
class AdminController extends AccountController {

    /**
     * Performs actions before processing any admin-related actions.
     */
    public function beforeAction() {
        parent::beforeAction();
        if ($this->account->get("role") < Model\Account::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."));
    }

    /**
     * This method Handles app-related actions such as editing settings.
     */
    public function settingAction() {
        switch(Request::get("request")) {
            case "admin/setting/edit":
                if (Request::isset("APP_URL") && Request::get("APP_URL") != App::get("APP_URL"))
                    App::set("APP_URL", Fairplay::string(Request::get("APP_URL")));

                if (Request::isset("APP_NAME") && Request::get("APP_NAME") != App::get("APP_NAME"))
                    App::set("APP_NAME", Fairplay::string(Request::get("APP_NAME")));

                if (Request::isset("APP_TITLE") && Request::get("APP_TITLE") != App::get("APP_TITLE"))
                    App::set("APP_TITLE", Fairplay::string(Request::get("APP_TITLE")));

                if (Request::isset("APP_AUTHOR") && Request::get("APP_AUTHOR") != App::get("APP_AUTHOR"))
                    App::set("APP_AUTHOR", Fairplay::string(Request::get("APP_AUTHOR")));
                
                if (Request::isset("APP_LANGUAGE") && Request::get("APP_LANGUAGE") != App::get("APP_LANGUAGE"))
                    App::set("APP_LANGUAGE", Fairplay::string(Request::get("APP_LANGUAGE")));

                if (Request::isset("APP_DESCRIPTION") && Request::get("APP_DESCRIPTION") != App::get("APP_DESCRIPTION"))
                    App::set("APP_DESCRIPTION", Fairplay::string(Request::get("APP_DESCRIPTION")));

                if (Request::isset("APP_VERSION") && Request::get("APP_VERSION") != App::get("APP_VERSION"))
                    App::set("APP_VERSION", Fairplay::string(Request::get("APP_VERSION")));

                if (Request::isset("APP_ONLINE") && Request::get("APP_ONLINE") != App::get("APP_ONLINE"))
                    App::set("APP_ONLINE", Fairplay::boolean(Request::get("APP_ONLINE")));
                
                if (Request::isset("APP_LOGIN") && Request::get("APP_LOGIN") != App::get("APP_LOGIN"))
                    App::set("APP_LOGIN", Fairplay::boolean(Request::get("APP_LOGIN")));

                if (Request::isset("APP_SIGNUP") && Request::get("APP_SIGNUP") != App::get("APP_SIGNUP"))
                    App::set("APP_SIGNUP", Fairplay::boolean(Request::get("APP_SIGNUP")));

                if (Request::isset("MAIL_HOST") && Request::get("MAIL_HOST") != App::get("MAIL_HOST"))
                    App::set("MAIL_HOST", Fairplay::string(Request::get("MAIL_HOST")));

                if (Request::isset("MAIL_SENDER") && Request::get("MAIL_SENDER") != App::get("MAIL_SENDER"))
                    App::set("MAIL_SENDER", Fairplay::email(Request::get("MAIL_SENDER")));

                if (Request::isset("MAIL_USERNAME") && Request::get("MAIL_USERNAME") != App::get("MAIL_USERNAME"))
                    App::set("MAIL_USERNAME", Fairplay::string(Request::get("MAIL_USERNAME")));

                if (Request::isset("MAIL_PASSWORD") && str_replace('=', '', base64_encode(Request::get("MAIL_PASSWORD"))) != App::get("MAIL_PASSWORD"))
                    if (Request::get("MAIL_PASSWORD") != "")
                        App::set("MAIL_PASSWORD", str_replace('=', '', base64_encode(Fairplay::string(Request::get("MAIL_PASSWORD")))));

                if (Request::isset("META_EDITABLE") && Request::get("META_EDITABLE") != App::get("META_EDITABLE"))
                    App::set("META_EDITABLE", json_encode(array_map('trim', explode(',', Fairplay::string(Request::get("META_EDITABLE"))))));

                if (Request::isset("APP_CRON") && Request::get("APP_CRON") != App::get("APP_CRON"))
                    App::set("APP_CRON", Fairplay::boolean(Request::get("APP_CRON")));

                if (Request::isset("CRON_KEY") && Request::get("CRON_KEY") != App::get("CRON_KEY")) {
                    $key = str_replace('=', '', base64_encode(Fairplay::string(Request::get("CRON_KEY"))));
                    App::set("CRON_KEY", $key);
                    Ajax::add('label[for="CRON_URL"]', 'URL <input type="text" value="'.App::get('APP_URL').'/cron?key='.$key.'" disabled/>');
                }

                if (Request::isset("config_name") && Request::isset("config_value"))
                    if (is_array(Request::get("config_name")) && is_array(Request::get("config_value")))
                        for($i = 0; $i < count(Request::get("config_name")); $i++)
                            App::set(Fairplay::string(Request::get("config_name")[$i]), Fairplay::string(Request::get("config_value")[$i]));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::get("request")));
        }
    }

    /**
     * This method Handles page-related actions such as adding, editing and deleting custom pages.
     */
    public function pageAction() {
        switch(Request::get("request")) {
            case "admin/page/add":
                if (!empty(Database::select("app_pages", "slug LIKE '".Request::get("slug")."'")[0]))
                    throw new Exception(_("Your entered slug is already used."));

                Database::insert("app_pages", "slug, title, description, robots, template, role", "'".Fairplay::string(Request::get("slug"))."', '".Fairplay::string(Request::get("title"))."', '".Fairplay::string(Request::get("description"))."', '".Fairplay::string(Request::get("robots"))."', '".Fairplay::string(Request::get("template"))."', '".Fairplay::integer(Request::get("role"))."'");

                Ajax::add(".pages .list", Template::get("admin/elements/PageListItem.tpl", ["item" => new Model\Page(Request::get("slug"))]), "append");
                Ajax::add('.response', '<div class="success">'._("Page added successfully.").'</div>');
                break;
            case "admin/page/edit":
                $page = new Model\Page(Request::get("slug"));
    
                if (Request::isset("title") && Request::get("title")  != $page->get("title")) {
                    $page->set("title", Fairplay::string(Request::get("title")));
                    Ajax::add('.list-item[data-id="'.Request::get("slug").'"] .title', Request::get("title"));
                }
                     
                if (Request::isset("slug") && Request::get("slug")  != $page->get("slug")) {
                    if (!empty(Database::select("app_pages", "slug LIKE '".Request::get("slug")."'")[0]))
                        throw new Exception(_("Your entered slug is already used."));

                    $page->set("slug", Fairplay::string(Request::get("slug")));
                    Ajax::add('.list-item[data-id="'.Request::get("slug").'"] .slug', "slug:".Request::get("slug"));
                }

                if (Request::isset("description") && Request::get("description")  != $page->get("description"))
                    $page->set("description", Fairplay::string(Request::get("description")));

                if (Request::isset("robots") && Request::get("robots")  != $page->get("robots"))
                    $page->set("robots", Fairplay::string(Request::get("robots")));

                if (Request::isset("template") && Request::get("template")  != $page->get("template"))
                    $page->set("template", Fairplay::string(Request::get("template")));
                
                if (Request::isset("role") && Request::get("role")  != $page->get("role"))
                    $page->set("role", Fairplay::integer(Request::get("role")));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/page/delete":
                Database::delete("app_pages", "slug = '".Fairplay::string(Request::get("value"))."'");
                Ajax::remove('.pages .list li[data-id="'.Request::get("value").'"]');
                Ajax::add('.response', '<div class="success">'._("Page deleted successfully.").'</div>');
                break;
            case "admin/page/page":
                $items = array();
                foreach (Database::select("app_pages", "slug IS NOT NULL") as $page)
                    $items[] = new Model\Page($page['slug']);

                $pages = ceil(count($items)/20);
                $page = Fairplay::integer(Request::get("value"));
                $items = array_slice($items, ($page-1)*20, 20);
                
                Ajax::add('.pages .list', Template::get("admin/elements/PageList.tpl", ["items" => $items, "page"=> $page, "pages" => $pages]));
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::get("request")));
        }
    }

    /**
     * This method Handles user-related actions such as editing and deleting user accounts.
     */
    public function accountAction() {
        switch(Request::get("request")) {
            case "admin/account/add":
                if (!empty(Database::select("app_accounts", "username LIKE '".Request::get("username")."'")[0]))
                    throw new Exception(_("Your entered username is already taken."));
    
                if (!empty(Database::select("app_accounts", "email LIKE '".Request::get("email")."'")[0]))
                    throw new Exception(_("Your entered email address is already taken."));
    
                Database::insert("app_accounts", "email, username, password, token, role", "'".strtolower(Fairplay::email(Request::get("email")))."', '".Fairplay::username(Request::get("username"))."', '".password_hash(Fairplay::password(Request::get("pw1"), Request::get("pw2")), PASSWORD_DEFAULT)."', '".Auth::get_instance_token()."', '".Fairplay::integer(Request::get("role"))."'");

                Ajax::add(".accounts .list", Template::get("admin/elements/AccountListItem.tpl", ["item" => new Model\Account(Database::$insert_id)]), "append");
                Ajax::add('.response', '<div class="success">'._("Account added successfully.").'</div>');
                break;
            case "admin/account/logout":
                if ($this->account->get("id") == Fairplay::integer(Request::get("value")))
                    throw new Exception(_("You can not delete yourself."));

                $account = new Model\Account(Fairplay::integer(Request::get("value")));
                $account->set("token", Auth::get_instance_token());
                Ajax::add('.response', '<div class="success">'._("User successfully logged out.").'</div>');
                break;
            case "admin/account/edit":
                $account = new Model\Account(Fairplay::integer(Request::get("id")));

                if (Request::isset("username") && Request::get("username") != $account->get("username")) {
                    if (!empty(Database::select("app_accounts", "username LIKE '".Fairplay::username(Request::get("username"))."'")[0]))
                        throw new Exception(_("Your entered username is already taken."));

                    $account->set("username", Request::get("username"));
                    Ajax::add('.list-item[data-id="'.Request::get("id").'"] .username', Request::get("username"));
                }

                if (Request::isset("email") && Request::get("email") != $account->get("email")) {
                    if (!empty(Database::select("app_accounts", "email LIKE '".Fairplay::email(Request::get("email"))."'")[0]))
                        throw new Exception(_("Your entered email address is already taken."));
    
                    $account->set("email", strtolower(Request::get("email")));
                }

                if (Request::isset("role") && Request::get("role") != $account->get("role")) {
                    if ($account->get("id") == $this->account->get("id"))
                        throw new Exception(_("You can not change your own role."));
                        
                    $account->set("role", Fairplay::integer(Request::get("role")));
                }

                if (Request::isset("pw1") && Request::isset("pw2"))
                    if (Request::get("pw1") != "" || Request::get("pw2") != "")
                        if (Fairplay::password(Request::get("pw1"), Request::get("pw2")) != "")
                            $account->set("password", password_hash(Request::get("pw1"), PASSWORD_DEFAULT));

                if (Request::isset("meta_name") && Request::isset("meta_value"))
                    if (is_array(Request::get("meta_name")) && is_array(Request::get("meta_value")))
                        for($i = 0; $i < count(Request::get("meta_name")); $i++)
                            $account->set(Fairplay::string(Request::get("meta_name")[$i]), Fairplay::string(Request::get("meta_value")[$i]));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/account/delete":
                if ($this->account->get("id") == Fairplay::integer(Request::get("value")))
                    throw new Exception(_("You can not delete yourself."));

                Database::delete("app_accounts", "id = '".Request::get("value")."'");

                Ajax::remove('.accounts .list li[data-id="'.Request::get("value").'"]');
                Ajax::add('.response', '<div class="success">'._("Account deleted successfully.").'</div>');
                break;
            case "admin/account/page":
                $items = array();
                foreach (Database::select("app_accounts", "id IS NOT NULL") as $account)
                    $items[] = new Model\Account($account['id']);

                $pages = ceil(count($items)/20);
                $page = Fairplay::integer(Request::get("value"));
                $items = array_slice($items, ($page-1)*20, 20);
                
                Ajax::add('.accounts', Template::get("admin/elements/AccountList.tpl", ["items" => $items, "page"=> $page, "pages" => $pages]));
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::get("request")));
        }
    }

}

?>