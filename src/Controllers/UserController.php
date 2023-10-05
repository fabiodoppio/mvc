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
use MVC\Email     as Email;
use MVC\Exception as Exception;
use MVC\Fairplay  as Fairplay;
use MVC\Models    as Model;
use MVC\Request   as Request;
use MVC\Template  as Template;

/**
 * UserController Class
 *
 * This controller class handles actions related to user-specific functionality, including logout and email verification.
 */
class UserController extends AccountController {

    /**
     * Performs actions before processing any user-related actions.
     */
    public function beforeAction() {
        parent::beforeAction();
        if ($this->account->get("role") < Model\Role::USER)
            throw new Exception(_("Your account does not have the required role."));
    }

    /**
     * Handles the logout action for the user, logging them out and redirecting to the home page.
     */
    public function logoutAction() {
        Auth::unset_cookie();
        Ajax::redirect(App::get("APP_URL")."/");
    }

    /**
     * Handles the email verification action for the user, including sending a verification email and processing the
     * verification code submission.
     */
    public function verifyAction() {
        switch(Request::get("requestParts")[2]??"") {
            case "request":
                $code = Auth::get_confirmcode($this->account->get("email"));
                $link = App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email")."/".$code));

                Email::send(sprintf(_("Email address verification | %s"), App::get("APP_NAME")), $this->account->get("email"), Template::get("email/verify.tpl", [
                    "username" => $this->account->get("username"),
                    "app_name" => App::get("APP_NAME"),
                    "code" => $code,
                    "link" => $link
                ]));

                Ajax::redirect(App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email"))));
                break;
            case "submit":
                Auth::verify_confirmcode($this->account->get("email"), Fairplay::string(str_replace(' ', '', Request::get("code"))));
                $this->account->set("role", ($this->account->get("role") == Model\Role::USER) ? Model\Role::VERIFIED : $this->account->get("role"));

                Ajax::add('.main-content form', '<div class="success">'._("Your email address has been successfully verified.").'</div>');
                break;
        }
    }

}

?>