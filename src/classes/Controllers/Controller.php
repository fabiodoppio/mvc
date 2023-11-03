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


namespace MVC\Controllers;

use MVC\Ajax as Ajax;
use MVC\Auth as Auth;

/**
 * Controller Class (Abstract)
 *
 * The Controller class serves as a base class for other controller classes and provides common functionality.
 */
abstract class Controller {

    /**
     * @var Model\Account|Model\Guest    $account   The user account associated with the current session.
     */
    protected $account;

    /**
     * Executes before performing any action in the controller.
     * It sets the $account property to the user account associated with the current session.
     */
    public function beforeAction() {
        session_start();
        $this->account = Auth::get_current_account();
    }

    /**
     * Executes after performing an action in the controller.
     * It sends all queued AJAX responses.
     */
    public function afterAction() {
        Ajax::push();
    }

} 

?>