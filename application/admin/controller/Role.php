<?php

namespace app\admin\controller;

use app\admin\model\Role as M;

class Role extends Base{

    //列表
    public function index(){
        $role = new M();
        $list = $role->index();
        $this->assign('list',$list);
        $this->assign('count',count($list));
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    //添加
    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Role');
            if(!$validate->check($data)){
                $this->error($validate->getError()); die;
            }
            $role = new M();
            $insert_id = $role->add($data);
            if ($insert_id){
                $this->success('添加成功!','/admin/role/index');
            }else{
                $this->error('添加失败!');
            }
        }
        return $this->fetch('edit');
    }

    //修改
    public function edit(){
        $role = new M();
        if (request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Role');
            if (!$validate->check($data)){
                $this->error($validate->getError());die;
            }
            $edit = $role->edit($data['id'],$data);
            if ($edit){
                $this->success('修改成功!','/admin/role/index');
            }else{
                $this->error('修改失败!');
            }
        }
        $id = input('get.id/d');
        $info = $role->find($id);
        $this->assign('info',$info);
        return $this->fetch('edit');
    }

    //删除
    public function del(){
        $id = input('get.id/d');
        $role = new M();
        $edit = $role->del($id);
        if ($edit){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}