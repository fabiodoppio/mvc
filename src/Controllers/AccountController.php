<?php

namespace Classes\Controllers;

use \Classes\Auth as Auth;
use \Classes\Ajax as Ajax;
use \Classes\Fairplay as Fairplay;
use \Classes\Request as Request;

class AccountController extends Controller {

    public function beforeAction() {
        $this->account = \Classes\Auth::get_current_account();
        \Classes\Auth::verify_client_token(\Classes\Request::get("client"));

        if (time() < strtotime($this->account->get("lastaction")) + 2)
            throw new \Classes\Exception("Zu viele Anfragen in kurzer Zeit");

        $this->account->set("lastaction", date("Y-m-d H:i:s", time()));

        if (!\Classes\App::get("APP_ONLINE") && $this->account->get("role") != \Classes\Models\Role::ADMINISTRATOR)
            throw new \Classes\Exception("Server offline");

        if ($this->account->get("role") < \Classes\Models\Role::GUEST)
            throw new \Classes\Exception("Dein Account wurde gesperrt oder deaktiviert.");
    }

    public function afterAction() {
        \Classes\Ajax::push();
    }

    public function loginAction() {
        if (!\Classes\App::get("APP_LOGIN"))
            throw new \Classes\Exception("Login zurzeit nicht möglich.");

        Auth::set_current_account(
            Fairplay::string(Request::get("credential")), 
            Fairplay::string(Request::get("pw")),
            Fairplay::boolean(Request::isset("stay") ? Request::get("stay") : false));

        Ajax::redirect("/account");
    }

    public function signupAction() {
        if (!\Classes\App::get("APP_SIGNUP"))
            throw new \Classes\Exception("Registrierung zurzeit nicht möglich.");

        Auth::set_new_account(
            Fairplay::string(Request::get("username")), 
            Fairplay::email(Request::get("email")),
            Fairplay::password(Request::get("pw1"), Request::get("pw2")));

        Ajax::redirect("/account/verify");
    }

    public function recoveryAction() {
        $credential = Fairplay::string(Request::get("credential"));
        if (empty($account = \Classes\Database::select("app_accounts", "email LIKE '".$credential."' OR username = '".$credential."'")))
            throw new \Classes\Exception("Es gibt keinen Account mit diesem Benutzernamen oder dieser E-Mail Adresse.");

        $account = new \Classes\Models\Account($account[0]["id"]);

        switch(Request::get("requestParts")[2]??"") {
            case "request":
                $code = Auth::get_confirmcode($credential);
                $link = \Classes\App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential."/".$code));

                \Classes\Email::send("Account wiederherstellen | ".\Classes\App::get("APP_NAME"), $account->get("email"), \Classes\Template::get("email/recovery.tpl", [
                    "username" => $account->get("username"),
                    "app_name" => \Classes\App::get("APP_NAME"),
                    "code" => $code,
                    "link" => $link
                ]));

                Ajax::redirect(\Classes\App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential)));
                break;
            case "submit":
                Auth::verify_confirmcode($credential, Fairplay::string(str_replace(' ', '', Request::get("code"))));
                if ($account->get("role") < \Classes\Models\Role::GUEST)
                    throw new \Classes\Exception("Dein Account kann nicht wiederhergestellt werden.");
            
                $account->set("password", password_hash(Fairplay::password(Request::get("pw1"), Request::get("pw2")), PASSWORD_DEFAULT));
                $account->set("role", ($account->get("role") == \Classes\Models\Role::DEACTIVATED) ? \Classes\Models\Role::USER : $account->get("role"));

                \Classes\Ajax::add('.main-content form', '<div class="success">Dein Account wurde erfolgreich wiederhergstellt. Du kannst dich nun wie gewohnt anmelden.</div>');
                break;
        }
    }

}

?>