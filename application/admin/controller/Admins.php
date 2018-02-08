<?php

namespace app\admin\controller;

use app\admin\model\Admins as M;
use app\admin\model\Role;
use think\Loader;
use think\Request;

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

    public function add(){
        if (request()->isPost()){
            $admins = new M();
            $data = input('post.');
            $validate = Loader::validate('Admins');
            if(!$validate->check($data)){//表单验证
                $this->error($validate->getError()); die;
            }
            $salt = random(6);
            $data['password'] = md5($data['password'].$salt);
            $data['salt'] = $salt;
            $data['create_time'] = time();
            $insert_id = $admins->add($data);
            if ($insert_id){
                $this->success('添加成功!','/admin/admins/index');
            }else{
                $this->error('添加失败!');
            }
        }
        $role = new Role();
        $roles = $role->getList();
        $this->assign('roles',$roles);
        return $this->fetch('edit');
    }

    public function edit(){
        $admins = new M();
        if (request()->isPost()){
            $data = input('post.');
            $validate = Loader::validate('Admins');
            if(!$validate->check($data)){//表单验证
                $this->error($validate->getError()); die;
            }
            if (!empty(trim($data['password']))){
                $salt = random(6);
                $data['password'] = md5($data['password'].$salt);
                $data['salt'] = $salt;
            }else{
                unset($data['password']);
            }
            $data['update_time'] = time();
            $where['id'] = $data['id'];
            $edit = $admins->edit($where,$data);
            if ($edit){
                $this->success('修改成功!','/admin/admins/index');
            }else{
                $this->error('修改失败!');
            }
        }
        $request = Request::instance();
        $id = $request->param('id');
        $where['id'] = $id;
        $info = $admins->lookup($where);
        $role = new Role();
        $roles = $role->getList();
        $this->assign('roles',$roles);
        $this->assign('info',$info);
        return $this->fetch();
    }

    //软删除
    public function delete(){
        $request = Request::instance();
        $id = $request->param('id');
        if ($id){
            $ids=explode(',',$id);
            $where['id'] = array('in',$ids);
            $admins = new M();
            $edit = $admins->del($where);
            if ($edit){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败!');
            }
        }else{
            $this->error('操作错误!');
        }
    }
}