<?php

require_once __DIR__.'/app/vendor/autoload.php';

MVC\App::init([
    "APP_URL"           => "https://",
    "APP_NAME"          => "",
    "APP_TITLE"         => "",
    #"APP_AUTHOR"       => "",
    #"APP_DESCRIPTION"  => "",
    "APP_LANGUAGE"      => "de_DE.utf8",
    #"APP_VERSION"      => "",
    #"APP_ONLINE"       => true,
    #"APP_DEBUG"        => true,
    #"APP_LOGIN"        => true,
    #"APP_SIGNUP"       => false,
    #"APP_UPLOAD_SIZE"  => 3072000,
    #"APP_UPLOAD_TYPES" => ['jpe' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jpg' => 'image/jpg', 'png' => 'image/png', 'gif' => 'image/gif'],

    "SALT_COOKIE"       => "",
    "SALT_TOKEN"        => "",
    "SALT_CACHE"        => "",

    "DB_HOST"           => "",
    "DB_USERNAME"       => "",
    "DB_PASSWORD"       => "",
    "DB_DATABASE"       => "",

    "DIR_ROOT"          => "/var/www",
    #"DIR_ASSETS"       => "/app/assets",
    #"DIR_CLASSES"      => "/app/classes",
    #"DIR_FONTS"        => "/app/assets/fonts",    
    #"DIR_SCRIPTS"      => "/app/assets/scripts",
    #"DIR_STYLES"       => "/app/assets/styles",
    #"DIR_LOCALE"       => "/app/locale",
    #"DIR_VENDOR"       => "/app/vendor",
    "DIR_VIEWS"         => "/app/views/de_DE",
    #"DIR_CACHE"        => "/app/cache",
    #"DIR_MEDIA"        => "/app/media",
    #"DIR_UPLOADS"      => "/app/media/uploads",

    #"MAIL_HOST"         => "",
    #"MAIL_SENDER"       => "",
    #"MAIL_USERNAME"     => "",
    #"MAIL_PASSWORD"     => ""
]);

?>