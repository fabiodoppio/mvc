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
            // Forbidden - Redirect to login page
            case 403:
                Auth::unset_cookie();
                http_response_code(403);
                $redirect = (Request::isset("redirect")) ? Request::string("redirect") : Request::string("request");
                header("Location: ".App::get("APP_URL")."/login?redirect=".urlencode($redirect));
                exit;
                break;
            // Not found - Redirect to oops page
            case 404:
                http_response_code(404);
                header("Location: ".App::get("APP_URL")."/oops");
                exit;
                break;
            // Not allowed - Redirect to account page
            case 405:
                http_response_code(405);
                header("Location: ".App::get("APP_URL")."/account");
                exit;
                break;
            // Not verified - Redirect to verification page
            case 406:
                http_response_code(406);
                $redirect = (Request::isset("redirect")) ? Request::string("redirect") : Request::string("request");
                header("Location: ".App::get("APP_URL")."/account/verify?redirect=".urlencode($redirect));
                exit;
                break;
            // Maintenance - Redirect to maintenance page
            case 407:
                http_response_code(407);
                header("Location: ".App::get("APP_URL")."/maintenance");
                exit;
                break;
            // Everything else
            default:
                http_response_code($this->getCode());
                echo $this->getMessage()."(Code: ".$this->getCode().")";
        }
    }

}

?>