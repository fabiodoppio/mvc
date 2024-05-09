{* Delete empty account meta fields (cleaning purpose) *}
{% MVC\Database::query("DELETE FROM app_accounts_meta WHERE value = '' OR value IS NULL"); %}

{* Delete detected illegal actions older than 30 days (cleaning purpose) *}
{% MVC\Database::query("DELETE FROM app_accounts_watchlist WHERE detected < DATE_SUB(NOW(), INTERVAL 30 DAY)"); %}

{* Delete deactivated accounts older than 90 days (privacy purpose) *}
{% foreach(MVC\Database::query("SELECT * FROM app_accounts WHERE role = '".$account->roles->deactivated."' AND lastaction < DATE_SUB(NOW(), INTERVAL 90 DAY)") as $account)
        (new MVC\Models\Accounts($account["id"]))->delete(); %}

{{"Cronjob executed."}}