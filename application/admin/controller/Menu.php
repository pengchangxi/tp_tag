<?php

namespace app\admin\controller;

use app\admin\model\Menu as M;
use think\Request;
use tree\Tree;


class Menu extends Base{

    //列表
    public function index(){
        $menu = new M();
        $objResult = $menu->index();
        $arrResult = $objResult ? collection($objResult)->toArray() : [];
        $tree       = new Tree();
        $tree->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $array = [];
        foreach ($arrResult as $r) {
            $r['str_manage'] = "<a title=\"添加\" href=\"javascript:;\" onclick=\"layer_open('菜单添加','".url('/admin/menu/add',array('pid'=>$r['id']))."','','510')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe600;</i>添加子节点</a>
            <a title=\"编辑\" href=\"javascript:;\" onclick=\"layer_open('菜单编辑','".url('/admin/menu/edit',array('id'=>$r['id']))."','','510')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe6df;</i>编辑</a>
            <a title=\"删除\" href=\"javascript:;\" onclick=\"del(this,'".$r['id']."')\" class=\"ml-5\" style=\"text-decoration:none\"><i class=\"Hui-iconfont\">&#xe6e2;</i>删除</a>";
            $r['status']     = $r['status'] ? "<span class=\"label label-success radius\">显示</span>" : "<span class=\"label label-warning radius\">隐藏</span>";
            $array[]         = $r;
        }
        $tree->init($array);
        $str = "<tr >
            <td >\$id</td>
            <td >\$spacer\$name</td>
            <td>\$url</td>
            <td>\$status</td>
            <td>\$str_manage</td>
        </tr>";

        $categories = $tree->getTree(0, $str);
        $this->assign("categories", $categories);
        return $this->fetch();
    }

    //添加
    public function add(){
        $menu = new M();
        if (request()->isPost()){
            $data = input('post.');
            $pmenu = $menu->find($data['pid']);
            $data['level'] = $pmenu ? $pmenu->level + 1 : 0;
            $insert_id = $menu->add($data);
            if ($insert_id){
                $this->success('添加成功','/admin/menu/index');
            }else{
                $this->error('添加失败');
            }
        }
        $request = Request::instance();
        $pid  = $request->param("pid");
        $navTrees = $this->treeList($pid);
        $this->assign("nav_trees", $navTrees);
        return $this->fetch('edit');
    }

    //修改
    public function edit(){
        $menu = new M();
        if (request()->isPost()){
            $data = input('post.');
            $where['id'] = $data['id'];
            $edit = $menu->edit($where,$data);
            if ($edit){
                $this->success('修改成功!','/admin/menu/index');
            }else{
                $this->error('修改失败!');
            }
        }
        $request = Request::instance();
        $id  = $request->param("id");
        $where['id'] = $id;
        $info = $menu->lookup($where);
        $navTrees = $this->treeList($info['pid']);
        $this->assign("nav_trees", $navTrees);
        $this->assign('info',$info);
        return $this->fetch();
    }

    //删除
    public function delete(){
        $request = Request::instance();
        $id  = $request->param("id");
        if (!$id){
            $this->error('操作错误!');
        }else{
            $menu = new M();
            $where['id'] = $id;
            $edit = $menu->del($where);
            if ($edit){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败!');
            }
        }
    }

    //生成树形结构上级节点
    public function treeList($pid){
        $list = db('menu')->select();
        $tree       = new Tree();
        $tree->icon = ['&nbsp;│ ', '&nbsp;├─ ', '&nbsp;└─ '];
        $tree->nbsp = '&nbsp;';
        $array      = [];
        foreach ($list as $r) {
            $r['selected']   = $r['id'] == $pid ? "selected" : "";
            $array[]         = $r;
        }
        $tree->init($array);
        $str      = "<option value='\$id' \$selected>\$spacer\$name</option>";
        $navTrees = $tree->getTree(0, $str);
        return $navTrees;
    }


}