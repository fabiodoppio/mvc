<?php

namespace Classes;


class Exception extends \Exception {

    public function process() {
        switch($this->getCode()) {
            case 403:
                Auth::unset_cookie();
                http_response_code(403);
                header("Location: /login");
                exit;
                break;
            case 404:
                http_response_code(404);
                header("Location: /404");
                exit;
                break;
            case 405:
                http_response_code(405);
                header("Location: /account");
                exit;
                break;
            default:
                Ajax::add(".response", '<div class="error">'.$this->getMessage().'</div>');
        }
    }

}

?>