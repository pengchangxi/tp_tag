<?php
namespace app\admin\validate;
use think\Validate;
class Admins extends Validate
{
    protected $rule = [
        'id'=>'require',
        'account'  =>  'min:6|require|unique:admins',
        'email'=>'email',
        'mobile'=>'regex:/^(1)[0-9]{10}$/',
        'password'=>'require|min:6'
    ];

    protected $message  =   [
        'account.min' => '账户名称太短',
        'account.require' => '账户不能为空',
        'account.unique' => '账户已存在',
        'email.email' => 'email地址不正确',
        'mobile.regex'=>'手机号不正确',
        'password.require'=>'密码不能为空',
        'password.min'=>'密码不能少于6位数'
    ];

    protected $scene = [
        'add'  =>  ['account','email','mobile','password'],
        'edit'  =>  ['id','account'  => 'require','email','mobile','password'=>'min'],
    ];




}
