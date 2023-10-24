# mvc
Model View Controller (MVC) design pattern for simple web applications.

### Features

- **Default Pages for Home, Login, Signup, Recovery, Verification, Account and 404-Errors:** Supports simple customizations through template files (.tpl) similar to Smarty.

- **Caching-Engine:** Pages are automatically cached for improved performance, reducing server load by serving cached content when appropriate.

- **User Roles:** Supports the implementation of user roles, define and manage different access levels and permissions for users.

- **Account Recovery:** Users can recover their accounts through a user-friendly recovery process, they can regain access to their accounts in case of forgotten passwords or other issues.

- **Account Verification:**  Includes a built-in function to verify Accounts via E-Mail, enhancing security and trustworthiness in user registration.

- **File Uploads:** Users can easily upload files through a secure and user-friendly interface, supporting various file formats and sizes.

- **Security Mechanisms:** The package implements modern security measures to protect against potential attacks. This includes cooldown periods for repeated incorrect or unauthorized inputs and the verification of action tokens to prevent malicious actions.

- **Multi Language Support**

- **Admin Backend**

- **More Features soon..**


Installation
============

Official installation method is via composer and its packagist package [fabiodoppio/mvc](https://packagist.org/packages/fabiodoppio/mvc).

```
$ composer require fabiodoppio/mvc
```

..or just copy the _example_ directory and run:
```
$ composer update
```

### SQL-Statements for your Database:

```sql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `app_accounts`( `id` int UNSIGNED NOT NULL, `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `role` int UNSIGNED NOT NULL, `registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_accounts_meta` ( `id` int UNSIGNED NOT NULL, `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `value` text COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_accounts_watchlist` ( `id` int UNSIGNED NOT NULL, `request` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `detected` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_badwords` ( `id` int UNSIGNED NOT NULL, `badword` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_config` ( `name` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `value` text COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_pages` ( `slug` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `title` varchar(256) COLLATE utf8mb4_general_ci NOT NULL, `description` varchar(512) COLLATE utf8mb4_general_ci NOT NULL, `robots` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `template` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `role` int UNSIGNED NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; ALTER TABLE `app_accounts` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`); ALTER TABLE `app_accounts_meta` ADD PRIMARY KEY (`id`,`name`); ALTER TABLE `app_accounts_watchlist` ADD PRIMARY KEY (`id`,`detected`); ALTER TABLE `app_badwords` ADD PRIMARY KEY (`id`); ALTER TABLE `app_config` ADD PRIMARY KEY (`name`); ALTER TABLE `app_pages` ADD PRIMARY KEY (`slug`); ALTER TABLE `app_accounts` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT; ALTER TABLE `app_badwords` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT; ALTER TABLE `app_accounts_meta` ADD CONSTRAINT `app_accounts_meta_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `app_accounts_watchlist` ADD CONSTRAINT `app_accounts_watchlist_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; COMMIT;
```


### Your .htaccess schould look like this:

```
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteBase /
    
    RewriteRule ^index\.php$ - [L]
    RewriteRule ^(.*)/$ /$1 [R=301,L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php [NC,QSA,L]
</IfModule>
```


Usage
=====

The simplest usage to create an App would be as follows in your _index.php_:

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

MVC\App::init([
    "APP_URL"           => "https://",
    "APP_NAME"          => "",
    #"APP_TITLE"        => "",
    #"APP_AUTHOR"       => "",
    #"APP_DESCRIPTION"  => "",
    #"APP_LANGUAGE"     => "de_DE",
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
    #"DIR_VIEWS"        => "/app/views/de_DE",
    #"DIR_CACHE"        => "/app/cache",
    #"DIR_MEDIA"        => "/app/media",
    #"DIR_UPLOADS"      => "/app/media/uploads",

    #"MAIL_HOST"         => "",
    #"MAIL_SENDER"       => "",
    #"MAIL_USERNAME"     => "",
    #"MAIL_PASSWORD"     => "",

    #"META_PROTECTED"    => "[\"username\"]"
]);

?>
```

***Detailed documentation will coming soon..***
