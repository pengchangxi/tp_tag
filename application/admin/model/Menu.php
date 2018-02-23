<?php

namespace app\admin\model;

use think\Model;
use think\Cache;

class Menu extends Base{

    //列表
    public function getList(){
        $list = $this->select();
        return $list;
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

    /**
     * 更新缓存
     * @param  $data
     * @return array
     */
    public function menuCache($data = null){
        if (empty($data)) {
            $data = $this->order("sort", "ASC")->column('');
            Cache::set('Menu', $data, 0);
        } else {
            Cache::set('Menu', $data, 0);
        }
        return $data;
    }

    /**
     * 查找URL
     * @param $menuId
     * @return array|false|\PDOStatement|string|Model
     */
    public function getUrl($menuId){
        return $this->where(["id" => $menuId])->field("url")->find();
    }




}