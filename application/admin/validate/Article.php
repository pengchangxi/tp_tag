<?php

namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title' => 'min:5|require',
    ];

    protected $message = [
        'title.min'     => '标题名称长度不能小于5',
        'title.require' => '标题不能为空',
    ];
}
