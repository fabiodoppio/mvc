<?php

namespace Classes\Controllers;

use \Classes\Fairplay as Fairplay;
use \Classes\Request as Request;
use \Classes\Auth as Auth;
use \Classes\Ajax as Ajax;

class UserController extends AccountController {

    public function beforeAction() {
        parent::beforeAction();
        if ($this->account->get("role") < \Classes\Models\Role::USER)
            throw new \Classes\Exception("Dein Account hat nicht die erforderlichen Rechte.");
    }

    public function logoutAction() {
        Auth::unset_cookie();
        Ajax::redirect("/");
    }

    public function verifyAction() {
        switch(Request::get("requestParts")[2]??"") {
            case "request":
                $code = Auth::get_confirmcode($this->account->get("email"));
                $link = \Classes\App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email")."/".$code));

                \Classes\Email::send("E-Mail Adresse verifizieren | ".\Classes\App::get("APP_NAME"), $this->account->get("email"), \Classes\Template::get("email/verify.tpl", [
                    "username" => $this->account->get("username"),
                    "app_name" => \Classes\App::get("APP_NAME"),
                    "code" => $code,
                    "link" => $link
                ]));

                Ajax::redirect(\Classes\App::get("APP_URL")."/account/verify?code=".str_replace('=', '', base64_encode($this->account->get("email"))));
                break;
            case "submit":
                Auth::verify_confirmcode($this->account->get("email"), Fairplay::string(str_replace(' ', '', Request::get("code"))));
                $this->account->set("role", ($this->account->get("role") == \Classes\Models\Role::USER) ? \Classes\Models\Role::VERIFIED : $this->account->get("role"));

                \Classes\Ajax::add('.main-content form', '<div class="success">Deine E-Mail Adresse wurde erfolgreich verifiziert.</div>');
                break;
        }
    }

}

?>