<?php

namespace app\admin\controller;

use app\admin\model\Avatar as M;

use cropper\Crop;

class Avatar extends Base
{
    public function index()
    {
        $avatar = new M();
        $list   = $avatar->index();
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        $this->assign('count', $list->total());
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {
            // todo
        }
        return $this->fetch('edit');
    }

    public function test()
    {
        $crop = new Crop($_POST['avatar_src'], $_POST['avatar_data'], $_FILES['avatar_file']);

        $response = array(
            'state'   => 200,
            'message' => $crop->getMsg(),
            'result'  => $crop->getResult()
        );

        echo json_encode($response);
    }

}
