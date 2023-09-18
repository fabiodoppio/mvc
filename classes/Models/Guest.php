<?php

namespace Classes\Models;

class Guest extends Account {

    public function __construct() {
        if (empty($this->data = $_SESSION["account"]??[])) {
            $_SESSION["account"] = [
                "id" => null,
                "email" => null,
                "username" => null,
                "password" => null,
                "token" => \Classes\Auth::get_instance_token(),
                "role" => \Classes\Models\Role::GUEST,
                "lastaction" => date('Y-m-d H:i:s', time()),
                "registered" => date('Y-m-d H:i:s', time())
            ];
            $this->data = $_SESSION["account"];
        }
    }
    
    public function get($key, $row = 0) {
        return $this->data[$key] ?? null;
    }
    
    public function set($key, $value) {
        $this->data[$key] = $value;
        $_SESSION["account"][$key] = $value;
    }
    
    public function length() {
        return count($this->data);
    }

}

?>