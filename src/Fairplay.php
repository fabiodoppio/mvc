<?php

namespace Classes;


class Fairplay 
{
    public static function username(string $val) {
        if (strlen($val) > 18 || strlen($val) < 3)
            throw new Exception("Dein Benutzername muss zwischen 3 und 18 Zeichen lang sein.");
        elseif (preg_match('/[^A-Za-z0-9]+/', $val, $res))
            throw new Exception("Dein Benutzername darf keine Sonderzeichen enthalten.");
        
        $check = preg_replace("/[^A-Za-z0-9üÜöÖäÄ]/", "", strtolower($val));
        foreach(Database::select("app_badwords", "id >= 1") as $badword)
            if  (strstr($check, strtolower($badword["badword"])) !== false)
                throw new Exception("Dein Benutzername ist nicht erlaubt.");

        return $val;
    }

    public static function password(string $val, ?string $val2 = null) {
        if ($val != ($val2??$val))
            throw new Exception("Deine Passwörter stimmen nicht überein.");
        elseif (strlen($val) < 8)
            throw new Exception("Dein Password muss mindestens 8 Zeichen lang sein.");
        return $val;
    }

    public static function email(string $val) {
        $val = filter_var($val, FILTER_VALIDATE_EMAIL);
		if ($val === false) 
            throw new Exception("Du hast eine ungültige E-Mail Adresse eingegeben.");
        return $val;
    }
    
	public static function integer(string $val) {
		$val = filter_var($val, FILTER_VALIDATE_INT);
		if ($val === false) 
            throw new Exception("Du hast eine ungültige Zahl eingegeben.");
		return $val;
	}
        
    public static function number(string $val) {
        if(!is_numeric($val))
            throw new Exception("Du hast eine ungültige Zahl eingegeben.");
        return $val;
    }
    
	public static function string(string $val) {
		if (!is_string($val))
            throw new Exception("Du hast eine ungültige Zeichenkette eingegeben.");
		return $val;
	}

	public static function boolean(string $val) {
		$val = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($val === null)
            throw new Exception("Du hast einen ungültigen Wahrheitswert eingegeben.");
		return $val;
	}

	public static function url(string $val) {
		$val = filter_var($val, FILTER_VALIDATE_URL);
		if ($val === false)
            throw new Exception("Du hast eine ungültige URL eingegeben.");
        return $val;
	}
    
}

?>