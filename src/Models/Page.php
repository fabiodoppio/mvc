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


namespace MVC\Models;

use MVC\Database  as Database;
use MVC\Exception as Exception;

/**
 * Page Class
 *
 * The Page class represents a custom webpage. 
 * It extends the Model class and provides methods 
 * for interacting with the page data in the database.
 */
class Page extends Model {

    /**
     * The name of the database table associated with the custom page.
     *
     * @var     string  $table
     */
    protected $table = "app_pages";

    /**
     * The primary key column name in the database table.
     *
     * @var     string  $primaryKey
     */
    protected $primaryKey = "app_pages.id";

}

?>