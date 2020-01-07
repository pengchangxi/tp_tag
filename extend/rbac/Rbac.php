<?php

namespace rbac;

use app\admin\model\Access;

class Rbac
{
    const ADMIN_ROLE = 1;

    public static function accessCheck($roleId, $url)
    {
        if ($roleId == self::ADMIN_ROLE) {
            return true;
        }
        $access = new Access();
        $ruleName = $access->getRuleName($roleId);
        if ($ruleName == $url) {
            return true;
        }
        return false;
    }

}
