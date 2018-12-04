<?php
namespace tf\index\validate;

/**
 * Class Action 方法校验器数据验证器
 * @package tf\index\validate
 */
class Action extends \think\Validate
{
    protected $rule = [
		'name' => ['require','alpha'],
		'caption' => ['require','regex' => '/^[\x{4e00}-\x{9fa5}\x{fe30}-\x{ffa0}a-zA-Z0-9\_\-]+$/u'],
		'is_abstract' => ['require','regex' => '/(yes|on|1|0)/i'],
		'is_static' => ['require','regex' => '/(yes|on|1|0)/i']
	];
    protected $field = [
		'name' => '名称',
		'caption' => '说明',
		'is_abstract' => '是否是抽象的',
		'is_static' => '是否是静态的'
	];
    protected $message = [
		'name' => '必须输入|英文字符',
		'caption' => '必须输入|中文、中文标点或英文字符包括横线下划线',
		'is_abstract' => '必须输入|是/否',
		'is_static' => '必须输入|是/否'
	];
}