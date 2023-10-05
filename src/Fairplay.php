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


namespace Classes;

/**
 * Fairplay Class
 *
 * The Fairplay class provides validation methods for various user inputs, such as usernames, passwords, emails, integers,
 * numbers, strings, booleans and URLs, to ensure fair and secure usage in the entire web application.
 */
class Fairplay {

    /**
     * Validate a username.
     *
     * @param   string  $val    The username to validate.
     * @return  string          The validated username if it meets the criteria.
     * @throws                  Exception If the username does not meet the criteria.
     */
    public static function username(string $val) {
        if (strlen($val) > 18 || strlen($val) < 3)
            throw new Exception(_("Your username must be between 3 and 18 characters long."));
        elseif (preg_match('/[^A-Za-z0-9]+/', $val, $res))
            throw new Exception(_("Your username cannot contain any special characters."));
        
        $check = preg_replace("/[^A-Za-z0-9üÜöÖäÄ]/", "", strtolower($val));
        foreach(Database::select("app_badwords", "id >= 1") as $badword)
            if  (strstr($check, strtolower($badword["badword"])) !== false)
                throw new Exception(_("Your username is not allowed."));

        return $val;
    }

    /**
     * Validate a password.
     *
     * @param   string          $val    The password to validate.
     * @param   string|null     $val2   (Optional) The second password to compare (for password confirmation).
     * @return  string                  The validated password if it meets the criteria.
     * @throws                          Exception If the password does not meet the criteria or passwords do not match.
     */
    public static function password(string $val, ?string $val2 = null) {
        if ($val != ($val2??$val))
            throw new Exception(_("Your passwords don't match."));
        elseif (strlen($val) < 8)
            throw new Exception(_("Your password must be at least 8 characters long."));
        return $val;
    }

    /**
     * Validate an email address.
     *
     * @param   string  $val    The email address to validate.
     * @return  string          The validated email address if it is valid.
     * @throws                  Exception If the email address is invalid.
     */
    public static function email(string $val) {
        $val = filter_var($val, FILTER_VALIDATE_EMAIL);
		if ($val === false) 
            throw new Exception(_("You have entered an invalid email address."));
        return $val;
    }
    
    /**
     * Validate an integer value.
     *
     * @param   string  $val    The integer value to validate.
     * @return  int             The validated integer value if it is valid.
     * @throws                  Exception If the value is not a valid integer.
     */
	public static function integer(string $val) {
		$val = filter_var($val, FILTER_VALIDATE_INT);
		if ($val === false) 
            throw new Exception(_("You entered an invalid integer."));
		return $val;
	}
    
    /**
     * Validate a numeric value.
     *
     * @param   string  $val    The numeric value to validate.
     * @return  string          The validated numeric value if it is valid.
     * @throws                  Exception If the value is not numeric.
     */
    public static function number(string $val) {
        if(!is_numeric($val))
            throw new Exception(_("You entered an invalid number."));
        return $val;
    }
    
    /**
     * Validate a string value.
     *
     * @param   string  $val    The string value to validate.
     * @return  string          The validated string value if it is valid.
     * @throws                  Exception If the value is not a string.
     */
	public static function string(string $val) {
		if (!is_string($val))
            throw new Exception(_("You entered an invalid string."));
		return $val;
	}

    /**
     * Validate a boolean value.
     *
     * @param   string      $val    The boolean value to validate.
     * @return  bool|null           The validated boolean value if it is valid.
     * @throws                      Exception If the value is not a valid boolean.
     */
	public static function boolean(string $val) {
		$val = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($val === null)
            throw new Exception(_("You entered an invalid boolean."));
		return $val;
	}

    /**
     * Validate a URL.
     *
     * @param   string  $val    The URL to validate.
     * @return  string          The validated URL if it is valid.
     * @throws                  Exception If the URL is invalid.
     */
	public static function url(string $val) {
		$val = filter_var($val, FILTER_VALIDATE_URL);
		if ($val === false)
            throw new Exception(_("You entered an invalid url."));
        return $val;
	}
    
}

?>