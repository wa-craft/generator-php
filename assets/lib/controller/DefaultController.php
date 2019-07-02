<?php

namespace app\common\controller;

use app\common\helper\Auth;
use app\common\model\Account;
use app\common\traits\AjaxJump;
use think\Loader;
use think\Request;

/**
 * CRUD
 */
abstract class DefaultController extends \think\Controller
{
    use AjaxJump;

    protected $module = null;
    protected $controller = null;
    protected $action = null;
    protected $template = null;

    /**
     * DefaultController constructor. 默认构造函数
     */
    public function __construct()
    {
        parent::__construct();
        //预处理请求
        $this->prepareRequest();

        if ($_SERVER['HTTP_HOST'] !== 'mysun.vm') {
            //user rights check
            if (Auth::authorize()) {
                Auth::refreshStat(0.25);
                $user = User::get(['id' => session('user_id', '', $this->module)]);
                $this->assign('user', $user);
            } else {
                $this->redirect('admin/anon/logout');
                exit();
            }
        } else {
            $user = User::get(['id' => '1']);
            $this->assign('user', $user);
            Auth::checkin($user);
        }
    }

    /**
     * 预处理请求，设置当前的模块/控制器/操作等参数
     */
    protected function prepareRequest()
    {
        $req = Request::instance();
        $this->module = $req->module();
        $this->controller = $req->controller();
        $this->action = $req->action();
    }

    /**
     * 通用的模板调用并渲染方法
     * @return mixed
     */
    protected function render()
    {
        $template = $this->template ?: $this->action;
        return $this->fetch($template);
    }

    /**
     * 显示资源列表
     * @return \think\Response
     */
    public function index()
    {
        $where = input('where') ?? '1';
        $model = Loader::model($this->controller);
        if (!isset($model)) {
            $list = [];
        } else {
            if ($where == '1') {
                $list = $model->order('id', 'desc')->paginate(config('paginate.list_rows'));
            } else {
                $list = $model->where($where)->order('id', 'desc')->paginate(config('paginate.list_rows'));
            }
        }

        $this->assign('voList', $list);
        return $this->render();
    }

    /**
     * 标准CRUD，增加数据方法
     * @return \think\Response
     */
    public function add()
    {
        //检测字段并自动生成关联数据的列表
        $model = Loader::model($this->controller);
        if (isset($model)) {
            $fields = $model->db()->getTableInfo('', 'fields');
            foreach ($fields as $field) {
                if (preg_match('/_id$/', $field)) {
                    $_model = str_replace('_id', '', $field);
                    if (class_exists('app\\' . $this->module . '\\model\\' . $_model)) {
                        $_m = Loader::model($_model, 'model', false, $this->module);
                    } else {
                        $_m = Loader::model($_model, 'model', false, 'common');
                    }
                    if (!isset($_m)) $_m = Loader::model($_model, 'model', false, 'common');
                    $list = $_m::all();
                    $this->assign($_model . 'List', $list);
                }
            }
            return $this->render();
        } else {
            $this->ajaxError('获取数据错误');
        }
    }

    /**
     * 标准CRUD，修改数据方法
     * @param $id
     * @return \think\Response
     */
    public function mod($id = 0)
    {
        if ($id != 0) {
            $model = Loader::model($this->controller);
            if (isset($model)) {
                $obj = $model::get($id);
                $this->assign('it', $obj);

                //检测字段并自动生成关联数据的列表
                $model = Loader::model($this->controller);
                $fields = $model->db()->getTableInfo('', 'fields');
                foreach ($fields as $field) {
                    if (preg_match('/_id$/', $field)) {
                        $_model = str_replace('_id', '', $field);
                        //$_m = Loader::model($_model, 'model', false, strtolower($this->module));
                        if (class_exists('app\\' . $this->module . '\\model\\' . $_model)) {
                            $_m = Loader::model($_model, 'model', false, $this->module);
                        } else {
                            $_m = Loader::model($_model, 'model', false, 'common');
                        }
                        if (!isset($_m)) Loader::model($_model, 'model', false, 'common');
                        $list = $_m::all();
                        $this->assign($_model . 'List', $list);
                    }
                }
                return $this->render();
            } else {
                $this->ajaxError('获取数据错误');
            }
        } else {
            $this->ajaxError('获取参数错误');
        }
    }

    /**
     * 标准CRUD，查看数据的方法
     * @param $id
     * @return \think\Response
     */
    public function view($id = 0)
    {
        if ($id != 0) {
            $model = Loader::model($this->controller);
            if (isset($model)) {
                $obj = $model::get($id);
                $this->assign('it', $obj);
                return $this->render();
            } else {
                $this->ajaxError('获取数据错误');
            }
        } else {
            $this->ajaxError('获取参数错误');
        }
    }

    /**
     * 标准CRUD，删除数据方法
     * @param $id
     * @return \think\Response
     */
    public function del($id = 0)
    {
        if ($id != 0) {
            $model = Loader::model($this->controller);
            $model::destroy($id);
            $this->ajaxSuccess('删除成功', $this->module . '/' . $this->controller . '/index');
        }
    }

    /**
     * 显示创建资源表单页，必须由子控制器实现。
     * @return \think\Response
     */
    abstract public function create();

    /**
     * 保存新建的资源，必须由子控制器实现。
     * @return \think\Response
     */
    abstract public function save();
}
