<?php 

namespace Classes\Controllers;

use \Classes\Ajax as Ajax;


abstract class Controller {

    protected $account;

    public function beforeAction() {
        return;
    }

    public function afterAction() {
        Ajax::push();
    }

} 

?>