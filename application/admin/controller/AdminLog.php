<?php

namespace app\admin\controller;

use app\admin\model\AdminLog as M;

class AdminLog extends Base{

    //多条件查询
    protected function _search(){
        $where = array();
        $fields=array('realname','start','end');
        foreach ($fields as $key => $val) {
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                switch ($val) {
                    case 'realname':
                        $admins = db('admins')->field('id')->where(array('realname' => array('like','%'.$_REQUEST[$val].'%')))->select();
                        $aids=array();
                        if($admins){
                            foreach($admins as $k =>$v){
                                $aids[]=$v['id'];
                            }
                        }
                        if ($aids) {
                            $where['aid'] = array('in',$aids);
                        }else{
                            $where['aid'] = null;
                        }
                        break;
                    case 'start':
                        if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                            $where['create_time'] = array('EGT', strtotime(date('Y-m-d 00:00:00', strtotime($_REQUEST['start']))));
                        }
                        break;
                    case 'end':
                        if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                            $where['create_time'] = array('ELT', strtotime(date('Y-m-d 23:59:59', strtotime($_REQUEST['end']))));
                        }
                        break;
                    default:
                        $where [$val] = $_REQUEST[$val];
                        break;
                }
                $this->assign($val, $_REQUEST[$val]);
            }
        }
        if (isset($_REQUEST ['start']) && $_REQUEST ['start'] != '' && isset($_REQUEST ['end']) && $_REQUEST ['end'] != '') {
            $where ['create_time'] = array('between', [strtotime(date("Y-m-d 00:00:00", strtotime($_REQUEST ['start']))), strtotime(date("Y-m-d 23:59:59", strtotime($_REQUEST ['end'])))]);
        }
        return $where;
    }

    //列表
    public function index(){
        $log = new M();
        $where = $this->_search();
        $list = $log->index($where);
        $this->assign('list',$list);
        $this->assign('count',$log->total($where));
        $this->assign('page',$list->render());
        return $this->fetch();
    }
}