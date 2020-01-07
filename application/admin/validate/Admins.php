<?php

namespace app\admin\validate;

use think\Validate;

class Admins extends Validate
{
    protected $rule = [
        'account'  => 'min:5|require|unique:admins',
        'realname' => 'require',
        'email'    => 'email',
        'mobile'   => 'regex:/^(1)[0-9]{10}$/',
        'password' => 'require'
    ];

    protected $message = [
        'account.min'      => '账户名称太短',
        'account.require'  => '账户不能为空',
        'account.unique'   => '账户已存在',
        'realname.require' => '姓名不能为空',
        'email.email'      => 'email地址不正确',
        'mobile.regex'     => '手机号不正确',
        'password.require' => '密码不能为空',
    ];

    protected $scene = [
        'add'  => ['account', 'email', 'mobile', 'password'],
        'edit' => ['account', 'email', 'mobile'],
    ];
}
