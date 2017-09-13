<?php

namespace app\common\helper;

use think\Exception;
use think\Request;

/**
 * Class File 跟文件相关的工具类
 * @package app\common\helper
 */
final class FileHelper
{
    /**
     * 获取文件上传路径
     * @return string
     */
    public static function getTargetPath()
    {
        return ROOT_PATH . '../public' . DS . 'uploads';
    }

    /**
     * 文件上传功能
     * @param \think\File $file
     * @param string $type
     * @param string $extension
     * @return array
     * @throws Exception
     */
    public static function upload(\think\File $file, $type = 'editor', $extension = 'jpg,jpeg,png,gif,bmp')
    {
        // 移动到框架应用根目录/public/uploads/ 目录下
        $target_path = FileHelper::getTargetPath();
        $res = [];

        //判断从 $type 传递过来的名称是否可以作为合法的文件名
        if($type === '.' || $type === '..') throw new Exception('错误的上传目录命名！');

        if ($file) {
            $info = $file->rule(function () use ($type) {
                return $type . DS . date('Ym') . DS . date('d') . DS . md5(microtime(true));
            })
                ->validate(['ext' =>$extension])
                ->move($target_path);
            if ($info) {
                // 成功上传后 获取上传信息
                $file_path = str_replace(ROOT_PATH, '/', $info->getPathname());
                $file_path = str_replace('../public/', '', $file_path);
                $res['file_path'] = $file_path;
                $res['info'] = $info;
            } else {
                // 上传失败获取错误信息
                $res['error'] = $file->getError();
            }
        }

        return $res;
    }

    /**
     * 图片上传的方法
     * fixme 当前的方法只返回成功的文件名，考虑到控制器需要继续处理，所以以后应该返回 $res
     * @param string $name 文件名
     * @param string $new_name 新的文件名
     * @param string $type 上传类型，默认是编辑器上传，还有附件上传，或者可以自定义
     * @return mixed|string
     * @throws Exception
     */
    public static function uploadImage($name, $new_name = "", $type = 'editor')
    {
        $image_url = "";
        //上传文件
        $req = Request::instance();
        if ($new_name === "") {
            if ($req->file($name)) {
                $res = FileHelper::upload($req->file($name), $type);
                if (isset($res['error'])) {
                    throw new Exception($res['error']);
                } else {
                    $image_url = $res['file_path'];
                }
            }
        } else {
            if ($req->file($new_name)) {
                $res = FileHelper::upload($req->file($new_name), $type);
                if (isset($res['error'])) {
                    throw new Exception($res['error']);
                } else {
                    $image_url = $res['file_path'];
                }
            } else {
                $image_url = input($name);
            }
        }

        if (!$image_url) $image_url = '';
        return $image_url;
    }

    /**
     * 其他文件的上传方法
     * @param $name
     * @param string $new_name
     * @param string $type
     * @return array
     * @throws Exception
     */
    public static function uploadFile($name, $new_name = "", $type = 'file')
    {
        //上传文件
        $extensions = 'flv,f4v,mp4,m3u8,webm,ogg,avi,mpg,mpeg,avi,doc,docx,pdf';
        $req = Request::instance();
        $res = [];
        if ($new_name === "") {
            if ($req->file($name)) {
                $res = FileHelper::upload($req->file($name), $type, $extensions);
            }
        } else {
            if ($req->file($new_name)) {
                $res = FileHelper::upload($req->file($new_name), $type, $extensions);
            } else {
                $res['file_path'] = input($name);
            }
        }

        return $res;
    }
}