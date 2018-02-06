<?php

namespace app\admin\controller;

use think\Controller;
use think\Session;

class Base extends Controller{

    public function _initialize(){
//        if (!Session::has('adminInfo')) {
//            $this->redirect('/admin/login/index');
//        }
//
//        $this->assign('authInfo',session('adminInfo'));
    }
}