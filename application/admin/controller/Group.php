<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Group as M;

class Group extends Base{

    //列表
    public function index(){
        $where = array();
        $group = new M();
        $list = $group->index($where);
        $this->assign('list',$list);
        $this->assign('count',count($list));
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    //添加
    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Group');
            if(!$validate->check($data)){
                $this->error($validate->getError()); die;
            }
            $group = new M();
            $data['create_time'] = time();
            $insert_id = $group->add($data);
            if ($insert_id){
                $this->success('添加成功!','/admin/group/index');
            }else{
                $this->error('添加失败!');
            }
        }
        return $this->fetch('edit');
    }

    //修改
    public function edit(){
        $group = new M();
        if (request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('Group');
            if(!$validate->check($data)){
                $this->error($validate->getError()); die;
            }
            $data['update_time'] = time();
            $where['id'] = $data['id'];
            $edit = $group->edit($where,$data);
            if ($edit){
                $this->success('修改成功!','/admin/group/index');
            }else{
                $this->error('修改失败!');
            }
        }
        $request = Request::instance();
        $id = $request->param('id');
        $where['id'] = $id;
        $info = $group->lookup($where);
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
            $group = new M();
            $edit = $group->del($where);
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