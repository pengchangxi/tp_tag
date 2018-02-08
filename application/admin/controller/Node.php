<?php

namespace app\admin\controller;

use app\admin\model\Node as M;
use app\admin\model\Group;
use think\Request;

class Node extends Base{

    public function index(){
        $request = Request::instance();
        $group = new M();
        if (request()->isAjax()) {
            try {
                $moduleId = $request->param('module_id');
                $groupId = $request->param('group_id');
                if ($request->param('type') == 'group') {
                    // 查询分组

                    // 查询二级节点下分组信息
                    $node = $group->getGroupId($moduleId);
                    if (!$node) {
                        $this->error('该模块下没有任何节点');
                    }
                    // 分组下菜单个数
                    $groupId = [];
                    foreach ($node as $vo) {
                        if (isset($groupId[$vo['group_id']])) {
                            $groupId[$vo['group_id']] += 1;
                        } else {
                            $groupId[$vo['group_id']] = 1;
                        }
                    }

                    // 分组信息['isdelete' => 0, 'id' => ['in', array_keys($groupId)]]
                    $where = array(
                        'isdelete' => 0,
                        'id' => ['in', array_keys($groupId)]
                    );
                    $group = new Group();
                    $groupList = $group->getList($where);
                    return ajax_return(['count' => $groupId, 'list' => $groupList]);
                } else {
                    // 查询节点
                    $list = Db::field('*')
                        ->name('admin_node')
                        ->union(function($query){
                            $query->name('admin_node')->where('isdelete=0 AND level>2');
                        })
                        ->where("isdelete=0 AND level=2 AND pid='{$moduleId}' AND group_id='{$groupId}'")
                        ->select();
                    // 重新组装节点
                    $list2 = [];
                    foreach ($list as $vo) {
                        $list2[] = [ 'name' => '<span class="c-warning">[ ' . ($vo['level'] == 1 ? '模块' : ($vo['type'] ? '控制器' : '方法')) . ' ]</span> '
                            . $vo['title'] . " (" . $vo['name'] . ") "
                            . ' <a></a><span class="c-secondary">[ 层级：' . $vo['level'] . ' ]</span> '
                            . show_status($vo['status'], $vo['id'])
                            . ' <a class="label label-primary radius J_add" data-id="' . $vo['id'] . '" href="javascript:;" title="添加子节点">添加</a>' ,'id' => $vo['id'], 'pid' => $vo['pid']];
                    }
                    $node = list_to_tree($list2, 'id', 'pid', 'children', $moduleId);

                    return ajax_return(['list' => $node]);
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            //var_dump(222);exit();
            // 模块
            $where = array(
                'pid'=>0,
                'isdelete'=>0
            );
            $modules = $group->index($where);
            $this->assign('modules', $modules);
            $this->assign('node', '');

            return $this->fetch();
        }
    }
}