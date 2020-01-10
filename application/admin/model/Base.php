<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Base extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * 列表
     * @param $where
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function index($where)
    {
        $list = $this->order('id desc')->where($where)->paginate(15, false, [
            'query' => request()->param(),]);
        return $list;
    }

    /**
     * @param $where
     * @return int|string
     * @throws \think\Exception
     */
    public function total($where)
    {
        $total = $this->where($where)->count();
        return $total;
    }

    /**
     * 添加
     * @param $data
     * @return int|string
     */
    public function add($data)
    {
        $insert_id = $this->insert($data);
        return $insert_id;
    }

    /**
     * 修改
     * @param $where
     * @param $data
     * @return $this
     */
    public function edit($where, $data)
    {
        $edit = $this->where($where)->update($data);
        return $edit;
    }

    /**
     * @param $where
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lookup($where)
    {
        $info = $this->where($where)->find();
        return $info;
    }

    public function setNumInc($where, $field, $step = 1)
    {
        $this->where($where)->setInc($field, $step);
    }

    /**
     * 软删除
     * @param $where
     * @return mixed
     */
    public function softDel($where)
    {
        $edit = $this->where($where)->delete();
        return $edit;
    }
}
