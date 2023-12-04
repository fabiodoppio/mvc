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
 *  App Class
 *
 *  The App class serves as the configuration manager for the web application. It provides
 *  settings and constants related to the application's behavior, such as database connection,
 *  directory paths, mail settings, and more. It also initializes the application based on
 *  the provided configuration.
 * 
 */
class App {

    /**
     *  @var    array   $config             Configuration settings for the application
     */
    protected static $config;

    /**
     *  @var    array   $properties         Custom or overriden properties for the application
     */
    protected static $properties;

    /**
     *  @var    string  $SRC_NAME           Name of the framework
     *  @var    string  $SRC_PACKAGE        Package name of the framework
     *  @var    string  $SRC_DESCRIPTION    Description of the framework
     *  @var    string  $SRC_AUTHOR         Author of the framework
     *  @var    string  $SRC_LICENSE        License of the framework
     *  @var    string  $SRC_VERSION        Version of the framework
     */
    protected static    $SRC_NAME           = "mvc";
    protected static    $SRC_PACKAGE        = "fabiodoppio/mvc";
    protected static    $SRC_DESCRIPTION    = "Model View Controller (MVC) design pattern for simple web applications.";
    protected static    $SRC_AUTHOR         = "Fabio Doppio";
    protected static    $SRC_LICENSE        = "MIT";
    protected static    $SRC_VERSION        = "2.0";

    /**
     *  @var    string  $APP_URL            URL of the app
     *  @var    string  $APP_NAME           Name of the app
     *  @var    string  $APP_TITLE          Title of the start page
     *  @var    string  $APP_AUTHOR         Author of the app
     *  @var    string  $APP_DESCRIPTION    Description of the app
     *  @var    string  $APP_LANGUAGE       Language of the app
     *  @var    string  $APP_THEME          Color scheme of the app
     *  @var    bool    $APP_DEBUG          Testing Env active
     *  @var    bool    $APP_LOGIN          Login active 
     *  @var    bool    $APP_SIGNUP         Signup active
     *  @var    bool    $APP_MAINTENANCE    Maintenance mode active
     *  @var    string  $APP_METAFIELDS     JSON-encoded array of editable user fields
     *  @var    string  $APP_BADWORDS       JSON-encoded array of banned words
     */
    protected static    $APP_URL;
    protected static    $APP_NAME           = "My App";
    protected static    $APP_TITLE          = "";
    protected static    $APP_AUTHOR         = "";
    protected static    $APP_DESCRIPTION    = "";
    protected static    $APP_LANGUAGE       = "en_EN.utf8";
    protected static    $APP_THEME          = "default";
    protected static    $APP_DEBUG          = false;
    protected static    $APP_LOGIN          = true;
    protected static    $APP_SIGNUP         = false;
    protected static    $APP_MAINTENANCE    = false;
    protected static    $APP_METAFIELDS     = "[\"email\",\"company\",\"displayname\",\"firstname\",\"lastname\",\"street\",\"postal\",\"city\",\"country\",\"avatar\",\"language\"]";
    protected static    $APP_BADWORDS       = "[\"\"]";
    
    /**
    *  @var    int     $UPLOAD_FILE_SIZE    Maximum allowed file size in bytes
    *  @var    array   $UPLOAD_FILE_TYPES   Allowed file types for uploads
    *  @var    array   $UPLOAD_IMAGE_TYPES  Allowed file types for images
    */
    protected static    $UPLOAD_FILE_SIZE    = 3072000;
    protected static    $UPLOAD_FILE_TYPES   = "[\"application/pdf\",\"image/jpeg\",\"image/jpg\",\"image/png\",\"image/gif\"]";
    protected static    $UPLOAD_IMAGE_TYPES  = "[\"image/jpeg\",\"image/jpg\",\"image/png\"]";

     /**
     *  @var    mixed   $CRON_AUTH          Randomized hash for cron jobs
     *  @var    bool    $CRON_ACTIVE        Cronjobs active
     */
    protected static    $CRON_AUTH;
    protected static    $CRON_ACTIVE        = false;

    /**
     *  @var    string  $SALT_COOKIE        Randomized Salt for cookies
     *  @var    string  $SALT_TOKEN         Randomized Salt for tokens
     *  @var    string  $SALT_CACHE         Randomized Salt for caching
     */
    protected static    $SALT_COOKIE;
    protected static    $SALT_TOKEN;
    protected static    $SALT_CACHE;

    /**
     *  @var    string  $DB_HOST            MySQL server hostname
     *  @var    string  $DB_USERNAME        MySQL server username
     *  @var    string  $DB_PASSWORD        MySQL server password
     *  @var    string  $DB_DATABASE        MySQL database name
     */
    protected static    $DB_HOST;
    protected static    $DB_USERNAME;
    protected static    $DB_PASSWORD;
    protected static    $DB_DATABASE;

    /**
     *  @var    string  $DIR_ROOT           Root directory of the app
     *  @var    string  $DIR_ASSETS         Directory for assets
     *  @var    string  $DIR_CLASSES        Directory for classes
     *  @var    string  $DIR_FONTS          Directory for fonts
     *  @var    string  $DIR_SCRIPTS        Directory for scripts
     *  @var    string  $DIR_STYLES         Directory for styles
     *  @var    string  $DIR_LOCALE         Directory for localization files
     *  @var    string  $DIR_VENDOR         Directory for third-party libraries
     *  @var    string  $DIR_VIEWS          Directory for view templates
     *  @var    string  $DIR_CACHE          Directory for caching files
     *  @var    string  $DIR_MEDIA          Directory for media files
     *  @var    string  $DIR_UPLOADS        Directory for uploaded files
     */
    protected static    $DIR_ROOT;
    protected static    $DIR_ASSETS         = "/app/assets";
    protected static    $DIR_CLASSES        = "/app/classes";   
    protected static    $DIR_FONTS          = "/app/assets/fonts";   
    protected static    $DIR_SCRIPTS        = "/app/assets/scripts";
    protected static    $DIR_STYLES         = "/app/assets/styles";
    protected static    $DIR_LOCALE         = "/app/locale"; 
    protected static    $DIR_VENDOR         = "/app/vendor";
    protected static    $DIR_VIEWS          = "/app/views";
    protected static    $DIR_CACHE          = "/app/cache";
    protected static    $DIR_MEDIA          = "/app/media";
    protected static    $DIR_UPLOADS        = "/app/media/uploads";

