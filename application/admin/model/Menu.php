<?php

namespace app\admin\model;

use think\Model;

class Menu extends Model{

    //列表
    public function index(){
        $list = $this->select();
        return $list;
    }

    /**
     * 添加
     * @param $data
     * @return int|string
     */
    public function add($data){
        $insert_id = $this->insert($data);
        return $insert_id;
    }

    /**
     * 修改
     * @param $where
     * @param $data
     * @return $this
     */
    public function edit($where,$data){
        $edit = $this->where($where)->update($data);
        return $edit;
    }

    /**
     * 查询
     * @param $where
     * @return array|false|\PDOStatement|string|Model
     */
    public function lookup($where){
        $info = $this->where($where)->find();
        return $info;
    }

    /**
     * 删除
     * @param $where
     * @return int
     */
    public function del($where){
        $edit = $this->where($where)->delete();
        return $edit;
    }
}