# MVC
Model View Controller (MVC) design pattern for simple web applications.

### Features

- **Default Pages for Home, Login, Signup, Recovery, Verification, Account and 404-Errors:** Supports simple customizations through template files (.tpl) similar to Smarty.

- **Caching-Engine:** Pages are automatically cached for improved performance, reducing server load by serving cached content when appropriate.

- **User Roles:** Supports the implementation of user roles, define and manage different access levels and permissions for users.

- **Account Recovery:** Users can recover their accounts through a user-friendly recovery process, they can regain access to their accounts in case of forgotten passwords or other issues.

- **Account Verification:**  Includes a built-in function to verify Accounts via E-Mail, enhancing security and trustworthiness in user registration.

- **Security Mechanisms:** The package implements modern security measures to protect against potential attacks. This includes cooldown periods for repeated incorrect or unauthorized inputs and the verification of action tokens to prevent malicious actions.

- **Multi Language Support**

- **2-Factor Authentication**

- **More Features soon..**

### Preview
![alt preview](https://github.com/fabiodoppio/mvc/blob/main/preview.jpg?raw=true)

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
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `app_accounts`( `id` int UNSIGNED NOT NULL, `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `role` int UNSIGNED NOT NULL, `registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; INSERT INTO `app_accounts` (`id`, `username`, `email`, `password`, `token`, `role`, `registered`, `lastaction`) VALUES (1000, 'admin', 'someone@example.com', '$2y$10$mF/1IeSTLohx/J35LYnEoueV50p3g9EOgnfADE0E7seJw127fHzY2', 'deP5E5KznHsLl0TMeLyvbndNg7KEky6W', 8, '2023-11-29 00:00:00', '2023-11-29 00:00:00'); CREATE TABLE `app_accounts_meta` ( `id` int UNSIGNED NOT NULL, `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_accounts_log`( `id` int UNSIGNED NOT NULL, `event` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; ALTER TABLE `app_accounts` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`); ALTER TABLE `app_accounts_meta` ADD PRIMARY KEY (`id`,`name`); ALTER TABLE `app_accounts_log` ADD PRIMARY KEY(`id`,`event`,`timestamp`); ALTER TABLE `app_accounts` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001; ALTER TABLE `app_accounts_meta` ADD CONSTRAINT `app_accounts_meta_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `app_accounts_log` ADD CONSTRAINT `app_accounts_log_ibfk_1` FOREIGN KEY(`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; COMMIT;
```


### Your .htaccess schould look like this:

```
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteRule ^index\.php$ - [L]
    RewriteRule ^(.*)/$ /$1 [R=301,L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [NC,QSA,L]
</IfModule>
```


Usage
=====

The simplest usage to create an App would be as follows in your _index.php_:

```php
<?php

require_once __DIR__.'/app/vendor/autoload.php';

MVC\App::config([
    "APP_URL"           => "https://",              // [REQUIRED] url to your app, no trailing slash
    #"APP_NAME"         => "My App",                // [OPTIONAL] name of your app
    #"APP_TITLE"        => "",                      // [OPTIONAL] title of your start page
    #"APP_AUTHOR"       => "",                      // [OPTIONAL] author of your app
    #"APP_DESCRIPTION"  => "",                      // [OPTIONAL] description of your app
    #"APP_LANGUAGE"     => "en_EN.utf8",            // [OPTIONAL] (server-)language of your app
    #"APP_LANGUAGES"    => [],                      // [OPTIONAL] available (server-)languages
    #"APP_CRON"         => false,                   // [OPTIONAL] de/activates cronjob
    #"APP_LOGIN"        => true,                    // [OPTIONAL] de/activates login (except admins)
    #"APP_SIGNUP"       => true,                    // [OPTIONAL] de/activates signup
    #"APP_MAINTENANCE"  => false,                   // [OPTIONAL] de/activates maintenance mode (except admins)
    #"APP_BADWORDS"     => [],                      // [OPTIONAL] forbidden words for usernames or messages

    "SALT_COOKIE"       => "",                      // [REQUIRED] randomized hash for security reasons
    "SALT_TOKEN"        => "",                      // [REQUIRED] randomized hash for security reasons
    "SALT_CACHE"        => "",                      // [REQUIRED] randomized hash for security reasons
    
    "DB_HOST"           => "",                      // [OPTIONAL] hostname to your mysql server
    "DB_USERNAME"       => "",                      // [OPTIONAL] username to your mysql server
    "DB_PASSWORD"       => "",                      // [OPTIONAL] password to your mysql server
    "DB_DATABASE"       => "",                      // [OPTIONAL] database to your mysql server

    "MAIL_HOST"         => "",                      // [OPTIONAL] hostname to your mail server
    "MAIL_SENDER"       => "",                      // [OPTIONAL] sender email address for system emails
    "MAIL_RECEIVER"     => "",                      // [OPTIONAL] receiver email address for contact form
    "MAIL_USERNAME"     => "",                      // [OPTIONAL] username to your mail server
    "MAIL_PASSWORD"     => "",                      // [OPTIONAL] password to your mail server
    #"MAIL_ENCRYPT"     => "ssl",                   // [OPTIONAL] ssl or tsl for encryption
    #"MAIL_PORT"        => "465,                    // [OPTIONAL] port to your mail server

    "DIR_ROOT"          => "/var/www"               // [REQUIRED] path to your root directory, no trailing slash
    #"DIR_CLASSES"      => "/app/classes",          // [OPTIONAL] path to your custom or extended classes
    #"DIR_ASSETS"       => "/app/assets",           // [OPTIONAL] path to your assets
    #"DIR_FONTS"        => "/app/assets/fonts",     // [OPTIONAL] path to your fonts
    #"DIR_SCRIPTS"      => "/app/assets/scripts",   // [OPTIONAL] path to your .js scripts
    #"DIR_STYLES"       => "/app/assets/styles",    // [OPTIONAL] path to your .css styles
    #"DIR_LOCALE"       => "/app/locale",           // [OPTIONAL] path to your locale .mo/.po files
    #"DIR_VENDOR"       => "/app/vendor",           // [OPTIONAL] path to your third-party libraries
    #"DIR_VIEWS"        => "/app/views",            // [OPTIONAL] path to your template files
    #"DIR_CACHE"        => "/app/cache",            // [OPTIONAL] path to your cache files
    #"DIR_MEDIA"        => "/app/media"             // [OPTIONAL] path to your media files
]);

MVC\App::init();

?>
```
You can now log in at https://yourdomain/login

Username: **admin**
Password: **admin123**

Don't forget to change your username and password!

### Debug mode

_index.php_:
```php
MVC\App::debug();
```

### Adding Pages
_index.php_:
```php
MVC\App::page([
    "slug"          => "/privacy",              // Regular Expression of your page slug
    "title"         => "Privacy Policy", 
    "description"   => "This is a custom page",
    "robots"        => "noindex, nofollow",
    "canonical"     => "/privacy",
    "class"         => "page privacy",
    "template"      => "/privacy.tpl"
]);
```

### Working with Templates

You can overwrite templates by simply placing them into your _views_ directory.
In your template files, you can use simple Smarty code. For example to include a file:

```smarty
{% include /_includes/mytemplate.tpl %}
```

..or to display a variable:

```smarty
{{$myvar}}
```

It's also allowed to use PHP code like this:

```smarty
{% myfunction(); %}
```

..or this:

```smarty
{% if (Condition): %}
    My Text
{% endif; %}
```

If you want to output a translated text, you can write your texts like this: 

```smarty
 {{"My text"}}
```

or

```smarty
    {{"My %s text", $myvar}}
```
    
(But don't forget to update your language files in your _locale_ directory!)


***Detailed documentation will coming soon..***