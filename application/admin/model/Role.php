<?php

namespace app\admin\model;

use think\Model;

class Role extends Model{

    //列表
    public function index($where){
        $list = $this->order('id desc')->where($where)->where('isdelete',0)->paginate(15, false, [
            'query' => request()->param(),]);
        return $list;
    }

    //列表不分页
    public function getList(){
        $list = $this->order('id desc')->where(array('isdelete'=>0,'status'=>1))->select();
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
     * @param $id
     * @param $data
     * @return $this
     */
    public function edit($id,$data){
        $edit = $this->where('id',$id)->update($data);
        return $edit;
    }

    /**
     * 根据ID查询
     * @param array|\Closure|null|string|\think\db\Query $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function lookup($id){
        $info = $this->where('id',$id)->find();
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
}