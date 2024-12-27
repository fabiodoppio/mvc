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

use MVC\Models      as Model;

/**
 * 
 *  Page Class
 *
 *  The Page class represents a custom page. 
 * 
 */
class Page extends Model\Model {

    /**
     *  @var    string  $table          The name of the database table associated with custom pages
     */
    protected $table = "app_pages";

    /**
     *  @var    string  $primaryKey     The primary key column name in the database table
     */
    protected $primaryKey = "app_pages.id";

}

?>