<?php

namespace app\admin\controller;

use think\Controller;
use think\captcha\Captcha;

class Login extends Controller{

    public function index(){
        return $this->fetch('/template/login');
    }

    /**
     * @return \think\Response
     * 生成验证码
     */
    public function verify(){
        $Verify = new Captcha();
        $Verify->length   = 4;
        return $Verify->entry();
    }

    /**
     * @param $code
     * @return bool
     * 核对验证码
     */
    public function verifyCheck($code){
        $verify = new Captcha();
        return $verify->check($code);
    }
}