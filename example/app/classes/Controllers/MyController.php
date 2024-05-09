<?php

namespace MVC\Controllers;


use MVC\Ajax as Ajax;

class MyController extends Controller {

    // Executes before the requested action
    public function beforeAction() {
        parent::beforeAction();
    }

    // Executes the requested action
    public function exampleAction(string $request) {
        // Adds a simple message to the .response element
        Ajax::add('.response', 'This method was called because the action '.$request.' was requested.');
    }

    // Executes after the requested action
    public function afterAction() {
        parent::afterAction();
    }
}

?>