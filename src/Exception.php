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

use MVC\App       as App;
use MVC\Validator as Validator;

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
     *  @since 2.3  Output exception message and code only on POST request method, redirect to 404 as default behaviour.
     *  @since 2.0
     *
     */
    public function process() {
        if (App::is_ajax_call()) {
            Ajax::add("#response", '<div class="alert is--error">'.$this->getMessage().' (Code: '.$this->getCode().')</div>');
            Ajax::push();
            exit();
        }

        switch($this->getCode()) {
            case 403:

                App::unset_auth_cookie();
                http_response_code(403);
                $redirect = (!empty($_GET["redirect"])) ? Validator::string($_GET["redirect"]) : $_SERVER["REQUEST_URI"];
                header("Location: ".App::get("APP_URL")."/login?redirect=".urlencode($redirect));
                exit();

                break;
            case 404:

                http_response_code(404);
                header("Location: ".App::get("APP_URL")."/oops");
                exit();

                break;
            case 405:

                http_response_code(405);
                header("Location: ".App::get("APP_URL")."/account");
                exit();

                break;
            case 406:

                http_response_code(406);
                header("Location: ".App::get("APP_URL")."/maintenance");
                exit();
                
                break;
            default:

                http_response_code(500);
                include App::get("DIR_ROOT").App::get("DIR_VIEWS")."/_includes/error.tpl"; 
                exit();   
        }
    }

}

?>