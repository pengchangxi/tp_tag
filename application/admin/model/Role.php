<?php

namespace app\admin\model;

class Role extends Base
{
    //列表不分页
    public function getList()
    {
        $list = $this->order('id desc')->where(array('status' => 1))->select();
        return $list;
    }
}
