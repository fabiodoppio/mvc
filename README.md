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
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `app_accounts`( `id` int(10) UNSIGNED NOT NULL, `username` varchar(64) NOT NULL, `email` varchar(64) NOT NULL, `password` varchar(64) NOT NULL, `token` varchar(64) NOT NULL, `role` int(10) UNSIGNED NOT NULL, `registered` datetime NOT NULL DEFAULT current_timestamp(), `lastaction` datetime NOT NULL DEFAULT current_timestamp()) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; INSERT INTO `app_accounts` (`id`, `username`, `email`, `password`, `token`, `role`, `registered`, `lastaction`) VALUES (1000, 'admin', 'someone@example.com', '$2y$10$mF/1IeSTLohx/J35LYnEoueV50p3g9EOgnfADE0E7seJw127fHzY2', 'deP5E5KznHsLl0TMeLyvbndNg7KEky6W', 8, '2023-11-29 00:00:00', '2023-11-29 00:00:00'); CREATE TABLE `app_accounts_log` ( `id` int(10) UNSIGNED NOT NULL, `event` varchar(64) NOT NULL, `timestamp` datetime NOT NULL DEFAULT current_timestamp() ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_accounts_meta` ( `id` int(10) UNSIGNED NOT NULL, `name` varchar(64) NOT NULL, `value` text NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_badwords` ( `id` int(10) UNSIGNED NOT NULL, `badword` varchar(64) NOT NULL, `timestamp` datetime NOT NULL DEFAULT current_timestamp() ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; CREATE TABLE `app_pages` ( `id` int(10) UNSIGNED NOT NULL, `slug` varchar(64) NOT NULL, `title` varchar(256) DEFAULT NULL, `description` varchar(512) DEFAULT NULL, `robots` varchar(64) DEFAULT 'index, follow', `canonical` varchar(64) DEFAULT NULL, `class` varchar(64) DEFAULT 'page', `template` varchar(128) DEFAULT NULL, `active` tinyint(1) NOT NULL DEFAULT 1 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; INSERT INTO `app_pages` (`id`, `slug`, `title`, `description`, `robots`, `canonical`, `class`, `template`, `active`) VALUES (1, '/imprint', 'Impressum', 'This is a custom page', 'noindex, nofollow', '/imprint', 'page imprint', '/imprint.tpl', 1), (2, '/privacy', 'Privacy Policy', 'This is a custom page', 'noindex, nofollow', '/privacy', 'page privacy', '/privacy.tpl', 1), (3, '/terms', 'Terms of Service', 'This is a custom page', 'noindex, nofollow', '/terms', 'page terms', '/terms.tpl', 1); ALTER TABLE `app_accounts` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`); ALTER TABLE `app_accounts_log` ADD PRIMARY KEY (`id`,`event`,`timestamp`); ALTER TABLE `app_accounts_meta` ADD PRIMARY KEY (`id`,`name`); ALTER TABLE `app_badwords` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `badword` (`badword`); ALTER TABLE `app_pages` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`); ALTER TABLE `app_accounts` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001; ALTER TABLE `app_badwords` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT; ALTER TABLE `app_pages` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4; ALTER TABLE `app_accounts_log` ADD CONSTRAINT `app_accounts_log_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `app_accounts_meta` ADD CONSTRAINT `app_accounts_meta_ibfk_1` FOREIGN KEY (`id`) REFERENCES `app_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; COMMIT;
```

### Minimum Configuration

In your _.env_ file you have to configure the following settings:

```
[General]
APP_URL = "https://"                            # url to your app, no trailing slash

[Directories]
DIR_ROOT = "/var/www"                           # path to your root directory, no trailing slash

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

[General]
APP_URL = "https://"                            # url to your app, no trailing slash
APP_NAME = "My App"                             # name of your app
APP_TITLE = ""                                  # title of your start page
APP_AUTHOR = ""                                 # author of your app
APP_DESCRIPTION = ""                            # description of your app
APP_TIMEZONE = "Europe/Berlin"                  # timezone for the app
APP_LANGUAGE = "en_EN.utf8"                     # (server-)language of your app
APP_LANGUAGES[] = "en_EN.utf8"                  # available (server-)languages
APP_LANGUAGES[] = "de_DE.utf8"                  # available (server-)languages
APP_MAINTENANCE = "false"                       # de/activates maintenance mode (except admins)
APP_CRON = "true"                               # de/activates cronjob
APP_LOGIN = "true"                              # de/activates login (except admins)
APP_SIGNUP = "true"                             # de/activates signup
APP_WELCOME = "true"                            # de/activates welcome mail for new accounts

[Directories]
DIR_ROOT = "/var/www"                           # path to your root directory, no trailing slash
DIR_CACHE = "/app/cache"                        # path to your cache files
DIR_CLASSES = "/app/classes"                    # path to your custom or extended classes
DIR_LOCALE = "/app/locale"                      # path to your locale .mo/.po files
DIR_VENDOR = "/app/vendor"                      # path to your third-party libraries
DIR_VIEWS = "/app/views"                        # path to your template files
DIR_FONTS = "/public/assets/fonts"              # path to your public fonts
DIR_SCRIPTS = "/public/assets/scripts"          # path to your public .js scripts
DIR_STYLES = "/public/assets/styles"            # path to your public .css styles
DIR_MEDIA = "/public/media"                     # path to your public media files

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

[Rules]
RULE_UN_REGEX = "/[^A-Za-z0-9]+/";              # regex for allowed characters in usernames
RULE_UN_LENGTH = "18";                          # max length of characters in usernames
RULE_MSG_LENGTH = "250";                        # max length of characters in messages (not in use)
RULE_PWD_LENGTH = "8";                          # min length of characters in passwords
RULE_ATT_FILESIZE = "12582912";                 # max filesize in bytes for mail attachments
RULE_AVA_FILESIZE = "3145728";                  # max filesize in bytes for account avatars
```

### Working with Templates

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


### Detailed documentation will coming soon..