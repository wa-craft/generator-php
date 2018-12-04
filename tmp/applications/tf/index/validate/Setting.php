<?php
namespace tf\index\validate;

/**
 * Class Setting 设置校验器数据验证器
 * @package tf\index\validate
 */
class Setting extends \think\Validate
{
    protected $rule = [
		'keyword' => ['require','alpha'],
		'caption' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'value' => ['require','alpha']
	];
    protected $field = [
		'keyword' => '键',
		'caption' => '说明',
		'value' => '值'
	];
    protected $message = [
		'keyword' => '必须输入|英文字符',
		'caption' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'value' => '必须输入|英文字符'
	];
}