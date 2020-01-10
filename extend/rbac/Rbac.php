<?php

namespace rbac;

use app\admin\model\Access;

class Rbac
{
    const ADMIN_ROLE = 1;//管理员角色

    public static function accessCheck($roleId, $url)
    {
        if ($roleId == self::ADMIN_ROLE) {
            return true;
        }
        $access = new Access();
        $ruleName = $access->getRuleName($roleId);
        if (in_array($url, $ruleName)) {
            return true;
        }
        return false;
    }

}
