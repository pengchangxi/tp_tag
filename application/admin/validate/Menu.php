<?php

namespace app\admin\validate;

use think\Validate;

class Node extends Validate
{

    protected $rule = [
        'title' => 'require|unique:node',
        'name'  => 'require',
    ];

    protected $message = [
        'title.require' => '节点名称不能为空',
        'title.unique'  => '节点名称已存在',
        'name.require'  => '节点不能为空',
    ];
}
