{% use MVC\Database as Database; %}
{% Database::delete("app_accounts_watchlist", "detected < NOW() - INTERVAL 30 DAY"); %}

Cron Job executed.