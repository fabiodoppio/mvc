<?php

/**
 * 
 *  MVC
 *  Model View Controller (MVC) design pattern for simple web applications.
 *
 *  @see     https://github.com/fabiodoppio/mvc
 *
 *  @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 *  @license https://opensource.org/license/mit/ MIT License
 * 
 */


namespace MVC\Controllers;

use MVC\Ajax      as Ajax;
use MVC\App       as App;
use MVC\Auth      as Auth;
use MVC\Database  as Database;
use MVC\Exception as Exception;
use MVC\Fairplay  as Fairplay;
use MVC\Models    as Model;
use MVC\Template  as Template;
use MVC\Upload    as Upload;

/**
 * 
 *  AdminController Class
 *
 *  The AdminController class is a specific controller handling administrative actions,
 *  such as managing settings, pages, accounts, and cache.
 * 
 */
class AdminController extends AccountController {

    /**
     * 
     *  Executes actions before the main action, including checking the user role.
     * 
     *  @since  2.0
     * 
     */
    public function beforeAction() {
        parent::beforeAction();
        if ($this->account->get("role") < Model\Account::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."), 1055);
    }

    /**
     * 
     *  Handles the settings action, allowing administrators to edit various application settings.
     * 
     *  @since  2.0
     *  @param  string  $request    The requested action
     * 
     */
    public function settingsAction(string $request) {
        switch($request) {
            case "admin/settings/edit":

                if (!empty($_POST["APP_DEBUG"]) && $_POST["APP_DEBUG"] != App::get("APP_DEBUG"))
                    App::set("APP_DEBUG", Fairplay::integer($_POST["APP_DEBUG"]));

                if (!empty($_POST["APP_URL"]) && $_POST["APP_URL"] != App::get("APP_URL"))
                    App::set("APP_URL", Fairplay::url($_POST["APP_URL"]));

                if (isset($_POST["APP_NAME"]) && $_POST["APP_NAME"] != App::get("APP_NAME"))
                    App::set("APP_NAME", Fairplay::string($_POST["APP_NAME"]));

                if (isset($_POST["APP_TITLE"]) && $_POST["APP_TITLE"] != App::get("APP_TITLE"))
                    App::set("APP_TITLE", Fairplay::string($_POST["APP_TITLE"]));

                if (isset($_POST["APP_AUTHOR"]) && $_POST["APP_AUTHOR"] != App::get("APP_AUTHOR"))
                    App::set("APP_AUTHOR", Fairplay::string($_POST["APP_AUTHOR"]));
                
                if (isset($_POST["APP_DESCRIPTION"]) && $_POST["APP_DESCRIPTION"] != App::get("APP_DESCRIPTION"))
                    App::set("APP_DESCRIPTION", Fairplay::string($_POST["APP_DESCRIPTION"]));

                if (!empty($_POST["APP_LANGUAGE"]) && $_POST["APP_LANGUAGE"] != App::get("APP_LANGUAGE"))
                    App::set("APP_LANGUAGE", Fairplay::string($_POST["APP_LANGUAGE"]));

                if (!empty($_POST["APP_THEME"]) && $_POST["APP_THEME"] != App::get("APP_THEME"))
                    App::set("APP_THEME", Fairplay::string($_POST["APP_THEME"]));
                
                if (isset($_POST["APP_LOGIN"]) && $_POST["APP_LOGIN"] != App::get("APP_LOGIN"))
                    App::set("APP_LOGIN", Fairplay::integer($_POST["APP_LOGIN"]));

                if (isset($_POST["APP_SIGNUP"]) && $_POST["APP_SIGNUP"] != App::get("APP_SIGNUP"))
                    App::set("APP_SIGNUP", Fairplay::integer($_POST["APP_SIGNUP"]));

                if (isset($_POST["CRON_ACTIVE"]) && $_POST["CRON_ACTIVE"] != App::get("CRON_ACTIVE"))
                    App::set("CRON_ACTIVE", Fairplay::integer($_POST["CRON_ACTIVE"]));

                if (isset($_POST["APP_MAINTENANCE"]) && $_POST["APP_MAINTENANCE"] != App::get("APP_MAINTENANCE"))
                    App::set("APP_MAINTENANCE", Fairplay::integer($_POST["APP_MAINTENANCE"]));

                if (isset($_POST["UPLOAD_FILE_SIZE"]) && $_POST["UPLOAD_FILE_SIZE"] != App::get("UPLOAD_FILE_SIZE"))
                    App::set("UPLOAD_FILE_SIZE", Fairplay::integer($_POST["UPLOAD_FILE_SIZE"]));

                if (isset($_POST["UPLOAD_FILE_TYPES"]) && $_POST["UPLOAD_FILE_TYPES"] != App::get("UPLOAD_FILE_TYPES"))
                    App::set("UPLOAD_FILE_TYPES", json_encode(array_map('trim', explode("\n", Fairplay::string($_POST["UPLOAD_FILE_TYPES"])))));

                if (isset($_POST["MAIL_HOST"]) && $_POST["MAIL_HOST"] != App::get("MAIL_HOST"))
                    App::set("MAIL_HOST", Fairplay::string($_POST["MAIL_HOST"]));

                if (isset($_POST["MAIL_SENDER"]) && $_POST["MAIL_SENDER"] != App::get("MAIL_SENDER"))
                    App::set("MAIL_SENDER", Fairplay::email($_POST["MAIL_SENDER"]));

                if (isset($_POST["MAIL_USERNAME"]) && $_POST["MAIL_USERNAME"] != App::get("MAIL_USERNAME"))
                    App::set("MAIL_USERNAME", Fairplay::string($_POST["MAIL_USERNAME"]));

                if (!empty($_POST["MAIL_PASSWORD"]) && $_POST["MAIL_PASSWORD"] != App::get("MAIL_PASSWORD"))
                    App::set("MAIL_PASSWORD", str_replace('=', '', base64_encode(Fairplay::string($_POST["MAIL_PASSWORD"]))));

                if (isset($_POST["APP_METAFIELDS"]) && $_POST["APP_METAFIELDS"] != App::get("APP_METAFIELDS"))
                    App::set("APP_METAFIELDS", json_encode(array_map('trim', explode("\n", Fairplay::string($_POST["APP_METAFIELDS"])))));
                
                if (isset($_POST["APP_BADWORDS"]) && $_POST["APP_BADWORDS"] != App::get("APP_BADWORDS"))
                    App::set("APP_BADWORDS", json_encode(array_map('trim', explode("\n", Fairplay::string($_POST["APP_BADWORDS"])))));

                if (isset($_POST["CUSTOM_JS"]) && $_POST["CUSTOM_JS"] != App::get("CUSTOM_JS"))
                    App::set("CUSTOM_JS", json_encode(array_map('trim', explode("\n", Fairplay::string($_POST["CUSTOM_JS"])))));

                if (isset($_POST["CUSTOM_CSS"]) && $_POST["CUSTOM_CSS"] != App::get("CUSTOM_CSS"))
                    App::set("CUSTOM_CSS", json_encode(array_map('trim', explode("\n", Fairplay::string($_POST["CUSTOM_CSS"])))));

                if (isset($_POST["name"]) && isset($_POST["value"]))
                    for($i = 0; $i < count($_POST["name"]); $i++)
                        if (!empty($_POST["name"][$i]) && isset($_POST["value"][$i])) {
                            if (in_array($_POST["name"][$i], array_keys(App::get_config())))
                                    throw new Exception(sprintf(_("You can not set %s as an additional field."), $_POST["name"][$i]), 1103);
                                
                                App::set(Fairplay::string($_POST["name"][$i]), Fairplay::string($_POST["value"][$i]));
                            }

                Ajax::add('.response', '<div class="success">'._("Changes successfully saved.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1056);
        }
    }

    /**
     * 
     *  Handles the page-related actions, including adding, editing, and deleting pages.
     * 
     *  @since  2.0
     *  @param  string  $request    The requested action
     * 
     */
    public function pageAction(string $request) {
        switch($request) {
            case "admin/page/add":

                if (empty($_POST["slug"]) || empty($_POST["title"]) && !isset($_POST["description"]) || !isset($_POST["robots"]) || empty($_POST["template"]) || empty($_POST["role"]))
                    throw new Exception(_("Required input not found."), 1057);

                if (!empty(Database::query("SELECT * FROM app_pages WHERE slug = ?", [$_POST["slug"]])[0]))
                        throw new Exception(_("This slug is already used."), 1058);

                Database::query("INSERT INTO app_pages (slug, title, description, robots, template, role) VALUES (?, ?, ?, ?, ?, ?)", [Fairplay::string($_POST["slug"]), Fairplay::string($_POST["title"]), Fairplay::string($_POST["description"]), Fairplay::string($_POST["robots"]), Fairplay::string($_POST["template"]), Fairplay::integer($_POST["role"])]);

                Ajax::add('.response', '<div class="success">'._("Page successfully added.").'</div>');
                Ajax::reload();

                break;
            case "admin/page/edit":
            
                if (empty($_POST["id"])) 
                    throw new Exception(_("Required input not found."), 1059);

                $page = new Model\Page(Fairplay::integer($_POST["id"]));
        
                if (!empty($_POST["title"]) && $_POST["title"] != $page->get("title")) {
                    $page->set("title", Fairplay::string($_POST["title"]));
                    Ajax::add('.admin.pages .list .list-item[data-id="'.$page->get("id").'"] .title', $_POST["title"]);
                }
                        
                if (!empty($_POST["slug"]) && $_POST["slug"] != $page->get("slug")) {
                    if (!empty(Database::query("SELECT * FROM app_pages WHERE slug = ?", [$_POST["slug"]])[0]))
                        throw new Exception(_("This slug is already used."), 1060);

                    $page->set("slug", Fairplay::string($_POST["slug"]));
                    Ajax::add('.admin.pages .list .list-item[data-id="'.$page->get("id").'"] .slug', "slug: ".$_POST["slug"]);
                }

                if (isset($_POST["description"]) && $_POST["description"] != $page->get("description"))
                    $page->set("description", Fairplay::string($_POST["description"]));

                if (isset($_POST["robots"]) && $_POST["robots"] != $page->get("robots"))
                    $page->set("robots", Fairplay::string($_POST["robots"]));

                if (!empty($_POST["template"]) && $_POST["template"] != $page->get("template"))
                    $page->set("template", Fairplay::string($_POST["template"]));
                    
                if (!empty($_POST["role"]) && $_POST["role"] != $page->get("role"))
                    $page->set("role", Fairplay::integer($_POST["role"]));

                if (isset($_POST["name"]) && isset($_POST["value"]))
                    for($i = 0; $i < count($_POST["name"]); $i++)
                        if (!empty($_POST["name"][$i]) && isset($_POST["value"][$i])) {
                            if (in_array($_POST["name"][$i], array_keys($page->get_data())))
                                throw new Exception(sprintf(_("You can not set %s as an additional field."), $_POST["name"][$i]), 1102);

                            $page->set(Fairplay::string($_POST["name"][$i]), Fairplay::string($_POST["value"][$i]));
                        }    

                Ajax::add('.response', '<div class="success">'._("Changes successfully saved.").'</div>');
               
                break;
            case "admin/page/delete":

                if (empty($_POST["value"]))
                    throw new Exception(_("Required input not found."), 1061);

                Database::query("DELETE FROM app_pages WHERE id = ?", [Fairplay::integer($_POST["value"])]);
                Ajax::remove('.admin.pages .list li[data-id="'.Fairplay::integer($_POST["value"]).'"]');
                Ajax::add('.response', '<div class="success">'._("Page successfully deleted.").'</div>');

                break;
            case "admin/page/scroll":

                $items = array();
                foreach (Database::query("SELECT * FROM app_pages") as $page) {
                    $page = new Model\Page($page["id"]);
                    $env = [
                        "page" => [
                            "id"            => $page->get("id"),
                            "slug"          => $page->get("slug"),
                            "title"         => $page->get("title"),
                            "description"   => $page->get("description"),
                            "robots"        => $page->get("robots"),
                            "canonical"     => $page->get("canonical"),
                            "template"      => $page->get("template"),
                            "role"          => $page->get("role"),
                            "metafields"    => $page->get_meta()
                        ]
                    ];

                    foreach ($page->get_meta() as $name => $value)
                        $env["page"][$name] = $value;

                    $items[] = (object) $env["page"];
                }
    
                $pages = ceil(count($items)/20);
                $page = (!empty($_POST["value"])) ? Fairplay::integer("value") : 1;
                $items = array_slice($items, ($page - 1) * 20, 20);
                
                Ajax::add('.admin.pages .list', Template::get(
                    "admin/elements/PageList.tpl", [
                        "page" => (object) [
                            "pagination" => (object) ["page" => $page, "pages" => $pages]
                        ],
                        "pages" => $items, 
                    ]
                ));

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1062);
        }
    }

    /**
     * 
     *  Handles the account-related actions, including adding, editing, and deleting accounts.
     * 
     *  @since  2.0
     *  @param  string  $request    The requested action
     * 
     */
    public function accountAction(string $request) {
        switch($request) {
            case "admin/account/add":

                if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["pw1"]) || empty($_POST["pw2"]) || empty($_POST["role"]))
                    throw new Exception(_("Required input not found."), 1063);

                if (!empty(Database::query("SELECT * FROM app_accounts WHERE username LIKE ?",[$_POST["username"]])[0]))
                    throw new Exception(_("This username is already taken."), 1064);
        
                if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [$_POST["email"]])[0]))
                    throw new Exception(_("This email address is already taken."), 1067);
        
                Database::query("INSERT INTO app_accounts (email, username, password, token, role) VALUES (?, ?, ?, ?, ?)", [strtolower(Fairplay::email($_POST["email"])), Fairplay::username($_POST["username"]), password_hash(Fairplay::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT), Auth::get_instance_token(), Fairplay::integer($_POST["role"])]);

                Ajax::add('.response', '<div class="success">'._("Account successfully added.").'</div>');
                Ajax::reload();
                    
                break;
            case "admin/account/logout":

                if (empty($_POST["value"]))
                    throw new Exception(_("Required input not found."), 1068);

                if ($this->account->get("id") == $_POST["value"])
                    throw new Exception(_("You can not logout yourself."), 1069);

                $account = new Model\Account(Fairplay::integer($_POST["value"]));
                $account->set("token", Auth::get_instance_token());
                Ajax::add('.response', '<div class="success">'._("Account successfully logged out.").'</div>');

                break;
            case "admin/account/edit":
                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1070);

                $account = new Model\Account(Fairplay::integer($_POST["id"]));

                if (!empty($_POST["username"]) && $_POST["username"] != $account->get("username")) {
                    if (!empty(Database::query("SELECT * FROM app_accounts WHERE username LIKE ?", [$_POST["username"]])[0]))
                        throw new Exception(_("This username is already taken."), 1071);

                    $account->set("username", Fairplay::username($_POST["username"]));
                    Ajax::add('.admin.accounts .list .list-item[data-id="'.$account->get("id").'"] .username', $_POST["username"]);
                }

                if (!empty($_POST["displayname"]) && $_POST["displayname"] != $account->get("displayname"))
                    if (in_array($_POST["displayname"], [$account->get("username"), $account->get("company"), $account->get("firstname"), $account->get("lastname"), $account->get("firstname")." ".$account->get("lastname"), $account->get("lastname")." ".$account->get("firstname")]))
                        $account->set("displayname", Fairplay::string($_POST["displayname"]));

                if (!empty($_POST["email"]) && $_POST["email"] != $account->get("email")) {
                    if (!empty(Database::query("SELECT * FROM app_accounts WHERE email LIKE ?", [$_POST["email"]])[0]))
                        throw new Exception(_("This email address is already taken."), 1072);
        
                    $account->set("email", strtolower(Fairplay::email($_POST["email"])));
                }

                if (!empty($_POST["role"]) && $_POST["role"] != $account->get("role")) {
                    if ($account->get("id") == $this->account->get("id"))
                        throw new Exception(_("You can not change your own role."), 1073);
                            
                    $account->get("role", Fairplay::integer($_POST["role"]));
                }

                if (!empty($_POST["pw1"]) && !empty($_POST["pw2"])) {
                    $account->set("password", password_hash(Fairplay::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT));
                    $account->set("token", Auth::get_instance_token());
                }

                if (isset($_POST["name"]) && isset($_POST["value"]))
                    for($i = 0; $i < count($_POST["name"]); $i++)
                        if (!empty($_POST["name"][$i]) && isset($_POST["value"][$i])) {
                            if (in_array($_POST["name"][$i], array_keys($account->get_data())))
                                throw new Exception(sprintf(_("You can not set %s as an additional field."), $_POST["name"][$i]), 1104);

                            $account->set(Fairplay::string($_POST["name"][$i]), Fairplay::string($_POST["value"][$i]));
                        }
                            
                Ajax::add('.response', '<div class="success">'._("Changes successfully saved.").'</div>'); 
                    
                break;
            case "admin/account/avatar/upload":

                if (empty($_POST["id"]) || !isset($_FILES["avatar"]))
                    throw new Exception(_("Required input not found."), 1074);

                $account = new Model\Account(Fairplay::integer($_POST["id"]));

                $file = Fairplay::file($_FILES["avatar"]);
                $size = getimagesize($file["tmp_name"]);
                if ($size[0] != $size[1])
                    throw new Exception(_("The avatar has to be squared."), 1075);

                if (!in_array(mime_content_type($file['tmp_name']), array_filter(json_decode(App::get("UPLOAD_IMAGE_TYPES")))))
                    throw new Exception(_("This file type not allowed."), 1105);
        
                $upload = new Upload($file,"avatar");

                if ($account->get("avatar"))
                    Upload::delete($account->get("avatar"));

                $account->set("avatar", $upload->get_file_name());
                Ajax::add('.admin.accounts .list li[data-id="'.$_POST["id"].'"] .avatar', '<img src="'.$upload->get_file_url().'"/>');
                Ajax::add('.response', '<div class="success">'._("Avatar successfully uploaded.").'</div>');
 
                break;
            case "admin/account/avatar/delete":
                if (empty($_POST["value"]))
                    throw new Exception(_("Required input not found."), 1076);

                $account = new Model\Account(Fairplay::integer($_POST["value"]));

                if ($account->get("avatar")) {
                    Upload::delete($account->get("avatar"));
                    $account->set("avatar", null);
                    Ajax::remove('.admin.accounts .list li[data-id="'.$_POST["value"].'"] .avatar img');
                }

                Ajax::add('.response', '<div class="success">'._("Avatar successfully deleted.").'</div>');

                break;
            case "admin/account/delete":
                if (empty($_POST["value"]))
                    throw new Exception(_("Required input not found."), 1077);
                
                if ($this->account->get("id") == $_POST["value"])
                    throw new Exception(_("You can not delete yourself."), 1078);

                Database::query("DELETE FROM app_accounts WHERE id = ?", [Fairplay::integer($_POST["value"])]);

                Ajax::remove('.admin.accounts .list li[data-id="'.$_POST["value"].'"]');
                Ajax::add('.response', '<div class="success">'._("Account successfully deleted.").'</div>');

                break;
            case "admin/account/scroll":

                $items = array();
                foreach (Database::query("SELECT * FROM app_accounts") as $account) {
                    $account = new Model\Account($account["id"]);
                    $env = [
                        "account" => [
                            "id"            => $account->get("id"),
                            "username"      => $account->get("username"),
                            "email"         => $account->get("email"),
                            "role"          => $account->get("role"),
                            "registered"    => $account->get("registered"),
                            "lastaction"    => $account->get("lastaction"),
                            "metafields"    => $account->get_meta(),
                            "displayname"   => $account->get("displayname"),
                            "avatar"        => $account->get("avatar"),
                        ]
                    ];

                    foreach ($account->get_meta() as $name => $value)
                        $env["account"][$name] = $value;

                    $items[] = (object) $env["account"];
                }

                $pages = ceil(count($items)/20);
                $page = (!empty($_POST["value"])) ? Fairplay::integer($_POST["value"]) : 1;
                $items = array_slice($items, ($page-1)*20, 20);
                
                Ajax::add('.admin.accounts', Template::get(
                    "admin/elements/AccountList.tpl", [
                        "page" => (object) [
                            "pagination" => (object) ["page" => $page, "pages" => $pages]
                        ],
                        "accounts" => $items
                    ]
                ));

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1079);
        }
    }

    /**
     * 
     *  Handles the cache-related actions, such as clearing the application cache.
     * 
     *  @since  2.0
     *  @param  string  $request    The requested action
     * 
     */
    public function cacheAction(string $request) {
        switch($request) {
            case "admin/cache/clear":

                Template::clear_cache();
                Ajax::add('.response', '<div class="success">'._("Cache successfully cleared.").'</div>');

                break;
            default: 
                throw new Exception(sprintf(_("Action %s not found."), $request), 1080);
        }
    }

}

?>