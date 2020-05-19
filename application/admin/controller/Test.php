<?php

namespace app\admin\controller;

use think\Controller;

class Test extends Controller
{
    public function index()
    {
        $byte = input('byte', 100000);
        var_dump(formatBytes($byte));
    }

}
