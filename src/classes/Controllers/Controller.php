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


namespace MVC\Controllers;

use MVC\App     as App;
use MVC\Ajax    as Ajax;

/**
 * 
 *  Controller Class (Abstract)
 *
 *  The Controller class is an abstract class that serves as the base class for all controllers
 *  in the MVC framework. It provides common functionality such as initializing the user account
 *  and executing actions before and after the main action.
 * 
 */
abstract class Controller {

    /**
     *  @var    Model\Account|Model\Guest   $account     User account or guest model
     */
    protected $account;


    /**
     * 
     *  Executes actions before the main action.
     * 
     *  @since  2.0
     * 
     */
    public function beforeAction() {
        session_start();
        $this->account = App::get_account_by_cookie();
    }

    /**
     * 
     *  Executes actions after the main action.
     * 
     *  @since  2.0
     * 
     */
    public function afterAction() {
        Ajax::push();
    }

} 

?>