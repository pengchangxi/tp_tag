<?php

namespace app\admin\controller;

use app\admin\model\AdminLog as M;

class AdminLog extends Base{

    public function index(){
        $log = new M();
        $where = array();
        $list = $log->index($where);
        $this->assign('list',$list);
        $this->assign('count',$log->total($where));
        $this->assign('page',$list->render());
        return $this->fetch();
    }
}