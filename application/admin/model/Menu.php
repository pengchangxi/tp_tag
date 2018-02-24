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

    /**
     * 根据角色ID获取权限列表
     * @param $roleid
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getMenuList($roleid){
        $where=array(
            'm.status' => 1,
            'a.role_id'=>$roleid
        );
        $menuList=db('access')->alias('a')->join('menu m','m.url = a.rule_name')->where($where)->order('m.sort desc')->select();
        return $menuList;
    }

    /**
     * 根据URL查询islog是否记录日志
     * @param $url
     * @return mixed
     */
    public function getLogByUrl($url){
        return $this->where('url',$url)->value('islog');
    }




}