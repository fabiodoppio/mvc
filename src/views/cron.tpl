{% MVC\Database::query("DELETE FROM app_accounts_meta WHERE value = ''"); %}
{% MVC\Database::query("DELETE FROM app_accounts_watchlist WHERE detected < DATE_SUB(NOW(), INTERVAL 30 DAY)"); %}
{% MVC\Database::query("DELETE FROM app_accounts WHERE role = '".$account->roles->deactivated."' AND lastaction < DATE_SUB(NOW(), INTERVAL 90 DAY)"); %}

{{"Cronjob executed."}}