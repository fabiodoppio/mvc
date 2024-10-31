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
 *  Fairplay Class
 *
 *  The Fairplay class provides validation methods for various user inputs, such as usernames, passwords, emails, integers,
 *  numbers, strings, arrays, booleans, urls and messages to ensure fair and secure usage in the entire web application.
 * 
 */
class Fairplay {

    /**
     * 
     *  Validate a username.
     *
     *  @since  2.0
     *  @param  string  $value  The username to validate.
     *  @return string          The validated username if it meets the criteria.
     * 
     */
    public static function username(string $value) {
        if (strlen($value) > 18 || strlen($value) < 3)
            throw new Exception(_("The username must be between 3 and 18 characters long."), 1007);
        elseif (preg_match('/[^A-Za-z0-9]+/', $value, $res))
            throw new Exception(_("The username cannot contain any special characters."), 1008);
        
        $check = preg_replace("/[^A-Za-z0-9üÜöÖäÄ]/", "", strtolower($value));
        foreach(App::get("APP_BADWORDS") as $badword)
            if (!empty($badword) && strstr($check, strtolower($badword)) !== false)
                throw new Exception(_("This username is not allowed."), 1009);

        return $value;
    }

    /**
     * 
     *  Validate a password.
     *
     *  @since  2.0
     *  @param  string          $value1     The password to validate.
     *  @param  string|null     $value2     (Optional) The second password to compare (for password confirmation).
     *  @return string                      The validated password if it meets the criteria.
     * 
     */
    public static function password(string $value1, ?string $value2 = null) {
        if ($value1 != $value2)
            throw new Exception(_("The passwords do not match."), 1010);
        elseif (strlen($value1) < 8)
            throw new Exception(sprintf(_("Your password must be at least %s characters long."), 8), 1011);
        return $value1;
    }

    /**
     * 
     *  Validate an email address.
     * 
     *  @since  2.4             Added badword filter.
     *  @since  2.0
     *  @param  string  $value  The email address to validate.
     *  @return string          The validated email address if it is valid.
     * 
     */
    public static function email(string $value) {
        $value = filter_var($value, FILTER_VALIDATE_EMAIL);
		if ($value === false) 
            throw new Exception(_("Invalid email address."), 1012);

        $check = preg_replace("/[^A-Za-z0-9üÜöÖäÄ@]/", "", strtolower($value));
        foreach(App::get("APP_BADWORDS") as $badword)
            if (!empty($badword) && strstr($check, strtolower($badword)) !== false)
                throw new Exception(_("Invalid email address."), 1012);

        return $value;
    }
    
    /**
     * 
     *  Validate an integer value.
     *
     *  @since  2.0
     *  @param  int|string  $value  The integer value to validate.
     *  @return int                 The validated integer value if it is valid.
     * 
     */
	public static function integer(int|string $value) {
		$value = filter_var($value, FILTER_VALIDATE_INT);
		if ($value === false) 
            throw new Exception(_("Invalid integer."), 1013);
		return $value;
	}
    
    /**
     * 
     *  Validate a numeric value.
     *
     *  @since  2.0
     *  @param  float|string  $value    The numeric value to validate.
     *  @return float                   The validated numeric value if it is valid.
     * 
     */
    public static function number(float|string $value) {
        if(!is_numeric($value))
            throw new Exception(_("Invalid number."), 1014);
        return $value;
    }

    /**
     * 
     *  Validate a array value.
     *
     *  @since  2.0
     *  @param  array|string    $value  The array value to validate.
     *  @return array                   The validated array value if it is valid.
     * 
     */
	public static function array(array|string $value) {
		if (!is_array($value))
            throw new Exception(_("Invalid array."), 1015);

		return $value;
	}
    
    /**
     * 
     *  Validate a string value.
     *
     *  @since  2.0
     *  @param  string  $value   The string value to validate.
     *  @return string           The validated string value if it is valid.
     * 
     */
	public static function string(string $value) {
		if (!is_string($value))
            throw new Exception(_("Invalid string."), 1016);
		return $value;
	}

    /**
     * 
     *  Validate a boolean value.
     *
     *  @since  2.0
     *  @param  bool|string     $value  The boolean value to validate.
     *  @return bool|null               The validated boolean value if it is valid.
     * 
     */
	public static function boolean(bool|string $value) {
		$value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($value === null)
            throw new Exception(_("Invalid boolean."), 1017);
		return $value;
	}

    /**
     * 
     *  Validate a URL.
     *
     *  @since  2.0
     *  @param  string  $value  The URL to validate.
     *  @return string          The validated URL if it is valid.
     * 
     */
	public static function url(string $value) {
		$value = filter_var($value, FILTER_VALIDATE_URL);
		if ($value === false)
            throw new Exception(_("Invalid url."), 1018);
        return $value;
	}

    /**
     * 
     *  Validate a datetime.
     *
     *  @since  2.0
     *  @param  string  $value  The URL to validate.
     *  @return string          The validated URL if it is valid.
     * 
     */
	public static function datetime(string $value) {
		if (!strtotime($value))
            throw new Exception(_("Invalid datetime."), 1019);
        return $value;
	}

    /**
     * 
     * Validate a short message.
     *
     *  @since  2.0
     *  @param  string  $value  The message to validate.
     *  @return string          The validated message if it is valid.
     * 
     */
    public static function message(string $value) {
        if (strlen($value) > 250 || strlen($value) < 2)
            throw new Exception(sprintf(_("The message must be between %1\$s and %2\$s characters long."), 2, 250), 1020);
        
        $check = preg_replace("/[^A-Za-z0-9üÜöÖäÄ]/", "", strtolower($value));
        foreach(App::get("APP_BADWORDS") as $badword)
            if (!empty($badword) && strstr($check, strtolower($badword)) !== false)
                throw new Exception(_("This message is not allowed."), 1021);

        return $value;
    }
    
}

?>