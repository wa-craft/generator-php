<?php
namespace app\common\traits;

trait AjaxJump
{
    /**
     * 操作成功跳转的快捷方法(AJAX)
     * @access protected
     * @param mixed $msg 提示信息
     * @param string $url 跳转的URL地址
     * @param mixed $data 返回的数据
     * @param integer $wait 跳转等待时间
     * @param array $header 发送的Header信息
     * @return void
     */
    protected function ajaxSuccess($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        $url = $url ?? '';
        $this->success($msg, $url, $data, $wait, $header);
    }
    
    /**
     * 操作错误跳转的快捷方法(AJAX)
     * @access protected
     * @param mixed $msg 提示信息
     * @param string $url 跳转的URL地址
     * @param mixed $data 返回的数据
     * @param integer $wait 跳转等待时间
     * @param array $header 发送的Header信息
     * @return void
     */
    protected function ajaxError($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        $url = $url ?? '';
        $this->error($msg, $url, $data, $wait, $header);
    }
    
}