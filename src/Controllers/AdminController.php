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
     * This method Handles user-related actions such as editing and deleting user accounts.
     */
    public function userAction() {
        switch(Request::get("requestParts")[2]??"") {
            case "edit":
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

                        $account->set("role", Request::get("role"));
                    }

                if (Request::isset("pw1") && Request::isset("pw2"))
                    if (Fairplay::password(Request::get("pw1"), Request::get("pw2")) != "")
                        $account->set("password", password_hash(Request::get("pw1"), PASSWORD_DEFAULT));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "delete":
                if ($this->account->get("id") == Fairplay::integer(Request::get("id")))
                    throw new Exception(_("You can not delete yourself."));

                Database::delete("app_accounts", "id = '".Request::get("id")."'");
                Ajax::add('.response', '<div class="success">'._("User deleted successfully.").'</div>');
        }
    }

}

?>