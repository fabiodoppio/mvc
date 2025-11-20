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


namespace MVC\Models;

use MVC\Exception   as Exception;
use MVC\Models      as Model;

/**
 *
 *  Cronjob Class
 *
 *  The Cronjob class represents a scheduled task.
 *
 */
class Cronjob extends Model\Model {

    /**
     *  @var    string  $table          The name of the database table associated with cron jobs
     */
    protected $table = "app_cronjobs";

    /**
     *  @var    string  $primaryKey     The primary key column name in the database table
     */
    protected $primaryKey = "app_cronjobs.id";


    /**
     *
     *  Executes cron job.
     *
     *  @since  3.1
     *
     */
    public function exec() {
        try {
            [$className, $methodName] = explode("/", trim($this->get("action")));
            $className = '\MVC\\'.ucfirst($className);

            if (!class_exists($className))
                throw new Exception(sprintf(_("Class %s not found."), $className), 2200);

            $class = new $className();

            if (!method_exists($class, $methodName))
                throw new Exception(sprintf(_("Action %s not found."), $methodName), 2201);

            $class->$methodName();
        }
        catch(Exception $exception) {
            $exception->process();
        }

        $this->set("last", (new \DateTime())->format("Y-m-d H:i:s"));
        $this->set("next", (new \DateTime())->modify("+".$this->get("period")." minutes")->format("Y-m-d H:i:s"));
    }

    /**
     *
     *  Checks if cron job is overdue and should be executed.
     *
     *  @since  3.1
     *
     */
    public function should_run() {
        return (new \DateTime()) >= (new \DateTime($this->get("next")));
    }

    /**
     *
     *  Checks if cron job is delayed.
     *
     *  @since  3.1
     *
     */
    public function is_delayed() {
        $diff = (new \DateTime())->diff(new \DateTime($this->get("last")?:""));
        $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

        return $minutes > $this->get("period");
    }

}

?>