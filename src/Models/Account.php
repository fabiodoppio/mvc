<?php

namespace Classes\Models;

use \Classes\Database as Database;


class Account extends Model {

    protected $table = "app_accounts";
    protected $primaryKey = "app_accounts.id";

    public function set_suspicious(string $request) {
        Database::insert("app_accounts_watchlist", "id, request", "'".$this->get("id")."', '".$request."'");
    }

    public function get_suspicions(string $request = "", float $timespan = 1440) {
        return count(Database::select("app_accounts_watchlist", "id = '".$this->get("id")."' AND request = '".$request."' AND detected BETWEEN (DATE_SUB(NOW(),INTERVAL ".$timespan." MINUTE)) AND NOW()"));
    }

}

?>