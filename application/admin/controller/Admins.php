<?php

namespace app\admin\controller;

use app\admin\model\Admins as M;

class Admins extends Base{

    public function index(){
        $admins = new M();
        $where = array();
        $list = $admins->index($where);
        $this->assign('list',$list);
        $this->assign('count',count($list));
        $this->assign('page',$list->render());
        return $this->fetch();
    }
}