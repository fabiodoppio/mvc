{% MVC\Database::query("DELETE FROM app_properties WHERE value = ''"); %}
{% MVC\Database::query("DELETE FROM app_accounts_meta WHERE value = ''"); %}
{% MVC\Database::query("DELETE FROM app_accounts_watchlist WHERE detected < DATE_SUB(NOW(), INTERVAL 30 DAY)"); %}

{{"Cronjob executed."}}