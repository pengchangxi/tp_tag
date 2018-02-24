<?php

namespace app\admin\model;

class Channel extends Base{

    /**
     * 根据aid查找渠道
     * @param $aid
     * @return mixed
     */
    public function getChannelByAid($aid){
        $channel = $this->where('aid',$aid)->column('channel');
        return $channel;
    }


}