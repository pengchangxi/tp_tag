<?php

namespace app\admin\controller;

use think\Controller;


class Base extends Controller{

    public function _initialize(){
        if (!session('?adminInfo')) {
            $this->redirect('/admin/login/index');
        }

        $this->assign('authInfo',session('adminInfo'));
    }
}