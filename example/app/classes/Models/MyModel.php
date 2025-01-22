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

namespace MVC\Models;


use MVC\Models as Model;

class MyModel extends Model\Model {

    // The name of the database table associated with this model
    protected $table = "app_mymodel";

    // The primary key column name in the database table
    protected $primaryKey = "app_mymodel.id";


    // Get a value from the model's data.
    public function get(string $name) {
        parent::get($name);
    }

    // Set a value in the model's data and update the corresponding database record.
    public function set(string $name, mixed $value) {
        parent::set($name, $value);
    }

    // Delete model.
    public function delete() {
        parent::delete();
    }

    // Get all data associated with the model.
    public function get_data() {
        parent::get_data();
    }

}

?>