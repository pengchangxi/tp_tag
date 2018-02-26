<?php

namespace app\admin\taglib;

use think\Request;
use think\template\TagLib;

class Custom extends TagLib{

    protected $tags = [

        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'handle'   => ['close' => 0],
    ];

    //列表页操作栏
    public function tagHandle($tag){
        $handle = isset($tag['menu']) ?
            (is_array($tag['menu']) ? $tag['menu'] : explode(',', $tag['menu'])) :
            ['add', 'delete'];
        $titleArr = isset($tag['title']) ?
            (is_array($tag['title']) ? $tag['title'] : explode(',', $tag['title'])) :
            [];
        $urlArr = isset($tag['url']) ?
            (is_array($tag['url']) ? $tag['url'] : explode(',', $tag['url'])) :
            [];
        $parseStr = '';
        //////////////////获取当前列表模块和控制器//////////////////////////////////
        $request = Request::instance();
        $module = $request->module();//模块
        $controller = $request->controller();//控制器
        $controller = strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $controller));//将AbcDef转化为abc_def
        ///////////////////////////////////////////////////////////////////////////
        foreach ($handle as $k => $m) {
            $m = strtolower($m);
            $url = isset($urlArr[$k]) && $urlArr[$k] ? $urlArr[$k] : (substr($m, 0, 1) == 's' ? substr($m, 1) : $m);
            $urls = explode(":", $url);
            $ver_url = '/'.$module.'/'.$controller.'/'.$urls[0];
            $parseStr .= "<?php if (\Rbac\Rbac::AccessCheck('".session('adminInfo')['role_id']."','" . $ver_url . "')) : ?>";
            switch ($m) {
                case 'add':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '添加';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a class="btn btn-primary radius mr-5" href="javascript:;" onclick="layer_open(\'' . $title . '\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')"><i class="Hui-iconfont">&#xe600;</i> ' . $title . '</a>';
                    break;
                case 'evaluate':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '版本更新';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a class="btn btn-secondary radius mr-5" href="javascript:;" onclick="assignment()"><i class="Hui-iconfont">&#xe6df;</i> ' . $title . '</a>';
                    break;
                case 'delete':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '批量删除';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= '<a href="javascript:;" onclick="del_all(\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="btn btn-danger radius mr-5"><i class="Hui-iconfont">&#xe6e2;</i> ' . $title . '</a>';
                    break;
                case 'sadd':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '添加子节点';
                    list($url, $param) = $this->parseUrl($url, 'pid=$vo["id"]');
                    $parseStr .= ' <a title="' . $title . '" href="javascript:;" onclick="layer_open(\'' . $title . '\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" style="text-decoration:none" class="ml-5"><i class="Hui-iconfont">&#xe6df;</i></a>';
                    break;
                case 'authorize':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '权限设置';
                    list($url, $param) = $this->parseUrl($url, 'pid=$vo["id"]');
                    $parseStr .= ' <a title="' . $title . '" href="javascript:;" onclick="layer_open(\'' . $title . '\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" style="text-decoration:none" class="ml-5"><i class="Hui-iconfont">&#xe63f;</i></a>';
                    break;
                case 'edit':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '编辑';
                    list($url, $param) = $this->parseUrl($url, 'id=$vo["id"]');
                    $parseStr .= ' <a title="' . $title . '" href="javascript:;" onclick="layer_open(\'' . $title . '\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" style="text-decoration:none" class="ml-5"><i class="Hui-iconfont">&#xe6df;</i></a>';
                    break;
                case 'del':
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '删除';
                    list($url, $param) = $this->parseUrl($url);
                    $parseStr .= ' <a title="' . $title . '" href="javascript:;" onclick="del(this,\'{$vo.id}\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>';
                    break;
                default:
                    // 默认为小菜单
                    $title = isset($titleArr[$k]) && $titleArr[$k] ? $titleArr[$k] : '菜单';
                    list($url, $param) = $this->parseUrl($url);
                    $class = isset($tag['class']) ? $tag['class'] : 'label-primary';
                    $parseStr .= ' <a title="' . $title . '" href="javascript:;" onclick="layer_open(\'' . $title . '\',\'<?php echo \think\Url::build(\'' . $url . '\', [' . $param . ']); ?>\')" class="label radius ml-5 ' . $class . '">' . $title . '</a>';
            }
            $parseStr .= "<?php endif; ?>";
        }
        return $parseStr;
    }

    /**
     * 将参数格式化成url和可用参数
     * @param $url
     * @param string $default
     * @return array
     */
    private function parseUrl($url, $default = ''){
        $urls = explode(":", $url, 2);
        $params = explode("&", count($urls) == 1 ? $default : $urls[1]);
        $ret = '';
        foreach ($params as $param) {
            if ($param) {
                list($key, $value) = explode("=", $param);
                $this->parseVar($value);
                $ret .= "'{$key}' => {$value}, ";
            }
        }

        return [$urls[0], $ret];
    }

    /**
     * 解析变量
     * @param $value
     */
    private function parseVar(&$value){
        $flag = substr($value, 0, 1);
        switch ($flag) {
            case '$':
                if (false !== $pos = strpos($value, '?')) {
                    $array = preg_split('/([!=]={1,2}|(?<!-)[><]={0,1})/', substr($value, 0, $pos), 2, PREG_SPLIT_DELIM_CAPTURE);
                    $name = $array[0];
                    $this->tpl->parseVar($name);
                    $this->tpl->parseVarFunction($name);

                    $value = trim(substr($value, $pos + 1));
                    $this->tpl->parseVar($value);
                    $first = substr($value, 0, 1);
                    if (strpos($name, ')')) {
                        // $name为对象或是自动识别，或者含有函数
                        if (isset($array[1])) {
                            $this->tpl->parseVar($array[2]);
                            $name .= $array[1] . $array[2];
                        }
                        switch ($first) {
                            case '?':
                                $value = '(' . $name . ') ? ' . $name . ' : ' . substr($value, 1);
                                break;
                            default:
                                $value = $name . '?' . $value;
                        }
                    } else {
                        if (isset($array[1])) {
                            $this->tpl->parseVar($array[2]);
                            $_name = ' && ' . $name . $array[1] . $array[2];
                        } else {
                            $_name = '';
                        }
                        // $name为数组
                        switch ($first) {
                            case '?':
                                // {$varname??'xxx'} $varname有定义则输出$varname,否则输出xxx
                                $value = 'isset(' . $name . ')' . $_name . ' ? ' . $name . ' : ' . substr($value, 1);
                                break;
                            case ':':
                                // {$varname?:'xxx'} $varname为真时输出$varname,否则输出xxx
                                $value = '!empty(' . $name . ')' . $_name . '?' . $name . $value;
                                break;
                            default:
                                if (strpos($value, ':')) {
                                    // {$varname ? 'a' : 'b'} $varname为真时输出a,否则输出b
                                    $value = '!empty(' . $name . ')' . $_name . '?' . $value;
                                } else {
                                    $value = $_name . '?' . $value;
                                }
                        }
                    }
                } else {
                    $this->tpl->parseVar($value);
                    $this->tpl->parseVarFunction($value);
                }
                break;
            case ':':
                // 输出某个函数的结果
                $value = substr($value, 1);
                $this->tpl->parseVar($value);
                break;
            case '~':
                // 执行某个函数
                $value = substr($value, 1);
                $this->tpl->parseVar($value);
                break;
            case '-':
            case '+':
                // 输出计算
                $this->tpl->parseVar($value);
                break;
            default:
                // 未识别的标签直接返回
                $value = "'{$value}'";
                break;
        }
    }
}