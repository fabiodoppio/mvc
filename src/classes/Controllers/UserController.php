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
use MVC\Email     as Email;
use MVC\Exception as Exception;
use MVC\Models    as Model;
use MVC\Request   as Request;
use MVC\Template  as Template;
use MVC\Upload    as Upload;

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

        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."));

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."));
    }

    /**
     * Handles the email verification action for the user, including sending a verification email and processing the
     * verification code submission.
     */
    public function verifyAction() {
        switch(Request::string("request")) {
            case "user/verify/request":
                $code = Auth::get_confirmcode($this->account->get("email"));
                $link = App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email")."/".$code));
                $redirect = (Request::isset("redirect")) ? "&redirect=".urlencode(Request::string("redirect")) : "";

                Email::send(sprintf(_("Email address verification | %s"), App::get("APP_NAME")), $this->account->get("email"), Template::get("email/verify.tpl", [
                    "username" => $this->account->get("username"),
                    "app_name" => App::get("APP_NAME"),
                    "code" => $code,
                    "link" => $link.$redirect
                ]));

                Ajax::redirect(App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email"))).$redirect);
                break;
            case "user/verify/submit":
                Auth::verify_confirmcode($this->account->get("email"), str_replace(' ', '', Request::string("code")));
                $this->account->set("role", ($this->account->get("role") == Model\Account::USER) ? Model\Account::VERIFIED : $this->account->get("role"));

                if (Request::isset("redirect"))
                    Ajax::redirect(App::get("APP_URL").Request::string("redirect")); 
                else
                    Ajax::add('.main-content form', '<div class="success">'._("Your email address has been successfully verified.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

    /**
     * This method Handles user-related actions such as editing the user account.
     */
    public function editAction() {
        switch(Request::string("request")) {
            case "user/edit":
                if (Request::isset("username")) {
                    if ($this->account->get("username") != Request::username()) {
                        if (!in_array("username", json_decode(App::get("META_PUBLIC"))))
                            throw new Exception(_("You are not allowed to edit your username."));

                        if (!empty(Database::select("app_accounts", "username LIKE '".Request::username()."'")[0]))
                            throw new Exception(_("Your entered username is already taken."));

                        $this->account->set("username", Request::username());
                    }
                }

                if (Request::isset("email")) {
                    if ($this->account->get("email") != Request::email()) {
                        if (!in_array("email", json_decode(App::get("META_PUBLIC"))))
                            throw new Exception(_("You are not allowed to edit your email address."));

                        if (!empty(Database::select("app_accounts", "email LIKE '".Request::email()."'")[0]))
                            throw new Exception(_("Your entered email address is already taken."));

                        $this->account->set("email", strtolower(Request::email()));
                        $this->account->set("role", ($this->account->get("role") == Model\Account::VERIFIED) ? Model\Account::USER : $this->account->get("role"));
                    }
                }

                if (Request::isset("pw") && Request::isset("pw1") && Request::isset("pw2") &&
                        Request::string("pw") != "" && Request::string("pw1") != "" && Request::string("pw2") != "") {
                    if (!in_array("password", json_decode(App::get("META_PUBLIC"))))
                        throw new Exception(_("You are not allowed to edit your password."));

                    if (!password_verify(Request::string("pw"), $this->account->get("password"))) 
                        throw new Exception(_("Your current password does not match."));
                
                    $this->account->set("password", password_hash(Request::password(), PASSWORD_DEFAULT));
                }

                if (Request::isset("meta_name") && Request::isset("meta_value"))
                    for($i = 0; $i < count(Request::array("meta_name")); $i++) {
                        if (!in_array(Request::array("meta_name")[$i], json_decode(App::get("META_PUBLIC"))))
                            throw new Exception(sprintf(_("You are not allowed to edit %s."), Request::array("meta_name")[$i]));

                        if (is_string(Request::array("meta_name")[$i]) && is_string(Request::array("meta_value")[$i]))
                            $this->account->set(Request::array("meta_name")[$i], Request::array("meta_value")[$i]);
                    }
        
                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "user/edit/avatar":
                if (!in_array("avatar", json_decode(App::get("META_PUBLIC"))))
                        throw new Exception(_("You are not allowed to edit your avatar."));

                if (Request::isset("avatar")) {
                    $file = Request::file("avatar");
                    $size = getimagesize($file["tmp_name"]);
                    if ($size[0] != $size[1])
                        throw new Exception(_("Your avatar has to be squared."));
                    $upload = new Upload($file,"avatar");
                    $this->account->set("avatar", $upload->get_file_name());
                    Ajax::add('.avatar', '<img src="'.$upload->get_file_url().'"/>');
                }
                else
                    if ($this->account->get("avatar")) {
                        Upload::delete($this->account->get("avatar"));
                        $this->account->set("avatar", null);
                        Ajax::remove('.avatar img');
                    }

                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")));
        }
    }

}

?>