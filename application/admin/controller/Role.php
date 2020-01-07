<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Role as M;
use app\admin\model\Access;
use app\admin\model\Menu;
use tree\Tree;

class Role extends Base
{
    //多条件查询
    protected function _search()
    {
        $where  = array();
        $fields = array('name');
        foreach ($fields as $key => $val) {
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                switch ($val) {
                    case 'name':
                        $where [$val] = array('like', '%' . $_REQUEST[$val] . '%');
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
    public function index()
    {
        $role  = new M();
        $where = $this->_search();
        $list  = $role->index($where);
        $this->assign('list', $list);
        $this->assign('count', $role->total($where));
        $this->assign('page', $list->render());
        return $this->fetch();
    }

    //添加
    public function add()
    {
        if (request()->isPost()) {
            $data     = input('post.');
            $validate = \think\Loader::validate('Role');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
                die;
            }
            $role                = new M();
            $data['create_time'] = time();
            $insert_id           = $role->add($data);
            if ($insert_id) {
                $this->success('添加成功!', '/admin/role/index');
            } else {
                $this->error('添加失败!');
            }
        }
        return $this->fetch('edit');
    }

    //修改
    public function edit()
    {
        $role = new M();
        if (request()->isPost()) {
            $data     = input('post.');
            $validate = \think\Loader::validate('Role');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
                die;
            }
            $data['update_time'] = time();
            $where['id']         = $data['id'];
            $edit                = $role->edit($where, $data);
            if ($edit) {
                $this->success('修改成功!', '/admin/role/index');
            } else {
                $this->error('修改失败!');
            }
        }
        $request     = Request::instance();
        $id          = $request->param('id');
        $where['id'] = $id;
        $info        = $role->lookup($where);
        $this->assign('info', $info);
        return $this->fetch('edit');
    }

    //软删除
    public function delete()
    {
        $request = Request::instance();
        $id      = $request->param('id');
        if ($id) {
            $ids         = explode(',', $id);
            $where['id'] = array('in', $ids);
            $role        = new M();
            $edit        = $role->del($where);
            if ($edit) {
                $this->success('删除成功!');
            } else {
                $this->error('删除失败!');
            }
        } else {
            $this->error('操作错误!');
        }
    }

    /**
     * 设置角色权限
     */
    public function authorize()
    {
        $access    = new Access();
        $menuModel = new Menu();
        if ($this->request->isPost()) {
            $roleId = $this->request->param("roleId", 0, 'intval');
            if (!$roleId) {
                $this->error("需要授权的角色不存在！");
            }
            if (is_array($this->request->param('menuId/a')) && count($this->request->param('menuId/a')) > 0) {

                $access->del($roleId);
                foreach ($_POST['menuId'] as $menuId) {
                    $menu = $menuModel->getUrl($menuId);
                    if ($menu) {
                        $name = $menu['url'];
                        $data = array(
                            "role_id"   => $roleId,
                            "rule_name" => $name
                        );
                        $access->add($data);
                    }
                }
                $this->success("授权成功!", '/admin/role/index');
            } else {
                //当没有数据时，清除当前角色授权
                $access->del($roleId);
                $this->error("没有接收到数据，执行清除授权成功！");
            }
        }

        //角色ID
        $roleId = $this->request->param("id", 0, 'intval');
        if (empty($roleId)) {
            $this->error("参数错误！");
        }

        $tree       = new Tree();
        $tree->icon = ['│ ', '├─ ', '└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $result = $menuModel->menuCache();

        $newMenus      = [];
        $privilegeData = $access->ruleName($roleId);//获取权限表数据
        foreach ($result as $m) {
            $newMenus[$m['id']] = $m;
        }

        foreach ($result as $n => $t) {
            $result[$n]['checked']      = ($this->_isChecked($t, $privilegeData)) ? ' checked' : '';
            $result[$n]['level']        = $this->_getLevel($t['id'], $newMenus);
            $result[$n]['style']        = empty($t['pid']) ? '' : 'display:none;';
            $result[$n]['parentIdNode'] = ($t['pid']) ? ' class="child-of-node-' . $t['pid'] . '"' : '';
        }

        $str = "<tr id='node-\$id'\$parentIdNode  style='\$style'>
                   <td style='padding-left:10px;'>\$spacer<input type='checkbox' name='menuId[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$name</td>
    			</tr>";
        $tree->init($result);

        $category = $tree->getTree(0, $str);


        $this->assign("category", $category);
        $this->assign("roleId", $roleId);
        //dump($category);exit();
        return $this->fetch();
    }


    /**
     * 检查指定菜单是否有权限
     * @param array $menu menu表中数组
     * @param $privData
     * @return bool
     */
    private function _isChecked($menu, $privData)
    {
        $name = $menu['url'];
        if ($privData) {
            if (in_array($name, $privData)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * 获取菜单深度
     * @param $id
     * @param array $array
     * @param int $i
     * @return int
     */
    protected function _getLevel($id, $array = [], $i = 0)
    {
        if ($array[$id]['pid'] == 0 || empty($array[$array[$id]['pid']]) || $array[$id]['pid'] == $id) {
            return $i;
        } else {
            $i++;
            return $this->_getLevel($array[$id]['pid'], $array, $i);
        }
    }
}
