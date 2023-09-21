<?php

namespace Classes;

class Ajax {

    protected static $output = [];

    public static function add(string $target, string $html = "") {
        @self::$output["html"][$target] = $html;
    }

    public static function remove(string $target) {
        @self::$output["remove"][$target] = true;
    }

    public static function redirect(string $url) {
        @self::$output["redirect"] = $url;
    }

    public static function push() {
        if (!empty(self::$output))
            echo json_encode(self::$output, JSON_HEX_QUOT | JSON_HEX_TAG);
    }

}

?>