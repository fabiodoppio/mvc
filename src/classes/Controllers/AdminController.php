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
use MVC\Models    as Model;
use MVC\Request   as Request;
use MVC\Template  as Template;
use MVC\Upload    as Upload;

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
    public function settingsAction() {
        switch(Request::string("request")) {
            case "admin/settings/edit":
                if (Request::isset("APP_DEBUG") && Request::boolean("APP_DEBUG") != App::get("APP_DEBUG"))
                    App::set("APP_DEBUG", Request::boolean("APP_DEBUG"));

                if (Request::isset("APP_URL") && Request::url("APP_URL") != App::get("APP_URL"))
                    App::set("APP_URL", Request::url("APP_URL"));

                if (Request::isset("APP_NAME") && Request::string("APP_NAME") != App::get("APP_NAME"))
                    App::set("APP_NAME", Request::string("APP_NAME"));

                if (Request::isset("APP_TITLE") && Request::string("APP_TITLE") != App::get("APP_TITLE"))
                    App::set("APP_TITLE", Request::string("APP_TITLE"));

                if (Request::isset("APP_AUTHOR") && Request::string("APP_AUTHOR") != App::get("APP_AUTHOR"))
                    App::set("APP_AUTHOR", Request::string("APP_AUTHOR"));
                
                if (Request::isset("APP_DESCRIPTION") && Request::string("APP_DESCRIPTION") != App::get("APP_DESCRIPTION"))
                    App::set("APP_DESCRIPTION", Request::string("APP_DESCRIPTION"));

                if (Request::isset("APP_LANGUAGE") && Request::string("APP_LANGUAGE") != App::get("APP_LANGUAGE"))
                    App::set("APP_LANGUAGE", Request::string("APP_LANGUAGE"));
                
                if (Request::isset("APP_LOGIN") && Request::boolean("APP_LOGIN") != App::get("APP_LOGIN"))
                    App::set("APP_LOGIN", Request::boolean("APP_LOGIN"));

                if (Request::isset("APP_SIGNUP") && Request::boolean("APP_SIGNUP") != App::get("APP_SIGNUP"))
                    App::set("APP_SIGNUP", Request::boolean("APP_SIGNUP"));

                if (Request::isset("APP_CRONJOB") && Request::boolean("APP_CRONJOB") != App::get("APP_CRONJOB"))
                    App::set("APP_CRONJOB", Request::boolean("APP_CRONJOB"));

                if (Request::isset("APP_MAINTENANCE") && Request::boolean("APP_MAINTENANCE") != App::get("APP_MAINTENANCE"))
                    App::set("APP_MAINTENANCE", Request::boolean("APP_MAINTENANCE"));

                if (Request::isset("MAIL_HOST") && Request::string("MAIL_HOST") != App::get("MAIL_HOST"))
                    App::set("MAIL_HOST", Request::string("MAIL_HOST"));

                if (Request::isset("MAIL_SENDER") && Request::email("MAIL_SENDER") != App::get("MAIL_SENDER"))
                    App::set("MAIL_SENDER", Request::email("MAIL_SENDER"));

                if (Request::isset("MAIL_USERNAME") && Request::string("MAIL_USERNAME") != App::get("MAIL_USERNAME"))
                    App::set("MAIL_USERNAME", Request::string("MAIL_USERNAME"));

                if (Request::isset("MAIL_PASSWORD") && Request::string("MAIL_PASSWORD") != "" && 
                        str_replace('=', '', base64_encode(Request::string("MAIL_PASSWORD"))) != App::get("MAIL_PASSWORD"))
                    App::set("MAIL_PASSWORD", str_replace('=', '', base64_encode(Request::string("MAIL_PASSWORD"))));

                if (Request::isset("META_PUBLIC") && Request::string("META_PUBLIC") != App::get("META_PUBLIC"))
                    App::set("META_PUBLIC", json_encode(array_map('trim', explode("\n", Request::string("META_PUBLIC")))));

                if (Request::isset("FILES_JS") && Request::string("FILES_JS") != App::get("FILES_JS"))
                    App::set("FILES_JS", json_encode(array_map('trim', explode("\n", Request::string("FILES_JS")))));

                if (Request::isset("FILES_CSS") && Request::string("FILES_CSS") != App::get("FILES_CSS"))
                    App::set("FILES_CSS", json_encode(array_map('trim', explode("\n", Request::string("FILES_CSS")))));

                if (Request::isset("config_name") && Request::isset("config_value"))
                    for($i = 0; $i < count(Request::array("config_name")); $i++)
                        if (is_string(Request::array("config_name")[$i]) && is_string(Request::array("config_value")[$i]))
                            App::set(Request::array("config_name")[$i], Request::array("config_value")[$i]);

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

    /**
     * This method Handles page-related actions such as adding, editing and deleting custom pages.
     */
    public function pageAction() {
        switch(Request::string("request")) {
            case "admin/page/add":
                if (!empty(Database::query("SELECT * FROM app_pages WHERE slug = ?", [Request::string("slug")])[0]))
                    throw new Exception(_("Your entered slug is already used."));

                Database::query("INSERT INTO app_pages (slug, title, description, robots, template, role) VALUES (?, ?, ?, ?, ?, ?)", [Request::string("slug"), Request::string("title"), Request::string("description"), Request::string("robots"), Request::string("template"), Request::integer("role")]);

                Ajax::add(".admin.pages .list", Template::get("admin/elements/PageListItem.tpl", ["item" => new Model\Page(Database::$insert_id)]), "append");
                Ajax::add('.response', '<div class="success">'._("Page added successfully.").'</div>');
                break;
            case "admin/page/edit":
                $page = new Model\Page(Request::integer("id"));
    
                if (Request::isset("title") && Request::string("title") != $page->get("title")) {
                    $page->set("title", Request::string("title"));
                    Ajax::add('.admin.pages .list .list-item[data-id="'.Request::integer("id").'"] .title', Request::string("title"));
                }
                     
                if (Request::isset("slug") && Request::string("slug") != $page->get("slug")) {
                    if (!empty(Database::query("SELECT * FROM app_pages WHERE slug = ?", [Request::string("slug")])[0]))
                        throw new Exception(_("Your entered slug is already used."));

                    $page->set("slug", Request::string("slug"));
                    Ajax::add('.admin.pages .list .list-item[data-id="'.Request::integer("id").'"] .slug', "slug:".Request::string("slug"));
                }

                if (Request::isset("description") && Request::string("description") != $page->get("description"))
                    $page->set("description", Request::string("description"));

                if (Request::isset("robots") && Request::string("robots") != $page->get("robots"))
                    $page->set("robots", Request::string("robots"));

                if (Request::isset("template") && Request::string("template") != $page->get("template"))
                    $page->set("template", Request::string("template"));
                
                if (Request::isset("role") && Request::integer("role") != $page->get("role"))
                    $page->set("role", Request::integer("role"));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/page/delete":
                Database::query("DELETE FROM app_pages WHERE id = ?", [Request::integer("value")]);
                Ajax::remove('.admin.pages .list li[data-id="'.Request::integer("value").'"]');
                Ajax::add('.response', '<div class="success">'._("Page deleted successfully.").'</div>');
                break;
            case "admin/page/scroll":
                $items = array();
                foreach (Database::query("SELECT * FROM app_pages") as $page)
                    $items[] = new Model\Page($page['id']);

                $pages = ceil(count($items)/20);
                $page = Request::integer("value");
                $items = array_slice($items, ($page - 1) * 20, 20);
                
                Ajax::add('.admin.pages .list', Template::get("admin/elements/PageList.tpl", ["items" => $items, "page"=> $page, "pages" => $pages]));
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

    /**
     * This method Handles user-related actions such as editing and deleting user accounts.
     */
    public function accountAction() {
        switch(Request::string("request")) {
            case "admin/account/add":
                if (!empty(Database::query("SELECT * FROM app_accounts username LIKE ?",[Request::username()])[0]))
                    throw new Exception(_("Your entered username is already taken."));
    
                if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [Request::email()])[0]))
                    throw new Exception(_("Your entered email address is already taken."));
    
                Database::query("INSERT INTO app_accounts (email, username, password, token, role) VALUES (?, ?, ?, ?, ?)", [strtolower(Request::email("email")), Request::username("username"), password_hash(Request::password(), PASSWORD_DEFAULT), Auth::get_instance_token(), Request::integer("role")]);

                Ajax::add(".admin.accounts .list", Template::get("admin/elements/AccountListItem.tpl", ["item" => new Model\Account(Database::$insert_id)]), "append");
                Ajax::add('.response', '<div class="success">'._("Account added successfully.").'</div>');
                break;
            case "admin/account/logout":
                if ($this->account->get("id") == Request::integer("value"))
                    throw new Exception(_("You can not logout yourself."));

                $account = new Model\Account(Request::integer("value"));
                $account->set("token", Auth::get_instance_token());
                Ajax::add('.response', '<div class="success">'._("Account successfully logged out.").'</div>');
                break;
            case "admin/account/edit":
                $account = new Model\Account(Request::integer("id"));

                if (Request::isset("username") && Request::username() != $account->get("username")) {
                    if (!empty(Database::query("SELECT * FROM app_accounts WHERE username LIKE ?", [Request::username()])[0]))
                        throw new Exception(_("Your entered username is already taken."));

                    $account->set("username", Request::username());
                    Ajax::add('.admin .accounts .list .list-item[data-id="'.Request::integer("id").'"] .username', Request::username());
                }

                if (Request::isset("email") && Request::email() != $account->get("email")) {
                    if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [Request::email()])[0]))
                        throw new Exception(_("Your entered email address is already taken."));
    
                    $account->set("email", strtolower(Request::email()));
                }

                if (Request::isset("role") && Request::integer("role") != $account->get("role")) {
                    if ($account->get("id") == $this->account->get("id"))
                        throw new Exception(_("You can not change your own role."));
                        
                    $account->set("role", Request::integer("role"));
                }

                if (Request::isset("pw1") && Request::isset("pw2") && Request::string("pw1") != "" && Request::string("pw2") != "")
                    $account->set("password", password_hash(Request::password(), PASSWORD_DEFAULT));

                if (Request::isset("meta_name") && Request::isset("meta_value"))
                    for($i = 0; $i < count(Request::array("meta_name")); $i++)
                        if (is_string(Request::array("meta_name")[$i]) && is_string(Request::array("meta_value")[$i]))
                            $account->set(Request::array("meta_name")[$i], Request::array("meta_value")[$i]);

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/account/avatar/upload":
                $account = new Model\Account(Request::integer("id"));

                if (Request::isset("avatar")) {
                    $file = Request::file("avatar");
                    $size = getimagesize($file["tmp_name"]);
                    if ($size[0] != $size[1])
                        throw new Exception(_("Your avatar has to be squared."));
                    $upload = new Upload($file,"avatar");

                    if ($account->get("avatar"))
                        Upload::delete($account->get("avatar"));

                    $account->set("avatar", $upload->get_file_name());
                    Ajax::add('.admin.accounts .list li[data-id="'.Request::integer("id").'"] .avatar', '<img src="'.$upload->get_file_url().'"/>');
                }

                Ajax::add('.response', '<div class="success">'._("Avatar uploaded successfully.").'</div>');
                break;
            case "admin/account/avatar/delete":
                $account = new Model\Account(Request::integer("value"));

                if ($account->get("avatar")) {
                    Upload::delete($account->get("avatar"));
                    $account->set("avatar", null);
                    Ajax::remove('.admin.accounts .list li[data-id="'.Request::integer("value").'"] .avatar img');
                }

                Ajax::add('.response', '<div class="success">'._("Avatar deleted successfully.").'</div>');
                break;
            case "admin/account/delete":
                if ($this->account->get("id") == Request::integer("value"))
                    throw new Exception(_("You can not delete yourself."));

                Database::query("DELETE FROM app_accounts WHERE id = ?", [Request::integer("value")]);

                Ajax::remove('.admin.accounts .list li[data-id="'.Request::integer("value").'"]');
                Ajax::add('.response', '<div class="success">'._("Account deleted successfully.").'</div>');
                break;
            case "admin/account/scroll":
                $items = array();
                foreach (Database::query("SELECT * FROM app_accounts") as $account)
                    $items[] = new Model\Account($account['id']);

                $pages = ceil(count($items)/20);
                $page = Request::integer("value");
                $items = array_slice($items, ($page-1)*20, 20);
                
                Ajax::add('.admin.accounts', Template::get("admin/elements/AccountList.tpl", ["items" => $items, "page"=> $page, "pages" => $pages]));
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

    /**
     * This method clears the cache.
     */
    public function cacheAction() {
        switch(Request::string("request")) {
            case "admin/cache/clear":
                Template::clear_cache();
                Ajax::add('.response', '<div class="success">'._("Cache cleared successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

}

?>