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


namespace MVC;

use MVC\Database  as Database;
use MVC\Models    as Model;

/**
 *
 *  Cleaner Class
 *
 *  The Cleaner class provides all cron jobs methods with cleaning purpose.
 *
 */
class Cleaner {

   /**
     *
     *  Deletes app log older than 90 days.
     *
     *  @since  3.1
     *
     */
   public function logs() {
      Database::query("DELETE FROM app_accounts_log WHERE timestamp < DATE_SUB(NOW(), INTERVAL 90 DAY)");
   }

   /**
     *
     *  Deletes empty account meta and page meta fields.
     *
     *  @since  3.1
     *
     */
   public function meta() {
      Database::query("DELETE FROM app_accounts_meta WHERE value = '' OR value IS NULL");
      Database::query("DELETE FROM app_pages_meta WHERE value = '' OR value IS NULL");
   }

   /**
     *
     *  Deletes deactivated accounts older than 90 days.
     *
     *  @since  3.1
     *
     */
   public function accounts() {
      foreach(Database::query("SELECT id FROM app_accounts WHERE role = ? AND lastaction < DATE_SUB(NOW(), INTERVAL 90 DAY)", [Model\Account::DEACTIVATED]) as $account)
         (new Model\Account($account["id"]))->delete();
   }

}

?>