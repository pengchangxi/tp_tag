<?php

namespace app\admin\controller;

use app\admin\model\Admins;
use think\Controller;
use think\captcha\Captcha;
use think\Request;

class Login extends Controller
{

    public function index()
    {
        return $this->fetch('/template/login');
    }

    /**
     * @return \think\Response
     * 生成验证码
     */
    public function verify()
    {
        $Verify         = new Captcha();
        $Verify->length = 4;
        return $Verify->entry();
    }

    /**
     * @param $code
     * @return bool
     * 核对验证码
     */
    public function verifyCheck($code)
    {
        $verify = new Captcha();
        return $verify->check($code);
    }

    /**
     * 验证登录
     */
    public function checkLogin()
    {
        $request    = Request::instance();
        $account    = $request->param('account');
        $password   = $request->param('password');
        $verifyCode = $request->param('verify');
        $admin      = new Admins();
        $verify     = $this->verifyCheck($verifyCode);
        if (!$verify) {
            $this->error('验证码错误！', '/admin/login/index');
        }
        $map      = array(
            'account' => $account,
            'status'  => 1
        );
        $authInfo = $admin->lookup($map);
        if (empty($authInfo)) {
            $this->error('帐号不存在或已禁用！', '/admin/login/index');
        } else {
            if (md5($password . $authInfo['salt']) != $authInfo['password']) {
                $this->error('密码错误！');
            }
            session('adminInfo', $authInfo);
            $data        = array(
                'last_login_time' => time(),
                'last_login_ip'   => $request->ip()
            );
            $where['id'] = $authInfo['id'];
            $admin->edit($where, $data);
            $jumpurl = session('?loginjumpurl') ? session('loginjumpurl') : '/admin/index/index';

            $this->success('登录成功！', $jumpurl);
        }
    }

    //退出
    public function logout()
    {
        if (session('?adminInfo')) {
            session('adminInfo', null);//清除session
            if (isset($_GET['isajax'])) {
                $this->success('超时退出！', '/admin/login/index');
            }
            $this->success('退出成功！', '/admin/login/index');
        } else {
            $this->success('已经退出！', '/admin/login/index');
        }
    }

}