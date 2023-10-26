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
 * App Class
 *
 * The App class serves as the configuration manager for the web application. It provides
 * settings and constants related to the application's behavior, such as database connection,
 * directory paths, mail settings, and more. It also initializes the application based on
 * the provided configuration.
 */
class App {

    // Application default settings and constants
    protected static $APP_CONFIG;
    protected static $APP_URL;
    protected static $APP_NAME;
    protected static $APP_TITLE         = "";
    protected static $APP_AUTHOR        = "";
    protected static $APP_DESCRIPTION   = "";
    protected static $APP_LANGUAGE      = "";
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
    protected static $DIR_LOCALE        = "/app/locale"; 
    protected static $DIR_VENDOR        = "/app/vendor";
    protected static $DIR_VIEWS         = "/app/views";
    protected static $DIR_CACHE         = "/app/cache";
    protected static $DIR_MEDIA         = "/app/media";
    protected static $DIR_UPLOADS       = "/app/media/uploads";
    
    protected static $MAIL_HOST;
    protected static $MAIL_SENDER;
    protected static $MAIL_USERNAME;
    protected static $MAIL_PASSWORD;

    protected static $META_PROTECTED    = "[\"username\"]";

    /**
     * Initialize the application based on the provided configuration.
     *
     * @param   array   $config     An associative array containing application configuration settings.
     */
    public static function init(array $config) {
        try {
            foreach ($config as $key => $value)
                if (property_exists(__CLASS__, $key))
                    self::$$key = $value;
                
            foreach (Database::select("app_config", "name IS NOT NULL") as $config)
                self::$APP_CONFIG = [$config["name"] => $config["value"]];
            
            putenv('LANGUAGE='.App::get("APP_LANGUAGE"));
            putenv('LC_ALL='.App::get("APP_LANGUAGE"));
            setlocale(LC_ALL, App::get("APP_LANGUAGE"));
            bindtextdomain("app", App::get("DIR_ROOT").App::get("DIR_LOCALE"));
            textdomain("app");

            if (App::get("APP_DEBUG")) {
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
   	        }

            $_REQUEST["request"]       = @$_REQUEST["request"] ?: strtok($_SERVER["REQUEST_URI"], '?');
            $_REQUEST["requestParts"]  = explode('/', $_REQUEST["request"]);

            $controllerName            = @$_REQUEST["requestParts"][0] ?: "index";
            $controllerClassName       = '\MVC\\Controllers\\'.ucfirst($controllerName).'Controller';

            $actionName                = @$_REQUEST["requestParts"][1] ?: "home";
            $actionMethodName          = $actionName."Action";

            if (!class_exists($controllerClassName))
                throw new Exception(sprintf(_("Controller %s not found."), $controllerName));
            
            $controller = new $controllerClassName();
                
            if ($controllerClassName = '\MVC\\Controllers\\IndexController')
                if (!method_exists($controller, $actionMethodName))
                    $actionMethodName = "customAction";

            if (!method_exists($controller, $actionMethodName))
                throw new Exception(sprintf(_("Action %s not found."), $actionName));
                
            $controller->beforeAction();
            $controller->$actionMethodName();
        }
        catch(Exception $exception) {
            $exception->process();
        }
        
        $controller->afterAction();
    }

    /**
     * Get the value of a configuration key.
     *
     * @param   string  $key    The configuration key to retrieve the value for.
     * @return  mixed           The value associated with the key.
     * @throws                  Exception If the specified key is not a valid configuration key.
     */
    public static function get($key) {
        if ((!property_exists(__CLASS__, $key) || !isset(self::$$key)) && !isset(self::$APP_CONFIG[$key]))
            throw new Exception(sprintf(_("Variable %s not found."), $key));

        return self::$APP_CONFIG[$key] ?? self::$$key;
    }

    /**
     * Update the app's custom config database record. 
     * If the column key does not exist, it sets or updates the custom config based on the 
     * given name and value. If the custom config with the provided name already exists, its 
     * value is updated. If the provided value is null, the custom config is deleted. If the 
     * custom config does not exist, a new entry is created.
     *
     * @param   string  $key    The key to set the value for.
     * @param   mixed   $value  The value to set.
     */
    public static function set($key, $value) {
        if (!empty(Database::select("app_config", "id IS NOT NULL")))
            if ($value === null)
                Database::delete("app_config", "name = '".$key."'");
            else
                Database::update("app_config", "value = '".$value."'", "name = '".$key."'");
        else
            Database::insert("app_config", "name, value", "'".$key."', '".$value."'");
    }

}

?>
