<?php

namespace app\admin\validate;

use think\Validate;

class Group extends Validate{

    protected $rule = [
        'name'  =>  'require|unique:group',
    ];

    protected $message = [
        'name.require' => '组名不能为空',
        'name.unique' => '组名已存在',
    ];
}