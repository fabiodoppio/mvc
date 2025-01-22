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


use MVC\Ajax as Ajax;

class MyController extends Controller {

    // Executes before the requested action
    public function beforeAction() {
        parent::beforeAction();
    }

    // Executes the requested action
    public function exampleAction(string $request) {
        // Adds a simple message to the #response element
        Ajax::add('#response', '<div class="alert is--success">'._('This method was called because the action '.$request.' was requested.').'</div>');
    }

    // Executes after the requested action
    public function afterAction() {
        parent::afterAction();
    }
}

?>