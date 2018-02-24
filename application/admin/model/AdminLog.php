<?php

namespace app\admin\model;

class AdminLog extends Base{

    public function items() {
        return $this->hasOne('admins', 'id', 'aid'); //关联的模型，关联模型中的关联键，当前模型的关联键
    }

    public function index($where){
        $list = self::with('items')->where($where)->where('isdelete',0)->order('id desc')->paginate(60, false, [
            'query' => request()->param(),]);
        return $list;
    }


}