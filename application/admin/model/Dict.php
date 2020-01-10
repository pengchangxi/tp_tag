<?php

namespace app\admin\model;

class Dict extends Base
{
    /**
     * 命名规则为6位
     * 前3位为业务类型
     * 后三位为业务码
     * 比如 ：100000 其中100 代表管理员状态 000表示禁用
     */

    //管理员状态
    const ADMIN_STATUS_0 = 100000; //禁用
    const ADMIN_STATUS_1 = 100001; //启用

    //评论状态
    const COMMENT_STATUS_0 = 101000; //隐藏
    const COMMENT_STATUS_1 = 101001; //显示

    //菜单相关
    const menu_status_0 = 102000; //隐藏
    const menu_status_1 = 102001; //显示
    const plain_menu    = 103000; //普通菜单
    const page_menu     = 103001; //页面菜单
    const NO_RECORD_LOG = 104000; //不记录操作日志
    const RECORD_LOG    = 104001; //记录操作日志

    //角色状态
    const role_status_0 = 105000; //禁用
    const role_status_1 = 105001; //启用

    public function getNameByCode($code)
    {
        $cname = $this->where('code', $code)->value('cname');
        return $cname;
    }

}
