<?php

require_once __DIR__.'/app/vendor/autoload.php';

MVC\App::config([
    "APP_URL"           => "https://mydomain.com",
    "DIR_ROOT"          => "/var/www",

    "APP_LANGUAGES"     => [
        "English"           => "en_EN.utf8",
        "Deutsch"           => "de_DE.utf8"
    ],

    // DON'T USE THESE!
    "SALT_COOKIE"       => "elo0 zaS)AxZe-h@i#e?CX*Q&_,5=>7ms15$5^znGtlpQMuvH@yCe1*n<z4r?}7", 
    "SALT_TOKEN"        => "QfC33ibh!mUu)AZ.Lwc{x?_yLy*^%W =?:M 5OPj<i[=x{8sRT@FTeVQf+h?Pcv",
    "SALT_CACHE"        => "b&-_zkVQ,T)/DJ@|LC7mx?Vm!Ml6`U)mmv!#s9j%pNmGLDRMck@t(2XY6Sr[&s_",

    "DB_HOST"           => "localhost",
    "DB_USERNAME"       => "username",
    "DB_PASSWORD"       => "************",
    "DB_DATABASE"       => "my_app",

    "MAIL_HOST"         => "mail.mydomain.com",
    "MAIL_SENDER"       => "noreply@mydomain.com",
    "MAIL_RECEIVER"     => "info@mydomain.com",
    "MAIL_USERNAME"     => "info@mydomain.com",
    "MAIL_PASSWORD"     => "************"
]);

MVC\App::page([
    "slug"          => "/imprint",
    "title"         => "Imprint",
    "description"   => "This is a custom page",
    "robots"        => "noindex, nofollow",
    "canonical"     => "/imprint",
    "class"         => "page imprint",
    "template"      => "/imprint.tpl"
]);
MVC\App::page([
    "slug"          => "/privacy",
    "title"         => "Privacy Policy",
    "description"   => "This is a custom page",
    "robots"        => "noindex, nofollow",
    "canonical"     => "/privacy",
    "class"         => "page privacy",
    "template"      => "/privacy.tpl"
]);
MVC\App::page([
    "slug"          => "/terms",
    "title"         => "Terms of Service",
    "description"   => "This is a custom page",
    "robots"        => "noindex, nofollow",
    "canonical"     => "/terms",
    "class"         => "page terms",
    "template"      => "/terms.tpl"
]);

MVC\App::init();

?>