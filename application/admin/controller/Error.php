<?php

namespace app\admin\controller;

class Error extends Base{

    //空操作
    public function _empty(){
        $this->error('当前控制器不存在');
    }
}