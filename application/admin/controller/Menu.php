<?php

namespace app\admin\controller;

use app\admin\model\Menu as M;

class Menu extends Base{

    public function index(){
        $menu = new M();
        $list = $menu->index();
        $this->assign('list',$list);
        return $this->fetch();
    }
}