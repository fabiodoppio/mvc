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
        if ($this->account->get("role") < Model\Role::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."));
    }

    /**
     * This method Handles app-related actions such as editing app settings.
     */
    public function appAction() {
        switch(Request::get("request")) {
            case "admin/app/edit":
                if (Request::isset("name"))
                    App::set("APP_NAME", Fairplay::string(Request::get("name")));

                if (Request::isset("title"))
                    App::set("APP_TITLE", Fairplay::string(Request::get("title")));

                if (Request::isset("author"))
                    App::set("APP_AUTHOR", Fairplay::string(Request::get("author")));

                if (Request::isset("description"))
                    App::set("APP_DESCRIPTION", Fairplay::string(Request::get("description")));

                if (Request::isset("language"))
                    App::set("APP_LANGUAGE", Fairplay::string(Request::get("language")));

                if (Request::isset("version"))
                    App::set("APP_VERSION", Fairplay::string(Request::get("version")));

                if (Request::isset("online"))
                    App::set("APP_ONLINE", Fairplay::boolean(Request::get("online")));

                if (Request::isset("login"))
                    App::set("APP_LOGIN", Fairplay::boolean(Request::get("login")));

                if (Request::isset("signup"))
                    App::set("APP_SIGNUP", Fairplay::boolean(Request::get("signup")));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Method %s not found."), Request::get("request")));
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

                $role = new Model\Role(Request::get("role"));
                Database::insert("app_pages", "slug, title, description, robots, template, role", "'".Fairplay::string(Request::get("slug"))."', '".Fairplay::string(Request::get("title"))."', '".Fairplay::string(Request::get("description"))."', '".Fairplay::string(Request::get("robots"))."', '".Fairplay::string(Request::get("template"))."', '".$role->get("id")."'");

                Ajax::add('.response', '<div class="success">'._("Page added successfully.").'</div>');
                break;
            case "admin/page/edit":
                $page = new Model\Page(Request::get("slug"));
                $role = new Model\Role(Request::get("role"));

                $page->set("title", Fairplay::string(Request::get("title")));
                $page->set("description", Fairplay::string(Request::get("description")));
                $page->set("robots", Fairplay::string(Request::get("robots")));
                $page->set("template", Fairplay::string(Request::get("template")));
                $page->set("role", $role->get("id"));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/page/delete":
                Database::delete("app_pages", "slug = '".Fairplay::string(Request::get("slug"))."'");
                Ajax::add('.response', '<div class="success">'._("Page deleted successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Method %s not found."), Request::get("request")));
        }
    }

    /**
     * This method Handles user-related actions such as editing and deleting user accounts.
     */
    public function userAction() {
        switch(Request::get("request")) {
            case "admin/user/logout":
                $account = new Model\Account(Fairplay::integer(Request::get("id")));
                $account->set("token", Auth::get_instance_token());
                Ajax::add('.response', '<div class="success">'._("User successfully logged out.").'</div>');
                break;
            case "admin/user/edit":
                $account = new Model\Account(Fairplay::integer(Request::get("id")));

                if (Request::isset("username")) 
                    if ($account->get("username") != Fairplay::username(Request::get("username"))) {
                        if (!empty(Database::select("app_accounts", "username LIKE '".Request::get("username")."'")[0]))
                            throw new Exception(_("Your entered username is already taken."));

                        $account->set("username", Request::get("username"));
                    }

                if (Request::isset("email")) 
                    if ($account->get("email") != Fairplay::email(Request::get("email"))) {
                        if (!empty(Database::select("app_accounts", "email LIKE '".Request::get("email")."'")[0]))
                            throw new Exception(_("Your entered email address is already taken."));
    
                        $account->set("email", strtolower(Request::get("email")));
                    }

                if (Request::isset("role"))
                    if ($account->get("role") != Fairplay::integer(Request::get("role"))) {
                        if ($account->get("id") == $this->account->get("id"))
                            throw new Exception(_("You can not change your own role."));
                        
                        $role = new Model\Role(Request::get("id"));
                        $account->set("role", $role->get("id"));
                    }

                if (Request::isset("pw1") && Request::isset("pw2"))
                    if (Fairplay::password(Request::get("pw1"), Request::get("pw2")) != "")
                        $account->set("password", password_hash(Request::get("pw1"), PASSWORD_DEFAULT));

                if (Request::isset("meta_name") && Request::isset("meta_value"))
                    if (is_array(Request::get("meta_name")) && is_array(Request::get("meta_value")))
                        for($i = 0; $i < count(Request::get("meta_name")); $i++)
                            $account->set(Request::get("meta_name")[$i], Request::get("meta_value")[$i]);

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/user/delete":
                if ($this->account->get("id") == Fairplay::integer(Request::get("id")))
                    throw new Exception(_("You can not delete yourself."));

                Database::delete("app_accounts", "id = '".Request::get("id")."'");
                Ajax::add('.response', '<div class="success">'._("User deleted successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Method %s not found."), Request::get("request")));
        }
    }

    /**
     * This method Handles role-related actions such as adding, editing and deleting user roles.
     */
    public function roleAction() {
        switch(Request::get("request")) {
            case "admin/role/add":
                Database::insert("app_roles", "name", "'".Fairplay::string(Request::get("name"))."'");
                Ajax::add('.response', '<div class="success">'._("Role added successfully.").'</div>');
                break;
            case "admin/role/edit":
                if (Request::get("id") <= Model\Role::ADMINISTRATOR)
                    throw new Exception(_("You can not edit this role."));

                $role = new Model\Role(Request::get("id"));
                $role->set("name", Fairplay::string(Request::get("name")));
                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "admin/role/delete":
                if (Request::get("id") <= Model\Role::ADMINISTRATOR)
                    throw new Exception(_("You can not delete this role."));

                $role = new Model\Role(Request::get("id"));
                Database::delete("app_roles", "id = '".$role->get("id")."'");
                Ajax::add('.response', '<div class="success">'._("Role deleted successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Method %s not found."), Request::get("request")));
        }
    }

}

?>