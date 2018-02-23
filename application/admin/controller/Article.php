<?php

namespace app\admin\controller;

use app\admin\model\Article as M;
use think\Request;

class Article extends Base{

    //多条件查询
    protected function _search(){
        $where = array();
        $fields=array('title','author');
        foreach ($fields as $key => $val) {
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                switch ($val) {
                    case 'title':
                        $where [$val] = array('like','%'.$_REQUEST[$val].'%');
                        break;
                    case 'author':
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
        $article = new M();
        $where = $this->_search();
        $list = $article->index($where);
        $this->assign('list',$list);
        $this->assign('count',count($list));
        $this->assign('page',$list->render());
        return $this->fetch();
    }

    //添加
    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('article');
            if(!$validate->check($data)){
                $this->error($validate->getError()); die;
            }
            $article = new M();
            $data['create_time'] = time();
            $insert_id = $article->add($data);
            if ($insert_id){
                $this->success('添加成功!','/admin/article/index');
            }else{
                $this->error('添加失败!');
            }
        }
        return $this->fetch('edit');
    }

    //修改
    public function edit(){
        $article = new M();
        if (request()->isPost()){
            $data = input('post.');
            $validate = \think\Loader::validate('article');
            if (!$validate->check($data)){
                $this->error($validate->getError());die;
            }
            $data['update_time'] = time();
            $where['id'] = $data['id'];
            $edit = $article->edit($where,$data);
            if ($edit){
                $this->success('修改成功!','/admin/article/index');
            }else{
                $this->error('修改失败!');
            }
        }
        $request = Request::instance();
        $id = $request->param('id');
        $where['id'] = $id;
        $info = $article->lookup($where);
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
            $article = new M();
            $edit = $article->del($where);
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