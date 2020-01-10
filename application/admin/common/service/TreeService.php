<?php

namespace app\admin\common\service;

class TreeService
{
    public static function generateTree($list, $uid = '', $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        $tree     = array();
        $packData = array();
        foreach ($list as $data) {
            $packData[$data[$pk]] = $data;
        }
        foreach ($packData as $key => $val) {

            if ($val['status'] == 1 || $val['uid'] === $uid) {
                if ($val[$pid] == $root) {
                    //代表跟节点, 重点一
                    $tree[] = &$packData[$key];
                } else {
                    //找到其父类,重点二
                    $packData[$val[$pid]][$child][] = &$packData[$key];
                }
            }
        }
        return $tree;
    }

    public function userTreeList()
    {

    }
}
