<?php 

namespace Classes\Controllers;

use \Classes\Ajax as Ajax;
use \Classes\Auth as Auth;


abstract class Controller {

    protected $account;

    public function beforeAction() {
        $this->account = Auth::get_current_account();
    }

    public function afterAction() {
        Ajax::push();
    }

} 

?>