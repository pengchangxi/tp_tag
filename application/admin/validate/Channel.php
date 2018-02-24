<?php

namespace app\admin\validate;

use think\Validate;

class Channel extends Validate{

    protected $rule = [
        'channel'  =>  'require|unique:channel',
        'name'=>'require',
        'new_code'=>'require',
        'force_code'=>'require',
        'url'=>'require|url'
    ];

    protected $message = [
        'channel.require' => '渠道编号不能为空',
        'channel.unique' => '渠道编号已存在',
        'new_code.require' => '最新版本ID不能为空',
        'force.require'=>'强升版本ID不能为空',
        'url.require' => '下载链接不能为空',
        'url.url' => '下载链接不正确',
    ];
}