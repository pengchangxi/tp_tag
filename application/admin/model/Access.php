<?php

namespace app\admin\model;

use think\Model;

class Access extends Model
{
    /**
     * 添加
     * @param $data
     * @return int|string
     */
    public function add($data)
    {
        return $this->insert($data);
    }

    /**
     * 删除
     * @param $role_id
     * @return int
     */
    public function del($role_id)
    {
        $edit = $this->where('role_id', $role_id)->delete();
        return $edit;
    }

    public function getRuleName($roleId)
    {
        return $this->where(["role_id" => $roleId])->column("rule_name");
    }
}
