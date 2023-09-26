<?php

namespace Classes\Controllers;

use \Classes\Ajax      as Ajax;
use \Classes\App       as App;
use \Classes\Auth      as Auth;
use \Classes\Email     as Email;
use \Classes\Exception as Exception;
use \Classes\Fairplay  as Fairplay;
use \Classes\Models    as Model;
use \Classes\Request   as Request;
use \Classes\Template  as Template;


class UserController extends AccountController {

    public function beforeAction() {
        parent::beforeAction();
        if ($this->account->get("role") < Model\Role::USER)
            throw new Exception("Dein Account hat nicht die erforderlichen Rechte.");
    }

    public function logoutAction() {
        Auth::unset_cookie();
        Ajax::redirect(App::get("APP_URL")."/");
    }

    public function verifyAction() {
        switch(Request::get("requestParts")[2]??"") {
            case "request":
                $code = Auth::get_confirmcode($this->account->get("email"));
                $link = App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email")."/".$code));

                Email::send("E-Mail Adresse verifizieren | ".App::get("APP_NAME"), $this->account->get("email"), Template::get("email/verify.tpl", [
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

                Ajax::add('.main-content form', '<div class="success">Deine E-Mail Adresse wurde erfolgreich verifiziert.</div>');
                break;
        }
    }

}

?>