    /**
     *  @var    string  $CUSTOM_JS          JSON-encoded array of custom JavaScript files
     *  @var    string  $CUSTOM_CSS         JSON-encoded array of custom CSS files
     */
    protected static    $CUSTOM_JS          = "[\"\"]";
    protected static    $CUSTOM_CSS         = "[\"\"]";
    
    /**
     *  @var    string  $MAIL_HOST          Mail server hostname
     *  @var    string  $MAIL_SENDER        Sender email address
     *  @var    string  $MAIL_USERNAME      Mail server username
     *  @var    string  $MAIL_PASSWORD      Mail server password
     */
    protected static    $MAIL_HOST          = "";
    protected static    $MAIL_SENDER        = "";
    protected static    $MAIL_USERNAME      = "";
    protected static    $MAIL_PASSWORD      = "";


    /**
     * 
     *  Initializes the application ased on the provided configuration.
     * 
     *  @since  2.0
     *  @param  array   $config     An associative array containing application configuration settings
     * 
     */
    public static function init(array $config) {
        try {
            foreach ($config as $name => $value)
                if (property_exists(__CLASS__, $name))
                    self::$$name = $value;

            foreach (Database::query("SELECT * FROM app_properties") as $property) 
                if (!property_exists(__CLASS__, $property["name"]))
                    self::$properties[$property["name"]] = $property["value"]; 
                else
                    self::$config[$property["name"]] = $property["value"];

            $language = $_COOKIE["locale"]??App::get("APP_LANGUAGE"); 
            putenv('LANGUAGE='.$language);
            putenv('LC_ALL='.$language);
            setlocale(LC_ALL, $language);
            bindtextdomain("app", App::get("DIR_ROOT").App::get("DIR_LOCALE"));
            bindtextdomain("default", App::get("DIR_ROOT").App::get("DIR_VENDOR").'/'.App::get("SRC_PACKAGE")."/src/locale");
            textdomain("default");

            if (App::get("APP_DEBUG")) {
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
   	        }

            $request                   = (!empty($_POST["request"])) ? $_POST["request"] : strtok($_SERVER["REQUEST_URI"], '?');
            $requestParts              = explode('/', $request);

            $controllerName            = $requestParts[0] ?: "index";
            $controllerClassName       = '\MVC\\Controllers\\'.ucfirst($controllerName).'Controller';

            $actionName                = $requestParts[1] ?: "home";
            $actionMethodName          = $actionName."Action";

            if (!class_exists($controllerClassName))
                throw new Exception(sprintf(_("Controller %s not found."), $controllerName), 1000);
            
            $controller = new $controllerClassName();
                
            if ($controllerClassName = '\MVC\\Controllers\\IndexController')
                if (!method_exists($controller, $actionMethodName))
                    $actionMethodName = "customAction";

            if (!method_exists($controller, $actionMethodName))
                throw new Exception(sprintf(_("Action %s not found."), $actionName), 1001);
                
            $controller->beforeAction();
            $controller->$actionMethodName($request);
            $controller->afterAction();
        }
        catch(Exception $exception) {
            $exception->process();
        }
    }

    /**
     *
     *  Retrieves the value of a configuration variable.
     *
     *  @since  2.0
     *  @param  string  $name   Name of the configuration variable
     *  @return mixed           Value of the configuration variable
     * 
     */
    public static function get(string $name) {
        if ((!property_exists(__CLASS__, $name) || !isset(self::$$name)) && !isset(self::$config[$name]) && !isset(self::$properties[$name]))
            throw new Exception(sprintf(_("Variable %s not set."), $name), 1002);

        return self::$config[$name] ?? self::$$name ?? self::$properties[$name];
    }

    /**
     * 
     *  Sets the value of a configuration variable and updates the database if applicable.
     *
     *  @since  2.0
     *  @param  string  $name   Name of the configuration variable
     *  @param  mixed   $value  Value to set for the configuration variable
     * 
     */
    public static function set(string $name, mixed $value) {
        if (isset(self::$config[$name]) || isset(self::$properties[$name]))
            if ($value !== "" && $value !== null)
                Database::query("UPDATE app_properties SET value = ? WHERE name = ?", [$value, $name]);
            else 
                Database::query("DELETE FROM app_properties WHERE name = ?", [$name]);
        else
            if ($value !== "" && $value !== null)
                Database::query("INSERT INTO app_properties (name, value) VALUES (?, ?)", [$name, $value]);

        if (isset(self::$config[$name]))
            self::$config[$name] = $value;
        else
            self::$properties[$name] = $value;
    }

    /**
     * 
     *  Retrieves all configuration settings.
     *
     *  @since  2.0
     *  @return array   Configuration settings
     * 
     */
    public static function get_config() {
        return self::$config ?? [];
    }

    /**
     * 
     *  Retrieves all custom properties
     *
     *  @since  2.0
     *  @return array   Custom properties
     * 
     **/
    public static function get_properties() {
        return self::$properties ?? [];
    }

}

?>