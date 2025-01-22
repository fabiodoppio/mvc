# MVC
Model View Controller (MVC) design pattern for simple web applications.

### Features

- **Default Pages for Home, Login, Signup, Recovery, Verification, Account and 404-Errors:** Supports simple customizations through template files (.tpl) similar to Smarty.

- **Caching-Engine:** Pages are automatically cached for improved performance, reducing server load by serving cached content when appropriate.

- **Access Levels:** Supports the implementation of user roles, define and manage different access levels and permissions for users.

- **Account Recovery:** Users can recover their accounts through a user-friendly recovery process, they can regain access to their accounts in case of forgotten passwords or other issues.

- **Account Verification:**  Includes a built-in function to verify Accounts via E-Mail, enhancing security and trustworthiness in user registration.

- **Security Mechanisms:** The package implements modern security measures to protect against potential attacks. This includes cooldown periods for repeated incorrect or unauthorized inputs and the verification of action tokens to prevent malicious actions.

- **Multi Language Support**

- **2-Factor Authentication**

- **Administration Pages**

- **Newsletter**

- **More Features soon..**

### Preview
![alt preview](https://github.com/fabiodoppio/mvc/blob/main/preview.jpg?raw=true)

Installation
============

Official installation method is via composer and its packagist package [fabiodoppio/mvc](https://packagist.org/packages/fabiodoppio/mvc).

```
$ composer require fabiodoppio/mvc
```

Just copy the _example_ directory to your preferred location and run:
```
$ composer update
```

Create the tables in your database with the SQL statements below, add your credentials to the _.env_ file and point your domain to the _public_ directory. That's it!

### SQL-Statements for your Database:

```sql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `app_accounts` ( `id` int UNSIGNED NOT NULL, `username` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `email` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `password` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `role` int UNSIGNED NOT NULL, `registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; INSERT INTO `app_accounts` (`id`, `username`, `email`, `password`, `token`, `role`, `registered`, `lastaction`) VALUES (1000, 'admin', 'someone@example.com', '$2y$10$mF/1IeSTLohx/J35LYnEoueV50p3g9EOgnfADE0E7seJw127fHzY2', 'deP5E5KznHsLl0TMeLyvbndNg7KEky6W', 8, '2023-11-29 00:00:00', '2023-11-29 00:00:00'); CREATE TABLE `app_accounts_log` ( `id` int UNSIGNED NOT NULL, `event` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_accounts_meta` ( `id` int UNSIGNED NOT NULL, `name` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `value` text COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_filters_badwords` ( `id` int UNSIGNED NOT NULL, `badword` varchar(64) COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_filters_providers` ( `id` int UNSIGNED NOT NULL, `provider` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_pages` ( `id` int UNSIGNED NOT NULL, `slug` varchar(64) COLLATE utf8mb4_general_ci NOT NULL, `template` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL, `requirement` tinyint(1) NOT NULL DEFAULT '0', `maintenance` tinyint(1) NOT NULL DEFAULT '1', `active` tinyint(1) NOT NULL DEFAULT '1' ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; INSERT INTO `app_pages` (`id`, `slug`, `template`, `requirement`, `maintenance`, `active`) VALUES (1, '/imprint', '/imprint.tpl', 0, 0, 1), (2, '/privacy', '/privacy.tpl', 0, 0, 1), (3, '/terms', '/terms.tpl', 0, 0, 1); CREATE TABLE `app_pages_meta` ( `id` int UNSIGNED NOT NULL, `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; INSERT INTO `app_pages_meta` (`id`, `name`, `value`) VALUES (1, 'class', 'page imprint'), (1, 'description', 'This is a custom page'), (1, 'robots', 'noindex, nofollow'), (1, 'title', 'Imprint'), (2, 'class', 'page privacy'), (2, 'description', 'This is a custom page'), (2, 'robots', 'noindex, nofollow'), (2, 'title', 'Privacy Policy'), (3, 'class', 'page terms'), (3, 'description', 'This is a custom page'), (3, 'robots', 'noindex, nofollow'), (3, 'title', 'Terms of Service'); ALTER TABLE `app_accounts` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`); ALTER TABLE `app_accounts_log` ADD PRIMARY KEY (`id`,`event`,`timestamp`); ALTER TABLE `app_accounts_meta` ADD PRIMARY KEY (`id`,`name`); ALTER TABLE `app_filters_badwords` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `badword` (`badword`); ALTER TABLE `app_filters_providers` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `provider` (`provider`); ALTER TABLE `app_pages` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`); ALTER TABLE `app_pages_meta` ADD PRIMARY KEY (`id`,`name`); ALTER TABLE `app_accounts` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001; ALTER TABLE `app_filters_badwords` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; ALTER TABLE `app_filters_providers` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; ALTER TABLE `app_pages` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4; ALTER TABLE `app_accounts_log` ADD CONSTRAINT `app_accounts_log_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `app_accounts_meta` ADD CONSTRAINT `app_accounts_meta_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `app_pages_meta` ADD CONSTRAINT `app_pages_meta_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; COMMIT;
```

### Minimum Configuration

In your _.env_ file you have to configure the following settings:

```
[Directories]
DIR_ROOT = "/var/www"                           # path to your root directory, no trailing slash

[General]
APP_URL = "https://"                            # url to your app, no trailing slash

[Security]
SALT_COOKIE = "USE A SALT OR HASH GENERATOR!"   # randomized hash for security reasons
SALT_TOKEN = "USE A SALT OR HASH GENERATOR!"    # randomized hash for security reasons
SALT_CACHE = "USE A SALT OR HASH GENERATOR!"    # randomized hash for security reasons

[Database]
DB_HOST = "localhost"                           # hostname to your sql server
DB_USERNAME = "username"                        # username to your sql server
DB_PASSWORD = "********"                        # password to your sql server
DB_DATABASE = "my_app"                          # database to your sql server

[Email]
MAIL_HOST = "localhost"                         # hostname to your mail server
MAIL_SENDER = "someone@example.com"             # sender email address for system emails
MAIL_RECEIVER = "someone@example.com"           # receiver email address for contact form
MAIL_USERNAME = "username"                      # username to your mail server
MAIL_PASSWORD = "********"                      # password to your mail server
```

Usage
=====

You can now log in at https://yourdomain/login

Username: **admin**
Password: **admin123**

Don't forget to change your username and password!

### Further Configuration

In your _.env_ file you can configure the following settings:
```
[Debug]
APP_DEBUG = "false"                             # de/activates error reporting and caching

[Directories]
DIR_ROOT = "/var/www"                           # path to your root directory, no trailing slash
DIR_CACHE = "/app/cache"                        # path to your cache files, no trailing slash
DIR_CLASSES = "/app/classes"                    # path to your custom or extended classes, no trailing slash
DIR_LOCALE = "/app/locale"                      # path to your locale .mo/.po files, no trailing slash
DIR_VENDOR = "/app/vendor"                      # path to your third-party libraries, no trailing slash
DIR_VIEWS = "/app/views"                        # path to your template files, no trailing slash
DIR_MEDIA = "/public/media"                     # path to your public media files, no trailing slash

[General]
APP_URL = "https://"                            # url to your app, no trailing slash
APP_NAME = "My App"                             # name of your app
APP_TITLE = ""                                  # title of your start page
APP_AUTHOR = ""                                 # author of your app
APP_DESCRIPTION = ""                            # description of your app
APP_TIMEZONE = "Europe/Berlin"                  # timezone for the app
APP_LANGUAGE = "en_GB.utf8"                     # (server-)language of your app
APP_LANGUAGES[] = "en_GB.utf8"                  # english as an selectable (server-)language
APP_LANGUAGES[] = "de_DE.utf8"                  # german as an selectable (server-)language
APP_MAINTENANCE = "false"                       # de/activates maintenance mode (except admins)
APP_CRON = "true"                               # de/activates cronjob
APP_LOGIN = "true"                              # de/activates login (except admins)
APP_SIGNUP = "true"                             # de/activates signup

[Security]
SALT_COOKIE = "USE A SALT OR HASH GENERATOR!"   # randomized hash for security reasons
SALT_TOKEN = "USE A SALT OR HASH GENERATOR!"    # randomized hash for security reasons
SALT_CACHE = "USE A SALT OR HASH GENERATOR!"    # randomized hash for security reasons

[Database]
DB_HOST = "localhost"                           # hostname to your sql server
DB_USERNAME = "username"                        # username to your sql server
DB_PASSWORD = "********"                        # password to your sql server
DB_DATABASE = "my_app"                          # database to your sql server

[Email]
MAIL_HOST = "localhost"                         # hostname to your mail server
MAIL_SENDER = "someone@example.com"             # sender email address for system emails
MAIL_RECEIVER = "someone@example.com"           # receiver email address for contact form
MAIL_USERNAME = "username"                      # username to your mail server
MAIL_PASSWORD = "********"                      # password to your mail server
MAIL_ENCRYPT = "ssl"                            # ssl or tsl for encryption
MAIL_PORT = "465"                               # port to your mail server

[Notifications]
NOTIFY_RECEIVED = "true"                        # de/activates notification email for a received contact request
NOTIFY_WELCOME = "true"                         # de/activates welcome email for a newly created account
NOTIFY_NEWACCOUNT = "true"                      # de/activates notification email about a new account
NOTIFY_DEACTIVATED = "true"                     # de/activates notification email about a deactivated account
```

### Quick Start: Working with Templates

You can add templates by simply placing them into your _views_ directory.
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


### Quick Start: Processing Requests

You can execute any method from any controller by submitting a form with the _data-request_ attribute:

```html
<form data-request="my/example">
    <input type="text" name="value" value="xyz"/>
    <input type="submit" value="Call my/example"/>
</form>
```
..or by clicking any link with the appropriate attributes:

```html
<a href="#" data-request="my/example" data-value="xyz">Call my/example</a>
```

The scheme for your _data-request_ attribute is always the same: Name of your Controller / Name of your Action, e.g. _my/example_
You can find the controller and action of this specific example in your _classes/Controllers_ directory.


### Detailed documentation will coming soon..