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

use MVC\Ajax        as Ajax;
use MVC\App         as App;
use MVC\Exception   as Exception;
use MVC\Database    as Database;
use MVC\Mailer      as Mailer;
use MVC\Models      as Model;
use MVC\Template    as Template;
use MVC\Validator   as Validator;

/**
 *
 *  AdminController Class
 *
 *  The AdminController class is a specific controller handling actions related to admin actions,
 *  such as editing, deleting or creating accounts and pages.
 *
 */
class AdminController extends Controller {

    /**
     *
     *  Executes actions before the main action.
     *
     *  @since  3.0
     *
     */
    public function beforeAction() {
        parent::beforeAction();

        App::verify_instance_token(App::get_bearer_token());

        if ($this->account->get("role") < Model\Account::ADMINISTRATOR)
            throw new Exception(_("Your account does not have the required role."), 1700);
    }

    /**
     *
     * Handles account editing-related actions, including updating account details, creating or deleting an account.
     *
     *  @since  3.0
     *  @param  string  $request    The requested action.
     *
     */
    public function accountAction(string $request) {
        switch($request) {
            case "/admin/account/create":

                if (empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["pw1"]) || empty($_POST["pw2"]))
                    throw new Exception(_("Required input not found."), 1701);

                $email = Validator::email($_POST["email"]);
                $username = Validator::username($_POST["username"]);
                $password = Validator::password($_POST["pw1"], $_POST["pw2"]);

                if (!empty(Database::query("SELECT id FROM app_accounts WHERE username LIKE ?",[$username])[0]))
                    throw new Exception(_("This username is already taken."), 1702);

                if (!empty(Database::query("SELECT id FROM app_accounts WHERE email LIKE ?", [$email])[0]))
                    throw new Exception(_("This email address is already taken."), 1703);

                $token = App::generate_token();
                Database::query("INSERT INTO app_accounts (email, username, password, token, role) VALUES (?, ?, ?, ?, ?)", [strtolower($email), $username, password_hash($password, PASSWORD_DEFAULT), $token, Model\Account::USER]);

                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully created.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/account/edit":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1704);

                $account = new Model\Account(Validator::integer($_POST["id"]));
                $current_meta = $account->get("meta");

                foreach(array_combine($_POST["custom"]["name"] ?? [], $_POST["custom"]["value"] ?? []) ?? [] as $key => $value)
                    if (!in_array($key, Model\Account::get_protected_names()) && $value != $account->get($key))
                        $account->set(Validator::string($key), Validator::string($value));

                if (isset($_POST["displayname"]) && $_POST["displayname"] != $account->get("displayname"))
                    $account->set("displayname", Validator::string($_POST["displayname"]));

                if (isset($_POST["company"]) && $_POST["company"] != $account->get("company"))
                    $account->set("company", Validator::string($_POST["company"]));

                if (isset($_POST["firstname"]) && $_POST["firstname"] != $account->get("firstname"))
                    $account->set("firstname", Validator::string($_POST["firstname"]));

                if (isset($_POST["lastname"]) && $_POST["lastname"] != $account->get("lastname"))
                    $account->set("lastname", Validator::string($_POST["lastname"]));

                switch($account->get("displayname")) {
                    case $current_meta["company"]??"":
                        $account->set("displayname", $account->get("company"));
                        break;
                    case $current_meta["firstname"]??"":
                        $account->set("displayname", $account->get("firstname"));
                        break;
                    case $current_meta["lastname"]??"":
                        $account->set("displayname", $account->get("lastname"));
                        break;
                    case ($current_meta["firstname"]??"")." ".($current_meta["lastname"]??""):
                        $account->set("displayname", $account->get("firstname")." ".$account->get("lastname"));
                        break;
                    case ($current_meta["lastname"]??"")." ".($current_meta["firstname"]??"");
                        $account->set("displayname", $account->get("lastname")." ".$account->get("firstname"));
                        break;
                }

                if (isset($_POST["street"]) && $_POST["street"] != $account->get("street"))
                    $account->set("street", Validator::string($_POST["street"]));

                if (isset($_POST["postal"]) && $_POST["postal"] != $account->get("postal"))
                    $account->set("postal", Validator::string($_POST["postal"]));

                if (isset($_POST["city"]) && $_POST["city"] != $account->get("city"))
                    $account->set("city", Validator::string($_POST["city"]));

                if (isset($_POST["country"]) && $_POST["country"] != $account->get("country"))
                    $account->set("country", Validator::string($_POST["country"]));

                if (isset($_POST["vat"]) && $_POST["vat"] != $account->get("vat"))
                    $account->set("vat", Validator::string($_POST["vat"]));

                if (!empty($_POST["username"]) && $_POST["username"] != $account->get("username")) {
                    $account->set("username", strtolower(Validator::string($_POST["username"])));
                    Ajax::add('.accounts table tr[data-id="'.$_POST["id"].'"] td:nth-child(2)', $account->get('username'));
                }

                if (!empty($_POST["email"]) && $_POST["email"] != $account->get("email")) {
                    $account->set("email", strtolower(Validator::email($_POST["email"])));
                    Ajax::add('.accounts table tr[data-id="'.$_POST["id"].'"] td:nth-child(3)', '<a href="mailto:'.$account->get('email').'" title="'._('Send Email').'">'.$account->get('email').'</a>');
                }

                if (!empty($_POST["pw1"]) && !empty($_POST["pw2"])) {
                    $new_token = App::generate_token();
                    $account->set("password", password_hash(Validator::password($_POST["pw1"], $_POST["pw2"]), PASSWORD_DEFAULT));
                    $account->set("token", $new_token);
                }

                if (!empty($_POST["role"]) && $_POST["role"] != $account->get("role")) {
                    if ($account->get("id") == $this->account->get("id"))
                        throw new Exception(_("You can not change your role."), 1705);

                    if ($_POST["role"] < 0 || $_POST["role"] > Model\Account::ADMINISTRATOR)
                        throw new Exception(_("You can not change to requested role."), 1706);

                    $account->set("role", Validator::integer($_POST["role"]));
                    Ajax::add('.accounts table tr[data-id="'.$_POST["id"].'"] td:nth-child(4)', $account->get_role_name());
                }

                Ajax::add('#response', '<div class="alert is--success">'._("Changes successfully saved.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            case "/admin/account/delete/avatar":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1707);

                $account = new Model\Account(Validator::integer($_POST["id"]));
                $account->set("avatar", null);

                Ajax::remove('.accounts table tr[data-id="'.$_POST["id"].'"] .avatar img');
                Ajax::add('#response', '<div class="alert is--success">'._("Avatar successfully deleted.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            case "/admin/account/block":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1708);

                $account = new Model\Account(Validator::integer($_POST["id"]));

                if ($account->get("role") == Model\Account::BLOCKED)
                    throw new Exception(_("Account already blocked."), 1709);

                if ($account->get("id") == $this->account->get("id"))
                    throw new Exception(_("You can not block your account."), 1710);

                $account->block();

                Ajax::remove('.accounts table tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully blocked.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            case "/admin/account/restore":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1711);

                (new Model\Account(Validator::integer($_POST["id"])))->set("role", Model\Account::USER);

                Ajax::remove('.accounts table tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully restored.").'</div>');

                break;
            case "/admin/account/deactivate":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1712);

                $account = new Model\Account(Validator::integer($_POST["id"]));

                if ($account->get("role") == Model\Account::DEACTIVATED)
                    throw new Exception(_("Account already deactivated."), 1713);

                if ($account->get("id") == $this->account->get("id"))
                    throw new Exception(_("You can not deactivate your account."), 1714);

                $account->deactivate();

                Ajax::remove('.accounts table tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully deactivated.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            case "/admin/account/delete":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1715);

                $account = new Model\Account(Validator::integer($_POST["id"]));

                if ($account->get("role") > Model\Account::DEACTIVATED)
                    throw new Exception(_("Account must be deactivated or blocked first."), 1716);

                $account->delete();

                Ajax::remove('.accounts table tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Account successfully deleted.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1717);
        }
    }

    /**
     *
     * Handles page editing-related actions, including updating page details, creating or deleting a page.
     *
     *  @since  3.0
     *  @param  string  $request    The requested action.
     *
     */
    public function pageAction(string $request) {
        switch($request) {
            case "/admin/page/create":

                if (empty($_POST["title"]) || empty($_POST["slug"]) || empty($_POST["template"]))
                    throw new Exception(_("Required input not found."), 1718);

                if (!empty(Database::query("SELECT id FROM app_pages WHERE slug LIKE ?",[$_POST["slug"]])[0]))
                    throw new Exception(_("This slug is already taken."), 1719);

                $title = Validator::string($_POST["title"]);

                Database::query("INSERT INTO app_pages (slug, template, requirement, maintenance, active) VALUES (?, ?, ?, ?, ?)", [Validator::regex($_POST["slug"]), Validator::string($_POST["template"]), 0, 0, 1]);

                (new Model\Page(Database::$insert_id))->set("title", $title);

                Ajax::add('#response', '<div class="alert is--success">'._("Page successfully created.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/page/edit":

                if (empty($_POST["id"]) || empty($_POST["title"]) || empty($_POST["slug"]) || empty($_POST["template"]))
                    throw new Exception(_("Required input not found."), 1720);

                $page = new Model\Page(Validator::integer($_POST["id"]));

                foreach(array_combine($_POST["custom"]["name"] ?? [], $_POST["custom"]["value"] ?? []) ?? [] as $key => $value)
                    if (!in_array($key, Model\Account::get_protected_names()) && $value != $page->get($key))
                        $page->set(Validator::string($key), Validator::string($value));

                if (!empty($_POST["slug"]) && $_POST["slug"] != $page->get("slug")) {
                    $page->set("slug", Validator::regex($_POST["slug"]));
                    Ajax::add('.pages table tr[data-id="'.$_POST["id"].'"] td:nth-child(2)', $_POST["slug"]);
                }

                if (!empty($_POST["template"]) && $_POST["template"] != $page->get("template"))
                    $page->set("template", Validator::string($_POST["template"]));

                if (isset($_POST["requirement"]) && $_POST["requirement"] != $page->get("requirement")) {
                    if ($_POST["requirement"] == "" || $_POST["requirement"] < 0 || $_POST["requirement"] > Model\Account::ADMINISTRATOR)
                        throw new Exception(_("You can not change to requested requirement."), 1721);

                    $page->set("requirement", Validator::integer($_POST["requirement"]));
                }

                if (isset($_POST["maintenance"]) && $_POST["maintenance"] != $page->get("maintenance"))
                    $page->set("maintenance", Validator::integer($_POST["maintenance"]));

                if (isset($_POST["title"]) && $_POST["title"] != $page->get("title")) {
                    $page->set("title", Validator::string($_POST["title"]));
                    Ajax::add('.pages table tr[data-id="'.$_POST["id"].'"] td:nth-child(1)', $_POST["title"]);
                }

                if (isset($_POST["description"]) && $_POST["description"] != $page->get("description"))
                    $page->set("description", Validator::string($_POST["description"]));

                if (isset($_POST["robots"]) && $_POST["robots"] != $page->get("robots"))
                    $page->set("robots", Validator::string($_POST["robots"]));

                if (isset($_POST["class"]) && $_POST["class"] != $page->get("class"))
                    $page->set("class", Validator::string($_POST["class"]));

                Ajax::add('#response', '<div class="alert is--success">'._("Changes successfully saved.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            case "/admin/page/activate":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1722);

                (new Model\Page(Validator::integer($_POST["id"])))->set("active", 1);

                Ajax::add('#response', '<div class="alert is--success">'._("Page successfully activated.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/page/deactivate":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1723);

                (new Model\Page(Validator::integer($_POST["id"])))->set("active", 0);

                Ajax::add('#response', '<div class="alert is--success">'._("Page successfully deactivated.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/page/delete":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1724);

                (new Model\Page(Validator::integer($_POST["id"])))->delete();

                Ajax::remove('.pages table tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Page successfully deleted.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1725);
        }
    }

    /**
     *
     * Handles filter actions, including adding and remvoving filter options like badwords and email providers.
     *
     *  @since  3.0
     *  @param  string  $request    The requested action.
     *
     */
    public function filtersAction(string $request) {
        switch($request) {
            case "/admin/filters/add":

                if (!empty($_POST["badwords"]))
                    foreach(array_map('trim', explode(',', Validator::string($_POST["badwords"]))) as $badword)
                        if (empty(Database::query("SELECT id FROM app_filters_badwords WHERE badword LIKE ?", [$badword])[0]))
                            if ($badword !== "" && $badword !== null)
                                Database::query("INSERT INTO app_filters_badwords (badword) VALUES (?)", [$badword]);

                if (!empty($_POST["providers"]))
                    foreach(array_map('trim', explode(',', Validator::string($_POST["providers"]))) as $provider)
                        if (empty(Database::query("SELECT id FROM app_filters_providers WHERE provider LIKE ?", [$provider])[0]))
                            if ($provider !== "" && $provider !== null)
                                Database::query("INSERT INTO app_filters_providers (provider) VALUES (?)", [$provider]);

                Ajax::add('#response', '<div class="alert is--success">'._("Filter successfully added.").'</div>');

                break;
            case "/admin/filters/remove":

                if (!empty($_POST["badwords"]))
                    foreach(array_map('trim', explode(',', Validator::string($_POST["badwords"]))) as $badword)
                        Database::query("DELETE FROM app_filters_badwords WHERE badword LIKE ?", [$badword]);

                if (!empty($_POST["providers"]))
                    foreach(array_map('trim', explode(',', Validator::string($_POST["providers"]))) as $provider)
                        Database::query("DELETE FROM app_filters_providers WHERE provider LIKE ?", [$provider]);

                Ajax::add('#response', '<div class="alert is--success">'._("Filter successfully removed.").'</div>');

                break;
            case "/admin/filters/check":

                if (empty($_POST["message"]))
                    throw new Exception(_("Required input not found."), 1726);

                foreach(array_map('trim', explode(',', Validator::string($_POST["message"]))) as $word)
                    if (str_contains($word, '@'))
                        Validator::email($word);
                    else
                        Validator::fstring($word);

                Ajax::add('#response', '<div class="alert is--success">'._("Check, no filter had to be applied.").'</div>');

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1727);
        }
    }

    /**
     *
     * Handles cron job related actions, including updating scheduled task details, creating or deleting a scheduled task.
     *
     *  @since  3.1
     *  @param  string  $request    The requested action.
     *
     */
    public function cronjobAction(string $request) {
        switch($request) {
            case "/admin/cronjob/create":

                if (empty($_POST["name"]) || empty($_POST["action"]) || empty($_POST["period"]))
                    throw new Exception(_("Required input not found."), 1728);

                if (!str_contains($_POST["action"], '/'))
                    throw new Exception(_("This action has an incorrect format."), 1729);

                $next = (new \DateTime())->modify("+".Validator::integer($_POST["period"])." minutes")->format("Y-m-d H:i:s");
                Database::query("INSERT INTO app_cronjobs (name, action, period, next) VALUES (?, ?, ?, ?)", [Validator::string($_POST["name"]), Validator::string($_POST["action"]), $_POST["period"], $next]);

                Ajax::add('#response', '<div class="alert is--success">'._("Task successfully created.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/cronjob/edit":

                if (empty($_POST["id"]) || empty($_POST["name"]) || empty($_POST["action"]) || empty($_POST["period"]) || empty($_POST["next"]))
                    throw new Exception(_("Required input not found."), 1730);

                if (!str_contains($_POST["action"], '/'))
                    throw new Exception(_("This action has an incorrect format."), 1731);

                $cron = new Model\Cronjob(Validator::integer($_POST["id"]));

                if (isset($_POST["name"]) && $_POST["name"] != $cron->get("name")) {
                    $cron->set("name", Validator::string($_POST["name"]));
                    Ajax::add('.cronjobs table tr[data-id="'.$_POST["id"].'"] td:nth-child(1)', $_POST["name"]);
                }

                if (isset($_POST["action"]) && $_POST["action"] != $cron->get("action"))
                    $cron->set("action", Validator::string($_POST["action"]));

                if (isset($_POST["period"]) && $_POST["period"] != $cron->get("period"))
                    $cron->set("period", Validator::integer($_POST["period"]));

                if (isset($_POST["next"]) && $_POST["next"] != $cron->get("next")) {
                    $cron->set("next", Validator::datetime($_POST["next"]));
                    Ajax::add('.cronjobs table tr[data-id="'.$_POST["id"].'"] td:nth-child(2)', (new \DateTime($_POST["next"]))->format("d.m.Y - H:i:s"));
                }

                Ajax::add('#response', '<div class="alert is--success">'._("Changes successfully saved.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            case "/admin/cronjob/execute":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1732);

                $cron = new Model\Cronjob(Validator::integer($_POST["id"]));
                $cron->exec();

                Ajax::add('.cronjobs table tr[data-id="'.$_POST["id"].'"] td:nth-child(2)', (new \DateTime($cron->get("next")))->format('d.m.Y - H:i:s'));
                Ajax::add('.cronjobs table tr[data-id="'.$_POST["id"].'"] td:nth-child(3)', (new \DateTime($cron->get("last")))->format('d.m.Y - H:i:s'));
                Ajax::add('#response', '<div class="alert is--success">'._("Task successfully executed.").'</div>');

                break;
            case "/admin/cronjob/activate":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1733);

                (new Model\Cronjob(Validator::integer($_POST["id"])))->set("active", 1);

                Ajax::add('#response', '<div class="alert is--success">'._("Task successfully activated.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/cronjob/deactivate":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1734);

                (new Model\Cronjob(Validator::integer($_POST["id"])))->set("active", 0);

                Ajax::add('#response', '<div class="alert is--success">'._("Task successfully deactivated.")." "._("Please wait while redirecting..").'</div>');
                Ajax::reload();

                break;
            case "/admin/cronjob/delete":

                if (empty($_POST["id"]))
                    throw new Exception(_("Required input not found."), 1735);

                (new Model\Cronjob(Validator::integer($_POST["id"])))->delete();

                Ajax::remove('.cronjobs table tr[data-id="'.$_POST["id"].'"]');
                Ajax::add('#response', '<div class="alert is--success">'._("Task successfully deleted.").'</div>');
                Ajax::trigger('[data-trigger="close"]', "click");

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1736);
        }
    }

    /**
     *
     *  Handles newsletter actions, including sending newsletter to all accounts.
     *
     *  @since  3.0
     *  @param  string  $request    The requested action.
     *
     */
    public function newsletterAction(string $request) {
        switch($request) {
            case "/admin/newsletter/send":

                if (empty($_POST["subject"]) || empty($_POST["message"]) || empty($_POST["queue"]))
                    throw new Exception(_("Required input not found."), 1737);

                $subject = Validator::string($_POST["subject"]);
                $message = Validator::string($_POST["message"]);
                $accountQueue = array_map('trim', explode(',', Validator::string($_POST["queue"])));
                $ignoreNewsletterPreference = (!empty($_POST["ignore"])) ? true : false;
                $counter = (!empty($_POST["counter"])) ? Validator::integer($_POST["counter"]) : 0;

                $count = 0;
                foreach($accountQueue ?? [] as $key => $account) {
                    if ($count <= 9) {
                        $account = new Model\Account($account);
                        if ($account->get("newsletter") !== "0" || $ignoreNewsletterPreference) {
                            App::set_locale_runtime($account->get("language") ?? App::get("APP_LANGUAGE"));
                            Mailer::send(sprintf(_($subject." | %s"), App::get("APP_NAME")), $account->get("email"), Template::get("/_emails/accountNewsletter.tpl", [
                                "var" => (object) [
                                    "subject" => $subject,
                                    "message" => $message
                                ],
                                "app" => (object) [
                                    "url" => App::get("APP_URL"),
                                    "name" => App::get("APP_NAME")
                                ]
                            ]), App::get("MAIL_RECEIVER"));
                            $count++;
                        }
                        unset($accountQueue[$key]);
                    }
                }

                if (count($accountQueue) > 0) {
                    Ajax::add('.queue', '<input type="text" name="queue" value="'.implode(',', $accountQueue).'"/>');
                    Ajax::add('.progressbar', '<progress max="'.$counter.'" value="'.count($accountQueue).'"></progress>');
                    Ajax::trigger('[data-trigger="submit"]', "submit");
                }
                else {
                    App::set_locale_runtime($_COOKIE["locale"] ?? App::get("APP_LANGUAGE"));
                    Ajax::add('form[data-request="admin/newsletter/send"]', '<div class="alert is--success">'._("Newsletter successfully sent.").'</div>');
                }

                break;
            default:
                throw new Exception(sprintf(_("Action %s not found."), $request), 1738);
        }
    }

}

?>