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
 * The Request class provides methods for retrieving and validating user inputs from HTTP requests
 * to ensure fair and secure usage in the entire web application.
 */
class Request {

    /**
     * Check if a request parameter exists by its name.
     *
     * @param   string  $name   The name of the request parameter.
     * @return  bool            True if the request parameter exists, false otherwise.
     */
    public static function isset(string $name) {
        return (isset($_REQUEST[$name]) || isset($FILES_[$name]));
    }

    /**
     * Get a value of any type by its name.
     *
     * @param   string  $name   The name of the request parameter.
     * @return  mixed           The value of the request parameter if it exists.
     * @throws                  Exception If the request parameter is not found.
     */
    private static function get(string $name) {
        if (!isset($_REQUEST[$name]))
            throw new Exception(sprintf(_("Input %s not found."), $name));

        return $_REQUEST[$name];
    }

    /**
     * Validate a file.
     *
     * @param   string  $name   The input name of the file to validate.
     * @return  array           The validated file if it meets the criteria.
     * @throws                  Exception If the file does not meet the criteria.
     */
    public static function file(string $name) {
        if ($_FILES[$name]['error'] !== 0)
            throw new Exception(_("There was a problem uploading your file."));
        
        if (!in_array(mime_content_type($_FILES[$name]['tmp_name']), App::get("APP_UPLOAD_TYPES")))
            throw new Exception(_("Your file type is not allowed."));

        if ($_FILES[$name]["size"] > App::get("APP_UPLOAD_SIZE"))
            throw new Exception(sprintf(_("Your file exceeds the maximum allowed file size of %s KB."), (App::get("APP_UPLOAD_SIZE")/1000)));

        return $_FILES[$name];
    }

    /**
     * Validate a username.
     *
     * @param   string  $name   The input name of the username value to validate.
     * @return  string          The validated username if it meets the criteria.
     * @throws                  Exception If the username does not meet the criteria.
     */
    public static function username(string $name = "username") {
        $val = self::get($name);

        if (strlen($val) > 18 || strlen($val) < 3)
            throw new Exception(sprintf(_("Your username must be between %1\$s and %2\$s characters long."), 2, 18));
        elseif (preg_match('/[^A-Za-z0-9]+/', $val, $res))
            throw new Exception(_("Your username cannot contain any special characters."));
        
        $check = preg_replace("/[^A-Za-z0-9üÜöÖäÄ]/", "", strtolower($val));
        foreach(Database::select("app_badwords", "badword IS NOT NULL") as $badword)
            if  (strstr($check, strtolower($badword["badword"])) !== false)
                throw new Exception(_("Your username is not allowed."));

        return $val;
    }

    /**
     * Validate a password.
     *
     * @param   string     $name1  The input name of the password to validate.
     * @param   string     $name2  (Optional) The second password to compare (for password confirmation).
     * @return  string             The validated password if it meets the criteria.
     * @throws                     Exception If the password does not meet the criteria or passwords do not match.
     */
    public static function password(string $name1 = "pw1", string $name2 = "pw2") {
        $val1 = self::get($name1);
        $val2 = self::get($name2);

        if ($val1 != $val2)
            throw new Exception(_("Your passwords do not match."));
        elseif (strlen($val1) < 8)
            throw new Exception(sprintf(_("Your password must be at least %s characters long."), 8));
        return $val1;
    }

    /**
     * Validate an email address.
     *
     * @param   string  $name   The input name of the email address to validate.
     * @return  string          The validated email address if it is valid.
     * @throws                  Exception If the email address is invalid.
     */
    public static function email(string $name = "email") {
        $val = filter_var(self::get($name), FILTER_VALIDATE_EMAIL);
		if ($val === false) 
            throw new Exception(_("You have entered an invalid email address."));
        return $val;
    }
    
    /**
     * Validate an integer value.
     *
     * @param   string  $name   The input name of the integer value to validate.
     * @return  int             The validated integer value if it is valid.
     * @throws                  Exception If the value is not a valid integer.
     */
	public static function integer(string $name) {
		$val = filter_var(self::get($name), FILTER_VALIDATE_INT);
		if ($val === false) 
            throw new Exception(_("You entered an invalid integer."));
		return $val;
	}
    
    /**
     * Validate a numeric value.
     *
     * @param   string  $name   The input name of the numeric value to validate.
     * @return  float           The validated numeric value if it is valid.
     * @throws                  Exception If the value is not numeric.
     */
    public static function number(string $name) {
        $val = self::get($name);
        if(!is_numeric($val))
            throw new Exception(_("You entered an invalid number."));
        return $val;
    }
    
    /**
     * Validate a string value.
     *
     * @param   string  $name   The input name of the string value to validate.
     * @return  string          The validated string value if it is valid.
     * @throws                  Exception If the value is not a string.
     */
	public static function string(string $name) {
        $val = self::get($name);
		if (!is_string($val))
            throw new Exception(_("You entered an invalid string."));
		return $val;
	}

    /**
     * Validate a array value.
     *
     * @param   string  $name   The input name of the array value to validate.
     * @return  array           The validated array value if it is valid.
     * @throws                  Exception If the value is not a array.
     */
	public static function array(string $name) {
        $val = self::get($name);
		if (!is_array($val))
            throw new Exception(_("You entered an invalid array."));

		return $val;
	}

    /**
     * Validate a boolean value.
     *
     * @param   string      $name   The input name of the boolean value to validate.
     * @return  bool|null           The validated boolean value if it is valid.
     * @throws                      Exception If the value is not a valid boolean.
     */
	public static function boolean(string $name) {
		$val = filter_var(self::get($name), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($val === null)
            throw new Exception(_("You entered an invalid boolean."));
		return $val;
	}

    /**
     * Validate a URL.
     *
     * @param   string  $val    The input name of the URL to validate.
     * @return  string          The validated URL if it is valid.
     * @throws                  Exception If the URL is invalid.
     */
	public static function url(string $name) {
		$val = filter_var(self::get($name), FILTER_VALIDATE_URL);
		if ($val === false)
            throw new Exception(_("You entered an invalid url."));
        return $val;
	}

    /**
     * Validate a short message.
     *
     * @param   string  $val    The input name of the message to validate.
     * @return  string          The validated message if it is valid.
     * @throws                  Exception If the message is invalid.
     */
    public static function message(string $name = "message") {
        $val = self::get($name);
        if (strlen($val) > 2 || strlen($val) < 250)
            throw new Exception(sprintf(_("Your message must be between %1\$s and %2\$s characters long."), 2, 250));
        
        $check = preg_replace("/[^A-Za-z0-9üÜöÖäÄ]/", "", strtolower($val));
        foreach(Database::select("app_badwords", "badword IS NOT NULL") as $badword)
            if  (strstr($check, strtolower($badword["badword"])) !== false)
                throw new Exception(_("Your message is not allowed."));

        return $val;
    }

}

?>