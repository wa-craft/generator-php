<?php
namespace tf\index\validate;

/**
 * Class Model 模型校验器数据验证器
 * @package tf\index\validate
 */
class Model extends \think\Validate
{
    protected $rule = [
		'name' => ['require','alpha'],
		'caption' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'autoWriteTimeStamp' => ['require','alphaDash']
	];
    protected $field = [
		'name' => '名称',
		'caption' => '说明',
		'autoWriteTimeStamp' => '是否自动创建 create_time、update_time 模型属性'
	];
    protected $message = [
		'name' => '必须输入|英文字符',
		'caption' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'autoWriteTimeStamp' => '必须输入|英文字符与下划线'
	];
}