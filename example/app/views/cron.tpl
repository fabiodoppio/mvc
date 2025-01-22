{* Delete empty account meta fields (cleaning purpose) *}
{% MVC\Database::query("DELETE FROM app_accounts_meta WHERE value = '' OR value IS NULL"); %}

{* Delete empty custom page meta fields (cleaning purpose) *}
{% MVC\Database::query("DELETE FROM app_pages_meta WHERE value = '' OR value IS NULL"); %}

{* Delete app log older than 90 days (cleaning purpose) *}
{% MVC\Database::query("DELETE FROM app_accounts_log WHERE timestamp < DATE_SUB(NOW(), INTERVAL 90 DAY)"); %}

{* Delete deactivated accounts older than 90 days (privacy purpose) *}
{% foreach(MVC\Database::query("SELECT id FROM app_accounts WHERE role = '".$account->roles->deactivated."' AND lastaction < DATE_SUB(NOW(), INTERVAL 90 DAY)") as $account)
        (new MVC\Models\Account($account["id"]))->delete(); %}

{{"Cronjob executed."}}