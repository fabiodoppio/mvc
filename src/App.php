<?php

namespace Classes;


class App {

    protected static $APP_URL;
    protected static $APP_NAME;
    protected static $APP_AUTHOR        = "";
    protected static $APP_DESCRIPTION   = "";
    protected static $APP_VERSION       = "";
    protected static $APP_ONLINE        = true;
    protected static $APP_DEBUG         = true;
    protected static $APP_LOGIN         = true;
    protected static $APP_SIGNUP        = false;
    protected static $APP_UPLOAD_SIZE   = 3072000;
    protected static $APP_UPLOAD_TYPES  = ['jpe' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jpg' => 'image/jpg', 'png' => 'image/png', 'gif' => 'image/gif'];

    protected static $SALT_COOKIE       = "61OPkPlKK0z/bShM84JL+kjp";
    protected static $SALT_TOKEN        = "Jneij:12Hn!9Q/QmYbCaaXov";
    protected static $SALT_CACHE        = "H_RyMlr=/wjz?KjFt?BrBGAs";
    
    protected static $DB_HOST;
    protected static $DB_USERNAME;
    protected static $DB_PASSWORD;
    protected static $DB_DATABASE;

    protected static $DIR_ROOT;
    protected static $DIR_ASSETS        = "/app/assets";
    protected static $DIR_CLASSES       = "/app/classes";   
    protected static $DIR_FONTS         = "/app/assets/fonts";   
    protected static $DIR_SCRIPTS       = "/app/assets/scripts";
    protected static $DIR_STYLES        = "/app/assets/styles";
    protected static $DIR_VENDOR        = "/app/vendor";
    protected static $DIR_VIEWS         = "/app/views";
    protected static $DIR_CACHE         = "/app/cache";
    protected static $DIR_MEDIA         = "/app/media";
    protected static $DIR_UPLOADS       = "/app/media/uploads";
    
    protected static $MAIL_HOST;
    protected static $MAIL_SENDER;
    protected static $MAIL_USERNAME;
    protected static $MAIL_PASSWORD;


    public static function init(array $config) {
        try {
            foreach ($config as $key => $value)
                if (property_exists(__CLASS__, $key))
                    self::$$key = $value;
            
            session_start();
            date_default_timezone_set('Europe/Berlin');

            if (App::get("APP_DEBUG")) {
		    ini_set('display_errors', 1);
		    ini_set('display_startup_errors', 1);
		    error_reporting(E_ALL);
   	    }

            $_REQUEST["request"]       = @$_REQUEST["request"] ?: "index/home";
            $_REQUEST["requestParts"]  = explode('/', $_REQUEST["request"]);

            $controllerName            = $_REQUEST["requestParts"][0] ?: "index";
            $controllerClassName       = '\\Classes\\Controllers\\'.ucfirst($controllerName).'Controller';

            $actionName                = $_REQUEST["requestParts"][1] ?: "home";
            $actionMethodName          = $actionName."Action";

            if (!class_exists($controllerClassName))
                throw new Exception("Controller '".$controllerName."' not found.", 404);
        
            $controller = new $controllerClassName();
            
            if (!method_exists($controller, $actionMethodName))
                throw new Exception("Method '".$actionName."' not found.", 404);
            
            $controller->beforeAction();
            $controller->$actionMethodName();
        }
        catch(Exception $exception) {
            $exception->process();
        }
        
        $controller->afterAction();
    }

    public static function get($key) {
        if (!property_exists(__CLASS__, $key) || !isset(self::$$key))
            throw new Exception("Die Variable ".$key." wurde nicht konfiguriert.");

        return self::$$key;
    }

}

?>
