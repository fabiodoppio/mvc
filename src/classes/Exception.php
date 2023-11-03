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


namespace MVC;

use MVC\App as App;

/**
 * Exception Class
 *
 * The Exception class extends the standard PHP Exception class and provides 
 * custom exception handling for different HTTP response codes and error messages.
 */
class Exception extends \Exception {

    /**
     * Process the exception by handling different HTTP response codes and error messages.
     */
    public function process() {
        switch($this->getCode()) {
            case 403:
                Auth::unset_cookie();
                http_response_code(403);
                $redirect = (Request::isset("redirect")) ? Fairplay::string(Request::get("redirect")) : Request::get("request");
                header("Location: ".App::get("APP_URL")."/login?redirect=".urlencode($redirect));
                exit;
                break;
            case 404:
                http_response_code(404);
                header("Location: ".App::get("APP_URL")."/oops");
                exit;
                break;
            case 405:
                http_response_code(405);
                header("Location: ".App::get("APP_URL")."/account");
                exit;
                break;
            case 406:
                http_response_code(406);
                $redirect = (Request::isset("redirect")) ? Fairplay::string(Request::get("redirect")) : Request::get("request");
                header("Location: ".App::get("APP_URL")."/account/verify?redirect=".urlencode($redirect));
                exit;
            case 407:
                http_response_code(407);
                header("Location: ".App::get("APP_URL")."/maintenance");
                exit;
                break;
            default:
                Ajax::add(".response", '<div class="error">'.$this->getMessage().'</div>');
        }
    }

}

?>