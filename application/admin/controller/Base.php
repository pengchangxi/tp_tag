<?php

namespace app\admin\controller;

use rbac\Rbac;
use think\Controller;
use think\Request;
use app\admin\model\Menu;
use app\admin\model\AdminLog;

class Base extends Controller
{

    protected $ignoreAction  = array('login'); // 不需要验证的动作
    protected $ignoreCompare = array('index');//判断是否为index操作

    public function _initialize()
    {
        if (!session('?adminInfo')) {
            $this->redirect('/admin/login/index');
        }
        $request = Request::instance();
        $url     = $this->getUrl();
        $menu    = new Menu();
        $isLog   = $menu->getLogByUrl($url);
        if ($isLog) {
            $this->adminLog();//操作日志
        }
        if (!in_array($request->controller(), $this->ignoreAction)) {
            if ($request->controller() != 'Index') {
                $access = Rbac::accessCheck(session('adminInfo')['role_id'], $url);
                if (!$access) {
                    $this->error('您没有权限操作该项');
                }
            }
        }
        $crumb = $this->crumbs();
        $menu  = $this->getRoleMenu();
        $this->assign('menu_list', $menu);
        $this->assign('crumb', $crumb);
        $this->assign('authInfo', session('adminInfo'));
    }

    /**
     * 获取权限菜单
     * @return array
     */
    public function getRoleMenu()
    {
        $modules = $roleMenu = $pmenu = array();
        $menu    = new Menu();
        $rs      = $menu->getList();
        $roleid  = session('adminInfo')['role_id'];
        if ($roleid == '1') {
            foreach ($rs as $row) {
                if ($row['level'] == 1) {
                    $modules[$row['pid']][] = $row;//子菜单分组
                }
                if ($row['level'] == 0) {
                    $pmenu[$row['id']] = $row;//二级父菜单
                }
            }
        } else {
            $rs = $menu->getMenuList($roleid);
            foreach ($rs as $row) {
                if ($row['level'] == 1) {
                    $modules[$row['pid']][] = $row;//子菜单分组
                }
                if ($row['level'] == 0) {
                    $pmenu[$row['id']] = $row;//二级父菜单
                }
            }
        }
        $keys = array_keys($modules);//导航菜单
        foreach ($pmenu as $k => $val) {
            if (in_array($k, $keys)) {
                $val['submenu'] = $modules[$k];//子菜单
                $roleMenu[]     = $val;
            }
        }
        return $roleMenu;
    }

    //面包屑
    public function crumbs()
    {

        $url = $this->getUrl();
        $r   = db('menu')->where('url', $url)->field('id,pid,name')->find();
        if ($r['pid']) {
            $parent = db('menu')->where('id', $r['pid'])->value('name');
            return $parent . ' &gt; ' . $r['name'];
        }
        return $r['name'];
    }

    //获取URL
    public function getUrl()
    {
        $request    = Request::instance();
        $module     = $request->module();//模块
        $controller = $request->controller();//控制器
        $controller = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $controller));//将AbcDef转化为abc_def
        $action     = $request->action();//方法
        $url        = '/' . $module . '/' . $controller . '/' . $action;
        return $url;
    }

    /**
     * 操作日志
     */
    protected function adminLog()
    {
        $request            = Request::instance();
        $url                = $request->url();
        $log                = array();
        $log['aid']         = session('adminInfo')['id'];
        $log['url']         = $url;
        $log['create_time'] = time();
        $data               = array_merge($_GET, $_POST);
        $log['data']        = empty($data) ? '' : json_encode($data);
        $adminLog           = new AdminLog();
        $adminLog->add($log);
    }
}
