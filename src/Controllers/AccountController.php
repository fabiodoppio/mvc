<?php

namespace Classes\Controllers;

use \Classes\Ajax      as Ajax;
use \Classes\App       as App;
use \Classes\Auth      as Auth;
use \Classes\Database  as Database;
use \Classes\Email     as Email;
use \Classes\Exception as Exception;
use \Classes\Fairplay  as Fairplay;
use \Classes\Models    as Model;
use \Classes\Request   as Request;
use \Classes\Template  as Template;


class AccountController extends Controller {

    public function beforeAction() {
        $this->account = Auth::get_current_account();
        Auth::verify_client_token(Request::get("client"));

        if (time() < strtotime($this->account->get("lastaction")) + 2)
            throw new Exception("Zu viele Anfragen in kurzer Zeit");

        $this->account->set("lastaction", date("Y-m-d H:i:s", time()));

        if (!App::get("APP_ONLINE") && $this->account->get("role") != Model\Role::ADMINISTRATOR)
            throw new Exception("Server offline");

        if ($this->account->get("role") < Model\Role::GUEST)
            throw new Exception("Dein Account wurde gesperrt oder deaktiviert.");
    }

    public function loginAction() {
        if (!App::get("APP_LOGIN"))
            throw new Exception("Login zurzeit nicht möglich.");

        Auth::set_current_account(
            Fairplay::string(Request::get("credential")), 
            Fairplay::string(Request::get("pw")),
            Fairplay::boolean(Request::isset("stay") ? Request::get("stay") : false));

        Ajax::redirect(App::get("APP_URL")."/account");
    }

    public function signupAction() {
        if (!App::get("APP_SIGNUP"))
            throw new Exception("Registrierung zurzeit nicht möglich.");

        Auth::set_new_account(
            Fairplay::string(Request::get("username")), 
            Fairplay::email(Request::get("email")),
            Fairplay::password(Request::get("pw1"), Request::get("pw2")));

        Ajax::redirect(App::get("APP_URL")."/account/verify");
    }

    public function recoveryAction() {
        $credential = Fairplay::string(Request::get("credential"));
        if (empty($account = Database::select("app_accounts", "email LIKE '".$credential."' OR username = '".$credential."'")))
            throw new Exception("Es gibt keinen Account mit diesem Benutzernamen oder dieser E-Mail Adresse.");

        $account = new Model\Account($account[0]["id"]);

        switch(Request::get("requestParts")[2]??"") {
            case "request":
                $code = Auth::get_confirmcode($credential);
                $link = App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential."/".$code));

                Email::send("Account wiederherstellen | ".App::get("APP_NAME"), $account->get("email"), Template::get("email/recovery.tpl", [
                    "username" => $account->get("username"),
                    "app_name" => App::get("APP_NAME"),
                    "code" => $code,
                    "link" => $link
                ]));

                Ajax::redirect(App::get("APP_URL")."/recovery?code=".str_replace('=', '', base64_encode($credential)));
                break;
            case "submit":
                Auth::verify_confirmcode($credential, Fairplay::string(str_replace(' ', '', Request::get("code"))));
                if ($account->get("role") < Model\Role::GUEST)
                    throw new Exception("Dein Account kann nicht wiederhergestellt werden.");
            
                $account->set("password", password_hash(Fairplay::password(Request::get("pw1"), Request::get("pw2")), PASSWORD_DEFAULT));
                $account->set("role", ($account->get("role") == Model\Role::DEACTIVATED) ? Model\Role::USER : $account->get("role"));

                Ajax::add('.main-content form', '<div class="success">Dein Account wurde erfolgreich wiederhergstellt. Du kannst dich nun wie gewohnt anmelden.</div>');
                break;
        }
    }

}

?>