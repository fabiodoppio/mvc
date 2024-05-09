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

use MVC\Models as Model;

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
     *  @var    string  $instance      Generated token for current instance
     */
    protected static $instance;

    /**
     *  @var    string  $SRC_NAME               Name of the framework
     *  @var    string  $SRC_PACKAGE            Package name of the framework
     *  @var    string  $SRC_DESCRIPTION        Description of the framework
     *  @var    string  $SRC_AUTHOR             Author of the framework
     *  @var    string  $SRC_LICENSE            License of the framework
     *  @var    string  $SRC_VERSION            Version of the framework
     */
    protected static    $SRC_NAME               = "mvc";
    protected static    $SRC_PACKAGE            = "fabiodoppio/mvc";
    protected static    $SRC_DESCRIPTION        = "Model View Controller (MVC) design pattern for simple web applications.";
    protected static    $SRC_AUTHOR             = "Fabio Doppio";
    protected static    $SRC_LICENSE            = "MIT";
    protected static    $SRC_VERSION            = "2.0";

    /**
     *  @var    string  $APP_URL                URL of the app
     *  @var    string  $APP_NAME               Name of the app
     *  @var    string  $APP_TITLE              Title of the start page
     *  @var    string  $APP_AUTHOR             Author of the app
     *  @var    string  $APP_DESCRIPTION        Description of the app
     *  @var    string  $APP_LANGUAGE           Language of the app
     *  @var    bool    $APP_CRON               Cronjobs active
     *  @var    bool    $APP_DEBUG              Testing Env active
     *  @var    bool    $APP_LOGIN              Login active 
     *  @var    bool    $APP_SIGNUP             Signup active
     *  @var    bool    $APP_MAINTENANCE        Maintenance mode active
     *  @var    string  $APP_BADWORDS           JSON-encoded array of banned words
     *  @var    array   $APP_PAGES              Custom pages of the app
     */
    protected static    $APP_URL;
    protected static    $APP_NAME               = "My App";
    protected static    $APP_TITLE              = "";
    protected static    $APP_AUTHOR             = "";
    protected static    $APP_DESCRIPTION        = "";
    protected static    $APP_LANGUAGE           = "en_EN.utf8";
    protected static    $APP_CRON               = true;
    protected static    $APP_DEBUG              = true;
    protected static    $APP_LOGIN              = true;
    protected static    $APP_SIGNUP             = true;
    protected static    $APP_MAINTENANCE        = false;
    protected static    $APP_BADWORDS           = [];
    protected static    $APP_PAGES              = [];

    /**
     *  @var    string  $DIR_ROOT               Root directory of the app
     *  @var    string  $DIR_CLASSES            Directory for classes
     *  @var    string  $DIR_FONTS              Directory for fonts
     *  @var    string  $DIR_SCRIPTS            Directory for scripts
     *  @var    string  $DIR_STYLES             Directory for styles
     *  @var    string  $DIR_LOCALE             Directory for localization files
     *  @var    string  $DIR_VENDOR             Directory for third-party libraries
     *  @var    string  $DIR_VIEWS              Directory for view templates
     *  @var    string  $DIR_CACHE              Directory for caching files
     *  @var    string  $DIR_MEDIA              Directory for media files
     */
    protected static    $DIR_ROOT;
    protected static    $DIR_CLASSES            = "/app/classes";   
    protected static    $DIR_FONTS              = "/app/assets/fonts";   
    protected static    $DIR_SCRIPTS            = "/app/assets/scripts";
    protected static    $DIR_STYLES             = "/app/assets/styles";
    protected static    $DIR_LOCALE             = "/app/locale"; 
    protected static    $DIR_VENDOR             = "/app/vendor";
    protected static    $DIR_VIEWS              = "/app/views";
    protected static    $DIR_CACHE              = "/app/cache";
    protected static    $DIR_MEDIA              = "/app/media";

    /**
     *  @var    string  $SALT_COOKIE            Randomized Salt for cookies
     *  @var    string  $SALT_TOKEN             Randomized Salt for tokens
     *  @var    string  $SALT_CACHE             Randomized Salt for caching
     */
    protected static    $SALT_COOKIE;
    protected static    $SALT_TOKEN;
    protected static    $SALT_CACHE;

    /**
     *  @var    string  $DB_HOST                MySQL server hostname
     *  @var    string  $DB_USERNAME            MySQL server username
     *  @var    string  $DB_PASSWORD            MySQL server password
     *  @var    string  $DB_DATABASE            MySQL database name
     */
    protected static    $DB_HOST                = "";
    protected static    $DB_USERNAME            = "";
    protected static    $DB_PASSWORD            = "";
    protected static    $DB_DATABASE            = "";

    /**
     *  @var    string  $MAIL_HOST              Mail server hostname
     *  @var    string  $MAIL_SENDER            Sender email address
     *  @var    string  $MAIL_RECEIVER          Receiver email address
     *  @var    string  $MAIL_USERNAME          Mail server username
     *  @var    string  $MAIL_PASSWORD          Mail server password
     *  @var    string  $MAIL_ENCRYPT           Mail server encryption
     *  @var    string  $MAIL_PORT              Mail server port
     */
    protected static    $MAIL_HOST              = "";
    protected static    $MAIL_SENDER            = "";
    protected static    $MAIL_RECEIVER          = "";
    protected static    $MAIL_USERNAME          = "";
    protected static    $MAIL_PASSWORD          = "";
    protected static    $MAIL_ENCRYPT           = "ssl";
    protected static    $MAIL_PORT              = "465";
    

    /**
     * 
     *  Initializes the application based on the provided configuration.
     * 
     *  @since  2.0
     *  @param  array   $config     An associative array containing application configuration settings
     * 
     */
    public static function init(array $config) {
        try {
            /* Set configuration for runtime */
            foreach ($config as $name => $value)
                if (property_exists(__CLASS__, $name))
                    self::$$name = $value;

            /* Set language for runtime */
            $language = $_COOKIE["locale"] ?? App::get("APP_LANGUAGE"); 
            putenv('LANGUAGE='.$language);
            putenv('LC_ALL='.$language);
            setlocale(LC_ALL, $language);

            $path = App::get("DIR_ROOT").App::get("DIR_LOCALE");
            if (!file_exists($path."/".$language."/LC_MESSAGES/app.mo"))
                $path = App::get("DIR_ROOT").App::get("DIR_VENDOR").'/'.App::get("SRC_PACKAGE")."/src/locale";
           
            bindtextdomain("app", $path);
            textdomain("app");

            /* Set debug mode for runtime */
            if (App::get("APP_DEBUG")) {
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
   	        }

            /* Execute requested action */
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
                    $actionMethodName = "pageAction";

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
        if (!property_exists(__CLASS__, $name))
            throw new Exception(sprintf(_("Variable %s not set."), $name), 1002);

        return self::$$name;
    }

    /**
     * 
     *  Generate a unique instance token for the application.
     *
     *  @since  2.0
     *  @param  bool    $store  Store token in session.
     *  @return string          The generated instance token.
     * 
     */
    public static function get_instance_token($store = false) {
        if (!is_null(self::$instance))
            return self::$instance;

        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);
    
        for ($i=0; $i < 32; $i++)
            $token .= $codeAlphabet[random_int(0, $max-1)];
    
        if ($store)
            $_SESSION["instance"][$token] = hash_hmac('sha256', $token, hash_hmac('md5', $token, App::get("SALT_TOKEN")));

        self::$instance = $token;
        return $token;
    }

    /**
     * 
     *  Verify a instance token for the current session.
     *
     *  @since  2.0
     *  @param  string  $token  The instance token to verify.
     * 
     */
    public static function verify_instance_token(string $token) {
        if (!hash_equals($_SESSION["instance"][$token]??"", hash_hmac('sha256', $token, hash_hmac('md5', $token, App::get("SALT_TOKEN")))))
            throw new Exception(_("Illegal activity detected."), 1012);
    }

    /**
     * 
     *  Get the current instance model based on the cookies and sessions.
     *
     *  @since  2.0
     *  @return Model\Account|Model\Guest   The current user account or a guest user if not logged in.
     * 
     */
    public static function get_instance_model() {
        if (!isset($_COOKIE["account"]))
            return new Model\Guest();

        if (empty($cookie = explode('$', $_COOKIE["account"])))
            throw new Exception(_("Unauthorized Access."), 403);

        if (empty($account = Database::query("SELECT * FROM app_accounts WHERE id = ?", [$cookie[1]])))
            throw new Exception(_("Unauthorized Access."), 403);

        $account = new Model\Account($account[0]["id"]);
        $hash = hash_hmac('sha256', $account->get("id").$account->get("token"), hash_hmac('md5', $account->get("id").$account->get("token"), App::get("SALT_COOKIE")));

        if (!hash_equals($cookie[0], $hash))
            throw new Exception(_("Unauthorized Access."), 403);

        return $account;
    }

}

?>