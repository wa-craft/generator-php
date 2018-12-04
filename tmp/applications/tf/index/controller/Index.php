<?php
namespace tf\index\controller;

use think\Loader;
use tf\common\helper\{File,Form};

/**
 * 默认控制器
 */
class Index extends \goldeagle\thinklib\controller\DefaultController
{
    /**
     * index 默认方法
     */
    public function index()
    {
        $view = new \think\View();
        return $view->fetch();
    }


    /**
     * 显示创建资源表单页，必须由子控制器实现。
     * @return \think\Response
     */
    public function create() {
        $model = Loader::model($this->controller);
        if (isset($model)) {
            $preset_data = [];



            $result = Form::validateAndSaveData(
                validate($this->controller),
                [],
                $model,
                $preset_data
            );
            if ($result === true) {
                $this->ajaxSuccess('操作成功', $this->module. '/' . $this->controller . '/index');
            } else {
                $this->ajaxError($result, $this->module. '/' . $this->controller . '/index');
            }
        } else {
            $this->ajaxError('系统错误，找不到对应的数据模型', $this->module. '/' . $this->controller . '/index');
        }
    }

    /**
     * 保存新建的资源，必须由子控制器实现。
     * @return \think\Response
     */
    public function save() {
		$_m = Loader::model($this->controller);
        $model = $_m::get(input('id'));
        if (isset($model)) {
            $preset_data = [];



            $result = Form::validateAndSaveData(
                validate($this->controller),
                [],
                $model,
                $preset_data
            );
            if ($result === true) {
                $this->ajaxSuccess('操作成功', $this->module. '/' . $this->controller . '/index');
            } else {
                $this->ajaxError($result, $this->module. '/' . $this->controller . '/index');
            }
        } else {
            $this->ajaxError('数据保存错误！');
        }
    }
}
