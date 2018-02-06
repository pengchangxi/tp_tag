<?php

namespace app\admin\controller;

use app\admin\model\Role as M;

class Role extends Base{

    public function index(){
        $role = new M();
        $list = $role->index();
        $this->assign('list',$list);
        $this->assign('count',count($list));
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    public function add(){
        //var_dump(111);exit();
        return $this->fetch('edit');
    }
}