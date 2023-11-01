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
     * This method Handles app-related actions such as editing app settings.
     */
    public function appAction() {
        switch(Request::get("request")) {
            case "admin/app/edit":
                if (Request::isset("APP_URL"))
                    App::set("APP_URL", Fairplay::string(Request::get("APP_URL")));

                if (Request::isset("APP_NAME"))
                    App::set("APP_NAME", Fairplay::string(Request::get("APP_NAME")));

                if (Request::isset("APP_TITLE"))
                    App::set("APP_TITLE", Fairplay::string(Request::get("APP_TITLE")));

                if (Request::isset("APP_AUTHOR"))
                    App::set("APP_AUTHOR", Fairplay::string(Request::get("APP_AUTHOR")));
                
                if (Request::isset("APP_LANGUAGE"))
                    App::set("APP_LANGUAGE", Fairplay::string(Request::get("APP_LANGUAGE")));

                if (Request::isset("APP_DESCRIPTION"))
                    App::set("APP_DESCRIPTION", Fairplay::string(Request::get("APP_DESCRIPTION")));

                if (Request::isset("APP_VERSION"))
                    App::set("APP_VERSION", Fairplay::string(Request::get("APP_VERSION")));

                if (Request::isset("APP_ONLINE"))
                    App::set("APP_ONLINE", Fairplay::boolean(Request::get("APP_ONLINE")));
                
                if (Request::isset("APP_LOGIN"))
                    App::set("APP_LOGIN", Fairplay::boolean(Request::get("APP_LOGIN")));

                if (Request::isset("APP_SIGNUP"))
                    App::set("APP_SIGNUP", Fairplay::boolean(Request::get("APP_SIGNUP")));

                if (Request::isset("META_PROTECTED"))
                    App::set("META_PROTECTED", json_encode(array_map('trim', explode(',', Fairplay::string(Request::get("META_PROTECTED"))))));

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
     * This method Handles mail-related actions such as editing the mail server.
     */
    public function mailAction() {
        switch(Request::get("request")) {
            case "admin/mail/edit":
                if (Request::isset("MAIL_HOST"))
                    App::set("MAIL_HOST", Fairplay::string(Request::get("MAIL_HOST")));

                if (Request::isset("MAIL_SENDER"))
                    App::set("MAIL_SENDER", Fairplay::email(Request::get("MAIL_SENDER")));

                if (Request::isset("MAIL_USERNAME"))
                    App::set("MAIL_USERNAME", Fairplay::string(Request::get("MAIL_USERNAME")));

                if (Request::isset("MAIL_PASSWORD"))
                    App::set("MAIL_PASSWORD", base64_encode(Fairplay::string(Request::get("MAIL_PASSWORD"))));

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

                Ajax::add('.response', '<div class="success">'._("Page added successfully.").'</div>');
                break;
            case "admin/page/edit":
                $page = new Model\Page(Request::get("slug"));
    
                if (Request::isset("title"))
                    $page->set("title", Fairplay::string(Request::get("title")));

                if (Request::isset("description"))
                    $page->set("description", Fairplay::string(Request::get("description")));

                if (Request::isset("robots"))
                    $page->set("robots", Fairplay::string(Request::get("robots")));

                if (Request::isset("template"))
                    $page->set("template", Fairplay::string(Request::get("template")));
                
                if (Request::isset("role"))
                    $page->set("role", Fairplay::integer(Request::get("role")));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/page/delete":
                Database::delete("app_pages", "slug = '".Fairplay::string(Request::get("value"))."'");
                Ajax::add('.response', '<div class="success">'._("Page deleted successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::get("request")));
        }
    }

    /**
     * This method Handles user-related actions such as editing and deleting user accounts.
     */
    public function userAction() {
        switch(Request::get("request")) {
            case "admin/user/logout":
                $account = new Model\Account(Fairplay::integer(Request::get("value")));
                $account->set("token", Auth::get_instance_token());
                Ajax::add('.response', '<div class="success">'._("User successfully logged out.").'</div>');
                break;
            case "admin/user/edit":
                $account = new Model\Account(Fairplay::integer(Request::get("id")));

                if (Request::isset("username")) {
                    if ($account->get("username") != Request::get("username"))
                        if (!empty(Database::select("app_accounts", "username LIKE '".Fairplay::username(Request::get("username"))."'")[0]))
                            throw new Exception(_("Your entered username is already taken."));

                    $account->set("username", Request::get("username"));
                }

                if (Request::isset("email")) {
                    if ($account->get("email") != Request::get("email"))
                        if (!empty(Database::select("app_accounts", "email LIKE '".Fairplay::email(Request::get("email"))."'")[0]))
                            throw new Exception(_("Your entered email address is already taken."));
    
                    $account->set("email", strtolower(Request::get("email")));
                }

                if (Request::isset("role")) {
                    if ($account->get("role") != Request::get("role"))
                        if ($account->get("id") == $this->account->get("id"))
                            throw new Exception(_("You can not change your own role."));
                        
                    $account->set("role", Fairplay::integer(Request::get("role")));
                }

                if (Request::isset("pw1") && Request::isset("pw2"))
                    if (Fairplay::password(Request::get("pw1"), Request::get("pw2")) != "")
                        $account->set("password", password_hash(Request::get("pw1"), PASSWORD_DEFAULT));

                if (Request::isset("meta_name") && Request::isset("meta_value"))
                    if (is_array(Request::get("meta_name")) && is_array(Request::get("meta_value")))
                        for($i = 0; $i < count(Request::get("meta_name")); $i++)
                            $account->set(Fairplay::string(Request::get("meta_name")[$i]), Fairplay::string(Request::get("meta_value")[$i]));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/user/delete":
                if ($this->account->get("id") == Fairplay::integer(Request::get("value")))
                    throw new Exception(_("You can not delete yourself."));

                Database::delete("app_accounts", "id = '".Request::get("value")."'");
                Ajax::add('.response', '<div class="success">'._("User deleted successfully.").'</div>');
                break;
            case "admin/user/page":
                $accounts = array();
                foreach (Database::select("app_accounts", "id IS NOT NULL") as $user)
                    $accounts[] = new Model\Account($user['id']);

                $pages = ceil(count($accounts)/1);
                $page = Fairplay::integer(Request::get("value"));
                
                Ajax::add('.accounts', Template::get("admin/AccountList.tpl", ["accounts" => $accounts, "page"=> $page, "pages" => $pages]));
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::get("request")));
        }
    }

}

?>