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

/**
 * 
 *  Ajax Class
 *
 *  The Ajax class provides a simple way to handle AJAX responses.
 *  It allows adding, removing, and redirecting elements in the response and then pushing
 *  the response as a JSON object to the client.
 * 
 */
class Ajax {

    /**
     *  @var    array   $output     An associative array to store AJAX response data
     */
    protected static $output = [];

    /**
     *  @var    string  HTML        Constant representing the jquery .html() command
     */
    public const HTML = 'html';

    /**
     *  @var    string  APPEND      Constant representing the jquery .append() command
     */
    public const APPEND = 'append';

    /**
     *  @var    string  PREPEND     Constant representing the jquery .prepend() command
     */
    public const PREPEND = 'prepend';

    /**
     *  @var    string  PREPLACE    Constant representing the jquery .replaceWith() command
     */
    public const REPLACE = 'replace';


    /**
     * 
     *  Add HTML content to a specific target in the AJAX response.
     *
     *  @since  2.0
     *  @param  string  $target     The target identifier where the HTML content will be added.
     *  @param  string  $html       The HTML content to be added to the target.
     *  @param  string  $option     The information how HTML content will be added.
     * 
     */
    public static function add(string $target, string $html = "", ?string $option = Ajax::HTML) {
        @self::$output[$option][$target] = $html;
    }

    /**
     * 
     *  Mark a specific target for removal in the AJAX response.
     *
     *  @since  2.0
     *  @param  string  $target     The target identifier to be removed.
     * 
     */
    public static function remove(string $target) {
        @self::$output["remove"][$target] = true;
    }

    /**
     * 
     *  Reload the client in the AJAX response.
     *
     *  @since  2.0
     * 
     */
    public static function reload() {
        @self::$output["reload"] = true;
    }

    /**
     * 
     *  Redirect the client to a new URL in the AJAX response.
     *
     *  @since  2.0
     *  @param  string  $url    The URL to which the client will be redirected.
     * 
     */
    public static function redirect(string $url) {
        @self::$output["redirect"] = $url;
    }

    /**
     * 
     *  Push the accumulated AJAX response to the client as a JSON object.
     * 
     *  @since 2.0
     * 
     */
    public static function push() {
        if (!empty(self::$output))
            echo json_encode(self::$output, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_PRETTY_PRINT);
    }

}

?>