{% use MVC\Database as Database; %}
{% Database::query("DELETE FROM app_accounts_watchlist WHERE detected < NOW() - INTERVAL 30 DAY"); %}

Cron Job executed.