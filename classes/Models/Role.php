<?php

namespace Classes\Models;

class Role extends Model {
    
    protected $table = "app_roles";
    protected $primaryKey = "app_roles.id";

    public const BLOCKED = 1;
    public const DEACTIVATED = 2;
    public const GUEST = 3;
    public const USER = 4;
    public const VERIFIED = 5;
    public const MODERATOR = 6;
    public const ADMINISTRATOR = 7;

}

?>
