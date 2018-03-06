<?php

namespace app\admin\model;

use think\Model;

class Avatar extends Model{

    public function index(){
        $list = $this->order('id desc')->paginate(15, false, [
            'query' => request()->param(),]);
        return $list;
    }
}