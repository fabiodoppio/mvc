<?php

/**
 * 
 *  MVC
 *  Model View Controller (MVC) design pattern for simple web applications.
 *
 *  @see     https://github.com/fabiodoppio/mvc
 *
 *  @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 *  @license https://opensource.org/license/mit/ MIT License
 * 
 */


namespace MVC;

use MVC\App as App;

/**
 * 
 *  Exception Class
 *
 *  The Exception class extends the standard PHP Exception class and provides 
 *  custom exception handling for different HTTP response codes and error messages.
 * 
 */
class Exception extends \Exception {

    /**
     * 
     *  Process the exception by handling different HTTP response codes and error messages.
     * 
     *  @since 2.0
     *
     */
    public function process() {
        switch($this->getCode()) {
            case 403:

                Auth::unset_auth_cookie();
                http_response_code(403);
                $redirect = (!empty($_GET["redirect"])) ? Fairplay::string($_GET["redirect"]) : strtok($_SERVER["REQUEST_URI"], '?');
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
                header("Location: ".App::get("APP_URL")."/maintenance");
                exit;
                
                break;
            default:
                echo $this->getMessage()." (Code: ".$this->getCode().")";
        }
    }

}

?>