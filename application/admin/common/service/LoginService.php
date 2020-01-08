<?php

namespace app\admin\common\service;

use think\Cookie;

class LoginService
{
    public static function setLoginErrorCookie()
    {
        $loginError = Cookie::get('login_error_num');
        if ($loginError) {
            Cookie::set('login_error_num', $loginError +1);
        } else {
            Cookie::set('login_error_num', 1);
        }
        return $loginError;
    }

}
