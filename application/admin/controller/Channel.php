<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Channel as M;
use app\admin\model\Admins;

class Channel extends Base{

    //多条件查询
    protected function _search(){
        $where = array();
        $fields=array('name','channel','spread');
        foreach ($fields as $key => $val) {
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                switch ($val) {
                    case 'name':
                        $where [$val] = array('like','%'.$_REQUEST[$val].'%');
                        break;
                    case 'channel':
                        $where [$val] = array('like','%'.$_REQUEST[$val].'%');
                        break;
                    default:
                        $where [$val] = $_REQUEST[$val];
                        break;
                }
                $this->assign($val, $_REQUEST[$val]);
            }
        }
        return $where;
    }

    //列表
    public function index(){
        $channel = new M();
        $where = $this->_search();
        $list = $channel->index($where);
        $this->assign('list',$list);
        $this->assign('count',count($list));
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    //添加
    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('channel');
            if(!$validate->check($data)){
                $this->error($validate->getError()); die;
            }
            $channel = new M();
            $data['create_time'] = time();
            $insert_id = $channel->add($data);
            if ($insert_id){
                $this->success('添加成功!','/admin/channel/index');
            }else{
                $this->error('添加失败!');
            }
        }
        $admins = new Admins();
        $aList = $admins->getList();
        $this->assign('aList',$aList);
        return $this->fetch('edit');
    }

    //修改
    public function edit(){
        $channel = new M();
        if (request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('channel');
            if (!$validate->check($data)){
                $this->error($validate->getError());die;
            }
            $data['update_time'] = time();
            $where['id'] = $data['id'];
            $edit = $channel->edit($where,$data);
            if ($edit){
                $this->success('修改成功!','/admin/channel/index');
            }else{
                $this->error('修改失败!');
            }
        }
        $request = Request::instance();
        $id = $request->param('id');
        $where['id'] = $id;
        $info = $channel->lookup($where);
        $this->assign('info',$info);
        $admins = new Admins();
        $aList = $admins->getList();
        $this->assign('aList',$aList);
        return $this->fetch('edit');
    }

    //软删除
    public function delete(){
        $request = Request::instance();
        $id = $request->param('id');
        if ($id){
            $ids=explode(',',$id);
            $where['id'] = array('in',$ids);
            $channel = new M();
            $edit = $channel->del($where);
            if ($edit){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败!');
            }
        }else{
            $this->error('操作错误!');
        }
    }

    //渠道版本赋值
    public function evaluate(){
        $request = Request::instance();
        $id = $request->param('id');
        if ($id){
            $ids=explode(',',$id);
            $where['id'] = array('in',$ids);
            $channel = new M();
            $map['id'] = 1;//赋值ID=1的数据
            $info = $channel->lookup($map);
            $data['force_code'] = $info['force_code'];
            $data['new_code'] = $info['new_code'];
            $data['remark'] = $info['remark'];
            $data['duration'] = $info['duration'];
            $data['update_time'] = time();
            $edit = $channel->edit($where,$data);
            if ($edit){
                $this->success('修改成功!');
            }else{
                $this->error('修改失败!');
            }
        }else{
            $this->error('操作错误!');
        }
    }
}