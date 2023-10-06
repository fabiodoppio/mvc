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
use MVC\Auth      as Auth;
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
                Auth::update_account(
                    Fairplay::integer(Request::get("id")),
                    Fairplay::username(Request::get("username")), 
                    Fairplay::email(Request::get("email")),
                    Fairplay::password(Request::get("pw1"), Request::get("pw2")));

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "delete":
                if ($this->account->get("id") == Request::get("id"))
                    throw new Exception(_("You can not delete yourself."));

                Auth::delete_account(Fairplay::integer(Request::get("id")));

                Ajax::add('.response', '<div class="success">'._("User deleted successfully.").'</div>');
        }
    }

}

?>