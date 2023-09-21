<?php

namespace Classes\Models;

abstract class Model {

    protected $data;
    protected $table;
    protected $objectID;
    protected $primaryKey;

    public function __construct($value) {
        $this->objectID = $value;
        if (empty($this->data = \Classes\Database::select($this->table, $this->primaryKey." = '".$this->objectID."'")))
            throw new \Classes\Exception("Model '".get_class($this).":".$this->objectID."' not found.");
    }
    
    public function get($key, $row = 0) {
        return $this->data[$row][$key] ?? null;
    }
    
    public function set($key, $value) {
        $this->data[0][$key] = $value;
        \Classes\Database::update($this->table, $key." = '".$value."'", $this->primaryKey." = '".$this->objectID."'");
    }
    
    public function length() {
        return count($this->data);
    }

}

?>