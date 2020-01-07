<?php

namespace app\admin\model;

class Role extends Base
{
    //列表不分页
    public function getList()
    {
        $list = $this->order('id desc')->select();
        return $list;
    }
}
