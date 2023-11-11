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
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `app_accounts`( `id` int UNSIGNED NOT NULL, `username` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `email` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `password` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `role` int UNSIGNED NOT NULL, `registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_accounts_meta` ( `id` int UNSIGNED NOT NULL, `name` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `value` text COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_accounts_watchlist` ( `id` int UNSIGNED NOT NULL, `request` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `detected` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_badwords` ( `badword` varchar(64) COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_config` ( `name` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `value` text COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_pages` ( `id` int UNSIGNED NOT NULL, `slug` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `title` varchar(256) COLLATE utf8mb4_general_ci NOT NULL, `description` varchar(512) COLLATE utf8mb4_general_ci NOT NULL, `robots` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `template` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `role` int UNSIGNED NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; ALTER TABLE `app_accounts` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`); ALTER TABLE `app_accounts_meta` ADD PRIMARY KEY (`id`,`name`); ALTER TABLE `app_accounts_watchlist` ADD PRIMARY KEY (`id`,`detected`); ALTER TABLE `app_badwords` ADD PRIMARY KEY (`badword`); ALTER TABLE `app_config` ADD PRIMARY KEY (`name`); ALTER TABLE `app_pages` ADD PRIMARY KEY (`id`) USING BTREE; ALTER TABLE `app_accounts` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT; ALTER TABLE `app_pages` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT; ALTER TABLE `app_accounts_meta` ADD CONSTRAINT `app_accounts_meta_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `app_accounts_watchlist` ADD CONSTRAINT `app_accounts_watchlist_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; COMMIT; 
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
    "APP_URL"           => "https://",              // [REQUIRED] url to your app, no trailing slash
    #"APP_NAME"         => "",                      // [OPTIONAL] name of your app
    #"APP_TITLE"        => "",                      // [OPTIONAL] title of your start page
    #"APP_AUTHOR"       => "",                      // [OPTIONAL] author of your app
    #"APP_DESCRIPTION"  => "",                      // [OPTIONAL] description of your app
    #"APP_LANGUAGE"     => "en_EN.utf8",            // [OPTIONAL] your prefered (server-)language
    #"APP_DEBUG"        => false,                   // [OPTIONAL] de/activates debug mode
    #"APP_LOGIN"        => true,                    // [OPTIONAL] de/activates login (except admins)
    #"APP_SIGNUP"       => false,                   // [OPTIONAL] de/activates signup
    #"APP_CRONJOB"      => false,                   // [OPTIONAL] de/activates cronjob
    #"APP_MAINTENANCE"  => false,                   // [OPTIONAL] de/activates maintenance mode (except admins)
   
    "SALT_COOKIE"       => "",                      // [REQUIRED] randomized hash for security reasons
    "SALT_TOKEN"        => "",                      // [REQUIRED] randomized hash for security reasons
    "SALT_CACHE"        => "",                      // [REQUIRED] randomized hash for security reasons
    "AUTH_CRON"         => "",                      // [REQUIRED] randomized hash for security reasons
    
    "DB_HOST"           => "",                      // [REQUIRED] hostname to your mysql server
    "DB_USERNAME"       => "",                      // [REQUIRED] username to your mysql server
    "DB_PASSWORD"       => "",                      // [REQUIRED] password to your mysql server
    "DB_DATABASE"       => "",                      // [REQUIRED] database to your mysql server

    "DIR_ROOT"          => "/var/www"               // [REQUIRED] path to your root directory, no trailing slash
    #"DIR_ASSETS"       => "/app/assets",           // [OPTIONAL] path to your asset files, such as styles and scripts
    #"DIR_CLASSES"      => "/app/classes",          // [OPTIONAL] path to your custom or extended classes
    #"DIR_FONTS"        => "/app/assets/fonts",     // [OPTIONAL] path to your fonts
    #"DIR_SCRIPTS"      => "/app/assets/scripts",   // [OPTIONAL] path to your .js scripts
    #"DIR_STYLES"       => "/app/assets/styles",    // [OPTIONAL] path to your .css styles
    #"DIR_LOCALE"       => "/app/locale",           // [OPTIONAL] path to your locale .mo/.po files
    #"DIR_VENDOR"       => "/app/vendor",           // [OPTIONAL] path to your vendor packages
    #"DIR_VIEWS"        => "/app/views",            // [OPTIONAL] path to your template files
    #"DIR_CACHE"        => "/app/cache",            // [OPTIONAL] path to your cache files
    #"DIR_MEDIA"        => "/app/media",            // [OPTIONAL] path to your media files
    #"DIR_UPLOADS"      => "/app/media/uploads",    // [OPTIONAL] path to your user uploads
]);

?>
```

***Detailed documentation will coming soon..***
