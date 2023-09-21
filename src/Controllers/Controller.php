<?php 

namespace Classes\Controllers;

abstract class Controller {

    protected $account;

    public function beforeAction() {
        return;
    }

    public function afterAction() {
        return;
    }

} 

?>