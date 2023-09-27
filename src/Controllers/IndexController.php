<?php

namespace Classes\Controllers;

use \Classes\App       as App;
use \Classes\Ajax      as Ajax;
use \Classes\Auth      as Auth;
use \Classes\Exception as Exception;
use \Classes\Fairplay  as Fairplay;
use \Classes\Models    as Model;
use \Classes\Request   as Request;
use \Classes\Template  as Template;


class IndexController extends Controller {

    public function homeAction() {
        echo Template::get(
            "home.tpl", [
                "title" => "Startseite | ".App::get("APP_NAME"),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "index, follow",
                "canonical" => App::get("APP_URL"),
                "client" => Auth::get_client_token()
        ]);
    }

    public function loginAction() {
        if (!App::get("APP_LOGIN"))
            throw new Exception("Login zurzeit nicht möglich.", 404);

        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception("Dein Account hat nicht die erforderlichen Rechte.", 405);

        echo Template::get(
            "login.tpl", [
                "title" => "Anmelden | ".App::get("APP_NAME"),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => App::get("APP_URL")."/login",
                "client" => Auth::get_client_token()
        ]);
    }

    public function logoutAction() {
        Auth::unset_cookie();
        header("Location: ".App::get("APP_URL")."/");
        exit();
    }

    public function signupAction() {
        if (!App::get("APP_SIGNUP"))
            throw new Exception("Registrierung zurzeit nicht möglich.", 404);

        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception("Dein Account hat nicht die erforderlichen Rechte.", 405);

        echo Template::get(
            "signup.tpl", [
                "title" => "Registrieren | ".App::get("APP_NAME"),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => App::get("APP_URL")."/signup",
                "client" => Auth::get_client_token()
        ]);
    }

    public function recoveryAction() {
        if ($this->account->get("role") > Model\Role::GUEST)
            throw new Exception("Dein Account hat nicht die erforderlichen Rechte.", 405);

        $base = (Request::isset("code")) ? base64_decode(Fairplay::string(Request::get("code"))) : "";
        $parts = explode('/',$base);
        $credential = $parts[0]??"";
        $code = $parts[1]??"";

        echo Template::get(
            "recovery.tpl", [
                "title" => "Account wiederherstellen | ".App::get("APP_NAME"),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => App::get("APP_URL")."/recovery",
                "client" => Auth::get_client_token(),
                "credential" => $credential,
                "code" => $code
        ]);
    }

    public function accountAction() {
        if ($this->account->get("role") < Model\Role::USER)
            throw new Exception("Dein Account hat nicht die erforderlichen Rechte.", 403);
        
        switch(Request::get("requestParts")[2]??"") {
            case "":
                echo Template::get(
                    "account.tpl", [
                        "title" => "Mein Account | ".App::get("APP_NAME"),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/account",
                        "client" => Auth::get_client_token()
                ]);
            break;
            case "verify":
                if ($this->account->get("role") > Model\Role::USER)
                    throw new Exception("", 405);

                $base = (Request::isset("code")) ? base64_decode(Fairplay::string(Request::get("code"))) : "";
                $parts = explode('/',$base);
                $email = $parts[0]??"";
                $code = $parts[1]??"";

                echo Template::get(
                    "verify.tpl", [
                        "title" => "E-Mail Adresse verifizieren | ".App::get("APP_NAME"),
                        "description" => App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => App::get("APP_URL")."/verify",
                        "client" => Auth::get_client_token(),
                        "email" => $email,
                        "code" => $code
                ]);
            break;
            default:
                throw new Exception("Seite nicht gefunden.", 404);
        }
    }

    public function notFoundAction() {
        echo Template::get(
            "404.tpl", [
                "title" => "404 - Seite nicht gefunden | ".App::get("APP_NAME"),
                "description" => App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => App::get("APP_URL")."/404",
                "client" => Auth::get_client_token()
        ]);
    }

}

?>