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
 * Ajax Class
 *
 * The Ajax class provides a simple way to handle AJAX responses.
 * It allows adding, removing, and redirecting elements in the response and then pushing
 * the response as a JSON object to the client.
 */
class Ajax {

    /**
     * An associative array to store AJAX response data.
     *
     * @var     array   $output
     */
    protected static $output = [];

    /**
     * Add HTML content to a specific target in the AJAX response.
     *
     * @param   string  $target     The target identifier where the HTML content will be added.
     * @param   string  $html       The HTML content to be added to the target.
     */
    public static function add(string $target, string $html = "") {
        @self::$output["html"][$target] = $html;
    }

    /**
     * Mark a specific target for removal in the AJAX response.
     *
     * @param   string  $target     The target identifier to be removed.
     */
    public static function remove(string $target) {
        @self::$output["remove"][$target] = true;
    }

    /**
     * Redirect the client to a new URL in the AJAX response.
     *
     * @param   string  $url    The URL to which the client will be redirected.
     */
    public static function redirect(string $url) {
        @self::$output["redirect"] = $url;
    }

    /**
     * Push the accumulated AJAX response to the client as a JSON object.
     *
     * This method sends the AJAX response data to the client using JSON encoding
     * with specific options for security.
     */
    public static function push() {
        if (!empty(self::$output))
            echo json_encode(self::$output, JSON_HEX_QUOT | JSON_HEX_TAG);
    }

}

?>