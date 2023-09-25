<?php

namespace Classes;

use \Classes\App as App;


class Exception extends \Exception {

    public function process() {
        switch($this->getCode()) {
            case 403:
                Auth::unset_cookie();
                http_response_code(403);
                header("Location: ".App::get("APP_URL")."/login");
                exit;
                break;
            case 404:
                http_response_code(404);
                header("Location: ".App::get("APP_URL")."/404");
                exit;
                break;
            case 405:
                http_response_code(405);
                header("Location: ".App::get("APP_URL")."/account");
                exit;
                break;
            default:
                Ajax::add(".response", '<div class="error">'.$this->getMessage().'</div>');
        }
    }

}

?>