<?php

require_once __DIR__.'/app/vendor/autoload.php';

MVC\App::init([
    "APP_URL"           => "https://mydomain.com",
    "DIR_ROOT"          => "/var/www",

    // DON'T USE THESE!
    "SALT_COOKIE"       => "elo0 zaS)AxZe-h@i#e?CX*Q&_,5=>7ms15$5^znGtlpQMuvH@yCe1*n<z4r?}7", 
    "SALT_TOKEN"        => "QfC33ibh!mUu)AZ.Lwc{x?_yLy*^%W =?:M 5OPj<i[=x{8sRT@FTeVQf+h?Pcv",
    "SALT_CACHE"        => "b&-_zkVQ,T)/DJ@|LC7mx?Vm!Ml6`U)mmv!#s9j%pNmGLDRMck@t(2XY6Sr[&s_",
    "AUTH_CRON"         => "8bJ-u|oK,zwGP40aXiO,Y^#j;~Vos0(HY? p<Kbx&!gzk.Vu-=A2NB/kK4K3v>s",

    "DB_HOST"           => "localhost",
    "DB_USERNAME"       => "username",
    "DB_PASSWORD"       => "************",
    "DB_DATABASE"       => "my_app",

    "MAIL_HOST"         => "mail.mydomain.com",
    "MAIL_SENDER"       => "your@email.com",
    "MAIL_USERNAME"     => "username",
    "MAIL_PASSWORD"     => "************"
]);

?>