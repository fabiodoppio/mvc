# mvc
Model View Controller (MVC) design pattern for simple web applications.

Installation
============

Official installation method is via composer and its packagist package [fabiodoppio/mvc](https://packagist.org/packages/fabiodoppio/mvc).

```
$ composer require fabiodoppio/mvc
```

SQL-Statements for your Database:

```sql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `app_accounts`( `id` int UNSIGNED NOT NULL, `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `role` int UNSIGNED NOT NULL, `registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_accounts_watchlist` ( `id` int UNSIGNED NOT NULL, `request` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `detected` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_badwords` ( `id` int UNSIGNED NOT NULL, `badword` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_roles` ( `id` int UNSIGNED NOT NULL, `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; INSERT INTO `app_roles` (`id`, `name`) VALUES (1, 'Gesperrt'), (2, 'Deaktiviert'), (3, 'Besucher:in'), (4, 'Benutzer:in'), (5, 'Verifiziert'), (6, 'Moderator:in'), (7, 'Administrator:in'); ALTER TABLE `app_accounts` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`), ADD KEY `role` (`role`); ALTER TABLE `app_accounts_watchlist` ADD PRIMARY KEY (`id`,`detected`); ALTER TABLE `app_badwords` ADD PRIMARY KEY (`id`); ALTER TABLE `app_roles` ADD PRIMARY KEY (`id`); ALTER TABLE `app_accounts` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT; ALTER TABLE `app_badwords` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT; ALTER TABLE `app_roles` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8; ALTER TABLE `app_accounts` ADD CONSTRAINT `app_accounts_ibfk_1` FOREIGN KEY (`role`) REFERENCES `app_roles` (`id`) ON UPDATE CASCADE; ALTER TABLE `app_accounts_watchlist` ADD CONSTRAINT `app_accounts_watchlist_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; COMMIT;
```


Your .htaccess schould look like this:

```
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteBase /

    RewriteRule ^404$ index.php?request=index/notFound [NC,L]
    RewriteRule ^(account)\/([a-zA-Z0-9]+)\/?$ index.php?request=index/account/$1 [NC,QSA,L]
    RewriteRule ^(?!home)([a-zA-Z0-9]+)\/?$ index.php?request=index/$1 [NC,QSA,L]
    
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ https://%{HTTP_HOST} [R=301,NC,L]

    ErrorDocument 403 https://%{HTTP_HOST}/404
    ErrorDocument 404 https://%{HTTP_HOST}/404
</IfModule>
```


Usage
=====

The simplest usage to create an App would be as follows:

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

\Classes\App::init([
    "APP_URL"           => "https://",
    "APP_NAME"          => "",
    #"APP_AUTHOR"       => "",
    #"APP_DESCRIPTION"  => "",
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
    #"DIR_VENDOR"       => "/app/vendor",
    #"DIR_VIEWS"        => "/app/views",
    #"DIR_CACHE"        => "/app/cache",
    #"DIR_MEDIA"        => "/app/media",
    #"DIR_UPLOADS"      => "/app/media/uploads",

    #"MAIL_HOST"         => "",
    #"MAIL_SENDER"       => "",
    #"MAIL_USERNAME"     => "",
    #"MAIL_PASSWORD"     => ""
]);

```
