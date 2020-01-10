<?php

namespace app\admin\validate;

use think\Validate;

class Comment extends Validate
{
    protected $rule = [
        'uid' => 'require'
    ];

    protected $message = [
        'uid.require' => '用户不能为空'
    ];

}
