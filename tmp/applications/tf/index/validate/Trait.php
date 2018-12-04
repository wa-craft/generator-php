<?php
namespace tf\index\validate;

/**
 * Class Trait 泛型校验器数据验证器
 * @package tf\index\validate
 */
class Trait extends \think\Validate
{
    protected $rule = [
		'name' => ['require','alpha'],
		'caption' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u']
	];
    protected $field = [
		'name' => '名称',
		'caption' => '说明'
	];
    protected $message = [
		'name' => '必须输入|英文字符',
		'caption' => '必须输入|中文、中文标点或英文字符包括横线下划线'
	];
}