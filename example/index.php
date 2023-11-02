<?php

require_once __DIR__.'/app/vendor/autoload.php';

MVC\App::init([
    "APP_URL"           => "https://",
    "APP_NAME"          => "My App",

    "SALT_COOKIE"       => "",
    "SALT_TOKEN"        => "",
    "SALT_CACHE"        => "",

    "DB_HOST"           => "",
    "DB_USERNAME"       => "",
    "DB_PASSWORD"       => "",
    "DB_DATABASE"       => "",

    "DIR_ROOT"          => "/var/www",
    "DIR_VIEWS"         => "/app/views/de_DE",

    "CRON_KEY"          => ""
]);

?>