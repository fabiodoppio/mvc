<?php

/**
 * mvc
 * Model View Controller (MVC) design pattern for simple web applications.
 *
 * @see     https://github.com/fabiodoppio/mvc
 *
 * @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 * @license https://opensource.org/license/mit/ MIT License
 */


namespace MVC\Models;

/**
 * Role Class
 *
 * The Role class represents user roles and provides constants
 * for commonly used role identifiers. It extends the Model class and is
 * used to interact with a database table that stores role information.
 */
class Role extends Model {
    
    /**
     * The name of the database table associated with user roles.
     *
     * @var     string  $table
     */
    protected $table = "app_roles";

    /**
     * The primary key column for the user roles table.
     *
     * @var     string  $primaryKey
     */
    protected $primaryKey = "app_roles.id";

    /**
     * Constant representing blocked user role.
     */
    public const BLOCKED = 1;

    /**
     * Constant representing deactivated user role.
     */
    public const DEACTIVATED = 2;

    /**
     * Constant representing the guest user role.
     */
    public const GUEST = 3;

    /**
     * Constant representing the default user role.
     */
    public const USER = 4;

    /**
     * Constant representing the verified user role.
     */
    public const VERIFIED = 5;

    /**
     * Constant representing the moderator role.
     */
    public const MODERATOR = 6;

    /**
     * Constant representing the administrator role.
     */
    public const ADMINISTRATOR = 7;

}

?>
