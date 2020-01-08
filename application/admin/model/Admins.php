<?php

namespace app\admin\model;

class Admins extends Base
{

    public function items()
    {
        return $this->hasOne('Role', 'id', 'role_id'); //关联的模型，关联模型中的关联键，当前模型的关联键
    }

    //列表
    public function index($where)
    {
        $list    = self::with('items')->where($where)->order('id desc')->paginate(15, false, [
            'query' => request()->param(),]);
        return $list;
    }

    public function getList()
    {
        $list = $this->where('role_id', 2)->select();//只查询角色为合作商户的
        return $list;
    }
}
