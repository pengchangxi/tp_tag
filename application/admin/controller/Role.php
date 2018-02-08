<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Role as M;

class Role extends Base{

    //多条件查询
    protected function _search(){
        $where = array();
        $fields=array('name','start','end');
        foreach ($fields as $key => $val) {
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                switch ($val) {
                    case 'name':
                        $where [$val] = array('like','%'.$_REQUEST[$val].'%');
                        break;
                    case 'start':
                        if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                            $where['create_time'] = array('EGT', strtotime(date('Y-m-d 00:00:00', strtotime($_REQUEST['start']))));
                        }
                        break;
                    case 'end':
                        if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                            $where['create_time'] = array('ELT', strtotime(date('Y-m-d 23:59:59', strtotime($_REQUEST['end']))));
                        }
                        break;
                    default:
                        $where [$val] = $_REQUEST[$val];
                        break;
                }
                $this->assign($val, $_REQUEST[$val]);
            }
        }
        if (isset($_REQUEST ['start']) && $_REQUEST ['start'] != '' && isset($_REQUEST ['end']) && $_REQUEST ['end'] != '') {
            $where ['create_time'] = array('between', [strtotime(date("Y-m-d 00:00:00", strtotime($_REQUEST ['start']))), strtotime(date("Y-m-d 23:59:59", strtotime($_REQUEST ['end'])))]);
        }
        return $where;
    }

    //列表
    public function index(){
        $role = new M();
        $where = $this->_search();
        $list = $role->index($where);
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
            $data['create_time'] = time();
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
            $data['update_time'] = time();
            $edit = $role->edit($data['id'],$data);
            if ($edit){
                $this->success('修改成功!','/admin/role/index');
            }else{
                $this->error('修改失败!');
            }
        }
        $request = Request::instance();
        $id = $request->param('id');
        $info = $role->lookup($id);
        $this->assign('info',$info);
        return $this->fetch('edit');
    }

    //软删除
    public function delete(){
        $request = Request::instance();
        $id = $request->param('id');
        if ($id){
            $ids=explode(',',$id);
            $where['id'] = array('in',$ids);
            $role = new M();
            $edit = $role->del($where);
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