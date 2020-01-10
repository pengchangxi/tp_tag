<?php

namespace app\admin\controller;

use app\admin\common\service\TreeService;
use app\admin\model\Comment as M;
use think\Controller;
use think\Loader;

class Comment extends Controller
{
    public function operateComment()
    {
        if (request()->isPost()) {
            $this->operatePost();
        }
        $id = input('id');
        $comment = new M();
        if ($id) {
            $info = $comment->lookup(['id' => $id]);
        } else {
            $info = [];
        }
        return $this->fetch('operate');
    }

    private function operatePost()
    {
        $input    = input();
        $validate = Loader::validate('comment');

        if (!$validate) {
            $this->error($validate->getError());
            die();
        }

        $comment = new M();
        if (isset($input['id'])) {
            $id = $input['id'];
            unset($input['id']);
            $result = $comment->update($input, ['id' => $id]);
        } else {
            $result = $comment->create($input);
        }
        if ($result) {
            $this->success();
        }
        $this->error();
    }

    public function getCommentList()
    {
        $uid = input('uid', 0);
        $aid = input('aid', 0);
        $comment = new M();
        $list = $comment->field('id, uid, pid, content, status')->select();
        //print_r(json_decode(json_encode($list), true));exit();
        $list = TreeService::generateTree(json_decode(json_encode($list), true));
        dump($list);exit();
    }

}
