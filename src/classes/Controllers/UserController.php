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

use MVC\Ajax      as Ajax;
use MVC\App       as App;
use MVC\Auth      as Auth;
use MVC\Database  as Database;
use MVC\Email     as Email;
use MVC\Exception as Exception;
use MVC\Fairplay  as Fairplay;
use MVC\Models    as Model;
use MVC\Template  as Template;
use MVC\Upload    as Upload;

/**
 * 
 *  UserController Class
 *
 *  The UserController class extends the AccountController and is responsible for handling
 *  user-related actions, such as profile management and verification processes.
 *
 */
class UserController extends AccountController {

    /**
     * 
     *  Executes actions before the main action, including checking the user role.
     * 
     *  @since  2.0
     * 
     */
    public function beforeAction() {
        parent::beforeAction();
        if (App::get("APP_MAINTENANCE") && $this->account->get("role") != Model\Account::ADMINISTRATOR)
            throw new Exception(_("App currently offline. Please try again later."), 1083);

        if ($this->account->get("role") < Model\Account::USER)
            throw new Exception(_("Your account does not have the required role."), 1084);
    }

    /**
     * 
     *  Handles user verification-related actions, including requesting and submitting verification.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     *
     */
    public function verifyAction(string $request) {
        switch($request) {
            case "user/verify/request":

                $code = Auth::get_confirmcode($this->account->get("email"));
                $link = App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email")."/".$code));
                $redirect = (!empty($_POST["redirect"])) ? "&redirect=".urlencode(Fairplay::string($_POST["redirect"])) : "";

                Email::send(sprintf(_("Email address verification | %s"), App::get("APP_NAME")), $this->account->get("email"), Template::get("email/verify.tpl", [
                    "var" => (object) [
                        "code" => $code,
                        "link" => $link.$redirect
                    ],
                    "app" => (object) [
                        "url" => App::get("APP_URL"),
                        "name" => App::get("APP_NAME")
                    ],
                    "account" => (object) [
                        "id" => $this->account->get("id"),
                        "email" => $this->account->get("email"),
                        "username" => $this->account->get("username"),
                        "displayname" => $this->account->get("displayname") ?? $this->account->get("username")
                    ]
                ]));

                Ajax::add('.response', '<div class="success">'._("Please wait while redirecting..").'</div>');
                Ajax::redirect(App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email"))).$redirect);

                break;
            case "user/verify/submit":
        
                if (empty($_POST["code"]))
                    throw new Exception(_("Required input not found."), 1085);

                Auth::verify_confirmcode($this->account->get("email"), str_replace(' ', '', Fairplay::string($_POST["code"])));
                $this->account->set("role", ($this->account->get("role") == Model\Account::USER) ? Model\Account::VERIFIED : $this->account->get("role"));


                if (!empty($_POST["redirect"])) {
                    Ajax::add('.response', '<div class="success">'._("Please wait while redirecting..").'</div>');
                    Ajax::redirect(App::get("APP_URL").Fairplay::string($_POST["redirect"])); 
                }
                else
                    Ajax::add('.account .main-content form', '<div class="success">'._("Email address successfully verified.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1086);
        }
    }

    /**
     * 
     * Handles user editing-related actions, including updating username, email, password, and custom fields.
     *
     *  @since  2.0
     *  @param  string  $request    The requested action.
     * 
     */
    public function editAction(string $request) {
        switch($request) {
            case "user/edit":

                if (!empty($_POST["username"]) && $_POST["username"] != $this->account->get("username")) {
                    if (!in_array("username", json_decode(App::get("APP_METAFIELDS"))))
                        throw new Exception(_("You are not allowed to edit your username."),1087);

                    if (!empty(Database::query("SELECT * FROM app_accounts WHERE username LIKE ?", [$_POST["username"]])[0]))
                        throw new Exception(_("This username is already taken."), 1088);

                    $this->account->set("username", Fairplay::username($_POST["username"]));
                }

                if (!empty($_POST["displayname"]) && $_POST["displayname"] != $this->account->get("displayname")) {
                    if (!in_array($_POST["displayname"], [$this->account->get("username"), $this->account->get("company"), $this->account->get("firstname"), $this->account->get("lastname"), $this->account->get("firstname")." ".$this->account->get("lastname"), $this->account->get("lastname")." ".$this->account->get("firstname")]))
                        throw new Exception(_("This displayname is not allowed."), 1089);

                    $this->account->set("displayname", Fairplay::string($_POST["displayname"]));
                }

                if (!empty($_POST["email"]) && $_POST["email"] != $this->account->get("email")) {
                    if (!in_array("email", json_decode(App::get("APP_METAFIELDS"))))
                        throw new Exception(_("You are not allowed to edit your email address."), 1090);

                    if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [$_POST["email"]])[0]))
                        throw new Exception(_("This email address is already taken."), 1091);

                    $this->account->set("email", strtolower(Fairplay::email($_POST["email"])));
                    $this->account->set("role", ($this->account->get("role") == Model\Account::VERIFIED) ? Model\Account::USER : $this->account->get("role"));
                }

                if (!empty($_POST["pw"]) && !empty($_POST["pw1"]) && !empty($_POST["pw2"])) {
                    if (!password_verify($_POST["pw"], $this->account->get("password"))) 
                        throw new Exception(_("Your current password does not match."), 1092);
                
                    $this->account->set("password", password_hash(Fairplay::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT));
                    $this->account->set("token", Auth::get_instance_token());
                    Auth::set_auth_cookie($this->account->get("id"), Auth::get_instance_token(), 0);
                }

                if (isset($_POST["name"]) && isset($_POST["value"]))
                    for($i = 0; $i < count($_POST["name"]); $i++) 
                        if (!empty($_POST["name"][$i]) && isset($_POST["value"][$i])) {
                            if (!in_array($_POST["name"][$i], json_decode(App::get("APP_METAFIELDS"))) || in_array($_POST["name"][$i], array_keys($this->account->get_data())))
                                throw new Exception(sprintf(_("You are not allowed to edit %s."), $_POST["name"][$i]), 1093);

                            $this->account->set(Fairplay::string($_POST["name"][$i]), Fairplay::string($_POST["value"][$i]));
                        }
        
                Ajax::add('.response', '<div class="success">'._("Changes successfully saved.").'</div>');

                break;
            case "user/edit/avatar/upload":

                if (!isset($_FILES["avatar"]))
                    throw new Exception(_("Required input not found."), 1094);

                if (!in_array("avatar", json_decode(App::get("APP_METAFIELDS"))))
                    throw new Exception(_("You are not allowed to edit your avatar."), 1095);

                if ($this->account->get("role") < Model\Account::VERIFIED)
                    throw new Exception(_("You have to verify your email address before you can upload a avatar."), 1106);

                $file = Fairplay::file($_FILES["avatar"]);
                $size = getimagesize($file["tmp_name"]);
                if ($size[0] != $size[1])
                    throw new Exception(_("The avatar has to be squared."), 1096);

                if (!in_array(mime_content_type($file['tmp_name']), array_filter(json_decode(App::get("UPLOAD_IMAGE_TYPES")))))
                    throw new Exception(_("This file type not allowed."), 1107);

                $upload = new Upload($file,"avatar");

                if ($this->account->get("avatar"))
                    Upload::delete($this->account->get("avatar"));

                $this->account->set("avatar", $upload->get_file_name());
                Ajax::add('.account .avatar', '<img src="'.$upload->get_file_url().'"/>');
                Ajax::add('.response', '<div class="success">'._("Avatar successfully uploaded.").'</div>');
 
                break;
            case "user/edit/avatar/delete":

                if (!in_array("avatar", json_decode(App::get("APP_METAFIELDS"))))
                    throw new Exception(_("You are not allowed to edit your avatar."), 1097);

                if ($this->account->get("avatar")) {
                    Upload::delete($this->account->get("avatar"));
                    $this->account->set("avatar", null);
                    Ajax::remove('.account .avatar img');
                }

                Ajax::add('.response', '<div class="success">'._("Avatar successfully deleted.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1098);
        }
    }

}

?>