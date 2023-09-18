<?php

namespace Classes\Controllers;

class IndexController extends Controller {

    public function homeAction() {
        echo \Classes\Template::get(
            "home.tpl", [
                "title" => "Startseite | ".\Classes\App::get("APP_NAME"),
                "description" => \Classes\App::get("APP_DESCRIPTION"),
                "robots" => "index, follow",
                "canonical" => \Classes\App::get("APP_URL"),
                "client" => \Classes\Auth::get_client_token()
        ]);
    }

    public function loginAction() {
        $this->account = \Classes\Auth::get_current_account();

        if ($this->account->get("role") > \Classes\Models\Role::GUEST)
            throw new \Classes\Exception("", 405);

        echo \Classes\Template::get(
            "login.tpl", [
                "title" => "Anmelden | ".\Classes\App::get("APP_NAME"),
                "description" => \Classes\App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => \Classes\App::get("APP_URL")."/login",
                "client" => \Classes\Auth::get_client_token()
        ]);
    }

    public function signupAction() {
        $this->account = \Classes\Auth::get_current_account();

        if ($this->account->get("role") > \Classes\Models\Role::GUEST)
            throw new \Classes\Exception("", 405);

        echo \Classes\Template::get(
            "signup.tpl", [
                "title" => "Registrieren | ".\Classes\App::get("APP_NAME"),
                "description" => \Classes\App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => \Classes\App::get("APP_URL")."/signup",
                "client" => \Classes\Auth::get_client_token()
        ]);
    }

    public function recoveryAction() {
        $this->account = \Classes\Auth::get_current_account();

        if ($this->account->get("role") > \Classes\Models\Role::GUEST)
            throw new \Classes\Exception("", 405);

        $base = (\Classes\Request::isset("code")) ? base64_decode(\Classes\Fairplay::string(\Classes\Request::get("code"))) : "";
        $parts = explode('/',$base);
        $credential = $parts[0]??"";
        $code = $parts[1]??"";

        echo \Classes\Template::get(
            "recovery.tpl", [
                "title" => "Account wiederherstellen | ".\Classes\App::get("APP_NAME"),
                "description" => \Classes\App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => \Classes\App::get("APP_URL")."/recovery",
                "client" => \Classes\Auth::get_client_token(),
                "credential" => $credential,
                "code" => $code
        ]);
    }

    public function accountAction() {
        $this->account = \Classes\Auth::get_current_account();

        if ($this->account->get("role") < \Classes\Models\Role::USER)
            throw new \Classes\Exception("Dein Account hat nicht die erforderlichen Rechte.", 403);
        
        switch(\Classes\Request::get("requestParts")[2]??"") {
            case "":
                echo \Classes\Template::get(
                    "account.tpl", [
                        "title" => "Mein Account | ".\Classes\App::get("APP_NAME"),
                        "description" => \Classes\App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => \Classes\App::get("APP_URL")."/account",
                        "client" => \Classes\Auth::get_client_token()
                ]);
            break;
            case "verify":
                if ($this->account->get("role") > \Classes\Models\Role::USER)
                    throw new \Classes\Exception("", 405);

                $base = (\Classes\Request::isset("code")) ? base64_decode(\Classes\Fairplay::string(\Classes\Request::get("code"))) : "";
                $parts = explode('/',$base);
                $email = $parts[0]??"";
                $code = $parts[1]??"";

                echo \Classes\Template::get(
                    "verify.tpl", [
                        "title" => "E-Mail Adresse verifizieren | ".\Classes\App::get("APP_NAME"),
                        "description" => \Classes\App::get("APP_DESCRIPTION"),
                        "robots" => "noindex, nofollow",
                        "canonical" => \Classes\App::get("APP_URL")."/verify",
                        "client" => \Classes\Auth::get_client_token(),
                        "email" => $email,
                        "code" => $code
                ]);
            break;
            default:
                throw new \Classes\Exception("Seite nicht gefunden.", 404);
        }
    }

    public function notFoundAction() {
        echo \Classes\Template::get(
            "404.tpl", [
                "title" => "404 - Seite nicht gefunden | ".\Classes\App::get("APP_NAME"),
                "description" => \Classes\App::get("APP_DESCRIPTION"),
                "robots" => "noindex, nofollow",
                "canonical" => \Classes\App::get("APP_URL")."/404",
                "client" => \Classes\Auth::get_client_token()
        ]);
    }

}

?>