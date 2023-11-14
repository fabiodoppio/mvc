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
            throw new Exception(_("App currently offline. Please try again later."), 1068);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 1069);
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
                    Ajax::add('.account .main-content form', '<div class="success">'._("Your email address has been successfully verified.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")), 1070);
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
                            throw new Exception(_("You are not allowed to edit your username."), 1071);

                        if (!empty(Database::query("SELECT * FROM app_accounts WHERE username LIKE ?", [Request::username()])[0]))
                            throw new Exception(_("Your entered username is already taken."), 1072);

                        $this->account->set("username", Request::username());
                    }
                }

                if (Request::isset("email")) {
                    if ($this->account->get("email") != Request::email()) {
                        if (!in_array("email", json_decode(App::get("META_PUBLIC"))))
                            throw new Exception(_("You are not allowed to edit your email address."), 1073);

                        if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [Request::email()])[0]))
                            throw new Exception(_("Your entered email address is already taken."), 1074);

                        $this->account->set("email", strtolower(Request::email()));
                        $this->account->set("role", ($this->account->get("role") == Model\Account::VERIFIED) ? Model\Account::USER : $this->account->get("role"));
                    }
                }

                if (Request::isset("pw") && Request::isset("pw1") && Request::isset("pw2") &&
                        Request::string("pw") != "" && Request::string("pw1") != "" && Request::string("pw2") != "") {
                    if (!in_array("password", json_decode(App::get("META_PUBLIC"))))
                        throw new Exception(_("You are not allowed to edit your password."), 1075);

                    if (!password_verify(Request::string("pw"), $this->account->get("password"))) 
                        throw new Exception(_("Your current password does not match."), 1076);
                
                    $this->account->set("password", password_hash(Request::password(), PASSWORD_DEFAULT));
                }

                if (Request::isset("meta_name") && Request::isset("meta_value"))
                    for($i = 0; $i < count(Request::array("meta_name")); $i++) {
                        if (!in_array(Request::array("meta_name")[$i], json_decode(App::get("META_PUBLIC"))))
                            throw new Exception(sprintf(_("You are not allowed to edit %s."), Request::array("meta_name")[$i]), 1077);

                        if (is_string(Request::array("meta_name")[$i]) && is_string(Request::array("meta_value")[$i]))
                            $this->account->set(Request::array("meta_name")[$i], Request::array("meta_value")[$i]);
                    }
        
                Ajax::add('.response', '<div class="success">'._("Changes saved successfully.").'</div>');
                break;
            case "user/edit/avatar/upload":
                if (!in_array("avatar", json_decode(App::get("META_PUBLIC"))))
                        throw new Exception(_("You are not allowed to edit your avatar."), 1078);

                if (Request::isset("avatar")) {
                    $file = Request::file("avatar");
                    $size = getimagesize($file["tmp_name"]);
                    if ($size[0] != $size[1])
                        throw new Exception(_("Your avatar has to be squared."), 1079);
                    $upload = new Upload($file,"avatar");

                    if ($this->account->get("avatar"))
                        Upload::delete($this->account->get("avatar"));

                    $this->account->set("avatar", $upload->get_file_name());
                    Ajax::add('.account .avatar', '<img src="'.$upload->get_file_url().'"/>');
                }

                Ajax::add('.response', '<div class="success">'._("Avatar uploaded successfully.").'</div>');
                break;
            case "user/edit/avatar/delete":
                if (!in_array("avatar", json_decode(App::get("META_PUBLIC"))))
                    throw new Exception(_("You are not allowed to edit your avatar."), 1080);

                if ($this->account->get("avatar")) {
                    Upload::delete($this->account->get("avatar"));
                    $this->account->set("avatar", null);
                    Ajax::remove('.account .avatar img');
                }

                Ajax::add('.response', '<div class="success">'._("Avatar deleted successfully.").'</div>');
                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), Request::string("request")), 1081);
        }
    }

}

?>