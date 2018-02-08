<?php

namespace app\admin\model;

use think\Model;

class Group extends Model{

    //列表
    public function index($where){
        $list = $this->order('id desc')->where($where)->where('isdelete',0)->paginate(15, false, [
            'query' => request()->param(),]);
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
     * 软删除
     * @param $where
     * @return $this
     */
    public function del($where){
        $data=array(
            'isdelete'=>1
        );
        $edit = $this->where($where)->update($data);
        return $edit;
    }

    /**
     * @param $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($where){
       $list =  $this->order('sort asc')
            ->field('id,name,icon,sort,status')
            ->where($where)
            ->select();
       return $list;
    }


}