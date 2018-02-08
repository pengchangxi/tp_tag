<?php

namespace app\admin\validate;
use think\Validate;

class Admins extends Validate{

    protected $rule = [
        'account'  =>  'min:5|require|unique:admins',
        'email'=>'email',
        'mobile'=>'regex:/^(1)[0-9]{10}$/',
    ];

    protected $message  =   [
        'account.min' => '账户名称太短',
        'account.require' => '账户不能为空',
        'account.unique' => '账户已存在',
        'email.email' => 'email地址不正确',
        'mobile.regex'=>'手机号不正确',
    ];

}
