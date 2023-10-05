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

/**
 * Request Class
 *
 * The Request class provides methods for handling and retrieving user input from HTTP requests.
 */
class Request {

    /**
     * Get the value of a request parameter by its name.
     *
     * @param   string  $input  The name of the request parameter.
     * @return  mixed           The value of the request parameter if it exists.
     * @throws                  Exception If the request parameter is not found.
     */
    public static function get(string $input) {
        if (!isset($_REQUEST[$input]))
            throw new Exception(sprintf(_("Input %s not found."), $input));
        
        return $_REQUEST[$input];
    }

    /**
     * Check if a request parameter exists by its name.
     *
     * @param   string  $input  The name of the request parameter.
     * @return  bool            True if the request parameter exists, false otherwise.
     */
    public static function isset(string $input) {
        return (isset($_REQUEST[$input]));
    }

}

?>