<?php
namespace tf\index\controller;

use think\Request;

/**
 * 空控制器
 */
class Error
{
    /**
     * index 入口
     */
    public function index(Request $request)
    {
        $view = new \think\View();
        return $view->fetch();
    }


}