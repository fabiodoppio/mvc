<?php

namespace Classes;

class Request 
{
    public static function get(string $input) {
        if (!isset($_REQUEST[$input]))
            throw new Exception("Input ".$input." not found.");
        
        return $_REQUEST[$input];
    }

    public static function isset(string $input) {
        return (isset($_REQUEST[$input]));
    }

}

?>